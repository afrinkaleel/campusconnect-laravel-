@extends('layouts.app')

@section('content')
<h2 style="margin-bottom:20px;">👋 Welcome, {{ $user->name }}!</h2>

<div class="stats-grid">
    <div class="stat-card">
        <div class="number">{{ $stats['total_projects'] }}</div>
        <div class="label">My Projects</div>
    </div>
    <div class="stat-card">
        <div class="number">{{ $stats['total_bookings'] }}</div>
        <div class="label">Total Bookings</div>
    </div>
    <div class="stat-card">
        <div class="number">{{ $stats['pending_bookings'] }}</div>
        <div class="label">Pending Bookings</div>
    </div>
    <div class="stat-card">
        <div class="number">{{ $stats['unread_notifs'] }}</div>
        <div class="label">Notifications</div>
    </div>
</div>

<div class="card">
    <h2>⚡ Quick Actions</h2>
    <a href="{{ route('projects.index') }}"
        class="btn btn-primary" style="margin-right:8px;">📁 My Projects</a>
    <a href="{{ route('projects.create') }}"
        class="btn btn-success" style="margin-right:8px;">➕ New Project</a>
    <a href="{{ route('resources.bookForm') }}"
        class="btn btn-warning">🔧 Book Resource</a>
</div>

<div class="card">
    <h2>📁 My Recent Projects</h2>
    <p style="color:#888;">No projects yet.</p>
</div>
@endsection
