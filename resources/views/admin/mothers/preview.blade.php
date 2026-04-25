<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mothers Report - Mamacare AI</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1cm;
        }
        body {
            font-family: 'Nunito', sans-serif;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #4c1d95;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            height: 80px;
            margin-bottom: 15px;
        }
        .report-title h1 {
            margin: 0;
            color: #4c1d95;
            font-size: 26px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .report-title p {
            margin: 8px 0 0;
            font-size: 14px;
            color: #555;
            font-weight: 600;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        th {
            background-color: #f3f4f6;
            color: #4c1d95;
            text-align: left;
            padding: 12px 10px;
            border: 1px solid #e5e7eb;
            font-weight: 800;
            text-transform: uppercase;
        }
        td {
            padding: 10px;
            border: 1px solid #e5e7eb;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 700;
            font-size: 10px;
            display: inline-block;
        }
        .status-pregnant { background: #dbeafe; color: #1e40af; }
        .status-new_parent { background: #dcfce7; color: #166534; }
        .status-trying { background: #fef3c7; color: #92400e; }
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            font-size: 11px;
            color: #777;
            text-align: center;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                padding: 0;
            }
        }
        .btn-print {
            background: #4c1d95;
            color: white;
            padding: 12px 24px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
            margin-bottom: 30px;
            display: inline-block;
            cursor: pointer;
            border: none;
            box-shadow: 0 4px 6px rgba(76, 29, 149, 0.2);
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: right; padding-bottom: 20px;">
        <button class="btn-print" onclick="window.print()">Print / Download PDF</button>
        <a href="{{ route('admin.mothers') }}" style="margin-left: 15px; color: #666; font-size: 14px; font-weight: bold; text-decoration: none;">&larr; Back to Dashboard</a>
    </div>

    <div class="header">
        <img src="{{ asset('meetup_3669956.png') }}" class="logo" alt="Mamacare AI">
        <div class="report-title">
            <h1>Mothers Database Report</h1>
            <p>Generated on: {{ now()->format('M d, Y H:i') }}</p>
            <p>Total Records: {{ count($mothers) }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>MK Number</th>
                <th>Full Name</th>
                <th>WhatsApp</th>
                <th>Status</th>
                <th>EDD / Baby Age</th>
                <th>Approval</th>
                <th>Joined Date</th>
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
                        EDD: {{ $mother->edd_date ? $mother->edd_date->format('M d, Y') : '-' }}
                    @elseif($mother->status === 'new_parent')
                        Age: {{ $mother->baby_age ?? '0' }} Months
                    @else
                        -
                    @endif
                </td>
                <td>{{ $mother->is_approved ? 'Approved' : 'Pending' }}</td>
                <td>{{ $mother->created_at->format('M d, Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        &copy; {{ date('Y') }} Mamacare AI. All rights reserved. | Confidential Report
    </div>

    <script>
        // Auto-open print dialog
        window.onload = function() {
            // setTimeout(() => window.print(), 500);
        }
    </script>
</body>
</html>
