<?php

use App\Http\Controllers\Task\TaskController;

/*
 * All routes related to the tasks management
 */

Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
Route::get('/create', [TaskController::class, 'create'])->name('tasks.create');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');

Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
Route::match(['put', 'patch'], '/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');

Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
