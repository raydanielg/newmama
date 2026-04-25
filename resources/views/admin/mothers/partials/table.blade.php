<div class="table-responsive">
    <table class="admin-table">
        <thead>
            <tr>
                <th>MK Number</th>
                <th>Name</th>
                <th>WhatsApp</th>
                <th>Status</th>
                <th>EDD</th>
                <th>Region</th>
                <th>Approval</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mothers as $mother)
            <tr>
                <td><strong>{{ $mother->mk_number }}</strong></td>
                <td>{{ $mother->full_name }}</td>
                <td>{{ $mother->whatsapp_number }}</td>
                <td>
                    <span class="badge status-{{ $mother->status }}">
                        {{ ucfirst(str_replace('_', ' ', $mother->status)) }}
                    </span>
                </td>
                <td>
                    @if($mother->status === 'pregnant')
                        {{ $mother->edd_date ? $mother->edd_date->format('M d, Y') : '-' }}
                    @else
                        -
                    @endif
                </td>
                <td>{{ $mother->region->name ?? '-' }}</td>
                <td>
                    @if($mother->is_approved)
                        <span class="badge" style="background:#dcfce7; color:#166534; border:1px solid #bbf7d0;">Approved</span>
                    @else
                        <span class="badge" style="background:#fee2e2; color:#991b1b; border:1px solid #fecaca;">Pending</span>
                    @endif
                </td>
                <td>{{ $mother->created_at->format('M d, Y') }}</td>
                <td>
                    <div style="display:flex; gap:8px; align-items:center;">
                        @if(!$mother->is_approved)
                            <form method="POST" action="{{ route('admin.mothers.approve', $mother) }}" style="display:inline;" class="approve-form">
                                @csrf
                                <button class="btn-ico-circle approve" type="submit" title="Approve Mother">
                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                </button>
                            </form>
                        @endif
                        <a class="btn-ico-circle" href="{{ route('admin.mothers.show', $mother) }}" title="View">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </a>
                        <a class="btn-ico-circle edit" href="{{ route('admin.mothers.edit', $mother) }}" title="Edit">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </a>
                        <a class="btn-ico-circle msg" href="{{ route('admin.mothers.messages', $mother) }}" title="Messages" style="position:relative;">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 1 1-7.6-7.6 8.38 8.38 0 0 1 3.8.9L21 5.5z"/></svg>
                            @if($mother->whatsapp_messages_count > 0)
                                <span class="count-badge">{{ $mother->whatsapp_messages_count }}</span>
                            @endif
                        </a>
                        <form method="POST" action="{{ route('admin.mothers.destroy', $mother) }}" style="display:inline;" onsubmit="return confirm('Delete this mother record?');" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button class="btn-ico-circle delete" type="submit" title="Delete">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
            @if(count($mothers) === 0)
                <tr>
                    <td colspan="9" style="text-align:center; padding:40px; color:#6b7280;">No mothers found matching your criteria.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

@if($mothers instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div style="margin-top: 16px;" id="pagination-links">
        {{ $mothers->links() }}
    </div>
@endif
