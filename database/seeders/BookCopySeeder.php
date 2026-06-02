<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookCopy;
use Illuminate\Database\Seeder;

class BookCopySeeder extends Seeder
{
    public function run(): void
    {
        $books = Book::orderBy('id')->get();

        foreach ($books as $book) {
            $targetAvailable = max(0, (int) $book->stock);
            $currentAvailable = $book->copies()->where('status', 'available')->count();

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

                $copies = $book->copies()
                    ->where('status', 'available')
                    ->orderByDesc('id')
                    ->limit($needToDelete)
                    ->get();

                foreach ($copies as $copy) {
                    $copy->delete();
                }
            }

            $book->syncStockFromCopies();
        }
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

