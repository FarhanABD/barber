@extends('admin.layouts.master')

@section('main_content')
@include('admin.layouts.nav')
@include('admin.layouts.sidebar')

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Edit Pengeluaran Gaji: {{ $gaji->kode_slip }}</h1>
            <div class="ml-auto">
                <a href="{{ route('admin.pengeluaran-gaji.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div class="section-body">
            <form action="{{ route('admin.pengeluaran-gaji.update', $gaji->id) }}" method="POST" id="formGaji">
                @csrf
                @method('PUT')
                <input type="hidden" name="skema_gaji" id="skema_gaji" value="{{ $gaji->skema_gaji ?? 'jam_kerja' }}">

                <div class="row">
                    {{-- SISI KIRI: DATA KARYAWAN & PERHITUNGAN --}}
                    <div class="col-12 col-lg-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4><i class="fas fa-user-check text-primary mr-2"></i> Data Karyawan & Periode</h4>
                                <span id="skema_badge" class="badge badge-info px-3 py-2" style="font-size: 12px; display: none;"></span>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    {{-- Karyawan --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label font-weight-bold">Pilih Karyawan <span class="text-danger">*</span></label>
                                        <select name="karyawan_id" id="karyawan_id" class="form-control @error('karyawan_id') is-invalid @enderror" required>
                                            <option value="">-- Pilih Karyawan --</option>
                                            @foreach($karyawans as $karyawan)
                                                <option value="{{ $karyawan->id }}" 
                                                        data-divisi="{{ $karyawan->divisi }}" 
                                                        {{ old('karyawan_id', $gaji->karyawan_id) == $karyawan->id ? 'selected' : '' }}>
                                                    {{ $karyawan->nama }} (Divisi: {{ ucfirst($karyawan->divisi) }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('karyawan_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Tanggal Bayar --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label font-weight-bold">Tanggal Pembayaran <span class="text-danger">*</span></label>
                                        <input type="date" name="tanggal_bayar" class="form-control @error('tanggal_bayar') is-invalid @enderror" value="{{ old('tanggal_bayar', $gaji->tanggal_bayar ? $gaji->tanggal_bayar->format('Y-m-d') : '') }}" required>
                                        @error('tanggal_bayar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Periode Mulai --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label font-weight-bold">Periode Awal (Opsional)</label>
                                        <input type="date" name="periode_mulai" class="form-control @error('periode_mulai') is-invalid @enderror" value="{{ old('periode_mulai', $gaji->periode_mulai ? $gaji->periode_mulai->format('Y-m-d') : '') }}">
                                        @error('periode_mulai')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Periode Selesai --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label font-weight-bold">Periode Akhir (Opsional)</label>
                                        <input type="date" name="periode_selesai" class="form-control @error('periode_selesai') is-invalid @enderror" value="{{ old('periode_selesai', $gaji->periode_selesai ? $gaji->periode_selesai->format('Y-m-d') : '') }}">
                                        @error('periode_selesai')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ======================================================== --}}
                        {{-- SECTION A: SKEMA BARBER (KOMISI 45% PER KEPALA)           --}}
                        {{-- ======================================================== --}}
                        <div class="card" id="section_barber" style="display: none;">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h4 class="text-dark"><i class="fas fa-cut text-info mr-2"></i> Rincian Bagi Hasil Barber (45% per Kepala)</h4>
                                <div class="d-flex align-items-center">
                                    <span class="mr-2 font-weight-bold small text-muted">Komisi:</span>
                                    <div class="input-group input-group-sm" style="width: 110px;">
                                        <input type="number" step="1" min="0" max="100" name="persentase_komisi" id="persentase_komisi" class="form-control text-right font-weight-bold" value="{{ old('persentase_komisi', $gaji->persentase_komisi ?? 45) }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <div class="alert alert-info py-2 px-3 mb-3 small">
                                    <i class="fas fa-info-circle mr-1"></i> Masukkan <strong>Jumlah Kepala / Customer</strong> yang dikerjakan oleh barber. Komisi <strong>45%</strong> dan total omset akan otomatis terhitung.
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm" id="table_layanan_barber">
                                        <thead class="thead-light">
                                            <tr>
                                                <th width="35%">Layanan / Paket</th>
                                                <th width="20%">Tarif Satuan (Rp)</th>
                                                <th width="15%" class="text-center">Komisi / Item</th>
                                                <th width="15%" class="text-center">Jml Kepala</th>
                                                <th width="15%" class="text-right">Subtotal Komisi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="barber_service_rows">
                                            @php
                                                $savedServices = $gaji->rincian_layanan ?? [];
                                                
                                                if (isset($barberServices) && $barberServices->count() > 0) {
                                                    $baseServices = $barberServices->map(function($s) {
                                                        return [
                                                            'nama' => $s->name,
                                                            'tipe' => $s->description ?? 'Layanan Barber',
                                                            'tarif' => (float)$s->price,
                                                        ];
                                                    })->toArray();
                                                } else {
                                                    $baseServices = [
                                                        ['nama' => 'Haircut DEWASA (Senin - Jumat)', 'tipe' => 'Senin - Jumat (Weekday)', 'tarif' => 30000],
                                                        ['nama' => 'Haircut DEWASA (Sabtu - Minggu)', 'tipe' => 'Sabtu - Minggu (Weekend)', 'tarif' => 35000],
                                                        ['nama' => 'Haircut ANAK (Senin - Jumat)', 'tipe' => 'Senin - Jumat (Weekday)', 'tarif' => 25000],
                                                        ['nama' => 'Haircut ANAK (Sabtu - Minggu)', 'tipe' => 'Sabtu - Minggu (Weekend)', 'tarif' => 30000],
                                                        ['nama' => 'Hair Coloring - Highlight', 'tipe' => 'All Day', 'tarif' => 130000],
                                                        ['nama' => 'Hair Coloring - Full Colour', 'tipe' => 'All Day', 'tarif' => 200000],
                                                        ['nama' => 'Hair Coloring - Bleaching', 'tipe' => 'All Day', 'tarif' => 110000],
                                                        ['nama' => 'Perawatan - Creambath', 'tipe' => 'All Day', 'tarif' => 60000],
                                                        ['nama' => 'Perawatan - Masker Rambut', 'tipe' => 'All Day', 'tarif' => 100000],
                                                    ];
                                                }

                                                // Gabungkan base dengan saved qty
                                                $renderedServices = [];
                                                foreach($baseServices as $d) {
                                                    $found = null;
                                                    foreach($savedServices as $s) {
                                                        if(isset($s['nama_layanan']) && $s['nama_layanan'] === $d['nama']) {
                                                            $found = $s;
                                                            break;
                                                        }
                                                    }
                                                    $renderedServices[] = [
                                                        'nama' => $d['nama'],
                                                        'tipe' => $d['tipe'],
                                                        'tarif' => $found ? $found['tarif'] : $d['tarif'],
                                                        'qty' => $found ? $found['jumlah_kepala'] : 0,
                                                        'custom' => false
                                                    ];
                                                }

                                                // Tambahkan custom items yang ada di database tapi tidak ada di base
                                                foreach($savedServices as $s) {
                                                    $isBase = false;
                                                    foreach($baseServices as $d) {
                                                        if(isset($s['nama_layanan']) && $s['nama_layanan'] === $d['nama']) {
                                                            $isBase = true;
                                                            break;
                                                        }
                                                    }
                                                    if(!$isBase) {
                                                        $renderedServices[] = [
                                                            'nama' => $s['nama_layanan'] ?? 'Layanan Kustom',
                                                            'tipe' => $s['tipe_hari'] ?? 'Custom',
                                                            'tarif' => $s['tarif'] ?? 0,
                                                            'qty' => $s['jumlah_kepala'] ?? 0,
                                                            'custom' => true
                                                        ];
                                                    }
                                                }
                                            @endphp

                                            @foreach($renderedServices as $idx => $srv)
                                            <tr class="barber-row">
                                                <td>
                                                    @if($srv['custom'])
                                                        <input type="text" name="rincian_layanan[{{ $idx }}][nama_layanan]" class="form-control form-control-sm font-weight-bold" value="{{ $srv['nama'] }}" required>
                                                        <input type="hidden" name="rincian_layanan[{{ $idx }}][tipe_hari]" value="{{ $srv['tipe'] }}">
                                                    @else
                                                        <strong>{{ $srv['nama'] }}</strong>
                                                        <input type="hidden" name="rincian_layanan[{{ $idx }}][nama_layanan]" value="{{ $srv['nama'] }}">
                                                        <input type="hidden" name="rincian_layanan[{{ $idx }}][tipe_hari]" value="{{ $srv['tipe'] }}">
                                                        <div class="text-muted small">{{ $srv['tipe'] }}</div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <input type="number" name="rincian_layanan[{{ $idx }}][tarif]" class="form-control form-control-sm text-right font-weight-bold srv-tarif" value="{{ $srv['tarif'] }}" min="0" step="1000">
                                                </td>
                                                <td class="text-center align-middle font-weight-bold text-muted srv-komisi-rate">
                                                    Rp {{ number_format($srv['tarif'] * (($gaji->persentase_komisi ?? 45) / 100), 0, ',', '.') }}
                                                </td>
                                                <td>
                                                    <input type="number" name="rincian_layanan[{{ $idx }}][jumlah_kepala]" class="form-control form-control-sm text-center font-weight-bold srv-qty" value="{{ $srv['qty'] }}" min="0" step="1">
                                                </td>
                                                <td class="text-right align-middle font-weight-bold text-success srv-subtotal-komisi">
                                                    Rp {{ number_format($srv['tarif'] * $srv['qty'] * (($gaji->persentase_komisi ?? 45) / 100), 0, ',', '.') }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr style="background-color: #f8f9fa;">
                                                <td colspan="3" class="text-right font-weight-bold">Total Customer & Omset:</td>
                                                <td class="text-center font-weight-bold text-primary" id="total_barber_kepala_text">{{ $gaji->total_kepala ?? 0 }} Kepala</td>
                                                <td class="text-right font-weight-bold text-success" id="total_barber_komisi_text">Rp {{ number_format($gaji->total_komisi ?? 0, 0, ',', '.') }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="btn_add_custom_service">
                                    <i class="fas fa-plus mr-1"></i> Tambah Layanan Kustom Lainnya
                                </button>
                            </div>
                        </div>

                        {{-- ======================================================== --}}
                        {{-- SECTION B: SKEMA ANGKRINGAN / UMUM (JAM KERJA)             --}}
                        {{-- ======================================================== --}}
                        <div class="card" id="section_angkringan">
                            <div class="card-header bg-light">
                                <h4 class="text-dark"><i class="fas fa-calculator text-success mr-2"></i> Rincian Jam Kerja & Fee Training</h4>
                            </div>
                            <div class="card-body">
                                {{-- 1. JAM KERJA REGULER --}}
                                <div class="p-3 mb-3 rounded" style="background-color: #f8f9fa; border: 1px solid #e9ecef;">
                                    <h6 class="font-weight-bold text-primary mb-3">
                                        <i class="fas fa-clock mr-1"></i> 1. Upah Jam Kerja Reguler
                                    </h6>
                                    <div class="row align-items-center">
                                        <div class="col-md-4 mb-2">
                                            <label class="form-label">Total Jam Kerja <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" step="0.5" min="0" name="total_jam_kerja" id="total_jam_kerja" class="form-control text-right font-weight-bold @error('total_jam_kerja') is-invalid @enderror" value="{{ old('total_jam_kerja', $gaji->total_jam_kerja) }}" placeholder="0">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Jam</span>
                                                </div>
                                            </div>
                                            @error('total_jam_kerja')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label class="form-label">Tarif Upah / Jam (Rp)</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number" name="tarif_per_jam" id="tarif_per_jam" class="form-control text-right font-weight-bold" value="{{ old('tarif_per_jam', $gaji->tarif_per_jam ?? 5000) }}" min="0">
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label class="form-label text-muted">Subtotal Upah Jam</label>
                                            <h5 class="text-primary font-weight-bold mb-0" id="subtotal_jam_kerja_text">Rp {{ number_format($gaji->total_upah_jam ?? 0, 0, ',', '.') }}</h5>
                                        </div>
                                    </div>
                                </div>

                                {{-- 2. FEE TRAINING ANGGOTA BARU --}}
                                <div class="p-3 mb-3 rounded" style="background-color: #fcf8e3; border: 1px solid #faebcc;">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="font-weight-bold text-warning mb-0" style="color: #b7791f !important;">
                                            <i class="fas fa-chalkboard-teacher mr-1"></i> 2. Fee Training Anggota Baru
                                        </h6>
                                        <span class="badge badge-warning text-white font-weight-bold">Rp 20.000 / 8 Jam</span>
                                    </div>
                                    <small class="text-muted d-block mb-3">Diberikan jika karyawan memberikan training kepada anggota baru (Rp 2.500/jam training).</small>

                                    <div class="row align-items-center">
                                        <div class="col-md-4 mb-2">
                                            <label class="form-label">Total Jam Training</label>
                                            <div class="input-group">
                                                <input type="number" step="0.5" min="0" name="jam_training" id="jam_training" class="form-control text-right font-weight-bold" value="{{ old('jam_training', $gaji->jam_training ?? 0) }}" placeholder="0">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Jam</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label class="form-label">Tarif Training (per 8 Jam)</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number" name="tarif_training_per_8jam" id="tarif_training_per_8jam" class="form-control text-right font-weight-bold" value="{{ old('tarif_training_per_8jam', $gaji->tarif_training_per_8jam ?? 20000) }}" min="0">
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label class="form-label text-muted">Subtotal Fee Training</label>
                                            <h5 class="text-success font-weight-bold mb-0" id="subtotal_training_text">Rp {{ number_format($gaji->total_fee_training ?? 0, 0, ',', '.') }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 3. BONUS & POTONGAN (UNTUK SEMUA DIVISI) --}}
                        <div class="card">
                            <div class="card-header bg-light">
                                <h4 class="text-dark"><i class="fas fa-coins text-warning mr-2"></i> Tambahan, Potongan & Catatan</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label font-weight-bold text-success"><i class="fas fa-plus-circle mr-1"></i> Bonus / Insentif Tambahan (Rp)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="number" name="bonus" id="bonus" class="form-control text-right font-weight-bold" value="{{ old('bonus', $gaji->bonus) }}" min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label font-weight-bold text-danger"><i class="fas fa-minus-circle mr-1"></i> Potongan / Kasbon (Rp)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="number" name="potongan" id="potongan" class="form-control text-right font-weight-bold" value="{{ old('potongan', $gaji->potongan) }}" min="0">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-0">
                                    <label class="form-label font-weight-bold">Catatan / Keterangan (Opsional)</label>
                                    <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan khusus untuk slip gaji ini...">{{ old('catatan', $gaji->catatan) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SISI KANAN: RINGKASAN & SUBMIT --}}
                    <div class="col-12 col-lg-4">
                        <div class="card card-hero">
                            <div class="card-header" style="background: linear-gradient(135deg, #47c363 0%, #178d33 100%); color: white; padding: 25px;">
                                <div class="card-icon">
                                    <i class="fas fa-wallet"></i>
                                </div>
                                <h4 class="text-white">Total Gaji Bersih</h4>
                                <div class="card-description text-white-50">
                                    Take Home Pay
                                </div>
                                <h2 class="text-white font-weight-bold mt-2" id="grand_total_display">Rp {{ number_format($gaji->total_gaji, 0, ',', '.') }}</h2>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush mb-4">
                                    {{-- Row untuk Barber --}}
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0" id="summary_barber_row" style="display: none;">
                                        <span>Bagi Hasil Barber (45%)</span>
                                        <strong class="text-primary" id="ringkasan_barber_komisi">Rp {{ number_format($gaji->total_komisi ?? 0, 0, ',', '.') }}</strong>
                                    </li>

                                    {{-- Row untuk Angkringan --}}
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0" id="summary_angkringan_jam_row">
                                        <span>Upah Jam Kerja</span>
                                        <strong id="ringkasan_upah_jam">Rp {{ number_format($gaji->total_upah_jam ?? 0, 0, ',', '.') }}</strong>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0" id="summary_angkringan_training_row">
                                        <span>Fee Training Anggota</span>
                                        <strong class="text-success" id="ringkasan_fee_training">+ Rp {{ number_format($gaji->total_fee_training ?? 0, 0, ',', '.') }}</strong>
                                    </li>

                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>Bonus Tambahan</span>
                                        <strong class="text-success" id="ringkasan_bonus">+ Rp {{ number_format($gaji->bonus ?? 0, 0, ',', '.') }}</strong>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>Potongan</span>
                                        <strong class="text-danger" id="ringkasan_potongan">- Rp {{ number_format($gaji->potongan ?? 0, 0, ',', '.') }}</strong>
                                    </li>
                                </ul>

                                <div class="form-group mb-3">
                                    <label class="form-label font-weight-bold">Metode Pembayaran</label>
                                    <select name="metode_pembayaran" class="form-control">
                                        <option value="cash" {{ old('metode_pembayaran', $gaji->metode_pembayaran) == 'cash' ? 'selected' : '' }}>Cash / Tunai</option>
                                        <option value="transfer" {{ old('metode_pembayaran', $gaji->metode_pembayaran) == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                    </select>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label font-weight-bold">Status Pembayaran</label>
                                    <select name="status" class="form-control">
                                        <option value="dibayar" {{ old('status', $gaji->status) == 'dibayar' ? 'selected' : '' }}>Sudah Dibayar (Lunas)</option>
                                        <option value="pending" {{ old('status', $gaji->status) == 'pending' ? 'selected' : '' }}>Pending / Menunggu</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg btn-block">
                                    <i class="fas fa-save mr-1"></i> Perbarui Slip Gaji
                                </button>
                                <a href="{{ route('admin.pengeluaran-gaji.index') }}" class="btn btn-secondary btn-block mt-2">
                                    Batal
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const karyawanSelect = document.getElementById('karyawan_id');
    const skemaGajiInput = document.getElementById('skema_gaji');
    const skemaBadge = document.getElementById('skema_badge');

    const sectionBarber = document.getElementById('section_barber');
    const sectionAngkringan = document.getElementById('section_angkringan');
    const summaryBarberRow = document.getElementById('summary_barber_row');
    const summaryAngkringanJamRow = document.getElementById('summary_angkringan_jam_row');
    const summaryAngkringanTrainingRow = document.getElementById('summary_angkringan_training_row');

    // Angkringan inputs
    const totalJamKerjaInput = document.getElementById('total_jam_kerja');
    const tarifPerJamInput = document.getElementById('tarif_per_jam');
    const jamTrainingInput = document.getElementById('jam_training');
    const tarifTrainingPer8JamInput = document.getElementById('tarif_training_per_8jam');
    const subtotalJamKerjaText = document.getElementById('subtotal_jam_kerja_text');
    const subtotalTrainingText = document.getElementById('subtotal_training_text');

    // Barber inputs & table
    const persentaseKomisiInput = document.getElementById('persentase_komisi');
    const barberServiceRows = document.getElementById('barber_service_rows');
    const totalBarberKepalaText = document.getElementById('total_barber_kepala_text');
    const totalBarberKomisiText = document.getElementById('total_barber_komisi_text');
    const ringkasanBarberKomisi = document.getElementById('ringkasan_barber_komisi');
    const btnAddCustomService = document.getElementById('btn_add_custom_service');

    // Common inputs & summary
    const bonusInput = document.getElementById('bonus');
    const potonganInput = document.getElementById('potongan');
    const ringkasanUpahJam = document.getElementById('ringkasan_upah_jam');
    const ringkasanFeeTraining = document.getElementById('ringkasan_fee_training');
    const ringkasanBonus = document.getElementById('ringkasan_bonus');
    const ringkasanPotongan = document.getElementById('ringkasan_potongan');
    const grandTotalDisplay = document.getElementById('grand_total_display');

    let customIndex = 100;

    function formatRupiah(number) {
        return 'Rp ' + Math.round(number).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function toggleFormByDivision() {
        const selectedOption = karyawanSelect.options[karyawanSelect.selectedIndex];
        const divisi = selectedOption ? selectedOption.getAttribute('data-divisi') : '';

        if (divisi === 'barber' || skemaGajiInput.value === 'komisi_barber') {
            skemaGajiInput.value = 'komisi_barber';
            sectionBarber.style.display = 'block';
            sectionAngkringan.style.display = 'none';
            summaryBarberRow.style.display = 'flex';
            summaryAngkringanJamRow.style.display = 'none';
            summaryAngkringanTrainingRow.style.display = 'none';

            skemaBadge.style.display = 'inline-block';
            skemaBadge.className = 'badge badge-info px-3 py-2';
            skemaBadge.innerHTML = '<i class="fas fa-cut mr-1"></i> Skema: Bagi Hasil 45% (Barber)';
            totalJamKerjaInput.removeAttribute('required');
        } else {
            skemaGajiInput.value = 'jam_kerja';
            sectionBarber.style.display = 'none';
            sectionAngkringan.style.display = 'block';
            summaryBarberRow.style.display = 'none';
            summaryAngkringanJamRow.style.display = 'flex';
            summaryAngkringanTrainingRow.style.display = 'flex';

            if (divisi) {
                skemaBadge.style.display = 'inline-block';
                skemaBadge.className = 'badge badge-warning text-white px-3 py-2';
                skemaBadge.innerHTML = '<i class="fas fa-utensils mr-1"></i> Skema: Upah Jam Kerja (Angkringan)';
                totalJamKerjaInput.setAttribute('required', 'required');
            } else {
                skemaBadge.style.display = 'none';
            }
        }
        hitungGaji();
    }

    function hitungBarber() {
        const globalPersen = parseFloat(persentaseKomisiInput.value) || 45;
        let totalKepala = 0;
        let totalOmset = 0;
        let totalKomisi = 0;

        const rows = barberServiceRows.querySelectorAll('.barber-row');
        rows.forEach(function(row) {
            const tarifInput = row.querySelector('.srv-tarif');
            const qtyInput = row.querySelector('.srv-qty');
            const komisiRateCell = row.querySelector('.srv-komisi-rate');
            const subtotalCell = row.querySelector('.srv-subtotal-komisi');

            const tarif = parseFloat(tarifInput.value) || 0;
            const qty = parseInt(qtyInput.value) || 0;
            const komisiPerItem = tarif * (globalPersen / 100);
            const subtotalKomisi = komisiPerItem * qty;

            if (komisiRateCell) {
                komisiRateCell.innerText = formatRupiah(komisiPerItem);
            }
            if (subtotalCell) {
                subtotalCell.innerText = formatRupiah(subtotalKomisi);
            }

            totalKepala += qty;
            totalOmset += (tarif * qty);
            totalKomisi += subtotalKomisi;
        });

        totalBarberKepalaText.innerText = totalKepala + ' Kepala';
        totalBarberKomisiText.innerText = formatRupiah(totalKomisi);
        ringkasanBarberKomisi.innerText = formatRupiah(totalKomisi);

        return totalKomisi;
    }

    function hitungAngkringan() {
        const jamKerja = parseFloat(totalJamKerjaInput.value) || 0;
        const tarifJam = parseFloat(tarifPerJamInput.value) || 0;
        const upahJamKerja = jamKerja * tarifJam;

        const jamTraining = parseFloat(jamTrainingInput.value) || 0;
        const tarifTraining = parseFloat(tarifTrainingPer8JamInput.value) || 0;
        const feeTraining = (jamTraining / 8) * tarifTraining;

        subtotalJamKerjaText.innerText = formatRupiah(upahJamKerja);
        subtotalTrainingText.innerText = formatRupiah(feeTraining);
        ringkasanUpahJam.innerText = formatRupiah(upahJamKerja);
        ringkasanFeeTraining.innerText = '+ ' + formatRupiah(feeTraining);

        return upahJamKerja + feeTraining;
    }

    function hitungGaji() {
        const bonus = parseFloat(bonusInput.value) || 0;
        const potongan = parseFloat(potonganInput.value) || 0;
        let baseEarn = 0;

        if (skemaGajiInput.value === 'komisi_barber') {
            baseEarn = hitungBarber();
        } else {
            baseEarn = hitungAngkringan();
        }

        const grandTotal = Math.max(0, (baseEarn + bonus - potongan));

        ringkasanBonus.innerText = '+ ' + formatRupiah(bonus);
        ringkasanPotongan.innerText = '- ' + formatRupiah(potongan);
        grandTotalDisplay.innerText = formatRupiah(grandTotal);
    }

    // Tambah Layanan Kustom
    if (btnAddCustomService) {
        btnAddCustomService.addEventListener('click', function() {
            customIndex++;
            const tr = document.createElement('tr');
            tr.className = 'barber-row';
            tr.innerHTML = `
                <td>
                    <input type="text" name="rincian_layanan[${customIndex}][nama_layanan]" class="form-control form-control-sm font-weight-bold" placeholder="Nama Layanan Kustom..." required>
                    <input type="hidden" name="rincian_layanan[${customIndex}][tipe_hari]" value="Custom">
                </td>
                <td>
                    <input type="number" name="rincian_layanan[${customIndex}][tarif]" class="form-control form-control-sm text-right font-weight-bold srv-tarif" value="50000" min="0" step="1000">
                </td>
                <td class="text-center align-middle font-weight-bold text-muted srv-komisi-rate">
                    Rp 22.500
                </td>
                <td>
                    <input type="number" name="rincian_layanan[${customIndex}][jumlah_kepala]" class="form-control form-control-sm text-center font-weight-bold srv-qty" value="1" min="0" step="1">
                </td>
                <td class="text-right align-middle font-weight-bold text-success srv-subtotal-komisi">
                    Rp 22.500
                </td>
            `;
            barberServiceRows.appendChild(tr);
            attachRowListeners(tr);
            hitungGaji();
        });
    }

    function attachRowListeners(row) {
        const inputs = row.querySelectorAll('input');
        inputs.forEach(function(inp) {
            inp.addEventListener('input', hitungGaji);
            inp.addEventListener('change', hitungGaji);
        });
    }

    // Attach listeners ke existing rows
    const existingRows = barberServiceRows.querySelectorAll('.barber-row');
    existingRows.forEach(function(row) {
        attachRowListeners(row);
    });

    // Event listeners
    karyawanSelect.addEventListener('change', toggleFormByDivision);
    persentaseKomisiInput.addEventListener('input', hitungGaji);
    [totalJamKerjaInput, tarifPerJamInput, jamTrainingInput, tarifTrainingPer8JamInput, bonusInput, potonganInput].forEach(function(input) {
        if (input) {
            input.addEventListener('input', hitungGaji);
            input.addEventListener('change', hitungGaji);
        }
    });

    // Inisialisasi awal
    toggleFormByDivision();
});
</script>
@endsection
