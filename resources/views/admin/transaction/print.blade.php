<!DOCTYPE html>
<html>
<head>
    <title>Resi Transaksi</title>
    <style>
        /* SET UKURAN KERTAS SAAT PRINT */
        @media print {
            @page {
                size: 80mm auto; /* 80mm thermal */
                margin: 0;
            }

            body {
                margin: 0;
            }
        }

        body {
            font-family: monospace;
            font-size: 12px;
            width: 80mm;
            padding: 5mm;
        }

        hr {
            border: none;
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        .center {
            text-align: center;
        }
    </style>
</head>

<body onload="window.print();">

    <div class="center">
          <strong style="font-size:15px;">BARBERSHOP ANTARSUKHA</strong><br>
            Jl. Contoh Alamat No. 123<br>
            Telp: 08xxxxxxxx
            <strong>======================================</strong>
    </div>

    <p>
        ID : {{ $transaction->transaction_code }}<br>
        Customer : {{ $transaction->customer_name }}<br>
        Barber : {{ $transaction->barber->name }}
    </p>

    <hr>

    @foreach($transaction->items as $item)
        {{ $item->service->name }}<br>
        Rp {{ number_format($item->price, 0, ',', '.') }}
        <br>
    @endforeach

    <hr>

    <strong>
        Diskon : {{ number_format($transaction->diskon, 0, ',', '.') }} %
    </strong>
    <br>
    <strong>
        Total : Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
    </strong>
    <br>
    ===============================================
    <div class="center">
        <br>Terima kasih 🙏
    </div>

    <script>
window.onafterprint = function () {
    window.close();
}
</script>

</body>
</html>
