<?php

namespace App\Filament\Widgets;

use App\Models\Deliverable;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class DeliverablesListWidget extends BaseWidget
{
    protected static ?string $heading = 'Deliverables';

    protected int|string|array $columnSpan = [
        'md' => '1/3',
        'xl' => '1/4',
    ];

    public static function canView(): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }
        return $user->can('View:DeliverablesListWidget');
    }

    protected function getTableQuery(): Builder
    {
        return Deliverable::query()
            ->with('event')
            ->latest('due_date');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('title')
                ->label('Title')
                ->limit(26)
                ->searchable(),
            Tables\Columns\TextColumn::make('event.title')
                ->label('Event')
                ->limit(24)
                ->toggleable()
                ->searchable(),
            Tables\Columns\TextColumn::make('due_date')
                ->label('Due')
                ->dateTime('Y-m-d H:i')
                ->sortable(),
            Tables\Columns\TextColumn::make('status')
                ->badge()
                ->colors([
                    'success' => 'completed',
                    'danger' => 'at_risk',
                    'warning' => 'pending',
                ])
                ->sortable(),
        ];
    }
}
