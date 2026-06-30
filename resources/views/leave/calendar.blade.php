@extends('layouts.app')
@section('content')
@php
$typeColors = [
    'annual'    => ['bg'=>'#cce5ff','text'=>'#004085','label'=>'Annual'],
    'sick'      => ['bg'=>'#f8d7da','text'=>'#721c24','label'=>'Sick'],
    'emergency' => ['bg'=>'#fff3cd','text'=>'#856404','label'=>'Emergency'],
];
@endphp

<div class="card" style="padding:15px 25px;">
    <div style="display:flex;align-items:center;gap:15px;flex-wrap:wrap;">
        <strong>📅 Leave Calendar — {{ $monthName }} {{ $year }}</strong>
        <div style="display:flex;gap:10px;margin-left:auto;">
            @foreach($typeColors as $type => $col)
            <span style="background:{{ $col['bg'] }};color:{{ $col['text'] }};
                padding:3px 12px;border-radius:12px;font-size:13px;font-weight:600;">
                {{ $col['label'] }}
            </span>
            @endforeach
        </div>
    </div>
</div>

<div class="card" style="padding:20px;">
    <div style="display:flex;justify-content:space-between;
                align-items:center;margin-bottom:20px;">
        <a href="{{ route('leave.calendar',
                ['month'=>$month-1,'year'=>$year]) }}"
            class="btn btn-warning" style="padding:8px 18px;">← Prev</a>
        <h2 style="margin:0;color:#1a73e8;">{{ $monthName }} {{ $year }}</h2>
        <a href="{{ route('leave.calendar',
                ['month'=>$month+1,'year'=>$year]) }}"
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
        <div style="min-height:90px;background:#fafafa;border-radius:6px;"></div>
        @endfor

        @for($day = 1; $day <= $daysInMonth; $day++)
        @php
            $isToday   = ($day==date('j') && $month==date('n')
                          && $year==date('Y'));
            $dayLeaves = $leaveDays[$day] ?? [];
        @endphp
        <div style="min-height:90px;
                    background:{{ $isToday ? '#e8f0fe' : 'white' }};
                    border-radius:6px;padding:6px;
                    border:{{ $isToday
                        ? '2px solid #1a73e8' : '1px solid #eee' }};">
            <div style="font-weight:600;font-size:13px;
                        color:{{ $isToday ? '#1a73e8' : '#333' }};
                        margin-bottom:4px;">
                {{ $day }}
                @if($isToday)
                    <span style="font-size:10px;color:#1a73e8;">Today</span>
                @endif
            </div>
            @foreach($dayLeaves as $entry)
            @php $col = $typeColors[$entry['type']] ?? ['bg'=>'#e2e3e5','text'=>'#383d41']; @endphp
            <div style="background:{{ $col['bg'] }};color:{{ $col['text'] }};
                        border-radius:4px;padding:2px 5px;font-size:11px;
                        font-weight:600;margin-bottom:2px;overflow:hidden;
                        white-space:nowrap;text-overflow:ellipsis;"
                title="{{ $entry['name'] }} — {{ ucfirst($entry['type']) }} leave">
                {{ Str::limit($entry['name'], 10) }}
            </div>
            @endforeach
        </div>
        @endfor

        @php $rem = (7-(($firstDay-1+$daysInMonth)%7))%7; @endphp
        @for($i = 0; $i < $rem; $i++)
        <div style="min-height:90px;background:#fafafa;border-radius:6px;"></div>
        @endfor
    </div>
</div>

<div class="card">
    <h2>📋 Approved Leaves — {{ $monthName }} {{ $year }}</h2>
    @if($leavesList->count() > 0)
    <table>
        <tr><th>Lecturer</th><th>Type</th><th>From</th><th>To</th><th>Days</th></tr>
        @foreach($leavesList as $l)
        @php
            $days = \Carbon\Carbon::parse($l->start_date)
                        ->diffInDays($l->end_date) + 1;
            $col  = $typeColors[$l->leave_type]
                    ?? ['bg'=>'#e2e3e5','text'=>'#383d41','label'=>ucfirst($l->leave_type)];
        @endphp
        <tr>
            <td>{{ $l->lecturer?->name }}</td>
            <td><span style="background:{{ $col['bg'] }};color:{{ $col['text'] }};
                    padding:3px 10px;border-radius:10px;font-size:12px;font-weight:600;">
                {{ $col['label'] }}</span></td>
            <td>{{ $l->start_date }}</td>
            <td>{{ $l->end_date }}</td>
            <td>{{ $days }} day(s)</td>
        </tr>
        @endforeach
    </table>
    @else
        <p style="color:#888;text-align:center;padding:20px;">
            No approved leaves this month.</p>
    @endif
</div>
@endsection