<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Member;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_books' => Book::count(),
            'total_members' => Member::count(),
            'active_loans' => Loan::where('status', 'borrowed')->count(),
            'finished_loans' => Loan::where('status', 'returned')->count(),
            'empty_stock_books' => Book::where('stock', 0)->count(),
            'overdue_loans' => Loan::where('status', 'borrowed')->whereDate('due_date', '<', now()->toDateString())->count(),
        ];

        $latestLoans = Loan::with('member', 'loanItems.book', 'loanItems.bookCopy')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact('stats', 'latestLoans'));
    }
}
