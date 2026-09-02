@extends('admin.layouts.master')

@section('main_content')

@include('admin.layouts.nav')
@include('admin.layouts.sidebar')

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Data Pengeluaran Bahan Baku Angkringan</h1>
        </div>

        <div class="section-body">
            
            @if(session('success'))
            <div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                    {{ session('success') }}
                </div>
            </div>
            @endif

            <div class="card">
                {{-- FILTER --}}
                <div class="card-header">
                    <form action="{{ route('admin.angkringan-expense.index') }}" method="GET" class="w-100">
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

                            {{-- Status --}}
                            <div class="col-md-2 mb-2">
                                <select name="status" class="form-control">
                                    <option value="">-- Status --</option>
                                    <option value="pengajuan" {{ request('status') == 'pengajuan' ? 'selected' : '' }}>Pengajuan</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>

                            {{-- Cari Nama / Kasir --}}
                            <div class="col-md-3 mb-2">
                                <input type="text"
                                       name="search"
                                       class="form-control"
                                       placeholder="Cari Pengeluaran / Kasir"
                                       value="{{ request('search') }}">
                            </div>

                            {{-- Buttons --}}
                            <div class="col-md-3 mb-2">
                                <button class="btn btn-primary">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                                <a href="{{ route('admin.angkringan-expense.index') }}" class="btn btn-secondary">
                                    Reset
                                </a>
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
                                    <th>Tanggal</th>
                                    <th>Nama Pengeluaran</th>
                                    <th>Jumlah</th>
                                    <th>Nama Kasir</th>
                                    <th>Bukti Resi</th>
                                    <th>Status</th>
                                    <th width="150">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse ($expenses as $key => $expense)
                                <tr>
                                    <td>{{ $expenses->firstItem() + $key }}</td>
                                    <td>{{ $expense->tanggal->format('d-m-Y H:i') }}</td>
                                    <td>{{ $expense->nama_pengeluaran }}</td>
                                    <td>Rp {{ number_format($expense->jumlah, 0, ',', '.') }}</td>
                                    <td>{{ $expense->nama_kasir ?? '-' }}</td>
                                    <td class="text-center">
                                        @if($expense->bukti_resi)
                                            <a href="#" class="view-receipt" data-bs-toggle="modal" data-bs-target="#receiptModal" data-src="{{ asset('storage/' . $expense->bukti_resi) }}">
                                                <img src="{{ asset('storage/' . $expense->bukti_resi) }}" alt="Resi" style="max-height: 40px; border-radius: 4px; border: 1px solid #ddd; padding: 2px;">
                                            </a>
                                        @else
                                            <span class="badge badge-warning text-small">Belum Ada Resi</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($expense->status === 'approved')
                                            <span class="badge badge-success text-uppercase">Approved</span>
                                        @elseif($expense->status === 'rejected')
                                            <span class="badge badge-danger text-uppercase">Rejected</span>
                                        @else
                                            <span class="badge badge-warning text-uppercase">Pengajuan</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex" style="gap: 5px;">
                                            @if($expense->bukti_resi)
                                                <button class="btn btn-info btn-sm view-receipt" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#receiptModal" 
                                                        data-src="{{ asset('storage/' . $expense->bukti_resi) }}" 
                                                        title="Lihat Resi">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            @endif

                                            @if($expense->status !== 'approved')
                                                <form action="{{ route('admin.angkringan-expense.status', $expense->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="approved">
                                                    <button class="btn btn-success btn-sm" title="Approve" onclick="return confirm('Setujui pengeluaran ini?')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            @if($expense->status !== 'rejected')
                                                <form action="{{ route('admin.angkringan-expense.status', $expense->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button class="btn btn-warning btn-sm" title="Reject" onclick="return confirm('Tolak pengeluaran ini?')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            @if(auth()->user()->role === 'admin')
                                                <form action="{{ route('admin.angkringan-expense.destroy', $expense->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data pengeluaran ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-sm" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Data pengeluaran tidak ditemukan</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $expenses->links() }}
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<!-- Modal Bukti Resi -->
<div class="modal fade" id="receiptModal" tabindex="-1" role="dialog" aria-labelledby="receiptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                                                <h5 class="modal-title" id="receiptModalLabel">Bukti Resi / Pembelian</h5>
                                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
            <div class="modal-body text-center p-3">
                <img id="modalReceiptImg" src="" alt="Resi Pengeluaran" class="img-fluid" style="border-radius: 5px; max-height: 500px;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.view-receipt').on('click', function(e) {
        e.preventDefault();
        var src = $(this).data('src');
        $('#modalReceiptImg').attr('src', src);
    });
});
</script>
@endpush
