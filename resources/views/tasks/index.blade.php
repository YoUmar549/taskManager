@extends('dashboard')

@section('content')
    <h1 id="task-title">Task List</h1>

    <form action="{{ route('tasks.index') }}" method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="user">User</label>
                    <select name="user" id="user" class="form-control">
                        <option value="">All Users</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ $user->name }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="">All Statuses</option>
                        {{--@foreach ($statuses as $status)
                            <option value="{{ $status }}" {{ $status }}>
                                {{ $status }}
                            </option>
                        @endforeach--}}
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
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

    <a href="{{ route('tasks.create') }}" class="btn btn-primary">Create New Task</a>

    <table class="table task-list">
        <thead>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>User</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($tasks as $task)
            <tr>
                <td>{{ $task->title }}</td>
                <td>{{ $task->description }}</td>
                <td>{{ $task->user->name }}</td>
                <td>{{ $task->status }}</td>
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
{{--        {{ $tasks->links() }}--}}
@endsection
