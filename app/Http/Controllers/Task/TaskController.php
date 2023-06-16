<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        $users = User::all();
        $statuses = ['New', 'In progress', 'Finished'];

        $selectedUser = $request->query('user');
        $selectedStatus = $request->query('status');

        $tasksQuery = DB::table('tasks');

        if ($selectedUser) {
            $tasksQuery->where('user_id', $selectedUser);
        }

        if ($selectedStatus) {
            $tasksQuery->where('status', $selectedStatus);
        }

        $tasks = $tasksQuery->paginate(3);

        $tasks->appends(['user' => $selectedUser, 'status' => $selectedStatus]);

        // Load the user data for each task
        $taskIds = $tasks->pluck('id');
        $taskUserMap = DB::table('tasks')
            ->whereIn('id', $taskIds)
            ->pluck('user_id', 'id')
            ->all();

        foreach ($tasks as $task) {
            $taskId = $task->id;
            $userId = $taskUserMap[$taskId] ?? null;
            $task->user = User::find($userId);
        }

        return view('tasks.index', compact('tasks', 'users', 'statuses', 'selectedUser', 'selectedStatus'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        $users = User::all();
        // Render the view to create a new task
        return view('tasks.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'user_id' => 'required',
            'deadline' => 'required',
        ]);

        // Create a new task with the validated data and user ID
        Task::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'user_id' => $validatedData['user_id'],
            'deadline' => $validatedData['deadline'],
        ]);

        // Optionally, you can add additional logic or redirect to another page
        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task) {
        $users = User::all();
        // Render the view to edit the specified task
        return view('tasks.edit', compact('task', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        // Check if the authenticated user owns the task
        if ($task->user_id !== auth()->user()->id) {
            return redirect()->route('tasks.index')->withErrors(['error' => 'You are not authorized to update this task.']);
        }

        // Authorization passed, proceed with updating the task
        $request->validate([
            'title' => 'required',
            'user_id' => 'required',
            'description' => 'nullable',
            'status' => 'required'
        ]);

        $task->title = $request->input('title');
        $task->description = $request->input('description');
        $task->user_id = $request->input('user_id');
        $task->status = $request->input('status');
        $task->load('user');

        $task->save();

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task) {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
}
