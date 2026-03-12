<?php

namespace App\Filament\Widgets;

use App\Models\Tour;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;

class FuelUsageChart extends ChartWidget
{
    protected static ?string $heading = 'Fuel Usage Trend';
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = 2;
    
    protected static string $color = 'danger';

    protected function getData(): array
    {
        $user = auth()->user();
        
        // Manual grouping since trend package might not be installed (let's check)
        // I will use raw query or Eloquent grouping
        
        $query = Tour::query();
        if ($user->hasRole('driver')) {
            $query->where('user_id', $user->id);
        }

        $data = $query->selectRaw('SUM(fuel_amount) as total, DATE_FORMAT(created_at, "%Y-%m-01") as month')
            ->groupBy('month')
            ->orderBy('month')
            ->limit(12)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Fuel Cost ($)',
                    'data' => $data->pluck('total')->toArray(),
                    'fill' => 'start',
                ],
            ],
            'labels' => $data->map(fn($item) => Carbon::parse($item->month)->format('M Y'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
