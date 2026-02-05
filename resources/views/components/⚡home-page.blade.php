<?php

use Livewire\Component;
use App\Models\MinisterProfile;
use App\Models\Event;
use App\Models\Booking;
use Filament\Notifications\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

new class extends Component
{
    public $minister;
    public $upcomingEvents = [];
    public $name;
    public $email;
    public $phone;
    public $purpose;
    public $requested_date;

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email',
        'phone' => 'required',
        'purpose' => 'required|min:10',
        'requested_date' => 'required|date|after:now',
    ];

    public function mount()
    {
        $this->minister = MinisterProfile::first();
        $this->upcomingEvents = Event::where('start_time', '>=', now())
            ->orderBy('start_time', 'asc')
            ->take(5)
            ->get();
    }

    public function submitBooking()
    {
        $this->validate();

        Booking::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'purpose' => $this->purpose,
            'requested_date' => $this->requested_date,
            'status' => 'pending',
        ]);

        $this->reset(['name', 'email', 'phone', 'purpose', 'requested_date']);

        session()->flash('message', 'Appointment request submitted successfully. You will be notified once approved.');
    }
};
?>

<div>
    <!-- Hero Section -->
    <section class="bg-blue-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-10 md:mb-0">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Welcome to Waziri360</h1>
                <p class="text-xl md:text-2xl mb-6 font-light">
                    {{ $minister->title ?? 'Minister of Waziri wa Nchi Ofisi ya Waziri Mkuu Kazi, Ajira na Mahusiano' }}
                </p>
                <p class="text-lg text-blue-100">
                    Streamlining schedules and engagements for efficient governance.
                </p>
            </div>
            <div class="md:w-1/2 flex justify-center">
                @if($minister && $minister->photo_path)
                    <img src="{{ Storage::url($minister->photo_path) }}" alt="{{ $minister->name }}" class="rounded-full w-64 h-64 object-cover border-4 border-white shadow-lg">
                @else
                    <div class="rounded-full w-64 h-64 bg-blue-400 flex items-center justify-center border-4 border-white shadow-lg">
                        <span class="text-6xl">👤</span>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Content Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">

            <!-- Calendar / Availability Section -->
            <div id="calendar">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">Upcoming Public Engagements</h2>
                <div class="bg-white rounded-lg shadow-md p-6">
                    @if(count($upcomingEvents) > 0)
                        <ul class="space-y-4">
                            @foreach($upcomingEvents as $event)
                                <li class="border-b pb-4 last:border-b-0 last:pb-0">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3 text-center w-16">
                                            <span class="block text-sm font-bold text-blue-600">{{ $event->start_time->format('M') }}</span>
                                            <span class="block text-xl font-bold text-gray-800">{{ $event->start_time->format('d') }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $event->title }}</h3>
                                            <p class="text-gray-500">{{ $event->start_time->format('h:i A') }} - {{ $event->end_time->format('h:i A') }}</p>
                                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full mt-1
                                                {{ $event->category->type === 'official' ? 'bg-blue-100 text-blue-800' :
                                                   ($event->category->type === 'urgent' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800') }}">
                                                {{ ucfirst($event->category->name) }}
                                            </span>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500 text-center py-8">No upcoming public events scheduled at the moment.</p>
                    @endif
                </div>
            </div>

            <!-- Booking Form Section -->
            <div id="book">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">Book an Appointment</h2>
                <div class="bg-white rounded-lg shadow-md p-8">
                    @if (session()->has('message'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                            {{ session('message') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="submitBooking">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Full Name</label>
                            <input wire:model="name" type="text" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email Address</label>
                            <input wire:model="email" type="email" id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror">
                            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="phone">Phone Number</label>
                            <input wire:model="phone" type="text" id="phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('phone') border-red-500 @enderror">
                            @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="date">Requested Date & Time</label>
                            <input wire:model="requested_date" type="datetime-local" id="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('requested_date') border-red-500 @enderror">
                            @error('requested_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="purpose">Purpose of Meeting</label>
                            <textarea wire:model="purpose" id="purpose" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('purpose') border-red-500 @enderror"></textarea>
                            @error('purpose') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-center justify-end">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition">
                                Submit Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
