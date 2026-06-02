<?php

namespace App\Models;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    protected $fillable = [
        'code',
        'title',
        'author',
        'publisher',
        'year',
        'category',
        'stock',
        'description',
        'cover_image',
    ];

    public function loanItems()
    {
        return $this->hasMany(LoanItem::class);
    }

    public function copies()
    {
        return $this->hasMany(BookCopy::class);
    }

    public function availableCopies()
    {
        return $this->copies()->where('status', 'available');
    }

    public function syncStockFromCopies(): void
    {
        $this->update([
            'stock' => $this->availableCopies()->count(),
        ]);
    }

    public function isAvailable(): bool
    {
        return $this->stock > 0;
    }

    public function coverUrl(): ?string
    {
        if (!$this->cover_image) {
            return null;
        }

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        return $disk->url($this->cover_image);
    }
}
