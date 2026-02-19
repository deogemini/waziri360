<?php

namespace App\Filament\Resources\ConstituencyActivities;

use App\Filament\Resources\ConstituencyActivities\Pages\ManageConstituencyActivities;
use App\Models\ConstituencyActivity;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ConstituencyActivityResource extends Resource
{
    protected static ?string $model = ConstituencyActivity::class;

    protected static bool $shouldRegisterNavigation = true;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-bolt';

    protected static \UnitEnum|string|null $navigationGroup = 'Jimboni Kwela';

    protected static ?string $navigationLabel = 'Shughuli za Jimbo';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Jina la Tukio')
                    ->required(),
                TextInput::make('activity_type')
                    ->label('Aina ya Tukio')
                    ->placeholder('mfano: Mkutano wa wananchi, ziara, harambee'),
                DatePicker::make('date')
                    ->label('Tarehe ya Tukio'),
                TextInput::make('district')
                    ->label('Wilaya'),
                TextInput::make('ward')
                    ->label('Kata'),
                TextInput::make('village')
                    ->label('Kijiji / Mtaa'),
                Textarea::make('description')
                    ->label('Maelezo ya Tukio')
                    ->columnSpanFull(),
                Textarea::make('key_participants')
                    ->label('Washiriki Wakuu')
                    ->columnSpanFull(),
                Textarea::make('outcomes')
                    ->label('Matokeo ya Tukio')
                    ->columnSpanFull(),
                FileUpload::make('attachment_path')
                    ->label('Picha / Nyaraka')
                    ->disk('public')
                    ->directory('constituency_activities')
                    ->imagePreviewHeight('150')
                    ->downloadable()
                    ->openable()
                    ->nullable(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Jina la Tukio')
                    ->searchable(),
                TextColumn::make('activity_type')
                    ->label('Aina ya Tukio')
                    ->limit(30),
                TextColumn::make('date')
                    ->label('Tarehe')
                    ->date(),
                TextColumn::make('district')
                    ->label('Wilaya'),
                TextColumn::make('ward')
                    ->label('Kata'),
                TextColumn::make('village')
                    ->label('Kijiji / Mtaa'),
            ])
            ->defaultSort('date', 'desc')
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
            'index' => ManageConstituencyActivities::route('/'),
        ];
    }
}
