<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SalesModuleController extends Controller
{
    public function invoices(Request $request)
    {
        $query = Voucher::query()->where('type', 'sales_invoice');

        if ($from = $request->query('from')) {
            $query->whereDate('posting_date', '>=', $from);
        }

        if ($to = $request->query('to')) {
            $query->whereDate('posting_date', '<=', $to);
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('ref', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $vouchers = $query->orderByDesc('posting_date')->orderByDesc('id')->paginate(20)->withQueryString();

        return view('admin.sales.invoices', [
            'title' => 'Sales Invoices',
            'vouchers' => $vouchers,
        ]);
    }

    public function payments(Request $request)
    {
        $query = Voucher::query()->where('type', 'cash_receipt');

        if ($from = $request->query('from')) {
            $query->whereDate('posting_date', '>=', $from);
        }

        if ($to = $request->query('to')) {
            $query->whereDate('posting_date', '<=', $to);
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('ref', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('payment_method', 'like', "%{$search}%");
            });
        }

        $vouchers = $query->orderByDesc('posting_date')->orderByDesc('id')->paginate(20)->withQueryString();

        return view('admin.sales.payments', [
            'title' => 'Payments',
            'vouchers' => $vouchers,
        ]);
    }

    public function paymentsCsv(Request $request): StreamedResponse
    {
        $query = Voucher::query()->where('type', 'cash_receipt')->orderByDesc('posting_date')->orderByDesc('id');

        if ($from = $request->query('from')) {
            $query->whereDate('posting_date', '>=', $from);
        }

        if ($to = $request->query('to')) {
            $query->whereDate('posting_date', '<=', $to);
        }

        $filename = 'payments_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['posting_date', 'ref', 'description', 'payment_method', 'total_amount', 'customer_id']);

            $query->chunk(500, function ($rows) use ($out) {
                foreach ($rows as $v) {
                    fputcsv($out, [
                        optional($v->posting_date)->toDateString(),
                        $v->ref,
                        $v->description,
                        $v->payment_method,
                        (float) $v->total_amount,
                        $v->customer_id,
                    ]);
                }
            });

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
