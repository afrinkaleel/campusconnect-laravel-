@extends('layouts.app')
@section('content')

<!-- Leave Balance -->
<div class="stats-grid" style="grid-template-columns:repeat(3,1fr);">
    <div class="stat-card">
        <div class="number">{{ $totalDays }}</div>
        <div class="label">Total Annual Days</div>
    </div>
    <div class="stat-card" style="border-left-color:#ea4335;">
        <div class="number" style="color:#ea4335;">{{ $usedDays }}</div>
        <div class="label">Used This Year</div>
    </div>
    <div class="stat-card" style="border-left-color:#34a853;">
        <div class="number" style="color:#34a853;">{{ $remainingDays }}</div>
        <div class="label">Remaining</div>
    </div>
</div>

<div class="card">
    <h2>📝 Apply for Leave</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-error">
            @foreach($errors->all() as $e){{ $e }}<br>@endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('leave.apply') }}">
        @csrf
        <div class="form-group">
            <label>Leave Type</label>
            <select name="leave_type" required>
                <option value="">-- Select leave type --</option>
                <option value="annual">Annual Leave</option>
                <option value="sick">Sick Leave</option>
                <option value="emergency">Emergency Leave</option>
            </select>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
            <div class="form-group">
                <label>Start Date</label>
                <input type="date" name="start_date"
                    id="start_date"
                    min="{{ date('Y-m-d') }}" required>
            </div>
            <div class="form-group">
                <label>End Date</label>
                <input type="date" name="end_date"
                    id="end_date"
                    min="{{ date('Y-m-d') }}" required>
            </div>
        </div>

        <!-- Duration Calculator -->
        <div id="duration_info" style="display:none;margin-bottom:15px;">
            <div class="alert alert-success" style="margin:0;">
                📅 Duration: <strong id="duration_days"></strong> day(s)
            </div>
        </div>

        <div class="form-group">
            <label>Reason</label>
            <textarea name="reason" rows="4"
                placeholder="Explain the reason..." required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            Submit Leave Request
        </button>
        <a href="{{ route('leave.myLeaves') }}"
            class="btn btn-warning" style="margin-left:10px;">
            View My Leaves
        </a>
    </form>
</div>

<div class="card">
    <h2>📋 Recent Leave Requests</h2>
    @if($myLeaves->count() > 0)
    <table>
        <tr>
            <th>Type</th><th>From</th><th>To</th>
            <th>Days</th><th>Status</th>
        </tr>
        @foreach($myLeaves as $l)
        @php
            $days = \Carbon\Carbon::parse($l->start_date)
                        ->diffInDays($l->end_date) + 1;
        @endphp
        <tr>
            <td>{{ ucfirst($l->leave_type) }}</td>
            <td>{{ $l->start_date }}</td>
            <td>{{ $l->end_date }}</td>
            <td>{{ $days }}</td>
            <td><span class="badge badge-{{ $l->status }}">
                {{ ucfirst($l->status) }}</span></td>
        </tr>
        @endforeach
    </table>
    @else
        <p style="color:#888;">No leave requests yet.</p>
    @endif
</div>

<script>
function calcDuration() {
    const start = document.getElementById('start_date').value;
    const end   = document.getElementById('end_date').value;
    if (start && end) {
        const diff = Math.round(
            (new Date(end) - new Date(start)) / (1000*60*60*24)
        ) + 1;
        if (diff > 0) {
            document.getElementById('duration_days').textContent = diff;
            document.getElementById('duration_info').style.display = 'block';
        }
    }
}
document.getElementById('start_date').addEventListener('change', calcDuration);
document.getElementById('end_date').addEventListener('change', calcDuration);
</script>
@endsection