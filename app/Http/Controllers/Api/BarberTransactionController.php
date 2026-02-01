<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BarberTransactionController extends Controller
{
  

public function store(Request $request)
{
    $request->validate([
        'customer_name' => 'nullable',
        'barber_id' => 'required',
        'diskon' => 'nullable|integer',
        'metode_pembayaran' => 'required',
        'items' => 'required|array'
    ]);

    DB::beginTransaction();

    try {

        $today = now()->format('Ymd');

        // ambil transaksi terakhir hari ini
        $last = Transaction::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        $lastNumber = 1;

        if ($last) {
            $lastNumber = intval(substr($last->transaction_code, -4)) + 1;
        }

        $transactionCode = 'TRX-' . $today . '-' . str_pad($lastNumber, 4, '0', STR_PAD_LEFT);

        $noAntrian = Transaction::whereDate('created_at', today())->count() + 1;

        // hitung total
        $total = collect($request->items)->sum('price');

        $diskon = $request->diskon ?? 0;

        if ($diskon > 0) {
            $total = $total - ($total * $diskon / 100);
        }

        $transaction = Transaction::create([
            'transaction_code' => $transactionCode,
            'no_antrian' => $noAntrian,
            'customer_name' => $request->customer_name,
            'barber_id' => $request->barber_id,
            'diskon' => $diskon,
            'total_price' => $total,
            'metode_pembayaran' => $request->metode_pembayaran,
        ]);

        foreach ($request->items as $item) {
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'service_id' => $item['service_id'],
                'price' => $item['price']
            ]);
        }

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Transaksi barber berhasil',
            'data' => $transaction->load('items.service')
        ]);

    } catch (\Exception $e) {

        DB::rollback();

        return response()->json([
            'status' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

}