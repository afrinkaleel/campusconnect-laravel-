@extends('layouts.app')
@section('content')
<div class="card">
    <h2>📁 My Projects
        <a href="{{ route('projects.create') }}" class="btn btn-success"
            style="float:right;font-size:13px;">➕ New Project</a>
    </h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($projects->count() > 0)
    <table>
        <tr>
            <th>#</th><th>Title</th><th>Supervisor</th>
            <th>Status</th><th>Action</th>
        </tr>
        @foreach($projects as $i => $p)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $p->title }}</td>
            <td>{{ $p->supervisor?->name ?? 'Not assigned' }}</td>
            <td><span class="badge badge-{{ $p->status }}">
                {{ ucfirst($p->status) }}</span></td>
            <td>
                <a href="{{ route('projects.show', $p->project_id) }}"
                    class="btn btn-primary"
                    style="padding:5px 12px;font-size:13px;">View</a>
            </td>
        </tr>
        @endforeach
    </table>
    @else
        <p style="color:#888;text-align:center;padding:20px;">
            No projects yet.
            <a href="{{ route('projects.create') }}">Register your first project!</a>
        </p>
    @endif
</div>
@endsection