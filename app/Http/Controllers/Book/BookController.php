<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function index(Request $request) {
        // Retrieved 'title' and 'filter' from the request
        $title = $request->title;
        $filter = $request->filter;

        // Initialized the query with a condition to apply a title filter if provided
        $books = Book::when(
            $title,
            fn($query, $title) => $query->title($title)
        );

        $books = match ($filter) {
            'popular_last_month' => $books->popularLastMonth(), // Books popular in the last month 
            'popular_last_6months' => $books->popularLast6Months(), // Books popular in the 6 months
            'highest_rated_last_month' => $books->highestRatedLastMonth(), // Highest rated books last month
            'highest_rated_last_6months' => $books->highestRatedLast6Months(), // Highest rated books in the last 6 months
            default => $books->latest()->withAvgRating()->withReviewsCount() // Default case to get latest books with average ratings and review counts
        };

        // Construct a unique cache key using the filter and title 
        $cacheKey = 'books:' . $filter . ':' . $title;

        // Cache the resulting books data for 3600 seconds (1 hour) to improve performance
        $books = cache()->remember(
            $cacheKey,
            3600,
            fn() =>
            $books->get() // Execute query in cache result
        );

        // Return the 'books.index' view and pass the books data 
        return view('books.index', compact('books'));
    }

    public function show(Book $book) {
        // Generate a cache key using the book ID
        $cacheKey = 'book:' . $book->id;

        // Attempt to retrieve the book details from cache or resolve it from the database
        $book = cache()->remember(
            $cacheKey,
            3600,
            fn() =>
            // Eager load book details including the latest reviews, average rating, and reviews count
            Book::with([
                'reviews' => fn($query) => $query->latest()
            ])->withAvgRating()->withReviewsCount()->findOrFail($book->id)
        );

        // Return the view for displaying the book details and pass the book object to the view 
        return view('books.show', ['book' => $book]);
    }
}
