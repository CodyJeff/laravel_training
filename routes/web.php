<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Book\BookController;
use App\Http\Controllers\Book\ReviewController;

Route::get('/', function () {
    return view('welcome');
});

// Grouping all book-related routes under '/books' prefix
Route::prefix('/books')->name('books')->group(function() {
    // Route to list all books 
    Route::get('/', [BookController::class, 'index'])->name('.index');
    // Route to show details for a specific book using a dynamic id
    Route::get('/{book}', [BookController::class, 'show'])->name('.show');

    // Nested routes for book reviews under '/review' prefix
    Route::prefix('/review')->name('.reviews')->group(function() {
        // Route to display the form for creating a new review for a specific book
        Route::get('/create/{book}', [ReviewController::class, 'create'])->name('.create');
        // Route to store a new review for a specific book with rate limiting applied
        Route::post('/store/{book}', [ReviewController::class, 'store'])->name('.store')->middleware('throttle:reviews');
    });
});