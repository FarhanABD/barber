<h3>RESI DAPUR ANGKRINGAN ANTARSUKHA</h3>
<hr>

Kode : {{ $transaction->kode_transaksi }}<br>
Tanggal : {{ $transaction->tanggal->format('d/m/Y H:i') }}<br>
Metode : {{ strtoupper($transaction->metode_pembayaran) }}
<hr>

@foreach($transaction->items as $item)
{{ $item->menu->nama }} <br>
{{ $item->qty }} x {{ number_format($item->harga) }}
= {{ number_format($item->subtotal) }}
<hr>
@endforeach

TOTAL : Rp {{ number_format($transaction->total) }}
