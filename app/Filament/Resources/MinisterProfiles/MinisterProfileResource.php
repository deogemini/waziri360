<?php

namespace App\Filament\Resources\MinisterProfiles;

use App\Filament\Resources\MinisterProfiles\Pages\ManageMinisterProfiles;
use App\Models\MinisterProfile;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;

class MinisterProfileResource extends Resource
{
    protected static ?string $model = MinisterProfile::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-circle';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                FileUpload::make('photo_path')
                    ->image()
                    ->directory('minister-profile')
                    ->default(null),
                Textarea::make('bio')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo_path')
                    ->circular(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageMinisterProfiles::route('/'),
        ];
    }
}
