@extends('layouts.app')
@section('content')
<div class="card">
    <h2>👨‍🏫 Supervision Requests</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($requests->count() > 0)
    <table>
        <tr>
            <th>Lecturer</th><th>Project</th><th>Student</th>
            <th>Requested</th><th>Status</th><th>Action</th>
        </tr>
        @foreach($requests as $r)
        <tr>
            <td>{{ $r->lecturer?->name }}</td>
            <td>{{ $r->project?->title }}</td>
            <td>{{ $r->project?->student?->name }}</td>
            <td style="font-size:13px;">
                {{ $r->created_at->format('Y-m-d') }}</td>
            <td><span class="badge badge-{{ $r->status }}">
                {{ ucfirst($r->status) }}</span></td>
            <td>
                @if($r->status === 'pending')
                <form method="POST"
                    action="{{ route('projects.handleSupervisionRequest',
                                    $r->request_id) }}"
                    style="display:inline;">
                    @csrf
                    <button name="action" value="approve"
                        class="btn btn-success"
                        style="padding:5px 10px;font-size:12px;margin-right:4px;"
                        onclick="return confirm('Approve?')">✅</button>
                    <button name="action" value="reject"
                        class="btn btn-danger"
                        style="padding:5px 10px;font-size:12px;"
                        onclick="return confirm('Reject?')">❌</button>
                </form>
                @else
                    <span style="color:#aaa;">—</span>
                @endif
            </td>
        </tr>
        @endforeach
    </table>
    @else
        <p style="color:#888;text-align:center;padding:20px;">
            No supervision requests yet.</p>
    @endif
</div>
@endsection