@extends('admin.layouts.master')

@section('main_content')

@include('admin.layouts.nav')
@include('admin.layouts.sidebar')

<div class="main-content">
<section class="section">

<div class="section-header justify-content-between">
    <h1>Data Mitra Angkringan</h1>
    <div class="ml-auto">
        <a href="{{ route('admin.mitras.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Mitra
        </a>
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
    <th>Nama Mitra</th>
    <th>No HP</th>
    <th>Alamat</th>
    <th>Status</th>
    <th width="120">Action</th>
</tr>
</thead>

<tbody>
@forelse ($mitras as $key => $mitra)
<tr>
    <td>{{ $key + 1 }}</td>
    <td>{{ $mitra->nama_mitra }}</td>
    <td>{{ $mitra->kontak }}</td>
    <td>{{ $mitra->alamat ?? '-' }}</td>

    {{-- STATUS --}}
    <td class="text-center">
        @if($mitra->status)
            <span class="badge badge-success">Aktif</span>
        @else
            <span class="badge badge-danger">Nonaktif</span>
        @endif
    </td>

    {{-- ACTION --}}
    <td>
        @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.mitras.edit', $mitra->id_mitra) }}"
               class="btn btn-primary btn-sm">
                <i class="fas fa-edit"></i>
            </a>

             {{-- VIEW --}}
    <a href="{{ route('admin.mitras.show', $mitra->id_mitra) }}"
       class="btn btn-info btn-sm"
       title="Lihat Penjualan Mitra">
        <i class="fas fa-eye"></i>
    </a>

            <form action="{{ route('admin.mitras.destroy', $mitra->id_mitra) }}"
                  method="POST"
                  style="display:inline-block"
                  onsubmit="return confirm('Yakin ingin menghapus mitra ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        @else
            <span class="badge badge-secondary">No Access</span>
        @endif
    </td>
</tr>
@empty
<tr>
    <td colspan="7" class="text-center">
        Data mitra belum tersedia
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
