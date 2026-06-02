<div class="mb-3">
    <label class="form-label">Kode Buku</label>
    <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
           value="{{ old('code', $book->code ?? '') }}">
    @error('code')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Judul</label>
    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
           value="{{ old('title', $book->title ?? '') }}">
    @error('title')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Penulis</label>
    <input type="text" name="author" class="form-control"
           value="{{ old('author', $book->author ?? '') }}">
</div>

<div class="mb-3">
    <label class="form-label">Penerbit</label>
    <input type="text" name="publisher" class="form-control"
           value="{{ old('publisher', $book->publisher ?? '') }}">
</div>

<div class="mb-3">
    <label class="form-label">Tahun</label>
    <input type="number" name="year" class="form-control @error('year') is-invalid @enderror"
           value="{{ old('year', $book->year ?? '') }}">
    @error('year')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Kategori</label>
    <input type="text" name="category" class="form-control"
           value="{{ old('category', $book->category ?? '') }}">
</div>

<div class="mb-3">
    <label class="form-label">Jumlah Copy Tersedia</label>
    <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror"
           value="{{ old('stock', $book->stock ?? 0) }}">
    <small class="text-muted">Nilai ini akan dipakai untuk sinkronisasi jumlah copy dengan status tersedia.</small>
    @error('stock')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Deskripsi</label>
    <textarea name="description" class="form-control" rows="3">{{ old('description', $book->description ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label class="form-label">Cover Buku</label>
    <input type="file" name="cover_image" class="form-control @error('cover_image') is-invalid @enderror" accept="image/*">
    @error('cover_image')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    @if (!empty($book?->cover_image))
        <div class="mt-2">
            <img src="{{ $book->coverUrl() }}" alt="Cover {{ $book->title }}" class="img-thumbnail" style="max-height: 140px;">
        </div>
    @endif
</div>
