<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\AttendeeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['api'])->group(function() {
    Route::prefix('/events')->name('events')->group(function() {
        Route::get('/', [EventController::class, 'index'])->name('.index');
        Route::get('/{event}', [EventController::class, 'show'])->name('.show');
        Route::post('/', [EventController::class, 'store'])->name('.store');
        Route::post('/{event}', [EventController::class, 'update'])->name('.update');
        Route::post('/delete/{event}', [EventController::class, 'delete'])->name('.delete');
 
        Route::prefix('/{event}/attendees')->name('.attendees')->group(function () {
            Route::get('/', [AttendeeController::class, 'index'])->name('.index');
            Route::get('/{attendee}', [AttendeeController::class, 'show'])->name('.show');
            Route::post('/', [AttendeeController::class, 'store'])->name('.store');
            Route::post('/{attendee}', [AttendeeController::class, 'update'])->name('.update');
            Route::post('/{attendee}', [AttendeeController::class, 'delete'])->name('.delete');
        });
    }); 
});
