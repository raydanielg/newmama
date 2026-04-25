<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WhatsappMessage;
use App\Models\Mother;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsappWebhookController extends Controller
{
    /**
     * 🔐 Verify webhook signature/API key
     */
    private function verifyWebhook(Request $request): bool
    {
        $apiKey = $request->header('X-API-Key');
        return $apiKey === config('services.whatsapp.api_key');
    }

    /**
     * 📩 Handle incoming WhatsApp status updates from Node.js bot
     */
    public function handleStatus(Request $request)
    {
        // Verify webhook
        if (!$this->verifyWebhook($request)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $data = $request->all();
            
            Log::info('WhatsApp status webhook received', $data);

            // Find message by external ID or create log entry
            if (isset($data['messageId'])) {
                $message = WhatsappMessage::where('external_id', $data['messageId'])
                    ->orWhere('wa_message_id', $data['messageId'])
                    ->first();

                if ($message) {
                    switch ($data['status']) {
                        case 'sent':
                            $message->update([
                                'status' => 'sent',
                                'sent_at' => $data['timestamp'] ?? now(),
                            ]);
                            break;

                        case 'delivered':
                            $message->update([
                                'status' => 'delivered',
                                'delivered_at' => $data['timestamp'] ?? now(),
                            ]);
                            break;

                        case 'read':
                            $message->update([
                                'status' => 'read',
                                'read_at' => $data['timestamp'] ?? now(),
                            ]);
                            break;

                        case 'failed':
                            $message->update([
                                'status' => 'failed',
                                'response_data' => array_merge(
                                    $message->response_data ?? [],
                                    ['error' => $data['error'] ?? 'Unknown error']
                                ),
                            ]);
                            break;
                    }
                }
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('WhatsApp webhook error', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * 📨 Handle incoming messages from WhatsApp
     */
    public function handleIncoming(Request $request)
    {
        // Verify webhook
        if (!$this->verifyWebhook($request)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $data = $request->all();
            
            Log::info('WhatsApp incoming message received', [
                'from' => $data['from'] ?? 'unknown',
                'body' => substr($data['body'] ?? '', 0, 100),
            ]);

            $fromNumber = $this->formatNumber($data['from'] ?? '');
            
            // Find mother by WhatsApp number
            $mother = Mother::where('whatsapp_number', $fromNumber)
                ->orWhere('whatsapp_number', str_replace('+', '', $fromNumber))
                ->first();

            // Store incoming message
            WhatsappMessage::create([
                'mother_id' => $mother?->id,
                'direction' => 'incoming',
                'to_number' => $fromNumber,
                'message' => $data['body'] ?? '',
                'wa_message_id' => $data['messageId'] ?? null,
                'status' => 'received',
                'type' => $this->detectMessageType($data['body'] ?? ''),
                'sent_at' => now(),
                'metadata' => [
                    'from_name' => $data['fromName'] ?? null,
                    'raw_data' => $data,
                ],
            ]);

            // Auto-reply for common queries
            if ($mother) {
                $this->handleAutoReply($mother, $data['body'] ?? '');
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('WhatsApp incoming webhook error', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * 🤖 Handle auto-replies for common queries
     */
    private function handleAutoReply(Mother $mother, string $message): void
    {
        $message = strtolower(trim($message));
        
        $replies = [
            'mk' => "🆔 *MK Number yako:* {$mother->mk_number}\n\n🔗 Ingia: " . config('app.url') . "/mother/login",
            'login' => "🔗 *Ingia kwenye dashboard:*\n" . config('app.url') . "/mother/login\n\nTumia MK Number yako: {$mother->mk_number}",
            'dashboard' => "🔗 *Dashboard yako:*\n" . config('app.url') . "/mother/dashboard",
            'appointment' => "📅 *Appointments zako:*\n" . config('app.url') . "/mother/appointments",
            'health' => "📊 *Ufuatiliaji wa afya:*\n" . config('app.url') . "/mother/health-data",
            'help' => "🤰 *MamaCare Commands:*\n• 'mk' - Angalia MK Number\n• 'login' - Link ya kuingia\n• 'dashboard' - Dashboard yako\n• 'appointment' - Appointments\n• 'health' - Health data\n• 'emergency' - Msaada wa dharura",
            'emergency' => "🚨 *NAMBA ZA DHARURA*\n• Huduma ya Dharura: 114\n• Polisi: 112\n• Moto: 114\n\n🔗 *Zaidi:* " . config('app.url') . "/mother/emergency",
            'hi' => "👋 *Habari {$mother->first_name}!*\n\nNiko hapa kukusaidia.\n\nAndika 'help' kuona nini ninaweza kufanya.",
            'habari' => "👋 *Habari {$mother->first_name}!*\n\nNiko hapa kukusaidia.\n\nAndika 'help' kuona nini ninaweza kufanya.",
        ];

        foreach ($replies as $keyword => $reply) {
            if (str_contains($message, $keyword)) {
                // Send reply via WhatsApp service
                $whatsAppService = app(\App\Services\WhatsAppService::class);
                $whatsAppService->sendMessage(
                    $mother->whatsapp_number,
                    $reply,
                    'auto_reply',
                    ['mother_id' => $mother->id, 'trigger' => $keyword]
                );
                break;
            }
        }
    }

    /**
     * 🏷️ Detect message type from content
     */
    private function detectMessageType(string $body): string
    {
        $body = strtolower($body);
        
        if (str_contains($body, 'mk')) return 'mk_query';
        if (str_contains($body, 'login') || str_contains($body, 'ingia')) return 'login_query';
        if (str_contains($body, 'appointment') || str_contains($body, 'kliniki')) return 'appointment_query';
        if (str_contains($body, 'health') || str_contains($body, 'afya')) return 'health_query';
        if (str_contains($body, 'help') || str_contains($body, 'msaada')) return 'help_query';
        if (str_contains($body, 'emergency') || str_contains($body, 'dharura')) return 'emergency_query';
        
        return 'general';
    }

    /**
     * 📱 Format phone number
     */
    private function formatNumber(string $number): string
    {
        // Remove @c.us suffix if present
        $number = str_replace('@c.us', '', $number);
        
        // Add + if not present
        if (!str_starts_with($number, '+')) {
            $number = '+' . $number;
        }
        
        return $number;
    }

    /**
     * 📊 Get webhook stats (for admin dashboard)
     */
    public function stats()
    {
        $stats = [
            'total_messages' => WhatsappMessage::count(),
            'sent_today' => WhatsappMessage::where('direction', 'outgoing')
                ->whereDate('sent_at', today())
                ->count(),
            'received_today' => WhatsappMessage::where('direction', 'incoming')
                ->whereDate('sent_at', today())
                ->count(),
            'failed_messages' => WhatsappMessage::where('status', 'failed')
                ->whereDate('created_at', today())
                ->count(),
            'pending_messages' => WhatsappMessage::where('status', 'pending')->count(),
            'delivery_rate' => $this->calculateDeliveryRate(),
        ];

        return response()->json($stats);
    }

    /**
     * 📈 Calculate delivery rate
     */
    private function calculateDeliveryRate(): float
    {
        $total = WhatsappMessage::where('direction', 'outgoing')
            ->whereDate('sent_at', today())
            ->count();
        
        if ($total === 0) return 0;

        $delivered = WhatsappMessage::where('direction', 'outgoing')
            ->whereIn('status', ['delivered', 'read'])
            ->whereDate('sent_at', today())
            ->count();

        return round(($delivered / $total) * 100, 2);
    }
}
