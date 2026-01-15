@extends('admin.layouts.master')

@section('main_content')
@include('admin.layouts.nav')
@include('admin.layouts.sidebar')

<div class="main-content">
<section class="section">

<div class="section-header">
    <h1>Penjualan Mitra: {{ $mitra->nama_mitra }}</h1>
</div>

<div class="section-body">

{{-- FILTER --}}
<div class="card mb-3">
<div class="card-body">
<form method="GET">
<div class="row">
    <div class="col-md-4">
        <input type="date" name="from" class="form-control" value="{{ $from }}">
    </div>
    <div class="col-md-4">
        <input type="date" name="to" class="form-control" value="{{ $to }}">
    </div>
    <div class="col-md-4">
        <button class="btn btn-primary">
            <i class="fas fa-filter"></i> Filter
        </button>
        <a href="{{ route('admin.mitras.show', $mitra->id_mitra) }}"
           class="btn btn-warning">
           Reset
        </a>
    </div>
</div>
</form>
</div>
</div>
<div class="card">
<div class="card-body">
<div class="table-responsive">

<table class="table table-bordered">
<thead>
<tr>
    <th>No</th>
    <th>Nama Menu</th>
    <th>Total Terjual (PCS)</th>
    <th>Total Pendapatan</th>
</tr>
</thead>
<tbody>
@forelse($menus as $key => $menu)
<tr>
    <td>{{ $key + 1 }}</td>
    <td>{{ $menu->nama_menu }}</td>
    <td class="text-center">{{ $menu->total_terjual }}</td>
    <td>
        Rp {{ number_format($menu->total_pendapatan, 0, ',', '.') }}
    </td>
</tr>
@empty
<tr>
    <td colspan="4" class="text-center">
        Belum ada transaksi
    </td>
</tr>
@endforelse
</tbody>

<tfoot>
<tr>
    <th colspan="2">TOTAL</th>
    <th class="text-center">{{ $grandTotalQty }}</th>
    <th>
        Rp {{ number_format($grandTotalRp, 0, ',', '.') }}
    </th>
</tr>
</tfoot>

</table>

</div>
</div>
</div>

</div>
</section>
</div>
@endsection
