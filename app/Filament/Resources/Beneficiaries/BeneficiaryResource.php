<?php

namespace App\Filament\Resources\Beneficiaries;

use App\Filament\Resources\Beneficiaries\Pages\ManageBeneficiaries;
use App\Models\Beneficiary;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BeneficiaryResource extends Resource
{
    protected static ?string $model = Beneficiary::class;

    protected static bool $shouldRegisterNavigation = true;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static \UnitEnum|string|null $navigationGroup = 'Jimboni Kwela';

    protected static ?string $navigationLabel = 'Wanufaika';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('full_name')
                    ->label('Jina Kamili')
                    ->required(),
                TextInput::make('nida_number')
                    ->label('Namba ya NIDA'),
                Select::make('gender')
                    ->label('Jinsia')
                    ->options([
                        'mwanaume' => 'Mwanaume',
                        'mwanamke' => 'Mwanamke',
                        'nyingine' => 'Nyingine',
                    ]),
                TextInput::make('district')
                    ->label('Wilaya'),
                TextInput::make('ward')
                    ->label('Kata'),
                TextInput::make('village')
                    ->label('Kijiji / Mtaa'),
                TextInput::make('group_name')
                    ->label('Kikundi')
                    ->placeholder('mfano: Vijana, Wanawake, Walemavu'),
                TextInput::make('support_type')
                    ->label('Aina ya Msaada'),
                DatePicker::make('benefited_at')
                    ->label('Tarehe ya Kunufaika'),
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
                TextColumn::make('full_name')
                    ->label('Jina Kamili')
                    ->searchable(),
                TextColumn::make('nida_number')
                    ->label('Namba ya NIDA')
                    ->searchable(),
                TextColumn::make('gender')
                    ->label('Jinsia'),
                TextColumn::make('district')
                    ->label('Wilaya'),
                TextColumn::make('ward')
                    ->label('Kata'),
                TextColumn::make('support_type')
                    ->label('Aina ya Msaada'),
                TextColumn::make('benefited_at')
                    ->label('Tarehe ya Kunufaika')
                    ->date(),
            ])
            ->defaultSort('benefited_at', 'desc')
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
            'index' => ManageBeneficiaries::route('/'),
        ];
    }
}
