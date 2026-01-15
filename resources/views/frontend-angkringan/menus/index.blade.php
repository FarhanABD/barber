@extends('admin.layouts.master')

@section('main_content')
@include('admin.layouts.nav')
@include('admin.layouts.sidebar')

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Menu</h1>
            <div class="ml-auto">
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.menus.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Menu
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
                                            <th>Gambar</th>
                                            <th>Nama Menu</th>
                                            <th>Kategori</th>
                                            <th>Harga</th>
                                            <th>Mitra</th>
                                            <th>Status</th>
                                            <th width="120">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($menus as $key => $menu)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>

                                                {{-- Gambar --}}
                                                <td class="text-center">
                                                    @if($menu->gambar)
                                                      <img src="{{ asset('storage/' . $menu->gambar) }}" width="100">

                                                    @else
                                                        <span class="badge badge-secondary">No Image</span>
                                                    @endif
                                                </td>

                                                {{-- Nama --}}
                                                <td>{{ $menu->nama }}</td>

                                                {{-- Kategori --}}
                                                <td>
                                                    {{ $menu->categoryData->nama ?? '-' }}
                                                </td>
                                                {{-- Harga --}}
                                                <td>
                                                    Rp {{ number_format($menu->harga, 0, ',', '.') }}
                                                </td>

                                                    <td>
    {{ $menu->mitra->nama_mitra ?? '-' }}
</td>

                                                {{-- Status --}}
                                                <td class="text-center">
                                                    @if($menu->status)
                                                        <span class="badge badge-success">Aktif</span>
                                                    @else
                                                        <span class="badge badge-danger">Nonaktif</span>
                                                    @endif
                                                </td>

                                                {{-- Action --}}
                                                <td>
                                                    @if(auth()->user()->role === 'admin')
                                                        <a href="{{ route('admin.menus.edit', $menu->id_menu) }}"
                                                           class="btn btn-primary btn-sm">
                                                            <i class="fas fa-edit"></i>
                                                        </a>

                                                        <form action="{{ route('admin.menus.destroy', $menu->id_menu) }}"
                                                              method="POST"
                                                              style="display:inline-block"
                                                              onsubmit="return confirm('Yakin ingin menghapus menu ini?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                    class="btn btn-danger btn-sm">
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
                                                    Data menu belum tersedia
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
