@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-1">Data Anggota</h3>
        <p class="text-muted mb-0">Kelola data anggota perpustakaan.</p>
    </div>
    <a href="{{ route('members.create') }}" class="btn btn-primary">Tambah Anggota</a>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('members.index') }}">
            <div class="row g-2">
                <div class="col-md-9">
                    <input type="text" name="search" class="form-control" value="{{ $search }}" placeholder="Cari kode anggota, nama, email, telepon...">
                </div>
                <div class="col-md-3 d-grid gap-2 d-md-flex justify-content-md-end">
                    <button class="btn btn-primary" type="submit">Cari</button>
                    <a href="{{ route('members.index') }}" class="btn btn-outline-secondary">Reset</a>
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
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Telepon</th>
                    <th>Email</th>
                    <th>Alamat</th>
                    <th width="160">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($members as $member)
                    <tr>
                        <td>{{ $member->member_code }}</td>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->phone }}</td>
                        <td>{{ $member->email }}</td>
                        <td>{{ $member->address }}</td>
                        <td>
                            <a href="{{ route('members.edit', $member) }}" class="btn btn-sm btn-warning">Edit</a>

                            <form action="{{ route('members.destroy', $member) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus anggota ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada data anggota.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $members->links() }}
</div>
@endsection
