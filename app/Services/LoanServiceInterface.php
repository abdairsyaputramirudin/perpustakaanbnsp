<?php

namespace App\Services;

use App\Models\Loan;

interface LoanServiceInterface
{
    public function createLoan(array $data): Loan;

    public function markReturned(Loan $loan): Loan;

    public function deleteLoan(Loan $loan): void;
}

