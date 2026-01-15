@extends('admin.layouts.master')

@section('main_content')
@include('admin.layouts.nav')
@include('admin.layouts.sidebar')

<div class="main-content">
<section class="section">
<div class="section-header justify-content-between">
    <h1>Detail Transaksi</h1>
    <div class="ml-auto">
        <a href="{{ route('admin.transactions.pdf', $transaction->id) }}"
           class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> Download PDF
        </a>
    </div>
</div>

<div class="section-body">
<div class="card">
<div class="card-body">

<h5>Informasi Transaksi</h5>
<table class="table table-bordered">
<tr>
    <th>ID Transaksi</th>
    <td>{{ $transaction->transaction_code }}</td>
</tr>
<tr>
    <th>Customer</th>
    <td>{{ $transaction->customer_name }}</td>
</tr>
<tr>
    <th>Barber</th>
    <td>{{ $transaction->barber->name ?? '-' }}</td>
</tr>
<tr>
    <th>Tanggal</th>
    <td>{{ $transaction->created_at->format('d M Y H:i') }}</td>
</tr>
</table>

<hr>

<h5>Detail Layanan</h5>
<table class="table table-striped table-bordered">
<thead>
<tr>
    <th>No</th>
    <th>Layanan</th>
    <th>Harga</th>
</tr>
</thead>
<tbody>
@php $total = 0; @endphp
@foreach ($transaction->items as $item)
@php $total += $item->price; @endphp
<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $item->service->name }}</td>
    <td>Rp {{ number_format($item->price,0,',','.') }}</td>
</tr>
@endforeach
</tbody>
<tfoot>
<tr>
    <th colspan="2">Subtotal</th>
    <th>Rp {{ number_format($total,0,',','.') }}</th>
</tr>
<tr>
    <th colspan="2">Diskon ({{ $transaction->diskon }}%)</th>
    <th>
        Rp {{ number_format(($transaction->diskon/100)*$total,0,',','.') }}
    </th>
</tr>
<tr>
    <th colspan="2">Total Bayar</th>
    <th>
        Rp {{ number_format($transaction->total_price,0,',','.') }}
    </th>
</tr>
</tfoot>
</table>

</div>
</div>
</div>
</section>
</div>
@endsection
