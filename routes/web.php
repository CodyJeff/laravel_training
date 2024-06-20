<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Book\BookController;

Route::get('/', function () {
    return view('welcome');
});

// Book Reviews route
Route::prefix('/books')->name('books')->group(function() {
    Route::get('/', [BookController::class, 'index'])->name('.index');
    Route::get('/create', [BookController::class, 'create'])->name('.create');
    Route::get('/{book}', [BookController::class, 'show'])->name('.show');
    Route::get('/{book}/edit', [BookController::class, 'edit'])->name('.edit');
    Route::post('/', [BookController::class, 'store'])->name('.store');
    Route::post('/update', [BookController::class, 'update'])->name('.update');
    Route::post('/{book}', [BookController::class, 'delete'])->name('.delete');
});