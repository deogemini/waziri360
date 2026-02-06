<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Deliverable;
use App\Models\Event;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class PlannerStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $now = Carbon::now();
        $today = $now->toDateString();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();
        $nextMonthStart = $startOfMonth->copy()->addMonth();
        $nextMonthEnd = $endOfMonth->copy()->addMonth();

        $bookingsCurrentMonth = Booking::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
        $bookingsNextMonth = Booking::whereBetween('requested_date', [$nextMonthStart, $nextMonthEnd])->count();

        $eventsToday = Event::whereDate('start_time', $today)->count();

        $attendeesJoinedToday = DB::table('event_user')
            ->join('events', 'event_user.event_id', '=', 'events.id')
            ->whereDate('events.start_time', $today)
            ->where('event_user.status', 'joined')
            ->count();

        $deliverablesDueToday = Deliverable::whereDate('due_date', $today)->count();
        $deliverablesCompletedToday = Deliverable::whereDate('due_date', $today)->where('status', 'completed')->count();
        $deliverablesPendingToday = $deliverablesDueToday - $deliverablesCompletedToday;

        return [
            Stat::make('Bookings (This Month)', (string) $bookingsCurrentMonth)
                ->description($bookingsCurrentMonth === 0 ? 'No bookings in current month' : 'New requests this month')
                ->icon('heroicon-o-calendar-days')
                ->color($bookingsCurrentMonth === 0 ? 'gray' : 'primary'),

            Stat::make('Bookings (Next Month)', (string) $bookingsNextMonth)
                ->description($bookingsNextMonth === 0 ? 'No scheduled for next month' : 'By requested date')
                ->icon('heroicon-o-forward')
                ->color($bookingsNextMonth === 0 ? 'gray' : 'info'),

            Stat::make('Today’s Events', (string) $eventsToday)
                ->description($eventsToday === 0 ? 'No events scheduled today' : 'Planner activity for today')
                ->icon('heroicon-o-calendar')
                ->color($eventsToday === 0 ? 'gray' : 'warning'),

            Stat::make('Attendees (Joined Today)', (string) $attendeesJoinedToday)
                ->description($attendeesJoinedToday === 0 ? 'No confirmed attendees today' : 'Confirmed participation')
                ->icon('heroicon-o-user-group')
                ->color($attendeesJoinedToday === 0 ? 'gray' : 'success'),

            Stat::make('Deliverables Due Today', (string) $deliverablesDueToday)
                ->description($deliverablesDueToday === 0 ? 'No deliverables due today' : $deliverablesCompletedToday . ' completed • ' . $deliverablesPendingToday . ' pending')
                ->icon('heroicon-o-clipboard-document-check')
                ->color($deliverablesPendingToday > 0 ? 'danger' : ($deliverablesDueToday === 0 ? 'gray' : 'success')),
        ];
    }
}
