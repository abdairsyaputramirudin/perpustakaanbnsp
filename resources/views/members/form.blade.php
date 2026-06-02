<div class="mb-3">
    <label class="form-label">Kode Anggota</label>
    <input type="text" name="member_code" class="form-control @error('member_code') is-invalid @enderror"
           value="{{ old('member_code', $member->member_code ?? '') }}">
    @error('member_code')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Nama</label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $member->name ?? '') }}">
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Telepon</label>
    <input type="text" name="phone" class="form-control"
           value="{{ old('phone', $member->phone ?? '') }}">
</div>

<div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
           value="{{ old('email', $member->email ?? '') }}">
    @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Alamat</label>
    <textarea name="address" class="form-control" rows="3">{{ old('address', $member->address ?? '') }}</textarea>
</div>