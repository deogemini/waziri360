<?php

namespace App\Filament\Resources\Deliverables;

use App\Filament\Resources\Deliverables\Pages\ManageDeliverables;
use App\Models\Deliverable;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DeliverableResource extends Resource
{
    protected static ?string $model = Deliverable::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')->required(),
                Textarea::make('description')->columnSpanFull(),
                DateTimePicker::make('due_date'),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'at_risk' => 'At Risk',
                    ])
                    ->required(),
                Select::make('event_id')
                    ->relationship('event', 'title')
                    ->label('Event')
                    ->searchable()
                    ->preload()
                    ->required(),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('event.title')
                    ->label('Event')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('due_date')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
                BadgeColumn::make('status')
                    ->colors([
                        'success' => 'completed',
                        'danger' => 'at_risk',
                        'warning' => 'pending',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageDeliverables::route('/'),
        ];
    }
}
