@extends('layouts.app')
@section('content')
<div class="card" style="padding:15px 25px;">
    <div style="display:flex;align-items:center;gap:15px;flex-wrap:wrap;">
        <strong>🔧 Resource Booking Calendar — {{ $monthName }} {{ $year }}</strong>
        <form method="GET" action="{{ route('resources.calendar') }}"
            style="display:flex;gap:8px;align-items:center;margin-left:auto;">
            <input type="hidden" name="month" value="{{ $month }}">
            <input type="hidden" name="year" value="{{ $year }}">
            <select name="resource_id" onchange="this.form.submit()"
                style="padding:6px 12px;border:1px solid #ddd;
                       border-radius:6px;font-size:13px;">
                <option value="0">All Resources</option>
                @foreach($resources as $r)
                <option value="{{ $r->resource_id }}"
                    {{ $filterResource == $r->resource_id ? 'selected' : '' }}>
                    {{ $r->name }}
                </option>
                @endforeach
            </select>
        </form>
    </div>
</div>

<div class="card" style="padding:20px;">
    <div style="display:flex;justify-content:space-between;
                align-items:center;margin-bottom:20px;">
        <a href="{{ route('resources.calendar',
                ['month'=>$month-1,'year'=>$year,
                 'resource_id'=>$filterResource]) }}"
            class="btn btn-warning" style="padding:8px 18px;">← Prev</a>
        <h2 style="margin:0;color:#1a73e8;">{{ $monthName }} {{ $year }}</h2>
        <a href="{{ route('resources.calendar',
                ['month'=>$month+1,'year'=>$year,
                 'resource_id'=>$filterResource]) }}"
            class="btn btn-warning" style="padding:8px 18px;">Next →</a>
    </div>

    <div style="display:grid;grid-template-columns:repeat(7,1fr);
                gap:4px;margin-bottom:4px;">
        @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $d)
        <div style="text-align:center;font-weight:600;font-size:13px;
                    color:#666;padding:8px 0;background:#f0f2f5;
                    border-radius:6px;">{{ $d }}</div>
        @endforeach
    </div>

    <div style="display:grid;grid-template-columns:repeat(7,1fr);gap:4px;">
        @for($i = 1; $i < $firstDay; $i++)
            <div style="min-height:80px;background:#fafafa;border-radius:6px;"></div>
        @endfor

        @for($day = 1; $day <= $daysInMonth; $day++)
        @php
            $isToday = ($day == date('j') && $month == date('n')
                        && $year == date('Y'));
            $dayBookings = $bookings->get((string)$day, collect());
        @endphp
        <div style="min-height:80px;background:{{ $isToday ? '#e8f0fe' : 'white' }};
                    border-radius:6px;padding:6px;
                    border:{{ $isToday ? '2px solid #1a73e8' : '1px solid #eee' }};">
            <div style="font-weight:600;font-size:13px;
                        color:{{ $isToday ? '#1a73e8' : '#333' }};
                        margin-bottom:4px;display:flex;
                        justify-content:space-between;">
                {{ $day }}
                @if($dayBookings->count() > 0)
                <span style="background:#1a73e8;color:white;border-radius:10px;
                             padding:1px 6px;font-size:10px;">
                    {{ $dayBookings->count() }}
                </span>
                @endif
            </div>
            @foreach($dayBookings as $b)
            <div style="background:#cce5ff;color:#004085;border-radius:4px;
                        padding:2px 5px;font-size:11px;font-weight:600;
                        margin-bottom:2px;overflow:hidden;white-space:nowrap;
                        text-overflow:ellipsis;"
                title="{{ $b->user?->name }} booked {{ $b->resource?->name }}
                       at {{ $b->time_slot }}">
                {{ Str::limit($b->user?->name, 8) }}
                — {{ Str::limit($b->resource?->name, 8) }}
            </div>
            @endforeach
        </div>
        @endfor

        @php
            $total = $firstDay - 1 + $daysInMonth;
            $rem   = (7 - ($total % 7)) % 7;
        @endphp
        @for($i = 0; $i < $rem; $i++)
            <div style="min-height:80px;background:#fafafa;border-radius:6px;"></div>
        @endfor
    </div>
</div>
@endsection