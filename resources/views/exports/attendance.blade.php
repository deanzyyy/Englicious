<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Attendance Report - {{ $classroom->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .subtitle {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .status {
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
        }
        .present {
            color: #22c55e;
        }
        .absent {
            color: #ef4444;
        }
        .late {
            color: #3b82f6;
        }
        .excused {
            color: #eab308;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Attendance Report</div>
        <div class="subtitle">{{ $classroom->name }} - {{ \Carbon\Carbon::parse($date)->format('l, d F Y') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%">No</th>
                <th style="width: 40%">Student Name</th>
                <th style="width: 25%">Status</th>
                <th style="width: 25%">Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $index => $attendance)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $attendance->student->name }}</td>
                <td>
                    <span class="status {{ $attendance->status }}">
                        {{ ucfirst($attendance->status) }}
                    </span>
                </td>
                <td>{{ $attendance->notes ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html> 