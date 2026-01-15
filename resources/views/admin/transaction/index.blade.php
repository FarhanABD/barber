@extends('admin.layouts.master')

@section('main_content')

@include('admin.layouts.nav')
@include('admin.layouts.sidebar')

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Data Transaksi Barber</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">

                        {{-- FILTER & EXPORT --}}
                        <div class="card-header">
                            <form action="{{ route('admin.transactions.index') }}" method="GET" class="w-100">
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="date"
                                               name="date"
                                               class="form-control"
                                               value="{{ request('date') }}">
                                    </div>

                                    <div class="col-md-4">
                                        <input type="text"
                                               name="search"
                                               class="form-control"
                                               placeholder="Cari ID / Customer / Barber"
                                               value="{{ request('search') }}">
                                    </div>

                                    <div class="col-md-5">
                                        <button class="btn btn-primary">
                                            <i class="fas fa-search"></i> Filter
                                        </button>

                                        <a href="{{ route('admin.transactions.index') }}"
                                           class="btn btn-secondary">
                                            Reset
                                        </a>

                                        <a href="{{ route('admin.transactions.export', request()->query()) }}"
                                           class="btn btn-success">
                                            <i class="fas fa-file-excel"></i> Export Excel
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        {{-- END FILTER --}}

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="example1">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>ID Transaksi</th>
                                            <th>Nama Customer</th>
                                            <th>Nama Barber</th>
                                            <th>Total Harga</th>
                                            <th width="120">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($transactions as $key => $transaction)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $transaction->transaction_code }}</td>
                                                <td>{{ $transaction->customer_name }}</td>
                                                <td>{{ $transaction->barber->name ?? '-' }}</td>
                                                <td>
                                                    Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                                                </td>
                                                <td>
    <a href="{{ route('admin.transactions.show', $transaction->id) }}"
       class="btn btn-info btn-sm">
        <i class="fas fa-eye"></i>
    </a>

    @if(auth()->user()->role === 'admin')
        <form action="{{ route('admin.transactions.destroy', $transaction->id) }}"
              method="POST"
              class="d-inline"
              onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger btn-sm">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    @else
        <span class="badge badge-secondary">No Access</span>
    @endif
</td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    Data transaksi belum tersedia
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
@push('scripts')
@if(session('print_transaction_id'))
<script>
Swal.fire({
    title: 'Cetak Resi?',
    text: 'Apakah Anda ingin mencetak resi transaksi ini?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Ya, Cetak',
    cancelButtonText: 'Tidak'
}).then((result) => {
    if (result.isConfirmed) {
        window.open(
            "{{ route('admin.transactions.print', session('print_transaction_id')) }}",
            '_blank'
        );
    }
});
</script>
@endif
@endpush




@endsection
