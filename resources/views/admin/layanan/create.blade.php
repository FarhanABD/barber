@extends('admin.layouts.master')

@section('main_content')
@include('admin.layouts.nav')
@include('admin.layouts.sidebar')

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Create Layanan</h1>
            <div class="ml-auto">
                <a href="{{ route('admin.services.index') }}" class="btn btn-primary">
                    <i class="fas fa-list"></i> View All
                </a>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <form action="{{ route('admin.services.store') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">Nama Layanan *</label>
                                    <input type="text"
                                           name="name"
                                           class="form-control"
                                           value="{{ old('name') }}"
                                           required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Deskripsi</label>
                                    <textarea name="description"
                                              class="form-control"
                                              rows="4">{{ old('description') }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Harga *</label>
                                    <input type="number"
                                           name="price"
                                           class="form-control"
                                           step="0.01"
                                           value="{{ old('price') }}"
                                           required>
                                </div>

                                <div class="mb-3">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Simpan
                                    </button>
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
