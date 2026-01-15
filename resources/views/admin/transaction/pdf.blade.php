<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Resi Transaksi</title>

    <style>
@media print {
    @page {
        size: 80mm auto;
        margin: 0;
    }

    html, body {
        width: 80mm;
        margin: 0;
        padding: 0;
    }
}

body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 11px;
    line-height: 1.4;
}

table {
    width: 100%;
    border-collapse: collapse;
}

td, th {
    padding: 3px 2px;
    font-size: 11px;
}

.center { text-align: center; }
.right { text-align: right; }

.divider td {
    border-top: 1px dashed #000;
    padding: 6px 0;
}

.items th {
    border-bottom: 1px solid #000;
}

.items td {
    border-bottom: 1px dashed #999;
}

.total td {
    font-weight: bold;
    font-size: 12px;
}
</style>

</head>
<body onload="window.print(); window.close();">

<table>

    <!-- HEADER -->
    <tr>
        <td class="center" colspan="3">
            <strong style="font-size:15px;">BARBERSHOP ANTARSUKHA</strong><br>
            Jl. Contoh Alamat No. 123<br>
            Telp: 08xxxxxxxx
        </td>
    </tr>

    <tr class="divider"><td colspan="3"></td></tr>

    <!-- INFO -->
    <tr>
        <td colspan="3">
            ID Transaksi : {{ $transaction->transaction_code }}<br>
            Customer : {{ $transaction->customer_name }}<br>
            Barber : {{ $transaction->barber->name ?? '-' }}<br>
            Tanggal : {{ $transaction->created_at->format('d M Y H:i') }}
        </td>
    </tr>

    <tr class="divider"><td colspan="3"></td></tr>

    <!-- ITEMS HEADER -->
    <tr class="items">
        <th width="10%">No</th>
        <th width="60%">Layanan</th>
        <th width="30%" class="right">Harga</th>
    </tr>

    <!-- ITEMS -->
    @php $subtotal = 0; @endphp
    @foreach ($transaction->items as $item)
        @php $subtotal += $item->price; @endphp
        <tr class="items">
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->service->name }}</td>
            <td class="right">
                Rp {{ number_format($item->price,0,',','.') }}
            </td>
        </tr>
    @endforeach

    <!-- SUMMARY -->
    <tr>
        <td colspan="2" class="right">Subtotal</td>
        <td class="right">Rp {{ number_format($subtotal,0,',','.') }}</td>
    </tr>
    <tr>
        <td colspan="2" class="right">Diskon ({{ $transaction->diskon ?? 0 }}%)</td>
        <td class="right">
            Rp {{ number_format(($transaction->diskon/100)*$subtotal,0,',','.') }}
        </td>
    </tr>
    <tr class="total">
        <td colspan="2" class="right">Total Bayar</td>
        <td class="right">
            Rp {{ number_format($transaction->total_price,0,',','.') }}
        </td>
    </tr>

    <tr class="divider"><td colspan="3"></td></tr>

    <!-- FOOTER -->
    <tr>
        <td colspan="3" class="center">
            Terima kasih telah mempercayakan penampilan Anda kepada kami ✂️<br>
            ~ Barbershop Kita ~
        </td>
    </tr>

</table>

</body>

</html>
