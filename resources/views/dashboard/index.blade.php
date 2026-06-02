@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h3 class="mb-1">Dashboard Perpustakaan</h3>
    <p class="text-muted mb-0">Ringkasan data utama dan aktivitas peminjaman terbaru.</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4 col-xl-2">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <small class="text-muted d-block">Total Buku</small>
                <h4 class="mb-0">{{ $stats['total_books'] }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-xl-2">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <small class="text-muted d-block">Total Anggota</small>
                <h4 class="mb-0">{{ $stats['total_members'] }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-xl-2">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <small class="text-muted d-block">Peminjaman Aktif</small>
                <h4 class="mb-0">{{ $stats['active_loans'] }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-xl-2">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <small class="text-muted d-block">Peminjaman Selesai</small>
                <h4 class="mb-0">{{ $stats['finished_loans'] }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-xl-2">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <small class="text-muted d-block">Stok Buku Habis</small>
                <h4 class="mb-0">{{ $stats['empty_stock_books'] }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-xl-2">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <small class="text-muted d-block">Peminjaman Terlambat</small>
                <h4 class="mb-0 text-danger">{{ $stats['overdue_loans'] }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <strong>5 Peminjaman Terbaru</strong>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Anggota</th>
                        <th>Tanggal Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                        <th>Koleksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($latestLoans as $loan)
                        <tr>
                            <td>{{ $loan->member->name }}</td>
                            <td>{{ $loan->borrow_date->format('d-m-Y') }}</td>
                            <td>{{ $loan->due_date->format('d-m-Y') }}</td>
                            <td>
                                @if ($loan->isBorrowed())
                                    <span class="badge text-bg-warning">Dipinjam</span>
                                    @if ($loan->isOverdue())
                                        <span class="badge text-bg-danger">Terlambat</span>
                                    @endif
                                @else
                                    <span class="badge text-bg-success">Dikembalikan</span>
                                @endif
                            </td>
                            <td>
                                @foreach ($loan->loanItems as $item)
                                    <span class="badge text-bg-light border">
                                        {{ $item->book->title }}
                                        @if ($item->bookCopy)
                                            ({{ $item->bookCopy->copy_code }})
                                        @endif
                                    </span>
                                @endforeach
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-3">Belum ada data peminjaman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
