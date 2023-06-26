@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Task</h1>

        <form action="{{ route('tasks.update', $task) }}" method="POST" class="task-form">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ $task->title }}" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control">{{ $task->description }}</textarea>
            </div>

            <div class="form-group">
                <label for="user_id">User</label>
                <select name="user_id" id="user_id" class="form-control">
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ $task->user_id === $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    @if ($task->status === 'New')
                        <option value="New" selected>New</option>
                        <option value="In progress">In Progress</option>
                    @elseif ($task->status === 'In progress')
                        <option value="In progress" selected>In Progress</option>
                        <option value="Finished">Finished</option>
                    @else
                        <option value="Finished" selected>Finished</option>
                    @endif
                </select>
            </div>

            <div class="form-group">
                <label for="description">Deadline</label>
                <input type="date" name="deadline" id="deadline" class="form-control" value="{{ \Carbon\Carbon::parse($task->deadline)->format('Y-m-d') }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Task</button>
        </form>

        @if (session('success'))
            <div class="alert alert-success">
                <span class="alert-icon">&#10003;</span>
                <span class="alert-message">{{ session('success') }}</span>
            </div>
        @endif
    </div>
@endsection
