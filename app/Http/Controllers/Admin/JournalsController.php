<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Journal;
use Illuminate\Http\Request;

class JournalsController extends Controller
{
    public function index(Request $request)
    {
        $query = Journal::query();

        if ($from = $request->query('from')) {
            $query->whereDate('posting_date', '>=', $from);
        }

        if ($to = $request->query('to')) {
            $query->whereDate('posting_date', '<=', $to);
        }

        if ($type = trim((string) $request->query('type', ''))) {
            $query->where('journal_type', $type);
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('ref', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('source_ref', 'like', "%{$search}%");
            });
        }

        $journals = $query->orderByDesc('posting_date')->orderByDesc('id')->paginate(20)->withQueryString();

        return view('admin.journals.index', compact('journals'));
    }

    public function show(Journal $journal)
    {
        $journal->load(['lines.account']);

        return view('admin.journals.show', compact('journal'));
    }
}
