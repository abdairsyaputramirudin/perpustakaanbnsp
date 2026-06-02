<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Loan;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $staff = User::create([
            'name' => 'Petugas',
            'email' => 'petugas@example.com',
            'password' => 'password',
        ]);

        $this->actingAs($staff);
    }

    private function createMember(array $data = []): Member
    {
        return Member::create(array_merge([
            'member_code' => 'AGT001',
            'name' => 'Budi Santoso',
            'phone' => '08123456789',
            'email' => 'budi@example.com',
            'address' => 'Surabaya',
        ], $data));
    }

    private function createBook(array $data = []): Book
    {
        $book = Book::create(array_merge([
            'code' => 'BK001',
            'title' => 'Dasar Pemrograman',
            'author' => 'Andi',
            'publisher' => 'Informatika',
            'year' => 2024,
            'category' => 'Teknologi',
            'stock' => 3,
            'description' => 'Buku dasar pemrograman.',
        ], $data));

        for ($i = 1; $i <= $book->stock; $i++) {
            BookCopy::create([
                'book_id' => $book->id,
                'copy_code' => sprintf('%s-C%03d', $book->code, $i),
                'status' => 'available',
            ]);
        }

        return $book;
    }

    private function createLoan(Member $member, BookCopy $copy, string $borrowDate = '2026-05-30')
    {
        return $this->post(route('loans.store'), [
            'member_id' => $member->id,
            'borrow_date' => $borrowDate,
            'book_copy_ids' => [$copy->id],
            'notes' => 'Peminjaman untuk belajar.',
        ]);
    }

    public function test_can_create_loan_and_due_date_is_seven_days_after_borrow_date(): void
    {
        $member = $this->createMember();
        $book = $this->createBook();
        $copy = $book->copies()->where('status', 'available')->firstOrFail();
        $response = $this->createLoan($member, $copy);

        $response->assertRedirect(route('loans.index'));

        $this->assertDatabaseHas('loans', [
            'member_id' => $member->id,
            'borrow_date' => '2026-05-30 00:00:00',
            'due_date' => '2026-06-06 00:00:00',
            'status' => 'borrowed',
        ]);

        $this->assertDatabaseHas('loan_items', [
            'book_id' => $book->id,
            'book_copy_id' => $copy->id,
            'quantity' => 1,
        ]);

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'stock' => 2,
        ]);

        $this->assertDatabaseHas('book_copies', [
            'id' => $copy->id,
            'status' => 'borrowed',
        ]);
    }

    public function test_stock_is_restored_when_loan_marked_as_returned(): void
    {
        $member = $this->createMember();
        $book = $this->createBook();
        $copy = $book->copies()->where('status', 'available')->firstOrFail();
        $this->createLoan($member, $copy);

        $loan = Loan::firstOrFail();

        $response = $this->patch(route('loans.return', $loan));

        $response->assertRedirect(route('loans.index'));
        $this->assertDatabaseHas('loans', [
            'id' => $loan->id,
            'status' => 'returned',
        ]);
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'stock' => 3,
        ]);

        $this->assertDatabaseHas('book_copies', [
            'id' => $copy->id,
            'status' => 'available',
        ]);
    }

    public function test_loan_fails_when_no_copy_selected(): void
    {
        $member = $this->createMember();

        $response = $this->from(route('loans.create'))->post(route('loans.store'), [
            'member_id' => $member->id,
            'borrow_date' => '2026-05-30',
            'book_copy_ids' => [],
        ]);

        $response->assertRedirect(route('loans.create'));
        $response->assertSessionHasErrors('book_copy_ids');
        $this->assertDatabaseCount('loans', 0);
    }

    public function test_loan_fails_when_copy_is_not_available(): void
    {
        $member = $this->createMember();
        $book = $this->createBook([
            'code' => 'BK002',
            'stock' => 1,
        ]);
        $copy = $book->copies()->firstOrFail();
        $copy->update(['status' => 'borrowed']);
        $book->syncStockFromCopies();

        $response = $this->from(route('loans.create'))->post(route('loans.store'), [
            'member_id' => $member->id,
            'borrow_date' => '2026-05-30',
            'book_copy_ids' => [$copy->id],
        ]);

        $response->assertRedirect(route('loans.create'));
        $response->assertSessionHasErrors('book_copy_ids');
        $this->assertDatabaseCount('loans', 0);
    }

    public function test_deleting_active_loan_restores_book_stock(): void
    {
        $member = $this->createMember();
        $book = $this->createBook();
        $copy = $book->copies()->where('status', 'available')->firstOrFail();
        $this->createLoan($member, $copy);

        $loan = Loan::firstOrFail();
        $response = $this->delete(route('loans.destroy', $loan));

        $response->assertRedirect(route('loans.index'));
        $this->assertDatabaseMissing('loans', ['id' => $loan->id]);
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'stock' => 3,
        ]);

        $this->assertDatabaseHas('book_copies', [
            'id' => $copy->id,
            'status' => 'available',
        ]);
    }

    public function test_book_with_active_loan_cannot_be_deleted(): void
    {
        $member = $this->createMember();
        $book = $this->createBook();
        $copy = $book->copies()->where('status', 'available')->firstOrFail();
        $this->createLoan($member, $copy);

        $response = $this->delete(route('books.destroy', $book));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('books', ['id' => $book->id]);
    }

    public function test_member_with_active_loan_cannot_be_deleted(): void
    {
        $member = $this->createMember();
        $book = $this->createBook();
        $copy = $book->copies()->where('status', 'available')->firstOrFail();
        $this->createLoan($member, $copy);

        $response = $this->delete(route('members.destroy', $member));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('members', ['id' => $member->id]);
    }
}
