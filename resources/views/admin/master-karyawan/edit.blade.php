@extends('admin.layouts.master')

@section('main_content')
@include('admin.layouts.nav')
@include('admin.layouts.sidebar')

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Edit Data Karyawan</h1>
            <div class="ml-auto">
                <a href="{{ route('admin.master-karyawan.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-10 offset-md-1">
                    <div class="card">
                        <div class="card-header">
                            <h4>Edit Karyawan: {{ $karyawan->nama }}</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.master-karyawan.update', $karyawan->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    {{-- Nama Karyawan --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label font-weight-bold">Nama Lengkap Karyawan <span class="text-danger">*</span></label>
                                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $karyawan->nama) }}" required placeholder="Contoh: Budi Santoso">
                                        @error('nama')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Divisi --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label font-weight-bold">Divisi / Penempatan <span class="text-danger">*</span></label>
                                        <select name="divisi" class="form-control @error('divisi') is-invalid @enderror" required>
                                            <option value="">-- Pilih Divisi --</option>
                                            <option value="angkringan" {{ old('divisi', $karyawan->divisi) == 'angkringan' ? 'selected' : '' }}>Angkringan</option>
                                            <option value="barber" {{ old('divisi', $karyawan->divisi) == 'barber' ? 'selected' : '' }}>Barber</option>
                                            <option value="lainnya" {{ old('divisi', $karyawan->divisi) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                        @error('divisi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Status --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label font-weight-bold">Status <span class="text-danger">*</span></label>
                                        <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                            <option value="aktif" {{ old('status', $karyawan->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                            <option value="nonaktif" {{ old('status', $karyawan->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Alamat --}}
                                    <div class="col-12 mb-3">
                                        <label class="form-label font-weight-bold">Alamat</label>
                                        <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3" placeholder="Masukkan alamat tempat tinggal karyawan...">{{ old('alamat', $karyawan->alamat) }}</textarea>
                                        @error('alamat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mt-4 border-top pt-3 text-right">
                                    <a href="{{ route('admin.master-karyawan.index') }}" class="btn btn-secondary mr-2">Batal</a>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Perbarui Data</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
