<?php

use App\Http\Controllers\ProfileController;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $tasks = Task::all();
    $users = User::all();
    $statuses = ['New', 'In progress', 'Finished'];
    // Fetch the selectedUser from the request query parameters
    $selectedUser = request()->query('user');
    $selectedStatus = request()->query('status');

    return view('tasks.index', compact('tasks', 'users', 'statuses', 'selectedUser', 'selectedStatus'));
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'admin'])->group(function () {
    // Routes that require admin access
});

Route::group(['prefix' => 'tasks', 'namespace' => 'App\Http\Controllers\Task'], function () {
    require __DIR__ . '/tasks.php';
});

Route::get('/users', [ProfileController::class, 'index'])->name('users.index');
//Update users role
Route::put('/user/{user}', [ProfileController::class, 'updateRole'])->name('users.updateRole');


require __DIR__.'/auth.php';
