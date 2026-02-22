<?php

namespace App\Filament\Widgets;

use App\Models\Beneficiary;
use App\Models\ConstituencyActivity;
use App\Models\ConstituencyProject;
use App\Models\ElectionPromise;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class KwelaStatsOverview extends BaseWidget
{
    public static function canView(): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }
        return $user->can('View:KwelaStatsOverview');
    }

    protected function getStats(): array
    {
        $beneficiaries = Beneficiary::count();
        $activities = ConstituencyActivity::count();
        $projects = ConstituencyProject::count();
        $promises = ElectionPromise::count();

        return [
            Stat::make('Wanufaika', (string) $beneficiaries)
                ->icon('heroicon-o-user-group')
                ->color($beneficiaries > 0 ? 'success' : 'gray'),
            Stat::make('Shughuli za Jimbo', (string) $activities)
                ->icon('heroicon-o-bolt')
                ->color($activities > 0 ? 'warning' : 'gray'),
            Stat::make('Miradi ya Jimbo', (string) $projects)
                ->icon('heroicon-o-building-office')
                ->color($projects > 0 ? 'info' : 'gray'),
            Stat::make('Ahadi za Uchaguzi', (string) $promises)
                ->icon('heroicon-o-flag')
                ->color($promises > 0 ? 'primary' : 'gray'),
        ];
    }
}
