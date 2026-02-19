<?php

namespace App\Filament\Resources\ElectionPromises;

use App\Filament\Resources\ElectionPromises\Pages\ManageElectionPromises;
use App\Models\ElectionPromise;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ElectionPromiseResource extends Resource
{
    protected static ?string $model = ElectionPromise::class;

    protected static bool $shouldRegisterNavigation = true;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-flag';

    protected static \UnitEnum|string|null $navigationGroup = 'Jimboni Kwela';

    protected static ?string $navigationLabel = 'Ahadi za Uchaguzi';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Kichwa cha Ahadi')
                    ->required(),
                Textarea::make('description')
                    ->label('Maelezo ya Ahadi')
                    ->columnSpanFull(),
                TextInput::make('district')
                    ->label('Wilaya'),
                TextInput::make('ward')
                    ->label('Kata'),
                TextInput::make('village')
                    ->label('Kijiji / Mtaa'),
                Select::make('status')
                    ->label('Hali ya Utekelezaji')
                    ->options([
                        'haijaanza' => 'Haijaanza',
                        'inaendelea' => 'Inaendelea',
                        'imekamilika' => 'Imekamilika',
                    ])
                    ->required(),
                Textarea::make('implementation_notes')
                    ->label('Maelezo ya Utekelezaji')
                    ->columnSpanFull(),
                DatePicker::make('start_date')
                    ->label('Tarehe ya Kuanza'),
                DatePicker::make('end_date')
                    ->label('Tarehe ya Kukamilika (au makadirio)'),
                Textarea::make('challenges')
                    ->label('Changamoto')
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Kichwa cha Ahadi')
                    ->searchable(),
                TextColumn::make('district')
                    ->label('Wilaya'),
                TextColumn::make('ward')
                    ->label('Kata'),
                TextColumn::make('village')
                    ->label('Kijiji / Mtaa'),
                BadgeColumn::make('status')
                    ->label('Hali')
                    ->colors([
                        'gray' => 'haijaanza',
                        'warning' => 'inaendelea',
                        'success' => 'imekamilika',
                    ]),
                TextColumn::make('start_date')
                    ->label('Kuanza')
                    ->date(),
                TextColumn::make('end_date')
                    ->label('Kukamilika')
                    ->date(),
            ])
            ->defaultSort('start_date', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageElectionPromises::route('/'),
        ];
    }
}
