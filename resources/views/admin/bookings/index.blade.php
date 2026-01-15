@extends('admin.layouts.master')

@section('main_content')
@include('admin.layouts.nav')
@include('admin.layouts.sidebar')

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Data Booking</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="example1">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No Antrian</th>
                                            <th>Nama Customer</th>
                                            <th>Barber</th>
                                            <th>Layanan</th>
                                            <th>Waktu</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                    @forelse ($bookings as $key => $booking)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>

                                            <td>
                                                <span class="badge badge-info">
                                                    {{ $booking->no_antrian }}
                                                </span>
                                            </td>

                                            <td>{{ $booking->nama_customer }}</td>

                                            <td>{{ $booking->barber->name ?? '-' }}</td>

                                            <td>{{ $booking->service->name ?? '-' }}</td>

                                            <td>
                                                <small>
                                                    {{ \Carbon\Carbon::parse($booking->start_time)->format('d M Y H:i') }}
                                                    <br>
                                                    s/d
                                                    <br>
                                                    {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                                                </small>
                                            </td>

                                            <td>
                                                @if($booking->status === 'pending')
                                                    <span class="badge badge-warning">Pending</span>
                                                @elseif($booking->status === 'confirmed')
                                                    <span class="badge badge-primary">Confirmed</span>
                                                @elseif($booking->status === 'completed')
                                                    <span class="badge badge-success">Completed</span>
                                                @else
                                                    <span class="badge badge-danger">Canceled</span>
                                                @endif
                                            </td>

                                            <td class="pt_10 pb_10">
                                                @if(auth()->user()->role === 'admin')

                                                    @if($booking->status === 'pending')
                                                        <form action="{{ route('admin.bookings.confirm', $booking->id) }}"
                                                              method="POST"
                                                              style="display:inline-block">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button class="btn btn-primary btn-sm">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                    @endif

                                                    @if($booking->status === 'confirmed')
                                                        <form action="{{ route('admin.bookings.complete', $booking->id) }}"
                                                              method="POST"
                                                              style="display:inline-block">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button class="btn btn-success btn-sm">
                                                                <i class="fas fa-cut"></i>
                                                            </button>
                                                        </form>
                                                    @endif

                                                    @if($booking->status !== 'completed')
                                                        <form action="{{ route('admin.bookings.cancel', $booking->id) }}"
                                                              method="POST"
                                                              style="display:inline-block"
                                                              onsubmit="return confirm('Batalkan booking ini?')">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button class="btn btn-danger btn-sm">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                    @endif

                                                @else
                                                    <span class="badge badge-secondary">No Access</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">
                                                Data booking belum tersedia
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
