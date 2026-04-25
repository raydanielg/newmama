@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header mb-4">
    <div class="header-info">
        <h3 class="fw-bold">{{ $title }}</h3>
        <p class="text-muted">Record of all sales returns and credit notes issued.</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.vouchers.credit-note.create') }}" class="btn btn-danger px-4 py-2 fw-bold shadow-sm">+ New Sales Return</a>
    </div>
</div>

<div class="content-card shadow-sm border-0 p-4 mb-4">
    <form method="GET" action="{{ route('admin.sales.return') }}" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label small fw-bold text-muted">From Date</label>
            <input type="date" name="from" value="{{ request('from') }}" class="form-control border-light bg-light">
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-bold text-muted">To Date</label>
            <input type="date" name="to" value="{{ request('to') }}" class="form-control border-light bg-light">
        </div>
        <div class="col-md-4">
            <label class="form-label small fw-bold text-muted">Search Reference</label>
            <input name="q" value="{{ request('q') }}" type="text" placeholder="Search ref..." class="form-control border-light bg-light">
        </div>
        <div class="col-md-2">
            <button class="btn btn-dark w-100 py-2 fw-bold" type="submit">Filter</button>
        </div>
    </form>
</div>

<div class="content-card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4 py-3 border-0 text-muted small uppercase fw-bold">Ref</th>
                    <th class="py-3 border-0 text-muted small uppercase fw-bold">Date</th>
                    <th class="py-3 border-0 text-muted small uppercase fw-bold">Description</th>
                    <th class="py-3 border-0 text-muted small uppercase fw-bold text-end">Amount</th>
                    <th class="py-3 border-0 text-muted small uppercase fw-bold text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody class="border-top-0">
                @forelse($vouchers as $v)
                <tr>
                    <td class="ps-4 fw-bold text-dark">{{ $v->ref }}</td>
                    <td>{{ $v->posting_date?->format('M d, Y') }}</td>
                    <td class="text-muted small">{{ Str::limit($v->description, 50) }}</td>
                    <td class="text-end fw-black text-danger">TSh {{ number_format((float) $v->total_amount, 0) }}</td>
                    <td class="text-end pe-4">
                        <a class="btn btn-sm btn-outline-primary rounded-pill px-3" href="{{ route('admin.vouchers.view', $v) }}">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-5 text-muted italic">No sales returns found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($vouchers->hasPages())
    <div class="card-footer bg-white border-top-0 p-4">
        {{ $vouchers->links() }}
    </div>
    @endif
</div>

<style>
    .fw-black { font-weight: 900; }
</style>
@endsection
