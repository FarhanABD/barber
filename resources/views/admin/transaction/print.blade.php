<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Print</title>
</head>
<body>

<pre id="print-area">
      BARBERSHOP ANTARSUKHA
================================

No  : {{ $transaction->transaction_code }}
Cust : {{ $transaction->customer_name }}

--------------------------------

@foreach($transaction->items as $item)
{{ $item->service->name }}
Rp {{ number_format($item->price,0,',','.') }}
@endforeach

--------------------------------
Diskon : {{ $transaction->diskon ?? 0 }} %
TOTAL   : Rp {{ number_format($transaction->total_price,0,',','.') }}

================================
    TERIMA KASIH :)
</pre>


<script>
window.onload = function() {

    let text = document.getElementById("print-area").innerText;

    let intent =
        "intent://print/#Intent;" +
        "scheme=rawbt;" +
        "package=ru.a402d.rawbtprinter;" +
        "S.text=" + encodeURIComponent(text) +
        ";end";

    window.location.href = intent;
}
</script>

</body>
</html>
