<?php

namespace App\Filament\Widgets;

use App\Models\Bus;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class BusTypeOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Free buses', Bus::query()->whereNull('user_id')->count()),
            Stat::make('Free drivers', User::query()->role('driver')->whereDoesntHave('bus')->count()),
            Stat::make('Total buses', Bus::query()->count()),
            Stat::make('Total drivers', User::query()->role('driver')->count()),
        ];
    }
}
