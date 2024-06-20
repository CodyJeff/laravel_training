<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('/task')->name('tasks')->group(function () {
    Route::get('/lists', [TaskController::class, 'index'])->name('.list');
    Route::get('/create', [TaskController::class, 'create'])->name('.create');
    Route::get('/{task}', [TaskController::class, 'show'])->name('.show');
    Route::get('/{task}/edit', [TaskController::class, 'edit'])->name('.edit');
    Route::post('/store', [TaskController::class, 'store'])->name('.store');
    Route::post('/update/{task}', [TaskController::class, 'update'])->name('.update');
    Route::post('/delete/{task}', [TaskController::class, 'delete'])->name('.delete');
    Route::post('/{task}/toggle-complete', [TaskController::class, 'toggleComplete'])->name('.toggle-complete');
});
