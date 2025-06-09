<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Achievement Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #4338ca;
            margin-bottom: 5px;
        }
        .header p {
            color: #6b7280;
            margin-top: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #d1d5db;
            padding: 8px 12px;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 0.8em;
            color: #6b7280;
        }
        .summary {
            margin-bottom: 20px;
        }
        .summary h2 {
            color: #4338ca;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .summary-label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $reportType === 'comprehensive' ? 'Comprehensive Achievement Report' : 'Executive Summary Report' }}</h1>
        <p>Generated on {{ $generatedAt }}</p>
    </div>

    @if($reportType === 'summary')
    <div class="summary">
        <h2>Achievement Summary</h2>
        <div class="summary-item">
            <span class="summary-label">Total Achievements:</span>
            <span>{{ $achievements->count() }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">International Level:</span>
            <span>{{ $achievements->where('level', 'Internasional')->count() }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">National Level:</span>
            <span>{{ $achievements->where('level', 'Nasional')->count() }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Regional Level:</span>
            <span>{{ $achievements->where('level', 'Regional')->count() }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Provincial Level:</span>
            <span>{{ $achievements->where('level', 'Provinsi')->count() }}</span>
        </div>
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Student</th>
                <th>Competition</th>
                <th>Type</th>
                <th>Level</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($achievements as $achievement)
            <tr>
                <td>{{ $achievement->id }}</td>
                <td>{{ $achievement->title }}</td>
                <td>{{ $achievement->user->name ?? 'Unknown' }}</td>
                <td>{{ $achievement->competition_name }}</td>
                <td>{{ $achievement->type }}</td>
                <td>{{ $achievement->level }}</td>
                <td>{{ $achievement->date }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>This report is generated automatically by the Achievement Management System.</p>
        <p>© {{ date('Y') }} Achievement Management System</p>
    </div>
</body>
</html> 