@extends('dashboard')

@section('content')
    <div class="container">
        <h1 class="h1 text-primary">Task List</h1>

        <form action="{{ route('tasks.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-lg-4 mb-3">
                    <div class="form-group text-info">
                        <label for="user" class="fw-bold">User</label>
                        <select name="user" id="user" class="form-control">
                            <option value="">All Users</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @if ($selectedUser == $user->id) selected @endif>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 mb-3">
                    <div class="form-group text-info">
                        <label for="status" class="fw-bold">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">All Statuses</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" @if ($selectedStatus == $status) selected @endif>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 align-self-end mb-3">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    @if ($selectedUser || $selectedStatus)
                        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Clear Filters</a>
                    @else
                        <a href="#" class="btn btn-secondary disabled">Clear Filters</a>
                    @endif
                </div>
            </div>
        </form>

        @if (session('success'))
            @php
                $successClass = '';
                if (str_contains(session('success'), 'deleted') !== false) {
                    $successClass = 'alert-delete';
                }
            @endphp
            <div class="alert alert-success {{ $successClass }}">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row mb-3">
            <div class="col-lg-6">
                <a href="{{ route('tasks.create') }}" class="btn btn-primary">Create New Task</a>
            </div>
            <div class="col-lg-6 text-lg-end">
                <div class="pagination">
                    {{--{{ $tasks->links() }}--}}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                @if ($tasks->isEmpty())
                    <div class="alert alert-info">
                        No tasks found.
                    </div>
                @else
                    <div class="editable-tasks-container">
                        <table class="table task-list">
                            <thead>
                            <tr>
                                <th>Title</th>
                                <th>Description</th>
                                <th>User</th>
                                <th>Status</th>
                                <th>Deadline</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($tasks as $task)
                                <tr>
                                    <td><span class="editable-task" contenteditable data-task-id="{{ $task->id }}" data-field-name="title">{{ $task->title }}</span></td>
                                    <td><span class="editable-task" contenteditable data-task-id="{{ $task->id }}" data-field-name="description">{{ $task->description }}</span></td>
                                    <td><span class="editable-task" contenteditable data-task-id="{{ $task->id }}" data-field-name="user">{{ $task->user->name }}</span></td>
                                    <td><span class="editable-task" contenteditable data-task-id="{{ $task->id }}" data-field-name="status">{{ $task->status }}</span></td>
                                    <td><span class="editable-task" contenteditable data-task-id="{{ $task->id }}" data-field-name="deadline">{{ $task->deadline }}</span></td>
                                    <td>
                                        <a href="{{ route('tasks.show', ['task' => $task->id]) }}" class="btn btn-primary">See more</a>
                                    </td>
                                    <td>
                                        <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-primary">Edit</a> |
                                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display: inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
