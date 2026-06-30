@extends('layouts.app')
@section('content')
<div class="card">
    <h2>✅ Manage Resource Bookings</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($bookings->count() > 0)
    <table>
        <tr>
            <th>Resource</th><th>Requested By</th><th>Date</th>
            <th>Time</th><th>Project</th><th>Status</th><th>Action</th>
        </tr>
        @foreach($bookings as $b)
        <tr>
            <td>{{ $b->resource?->name }}</td>
            <td>{{ $b->user?->name }}</td>
            <td>{{ $b->booking_date }}</td>
            <td>{{ $b->time_slot }}</td>
            <td>{{ $b->project?->title ?? '—' }}</td>
            <td><span class="badge badge-{{ $b->status }}">
                {{ ucfirst($b->status) }}</span></td>
            <td>
                @if($b->status === 'pending')
                <form method="POST"
                    action="{{ route('resources.handleBooking',
                                    $b->booking_id) }}"
                    style="display:inline;">
                    @csrf
                    <button name="action" value="approve"
                        class="btn btn-success"
                        style="padding:5px 10px;font-size:12px;margin-right:4px;"
                        onclick="return confirm('Approve?')">✅</button>
                    <button name="action" value="reject"
                        class="btn btn-danger"
                        style="padding:5px 10px;font-size:12px;"
                        onclick="return confirm('Reject?')">❌</button>
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