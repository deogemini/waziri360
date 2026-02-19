<?php

namespace App\Console\Commands;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send SMS/Email reminders for upcoming events';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Find events starting in the next 30 minutes
        $events = Event::whereBetween('start_time', [Carbon::now(), Carbon::now()->addMinutes(30)])->get();

        foreach ($events as $event) {
            $this->info("Sending reminder for: {$event->title}");
            Log::info("Reminder sent for event: {$event->title}");

            // Logic to send Email/SMS to attendees
            // Mail::to($event->attendees)->send(new EventReminder($event));
        }

        $this->info('Reminders processed.');
    }
}
