@extends('layouts.app')

@section('content')
<h2 style="margin-bottom:20px;">👋 Welcome, {{ $user->name }}!</h2>

<div class="stats-grid">
    <div class="stat-card">
        <div class="number">{{ $stats['supervised'] }}</div>
        <div class="label">Projects Supervised</div>
    </div>
    <div class="stat-card">
        <div class="number">{{ $stats['total_leaves'] }}</div>
        <div class="label">Leave Requests</div>
    </div>
    <div class="stat-card">
        <div class="number">{{ $stats['pending_leaves'] }}</div>
        <div class="label">Pending Leaves</div>
    </div>
    <div class="stat-card">
        <div class="number">{{ $stats['unread_notifs'] }}</div>
        <div class="label">Notifications</div>
    </div>
</div>

<div class="card">
    <h2>⚡ Quick Actions</h2>
    <a href="{{ route('projects.supervise') }}"
        class="btn btn-primary" style="margin-right:8px;">📋 Projects</a>
    <a href="{{ route('resources.bookForm') }}"
        class="btn btn-warning" style="margin-right:8px;">🔧 Book Resource</a>
    <a href="{{ route('leave.applyForm') }}"
        class="btn btn-success">📝 Apply Leave</a>
</div>

<div class="card">
    <h2>📋 Projects I Supervise</h2>
    <p style="color:#888;">No projects assigned yet.</p>
</div>
@endsection