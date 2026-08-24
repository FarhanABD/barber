@extends('admin.layouts.master')

@section('main_content')

@include('admin.layouts.nav')
@include('admin.layouts.sidebar')

<div class="main-content">
<section class="section">
    <div class="section-header justify-content-between">
        <h1>Data Transaksi Angkringan</h1>
    </div>

    <div class="section-body">
        <div class="card">

            {{-- FILTER --}}
            <div class="card-header">
               <form action="{{ route('admin.transaction-angkringan.index') }}" method="GET" class="w-100">
    <div class="row">

        {{-- Rentang Tanggal --}}
        <div class="col-md-4 mb-2">
            <div class="input-group">
                <input type="date"
                       name="start_date"
                       class="form-control"
                       value="{{ request('start_date') }}"
                       data-toggle="tooltip"
                       title="Tanggal Mulai">
                <div class="input-group-append input-group-prepend">
                    <span class="input-group-text">s/d</span>
                </div>
                <input type="date"
                       name="end_date"
                       class="form-control"
                       value="{{ request('end_date') }}"
                       data-toggle="tooltip"
                       title="Tanggal Selesai">
            </div>
        </div>

        {{-- Nama Kasir --}}
        <div class="col-md-2 mb-2">
            <input type="text"
                   name="kasir"
                   class="form-control"
                   placeholder="Nama Kasir"
                   value="{{ request('kasir') }}">
        </div>

        {{-- Metode Pembayaran --}}
        <div class="col-md-2 mb-2">
            <select name="metode"
                    class="form-control">
                <option value="">-- Metode --</option>
                <option value="cash" {{ request('metode') == 'cash' ? 'selected' : '' }}>Cash</option>
                <option value="qris" {{ request('metode') == 'qris' ? 'selected' : '' }}>QRIS</option>
                <option value="transfer" {{ request('metode') == 'transfer' ? 'selected' : '' }}>Transfer</option>
            </select>
        </div>

        {{-- Kode Transaksi --}}
        <div class="col-md-2 mb-2">
            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="Kode Transaksi"
                   value="{{ request('search') }}">
        </div>

        {{-- Button --}}
        <div class="col-md-2 mb-2">
            <button class="btn btn-primary w-100 mb-1">
                <i class="fas fa-search"></i> Filter
            </button>
            <div class="d-flex" style="gap: 5px;">
                <a href="{{ route('admin.transaction-angkringan.index') }}"
                   class="btn btn-secondary btn-sm flex-fill text-center">
                    Reset
                </a>
                <a href="{{ route('admin.transaction-angkringan.export', request()->query()) }}"
                   class="btn btn-success btn-sm flex-fill text-center" style="white-space: nowrap;">
                    <i class="fas fa-file-excel"></i> Export
                </a>
            </div>
        </div>

    </div>
</form>

            </div>

            {{-- TABLE --}}
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Transaksi</th>
                                <th>Tanggal</th>
                                <th>Metode Pembayaran</th>
                                <th>Total</th>
                                <th width="120">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($transactions as $key => $trx)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $trx->kode_transaksi }}</td>
                                <td>{{ $trx->tanggal->format('d-m-Y H:i') }}</td>
                                <td>
                                    <span class="badge badge-info text-uppercase">
                                        {{ $trx->metode_pembayaran }}
                                    </span>
                                </td>
                                <td>
                                    Rp {{ number_format($trx->total, 0, ',', '.') }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.transaction-angkringan.show', $trx->id_transaction) }}"
                                       class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                   @if(auth()->user()->role === 'admin')
        <form action="{{ route('admin.transaction-angkringan.destroy', $trx->id_transaction) }}"
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
                 <div class="mt-4">
                                {{ $transactions->links() }}
                            </div>
            </div>

        </div>
    </div>
</section>
</div>

@endsection

@push('scripts')
@if(session('print_transaction_id'))
<script>
Swal.fire({
    title: 'Cetak Struk?',
    text: 'Apakah Anda ingin mencetak struk transaksi ini?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Ya, Cetak',
    cancelButtonText: 'Tidak'
}).then((result) => {
    if (result.isConfirmed) {
        printRawBT({{ session('print_transaction_id') }});
    }
});


function printRawBT(id){

fetch(`/admin/transaction-angkringan/${id}/json`)
.then(res => res.json())
.then(trx => {

    let struk = "";
    struk += "ANGKRINGAN ANTARSUKHA\r\n";
    struk += "------------------------------\r\n";
    struk += "Kode : "+trx.kode+"\r\n";
    struk += "Kasir : "+trx.kasir+"\r\n";
    struk += "Tanggal : "+trx.tanggal+"\r\n";
    struk += "------------------------------\r\n";

    trx.items.forEach(i=>{
        struk += i.nama+"\r\n";
        struk += i.qty+" x "+i.harga+" = "+i.subtotal+"\r\n";
    });

    struk += "------------------------------\r\n";
    struk += "TOTAL : "+trx.total+"\r\n";
    struk += "TERIMA KASIH\r\n\r\n\r\n";

    let encoded = encodeURIComponent(struk);

    window.location.href = "rawbt://print?text=" + encoded;

});
}
</script>
@endif
@endpush

