@extends('layouts.app')
@section('content')
<div class="card">
    <h2>🔍 Unassigned Projects</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif
    @if($projects->count() > 0)
    <table>
        <tr>
            <th>#</th><th>Title</th><th>Student</th>
            <th>Status</th><th>Description</th><th>Action</th>
        </tr>
        @foreach($projects as $i => $p)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $p->title }}</td>
            <td>{{ $p->student?->name }}</td>
            <td><span class="badge badge-{{ $p->status }}">
                {{ ucfirst($p->status) }}</span></td>
            <td style="font-size:13px;color:#666;max-width:200px;">
                {{ Str::limit($p->description, 80) }}</td>
            <td>
                <form method="POST"
                    action="{{ route('projects.requestSupervision') }}">
                    @csrf
                    <input type="hidden" name="project_id"
                        value="{{ $p->project_id }}">
                    <button type="submit" class="btn btn-primary"
                        style="padding:5px 12px;font-size:13px;"
                        onclick="return confirm('Request to supervise?')">
                        Request
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
    @else
        <p style="color:#888;text-align:center;padding:20px;">
            No unassigned projects.</p>
    @endif
</div>
@endsection