<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: monospace; }
    </style>
</head>
<body onload="window.print()">




<h3>ANGKRINGAN ANTARSUKHA</h3>
<hr>

Kode : {{ $transaction->kode_transaksi }}<br>
Tanggal : {{ $transaction->tanggal->format('d/m/Y H:i') }}<br>
Metode Pembayaran : {{ strtoupper($transaction->metode_pembayaran) }}

<hr>

@foreach($transaction->items as $item)
{{ $item->menu->nama }} <br>
{{ $item->qty }} x {{ number_format($item->harga) }}
= {{ number_format($item->subtotal) }}
<hr>
@endforeach

TOTAL : Rp {{ number_format($transaction->total) }}

@if($transaction->metode_pembayaran === 'cash')
BAYAR : Rp {{ number_format($transaction->jumlah_bayar) }}  
KEMBALI : Rp {{ number_format($transaction->kembalian) }}
@endif

<body onload="printPage()">

<script>
function printPage() {
    window.print();
    setTimeout(() => window.close(), 500);
}
</script>
<hr style="border-top:2px dashed;">
@include('admin.transaction-angkringan.partials.resi')

</body>
</html>
