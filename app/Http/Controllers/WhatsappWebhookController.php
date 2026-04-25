<?php

namespace App\Http\Controllers;

use App\Models\Mother;
use App\Models\SystemSetting;
use App\Models\WhatsappMessage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WhatsappWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $secret = (string) SystemSetting::query()->where('key', 'whatsapp.webhook_secret')->value('value');

        if ($secret !== '') {
            $provided = (string) ($request->header('X-Webhook-Secret') ?: $request->query('secret') ?: '');
            if (!hash_equals($secret, $provided)) {
                return response()->json(['ok' => false, 'error' => 'unauthorized'], 401);
            }
        }

        $payload = $request->all();

        // Generic normalized format (recommended):
        // {
        //   "from": "+2557...", "to": "+255...",
        //   "direction": "in"|"out",
        //   "type": "text",
        //   "body": "...",
        //   "message_id": "provider-id",
        //   "sent_at": "2026-01-01T12:00:00Z"
        // }
        $from = $this->normalizePhone((string) ($payload['from'] ?? $payload['wa_from'] ?? $payload['sender'] ?? ''));
        $to = $this->normalizePhone((string) ($payload['to'] ?? $payload['wa_to'] ?? ''));
        $direction = (string) ($payload['direction'] ?? 'in');
        $direction = in_array($direction, ['in', 'out'], true) ? $direction : 'in';

        $type = (string) ($payload['type'] ?? 'text');
        $body = (string) ($payload['body'] ?? $payload['text'] ?? $payload['message'] ?? '');
        $waMessageId = (string) ($payload['message_id'] ?? $payload['wa_message_id'] ?? $payload['id'] ?? '');

        $sentAt = $payload['sent_at'] ?? $payload['timestamp'] ?? null;

        // Try to find the mother by matching the "from" number first, then "to" for outgoing.
        $candidate = $direction === 'in' ? $from : $to;
        $mother = $this->findMotherByPhone($candidate);

        if (!$mother) {
            return response()->json([
                'ok' => false,
                'error' => 'mother_not_found',
            ], 404);
        }

        // Avoid duplicates if provider sends retries.
        if ($waMessageId !== '' && WhatsappMessage::query()->where('wa_message_id', $waMessageId)->exists()) {
            return response()->json(['ok' => true, 'deduped' => true]);
        }

        $msg = WhatsappMessage::create([
            'mother_id' => $mother->id,
            'direction' => $direction,
            'type' => $type,
            'body' => $body,
            'wa_message_id' => $waMessageId !== '' ? $waMessageId : null,
            'sent_at' => $this->parseDateTime($sentAt),
            'meta' => [
                'from' => $from,
                'to' => $to,
                'raw' => $payload,
            ],
        ]);

        return response()->json([
            'ok' => true,
            'message_id' => $msg->id,
        ]);
    }

    private function normalizePhone(string $value): string
    {
        $value = trim($value);
        if ($value === '') {
            return '';
        }

        // keep + and digits
        $value = preg_replace('/[^0-9+]/', '', $value);
        if ($value === null) {
            return '';
        }

        // If starts with 0, keep as-is (local format), but it will be matched by suffix search below.
        return $value;
    }

    private function findMotherByPhone(string $phone): ?Mother
    {
        $phone = $this->normalizePhone($phone);
        if ($phone === '') {
            return null;
        }

        // Exact match first
        $m = Mother::query()->where('whatsapp_number', $phone)->first();
        if ($m) return $m;

        // Try suffix match (handles "7XXXXXXXX" stored without country code, or incoming with +255...)
        $digits = preg_replace('/\D+/', '', $phone);
        if (!$digits) {
            return null;
        }

        $suffix = substr($digits, -9);
        if ($suffix && strlen($suffix) >= 9) {
            return Mother::query()->where('whatsapp_number', 'like', "%{$suffix}")->first();
        }

        return null;
    }

    private function parseDateTime($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        try {
            return now()->parse($value)->toDateTimeString();
        } catch (\Throwable) {
            return null;
        }
    }
}
