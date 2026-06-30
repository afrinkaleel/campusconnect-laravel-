@extends('layouts.app')
@section('content')
<div class="card">
    <h2>➕ Register New Project</h2>

    @if($errors->any())
        <div class="alert alert-error">
            @foreach($errors->all() as $e) {{ $e }}<br> @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('projects.store') }}">
        @csrf
        <div class="form-group">
            <label>Project Title</label>
            <input type="text" name="title"
                value="{{ old('title') }}"
                placeholder="e.g. IoT Weather Station" required>
        </div>
        <div class="form-group">
            <label>Project Description</label>
            <textarea name="description" rows="5"
                placeholder="Describe your project..." required>
                {{ old('description') }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Register Project</button>
        <a href="{{ route('projects.index') }}"
            class="btn btn-warning" style="margin-left:10px;">Cancel</a>
    </form>
</div>
@endsection