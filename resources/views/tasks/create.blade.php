@extends('layouts.app')

@section('content')
    <h1>Create Task</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tasks.store') }}" method="POST" class="task-form">
        @csrf
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" class="form-control" id="title" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control" id="description" required></textarea>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control" disabled>
                <option value="New" selected>New</option>
            </select>
        </div>
        <div class="form-group">
            <label for="deadline">Deadline</label>
            <input type="datetime-local" name="deadline" class="form-control" id="deadline" required min="{{ now()->format('Y-m-d\TH:i') }}">
        </div>
        <div class="form-group">
            <label for="user_id">User</label>
            <select name="user_id" class="form-control" id="user_id" required>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Create Task</button>
    </form>
@endsection
