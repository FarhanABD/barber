@extends('admin.layouts.master')

@section('main_content')
@include('admin.layouts.nav')
@include('admin.layouts.sidebar')

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Master Data Karyawan</h1>
            <div class="ml-auto">
                <a href="{{ route('admin.master-karyawan.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Karyawan
                </a>
            </div>
        </div>

        {{-- WIDGET STATISTIK RINGKAS --}}
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Karyawan</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalKaryawan }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-info">
                        <i class="fas fa-cut"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Divisi Barber</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalBarber }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Divisi Angkringan</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalAngkringan }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Karyawan Aktif</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalAktif }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- FILTER CARD --}}
        <div class="card mb-3">
            <div class="card-body py-3">
                <form method="GET" action="{{ route('admin.master-karyawan.index') }}">
                    <div class="row align-items-center">
                        <div class="col-md-3 mb-2">
                            <label class="font-weight-bold mb-1">Filter Divisi</label>
                            <select name="divisi" class="form-control" onchange="this.form.submit()">
                                <option value="all" {{ request('divisi') == 'all' ? 'selected' : '' }}>-- Semua Divisi --</option>
                                <option value="angkringan" {{ request('divisi') == 'angkringan' ? 'selected' : '' }}>Angkringan</option>
                                <option value="barber" {{ request('divisi') == 'barber' ? 'selected' : '' }}>Barber</option>
                                <option value="lainnya" {{ request('divisi') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="font-weight-bold mb-1">Filter Status</label>
                            <select name="status" class="form-control" onchange="this.form.submit()">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>-- Semua Status --</option>
                                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="font-weight-bold mb-1">Pencarian</label>
                            <input type="text" name="search" class="form-control" placeholder="Cari nama karyawan, alamat..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2 mb-2 d-flex align-items-end" style="height: 64px;">
                            <button type="submit" class="btn btn-primary mr-1"><i class="fas fa-search"></i> Cari</button>
                            <a href="{{ route('admin.master-karyawan.index') }}" class="btn btn-secondary"><i class="fas fa-sync"></i> Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- TABLE DATA --}}
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="example1">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="50">No</th>
                                            <th>Nama Karyawan</th>
                                            <th>Divisi</th>
                                            <th>Alamat</th>
                                            <th class="text-center">Status</th>
                                            <th width="140" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($karyawans as $key => $karyawan)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td class="font-weight-bold">{{ $karyawan->nama }}</td>
                                            <td>
                                                @if($karyawan->divisi === 'barber')
                                                    <span class="badge badge-info"><i class="fas fa-cut"></i> Barber</span>
                                                @elseif($karyawan->divisi === 'angkringan')
                                                    <span class="badge badge-warning text-white"><i class="fas fa-utensils"></i> Angkringan</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ ucfirst($karyawan->divisi) }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $karyawan->alamat ?? '-' }}</td>
                                            <td class="text-center">
                                                @if($karyawan->status === 'aktif')
                                                    <span class="badge badge-success">Aktif</span>
                                                @else
                                                    <span class="badge badge-danger">Nonaktif</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.master-karyawan.show', $karyawan->id) }}" class="btn btn-info btn-sm" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.master-karyawan.edit', $karyawan->id) }}" class="btn btn-primary btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.master-karyawan.destroy', $karyawan->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data karyawan ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                <i class="fas fa-users-slash fa-2x mb-2 d-block"></i>
                                                Belum ada data karyawan.
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
