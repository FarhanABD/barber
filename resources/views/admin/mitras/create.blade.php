@extends('admin.layouts.master')

@section('main_content')
@include('admin.layouts.nav')
@include('admin.layouts.sidebar')

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Tambah Mitra</h1>
            <div class="ml-auto">
                <a href="{{ route('admin.mitras.index') }}" class="btn btn-primary">
                    <i class="fas fa-list"></i> View All
                </a>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin.mitras.store') }}" method="POST">
                                @csrf

                                <div class="row">
                                    {{-- Nama Mitra --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama Mitra *</label>
                                        <input type="text"
                                               name="nama_mitra"
                                               class="form-control @error('nama_mitra') is-invalid @enderror"
                                               value="{{ old('nama_mitra') }}"
                                               required>

                                        @error('nama_mitra')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Kontak --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kontak</label>
                                        <input type="text"
                                               name="kontak"
                                               class="form-control @error('kontak') is-invalid @enderror"
                                               value="{{ old('kontak') }}">

                                        @error('kontak')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    {{-- Email --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email"
                                               name="email"
                                               class="form-control @error('email') is-invalid @enderror"
                                               value="{{ old('email') }}">

                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Status --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Status *</label>
                                        <select name="status"
                                                class="form-control @error('status') is-invalid @enderror"
                                                required>
                                            <option value="">-- Pilih Status --</option>
                                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Aktif</option>
                                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Nonaktif</option>
                                        </select>

                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Alamat --}}
                                <div class="mb-3">
                                    <label class="form-label">Alamat</label>
                                    <textarea name="alamat"
                                              class="form-control @error('alamat') is-invalid @enderror"
                                              rows="3">{{ old('alamat') }}</textarea>

                                    @error('alamat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Button --}}
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Simpan
                                    </button>
                                    <a href="{{ route('admin.mitras.index') }}"
                                       class="btn btn-danger ml-2">
                                        Batal
                                    </a>
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
