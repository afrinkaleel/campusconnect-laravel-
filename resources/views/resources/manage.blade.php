@extends('layouts.app')
@section('content')
<div class="card">
    <h2>➕ Add New Resource</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-error">
            @foreach($errors->all() as $e){{ $e }}<br>@endforeach
        </div>
    @endif
    <form method="POST" action="{{ route('resources.store') }}"
        style="display:grid;grid-template-columns:1fr 1fr 1fr auto;
               gap:15px;align-items:flex-end;">
        @csrf
        <div class="form-group" style="margin:0;">
            <label>Resource Name</label>
            <input type="text" name="name"
                placeholder="e.g. Arduino Kit" required>
        </div>
        <div class="form-group" style="margin:0;">
            <label>Quantity</label>
            <input type="number" name="quantity" min="1"
                placeholder="e.g. 5" required>
        </div>
        <div class="form-group" style="margin:0;">
            <label>Location</label>
            <input type="text" name="location"
                placeholder="e.g. Lab Room 3" required>
        </div>
        <button type="submit" class="btn btn-primary">Add</button>
    </form>
</div>

<div class="card">
    <h2>🔧 All Lab Resources</h2>
    @if($resources->count() > 0)
    <table>
        <tr>
            <th>#</th><th>Name</th><th>Total</th>
            <th>Available</th><th>Location</th><th>Action</th>
        </tr>
        @foreach($resources as $i => $r)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $r->name }}</td>
            <td>{{ $r->quantity_total }}</td>
            <td style="color:{{ $r->quantity_available > 0
                ? '#34a853' : '#ea4335' }};font-weight:bold;">
                {{ $r->quantity_available }}
            </td>
            <td>{{ $r->location }}</td>
            <td>
                <form method="POST"
                    action="{{ route('resources.destroy', $r->resource_id) }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger"
                        style="padding:5px 12px;font-size:13px;"
                        onclick="return confirm('Delete this resource?')">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
    @else
        <p style="color:#888;text-align:center;padding:20px;">
            No resources added yet.</p>
    @endif
</div>
@endsection