<!DOCTYPE html>
<html lang="en">
@include('admin.layouts.master')

<body>
<div id="app">
    <div class="main-wrapper">

        <div class="navbar-bg"></div>
        <nav class="navbar navbar-expand-lg main-navbar">
            <form class="form-inline mr-auto">
                <ul class="navbar-nav mr-3">
                    <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
                </ul>
            </form>
            <ul class="navbar-nav navbar-right justify-content-end rightsidetop">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img alt="image" src="{{ asset('frontend/images/Antarshuka.png') }}" class="rounded-circle-custom">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                      <form id="logout-form" action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item" type="submit">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </ul>
                </li>
            </ul>
        </nav>

        @include('admin.layouts.sidebar')

        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>Dashboard</h1>
                </div>

                @auth
                @if(auth()->user()->role === 'admin')
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="far fa-user"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Layanan</h4>
                                </div>
                             <div class="card-body">
    {{ $totalService }}
</div>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-danger">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Barber</h4>
                                </div>
                               <div class="card-body">
    {{ $totalBarber }}
</div>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-warning">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Transaksi</h4>
                                </div>
                               <div class="card-body">
    {{ $totalTransaction }}
</div>

                            </div>
                        </div>
                    </div>
                </div>
                @endif
                {{-- Tampilkan halaman kasir di dashboard --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Kasir</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.transactions.store') }}" method="POST" id="transactionForm">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="customer_name" class="form-label">Nama Pelanggan</label>
                                        <input type="text" name="customer_name" id="customer_name" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="barber_id" class="form-label">Pilih Barber</label>
                                        <select name="barber_id" id="barber_id" class="form-control" required>
                                            <option value="">-- Pilih Barber --</option>
                                            @foreach($barbers as $barber)
                                                <option value="{{ $barber->id }}">{{ $barber->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="diskon" class="form-label">Diskon</label>
                                        <input type="text" name="diskon" id="diskon" class="form-control" placeholder="Masukkan Diskon Jika ada" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Pilih Layanan</label>
                                        <table class="table table-bordered" id="servicesTable">
                                            <thead>
                                                <tr>
                                                    <th>Layanan</th>
                                                    <th>Harga</th>
                                                    <th><button type="button" class="btn btn-sm btn-success" id="addServiceRow">+</button></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <select name="services[]" class="form-control service-select" required>
                                                            <option value="">-- Pilih Layanan --</option>
                                                            @foreach($services as $service)
                                                                <option value="{{ $service->id }}" data-price="{{ $service->price }}">
                                                                    {{ $service->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control service-price" value="0" readonly>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-danger remove-row">x</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mb-3 text-end">
                                        <h5>Total: Rp <span id="totalPrice">0</span></h5>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endauth

            </section>
        </div>

    </div>
</div>

<script src="{{ asset('dist/js/scripts.js') }}"></script>
<script src="{{ asset('dist/js/custom.js') }}"></script>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const servicesTable = document.getElementById('servicesTable').getElementsByTagName('tbody')[0];

    document.getElementById('addServiceRow').addEventListener('click', function() {
        const newRow = servicesTable.rows[0].cloneNode(true);
        newRow.querySelector('select').value = '';
        newRow.querySelector('.service-price').value = '0';
        servicesTable.appendChild(newRow);
        calculateTotal();
    });

    servicesTable.addEventListener('click', function(e) {
        if(e.target && e.target.classList.contains('remove-row')) {
            if(servicesTable.rows.length > 1) {
                e.target.closest('tr').remove();
                calculateTotal();
            }
        }
    });

    servicesTable.addEventListener('change', function(e) {
    if(e.target && e.target.classList.contains('service-select')) {
        const priceInput = e.target.closest('tr').querySelector('.service-price');
        const price = parseInt(e.target.selectedOptions[0].dataset.price) || 0;
        priceInput.value = price;
        calculateTotal();
    }
    });

    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.service-price').forEach(function(input) {
            total += parseInt(input.value) || 0;
        });
        document.getElementById('totalPrice').textContent = total.toLocaleString();
    }

});
</script>

</body>
</html>
