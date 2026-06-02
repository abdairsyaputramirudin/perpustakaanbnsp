@extends('layouts.app')

@section('content')
<h3>Tambah Buku</h3>

<form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    @include('books.form')

    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('books.index') }}" class="btn btn-secondary">Kembali</a>
</form>
@endsection
