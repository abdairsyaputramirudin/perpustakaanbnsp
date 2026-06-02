@extends('layouts.app')

@section('content')
<h3>Edit Buku</h3>

<form action="{{ route('books.update', $book) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    @include('books.form')

    <button class="btn btn-primary">Update</button>
    <a href="{{ route('books.index') }}" class="btn btn-secondary">Kembali</a>
</form>
@endsection
