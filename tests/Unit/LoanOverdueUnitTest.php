<?php

namespace Tests\Unit;

use App\Models\Loan;
use Carbon\Carbon;
use Tests\TestCase;

class LoanOverdueUnitTest extends TestCase
{
    public function test_is_overdue_returns_true_for_borrowed_loan_past_due_date(): void
    {
        Carbon::setTestNow('2026-06-10');

        $loan = new Loan([
            'status' => 'borrowed',
            'due_date' => '2026-06-05',
        ]);

        $this->assertTrue($loan->isOverdue());

        Carbon::setTestNow();
    }

    public function test_is_overdue_returns_false_for_returned_loan(): void
    {
        Carbon::setTestNow('2026-06-10');

        $loan = new Loan([
            'status' => 'returned',
            'due_date' => '2026-06-05',
        ]);

        $this->assertFalse($loan->isOverdue());

        Carbon::setTestNow();
    }
}

