<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class PublicCatalogController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));

        $query = Book::query();

        if ($search !== '') {
            $query->where(function ($bookQuery) use ($search) {
                $bookQuery
                    ->where('code', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('publisher', 'like', "%{$search}%");
            });
        }

        $books = $query->latest()->paginate(12)->withQueryString();

        return view('catalog.index', compact('books', 'search'));
    }

    public function show(Book $book)
    {
        return view('catalog.show', compact('book'));
    }
}
