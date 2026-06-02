@extends('layouts.app')

@section('content')
<h3>Tambah Peminjaman</h3>

<form action="{{ route('loans.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label class="form-label">Anggota</label>
        <select name="member_id" class="form-select @error('member_id') is-invalid @enderror">
            <option value="">Pilih Anggota</option>
            @foreach ($members as $member)
                <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                    {{ $member->member_code }} - {{ $member->name }}
                </option>
            @endforeach
        </select>
        @error('member_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Tanggal Pinjam</label>
        <input type="date" name="borrow_date" class="form-control @error('borrow_date') is-invalid @enderror"
               value="{{ old('borrow_date', date('Y-m-d')) }}">
        <small class="text-muted">Tanggal harus kembali otomatis 7 hari dari tanggal pinjam.</small>
        @error('borrow_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Pilih Eksemplar Buku (Copy)</label>
        <div class="border rounded p-3">
            @forelse ($books as $book)
                <div class="mb-2">
                    <p class="mb-1 fw-semibold">{{ $book->code }} - {{ $book->title }}</p>
                    @foreach ($book->copies as $copy)
                        <div class="form-check ms-3">
                            <input class="form-check-input" type="checkbox" name="book_copy_ids[]"
                                   value="{{ $copy->id }}" id="copy{{ $copy->id }}"
                                   {{ in_array($copy->id, old('book_copy_ids', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="copy{{ $copy->id }}">
                                Copy: {{ $copy->copy_code }}
                            </label>
                        </div>
                    @endforeach
                </div>
            @empty
                <p class="mb-0 text-danger">Belum ada copy buku dengan status tersedia.</p>
            @endforelse
        </div>
        @error('book_copy_ids')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Catatan</label>
        <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
    </div>

    <button class="btn btn-primary">Simpan Peminjaman</button>
    <a href="{{ route('loans.index') }}" class="btn btn-secondary">Kembali</a>
</form>
@endsection
