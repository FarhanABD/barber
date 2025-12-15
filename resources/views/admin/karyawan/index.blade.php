@extends('admin.layouts.master')
@section('main_content')
@include('admin.layouts.nav')
@include('admin.layouts.sidebar')
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Karyawan</h1>
            <div class="ml-auto">
                <a href="{{ route('admin.karyawan.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambahkan Karyawan</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="example1">
                                    <thead>
                                        <tr>
                                           <th>No</th>
<th>Nama Karyawan</th>
<th>Action</th>

                                        </tr>
                                    </thead>
                                  <tbody>
@forelse ($karyawans as $key => $karyawan)
<tr>
    <td>{{ $key + 1 }}</td>
    <td>{{ $karyawan->name }}</td>

    <td class="pt_10 pb_10">
        <a href="{{ route('admin.karyawan.edit', $karyawan->id) }}"
           class="btn btn-primary btn-sm">
            <i class="fas fa-edit"></i>
        </a>

        <form action="{{ route('admin.karyawan.destroy', $karyawan->id) }}"
              method="POST"
              style="display:inline-block"
              onsubmit="return confirm('Yakin ingin menghapus karyawan ini?')">
            @csrf
            @method('DELETE')

            <button class="btn btn-danger btn-sm">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    </td>
</tr>
@empty
<tr>
    <td colspan="3" class="text-center">
        Data karyawan belum tersedia
    </td>
</tr>
@endforelse
</tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection