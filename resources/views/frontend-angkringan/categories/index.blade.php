@extends('admin.layouts.master')
@section('main_content')
@include('admin.layouts.nav')
@include('admin.layouts.sidebar')
<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Kategori Menu</h1>
            <div class="ml-auto">
               @if(auth()->user()->role === 'admin')
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambahkan Kategori Menu
    </a>
@endif

            </div>
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
                                            <th>Nama Kategori</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
    @forelse ($categories as $key => $category)
        <tr>
            <td>{{ $key + 1 }}</td>

            <td>
                {{ $category->nama }}
            </td>

        <td class="text-center">
    <label class="custom-switch">
        <input type="checkbox"
               class="custom-switch-input toggle-status"
               data-id="{{ $category->id_category }}"
               {{ $category->status ? 'checked' : '' }}>
        <span class="custom-switch-indicator"></span>
    </label>
</td>


           <td class="pt_10 pb_10">
    @if(auth()->user()->role === 'admin')
        <a href="{{ route('admin.categories.edit', $category->id_category) }}"
           class="btn btn-primary btn-sm">
            <i class="fas fa-edit"></i>
        </a>

        <form action="{{ route('admin.categories.destroy', $category->id_category) }}"
              method="POST"
              style="display:inline-block"
              onsubmit="return confirm('Yakin ingin menghapus data ini?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">
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
            <td colspan="5" class="text-center">
                Data Kategori Menu belum tersedia
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

@push('scripts')
<script>
    $(document).on('change', '.toggle-status', function () {
        let categoryId = $(this).data('id');

        $.ajax({
            url: `/admin/categories/${categoryId}/toggle-status`,
            type: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function (res) {
                if (res.success) {
                    console.log('Status updated:', res.status);
                }
            },
            error: function () {
                alert('Gagal mengubah status');
            }
        });
    });
</script>
@endpush
