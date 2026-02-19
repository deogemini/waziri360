<?php

namespace App\Filament\Resources\ConstituencyProjects;

use App\Filament\Resources\ConstituencyProjects\Pages\ManageConstituencyProjects;
use App\Models\ConstituencyProject;
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

class ConstituencyProjectResource extends Resource
{
    protected static ?string $model = ConstituencyProject::class;

    protected static bool $shouldRegisterNavigation = true;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';

    protected static \UnitEnum|string|null $navigationGroup = 'Jimboni Kwela';

    protected static ?string $navigationLabel = 'Miradi ya Jimbo';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Jina la Mradi')
                    ->required(),
                Select::make('project_type')
                    ->label('Aina ya Mradi')
                    ->options([
                        'elimu' => 'Elimu',
                        'afya' => 'Afya',
                        'maji' => 'Maji',
                        'barabara' => 'Barabara',
                        'nyingine' => 'Nyingine',
                    ])
                    ->native(false),
                TextInput::make('district')
                    ->label('Wilaya'),
                TextInput::make('ward')
                    ->label('Kata'),
                TextInput::make('village')
                    ->label('Kijiji / Mtaa'),
                Select::make('funding_source')
                    ->label('Chanzo cha Fedha')
                    ->options([
                        'serikali' => 'Serikali',
                        'mfuko_wa_jimbo' => 'Mfuko wa Jimbo',
                        'wadau' => 'Wadau',
                        'nyingine' => 'Nyingine',
                    ])
                    ->native(false),
                TextInput::make('budget')
                    ->label('Bajeti ya Mradi')
                    ->numeric()
                    ->prefix('TZS'),
                TextInput::make('amount_spent')
                    ->label('Kiasi Kilichotumika')
                    ->numeric()
                    ->prefix('TZS'),
                Select::make('status')
                    ->label('Hali ya Mradi')
                    ->options([
                        'kupangwa' => 'Kupangwa',
                        'unaendelea' => 'Unaendelea',
                        'umekamilika' => 'Umekamilika',
                    ])
                    ->required(),
                DatePicker::make('start_date')
                    ->label('Tarehe ya Kuanza'),
                DatePicker::make('end_date')
                    ->label('Tarehe ya Kukamilika'),
                TextInput::make('contractor')
                    ->label('Mkandarasi / Msimamizi'),
                Textarea::make('notes')
                    ->label('Maelezo ya Ziada')
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Jina la Mradi')
                    ->searchable(),
                TextColumn::make('project_type')
                    ->label('Aina ya Mradi'),
                TextColumn::make('district')
                    ->label('Wilaya'),
                TextColumn::make('ward')
                    ->label('Kata'),
                TextColumn::make('village')
                    ->label('Kijiji / Mtaa'),
                TextColumn::make('funding_source')
                    ->label('Chanzo cha Fedha'),
                TextColumn::make('budget')
                    ->label('Bajeti')
                    ->money('TZS', true),
                TextColumn::make('amount_spent')
                    ->label('Kilichotumika')
                    ->money('TZS', true),
                BadgeColumn::make('status')
                    ->label('Hali')
                    ->colors([
                        'gray' => 'kupangwa',
                        'warning' => 'unaendelea',
                        'success' => 'umekamilika',
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
            'index' => ManageConstituencyProjects::route('/'),
        ];
    }
}
