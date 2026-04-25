@extends('layouts.mother')

@section('title', 'Appointments - MamaCare')

@section('content')
<div class="header">
    <h1 class="page-title">
        <i class="fas fa-calendar-check"></i>
        Appointments
    </h1>
    <button class="btn btn-primary" onclick="showAddModal()">
        <i class="fas fa-plus"></i>
        Schedule Appointment
    </button>
</div>

{{-- Upcoming Appointments --}}
<div class="card" style="margin-bottom: 24px;">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-calendar-day"></i>
            Upcoming Appointments
        </h3>
        <span class="badge badge-pink">{{ $upcoming->count() }} scheduled</span>
    </div>
    
    @if($upcoming->count() > 0)
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Location</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($upcoming as $appointment)
                <tr>
                    <td>
                        <div style="font-weight: 600;">{{ $appointment->appointment_date->format('M d, Y') }}</div>
                        <div style="font-size: 12px; color: var(--gray);">{{ $appointment->appointment_date->format('g:i A') }}</div>
                    </td>
                    <td>{{ $appointment->title }}</td>
                    <td>
                        <span class="badge badge-blue">{{ $appointment->type_label }}</span>
                    </td>
                    <td>
                        @if($appointment->clinic_name)
                            <div>{{ $appointment->clinic_name }}</div>
                            @if($appointment->doctor_name)
                            <div style="font-size: 12px; color: var(--gray);">Dr. {{ $appointment->doctor_name }}</div>
                            @endif
                        @else
                            <span style="color: var(--gray);">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-{{ $appointment->status === 'scheduled' ? 'green' : 'gray' }}">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div style="text-align: center; padding: 60px 20px; color: var(--gray);">
        <i class="fas fa-calendar-plus" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
        <p style="font-size: 16px; margin-bottom: 20px;">No upcoming appointments scheduled</p>
        <button class="btn btn-primary" onclick="showAddModal()">
            <i class="fas fa-plus"></i> Schedule Your First Appointment
        </button>
    </div>
    @endif
</div>

{{-- Past Appointments --}}
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-history"></i>
            Past Appointments
        </h3>
    </div>
    
    @if($past->count() > 0)
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Outcome</th>
                </tr>
            </thead>
            <tbody>
                @foreach($past as $appointment)
                <tr>
                    <td>{{ $appointment->appointment_date->format('M d, Y') }}</td>
                    <td>{{ $appointment->title }}</td>
                    <td>
                        <span class="badge badge-gray">{{ $appointment->type_label }}</span>
                    </td>
                    <td>
                        @if($appointment->outcome)
                            <span style="color: var(--success);">
                                <i class="fas fa-check-circle"></i> Completed
                            </span>
                        @else
                            <span style="color: var(--gray);">{{ ucfirst($appointment->status) }}</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div style="text-align: center; padding: 40px 20px; color: var(--gray);">
        <i class="fas fa-clipboard" style="font-size: 32px; margin-bottom: 12px; opacity: 0.5;"></i>
        <p>No past appointments on record</p>
    </div>
    @endif
</div>

{{-- Add Appointment Modal --}}
<div id="addModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 2000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 20px; padding: 32px; width: 90%; max-width: 500px; max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h3 style="font-size: 20px; font-weight: 600;">Schedule Appointment</h3>
            <button onclick="hideAddModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: var(--gray);">&times;</button>
        </div>
        
        <form action="{{ route('mother.appointments.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Appointment Title *</label>
                <input type="text" name="title" class="form-input" placeholder="e.g., Regular Checkup, Ultrasound" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Date & Time *</label>
                <input type="datetime-local" name="appointment_date" class="form-input" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Type</label>
                <select name="type" class="form-input">
                    <option value="checkup">Clinic Checkup</option>
                    <option value="ultrasound">Ultrasound Scan</option>
                    <option value="lab_test">Laboratory Test</option>
                    <option value="vaccination">Vaccination</option>
                    <option value="other">Other</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Clinic/Hospital Name</label>
                <input type="text" name="clinic_name" class="form-input" placeholder="e.g., Mama Care Clinic">
            </div>
            
            <div class="form-group">
                <label class="form-label">Doctor's Name</label>
                <input type="text" name="doctor_name" class="form-input" placeholder="e.g., Dr. John Smith">
            </div>
            
            <div class="form-group">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-input" rows="3" placeholder="Any special instructions or questions..."></textarea>
            </div>
            
            <div style="display: flex; gap: 12px; margin-top: 24px;">
                <button type="button" class="btn btn-secondary" onclick="hideAddModal()" style="flex: 1;">Cancel</button>
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <i class="fas fa-save"></i> Save Appointment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showAddModal() {
        document.getElementById('addModal').style.display = 'flex';
        // Set minimum datetime to now
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        document.querySelector('input[name="appointment_date"]').min = now.toISOString().slice(0,16);
    }
    
    function hideAddModal() {
        document.getElementById('addModal').style.display = 'none';
    }
    
    // Close modal when clicking outside
    document.getElementById('addModal').addEventListener('click', function(e) {
        if (e.target === this) hideAddModal();
    });
</script>
@endpush
