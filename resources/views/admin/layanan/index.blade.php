@extends('admin.layouts.master')
@section('main_content')
@include('admin.layouts.nav')
@include('admin.layouts.sidebar')
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Layanan</h1>
            <div class="ml-auto">
               @if(auth()->user()->role === 'admin')
    <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambahkan Layanan
    </a>
@endif

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
                                            <th>Nama Layanan</th>
                                            <th>Deskripsi</th>
                                            <th>Harga</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
    @forelse ($services as $key => $service)
        <tr>
            <td>{{ $key + 1 }}</td>

            <td>
                {{ $service->name }}
            </td>

            <td>
                {{ $service->description ?? '-' }}
            </td>

            <td>
                Rp {{ number_format($service->price, 0, ',', '.') }}
            </td>

           <td class="pt_10 pb_10">
    @if(auth()->user()->role === 'admin')
        <a href="{{ route('admin.services.edit', $service->id) }}"
           class="btn btn-primary btn-sm">
            <i class="fas fa-edit"></i>
        </a>

        <form action="{{ route('admin.services.destroy', $service->id) }}"
              method="POST"
              style="display:inline-block"
              onsubmit="return confirm('Yakin ingin menghapus data ini?');">
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
            <td colspan="5" class="text-center">
                Data layanan belum tersedia
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