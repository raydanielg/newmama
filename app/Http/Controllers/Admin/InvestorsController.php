<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Investor;
use App\Models\InvestorTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InvestorsController extends Controller
{
    public function index(Request $request)
    {
        $query = Investor::query();

        if ($status = $request->query('status')) {
            if (in_array($status, ['active', 'inactive'], true)) {
                $query->where('status', $status);
            }
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('investor_number', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('id_number', 'like', "%{$search}%");
            });
        }

        $investors = $query->orderBy('name')->paginate(20)->withQueryString();

        $kpis = [
            'active_investors' => (int) Investor::query()->where('status', 'active')->count(),
            'total_balance' => (float) Investor::query()->sum('balance'),
            'mtd_inflows' => (float) InvestorTransaction::query()
                ->where('type', 'contribution')
                ->whereBetween('posting_date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
                ->sum('amount'),
            'mtd_outflows' => (float) InvestorTransaction::query()
                ->where('type', 'withdrawal')
                ->whereBetween('posting_date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
                ->sum('amount'),
        ];

        return view('admin.investors.index', [
            'title' => 'Investors Overview',
            'investors' => $investors,
            'kpis' => $kpis,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'id_number' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
            'notes' => ['nullable', 'string'],
        ]);

        $next = (int) Investor::query()->max('id') + 1;
        $invNo = 'INV-' . str_pad((string) $next, 5, '0', STR_PAD_LEFT);

        $inv = Investor::create(array_merge($data, [
            'investor_number' => $invNo,
            'balance' => 0,
        ]));

        return redirect()->route('admin.investors.show', $inv)->with('status', 'Investor created');
    }

    public function show(Investor $investor, Request $request)
    {
        $transactions = InvestorTransaction::query()
            ->where('investor_id', $investor->id)
            ->orderByDesc('posting_date')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        $kpis = [
            'balance' => (float) $investor->balance,
            'ytd_inflows' => (float) InvestorTransaction::query()
                ->where('investor_id', $investor->id)
                ->where('type', 'contribution')
                ->whereYear('posting_date', now()->year)
                ->sum('amount'),
            'ytd_outflows' => (float) InvestorTransaction::query()
                ->where('investor_id', $investor->id)
                ->where('type', 'withdrawal')
                ->whereYear('posting_date', now()->year)
                ->sum('amount'),
        ];

        return view('admin.investors.show', [
            'title' => 'Investor Profile',
            'investor' => $investor,
            'transactions' => $transactions,
            'kpis' => $kpis,
        ]);
    }

    public function edit(Investor $investor)
    {
        return view('admin.investors.edit', [
            'title' => 'Edit Investor',
            'investor' => $investor,
        ]);
    }

    public function update(Investor $investor, Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'id_number' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
            'notes' => ['nullable', 'string'],
        ]);

        $investor->update($data);

        return redirect()->route('admin.investors.show', $investor)->with('status', 'Investor updated');
    }

    public function toggleStatus(Investor $investor)
    {
        $new = $investor->status === 'active' ? 'inactive' : 'active';
        $investor->update(['status' => $new]);

        return redirect()->back()->with('status', 'Investor status updated');
    }

    public function transactionStore(Investor $investor, Request $request)
    {
        $data = $request->validate([
            'posting_date' => ['required', 'date'],
            'type' => ['required', 'in:contribution,withdrawal,dividend,adjustment'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'method' => ['nullable', 'string', 'max:50'],
            'reference' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($investor, $data) {
            InvestorTransaction::create([
                'investor_id' => $investor->id,
                'posting_date' => $data['posting_date'],
                'type' => $data['type'],
                'amount' => (float) $data['amount'],
                'method' => $data['method'] ?? null,
                'reference' => $data['reference'] ?? null,
                'description' => $data['description'] ?? null,
                'created_by' => optional(auth()->user())->id,
            ]);

            $delta = (float) $data['amount'];
            if ($data['type'] === 'withdrawal') {
                $delta = -abs($delta);
            }

            $investor->refresh();
            $investor->update(['balance' => (float) $investor->balance + $delta]);
        });

        return redirect()->route('admin.investors.show', $investor)->with('status', 'Transaction posted');
    }

    public function hub(Request $request)
    {
        $from = $request->query('from') ?: now()->startOfMonth()->toDateString();
        $to = $request->query('to') ?: now()->endOfMonth()->toDateString();

        $recent = InvestorTransaction::query()
            ->with('investor')
            ->whereBetween('posting_date', [$from, $to])
            ->orderByDesc('posting_date')
            ->orderByDesc('id')
            ->limit(25)
            ->get();

        $totals = [
            'inflows' => (float) InvestorTransaction::query()->whereBetween('posting_date', [$from, $to])->where('type', 'contribution')->sum('amount'),
            'outflows' => (float) InvestorTransaction::query()->whereBetween('posting_date', [$from, $to])->where('type', 'withdrawal')->sum('amount'),
            'dividends' => (float) InvestorTransaction::query()->whereBetween('posting_date', [$from, $to])->where('type', 'dividend')->sum('amount'),
        ];

        return view('admin.investors.hub', [
            'title' => 'Investors Hub',
            'from' => $from,
            'to' => $to,
            'recent' => $recent,
            'totals' => $totals,
        ]);
    }

    public function portfolio(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $investors = Investor::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($x) use ($q) {
                    $x->where('name', 'like', "%{$q}%")
                        ->orWhere('investor_number', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('balance')
            ->paginate(20)
            ->withQueryString();

        $total = (float) Investor::query()->sum('balance');

        return view('admin.investors.portfolio', [
            'title' => 'Investors Portfolio',
            'investors' => $investors,
            'total' => $total,
            'q' => $q,
        ]);
    }

    public function reports(Request $request)
    {
        $from = $request->query('from') ?: now()->startOfMonth()->toDateString();
        $to = $request->query('to') ?: now()->endOfMonth()->toDateString();
        $type = $request->query('type');
        $investorId = $request->query('investor_id');

        $query = InvestorTransaction::query()->with('investor')->whereBetween('posting_date', [$from, $to]);

        if ($type && in_array($type, ['contribution', 'withdrawal', 'dividend', 'adjustment'], true)) {
            $query->where('type', $type);
        }

        if ($investorId) {
            $query->where('investor_id', $investorId);
        }

        $rows = $query->orderByDesc('posting_date')->orderByDesc('id')->paginate(25)->withQueryString();

        $summary = [
            'total' => (float) (clone $query)->sum('amount'),
            'count' => (int) (clone $query)->count(),
        ];

        $investors = Investor::query()->orderBy('name')->get();

        return view('admin.investors.reports', [
            'title' => 'Investors Reports',
            'from' => $from,
            'to' => $to,
            'type' => $type,
            'investorId' => $investorId,
            'rows' => $rows,
            'summary' => $summary,
            'investors' => $investors,
        ]);
    }

    public function reportsCsv(Request $request): StreamedResponse
    {
        $from = $request->query('from') ?: now()->startOfMonth()->toDateString();
        $to = $request->query('to') ?: now()->endOfMonth()->toDateString();
        $type = $request->query('type');
        $investorId = $request->query('investor_id');

        $query = InvestorTransaction::query()->with('investor')->whereBetween('posting_date', [$from, $to]);

        if ($type && in_array($type, ['contribution', 'withdrawal', 'dividend', 'adjustment'], true)) {
            $query->where('type', $type);
        }

        if ($investorId) {
            $query->where('investor_id', $investorId);
        }

        $filename = 'investors_reports_' . $from . '_to_' . $to . '.csv';

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Date', 'Investor Number', 'Investor Name', 'Type', 'Amount', 'Method', 'Reference', 'Description']);

            $query
                ->orderBy('posting_date')
                ->orderBy('id')
                ->chunk(500, function ($rows) use ($out) {
                    foreach ($rows as $r) {
                        fputcsv($out, [
                            optional($r->posting_date)->toDateString(),
                            optional($r->investor)->investor_number,
                            optional($r->investor)->name,
                            $r->type,
                            (string) $r->amount,
                            $r->method,
                            $r->reference,
                            $r->description,
                        ]);
                    }
                });

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
