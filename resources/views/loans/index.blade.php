@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-1">Data Peminjaman</h3>
        <p class="text-muted mb-0">Pencatatan peminjaman dan pengembalian eksemplar buku.</p>
    </div>
    <a href="{{ route('loans.create') }}" class="btn btn-primary">Tambah Peminjaman</a>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('loans.index') }}">
            <div class="row g-2">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" value="{{ $search }}" placeholder="Cari nama anggota atau kode anggota...">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="borrowed" {{ $status === 'borrowed' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="returned" {{ $status === 'returned' ? 'selected' : '' }}>Dikembalikan</option>
                    </select>
                </div>
                <div class="col-md-3 d-grid gap-2 d-md-flex justify-content-md-end">
                    <button class="btn btn-primary" type="submit">Cari</button>
                    <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary">Reset</a>
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
                    <th>Anggota</th>
                    <th>Tanggal Pinjam</th>
                    <th>Harus Kembali</th>
                    <th>Status</th>
                    <th>Koleksi</th>
                    <th width="230">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($loans as $loan)
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
                                @if ($loan->wasReturnedLate())
                                    <span class="badge text-bg-danger">Terlambat Dikembalikan</span>
                                @endif
                            @endif
                        </td>
                        <td>
                            <ul class="mb-0">
                                @foreach ($loan->loanItems as $item)
                                    <li>
                                        {{ $item->book->title }}
                                        @if ($item->bookCopy)
                                            ({{ $item->bookCopy->copy_code }})
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            <a href="{{ route('loans.show', $loan) }}" class="btn btn-sm btn-info">Detail</a>

                            @if ($loan->isBorrowed())
                                <form action="{{ route('loans.return', $loan) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-success" onclick="return confirm('Tandai sudah dikembalikan?')">
                                        Kembali
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('loans.destroy', $loan) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus data peminjaman ini?')">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada data peminjaman.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $loans->links() }}
</div>
@endsection
