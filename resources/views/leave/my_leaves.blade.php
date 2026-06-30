@extends('layouts.app')
@section('content')
<div class="card">
    <h2>📋 My Leave Requests
        <a href="{{ route('leave.applyForm') }}"
            class="btn btn-success"
            style="float:right;font-size:13px;">➕ Apply Leave</a>
    </h2>
    @if($leaves->count() > 0)
    <table>
        <tr>
            <th>#</th><th>Type</th><th>From</th>
            <th>To</th><th>Days</th><th>Reason</th><th>Status</th>
        </tr>
        @foreach($leaves as $i => $l)
        @php
            $days = \Carbon\Carbon::parse($l->start_date)
                        ->diffInDays($l->end_date) + 1;
        @endphp
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ ucfirst($l->leave_type) }}</td>
            <td>{{ $l->start_date }}</td>
            <td>{{ $l->end_date }}</td>
            <td>{{ $days }}</td>
            <td style="max-width:200px;font-size:13px;">
                {{ $l->reason }}</td>
            <td><span class="badge badge-{{ $l->status }}">
                {{ ucfirst($l->status) }}</span></td>
        </tr>
        @endforeach
    </table>
    @else
        <p style="color:#888;text-align:center;padding:20px;">
            No leave requests yet.</p>
    @endif
</div>
@endsection