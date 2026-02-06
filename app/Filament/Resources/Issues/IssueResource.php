<?php

namespace App\Filament\Resources\Issues;

use App\Filament\Resources\Issues\Pages\ManageIssues;
use App\Models\Issue;
use App\Models\User;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class IssueResource extends Resource
{
    protected static ?string $model = Issue::class;

        protected static bool $shouldRegisterNavigation = true;


    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Issue')
                    ->schema([
                        TextInput::make('title')->required(),
                        Select::make('priority')
                            ->options([
                                'urgent' => 'Urgent',
                                'normal' => 'Normal',
                                'low' => 'Low',
                            ])->required(),
                        Select::make('status')
                            ->options([
                                'assigned' => 'Assigned',
                                'in_progress' => 'In Progress',
                                'completed' => 'Completed',
                            ])->required(),
                        Select::make('deputy_id')
                            ->label('Deputy')
                            ->options(fn () => User::where('role', 'deputy')->pluck('name', 'id'))
                            ->searchable()
                            ->preload(),
                        DateTimePicker::make('due_date')->label('Due Date'),
                        Textarea::make('description')->columnSpanFull(),
                    ])->columns(2),

                Section::make('Attachments')
                    ->schema([
                        Repeater::make('attachments')
                            ->relationship('attachments')
                            ->schema([
                                TextInput::make('type')->label('Type')->placeholder('doc/photo/url'),
                                FileUpload::make('path')
                                    ->disk('public')
                                    ->directory('issue_attachments')
                                    ->preserveFilenames()
                                    ->required(),
                            ])
                            ->addActionLabel('Add Attachment')
                            ->columnSpanFull(),
                    ]),

                Section::make('Feedback')
                    ->schema([
                        Textarea::make('remarks')
                            ->label('Deputy Remarks')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')->searchable(),
                TextColumn::make('priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'urgent' => 'danger',
                        'normal' => 'warning',
                        'low' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'assigned' => 'info',
                        'in_progress' => 'warning',
                        'completed' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('deputy.name')->label('Deputy')->searchable(),
                TextColumn::make('due_date')->dateTime()->sortable(),
                IconColumn::make('escalated')->boolean()->label('Escalated'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('escalate')
                    ->label('Escalate')
                    ->icon('heroicon-o-arrow-up-right')
                    ->requiresConfirmation()
                    ->visible(fn (Issue $record) => !$record->escalated && $record->status !== 'completed' && ($record->due_date?->isPast() ?? false))
                    ->form([
                        Textarea::make('reason')->label('Escalation Reason')->required(),
                    ])
                    ->action(function (Issue $record, array $data) {
                        $record->update([
                            'escalated' => true,
                            'escalated_at' => now(),
                            'escalation_reason' => $data['reason'],
                        ]);
                    }),
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
            'index' => ManageIssues::route('/'),
        ];
    }
}
