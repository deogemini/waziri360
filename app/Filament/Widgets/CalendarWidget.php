<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Widgets\Widget;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Contracts\HasActions;
use Illuminate\Support\Collection;

class CalendarWidget extends Widget implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    protected string $view = 'filament.widgets.calendar-widget';
    protected int | string | array $columnSpan = 'full';

    public string $currentDate;
    public string $viewType = 'month'; // 'month', 'week', 'day'
    public string $searchDate = '';

    protected ?Collection $events = null;

    public function mount(): void
    {
        $this->currentDate = now()->toDateString();
    }

    public function getCarbonDateProperty(): Carbon
    {
        return Carbon::parse($this->currentDate);
    }

    public function next(): void
    {
        $date = $this->carbonDate;

        match ($this->viewType) {
            'month' => $date->addMonth(),
            'week' => $date->addWeek(),
            'day' => $date->addDay(),
        };

        $this->currentDate = $date->toDateString();
        $this->events = null;
    }

    public function previous(): void
    {
        $date = $this->carbonDate;

        match ($this->viewType) {
            'month' => $date->subMonth(),
            'week' => $date->subWeek(),
            'day' => $date->subDay(),
        };

        $this->currentDate = $date->toDateString();
        $this->events = null;
    }

    public function today(): void
    {
        $this->currentDate = now()->toDateString();
        $this->events = null;
    }

    public function setViewType(string $type): void
    {
        $this->viewType = $type;
        $this->events = null;
    }

    public function getDaysProperty(): array
    {
        $date = $this->carbonDate;
        $days = [];

        if ($this->viewType === 'month') {
            $startOfCalendar = $date->copy()->startOfMonth()->startOfWeek(Carbon::SUNDAY);
            $endOfCalendar = $date->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);
        } elseif ($this->viewType === 'week') {
            $startOfCalendar = $date->copy()->startOfWeek(Carbon::SUNDAY);
            $endOfCalendar = $date->copy()->endOfWeek(Carbon::SUNDAY);
        } else { // day
            $startOfCalendar = $date->copy();
            $endOfCalendar = $date->copy();
        }

        $current = $startOfCalendar->copy();

        while ($current <= $endOfCalendar) {
            $days[] = $current->copy();
            $current->addDay();
        }

        return $days;
    }

    public function getEvents(): Collection
    {
        if ($this->events) {
            return $this->events;
        }

        $date = $this->carbonDate;

        if ($this->viewType === 'month') {
            $start = $date->copy()->startOfMonth()->startOfWeek(Carbon::SUNDAY);
            $end = $date->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);
        } elseif ($this->viewType === 'week') {
            $start = $date->copy()->startOfWeek(Carbon::SUNDAY);
            $end = $date->copy()->endOfWeek(Carbon::SUNDAY);
        } else {
            $start = $date->copy()->startOfDay();
            $end = $date->copy()->endOfDay();
        }

        return $this->events = Event::query()
            ->whereBetween('start_time', [$start, $end])
            ->orderBy('start_time')
            ->get();
    }

    public function getEventsForDate(Carbon $date): Collection
    {
        return $this->getEvents()->filter(function ($event) use ($date) {
            return $event->start_time->isSameDay($date);
        });
    }

    public function updatedSearchDate($value): void
    {
        if ($value) {
            $this->currentDate = Carbon::parse($value)->toDateString();
            $this->viewType = 'day';
            $this->events = null;
        }
    }

    public function createEventAction(): Action
    {
        return CreateAction::make('createEvent')
            ->model(Event::class)
            ->form([
                Forms\Components\TextInput::make('title')->required(),
                Forms\Components\Textarea::make('description'),
                Forms\Components\DateTimePicker::make('start_time')->required(),
                Forms\Components\DateTimePicker::make('end_time')->required(),
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                Forms\Components\TextInput::make('location'),
            ])
            ->mountUsing(function ($form, array $arguments) {
                $form->fill([
                    'start_time' => $arguments['start_time'] ?? now(),
                    'end_time' => isset($arguments['start_time']) ? Carbon::parse($arguments['start_time'])->addHour() : now()->addHour(),
                ]);
            })
            ->successNotificationTitle('Event created');
    }

    public function viewEventAction(): Action
    {
        return ViewAction::make('viewEvent')
            ->model(Event::class)
            ->record(function (array $arguments) {
                return Event::find($arguments['id'] ?? null);
            })
            ->form([
                Forms\Components\TextInput::make('title'),
                Forms\Components\Textarea::make('description'),
                Forms\Components\DateTimePicker::make('start_time'),
                Forms\Components\DateTimePicker::make('end_time'),
                Forms\Components\TextInput::make('location'),
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name'),
            ]);
    }

    public function downloadSampleAction(): Action
    {
        return Action::make('downloadSample')
            ->label('Download Sample CSV')
            ->color('gray')
            ->icon('heroicon-o-arrow-down-tray')
            ->link()
            ->action(function () {
                $headers = ['Title', 'Description', 'Start Time', 'End Time', 'Category', 'Location'];
                $data = [
                    ['Team Meeting', 'Discuss weekly progress', '2024-01-01 10:00', '2024-01-01 11:00', 'Internal', 'Conference Room A'],
                    ['Client Call', 'Project kickoff', '2024-01-02 14:00', '2024-01-02 15:00', 'External', 'Zoom'],
                ];

                $callback = function () use ($headers, $data) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $headers);
                    foreach ($data as $row) {
                        fputcsv($file, $row);
                    }
                    fclose($file);
                };

                return response()->streamDownload($callback, 'events_sample.csv');
            });
    }

    public function importEventsAction(): Action
    {
        return Action::make('importEvents')
            ->label('Import Events')
            ->color('success')
            ->icon('heroicon-o-arrow-up-tray')
            ->form([
                Forms\Components\FileUpload::make('attachment')
                    ->label('Upload CSV File')
                    ->acceptedFileTypes(['text/csv', 'text/plain', 'application/vnd.ms-excel'])
                    ->required()
                    ->disk('public')
                    ->directory('temp_imports')
                    ->helperText('Upload a CSV file. Click "Download Sample CSV" below to see the format.'),
            ])
            ->extraModalFooterActions([
                $this->downloadSampleAction(),
            ])
            ->action(function (array $data) {
                $file = $data['attachment'];
                $path = \Illuminate\Support\Facades\Storage::disk('public')->path($file);

                if (($handle = fopen($path, "r")) !== FALSE) {
                    // Skip header
                    fgetcsv($handle, 1000, ",");

                    while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        // Expected: Title, Description, Start Time, End Time, Category, Location
                        if (count($row) < 5) continue;

                        $categoryName = $row[4] ?? 'General';
                        $category = \App\Models\Category::firstOrCreate(
                            ['name' => $categoryName],
                            ['color' => '#6b7280', 'type' => 'event']
                        );

                        try {
                            Event::create([
                                'title' => $row[0],
                                'description' => $row[1] ?? null,
                                'start_time' => Carbon::parse($row[2]),
                                'end_time' => Carbon::parse($row[3]),
                                'category_id' => $category->id,
                                'location' => $row[5] ?? null,
                            ]);
                        } catch (\Exception $e) {
                            // Skip invalid rows or log them
                            continue;
                        }
                    }
                    fclose($handle);
                }

                \Filament\Notifications\Notification::make()
                    ->title('Events imported successfully')
                    ->success()
                    ->send();

                // Cleanup
                \Illuminate\Support\Facades\Storage::disk('public')->delete($file);
            });
    }
}
