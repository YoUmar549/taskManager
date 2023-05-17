@extends('dashboard')

@section('content')
    <h1>Task List</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('tasks.create') }}" class="btn btn-primary">Create New Task</a>

    <table class="table" style="border: 1px solid blue;">
        <thead>
        <tr style="border: 1px solid blue;">
            <th style="border: 1px solid blue;">Title</th>
            <th style="border: 1px solid blue;">Description</th>
            <th style="border: 1px solid blue;">Actions</th>
        </tr>
        </thead>
        <tbody style="border: 1px solid blue;">
        @foreach ($tasks as $task)
            <tr style="border: 1px solid blue;">
                <td style="border: 1px solid blue;">{{ $task->title }}</td>
                <td style="border: 1px solid blue;">{{ $task->description }}</td>
                <td style="border: 1px solid blue;">
                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-primary">Edit</a>
                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display: inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
