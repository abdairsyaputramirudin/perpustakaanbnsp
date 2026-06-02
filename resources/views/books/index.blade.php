@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-1">Katalog Koleksi Buku</h3>
        <p class="text-muted mb-0">Kelola koleksi buku dan ketersediaan eksemplar.</p>
    </div>
    <a href="{{ route('books.create') }}" class="btn btn-primary">Tambah Buku</a>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('books.index') }}">
            <div class="row g-2">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" value="{{ $search }}" placeholder="Cari kode, judul, penulis, penerbit...">
                </div>
                <div class="col-md-3">
                    <select name="stock" class="form-select">
                        <option value="all" {{ $stock === 'all' ? 'selected' : '' }}>Semua Stok</option>
                        <option value="available" {{ $stock === 'available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="empty" {{ $stock === 'empty' ? 'selected' : '' }}>Habis</option>
                    </select>
                </div>
                <div class="col-md-3 d-grid gap-2 d-md-flex justify-content-md-end">
                    <button class="btn btn-primary" type="submit">Cari</button>
                    <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-bordered table-striped mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Cover</th>
                    <th>Kode</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Penerbit</th>
                    <th>Tahun</th>
                    <th>Stok</th>
                    <th width="250">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($books as $book)
                    <tr>
                        <td>
                            @if ($book->cover_image)
                                <img src="{{ $book->coverUrl() }}" alt="Cover {{ $book->title }}" class="rounded border" style="width: 54px; height: 72px; object-fit: cover;">
                            @else
                                <div class="border rounded bg-light text-muted d-flex align-items-center justify-content-center" style="width: 54px; height: 72px; font-size: 10px;">
                                    No Cover
                                </div>
                            @endif
                        </td>
                        <td>{{ $book->code }}</td>
                        <td>{{ $book->title }}</td>
                        <td>{{ $book->author }}</td>
                        <td>{{ $book->publisher ?: '-' }}</td>
                        <td>{{ $book->year }}</td>
                        <td>
                            <span class="me-1">{{ $book->stock }}</span>
                            @if ($book->isAvailable())
                                <span class="badge text-bg-success">Tersedia</span>
                            @else
                                <span class="badge text-bg-danger">Habis</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('books.show', $book) }}" class="btn btn-sm btn-info">Detail</a>
                            <a href="{{ route('books.edit', $book) }}" class="btn btn-sm btn-warning">Edit</a>

                            <form action="{{ route('books.destroy', $book) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus buku ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Belum ada data buku.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $books->links() }}
</div>
@endsection
