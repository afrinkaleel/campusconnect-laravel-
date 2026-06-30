@extends('layouts.app')
@section('content')
<div class="card">
    <h2>🔔 All Notifications
        <span style="font-size:14px;color:#888;font-weight:normal;">
            ({{ $total }} total)
        </span>
    </h2>

    @if($notifications->count() > 0)
        @foreach($notifications as $n)
        <div style="padding:14px;margin-bottom:10px;border-radius:8px;
                    background:#f9f9f9;border-left:4px solid #1a73e8;
                    font-size:14px;">
            <div style="margin-bottom:5px;">
                {{ $n->message }}
            </div>
            <div style="font-size:12px;color:#aaa;">
                🕐 {{ $n->created_at->format('Y-m-d H:i') }}
            </div>
        </div>
        @endforeach
    @else
        <p style="color:#888;text-align:center;padding:30px;">
            No notifications yet.
        </p>
    @endif
</div>
@endsection