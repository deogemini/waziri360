<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Summary</title>
    <style>
        body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 12px; color: #111827; }
        h1 { font-size: 22px; margin-bottom: 6px; }
        h2 { font-size: 16px; margin-top: 18px; margin-bottom: 6px; }
        .meta { margin-bottom: 10px; }
        .meta p { margin: 2px 0; }
        .muted { color: #6b7280; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #e5e7eb; padding: 6px; text-align: left; }
        th { background: #f3f4f6; }
    </style>
</head>
<body>
    @if(!empty($ministerPhotoUrl))
        <div style="text-align:center; margin-bottom: 10px;">
            <img src="{{ $ministerPhotoUrl }}" alt="Minister" style="height: 120px; width: 120px; object-fit: cover; border-radius: 60px; border: 2px solid #e5e7eb;">
            <div style="margin-top:6px; font-weight: bold;">
                {{ $minister->name ?? '' }}
            </div>
            <div class="muted">{{ $minister->title ?? '' }}</div>
        </div>
    @endif
    <h1>{{ $event->title }}</h1>
    <div class="meta">
        <p><strong>Category:</strong> {{ $event->category?->name }}</p>
        <p><strong>Schedule:</strong> {{ $event->start_time?->format('j/n/Y g:i:s A') }} — {{ $event->end_time?->format('j/n/Y g:i:s A') }}</p>
        <p><strong>Location:</strong> {{ $event->location }}</p>
        <p><strong>Attendees:</strong> {{ $event->attendees_manual ?: $event->attendees?->pluck('name')->implode(', ') }}</p>
    </div>
    <h2>Description</h2>
    <p>{{ $event->description }}</p>
    <h2>Deliverables</h2>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Status</th>
                <th>Due Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($event->deliverables as $d)
                <tr>
                    <td>{{ $d->title }}</td>
                    <td>{{ ucfirst($d->status) }}</td>
                    <td>{{ optional($d->due_date)->format('j/n/Y g:i:s A') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <h2>Summary</h2>
    <p><strong>Successes:</strong> {{ $event->successes }}</p>
    <p><strong>Challenges:</strong> {{ $event->challenges }}</p>
    <p><strong>Next Steps:</strong> {{ $event->next_steps }}</p>
    <p class="muted">Generated on {{ now()->format('j/n/Y g:i:s A') }}</p>
</body>
</html>
