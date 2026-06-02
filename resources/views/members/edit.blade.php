@extends('layouts.app')

@section('content')
<h3>Edit Anggota</h3>

<form action="{{ route('members.update', $member) }}" method="POST">
    @csrf
    @method('PUT')

    @include('members.form')

    <button class="btn btn-primary">Update</button>
    <a href="{{ route('members.index') }}" class="btn btn-secondary">Kembali</a>
</form>
@endsection