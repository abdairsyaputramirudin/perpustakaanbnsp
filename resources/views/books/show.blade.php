@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Detail Buku</h3>
    <a href="{{ route('books.index') }}" class="btn btn-secondary">Kembali</a>
</div>

@php
    $availableCount = $book->copies->where('status', 'available')->count();
    $borrowedCount = $book->copies->where('status', 'borrowed')->count();
@endphp

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                @if ($book->cover_image)
                    <img src="{{ $book->coverUrl() }}" alt="Cover {{ $book->title }}" class="img-fluid rounded border">
                @else
                    <div class="border rounded bg-light text-muted d-flex align-items-center justify-content-center" style="height: 240px;">
                        Tanpa Cover
                    </div>
                @endif
            </div>
            <div class="col-md-9">
                <p class="mb-1"><strong>Kode Buku:</strong> {{ $book->code }}</p>
                <p class="mb-1"><strong>Judul:</strong> {{ $book->title }}</p>
                <p class="mb-1"><strong>Penerbit:</strong> {{ $book->publisher ?: '-' }}</p>
                <p class="mb-1"><strong>Penulis:</strong> {{ $book->author ?: '-' }}</p>
                <p class="mb-1"><strong>Tahun:</strong> {{ $book->year ?: '-' }}</p>
                <p class="mb-1"><strong>Deskripsi:</strong> {{ $book->description ?: '-' }}</p>

                <hr>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge text-bg-success">Tersedia: {{ $availableCount }}</span>
                    <span class="badge text-bg-warning">Dipinjam: {{ $borrowedCount }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <strong>Daftar Eksemplar Buku</strong>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Kode Copy</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($book->copies as $copy)
                    <tr>
                        <td>{{ $copy->copy_code }}</td>
                        <td>
                            @if ($copy->status === 'available')
                                <span class="badge text-bg-success">Tersedia</span>
                            @elseif ($copy->status === 'borrowed')
                                <span class="badge text-bg-warning">Dipinjam</span>
                            @elseif ($copy->status === 'damaged')
                                <span class="badge text-bg-secondary">Rusak</span>
                            @else
                                <span class="badge text-bg-danger">Hilang</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center">Belum ada data copy.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
