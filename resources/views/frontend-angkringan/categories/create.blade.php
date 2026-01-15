@extends('admin.layouts.master')

@section('main_content')
@include('admin.layouts.nav')
@include('admin.layouts.sidebar')

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Tambah Kategori Menu</h1>
            <div class="ml-auto">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-primary">
                    <i class="fas fa-list"></i> View All
                </a>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">

                            <form action="{{ route('admin.categories.store') }}" method="POST">
                                @csrf

                                {{-- Nama Kategori --}}
                                <div class="mb-3">
                                    <label class="form-label">Nama Kategori *</label>
                                    <input type="text"
                                           name="nama"
                                           class="form-control @error('nama') is-invalid @enderror"
                                           value="{{ old('nama') }}"
                                           required>

                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Status --}}
                                <div class="mb-3">
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

                                {{-- Button --}}
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Simpan
                                    </button>
                                    <a href="{{ route('admin.categories.index') }}"
                                       class="btn btn-secondary ml-2">
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
