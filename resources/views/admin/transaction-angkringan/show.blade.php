@extends('admin.layouts.master')

@section('main_content')
@include('admin.layouts.nav')
@include('admin.layouts.sidebar')

<div class="main-content">
<section class="section">

<div class="section-header justify-content-between">
    <h1>Detail Transaksi Angkringan</h1>
    <div class="ml-auto">
        <a href="{{ route('admin.transaction-angkringan.index') }}"
           class="btn btn-warning">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="section-body">
<div class="card">
<div class="card-body">

{{-- ================= INFO TRANSAKSI ================= --}}
<h5>Informasi Transaksi</h5>
<table class="table table-bordered">
    <tr>
        <th>Kode Transaksi</th>
        <td>{{ $transaction->kode_transaksi }}</td>
    </tr>
    <tr>
        <th>Tanggal</th>
        <td>{{ $transaction->tanggal->format('d M Y H:i') }}</td>
    </tr>
    <tr>
        <th>Metode Pembayaran</th>
        <td>
            <span class="badge badge-info text-uppercase">
                {{ $transaction->metode_pembayaran }}
            </span>
        </td>
    </tr>
    <tr>
        <th>Status</th>
        <td>
            <span class="badge badge-success">
                {{ strtoupper($transaction->status) }}
            </span>
        </td>
    </tr>
</table>

<hr>

{{-- ================= ITEM TRANSAKSI ================= --}}
<h5>Detail Menu</h5>
<table class="table table-striped table-bordered">
<thead>
<tr>
    <th>No</th>
    <th>Menu</th>
    <th>Harga</th>
    <th>Qty</th>
    <th>Subtotal</th>
</tr>
</thead>
<tbody>
@foreach ($transaction->items as $item)
<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $item->menu->nama }}</td>
    <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
    <td>{{ $item->qty }}</td>
    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
</tr>
@endforeach
</tbody>

<tfoot>
<tr>
    <th colspan="4" class="text-right">Total</th>
    <th>Rp {{ number_format($transaction->total, 0, ',', '.') }}</th>
</tr>

@if($transaction->metode_pembayaran === 'cash')
<tr>
    <th colspan="4" class="text-right">Jumlah Bayar</th>
    <th>
        Rp {{ number_format($transaction->jumlah_bayar, 0, ',', '.') }}
    </th>
</tr>
<tr>
    <th colspan="4" class="text-right">Kembalian</th>
    <th>
        Rp {{ number_format($transaction->kembalian, 0, ',', '.') }}
    </th>
</tr>
@endif
</tfoot>
</table>

</div>
</div>
</div>
</section>
</div>

@endsection
