@extends('layouts.app')

@section('content')
<h3>Detail Peminjaman</h3>

<div class="card mb-3">
    <div class="card-body">
        <p><strong>Anggota:</strong> {{ $loan->member->member_code }} - {{ $loan->member->name }}</p>
        <p><strong>Tanggal Pinjam:</strong> {{ $loan->borrow_date->format('d-m-Y') }}</p>
        <p><strong>Tanggal Harus Kembali:</strong> {{ $loan->due_date->format('d-m-Y') }}</p>
        <p><strong>Tanggal Dikembalikan:</strong> 
            {{ $loan->return_date ? $loan->return_date->format('d-m-Y') : '-' }}
        </p>
        <p><strong>Status:</strong> 
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
        </p>
        <p><strong>Catatan:</strong> {{ $loan->notes ?? '-' }}</p>
    </div>
</div>

<h5>Koleksi yang Dipinjam</h5>

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Kode Buku</th>
            <th>Judul</th>
            <th>Kode Copy</th>
            <th>Jumlah</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($loan->loanItems as $item)
            <tr>
                <td>{{ $item->book->code }}</td>
                <td>{{ $item->book->title }}</td>
                <td>{{ $item->bookCopy?->copy_code ?? '-' }}</td>
                <td>{{ $item->quantity }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<a href="{{ route('loans.index') }}" class="btn btn-secondary">Kembali</a>
@endsection
