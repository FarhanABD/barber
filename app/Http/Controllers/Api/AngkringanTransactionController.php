<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransactionAngkringan;
use App\Models\TransactionItemAngkringan;
use Illuminate\Support\Facades\DB;

class AngkringanTransactionController extends Controller
{
    public function store(Request $request)
{
    DB::beginTransaction();

    try {

        $today = now()->format('Ymd');

        $last = TransactionAngkringan::whereDate('created_at', today())
            ->orderBy('id_transaction', 'desc')
            ->first();

        $lastNumber = 1;

        if ($last) {
            $lastNumber = intval(substr($last->kode_transaksi, -4)) + 1;
        }

        $kode = 'ANG-' . $today . '-' . str_pad($lastNumber, 4, '0', STR_PAD_LEFT);

        $trx = TransactionAngkringan::create([
            'kode_transaksi' => $kode,
            'tanggal' => now(),
            'total' => $request->total,
            'metode_pembayaran' => $request->metode_pembayaran,
            'jumlah_bayar' => $request->jumlah_bayar,
            'kembalian' => $request->kembalian,
            'status' => 'paid',
            'nama_kasir' => $request->nama_kasir,
            'mitra_id' => $request->mitra_id
        ]);

        foreach ($request->items as $item) {
            TransactionItemAngkringan::create([
                'transaction_id' => $trx->id_transaction,
                'menu_id' => $item['menu_id'],
                'harga' => $item['harga'],
                'qty' => $item['qty'],
                'subtotal' => $item['subtotal']
            ]);
        }

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Transaksi angkringan berhasil',
            'data' => $trx->load('items.menu')
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