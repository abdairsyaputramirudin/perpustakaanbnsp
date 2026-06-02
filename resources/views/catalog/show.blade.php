@extends('layouts.app')

@section('content')
<div class="mb-3">
    <a href="{{ route('katalog.index') }}" class="btn btn-sm btn-outline-secondary">Kembali ke Katalog</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        @if ($book->cover_image)
            <img src="{{ $book->coverUrl() }}" alt="Cover {{ $book->title }}" class="img-fluid rounded border mb-3" style="max-height: 320px; object-fit: cover;">
        @else
            <div class="border rounded d-flex align-items-center justify-content-center bg-light text-muted mb-3" style="height: 220px;">
                Tanpa Cover
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <small class="text-muted d-block">{{ $book->code }}</small>
                <h3 class="mb-1">{{ $book->title }}</h3>
                <p class="text-muted mb-0">{{ $book->author ?: '-' }} | {{ $book->year ?: '-' }}</p>
            </div>

            @if ($book->isAvailable())
                <span class="badge text-bg-success">Tersedia</span>
            @else
                <span class="badge text-bg-danger">Habis</span>
            @endif
        </div>

        <div class="mb-3">
            <strong>Penerbit:</strong> {{ $book->publisher ?: '-' }}
        </div>

        <div class="mb-3">
            <strong>Stok Tersedia:</strong> {{ $book->stock }}
        </div>

        <div>
            <strong>Deskripsi:</strong>
            <p class="mb-0 mt-1">{{ $book->description ?: 'Belum ada deskripsi buku.' }}</p>
        </div>
    </div>
</div>
@endsection
