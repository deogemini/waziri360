<?php

namespace App\Observers;

use App\Models\Booking;
use App\Models\Event;
use App\Models\Category;
use Filament\Notifications\Notification;

class BookingObserver
{
    /**
     * Handle the Booking "updated" event.
     */
    public function updated(Booking $booking): void
    {
        if ($booking->wasChanged('status') && $booking->status === 'approved') {
            
            // Check if an event for this booking already exists to prevent duplicates
            // We can check by description or add a 'booking_id' column to events in the future.
            // For now, we'll check if an event exists at the same time with the same title.
            $exists = Event::where('start_time', $booking->requested_date)
                ->where('title', 'Meeting: ' . $booking->name)
                ->exists();

            if ($exists) {
                return;
            }

            $category = Category::firstOrCreate(
                ['name' => 'Appointments'],
                ['color' => '#10b981', 'type' => 'event']
            );

            Event::create([
                'title' => 'Meeting: ' . $booking->name,
                'description' => $booking->purpose . "\n\nContact: " . $booking->phone . " | " . $booking->email,
                'start_time' => $booking->requested_date,
                'end_time' => $booking->requested_date->copy()->addHour(),
                'category_id' => $category->id,
                'location' => 'Minister Office',
            ]);

            Notification::make()
                ->title('Added to Calendar')
                ->body('The approved booking has been automatically added to the calendar.')
                ->success()
                ->send();
        }
    }
}
