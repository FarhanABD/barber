@extends('admin.layouts.master')

@section('main_content')
@include('admin.layouts.nav')
@include('admin.layouts.sidebar')

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Detail Slip Gaji</h1>
            <div class="ml-auto">
                <a href="{{ route('admin.pengeluaran-gaji.pdf', $gaji->id) }}" target="_blank" class="btn btn-danger mr-1">
                    <i class="fas fa-file-pdf"></i> Cetak / Download PDF
                </a>
                <a href="{{ route('admin.pengeluaran-gaji.print', $gaji->id) }}" target="_blank" class="btn btn-warning text-white mr-1">
                    <i class="fas fa-print"></i> Print Slip
                </a>
                <a href="{{ route('admin.pengeluaran-gaji.edit', $gaji->id) }}" class="btn btn-primary mr-1">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('admin.pengeluaran-gaji.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div class="section-body">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8">
                    <div class="card card-primary">
                        <div class="card-header d-flex justify-content-between align-items-center border-bottom pb-3">
                            <div>
                                <h4 class="text-primary mb-1">SLIP GAJI KARYAWAN</h4>
                                <span class="badge badge-light font-weight-bold">{{ $gaji->kode_slip }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-muted small d-block">Tanggal Pembayaran</span>
                                <strong>{{ $gaji->tanggal_bayar ? $gaji->tanggal_bayar->format('d F Y') : '-' }}</strong>
                            </div>
                        </div>

                        <div class="card-body">
                            {{-- INFORMASI KARYAWAN --}}
                            <div class="row mb-4 p-3 rounded" style="background-color: #f8f9fa;">
                                <div class="col-md-6 mb-2">
                                    <span class="text-muted d-block small">Nama Karyawan:</span>
                                    <h5 class="font-weight-bold text-dark mb-1">{{ $gaji->karyawan->nama ?? 'Karyawan Dihapus' }}</h5>
                                    @if($gaji->karyawan)
                                        @if($gaji->karyawan->divisi === 'barber')
                                            <span class="badge badge-info"><i class="fas fa-cut"></i> Barber</span>
                                        @elseif($gaji->karyawan->divisi === 'angkringan')
                                            <span class="badge badge-warning text-white"><i class="fas fa-utensils"></i> Angkringan</span>
                                        @else
                                            <span class="badge badge-secondary">{{ ucfirst($gaji->karyawan->divisi) }}</span>
                                        @endif
                                    @endif
                                </div>
                                <div class="col-md-6 mb-2 text-md-right">
                                    <span class="text-muted d-block small">Periode Kerja:</span>
                                    <strong>
                                        @if($gaji->periode_mulai && $gaji->periode_selesai)
                                            {{ $gaji->periode_mulai->format('d M Y') }} s/d {{ $gaji->periode_selesai->format('d M Y') }}
                                        @else
                                            -
                                        @endif
                                    </strong>
                                    <div class="mt-1">
                                        <span class="text-muted small">Status: </span>
                                        @if($gaji->status === 'dibayar')
                                            <span class="badge badge-success">Sudah Dibayar</span>
                                        @else
                                            <span class="badge badge-warning text-white">Pending</span>
                                        @endif
                                        <span class="badge badge-outline-secondary ml-1">{{ strtoupper($gaji->metode_pembayaran) }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- TABEL RINCIAN PENDAPATAN & POTONGAN --}}
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                        @if($gaji->skema_gaji === 'komisi_barber')
                                        <tr>
                                            <th>Layanan / Paket Barber</th>
                                            <th class="text-center" width="100">Jml Kepala</th>
                                            <th class="text-right" width="140">Tarif Satuan</th>
                                            <th class="text-center" width="120">Komisi (%)</th>
                                            <th class="text-right" width="160">Subtotal Komisi</th>
                                        </tr>
                                        @else
                                        <tr>
                                            <th>Deskripsi Komponen Gaji</th>
                                            <th class="text-center" width="120">Jumlah</th>
                                            <th class="text-right" width="150">Tarif Satuan</th>
                                            <th class="text-right" width="180">Subtotal</th>
                                        </tr>
                                        @endif
                                    </thead>
                                    <tbody>
                                        @if($gaji->skema_gaji === 'komisi_barber')
                                            {{-- RINCIAN KOMISI BARBER --}}
                                            @if(!empty($gaji->rincian_layanan) && is_array($gaji->rincian_layanan))
                                                @foreach($gaji->rincian_layanan as $item)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $item['nama_layanan'] }}</strong>
                                                        <div class="text-muted small">{{ $item['tipe_hari'] ?? '-' }}</div>
                                                    </td>
                                                    <td class="text-center font-weight-bold">{{ $item['jumlah_kepala'] }} Kepala</td>
                                                    <td class="text-right">Rp {{ number_format($item['tarif'] ?? 0, 0, ',', '.') }}</td>
                                                    <td class="text-center font-weight-bold text-info">{{ $item['persentase_komisi'] ?? $gaji->persentase_komisi ?? 45 }}%</td>
                                                    <td class="text-right font-weight-bold text-success">Rp {{ number_format($item['subtotal_komisi'] ?? 0, 0, ',', '.') }}</td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td><strong>Total Bagi Hasil Barber</strong></td>
                                                    <td class="text-center font-weight-bold">{{ $gaji->total_kepala }} Kepala</td>
                                                    <td class="text-right">Omset: Rp {{ number_format($gaji->total_omset, 0, ',', '.') }}</td>
                                                    <td class="text-center font-weight-bold text-info">{{ $gaji->persentase_komisi }}%</td>
                                                    <td class="text-right font-weight-bold text-success">Rp {{ number_format($gaji->total_komisi, 0, ',', '.') }}</td>
                                                </tr>
                                            @endif

                                            <tr style="background-color: #f8f9fa;">
                                                <th colspan="4" class="text-right">SUBTOTAL KOMISI BAGI HASIL ({{ $gaji->total_kepala }} KEPALA):</th>
                                                <th class="text-right font-weight-bold text-primary">Rp {{ number_format($gaji->total_komisi, 0, ',', '.') }}</th>
                                            </tr>

                                        @else
                                            {{-- 1. Upah Jam Kerja --}}
                                            <tr>
                                                <td>
                                                    <strong>Upah Jam Kerja Reguler</strong>
                                                    <div class="text-muted small">Upah berbasis jam operasional kerja</div>
                                                </td>
                                                <td class="text-center font-weight-bold">{{ $gaji->total_jam_kerja }} Jam</td>
                                                <td class="text-right">Rp {{ number_format($gaji->tarif_per_jam, 0, ',', '.') }} / jam</td>
                                                <td class="text-right font-weight-bold">Rp {{ number_format($gaji->total_upah_jam, 0, ',', '.') }}</td>
                                            </tr>

                                            {{-- 2. Fee Training --}}
                                            @if($gaji->jam_training > 0)
                                            <tr>
                                                <td>
                                                    <strong class="text-warning" style="color: #d97706 !important;">Fee Training Anggota Baru</strong>
                                                    <div class="text-muted small">Insentif mendampingi/mentraining anggota baru</div>
                                                </td>
                                                <td class="text-center font-weight-bold">{{ $gaji->jam_training }} Jam</td>
                                                <td class="text-right">Rp {{ number_format($gaji->tarif_training_per_8jam, 0, ',', '.') }} / 8 jam</td>
                                                <td class="text-right font-weight-bold text-success">+ Rp {{ number_format($gaji->total_fee_training, 0, ',', '.') }}</td>
                                            </tr>
                                            @endif
                                        @endif

                                        {{-- 3. Bonus Tambahan (jika ada) --}}
                                        @if($gaji->bonus > 0)
                                        <tr>
                                            <td colspan="{{ $gaji->skema_gaji === 'komisi_barber' ? 4 : 3 }}">
                                                <strong>Bonus / Insentif Tambahan</strong>
                                            </td>
                                            <td class="text-right font-weight-bold text-success">+ Rp {{ number_format($gaji->bonus, 0, ',', '.') }}</td>
                                        </tr>
                                        @endif

                                        {{-- 4. Potongan (jika ada) --}}
                                        @if($gaji->potongan > 0)
                                        <tr>
                                            <td colspan="{{ $gaji->skema_gaji === 'komisi_barber' ? 4 : 3 }}">
                                                <strong class="text-danger">Potongan / Kasbon</strong>
                                            </td>
                                            <td class="text-right font-weight-bold text-danger">- Rp {{ number_format($gaji->potongan, 0, ',', '.') }}</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                    <tfoot>
                                        <tr style="background-color: #e8f5e9; font-size: 16px;">
                                            <th colspan="{{ $gaji->skema_gaji === 'komisi_barber' ? 4 : 3 }}" class="text-right font-weight-bold text-dark">TOTAL GAJI BERSIH (TAKE HOME PAY):</th>
                                            <th class="text-right font-weight-bold text-success" style="font-size: 18px;">
                                                Rp {{ number_format($gaji->total_gaji, 0, ',', '.') }}
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            @if($gaji->catatan)
                            <div class="alert alert-light border mb-4">
                                <strong>Catatan:</strong> {{ $gaji->catatan }}
                            </div>
                            @endif

                            {{-- TANDA TANGAN --}}
                            <div class="row pt-4 text-center">
                                <div class="col-6">
                                    <p class="text-muted mb-5">Penerima / Karyawan,</p>
                                    <h6 class="font-weight-bold mb-0">({{ $gaji->karyawan->nama ?? '..........................' }})</h6>
                                </div>
                                <div class="col-6">
                                    <p class="text-muted mb-5">Owner,</p>
                                    <h6 class="font-weight-bold mb-0">(....................................)</h6>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-light text-right border-top">
                            <a href="{{ route('admin.pengeluaran-gaji.pdf', $gaji->id) }}" target="_blank" class="btn btn-danger mr-2">
                                <i class="fas fa-file-pdf"></i> Cetak / Download PDF
                            </a>
                            <a href="{{ route('admin.pengeluaran-gaji.print', $gaji->id) }}" target="_blank" class="btn btn-success">
                                <i class="fas fa-print"></i> Print Slip
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
