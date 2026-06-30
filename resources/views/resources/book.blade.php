@extends('layouts.app')
@section('content')
<div class="card">
    <h2>🔧 Book a Resource</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    @if($resources->count() > 0)
    <form method="POST" action="{{ route('resources.book') }}">
        @csrf
        <div class="form-group">
            <label>Select Resource</label>
            <select name="resource_id" required>
                <option value="">-- Select a resource --</option>
                @foreach($resources as $r)
                <option value="{{ $r->resource_id }}">
                    {{ $r->name }}
                    (Available: {{ $r->quantity_available }})
                    — {{ $r->location }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Booking Date</label>
            <input type="date" name="booking_date"
                min="{{ date('Y-m-d') }}" required>
        </div>
        <div class="form-group">
            <label>Time Slot</label>
            <select name="time_slot" required>
                <option value="">-- Select time slot --</option>
                <option value="8:00-10:00">8:00 AM – 10:00 AM</option>
                <option value="10:00-12:00">10:00 AM – 12:00 PM</option>
                <option value="12:00-14:00">12:00 PM – 2:00 PM</option>
                <option value="14:00-16:00">2:00 PM – 4:00 PM</option>
                <option value="16:00-18:00">4:00 PM – 6:00 PM</option>
            </select>
        </div>
        <div class="form-group">
            <label>Link to Project
                <span style="color:#aaa;font-weight:normal;">(optional)</span>
            </label>
            <select name="project_id">
                <option value="">-- No project --</option>
                @foreach($projects as $p)
                <option value="{{ $p->project_id }}">{{ $p->title }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">
            Submit Booking Request
        </button>
        <a href="{{ route('resources.myBookings') }}"
            class="btn btn-warning" style="margin-left:10px;">
            My Bookings
        </a>
    </form>
    @else
        <p style="color:#888;text-align:center;padding:20px;">
            No resources available right now.</p>
    @endif
</div>
@endsection