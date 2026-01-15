<!DOCTYPE html>
<html lang="en">
@include('admin.layouts.master')

<body>
<div id="app">
    <div class="main-wrapper">

    @include('admin.layouts.nav')
    @include('admin.layouts.sidebar')

        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>Dashboard</h1>
                </div>

                @auth
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'user')
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="far fa-user"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>No Antrian Booking</h4>
                                </div>
                             <div class="card-body text-center">
    @if($currentBooking)
        <h2 class="mb-0">A-{{ str_pad($currentBooking->no_antrian, 3, '0', STR_PAD_LEFT) }}</h2>
    @else
        <span class="text-muted">Tidak ada antrian booking</span>
    @endif
</div>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
        <div class="card-icon bg-success">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="card-wrap">
            <div class="card-header">
                <h4>Omset Hari Ini</h4>
            </div>
            <div class="card-body text-center">
                Rp {{ number_format($totalOmset, 0, ',', '.') }}
            </div>
        </div>
    </div>
</div>

                  <div class="col-lg-4 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
        <div class="card-icon bg-warning">
            <i class="fas fa-receipt"></i>
        </div>
        <div class="card-wrap">
            <div class="card-header">
                <h4>Total Transaksi Hari Ini</h4>
            </div>
            <div class="card-body text-center">
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
                           <div class="card-header d-flex justify-content-between align-items-center">
    <h4 id="kasirTitle">Kasir Barber</h4>

    <div class="custom-switch custom-switch-label">
        <input type="checkbox"
               class="custom-switch-input"
               id="switchKasir">
        <label class="custom-switch-indicator" for="switchKasir"></label>
        <span class="custom-switch-description">Angkringan</span>
    </div>
