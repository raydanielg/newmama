<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\JournalLine;
use Illuminate\Http\Request;

class BanksController extends Controller
{
    public function index(Request $request)
    {
        $bankCodes = ['1010', '1020', '1021', '1022', '1030', '1031', '1040'];

        $accounts = Account::query()
            ->whereIn('code', $bankCodes)
            ->where('is_active', true)
            ->orderBy('code')
            ->get();

        $selectedCode = (string) $request->query('code', '');
        if ($selectedCode === '' && $accounts->count() > 0) {
            $selectedCode = (string) $accounts->first()->code;
        }

        $selectedAccount = $accounts->firstWhere('code', $selectedCode);

        $from = $request->query('from') ?: now()->startOfMonth()->toDateString();
        $to = $request->query('to') ?: now()->toDateString();

        $lines = collect();
        $summary = [
            'in' => 0.0,
            'out' => 0.0,
            'net' => 0.0,
        ];

        if ($selectedAccount) {
            $lines = JournalLine::query()
                ->with(['journal'])
                ->where('account_id', $selectedAccount->id)
                ->whereHas('journal', function ($q) use ($from, $to) {
                    $q->where('status', 'posted')
                        ->whereDate('posting_date', '>=', $from)
                        ->whereDate('posting_date', '<=', $to);
                })
                ->orderByDesc('id')
                ->get();

            $summary['in'] = (float) $lines->sum('debit');
            $summary['out'] = (float) $lines->sum('credit');
            $summary['net'] = $summary['in'] - $summary['out'];
        }

        return view('admin.banks.index', [
            'title' => 'Banks',
            'accounts' => $accounts,
            'selectedAccount' => $selectedAccount,
            'lines' => $lines,
            'from' => $from,
            'to' => $to,
            'summary' => $summary,
        ]);
    }
}
