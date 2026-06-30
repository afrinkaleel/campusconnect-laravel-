@extends('layouts.app')
@section('content')
<div class="card">
    <h2>📋 My Resource Bookings
        <a href="{{ route('resources.bookForm') }}"
            class="btn btn-success"
            style="float:right;font-size:13px;">➕ New Booking</a>
    </h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($bookings->count() > 0)
    <table>
        <tr>
            <th>Resource</th><th>Date</th><th>Time</th>
            <th>Project</th><th>Status</th><th>Action</th>
        </tr>
        @foreach($bookings as $b)
        <tr>
            <td>{{ $b->resource?->name }}</td>
            <td>{{ $b->booking_date }}</td>
            <td>{{ $b->time_slot }}</td>
            <td>{{ $b->project?->title ?? '—' }}</td>
            <td><span class="badge badge-{{ $b->status }}">
                {{ ucfirst($b->status) }}</span></td>
            <td>
                @if($b->status === 'approved')
                <form method="POST"
                    action="{{ route('resources.return', $b->booking_id) }}">
                    @csrf
                    <button type="submit" class="btn btn-warning"
                        style="padding:5px 12px;font-size:13px;"
                        onclick="return confirm('Mark as returned?')">
                        Return
                    </button>
                </form>
                @else
                    <span style="color:#aaa;">—</span>
                @endif
            </td>
        </tr>
        @endforeach
    </table>
    @else
        <p style="color:#888;text-align:center;padding:20px;">
            No bookings yet.</p>
    @endif
</div>
@endsection