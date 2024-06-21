<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Book\ReviewRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Book;

class ReviewController extends Controller
{
    public function create(Book $book) {
        // Render the view for creating a new review for a specific book
        // The 'book' object is passed to the view to ensure the review is associated with the correct book
        return view('books.reviews.create', compact('book'));
    }

    public function store(ReviewRequest $request, Book $book) {
        // Start Database Transaction
        DB::beginTransaction();

        try {
            // Attempt to create a new review using validated data from the request
            // This is added to the 'reviews' relationship of the Book model
            $book->reviews()->create($request->validated());

            // if no exceptions are thrown, commit the transaction
            DB::commit();
        } catch (\Exception $e) {
            // if an exception occurs, roll back the transaction to avoid partial date persistence
            DB::rollBack();
            // Return or handle the exception as needed, here it's returned directly for simplicity
            return $e;
        }

        // Redirect the user to the book detail page after successfully saving the review
        return redirect()->route('books.show', $book);
    }
}
