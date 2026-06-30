@use(App\Models\Project)
@extends('layouts.app')
@section('content')
<div class="card">
    <h2>📝 Manage Leave Requests
        <a href="{{ route('leave.calendar') }}"
            class="btn btn-primary"
            style="float:right;font-size:13px;">📅 Calendar</a>
    </h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($leaves->count() > 0)
    <table>
        <tr>
            <th>Lecturer</th><th>Type</th><th>From</th><th>To</th>
            <th>Days</th><th>Reason</th><th>Status</th><th>Action</th>
        </tr>
        @foreach($leaves as $l)
        @php
            $days = \Carbon\Carbon::parse($l->start_date)
                        ->diffInDays($l->end_date) + 1;
            $affected = Project::where('supervisor_id', $l->lecturer_id)
                ->where('status','!=','completed')->count();
        @endphp
        <tr>
            <td>{{ $l->lecturer?->name }}</td>
            <td>{{ ucfirst($l->leave_type) }}</td>
            <td>{{ $l->start_date }}</td>
            <td>{{ $l->end_date }}</td>
            <td>{{ $days }}</td>
            <td style="font-size:13px;max-width:150px;">
                {{ $l->reason }}
                @if($affected > 0 && $l->status === 'pending')
                <br><span style="color:#ea4335;font-size:12px;">
                    ⚠️ {{ $affected }} project(s) affected
                </span>
                @endif
            </td>
            <td><span class="badge badge-{{ $l->status }}">
                {{ ucfirst($l->status) }}</span></td>
            <td>
                @if($l->status === 'pending')
                <form method="POST"
                    action="{{ route('leave.handle', $l->leave_id) }}">
                    @csrf
                    @if($affected > 0)
                    <select name="temp_supervisor_id"
                        style="font-size:12px;padding:4px;border:1px solid #ddd;
                               border-radius:4px;margin-bottom:4px;width:100%;">
                        <option value="">-- Temp Supervisor --</option>
                        @foreach($lecturers as $lec)
                            @if($lec->id != $l->lecturer_id)
                            <option value="{{ $lec->id }}">
                                {{ $lec->name }}
                            </option>
                            @endif
                        @endforeach
                    </select>
                    @else
                    <input type="hidden" name="temp_supervisor_id" value="">
                    @endif
                    <button name="action" value="approve"
                        class="btn btn-success"
                        style="padding:4px 10px;font-size:12px;
                               width:100%;margin-bottom:4px;"
                        onclick="return confirm('Approve?')">
                        ✅ Approve
                    </button>
                    <button name="action" value="reject"
                        class="btn btn-danger"
                        style="padding:4px 10px;font-size:12px;width:100%;"
                        onclick="return confirm('Reject?')">
                        ❌ Reject
                    </button>
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
            No leave requests yet.</p>
    @endif
</div>
@endsection