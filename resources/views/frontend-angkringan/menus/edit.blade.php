@extends('admin.layouts.master')

@section('main_content')
@include('admin.layouts.nav')
@include('admin.layouts.sidebar')

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Edit Menu</h1>
            <div class="ml-auto">
                <a href="{{ route('admin.menus.index') }}" class="btn btn-primary">
                    <i class="fas fa-list"></i> View All
                </a>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <form action="{{ route('admin.menus.update', $menu->id_menu) }}"
                                  method="POST"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                {{-- Kategori & Nama Menu --}}
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kategori *</label>
                                        <select name="category"
                                                class="form-control @error('category') is-invalid @enderror"
                                                required>
                                            <option value="">-- Pilih Kategori --</option>
                                            @foreach($categories as $category)
                                              <option value="{{ $category->id_category }}"
    {{ old('category', $menu->category) == $category->id_category ? 'selected' : '' }}>
    {{ $category->nama }}
</option>

                                            @endforeach
                                        </select>

                                        @error('category')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama Menu *</label>
                                        <input type="text"
                                               name="nama"
                                               class="form-control @error('nama') is-invalid @enderror"
                                               value="{{ old('nama', $menu->nama) }}"
                                               required>

                                        @error('nama')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Harga & Status --}}
                                <div class="row">
                                      <div class="col-md-6 mb-3">
                                        <label class="form-label">HPP Produk *</label>
                                        <input type="number"
                                               name="hpp"
                                               class="form-control @error('hpp') is-invalid @enderror"
                                               value="{{ old('hpp', $menu->hpp) }}"
                                               required>

                                        @error('hpp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Harga Eceran Tertinggi *</label>
                                        <input type="number"
                                               name="harga"
                                               class="form-control @error('harga') is-invalid @enderror"
                                               value="{{ old('harga', $menu->harga) }}"
                                               required>

                                        @error('harga')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                  

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Status *</label>
                                        <select name="status"
                                                class="form-control @error('status') is-invalid @enderror"
                                                required>
                                            <option value="">-- Pilih Status --</option>
                                            <option value="1" {{ old('status', $menu->status) == 1 ? 'selected' : '' }}>Aktif</option>
                                            <option value="0" {{ old('status', $menu->status) == 0 ? 'selected' : '' }}>Nonaktif</option>
                                        </select>

                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Gambar --}}
                                <div class="mb-3">
                                    <label class="form-label">Gambar Menu</label>
                                    <input type="file"
                                           name="gambar"
                                           class="form-control @error('gambar') is-invalid @enderror">

                                    @error('gambar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                 @if($menu->gambar)
    <div class="mt-2">
        <img src="{{ asset('storage/'.$menu->gambar) }}"
             alt="Gambar Menu"
             class="img-thumbnail"
             width="150">
    </div>
@endif

                                </div>

                                
                                <div class="mb-3">
    <label class="form-label">Mitra</label>
    <select name="mitra_id" class="form-control">
        <option value="">-- Tanpa Mitra --</option>
        @foreach($mitras as $mitra)
            <option value="{{ $mitra->id_mitra }}"
                {{ old('mitra_id', $menu->mitra_id ?? '') == $mitra->id_mitra ? 'selected' : '' }}>
                {{ $mitra->nama_mitra }}
            </option>
        @endforeach
    </select>
</div>

                                {{-- Deskripsi --}}
                                <div class="mb-3">
                                    <label class="form-label">Deskripsi</label>
                                    <textarea name="deskripsi"
                                              class="form-control @error('deskripsi') is-invalid @enderror"
                                              rows="3">{{ old('deskripsi', $menu->deskripsi) }}</textarea>

                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Button --}}
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Update
                                    </button>
                                    <a href="{{ route('admin.menus.index') }}"
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
