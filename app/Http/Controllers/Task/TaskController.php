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
    public function index(Request $request)
    {
        $users = User::all();
        $statuses = ['New', 'In progress', 'Finished'];

        $selectedUser = $request->query('user');
        $selectedStatus = $request->query('status');

        $tasksQuery = Task::query();

        if ($selectedUser) {
            $tasksQuery->where('user_id', $selectedUser);
        }

        if ($selectedStatus) {
            $tasksQuery->where('status', $selectedStatus);
        }

        $tasks = $tasksQuery->paginate(10);

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
    public function show(string $id)
    {
        $task = Task::findOrFail($id);
        return view('tasks.show', compact('task'));
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
    public function update(Request $request, Task $task) {
        // Check if the request is an AJAX request
        if ($request->ajax()) {
            $fieldName = $request->input('field_name');
            $updatedValue = $request->input('updated_value');

            // Update the task attribute based on the field name
            if ($fieldName === 'title') {
                $task->title = $updatedValue;
            } elseif ($fieldName === 'description') {
                $task->description = $updatedValue;
            } elseif ($fieldName === 'user') {
                $task->user_id = $updatedValue;
            } elseif ($fieldName === 'status') {
                $task->status = $updatedValue;
            } elseif ($fieldName === 'deadline') {
                $task->deadline = $updatedValue;
            }

            // Save the updated task
            $task->save();

            return response()->json([
                'message' => 'Task updated successfully.',
            ]);
        }

        // Check if the authenticated user is the owner of the task or an admin
        $user = auth()->user();
        if ($task->user_id !== $user->id && !$user->admin) {
            return redirect()->route('tasks.index')->withErrors(['error' => 'You are not authorized to update this task.']);
        }

        // Validation rules
        $rules = [
            'status' => 'required',
        ];

        // Only enforce other field validations if the user is an admin
        if ($user->admin) {
            $rules = array_merge($rules, [
                'title' => 'required',
                'user_id' => 'required',
                'description' => 'nullable',
                'deadline' => 'required|date',
            ]);
        }

        // Validate the request
        $request->validate($rules);

        // Update the task attributes
        if ($user->admin) {
            $task->title = $request->input('title');
            $task->user_id = $request->input('user_id');
            $task->description = $request->input('description');
            $task->deadline = $request->input('deadline');
        }

        $task->status = $request->input('status');
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
