@extends('layouts.app')
@section('content')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <h2>📁 {{ $project->title }}</h2>
    <table style="width:auto;">
        <tr>
            <td style="padding:6px 16px 6px 0;font-weight:600;">Student:</td>
            <td>{{ $project->student?->name }}</td>
        </tr>
        <tr>
            <td style="padding:6px 16px 6px 0;font-weight:600;">Supervisor:</td>
            <td>{{ $project->supervisor?->name ?? 'Not assigned' }}</td>
        </tr>
        @if($project->tempSupervisor)
        <tr>
            <td style="padding:6px 16px 6px 0;font-weight:600;">Temp Supervisor:</td>
            <td style="color:#856404;font-weight:600;">
                ⚠️ {{ $project->tempSupervisor->name }}
                <span style="color:#aaa;font-size:12px;">(covering during leave)</span>
            </td>
        </tr>
        @endif
        <tr>
            <td style="padding:6px 16px 6px 0;font-weight:600;">Status:</td>
            <td><span class="badge badge-{{ $project->status }}">
                {{ ucfirst($project->status) }}</span></td>
        </tr>
        <tr>
            <td style="padding:6px 16px 6px 0;font-weight:600;">Description:</td>
            <td>{{ $project->description }}</td>
        </tr>
    </table>
</div>

{{-- HOD: Assign Supervisor --}}
@if(auth()->user()->user_type === 'hod')
<div class="card">
    <h2>👨‍🏫 Assign Supervisor</h2>
    <form method="POST"
        action="{{ route('projects.assignSupervisor', $project->project_id) }}"
        style="display:flex;gap:10px;align-items:flex-end;">
        @csrf
        <div class="form-group" style="margin:0;flex:1;">
            <label>Select Lecturer</label>
            <select name="supervisor_id" required>
                <option value="">-- Select Lecturer --</option>
                @foreach($lecturers as $l)
                <option value="{{ $l->id }}"
                    {{ $project->supervisor_id == $l->id ? 'selected' : '' }}>
                    {{ $l->name }}
                </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Assign</button>
    </form>
</div>
@endif

{{-- Student: Add Progress Update --}}
@if(auth()->user()->user_type === 'student'
    && $project->student_id == auth()->id())
<div class="card">
    <h2>📝 Add Progress Update</h2>
    <form method="POST"
        action="{{ route('projects.addUpdate', $project->project_id) }}">
        @csrf
        <div class="form-group">
            <label>Update Status</label>
            <select name="status" required>
                @foreach(['planning','design','implementation','testing','completed'] as $s)
                <option value="{{ $s }}"
                    {{ $project->status === $s ? 'selected' : '' }}>
                    {{ ucfirst($s) }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Progress Description</label>
            <textarea name="update_text" rows="4"
                placeholder="Describe what you did..." required></textarea>
        </div>
        <button type="submit" class="btn btn-success">Submit Update</button>
    </form>
</div>
@endif

{{-- Progress History --}}
<div class="card">
    <h2>📊 Progress History</h2>
    @if($project->updates->count() > 0)
        @foreach($project->updates->sortByDesc('created_at') as $u)
        <div style="padding:12px 0;border-bottom:1px solid #eee;">
            <div style="font-size:12px;color:#888;margin-bottom:4px;">
                🕐 {{ $u->created_at->format('Y-m-d H:i') }}
            </div>
            <div>{{ $u->update_text }}</div>
        </div>
        @endforeach
    @else
        <p style="color:#888;">No progress updates yet.</p>
    @endif
</div>

<a href="javascript:history.back()" class="btn btn-warning">← Back</a>
@endsection