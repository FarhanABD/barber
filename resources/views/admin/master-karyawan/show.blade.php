@extends('admin.layouts.master')

@section('main_content')
@include('admin.layouts.nav')
@include('admin.layouts.sidebar')

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Detail Karyawan</h1>
            <div class="ml-auto">
                <a href="{{ route('admin.master-karyawan.edit', $karyawan->id) }}" class="btn btn-primary mr-1">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('admin.master-karyawan.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                {{-- PROFIL SINGKAT --}}
                <div class="col-12 col-md-4">
                    <div class="card card-hero">
                        <div class="card-header" style="background: linear-gradient(135deg, #6777ef 0%, #3549ee 100%); color: white; padding: 25px;">
                            <div class="card-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <h4>{{ $karyawan->nama }}</h4>
                            <div class="card-description">
                                @if($karyawan->divisi === 'barber')
                                    <span class="badge badge-info"><i class="fas fa-cut"></i> Barber</span>
                                @elseif($karyawan->divisi === 'angkringan')
                                    <span class="badge badge-warning text-white"><i class="fas fa-utensils"></i> Angkringan</span>
                                @else
                                    <span class="badge badge-light">{{ ucfirst($karyawan->divisi) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">Status</span>
                                    @if($karyawan->status === 'aktif')
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-danger">Nonaktif</span>
                                    @endif
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">Terdaftar Pada</span>
                                    <span class="font-weight-bold">{{ $karyawan->created_at ? $karyawan->created_at->format('d F Y') : '-' }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- INFORMASI LENGKAP --}}
                <div class="col-12 col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Informasi Karyawan</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-md">
                                    <tbody>
                                        <tr>
                                            <th width="200"><i class="fas fa-user text-primary mr-2"></i> Nama Lengkap</th>
                                            <td class="font-weight-bold">{{ $karyawan->nama }}</td>
                                        </tr>
                                        <tr>
                                            <th><i class="fas fa-building text-primary mr-2"></i> Divisi / Penempatan</th>
                                            <td>
                                                @if($karyawan->divisi === 'barber')
                                                    <span class="badge badge-info"><i class="fas fa-cut"></i> Barber</span>
                                                @elseif($karyawan->divisi === 'angkringan')
                                                    <span class="badge badge-warning text-white"><i class="fas fa-utensils"></i> Angkringan</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ ucfirst($karyawan->divisi) }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><i class="fas fa-toggle-on text-primary mr-2"></i> Status</th>
                                            <td>
                                                @if($karyawan->status === 'aktif')
                                                    <span class="badge badge-success">Aktif</span>
                                                @else
                                                    <span class="badge badge-danger">Nonaktif</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><i class="fas fa-map-marker-alt text-danger mr-2"></i> Alamat</th>
                                            <td>{{ $karyawan->alamat ?? '-' }}</td>
                                        </tr>
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
