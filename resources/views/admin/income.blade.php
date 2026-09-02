@extends('admin.layouts.master')

@section('main_content')

@include('admin.layouts.nav')
@include('admin.layouts.sidebar')

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between bg-white shadow-sm rounded-lg mb-4 p-4">
            <div>
                <h1 class="mb-2 text-primary"><i class="fas fa-wallet mr-2"></i> Laporan Laba & Rugi</h1>
                <p class="text-muted mb-0">Ringkasan arus kas, pendapatan operasional, beban pengeluaran, dan laba bersih perusahaan</p>
            </div>
            <div class="ml-auto">
                <a href="{{ route('admin.income.pdf', request()->all()) }}" target="_blank" class="btn btn-danger btn-lg shadow-sm">
                    <i class="fas fa-file-pdf mr-1"></i> Cetak / Unduh PDF
                </a>
            </div>
        </div>

        <div class="section-body">

            {{-- FILTER PERIODE & TANGGAL --}}
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header bg-white border-bottom-0 pb-0">
                    <h4 class="text-dark"><i class="fas fa-filter text-primary mr-2"></i> Filter Laporan</h4>
                </div>
                <div class="card-body pt-3">
                    <form method="GET" action="{{ route('admin.income') }}" id="filterForm">
                        <div class="row align-items-end">
                            {{-- Quick Filters --}}
                            <div class="col-lg-5 col-md-12 mb-3 mb-lg-0">
                                <label class="form-label font-weight-bold text-muted small d-block">PILIHAN CEPAT PERIODE</label>
                                <div class="btn-group w-100 shadow-sm" role="group">
                                    <a href="{{ route('admin.income') }}" class="btn {{ empty($periodType) || $periodType === 'custom' && empty($startDate) ? 'btn-primary' : 'btn-outline-primary' }}">Semua</a>
                                    <a href="{{ route('admin.income', ['period' => 'today']) }}" class="btn {{ $periodType === 'today' ? 'btn-primary' : 'btn-outline-primary' }}">Hari Ini</a>
                                    <a href="{{ route('admin.income', ['period' => 'this_month']) }}" class="btn {{ $periodType === 'this_month' ? 'btn-primary' : 'btn-outline-primary' }}">Bulan Ini</a>
                                    <a href="{{ route('admin.income', ['period' => 'this_year']) }}" class="btn {{ $periodType === 'this_year' ? 'btn-primary' : 'btn-outline-primary' }}">Tahun Ini</a>
                                </div>
                            </div>

                            {{-- Custom Date Range --}}
                            <div class="col-lg-5 col-md-8 mb-3 mb-lg-0">
                                <label class="form-label font-weight-bold text-muted small">RENTANG TANGGAL KUSTOM</label>
                                <div class="input-group shadow-sm">
                                    <input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="form-control" placeholder="Mulai">
                                    <div class="input-group-append input-group-prepend">
                                        <span class="input-group-text bg-light border-left-0 border-right-0 text-muted">s/d</span>
                                    </div>
                                    <input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="form-control" placeholder="Selesai">
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="col-lg-2 col-md-4 text-lg-right">
                                <button type="submit" class="btn btn-primary btn-block shadow-sm mb-2">
                                    <i class="fas fa-search mr-1"></i> Terapkan
                                </button>
                                <a href="{{ route('admin.income') }}" class="btn btn-light btn-block shadow-sm">
                                    <i class="fas fa-sync-alt mr-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- 4 KARTU KPI RINGKASAN FINANSIAL --}}
            <div class="row">
                {{-- 1. TOTAL PEMASUKAN --}}
                <div class="col-lg-3 col-md-6 col-12 mb-4">
                    <div class="card card-statistic-1 shadow-sm border-0 h-100 overflow-hidden">
                        <div class="card-icon bg-success">
                            <i class="fas fa-arrow-down text-white"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header pb-1">
                                <h4>Total Pemasukan</h4>
                            </div>
                            <div class="card-body text-success pt-0" style="font-size: 1.25rem; padding: 12px 25px;">
                                Rp {{ number_format($totalIncome, 0, ',', '.') }}
                            </div>
                            <div class="px-3 pb-3 mt-1 text-muted small">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span><i class="fas fa-cut text-info mr-1"></i> Barber</span>
                                    <strong class="text-dark">Rp {{ number_format($totalIncomeBarber, 0, ',', '.') }}</strong>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-utensils text-warning mr-1"></i> Angkringan</span>
                                    <strong class="text-dark">Rp {{ number_format($totalIncomeAngkringan, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2. TOTAL PENGELUARAN --}}
                <div class="col-lg-3 col-md-6 col-12 mb-4">
                    <div class="card card-statistic-1 shadow-sm border-0 h-100 overflow-hidden">
                        <div class="card-icon bg-danger">
                            <i class="fas fa-arrow-up text-white"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header pb-1">
                                <h4>Total Beban</h4>
                            </div>
                            <div class="card-body text-danger pt-0" style="font-size: 1.25rem; padding: 12px 25px;">
                                Rp {{ number_format($totalExpense, 0, ',', '.') }}
                            </div>
                            <div class="px-3 pb-3 mt-1 text-muted small">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span><i class="fas fa-users text-primary mr-1"></i> Gaji</span>
                                    <strong class="text-dark">Rp {{ number_format($totalGaji, 0, ',', '.') }}</strong>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-box text-secondary mr-1"></i> Bahan Baku</span>
                                    <strong class="text-dark">Rp {{ number_format($totalBahanBaku, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 3. LABA / RUGI BERSIH (NET PROFIT) --}}
                <div class="col-lg-3 col-md-6 col-12 mb-4">
                    <div class="card card-statistic-1 shadow-sm border-0 h-100 overflow-hidden" style="border-bottom: 4px solid {{ $netProfit >= 0 ? '#47c363' : '#fc544b' }} !important;">
                        <div class="card-icon {{ $netProfit >= 0 ? 'bg-success' : 'bg-danger' }}">
                            <i class="fas {{ $netProfit >= 0 ? 'fa-chart-line' : 'fa-level-down-alt' }} text-white"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header pb-1">
                                <h4>Laba / Rugi Bersih</h4>
                            </div>
                            <div class="card-body {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }} pt-0" style="font-size: 1.25rem;">
                                {{ $netProfit < 0 ? '- ' : '' }}Rp {{ number_format(abs($netProfit), 0, ',', '.') }}
                            </div>
                            <div class="px-3 pb-3 mt-2">
                                @if($netProfit > 0)
                                    <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> SURPLUS / PROFIT</span>
                                @elseif($netProfit < 0)
                                    <span class="badge badge-danger px-2 py-1"><i class="fas fa-exclamation-triangle mr-1"></i> DEFISIT / RUGI</span>
                                @else
                                    <span class="badge badge-secondary px-2 py-1">BREAK EVEN</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 4. PROFIT MARGIN --}}
                <div class="col-lg-3 col-md-6 col-12 mb-4">
                    <div class="card card-statistic-1 shadow-sm border-0 h-100 overflow-hidden">
                        <div class="card-icon bg-info">
                            <i class="fas fa-percentage text-white"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header pb-1">
                                <h4>Margin Keuntungan</h4>
                            </div>
                            <div class="card-body text-info pt-0" style="font-size: 1.5rem;">
                                {{ $profitMargin }}%
                            </div>
                            <div class="px-3 pb-3 mt-2 text-muted small">
                                Rasio Laba Bersih terhadap Pemasukan Kotor (Revenue).
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TABEL PEMBUKUAN LAPORAN LABA RUGI RESMI --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <h4 class="text-dark mb-0"><i class="fas fa-file-invoice-dollar text-primary mr-2"></i> Rincian Pembukuan Finansial</h4>
                    <span class="badge badge-light border text-dark px-3 py-2">
                        <i class="fas fa-calendar-alt mr-1"></i> 
                        @if($startDate && $endDate)
                            {{ date('d M Y', strtotime($startDate)) }} - {{ date('d M Y', strtotime($endDate)) }}
                        @elseif($startDate)
                            Mulai {{ date('d M Y', strtotime($startDate)) }}
                        @elseif($endDate)
                            Hingga {{ date('d M Y', strtotime($endDate)) }}
                        @else
                            Seluruh Waktu
                        @endif
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th width="50%" class="align-middle text-uppercase">Deskripsi Pos Keuangan</th>
                                    <th width="20%" class="text-center align-middle text-uppercase">Kuantitas</th>
                                    <th width="30%" class="text-right align-middle text-uppercase">Nominal (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- SECTION 1: PENDAPATAN OPERASIONAL --}}
                                <tr class="bg-light">
                                    <td colspan="3" class="font-weight-bold text-primary">
                                        <i class="fas fa-plus-circle mr-1"></i> I. PENDAPATAN OPERASIONAL (REVENUE)
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pl-4 py-3">
                                        <h6 class="mb-1 text-dark">1. Pendapatan Barbershop</h6>
                                        <div class="text-muted small">Penjualan jasa potong rambut & treatment</div>
                                    </td>
                                    <td class="text-center align-middle"><span class="badge badge-light border">{{ $countTransactionBarber }} Transaksi</span></td>
                                    <td class="text-right align-middle font-weight-bold text-dark" style="font-size: 1.1rem;">
                                        Rp {{ number_format($totalIncomeBarber, 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pl-4 py-3">
                                        <h6 class="mb-1 text-dark">2. Pendapatan Angkringan</h6>
                                        <div class="text-muted small">Penjualan kasir makanan & minuman</div>
                                    </td>
                                    <td class="text-center align-middle"><span class="badge badge-light border">{{ $countTransactionAngkringan }} Transaksi</span></td>
                                    <td class="text-right align-middle font-weight-bold text-dark" style="font-size: 1.1rem;">
                                        Rp {{ number_format($totalIncomeAngkringan, 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr class="table-success">
                                    <td colspan="2" class="text-right font-weight-bold align-middle">
                                        TOTAL PENDAPATAN OPERASIONAL (A):
                                    </td>
                                    <td class="text-right font-weight-bold text-success" style="font-size: 1.2rem;">
                                        Rp {{ number_format($totalIncome, 0, ',', '.') }}
                                    </td>
                                </tr>

                                {{-- SECTION 2: BEBAN PENGELUARAN OPERASIONAL --}}
                                <tr class="bg-light">
                                    <td colspan="3" class="font-weight-bold text-danger">
                                        <i class="fas fa-minus-circle mr-1"></i> II. BEBAN & PENGELUARAN OPERASIONAL (EXPENSES)
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pl-4 py-3">
                                        <h6 class="mb-1 text-dark">1. Beban Gaji Karyawan (Barber)</h6>
                                        <div class="text-muted small">Bagi hasil komisi 45% + insentif</div>
                                    </td>
                                    <td class="text-center align-middle text-muted">-</td>
                                    <td class="text-right align-middle font-weight-bold text-dark" style="font-size: 1.1rem;">
                                        Rp {{ number_format($gajiBarber, 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pl-4 py-3">
                                        <h6 class="mb-1 text-dark">2. Beban Gaji Karyawan (Angkringan)</h6>
                                        <div class="text-muted small">Upah jam kerja & fee training</div>
                                    </td>
                                    <td class="text-center align-middle text-muted">-</td>
                                    <td class="text-right align-middle font-weight-bold text-dark" style="font-size: 1.1rem;">
                                        Rp {{ number_format($gajiAngkringan, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @if($gajiLainnya > 0)
                                <tr>
                                    <td class="pl-4 py-3">
                                        <h6 class="mb-1 text-dark">3. Beban Gaji Karyawan Lainnya</h6>
                                    </td>
                                    <td class="text-center align-middle text-muted">-</td>
                                    <td class="text-right align-middle font-weight-bold text-dark" style="font-size: 1.1rem;">
                                        Rp {{ number_format($gajiLainnya, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="pl-4 py-3">
                                        <h6 class="mb-1 text-dark">{{ $gajiLainnya > 0 ? '4' : '3' }}. Beban Bahan Baku Angkringan</h6>
                                        <div class="text-muted small">Belanja operasional angkringan (disetujui)</div>
                                    </td>
                                    <td class="text-center align-middle"><span class="badge badge-light border">{{ $countExpenseItems }} Item</span></td>
                                    <td class="text-right align-middle font-weight-bold text-dark" style="font-size: 1.1rem;">
                                        Rp {{ number_format($totalBahanBaku, 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr class="table-danger">
                                    <td colspan="2" class="text-right font-weight-bold align-middle">
                                        TOTAL BEBAN PENGELUARAN (B):
                                    </td>
                                    <td class="text-right font-weight-bold text-danger" style="font-size: 1.2rem;">
                                        - Rp {{ number_format($totalExpense, 0, ',', '.') }}
                                    </td>
                                </tr>

                                {{-- SECTION 3: LABA / RUGI BERSIH AKHIR --}}
                                <tr style="background-color: {{ $netProfit >= 0 ? '#f0fdf4' : '#fef2f2' }}; border-top: 2px solid {{ $netProfit >= 0 ? '#16a34a' : '#dc2626' }};">
                                    <td colspan="2" class="text-right font-weight-bold align-middle text-uppercase text-dark" style="font-size: 1.1rem;">
                                        Laba / Rugi Bersih Operasional (A - B):
                                    </td>
                                    <td class="text-right font-weight-bold align-middle" style="font-size: 1.4rem;">
                                        <div class="{{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $netProfit < 0 ? '- ' : '' }}Rp {{ number_format(abs($netProfit), 0, ',', '.') }}
                                        </div>
                                        <div class="small font-weight-normal text-muted mt-1" style="font-size: 0.85rem;">
                                            Margin: {{ $profitMargin }}%
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

@endsection
