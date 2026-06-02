<?php

namespace App\Services;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LoanService implements LoanServiceInterface
{
    public function createLoan(array $data): Loan
    {
        return DB::transaction(function () use ($data) {
            $borrowDate = Carbon::parse($data['borrow_date']);
            $copyIds = array_values(array_unique($data['book_copy_ids']));

            $copies = BookCopy::whereIn('id', $copyIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($copyIds as $copyId) {
                $copy = $copies->get($copyId);

                if (!$copy || !$copy->isAvailable()) {
                    Log::warning('Peminjaman gagal: copy tidak tersedia', [
                        'book_copy_id' => $copyId,
                        'member_id' => $data['member_id'],
                    ]);

                    throw ValidationException::withMessages([
                        'book_copy_ids' => 'Salah satu copy buku sudah tidak tersedia. Silakan pilih ulang.',
                    ]);
                }
            }

            $loan = Loan::create([
                'member_id' => $data['member_id'],
                'borrow_date' => $borrowDate->toDateString(),
                'due_date' => $borrowDate->copy()->addDays(7)->toDateString(),
                'status' => 'borrowed',
                'notes' => $data['notes'] ?? null,
            ]);

            $affectedBookIds = [];

            foreach ($copyIds as $copyId) {
                $copy = $copies->get($copyId);

                $loan->loanItems()->create([
                    'book_id' => $copy->book_id,
                    'book_copy_id' => $copy->id,
                    'quantity' => 1,
                ]);

                $copy->update(['status' => 'borrowed']);
                $affectedBookIds[] = $copy->book_id;
            }

            $this->syncBookStockByIds($affectedBookIds);

            Log::info('Peminjaman dibuat', [
                'loan_id' => $loan->id,
                'member_id' => $loan->member_id,
                'book_count' => count($copyIds),
            ]);

            return $loan->fresh(['member', 'loanItems.book', 'loanItems.bookCopy']);
        });
    }

    public function markReturned(Loan $loan): Loan
    {
        if ($loan->isReturned()) {
            return $loan;
        }

        return DB::transaction(function () use ($loan) {
            $loan->load('loanItems.bookCopy');
            $affectedBookIds = [];

            foreach ($loan->loanItems as $item) {
                $copy = BookCopy::whereKey($item->book_copy_id)->lockForUpdate()->first();

                if ($copy && $copy->status === 'borrowed') {
                    $copy->update(['status' => 'available']);
                    $affectedBookIds[] = $copy->book_id;
                    continue;
                }

                if (!$copy && $item->book_copy_id === null) {
                    $book = Book::whereKey($item->book_id)->lockForUpdate()->first();

                    if ($book) {
                        $book->increment('stock', $item->quantity);
                    }
                }
            }

            $this->syncBookStockByIds($affectedBookIds);

            $loan->update([
                'status' => 'returned',
                'return_date' => now()->toDateString(),
            ]);

            Log::info('Buku dikembalikan', [
                'loan_id' => $loan->id,
                'member_id' => $loan->member_id,
            ]);

            return $loan->fresh(['member', 'loanItems.book', 'loanItems.bookCopy']);
        });
    }

    public function deleteLoan(Loan $loan): void
    {
        DB::transaction(function () use ($loan) {
            $loan->load('loanItems.bookCopy');
            $affectedBookIds = [];

            if ($loan->isBorrowed()) {
                foreach ($loan->loanItems as $item) {
                    $copy = BookCopy::whereKey($item->book_copy_id)->lockForUpdate()->first();

                    if ($copy && $copy->status === 'borrowed') {
                        $copy->update(['status' => 'available']);
                        $affectedBookIds[] = $copy->book_id;
                        continue;
                    }

                    if (!$copy && $item->book_copy_id === null) {
                        $book = Book::whereKey($item->book_id)->lockForUpdate()->first();

                        if ($book) {
                            $book->increment('stock', $item->quantity);
                        }
                    }
                }
            }

            $this->syncBookStockByIds($affectedBookIds);
            $loan->delete();
        });
    }

    private function syncBookStockByIds(array $bookIds): void
    {
        $bookIds = array_values(array_unique(array_filter($bookIds)));

        if ($bookIds === []) {
            return;
        }

        $availableCounts = BookCopy::query()
            ->selectRaw('book_id, COUNT(*) as total')
            ->whereIn('book_id', $bookIds)
            ->where('status', 'available')
            ->groupBy('book_id')
            ->pluck('total', 'book_id');

        $books = Book::whereIn('id', $bookIds)->lockForUpdate()->get();

        foreach ($books as $book) {
            $book->update([
                'stock' => (int) ($availableCounts[$book->id] ?? 0),
            ]);
        }
    }
}
