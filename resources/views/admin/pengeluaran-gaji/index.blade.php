@extends('admin.layouts.master')

@section('main_content')
@include('admin.layouts.nav')
@include('admin.layouts.sidebar')

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Pengeluaran Gaji Karyawan</h1>
            <div class="ml-auto">
                <a href="{{ route('admin.pengeluaran-gaji.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Input Gaji Baru
                </a>
            </div>
        </div>

        {{-- WIDGET STATISTIK RINGKAS --}}
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Pengeluaran Gaji</h4>
                        </div>
                        <div class="card-body">
                            Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-info">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Jam Kerja</h4>
                        </div>
                        <div class="card-body">
                            {{ number_format($totalJamKerja, 0, ',', '.') }} Jam
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Fee Training</h4>
                        </div>
                        <div class="card-body">
                            Rp {{ number_format($totalFeeTraining, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Slip Dibuat</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalSlip }} Slip
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- FILTER CARD --}}
        <div class="card mb-3">
            <div class="card-body py-3">
                <form method="GET" action="{{ route('admin.pengeluaran-gaji.index') }}">
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
                        <div class="col-md-2 mb-2">
                            <label class="font-weight-bold mb-1">Status</label>
                            <select name="status" class="form-control" onchange="this.form.submit()">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>-- Semua --</option>
                                <option value="dibayar" {{ request('status') == 'dibayar' ? 'selected' : '' }}>Dibayar</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label class="font-weight-bold mb-1">Bulan</label>
                            <input type="month" name="bulan" class="form-control" value="{{ request('bulan') }}" onchange="this.form.submit()">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="font-weight-bold mb-1">Pencarian</label>
                            <input type="text" name="search" class="form-control" placeholder="Cari nama karyawan, no slip..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2 mb-2 d-flex align-items-end" style="height: 64px;">
                            <button type="submit" class="btn btn-primary mr-1"><i class="fas fa-search"></i> Cari</button>
                            <a href="{{ route('admin.pengeluaran-gaji.index') }}" class="btn btn-secondary"><i class="fas fa-sync"></i> Reset</a>
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
                                            <th width="40">No</th>
                                            <th>No. Slip</th>
                                            <th>Nama Karyawan</th>
                                            <th>Tanggal Bayar</th>
                                            <th>Rincian Kerja</th>
                                            <th>Komisi / Fee</th>
                                            <th>Total Gaji</th>
                                            <th class="text-center">Status</th>
                                            <th width="150" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($gajis as $key => $gaji)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <span class="badge badge-light font-weight-bold">{{ $gaji->kode_slip }}</span>
                                            </td>
                                            <td>
                                                <strong>{{ $gaji->karyawan->nama ?? 'Karyawan Dihapus' }}</strong>
                                                <br>
                                                @if($gaji->karyawan)
                                                    @if($gaji->karyawan->divisi === 'barber' || $gaji->skema_gaji === 'komisi_barber')
                                                        <span class="badge badge-info mt-1"><i class="fas fa-cut"></i> Barber (45%)</span>
                                                    @elseif($gaji->karyawan->divisi === 'angkringan')
                                                        <span class="badge badge-warning text-white mt-1"><i class="fas fa-utensils"></i> Angkringan</span>
                                                    @else
                                                        <span class="badge badge-secondary mt-1">{{ ucfirst($gaji->karyawan->divisi) }}</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                {{ $gaji->tanggal_bayar ? $gaji->tanggal_bayar->format('d/m/Y') : '-' }}
                                                @if($gaji->periode_mulai && $gaji->periode_selesai)
                                                    <div class="text-muted small">
                                                        {{ $gaji->periode_mulai->format('d M') }} - {{ $gaji->periode_selesai->format('d M Y') }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                @if($gaji->skema_gaji === 'komisi_barber')
                                                    <strong>{{ $gaji->total_kepala }} Kepala</strong>
                                                    <div class="text-muted small">Omset: Rp {{ number_format($gaji->total_omset, 0, ',', '.') }}</div>
                                                @else
                                                    <strong>{{ $gaji->total_jam_kerja }} Jam</strong>
                                                    <div class="text-muted small">@ Rp {{ number_format($gaji->tarif_per_jam, 0, ',', '.') }} = Rp {{ number_format($gaji->total_upah_jam, 0, ',', '.') }}</div>
                                                @endif
                                            </td>
                                            <td>
                                                @if($gaji->skema_gaji === 'komisi_barber')
                                                    <div class="text-info font-weight-bold small">Komisi {{ $gaji->persentase_komisi }}%</div>
                                                    <div class="text-success font-weight-bold">Rp {{ number_format($gaji->total_komisi, 0, ',', '.') }}</div>
                                                @else
                                                    @if($gaji->jam_training > 0)
                                                        <strong>{{ $gaji->jam_training }} Jam Training</strong>
                                                        <div class="text-success small font-weight-bold">+ Rp {{ number_format($gaji->total_fee_training, 0, ',', '.') }}</div>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                <strong class="text-primary" style="font-size: 15px;">Rp {{ number_format($gaji->total_gaji, 0, ',', '.') }}</strong>
                                                <div class="small text-muted">{{ ucfirst($gaji->metode_pembayaran) }}</div>
                                            </td>
                                            <td class="text-center">
                                                @if($gaji->status === 'dibayar')
                                                    <span class="badge badge-success">Dibayar</span>
                                                @else
                                                    <span class="badge badge-warning text-white">Pending</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.pengeluaran-gaji.show', $gaji->id) }}" class="btn btn-info btn-sm" title="Lihat Detail & Slip">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.pengeluaran-gaji.pdf', $gaji->id) }}" target="_blank" class="btn btn-danger btn-sm" title="Download / Cetak PDF">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                                <a href="{{ route('admin.pengeluaran-gaji.print', $gaji->id) }}" target="_blank" class="btn btn-secondary btn-sm" title="Cetak Print Slip">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                                <a href="{{ route('admin.pengeluaran-gaji.edit', $gaji->id) }}" class="btn btn-primary btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.pengeluaran-gaji.destroy', $gaji->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus slip gaji ini?');">
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
                                            <td colspan="9" class="text-center py-4 text-muted">
                                                <i class="fas fa-receipt fa-2x mb-2 d-block"></i>
                                                Belum ada data pengeluaran gaji karyawan.
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
