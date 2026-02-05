<?php

namespace App\Filament\Resources\Events;

use App\Filament\Resources\Events\Pages\ManageEvents;
use App\Models\Event;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Event Details')
                    ->schema([
                        TextInput::make('title')
                            ->required(),
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        DateTimePicker::make('start_time')
                            ->required(),
                        DateTimePicker::make('end_time')
                            ->required()
                            ->after('start_time'),
                        TextInput::make('location'),
                        Textarea::make('description')
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Recurrence')
                    ->schema([
                        Toggle::make('is_recurring')
                            ->live(),
                        Select::make('recurrence_pattern')
                            ->options([
                                'daily' => 'Daily',
                                'weekly' => 'Weekly',
                                'monthly' => 'Monthly',
                            ])
                            ->visible(fn ($get) => $get('is_recurring')),
                    ]),

                Section::make('Attendees')
                    ->schema([
                        Select::make('attendees')
                            ->relationship('attendees', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->label('Select Attendees'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->description(fn (Event $record) => $record->category->name)
                    ->tooltip(fn (Event $record) => $record->description),
                TextColumn::make('category.name')
                    ->badge()
                    ->color(fn ($record) => match ($record->category->type) {
                        'official' => 'info',
                        'social' => 'success',
                        'urgent' => 'danger',
                        default => 'gray',
                    })
                    ->searchable(),
                TextColumn::make('start_time')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('end_time')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('location')
                    ->searchable(),
                IconColumn::make('is_recurring')
                    ->boolean(),
                TextColumn::make('attendees_count')
                    ->counts('attendees')
                    ->label('Attendees'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                Action::make('export_csv')
                    ->label('Export to CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {
                        $response = new StreamedResponse(function () {
                            $handle = fopen('php://output', 'w');
                            fputcsv($handle, ['Title', 'Category', 'Start Time', 'End Time', 'Location', 'Recurring', 'Attendees Count']);

                            Event::with(['category', 'attendees'])->chunk(100, function (Collection $events) use ($handle) {
                                foreach ($events as $event) {
                                    fputcsv($handle, [
                                        $event->title,
                                        $event->category->name,
                                        $event->start_time->toDateTimeString(),
                                        $event->end_time->toDateTimeString(),
                                        $event->location,
                                        $event->is_recurring ? 'Yes' : 'No',
                                        $event->attendees->count(),
                                    ]);
                                }
                            });

                            fclose($handle);
                        });

                        $response->headers->set('Content-Type', 'text/csv');
                        $response->headers->set('Content-Disposition', 'attachment; filename="events_export.csv"');

                        return $response;
                    }),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageEvents::route('/'),
        ];
    }
}