</div>

                            <div class="card-body" id="formBarber">
                                <form action="{{ route('admin.transactions.store') }}" method="POST" id="transactionForm">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="customer_name" class="form-label">Nama Pelanggan</label>
                                    <input type="text" name="customer_name" class="form-control"
                                        value="{{ $currentBooking->nama_customer ?? '' }}"
                                        {{ $currentBooking ? 'readonly' : '' }}>
                                    </div>

                                    @if($currentBooking)
                                        <input type="hidden" name="booking_id" value="{{ $currentBooking->id }}">
                                    @endif


                                    <div class="mb-3">
                                        <label for="barber_id" class="form-label">Pilih Barber</label>
                                       <select name="barber_id" class="form-control" {{ $currentBooking ? 'disabled' : '' }}>
                                            <option value="">-- Pilih Barber --</option>
                                            @foreach($barbers as $barber)
                                                <option value="{{ $barber->id }}"
                                                    @if($currentBooking && $currentBooking->barber_id == $barber->id) selected @endif>
                                                    {{ $barber->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if($currentBooking)
                                        <input type="hidden" name="barber_id" value="{{ $currentBooking->barber_id }}">
                                        @endif

                                    </div>

                                    <div class="mb-3">
                                        <label for="diskon" class="form-label">Diskon</label>
                                        <input type="text" name="diskon" id="diskon" class="form-control" placeholder="Masukkan Diskon Jika ada" >
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
                                                     <select name="services[]" class="form-control service-select">
                                                        <option value="">-- Pilih Layanan --</option>
                                                        @foreach($services as $service)
                                                            <option 
                                                                value="{{ $service->id }}"
                                                                data-price="{{ $service->price }}"
                                                                @if($currentBooking && $currentBooking->service_id == $service->id) selected @endif
                                                            >
                                                                {{ $service->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    </td>
                                                    <td>
                                                        {{-- <input type="text" class="form-control service-price" value="0" readonly> --}}
                                                        <input type="text" class="form-control service-price" value="{{ $currentBooking->total_price ?? 0 }}" readonly>

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

                            <div class="card-body" id="formAngkringan" style="display:none;">
    <form action="{{ route('admin.transaction-angkringan.store') }}" method="POST">
        @csrf

        <table class="table table-bordered" id="angkringanTable">
            <thead>
                <tr>
                    <th>Menu</th>
                    <th width="120">Harga</th>
                    <th width="80">Qty</th>
                    <th width="140">Subtotal</th>
                    <th width="50">
                        <button type="button" class="btn btn-sm btn-success" id="addRowAngkringan">+</button>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select name="items[0][menu_id]" class="form-control menu-select">
                            <option value="">-- Pilih Menu --</option>
                            @foreach($menus as $menu)
                                <option value="{{ $menu->id_menu }}"
                                    data-harga="{{ $menu->harga }}">
                                    {{ $menu->nama }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control harga" readonly value="0">
                    </td>
                    <td>
                      <div class="d-flex align-items-center">
    <button type="button" class="btn btn-sm btn-danger btn-minus">−</button>

    <input type="text"
           name="items[0][qty]"
           class="form-control qty mx-1 text-center"
           value="1"
           readonly
           style="width:50px;">

    <button type="button" class="btn btn-sm btn-success btn-plus">+</button>
</div>

                    </td>
                    <td>
                        <input type="text" class="form-control subtotal" readonly value="0">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger remove-row">x</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="mb-3">
    <label class="form-label">Metode Pembayaran</label>
    <select name="metode_pembayaran" class="form-control" required>
        <option value="">-- Pilih Metode Pembayaran --</option>
        <option value="cash">Cash</option>
        <option value="qris">QRIS</option>
        <option value="transfer">Transfer</option>
    </select>
</div>

<div id="cashFields" style="display:none;">
    <div class="mb-3">
        <label class="form-label">Jumlah Bayar</label>
        <input type="number"
               name="jumlah_bayar"
               id="jumlahBayar"
               class="form-control"
               min="0">
    </div>

    <div class="mb-3">
        <label class="form-label">Kembalian</label>
        <input type="text"
               name="kembalian"
               id="kembalian"
               class="form-control"
               readonly>
    </div>
</div>



        <div class="text-end mb-3">
            <h5>Total: Rp <span id="totalAngkringan">0</span></h5>
        </div>

        <button class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan Transaksi Angkringan
        </button>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const servicesTable = document.getElementById('servicesTable').getElementsByTagName('tbody')[0];

    document.querySelectorAll('.service-select').forEach(select => {
        if (select.value) {
            select.dispatchEvent(new Event('change'));
        }
    });
});
</script>

<script>
document.getElementById('switchKasir').addEventListener('change', function () {
    const formBarber = document.getElementById('formBarber');
    const formAngkringan = document.getElementById('formAngkringan');
    const title = document.getElementById('kasirTitle');

    if (this.checked) {
        formBarber.style.display = 'none';
        formAngkringan.style.display = 'block';
        title.innerText = 'Kasir Angkringan';
    } else {
        formBarber.style.display = 'block';
        formAngkringan.style.display = 'none';
        title.innerText = 'Kasir Barber';
    }
});
</script>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.querySelector('#angkringanTable tbody');
    const totalText = document.getElementById('totalAngkringan');
    let index = 1;

    // Tambah baris
    document.getElementById('addRowAngkringan').addEventListener('click', function () {
        const row = tableBody.rows[0].cloneNode(true);

        row.querySelector('.menu-select').name = `items[${index}][menu_id]`;
        row.querySelector('.qty').name = `items[${index}][qty]`;

        row.querySelector('.menu-select').value = '';
        row.querySelector('.harga').value = 0;
        row.querySelector('.qty').value = 1;
        row.querySelector('.subtotal').value = 0;

        tableBody.appendChild(row);
        index++;
    });

    // Hapus baris
    tableBody.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row')) {
            if (tableBody.rows.length > 1) {
                e.target.closest('tr').remove();
                hitungTotal();
            }
        }
    });

    // Pilih menu
    tableBody.addEventListener('change', function (e) {
        if (e.target.classList.contains('menu-select')) {
            const row = e.target.closest('tr');
            const harga = e.target.selectedOptions[0]?.dataset.harga || 0;

            row.querySelector('.harga').value = harga;
            hitungSubtotal(row);
        }
    });

    // Tombol +
    tableBody.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-plus')) {
            const row = e.target.closest('tr');
            const qtyInput = row.querySelector('.qty');
            qtyInput.value = parseInt(qtyInput.value) + 1;
            hitungSubtotal(row);
        }
    });

    // Tombol -
    tableBody.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-minus')) {
            const row = e.target.closest('tr');
            const qtyInput = row.querySelector('.qty');
            const qty = parseInt(qtyInput.value);
            if (qty > 1) {
                qtyInput.value = qty - 1;
                hitungSubtotal(row);
            }
        }
    });

    function hitungSubtotal(row) {
        const harga = parseInt(row.querySelector('.harga').value) || 0;
        const qty = parseInt(row.querySelector('.qty').value) || 1;
        row.querySelector('.subtotal').value = harga * qty;
        hitungTotal();
    }

    function hitungTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal').forEach(input => {
            total += parseInt(input.value) || 0;
        });
        totalText.textContent = total.toLocaleString('id-ID');
    }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const metodeSelect = document.querySelector('[name="metode_pembayaran"]');
    const cashFields = document.getElementById('cashFields');
    const jumlahBayar = document.getElementById('jumlahBayar');
    const kembalian = document.getElementById('kembalian');
    const totalText = document.getElementById('totalAngkringan');

    metodeSelect.addEventListener('change', function () {
        if (this.value === 'cash') {
            cashFields.style.display = 'block';
        } else {
            cashFields.style.display = 'none';
            jumlahBayar.value = '';
            kembalian.value = '';
        }
    });

    jumlahBayar.addEventListener('input', function () {
        const total = parseInt(totalText.innerText.replace(/\./g, '')) || 0;
        const bayar = parseInt(this.value) || 0;
        const kembali = bayar - total;

        kembalian.value = kembali > 0 ? kembali.toLocaleString('id-ID') : 0;
    });
});
</script>




</body>
</html>
