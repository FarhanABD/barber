@extends('admin.layouts.master')

@section('main_content')
@include('admin.layouts.nav')
@include('admin.layouts.sidebar')

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Edit Layanan</h1>
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

                           <form action="{{ route('admin.karyawan.update', $karyawan->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Nama Karyawan *</label>
        <input type="text"
               class="form-control"
               name="name"
               value="{{ old('name', $karyawan->name) }}"
               required>
    </div>

    <button class="btn btn-success">Update</button>
</form>


                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>
@endsection
