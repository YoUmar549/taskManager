@extends('dashboard')

@section('content')
    <div class="container">
        <h1 class="h1 text-primary">Task Details</h1>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $task->title }}</h5>
                <p class="card-text">{{ $task->description }}</p>
                <p class="card-text">{{ $task->status }}</p>
                <p class="card-text">{{ $task->deadline }}</p>

                <a href="{{ route('tasks.index') }}" class="btn btn-primary">Back to Task List</a>
            </div>
        </div>
    </div>
@endsection
