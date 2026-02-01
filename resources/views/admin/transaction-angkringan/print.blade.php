<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Print Resi</title>

<style>
body {
    font-family: monospace;
    font-size: 12px;
}
hr {
    border-top: 1px dashed black;
}
</style>

</head>

<body onload="printPage()">

<h3>ANGKRINGAN ANTARSUKHA</h3>
<hr>

Kode : {{ $transaction->kode_transaksi }}<br>
Tanggal : {{ $transaction->tanggal->format('d/m/Y H:i') }}<br>
Metode : {{ strtoupper($transaction->metode_pembayaran) }}

<hr>

@foreach($transaction->items as $item)
{{ $item->menu->nama }}<br>
{{ $item->qty }} x {{ number_format($item->harga) }}
= {{ number_format($item->subtotal) }}
<hr>
@endforeach

TOTAL : Rp {{ number_format($transaction->total) }}<br>

@if($transaction->metode_pembayaran === 'cash')
BAYAR : Rp {{ number_format($transaction->jumlah_bayar) }}<br>
KEMBALI : Rp {{ number_format($transaction->kembalian) }}<br>
@endif

<hr>

@include('admin.transaction-angkringan.partials.resi')

<script>
function printPage() {

    window.print();

    // Auto close supaya kembali ke POS
    // setTimeout(() => {
    //     window.close();
    // }, 500);

}
</script>

</body>
</html>
