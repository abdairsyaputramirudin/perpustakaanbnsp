<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Member;
use App\Services\LoanServiceInterface;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function __construct(private LoanServiceInterface $loanService)
    {
    }

    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status', 'all');

        $query = Loan::with('member', 'loanItems.book', 'loanItems.bookCopy');

        if ($search !== '') {
            $query->whereHas('member', function ($memberQuery) use ($search) {
                $memberQuery
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('member_code', 'like', "%{$search}%");
            });
        }

        if (in_array($status, ['borrowed', 'returned'], true)) {
            $query->where('status', $status);
        }

        $loans = $query->latest()->paginate(10)->withQueryString();

        return view('loans.index', compact('loans', 'search', 'status'));
    }

    public function create()
    {
        $members = Member::orderBy('name')->get();
        $books = Book::with(['copies' => fn ($query) => $query
            ->where('status', 'available')
            ->orderBy('copy_code')])
            ->whereHas('copies', fn ($query) => $query->where('status', 'available'))
            ->orderBy('title')
            ->get();

        return view('loans.create', compact('members', 'books'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => ['required', 'exists:members,id'],
            'borrow_date' => ['required', 'date'],
            'book_copy_ids' => ['required', 'array', 'min:1'],
            'book_copy_ids.*' => ['exists:book_copies,id'],
            'notes' => ['nullable', 'string'],
        ]);

        $this->loanService->createLoan($validated);

        return redirect()->route('loans.index')->with('success', 'Data peminjaman berhasil ditambahkan.');
    }

    public function show(Loan $loan)
    {
        $loan->load('member', 'loanItems.book', 'loanItems.bookCopy');

        return view('loans.show', compact('loan'));
    }

    public function markReturned(Loan $loan)
    {
        if ($loan->isReturned()) {
            return redirect()->route('loans.index')->with('success', 'Peminjaman sudah dikembalikan.');
        }

        $this->loanService->markReturned($loan);

        return redirect()->route('loans.index')->with('success', 'Buku berhasil dikembalikan.');
    }

    public function destroy(Loan $loan)
    {
        $this->loanService->deleteLoan($loan);

        return redirect()->route('loans.index')->with('success', 'Data peminjaman berhasil dihapus.');
    }
}
