<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mother;
use Illuminate\Http\Request;

class MotherIntakeController extends Controller
{
    public function approved(Request $request)
    {
        $query = Mother::query()
            ->where('is_approved', true)
            ->with(['country', 'region', 'district']);

        if ($status = $request->query('status')) {
            if (in_array($status, ['pregnant', 'new_parent', 'trying'], true)) {
                $query->where('status', $status);
            }
        }

        if ($since = $request->query('since')) {
            $query->where('updated_at', '>=', $since);
        }

        $perPage = $request->query('per_page');
        if ($perPage !== null) {
            $perPageInt = max(1, min(500, (int) $perPage));
            $mothers = $query->orderBy('id')->paginate($perPageInt);
            return response()->json($this->formatPaginated($mothers));
        }

        $mothers = $query->orderBy('id')->get();

        return response()->json([
            'success' => true,
            'count' => $mothers->count(),
            'data' => $mothers->map(fn (Mother $m) => $this->formatMother($m))->values(),
        ]);
    }

    private function formatPaginated($paginator): array
    {
        return [
            'success' => true,
            'count' => $paginator->count(),
            'total' => $paginator->total(),
            'per_page' => $paginator->perPage(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'next_page_url' => $paginator->nextPageUrl(),
            'prev_page_url' => $paginator->previousPageUrl(),
            'data' => collect($paginator->items())->map(fn (Mother $m) => $this->formatMother($m))->values(),
        ];
    }

    private function formatMother(Mother $m): array
    {
        return [
            'id' => $m->id,
            'mk_number' => $m->mk_number,
            'full_name' => $m->full_name,
            'whatsapp_number' => $m->whatsapp_number,
            'status' => $m->status,
            'edd_date' => optional($m->edd_date)->format('Y-m-d'),
            'baby_age' => $m->baby_age,
            'trying_duration' => $m->trying_duration,
            'is_approved' => (bool) $m->is_approved,
            'approved_at' => optional($m->approved_at)->toISOString(),
            'country' => $m->country ? [
                'id' => $m->country->id,
                'name' => $m->country->name,
                'iso2' => $m->country->iso2,
                'phone_code' => $m->country->phone_code,
            ] : null,
            'region' => $m->region ? [
                'id' => $m->region->id,
                'name' => $m->region->name,
            ] : null,
            'district' => $m->district ? [
                'id' => $m->district->id,
                'name' => $m->district->name,
            ] : null,
            'current_step' => $m->current_step,
            'created_at' => optional($m->created_at)->toISOString(),
            'updated_at' => optional($m->updated_at)->toISOString(),
            'metadata' => $m->metadata,
        ];
    }
}
