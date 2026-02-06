<x-filament-widgets::widget>
    <style>
        .fc-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 1rem; }
        .fc-header-title { font-size: 1.25rem; font-weight: 700; color: #111827; }
        .fc-actions { display: flex; align-items: center; gap: 0.5rem; }
        .fc-view-switcher { display: flex; align-items: center; background-color: #f3f4f6; border-radius: 0.5rem; padding: 0.25rem; }
        .fc-view-btn { padding: 0.25rem 0.75rem; font-size: 0.875rem; font-weight: 500; border-radius: 0.375rem; transition: all 0.2s; border: none; cursor: pointer; background: transparent; color: #6b7280; }
        .fc-view-btn.active { background-color: white; color: #d97706; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
        .fc-view-btn:hover:not(.active) { color: #374151; }

        .fc-grid-wrapper { border: 1px solid #e5e7eb; border-radius: 0.5rem; overflow: hidden; background-color: #e5e7eb; }
        .fc-grid { display: grid; gap: 1px; }
        .fc-grid-cols-7 { grid-template-columns: repeat(7, 1fr); }
        .fc-grid-cols-1 { grid-template-columns: 1fr; }
        
        .fc-day-header { background-color: #f9fafb; padding: 0.5rem; text-align: center; font-size: 0.875rem; font-weight: 600; color: #374151; }
        
        .fc-day { background-color: white; padding: 0.5rem; position: relative; display: flex; flex-direction: column; }
        .fc-day:hover { background-color: #f9fafb; }
        .fc-day-other { background-color: #f9fafb; color: #9ca3af; }
        
        .fc-day-header-row { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem; }
        .fc-date-num { font-size: 0.875rem; font-weight: 500; width: 1.75rem; height: 1.75rem; display: flex; align-items: center; justify-content: center; border-radius: 9999px; color: #374151; }
        .fc-date-num.today { background-color: #d97706; color: white; }
        
        .fc-add-btn { font-size: 0.75rem; color: #d97706; background-color: #fffbeb; padding: 0.125rem 0.5rem; border-radius: 0.25rem; border: none; cursor: pointer; font-weight: 500; opacity: 0; transition: opacity 0.2s; }
        .fc-day:hover .fc-add-btn { opacity: 1; }
        
        .fc-events-list { flex: 1; overflow-y: auto; display: flex; flex-direction: column; gap: 0.25rem; }
        .fc-event-item { font-size: 0.75rem; padding: 0.25rem 0.375rem; border-radius: 0.25rem; background-color: #fffbeb; color: #b45309; border-left: 2px solid #f59e0b; cursor: pointer; display: flex; align-items: center; gap: 0.25rem; white-space: nowrap; overflow: hidden; }
        .fc-event-item:hover { opacity: 0.8; }
        .fc-event-time { font-weight: 600; opacity: 0.75; }
        .fc-event-title { font-weight: 600; text-overflow: ellipsis; overflow: hidden; }
        .fc-empty-text { font-size: 0.75rem; color: #9ca3af; font-style: italic; text-align: center; margin-top: 1rem; }

        /* Dark Mode Support */
        @media (prefers-color-scheme: dark) {
            .fc-header-title { color: white; }
            .fc-view-switcher { background-color: #1f2937; }
            .fc-view-btn { color: #9ca3af; }
            .fc-view-btn.active { background-color: #374151; color: #fbbf24; }
            .fc-view-btn:hover:not(.active) { color: #e5e7eb; }
            .fc-grid-wrapper { border-color: #374151; background-color: #374151; }
            .fc-day-header { background-color: #1f2937; color: #d1d5db; }
            .fc-day { background-color: #111827; }
            .fc-day:hover { background-color: #1f2937; }
            .fc-day-other { background-color: #1f2937; color: #6b7280; }
            .fc-date-num { color: #d1d5db; }
            .fc-date-num.today { background-color: #d97706; color: white; }
            .fc-add-btn { background-color: rgba(245, 158, 11, 0.2); color: #fbbf24; }
            .fc-event-item { background-color: rgba(245, 158, 11, 0.1); color: #fbbf24; }
        }
    </style>

    <div class="fi-section p-6 bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 rounded-xl">
        
        {{-- Header --}}
        <div class="fc-header">
            <div class="flex items-center gap-2">
                <h2 class="fc-header-title">
                    @if($viewType === 'day')
                        {{ $this->carbonDate->format('F j, Y') }}
                    @else
                        {{ $this->carbonDate->format('F Y') }}
                    @endif
                </h2>
            </div>
            
            <div class="fc-actions">
                <x-filament::button color="gray" size="sm" wire:click="previous">
                    Previous
                </x-filament::button>
                <x-filament::button color="gray" size="sm" wire:click="today">
                    Today
                </x-filament::button>
                <x-filament::button color="gray" size="sm" wire:click="next">
                    Next
                </x-filament::button>
            </div>

            <div class="fc-view-switcher">
                @foreach(['month', 'week', 'day'] as $type)
                    <button
                        wire:click="setViewType('{{ $type }}')"
                        class="fc-view-btn {{ $viewType === $type ? 'active' : '' }}"
                    >
                        {{ ucfirst($type) }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Calendar Grid --}}
        <div class="fc-grid-wrapper">
            <div class="fc-grid {{ $viewType === 'day' ? 'fc-grid-cols-1' : 'fc-grid-cols-7' }}">
                
                {{-- Days Header --}}
                @if($viewType !== 'day')
                    @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                        <div class="fc-day-header">
                            {{ $day }}
                        </div>
                    @endforeach
                @endif

                {{-- Days --}}
                @foreach($this->days as $day)
                    @php
                        $isCurrentMonth = $day->month === $this->carbonDate->month;
                        $isToday = $day->isToday();
                        $events = $this->getEventsForDate($day);
                        $hasEvents = $events->isNotEmpty();
                    @endphp

                    <div 
                        class="fc-day {{ $viewType === 'month' && !$isCurrentMonth ? 'fc-day-other' : '' }}"
                        style="min-height: {{ $viewType === 'month' ? '120px' : ($viewType === 'week' ? '300px' : '500px') }}"
                    >
                        {{-- Date Number --}}
                        <div class="fc-day-header-row">
                            <div class="flex items-center gap-2">
                                <span class="fc-date-num {{ $isToday ? 'today' : '' }}">
                                    {{ $day->day }}
                                </span>
                                @if($viewType === 'day')
                                    <span style="font-size: 1.125rem; font-weight: 600; color: inherit;">{{ $day->format('l') }}</span>
                                @endif
                            </div>
                            
                            {{-- Add Button --}}
                            <button 
                                wire:click="mountAction('createEvent', { start_time: '{{ $day->toDateTimeString() }}' })"
                                class="fc-add-btn"
                                style="{{ $viewType !== 'month' ? 'opacity: 1' : '' }}"
                            >
                                + Add
                            </button>
                        </div>

                        {{-- Events List --}}
                        <div class="fc-events-list">
                            @foreach($events as $event)
                                <div 
                                    wire:click="mountAction('viewEvent', { id: {{ $event->id }} })"
                                    class="fc-event-item"
                                    title="{{ $event->title }}"
                                >
                                    <span class="fc-event-time">{{ $event->start_time->format('H:i') }}</span>
                                    <span class="fc-event-title">{{ $event->title }}</span>
                                </div>
                            @endforeach
                            
                            @if(!$hasEvents)
                                <div class="fc-empty-text">
                                    {{ $viewType === 'day' ? 'No events scheduled.' : 'Open' }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
        <x-filament-actions::modals />
    </div>
</x-filament-widgets::widget>
