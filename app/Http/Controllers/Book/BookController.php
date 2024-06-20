<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function index(Request $request) {
        $title = $request->title;
        $filter = $request->filter;

        $books = Book::when(
            $title,
            fn($query, $title) => $query->title($title)
        );

        $books = match ($filter) {
            'popular_last_month' => $books->popularLastMonth(),
            'popular_last_6months' => $books->popularLast6Months(),
            'highest_rated_last_month' => $books->highestRatedLastMonth(),
            'highest_rated_last_6months' => $books->highestRatedLast6Months(),
            default => $books->latest()
        };

        $cacheKey = 'books:' . $filter . ':' . $title;
        $books = cache()->remember($cacheKey, 3600, fn() => $books->get());

        return view('books.index', compact('books'));
    }

    public function create() {

    }

    public function store(Request $request) {

    }

    public function show(Book $book) {
        return view('books.show', [
            'book' => $book->load([
                'reviews' => fn($query) => $query->latest()
            ])
        ]);
    }

    public function update(Book $book, Request $request) {

    }

    public function edit(Book $book) {

    }

    public function delete(Book $book) {
        
    }
}
