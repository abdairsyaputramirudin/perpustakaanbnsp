<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'member_id',
        'borrow_date',
        'due_date',
        'return_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function loanItems()
    {
        return $this->hasMany(LoanItem::class);
    }

    public function isBorrowed(): bool
    {
        return $this->status === 'borrowed';
    }

    public function isReturned(): bool
    {
        return $this->status === 'returned';
    }

    public function isOverdue(): bool
    {
        if (!$this->isBorrowed()) {
            return false;
        }

        return Carbon::today()->gt($this->due_date);
    }

    public function wasReturnedLate(): bool
    {
        if (!$this->isReturned() || !$this->return_date || !$this->due_date) {
            return false;
        }

        return $this->return_date->gt($this->due_date);
    }
}
