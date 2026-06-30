@extends('layouts.app')

@section('content')
<h2 style="margin-bottom:20px;">👋 Welcome, {{ $user->name }}!</h2>

<div class="stats-grid">
    <div class="stat-card">
        <div class="number">{{ $stats['total_projects'] }}</div>
        <div class="label">Total Projects</div>
    </div>
    <div class="stat-card">
        <div class="number">{{ $stats['total_students'] }}</div>
        <div class="label">Students</div>
    </div>
    <div class="stat-card">
        <div class="number">{{ $stats['total_lecturers'] }}</div>
        <div class="label">Lecturers</div>
    </div>
    <div class="stat-card">
        <div class="number">{{ $stats['total_resources'] }}</div>
        <div class="label">Lab Resources</div>
    </div>
    <div class="stat-card" style="border-left-color:#fbbc04;">
        <div class="number" style="color:#fbbc04;">{{ $stats['pending_bookings'] }}</div>
        <div class="label">Pending Bookings</div>
    </div>
    <div class="stat-card" style="border-left-color:#ea4335;">
        <div class="number" style="color:#ea4335;">{{ $stats['pending_leaves'] }}</div>
        <div class="label">Pending Leaves</div>
    </div>
</div>

<div class="card">
    <h2>⚡ Quick Actions</h2>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;">
        <div style="background:#f0f2f5;border-radius:10px;padding:15px;text-align:center;">
            <div style="font-size:22px;margin-bottom:8px;">📁</div>
            <div style="font-weight:600;font-size:13px;color:#444;margin-bottom:10px;">Projects</div>
            <a href="{{ route('projects.all') }}" class="btn btn-primary"
                style="display:block;margin-bottom:6px;font-size:12px;padding:7px;">
                All Projects
            </a>
            <a href="{{ route('projects.supervisionRequests') }}" class="btn btn-success"
                style="display:block;font-size:12px;padding:7px;">
                Supervision Requests
            </a>
        </div>
        <div style="background:#f0f2f5;border-radius:10px;padding:15px;text-align:center;">
            <div style="font-size:22px;margin-bottom:8px;">🔧</div>
            <div style="font-weight:600;font-size:13px;color:#444;margin-bottom:10px;">Resources</div>
            <a href="{{ route('resources.manage') }}" class="btn btn-primary"
                style="display:block;margin-bottom:6px;font-size:12px;padding:7px;">
                Manage Resources
            </a>
            <a href="{{ route('resources.manageBookings') }}" class="btn btn-success"
                style="display:block;font-size:12px;padding:7px;">
                Approve Bookings
            </a>
        </div>
        <div style="background:#f0f2f5;border-radius:10px;padding:15px;text-align:center;">
            <div style="font-size:22px;margin-bottom:8px;">📝</div>
            <div style="font-weight:600;font-size:13px;color:#444;margin-bottom:10px;">Leave</div>
            <a href="{{ route('leave.manage') }}" class="btn btn-warning"
                style="display:block;margin-bottom:6px;font-size:12px;padding:7px;">
                Manage Leave
            </a>
            <a href="{{ route('leave.calendar') }}" class="btn btn-primary"
                style="display:block;font-size:12px;padding:7px;">
                Leave Calendar
            </a>
        </div>
        <div style="background:#f0f2f5;border-radius:10px;padding:15px;text-align:center;">
            <div style="font-size:22px;margin-bottom:8px;">📊</div>
            <div style="font-weight:600;font-size:13px;color:#444;margin-bottom:10px;">Reports</div>
            <a href="{{ route('reports.index') }}" class="btn btn-primary"
                style="display:block;margin-bottom:6px;font-size:12px;padding:7px;">
                View Reports
            </a>
            <a href="{{ route('resources.calendar') }}" class="btn btn-success"
                style="display:block;font-size:12px;padding:7px;">
                Resource Calendar
            </a>
        </div>
    </div>
</div>

<div class="card">
    <h2>📁 Recent Projects</h2>
    <p style="color:#888;">No projects registered yet.</p>
</div>
@endsection