<?php

namespace App\Filament\Widgets;

use App\Models\Tour;
use App\Models\User;
use App\Models\Vehicle;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $user = auth()->user();
        
        if ($user->hasRole('driver')) {
            return [
                Stat::make('My Total Tours', Tour::where('user_id', $user->id)->count())
                    ->description('All your logged trips')
                    ->descriptionIcon('heroicon-m-map')
                    ->color('success'),
                Stat::make('Ongoing Tours', Tour::where('user_id', $user->id)->whereNull('end_mileage')->count())
                    ->description('Trips currently in progress')
                    ->descriptionIcon('heroicon-m-clock')
                    ->color('warning'),
                Stat::make('Latest Trip', Tour::where('user_id', $user->id)->latest()->first()?->tour_name ?? 'None')
                    ->description('Your most recent tour'),
            ];
        }

        return [
            Stat::make('Total Vehicles', Vehicle::count())
                ->description('Fleet size')
                ->descriptionIcon('heroicon-m-truck')
                ->color('info'),
            Stat::make('Total Tours', Tour::count())
                ->description('Total trips logged')
                ->descriptionIcon('heroicon-m-map')
                ->color('success'),
            Stat::make('Active Drivers', User::role('driver')->count())
                ->description('Registered drivers')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
            Stat::make('Ongoing Tours', Tour::whereNull('end_mileage')->count())
                ->description('Tours currently in progress')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
}
