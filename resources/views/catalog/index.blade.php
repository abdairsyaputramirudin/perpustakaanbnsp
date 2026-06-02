@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-1">Katalog Koleksi Perpustakaan</h3>
        <p class="text-muted mb-0">Halaman ini khusus untuk anggota melihat koleksi.</p>
    </div>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('katalog.index') }}">
            <div class="row g-2">
                <div class="col-md-9">
                    <input type="text" name="search" class="form-control" value="{{ $search }}" placeholder="Cari kode, judul, penulis, penerbit...">
                </div>
                <div class="col-md-3 d-grid gap-2 d-md-flex justify-content-md-end">
                    <button class="btn btn-primary" type="submit">Cari</button>
                    <a href="{{ route('katalog.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row g-3">
    @forelse ($books as $book)
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    @if ($book->cover_image)
                        <img src="{{ $book->coverUrl() }}" alt="Cover {{ $book->title }}" class="img-fluid rounded" style="height: 140px; width: 100%; object-fit: cover;">
                    @else
                        <div class="border rounded d-flex align-items-center justify-content-center bg-light text-muted" style="height: 140px;">
                            Tanpa Cover
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <small class="text-muted">{{ $book->code }}</small>
                        @if ($book->isAvailable())
                            <span class="badge text-bg-success">Tersedia</span>
                        @else
                            <span class="badge text-bg-danger">Habis</span>
                        @endif
                    </div>

                    <h5 class="card-title mb-1">{{ $book->title }}</h5>
                    <p class="text-muted small mb-2">{{ $book->author ?: '-' }} | {{ $book->publisher ?: '-' }}</p>
                    <p class="card-text small">{{ \Illuminate\Support\Str::limit($book->description ?: 'Tidak ada deskripsi.', 110) }}</p>
                </div>
                <div class="card-footer bg-white border-top-0 pt-0 pb-3">
                    <a href="{{ route('katalog.show', $book) }}" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-secondary mb-0">Belum ada data buku.</div>
        </div>
    @endforelse
</div>

<div class="mt-3">
    {{ $books->links() }}
</div>
@endsection
