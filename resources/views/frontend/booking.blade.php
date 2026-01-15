<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Booking Barber</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(
                rgba(0,0,0,0.6),
                rgba(0,0,0,0.6)
            ),
            url('https://images.unsplash.com/photo-1599351431202-1e0f0137899a') no-repeat center center;
            background-size: cover;
            font-family: 'Segoe UI', sans-serif;
        }

        .booking-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
            padding: 30px;
        }

        .booking-title {
            font-weight: 700;
            letter-spacing: 1px;
        }

        .form-label {
            font-weight: 600;
        }

        .btn-primary {
            background: #111;
            border: none;
        }

        .btn-primary:hover {
            background: #000;
        }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="col-md-7 col-lg-6">
        <div class="booking-card">
            <h2 class="mb-4 text-center booking-title">
                ✂️ Booking Barber
            </h2>

            @if(session('success'))
                <div class="alert alert-success text-center">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ url('/bookings') }}" method="POST">
                @csrf

                <!-- Nama Customer -->
                <div class="mb-3">
                    <label class="form-label">Nama Customer</label>
                    <input type="text"
                           name="nama_customer"
                           class="form-control"
                           placeholder="Masukkan nama"
                           required>
                </div>

                <!-- Pilih Barber -->
                <div class="mb-3">
                    <label class="form-label">Pilih Barber</label>
                    <select name="barber_id" class="form-select" required>
                        <option value="">-- Pilih Barber --</option>
                        @foreach($barbers as $barber)
                            <option value="{{ $barber->id }}">
                                {{ $barber->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Pilih Service -->
                <div class="mb-3">
                    <label class="form-label">Pilih Layanan</label>
                    <select name="service_id" class="form-select" required>
                        <option value="">-- Pilih Layanan --</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}">
                                {{ $service->name }} - Rp {{ number_format($service->price) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Waktu -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Waktu Mulai</label>
                        <input type="datetime-local"
                               name="start_time"
                               id="start_time"
                               class="form-control"
                               required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Waktu Selesai</label>
                        <input type="datetime-local"
                               name="end_time"
                               id="end_time"
                               class="form-control"
                               readonly>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 mt-3">
                    Booking Sekarang
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('start_time').addEventListener('change', function () {
    let start = new Date(this.value);

    if (!isNaN(start.getTime())) {
        start.setMinutes(start.getMinutes() + 45);

        let year = start.getFullYear();
        let month = String(start.getMonth() + 1).padStart(2, '0');
        let day = String(start.getDate()).padStart(2, '0');
        let hour = String(start.getHours()).padStart(2, '0');
        let minute = String(start.getMinutes()).padStart(2, '0');

        document.getElementById('end_time').value =
            `${year}-${month}-${day}T${hour}:${minute}`;
    }
});
</script>

</body>
</html>
