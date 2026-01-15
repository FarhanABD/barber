@extends('admin.layouts.master')

@section('main_content')
@include('admin.layouts.nav')
@include('admin.layouts.sidebar')

<div class="main-content">
<section class="section">

<div class="section-header">
    <h1>Laporan Karyawan: {{ $barber->name }}</h1>
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
        <a href="{{ route('admin.karyawan.show', $barber->id) }}"
           class="btn btn-warning">
           Reset
        </a>
    </div>
</div>
</form>
</div>
</div>

{{-- HASIL --}}
<div class="card">
<div class="card-body">
<div class="table-responsive">

<table class="table table-bordered">
<thead>
<tr>
    <th>Total Orang Dipotong</th>
    <th>Total Omset</th>
</tr>
</thead>
<tbody>
<tr>
    <td class="text-center">
        <span class="badge badge-success" style="font-size: 16px;">
            {{ $totalOrang }} Orang
        </span>
    </td>
    <td>
        Rp {{ number_format($totalOmset, 0, ',', '.') }}
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
