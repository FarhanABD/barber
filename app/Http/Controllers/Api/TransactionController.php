<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\TransactionAngkringan;
use App\Models\TransactionItemAngkringan;
use DB;

class TransactionController extends Controller
{

/* ================= BARBER ================= */

public function storeBarber(Request $request)
{
    DB::beginTransaction();

    try {

        $trx = Transaction::create([
            'transaction_code' => 'TRX-' . time(),
            'no_antrian' => rand(1,999),
            'customer_name' => $request->customer_name,
            'barber_id' => $request->barber_id,
            'diskon' => $request->diskon,
            'total_price' => $request->total,
            'metode_pembayaran' => $request->payment,
        ]);

        foreach ($request->services as $item) {
            TransactionItem::create([
                'transaction_id' => $trx->id,
                'service_id' => $item['service_id'],
                'price' => $item['price'],
            ]);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Transaksi barber berhasil'
        ]);

    } catch (\Exception $e) {

        DB::rollback();

        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}

/* ================= ANGKRINGAN ================= */

public function storeAngkringan(Request $request)
{
    $request->validate([
        'payment' => 'required|in:cash,qris,transfer',
        'bayar'   => 'nullable|required_if:payment,cash|integer|min:0',
        'kasir'   => 'required|string',
        'items'   => 'required|array|min:1',
        'items.*.menu_id' => 'required|exists:menus,id_menu',
        'items.*.qty'     => 'required|integer|min:1',
    ]);

    DB::beginTransaction();

    try {

        // 🔹 HITUNG TOTAL DARI DB
        $total = 0;
        foreach ($request->items as $item) {
            $menu = \App\Models\Menu::findOrFail($item['menu_id']);
            $total += $menu->harga * $item['qty'];
        }

        $bayar     = $request->bayar ?? 0;
        $kembalian = $request->payment === 'cash'
            ? $bayar - $total
            : 0;

        // 🔹 SIMPAN TRANSAKSI (TANPA mitra)
        $trx = TransactionAngkringan::create([
            'kode_transaksi'    => 'ANG-' . now()->format('YmdHis'),
            'tanggal'           => now(),
            'total'             => $total,
            'metode_pembayaran' => $request->payment,
            'jumlah_bayar'      => $bayar,
            'kembalian'         => $kembalian,
            'status'            => 'paid',
            'nama_kasir'        => $request->kasir,
        ]);

        // 🔹 SIMPAN ITEM + MITRA
        foreach ($request->items as $item) {
            $menu = \App\Models\Menu::findOrFail($item['menu_id']);

            TransactionItemAngkringan::create([
                'transaction_id' => $trx->id_transaction,
                'menu_id'        => $menu->id_menu,
                'mitra_id'       => $menu->mitra_id,
                'harga'          => $menu->harga,
                'qty'            => $item['qty'],
                'subtotal'       => $menu->harga * $item['qty'],
            ]);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Transaksi angkringan berhasil',
            'kode'    => $trx->kode_transaksi
        ]);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}


}