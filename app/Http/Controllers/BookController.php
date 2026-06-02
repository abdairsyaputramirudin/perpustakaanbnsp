<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCopy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $stock = $request->query('stock', 'all');

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

        if ($stock === 'available') {
            $query->where('stock', '>', 0);
        }

        if ($stock === 'empty') {
            $query->where('stock', 0);
        }

        $books = $query->latest()->paginate(10)->withQueryString();

        return view('books.index', compact('books', 'search', 'stock'));
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        $uploadedCover = $request->file('cover_image');
        if ($uploadedCover && !$uploadedCover->isValid()) {
            return back()
                ->withErrors(['cover_image' => 'Upload cover gagal: ' . $uploadedCover->getErrorMessage()])
                ->withInput();
        }

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:books,code'],
            'title' => ['required', 'string', 'max:255'],
            'author' => ['nullable', 'string', 'max:255'],
            'publisher' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'category' => ['nullable', 'string', 'max:100'],
            'stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('book-covers', 'public');
        }

        $book = Book::create($validated);
        $this->syncAvailableCopies($book, (int) $validated['stock']);

        return redirect()->route('books.index')->with('success', 'Data buku berhasil ditambahkan.');
    }

    public function show(Book $book)
    {
        $book->load(['copies' => fn ($query) => $query->orderBy('copy_code')]);

        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $uploadedCover = $request->file('cover_image');
        if ($uploadedCover && !$uploadedCover->isValid()) {
            return back()
                ->withErrors(['cover_image' => 'Upload cover gagal: ' . $uploadedCover->getErrorMessage()])
                ->withInput();
        }

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('books', 'code')->ignore($book->id)],
            'title' => ['required', 'string', 'max:255'],
            'author' => ['nullable', 'string', 'max:255'],
            'publisher' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'category' => ['nullable', 'string', 'max:100'],
            'stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $oldCover = $book->cover_image;
        $newCover = null;

        if ($request->hasFile('cover_image')) {
            $newCover = $request->file('cover_image')->store('book-covers', 'public');
            $validated['cover_image'] = $newCover;
        }

        try {
            $book->update($validated);
            $this->syncAvailableCopies($book, (int) $validated['stock']);
        } catch (\Throwable $e) {
            if ($newCover && Storage::disk('public')->exists($newCover)) {
                Storage::disk('public')->delete($newCover);
            }

            throw $e;
        }

        if ($newCover && $oldCover && Storage::disk('public')->exists($oldCover)) {
            Storage::disk('public')->delete($oldCover);
        }

        return redirect()->route('books.index')->with('success', 'Data buku berhasil diperbarui.');
    }

    public function destroy(Book $book)
    {
        $hasActiveLoan = $book->loanItems()
            ->whereHas('loan', fn ($loanQuery) => $loanQuery->where('status', 'borrowed'))
            ->exists();

        if ($hasActiveLoan) {
            return back()->with('error', 'Buku tidak bisa dihapus karena sedang dipinjam.');
        }

        if ($book->loanItems()->exists()) {
            return back()->with('error', 'Buku tidak bisa dihapus karena sudah punya histori peminjaman.');
        }

        if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Data buku berhasil dihapus.');
    }

    private function syncAvailableCopies(Book $book, int $targetAvailable): void
    {
        $targetAvailable = max(0, $targetAvailable);
        $currentAvailable = $book->availableCopies()->count();

        if ($targetAvailable > $currentAvailable) {
            $needToCreate = $targetAvailable - $currentAvailable;

            for ($i = 0; $i < $needToCreate; $i++) {
                $book->copies()->create([
                    'copy_code' => $this->makeNextCopyCode($book),
                    'status' => 'available',
                ]);
            }
        }

        if ($targetAvailable < $currentAvailable) {
            $needToDelete = $currentAvailable - $targetAvailable;

            $copies = $book->availableCopies()
                ->orderByDesc('id')
                ->limit($needToDelete)
                ->get();

            foreach ($copies as $copy) {
                $copy->delete();
            }
        }

        $book->syncStockFromCopies();
    }

    private function makeNextCopyCode(Book $book): string
    {
        $sequence = $book->copies()->count() + 1;

        do {
            $code = sprintf('%s-C%03d', $book->code, $sequence);
            $exists = BookCopy::where('copy_code', $code)->exists();
            $sequence++;
        } while ($exists);

        return $code;
    }
}
