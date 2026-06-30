@extends('layouts.app')
@section('content')
<div class="card">
    <h2>📋 Projects I Supervise</h2>
    @if($projects->count() > 0)
    <table>
        <tr><th>#</th><th>Title</th><th>Student</th><th>Status</th><th>Action</th></tr>
        @foreach($projects as $i => $p)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $p->title }}</td>
            <td>{{ $p->student?->name }}</td>
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
            No projects assigned yet.</p>
    @endif
</div>
@endsection