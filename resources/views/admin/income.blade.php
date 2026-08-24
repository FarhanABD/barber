@extends('admin.layouts.master')

@section('main_content')

@include('admin.layouts.nav')
@include('admin.layouts.sidebar')

<div class="main-content">
    <section class="section">

        <div class="section-header">
            <h1>Income</h1>
        </div>

        <div class="section-body">

            {{-- FILTER TANGGAL --}}
            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-5">
                                <label>Rentang Tanggal</label>
                                <div class="input-group">
                                    <input type="date" name="start_date" value="{{ request('start_date', $startDate ?? '') }}" class="form-control" placeholder="Mulai">
                                    <div class="input-group-append input-group-prepend">
                                        <span class="input-group-text">s/d</span>
                                    </div>
                                    <input type="date" name="end_date" value="{{ request('end_date', $endDate ?? '') }}" class="form-control" placeholder="Selesai">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-primary">Filter</button>
                                <a href="{{ route('admin.income') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- INCOME --}}
            <div class="row">
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h6>Income Barber</h6>
                            <h4 class="text-success">
                                Rp {{ number_format($totalBarber,0,',','.') }}
                            </h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h6>Income Angkringan</h6>
                            <h4 class="text-success">
                                Rp {{ number_format($totalAngkringan,0,',','.') }}
                            </h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card text-center bg-light">
                        <div class="card-body">
                            <h6>Total Income</h6>
                            <h3 class="text-primary">
                                Rp {{ number_format($grandTotal,0,',','.') }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

@endsection
