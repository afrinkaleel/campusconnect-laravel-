@extends('layouts.app')
@section('content')

<h2 style="margin-bottom:20px;">📊 CampusConnect Reports</h2>

{{-- System Overview --}}
<div class="card">
    <h2>🏫 System Overview</h2>
    <div class="stats-grid">
        <div class="stat-card">
            <div class="number">{{ $overview['total_users'] }}</div>
            <div class="label">Total Users</div>
        </div>
        <div class="stat-card">
            <div class="number">{{ $overview['total_students'] }}</div>
            <div class="label">Students</div>
        </div>
        <div class="stat-card">
            <div class="number">{{ $overview['total_lecturers'] }}</div>
            <div class="label">Lecturers</div>
        </div>
        <div class="stat-card">
            <div class="number">{{ $overview['total_projects'] }}</div>
            <div class="label">Total Projects</div>
        </div>
        <div class="stat-card">
            <div class="number">{{ $overview['total_bookings'] }}</div>
            <div class="label">Total Bookings</div>
        </div>
        <div class="stat-card">
            <div class="number">{{ $overview['total_leaves'] }}</div>
            <div class="label">Leave Requests</div>
        </div>
    </div>
</div>

{{-- Pending Approvals --}}
<div class="card">
    <h2>⏳ Pending Approvals Summary</h2>
    <div class="stats-grid" style="grid-template-columns:repeat(3,1fr);">
        <div class="stat-card" style="border-left-color:#fbbc04;">
            <div class="number" style="color:#fbbc04;">
                {{ $pending['bookings'] }}</div>
            <div class="label">Bookings Awaiting</div>
        </div>
        <div class="stat-card" style="border-left-color:#ea4335;">
            <div class="number" style="color:#ea4335;">
                {{ $pending['leaves'] }}</div>
            <div class="label">Leaves Pending</div>
        </div>
        <div class="stat-card" style="border-left-color:#9c27b0;">
            <div class="number" style="color:#9c27b0;">
                {{ $pending['projects'] }}</div>
            <div class="label">Projects Without Supervisor</div>
        </div>
    </div>
</div>

{{-- Project Status Report --}}
<div class="card">
    <h2>📁 Project Status Report</h2>

    {{-- Status Summary --}}
    <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:20px;">
        @foreach($projectStats as $ps)
        <div style="background:#f0f2f5;border-radius:8px;
                    padding:10px 20px;text-align:center;min-width:100px;">
            <div style="font-size:22px;font-weight:700;color:#1a73e8;">
                {{ $ps->total }}</div>
            <span class="badge badge-{{ $ps->status }}">
                {{ ucfirst($ps->status) }}</span>
        </div>
        @endforeach
    </div>

    {{-- Projects Table --}}
    @if($allProjects->count() > 0)
    <table>
        <tr>
            <th>#</th><th>Title</th><th>Student</th>
            <th>Supervisor</th><th>Status</th>
        </tr>
        @foreach($allProjects as $i => $p)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $p->title }}</td>
            <td>{{ $p->student?->name }}</td>
            <td>{{ $p->supervisor?->name
                ?? '<em style="color:#aaa;">Unassigned</em>' }}</td>
            <td><span class="badge badge-{{ $p->status }}">
                {{ ucfirst($p->status) }}</span></td>
        </tr>
        @endforeach
    </table>
    @else
        <p style="color:#888;">No projects yet.</p>
    @endif
</div>

{{-- Resource Utilization --}}
<div class="card">
    <h2>🔧 Resource Utilization Report</h2>
    @if($resources->count() > 0)
    <table>
        <tr>
            <th>Resource</th><th>Location</th><th>Total</th>
            <th>Available</th><th>Bookings</th>
            <th>Approved</th><th>Utilization</th>
        </tr>
        @foreach($resources as $r)
        @php
            $utilization = $r->quantity_total > 0
                ? round((($r->quantity_total - $r->quantity_available)
                    / $r->quantity_total) * 100)
                : 0;
            $barColor = $utilization > 75 ? '#ea4335'
                : ($utilization > 40 ? '#fbbc04' : '#34a853');
        @endphp
        <tr>
            <td>{{ $r->name }}</td>
            <td>{{ $r->location }}</td>
            <td>{{ $r->quantity_total }}</td>
            <td style="color:{{ $r->quantity_available > 0
                ? '#34a853' : '#ea4335' }};font-weight:bold;">
                {{ $r->quantity_available }}</td>
            <td>{{ $r->total_bookings }}</td>
            <td>{{ $r->approved_bookings }}</td>
            <td style="min-width:120px;">
                <div style="background:#eee;border-radius:10px;height:10px;">
                    <div style="width:{{ $utilization }}%;
                                background:{{ $barColor }};
                                height:10px;border-radius:10px;"></div>
                </div>
                <small>{{ $utilization }}%</small>
            </td>
        </tr>
        @endforeach
    </table>
    @else
        <p style="color:#888;">No resources added yet.</p>
    @endif
</div>

{{-- Staff Leave Report --}}
<div class="card">
    <h2>📝 Staff Leave Report ({{ date('Y') }})</h2>
    @if($leaveStats->count() > 0)
    <table>
        <tr>
            <th>Lecturer</th><th>Total Requests</th>
            <th>Days Taken</th><th>Remaining</th>
            <th>Pending</th><th>Balance</th>
        </tr>
        @foreach($leaveStats as $l)
        @php
            $balPct   = round(($l->remaining / 20) * 100);
            $balColor = $l->remaining < 5 ? '#ea4335'
                : ($l->remaining < 10 ? '#fbbc04' : '#34a853');
        @endphp
        <tr>
            <td>{{ $l->name }}</td>
            <td>{{ $l->total_requests }}</td>
            <td>{{ $l->days_taken }} / 20</td>
            <td style="color:{{ $balColor }};font-weight:bold;">
                {{ $l->remaining }} days</td>
            <td>{{ $l->pending_count }}</td>
            <td style="min-width:130px;">
                <div style="background:#eee;border-radius:10px;height:10px;">
                    <div style="width:{{ $balPct }}%;
                                background:{{ $balColor }};
                                height:10px;border-radius:10px;"></div>
                </div>
                <small>{{ $l->remaining }} of 20 days left</small>
            </td>
        </tr>
        @endforeach
    </table>
    @else
        <p style="color:#888;">No lecturers registered yet.</p>
    @endif
</div>

@endsection