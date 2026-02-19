<?php

namespace App\Filament\Resources\Events;

use App\Filament\Resources\Events\Pages\ManageEvents;
use App\Models\Event;
use App\Models\MinisterProfile;
use BackedEnum;
use Dompdf\Dompdf;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View;
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
                        Textarea::make('attendees_manual')
                            ->label('Attendees')
                            ->placeholder('Enter attendee names, separated by commas or new lines')
                            ->rows(3),
                    ]),

                Section::make('Documents')
                    ->schema([
                        Repeater::make('documents')
                            ->relationship('documents')
                            ->schema([
                                Select::make('type')
                                    ->options([
                                        'photo' => 'Photo',
                                        'minutes' => 'Minutes',
                                        'presentation' => 'Presentation',
                                    ])
                                    ->required(),
                                FileUpload::make('path')
                                    ->disk('public')
                                    ->directory('event_documents')
                                    ->preserveFilenames()
                                    ->required(),
                            ])
                            ->addActionLabel('Add Document')
                            ->columnSpanFull(),
                    ]),

                Section::make('Deliverables')
                    ->schema([
                        Repeater::make('deliverables')
                            ->relationship('deliverables')
                            ->schema([
                                TextInput::make('title')
                                    ->required(),
                                Textarea::make('description'),
                                DateTimePicker::make('due_date')
                                    ->label('Due date')
                                    ->required(),
                                Select::make('status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'completed' => 'Completed',
                                        'at_risk' => 'At Risk',
                                    ])
                                    ->required(),
                            ])
                            ->addActionLabel('Add Deliverable')
                            ->columnSpanFull(),
                    ]),

                Section::make('Themes')
                    ->schema([
                        Select::make('tags')
                            ->relationship('tags', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->label('Event Themes'),
                    ]),

                Section::make('Summary')
                    ->schema([
                        Textarea::make('successes')->label('Successes'),
                        Textarea::make('challenges')->label('Challenges'),
                        Textarea::make('next_steps')->label('Next Steps'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sn')
                    ->label('SN')
                    ->rowIndex(),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Description')
                    ->limit(60)
                    ->wrap(),
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
                TextColumn::make('category.name')
                    ->badge()
                    ->color(fn ($record) => match ($record->category->type) {
                        'official' => 'info',
                        'social' => 'success',
                        'urgent' => 'danger',
                        default => 'gray',
                    })
                    ->searchable(),
            ])
            ->filters([
                Filter::make('date_range')
                    ->label('Date range')
                    ->form([
                        DatePicker::make('from')->label('From'),
                        DatePicker::make('until')->label('To'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'] ?? null, fn ($q, $date) => $q->where('start_time', '>=', Carbon::parse($date)->startOfDay()))
                            ->when($data['until'] ?? null, fn ($q, $date) => $q->where('end_time', '<=', Carbon::parse($date)->endOfDay()));
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('export_pdf_record')
                    ->label('Export PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function (Event $record) {
                        $minister = MinisterProfile::first();
                        $photoPath = null;
                        if ($minister && $minister->photo_path) {
                            $relative = ltrim($minister->photo_path, '\\/');
                            $publicAbsolute = storage_path('app/public/'.$relative);
                            $privateAbsolute = storage_path('app/private/'.$relative);
                            if (is_file($publicAbsolute)) {
                                $photoPath = $publicAbsolute;
                            } elseif (is_file($privateAbsolute)) {
                                $photoPath = $privateAbsolute;
                            }
                        }
                        $photoUrl = null;
                        if ($photoPath) {
                            $mime = null;
                            if (function_exists('mime_content_type')) {
                                $mime = @mime_content_type($photoPath) ?: null;
                            }
                            if (! $mime) {
                                $ext = strtolower(pathinfo($photoPath, PATHINFO_EXTENSION));
                                $mime = match ($ext) {
                                    'png' => 'image/png',
                                    'gif' => 'image/gif',
                                    'webp' => 'image/webp',
                                    default => 'image/jpeg',
                                };
                            }
                            $data = @file_get_contents($photoPath);
                            if ($data !== false) {
                                $photoUrl = 'data:'.$mime.';base64,'.base64_encode($data);
                            } else {
                                $photoUrl = 'file:///'.str_replace('\\', '/', $photoPath);
                            }
                        }

                        $html = View::make('exports.event-summary-pdf', [
                            'event' => $record,
                            'minister' => $minister,
                            'ministerPhotoUrl' => $photoUrl,
                        ])->render();

                        $dompdf = class_exists('Dompdf\\Options')
                            ? new Dompdf(tap(new \Dompdf\Options, function ($o) {
                                $o->set('isRemoteEnabled', true);
                            }))
                            : new Dompdf;
                        if (method_exists($dompdf, 'set_option')) {
                            $dompdf->set_option('isRemoteEnabled', true);
                        }
                        $dompdf->loadHtml($html);
                        $dompdf->setPaper('a4', 'portrait');
                        $dompdf->render();

                        return response()->streamDownload(
                            function () use ($dompdf) {
                                echo $dompdf->output();
                            },
                            'event_'.$record->id.'.pdf',
                            [
                                'Content-Type' => 'application/pdf',
                            ]
                        );
                    }),
                Action::make('summary_report')
                    ->label('Summary')
                    ->icon('heroicon-o-document-text')
                    ->action(function (Event $record) {
                        $deliverablesTotal = $record->deliverables()->count();
                        $deliverablesCompleted = $record->deliverables()->where('status', 'completed')->count();
                        $tags = $record->tags()->pluck('name')->implode(', ');
                        $attendees = $record->attendees_manual ?: $record->attendees()->pluck('name')->implode(', ');
                        $html = '<html><head><meta charset="UTF-8"><title>Event Summary</title></head><body>';
                        $html .= '<h1>'.e($record->title).'</h1>';
                        $html .= '<p><strong>Category:</strong> '.e($record->category->name).'</p>';
                        $html .= '<p><strong>Themes:</strong> '.e($tags).'</p>';
                        $html .= '<p><strong>Location:</strong> '.e($record->location).'</p>';
                        $html .= '<p><strong>Schedule:</strong> '.$record->start_time->toDateTimeString().' — '.$record->end_time->toDateTimeString().'</p>';
                        $html .= '<p><strong>Attendees:</strong> '.e($attendees).'</p>';
                        $html .= '<h2>Deliverables</h2>';
                        $html .= '<p>'.$deliverablesCompleted.' of '.$deliverablesTotal.' completed</p>';
                        $html .= '<h2>Successes</h2><p>'.nl2br(e($record->successes)).'</p>';
                        $html .= '<h2>Challenges</h2><p>'.nl2br(e($record->challenges)).'</p>';
                        $html .= '<h2>Next Steps</h2><p>'.nl2br(e($record->next_steps)).'</p>';
                        $html .= '</body></html>';
                        $response = new StreamedResponse(function () use ($html) {
                            echo $html;
                        });
                        $response->headers->set('Content-Type', 'text/html; charset=UTF-8');
                        $response->headers->set('Content-Disposition', 'attachment; filename="event_summary.html"');

                        return $response;
                    }),
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
                Action::make('export_pdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function () {
                        $events = Event::with(['category', 'attendees'])->orderBy('start_time')->get();
                        $minister = MinisterProfile::first();
                        $photoPath = null;
                        if ($minister && $minister->photo_path) {
                            $relative = ltrim($minister->photo_path, '\\/');
                            $publicAbsolute = storage_path('app/public/'.$relative);
                            $privateAbsolute = storage_path('app/private/'.$relative);
                            if (is_file($publicAbsolute)) {
                                $photoPath = $publicAbsolute;
                            } elseif (is_file($privateAbsolute)) {
                                $photoPath = $privateAbsolute;
                            }
                        }
                        $photoUrl = null;
                        if ($photoPath) {
                            $mime = null;
                            if (function_exists('mime_content_type')) {
                                $mime = @mime_content_type($photoPath) ?: null;
                            }
                            if (! $mime) {
                                $ext = strtolower(pathinfo($photoPath, PATHINFO_EXTENSION));
                                $mime = match ($ext) {
                                    'png' => 'image/png',
                                    'gif' => 'image/gif',
                                    'webp' => 'image/webp',
                                    default => 'image/jpeg',
                                };
                            }
                            $data = @file_get_contents($photoPath);
                            if ($data !== false) {
                                $photoUrl = 'data:'.$mime.';base64,'.base64_encode($data);
                            } else {
                                $photoUrl = 'file:///'.str_replace('\\', '/', $photoPath);
                            }
                        }

                        $html = View::make('exports.events-pdf', [
                            'events' => $events,
                            'minister' => $minister,
                            'ministerPhotoUrl' => $photoUrl,
                        ])->render();

                        $dompdf = class_exists('Dompdf\\Options')
                            ? new Dompdf(tap(new \Dompdf\Options, function ($o) {
                                $o->set('isRemoteEnabled', true);
                            }))
                            : new Dompdf;
                        if (method_exists($dompdf, 'set_option')) {
                            $dompdf->set_option('isRemoteEnabled', true);
                        }
                        $dompdf->loadHtml($html);
                        $dompdf->setPaper('a4', 'portrait');
                        $dompdf->render();

                        return response()->streamDownload(
                            function () use ($dompdf) {
                                echo $dompdf->output();
                            },
                            'events.pdf',
                            [
                                'Content-Type' => 'application/pdf',
                            ]
                        );
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
