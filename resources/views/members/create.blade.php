@extends('layouts.app')

@section('content')
<h3>Tambah Anggota</h3>

<form action="{{ route('members.store') }}" method="POST">
    @csrf

    @include('members.form')

    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('members.index') }}" class="btn btn-secondary">Kembali</a>
</form>
@endsection