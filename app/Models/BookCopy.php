<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookCopy extends Model
{
    protected $fillable = [
        'book_id',
        'copy_code',
        'status',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function loanItems()
    {
        return $this->hasMany(LoanItem::class);
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }
}

