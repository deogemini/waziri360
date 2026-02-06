<?php

namespace App\Observers;

use App\Models\Booking;
use App\Models\Event;
use App\Models\Category;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class BookingObserver
{
    /**
     * Handle the Booking "updated" event.
     */
    public function updated(Booking $booking): void
    {
        Log::info('Booking updated observer fired', ['id' => $booking->id, 'status' => $booking->status, 'was_changed' => $booking->wasChanged('status')]);

        if ($booking->wasChanged('status') && $booking->status === 'approved') {
            
            Log::info('Booking status changed to approved. Proceeding to create event.');

            // Check if an event for this booking already exists to prevent duplicates
            // We can check by description or add a 'booking_id' column to events in the future.
            // For now, we'll check if an event exists at the same time with the same title.
            $exists = Event::where('start_time', $booking->requested_date)
                ->where('title', 'Meeting: ' . $booking->name)
                ->exists();

            if ($exists) {
                Log::info('Event already exists for this booking.');
                return;
            }

            try {
                $category = Category::firstOrCreate(
                    ['name' => 'Appointments'],
                    ['color' => '#10b981', 'type' => 'event']
                );
    
                $event = Event::create([
                    'title' => 'Meeting: ' . $booking->name,
                    'description' => $booking->purpose . "\n\nContact: " . $booking->phone . " | " . $booking->email,
                    'start_time' => $booking->requested_date,
                    'end_time' => $booking->requested_date->copy()->addHour(),
                    'category_id' => $category->id,
                    'location' => 'Minister Office',
                ]);

                Log::info('Event created successfully', ['event_id' => $event->id]);
    
                Notification::make()
                    ->title('Added to Calendar')
                    ->body('The approved booking has been automatically added to the calendar.')
                    ->success()
                    ->send();
            } catch (\Exception $e) {
                Log::error('Failed to create event from booking: ' . $e->getMessage());
            }
        }
    }
}
