<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1 { font-size: 18px; text-align: center; }
        .header { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Tour Report</h1>
        <p>Generated on: {{ \Carbon\Carbon::now()->format('Y-m-d H:i:s') }}</p>
        
        @if(!empty($filters['date_from']) || !empty($filters['date_to']))
            <p><strong>Date Range:</strong> {{ $filters['date_from'] ?? 'Start' }} to {{ $filters['date_to'] ?? 'End' }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Tour Name</th>
                <th>Driver</th>
                <th>Vehicle</th>
                <th>Start Mileage</th>
                <th>End Mileage</th>
                <th>Fuel Cost</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tours as $tour)
            <tr>
                <td>{{ $tour->created_at->format('Y-m-d') }}</td>
                <td>{{ $tour->tour_name }}</td>
                <td>{{ $tour->user->name ?? 'N/A' }}</td>
                <td>{{ $tour->vehicle->plate_number ?? 'N/A' }}</td>
                <td>{{ $tour->start_mileage }}</td>
                <td>{{ $tour->end_mileage ?? 'Ongoing' }}</td>
                <td>{{ $tour->fuel_amount ? '$' . number_format($tour->fuel_amount, 2) : 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
