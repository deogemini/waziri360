<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Events</title>
    <style>
        body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 12px; color: #111827; }
        h1 { font-size: 20px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #e5e7eb; padding: 8px; text-align: left; }
        th { background: #f3f4f6; }
        .small { font-size: 11px; color: #6b7280; }
    </style>
    </head>
<body>
    @if(!empty($ministerPhotoUrl))
        <div style="text-align:center; margin-bottom: 10px;">
            <img src="{{ $ministerPhotoUrl }}" alt="Minister" style="height: 120px; width: 120px; object-fit: cover; border-radius: 60px; border: 2px solid #e5e7eb;">
            <div style="margin-top:6px; font-weight: bold;">
                {{ $minister->name ?? '' }}
            </div>
            <div class="small">{{ $minister->title ?? '' }}</div>
        </div>
    @endif
    <h1>Events</h1>
    <table>
        <thead>
            <tr>
                <th>SN</th>
                <th>Title</th>
                <th>Category</th>
                <th>Start</th>
                <th>End</th>
                <th>Location</th>
                <th>Attendees</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $event->title }}</td>
                    <td>{{ $event->category?->name }}</td>
                    <td>{{ $event->start_time?->format('Y-m-d H:i') }}</td>
                    <td>{{ $event->end_time?->format('Y-m-d H:i') }}</td>
                    <td>{{ $event->location }}</td>
                    <td>{{ $event->attendees?->count() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p class="small">Generated on {{ now()->format('Y-m-d H:i') }}</p>
</body>
</html>
