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
        $items = $request->items ?? $request->services ?? [];

        $today = now()->format('Ymd');
        $last = Transaction::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();
        $lastNumber = 1;
        if ($last && str_contains($last->transaction_code, '-')) {
            $parts = explode('-', $last->transaction_code);
            $lastNumber = intval(end($parts)) + 1;
        }
        $transactionCode = 'TRX-' . $today . '-' . str_pad($lastNumber, 4, '0', STR_PAD_LEFT);
        $noAntrian = Transaction::whereDate('created_at', today())->count() + 1;

        $trx = Transaction::create([
            'transaction_code' => $transactionCode,
            'no_antrian'       => $noAntrian,
            'customer_name'    => $request->customer_name,
            'barber_id'        => $request->barber_id,
            'diskon'           => $request->diskon ?? 0,
            'total_price'      => $request->total ?? $request->total_price ?? 0,
            'metode_pembayaran'=> $request->metode_pembayaran ?? $request->payment,
            'nama_kasir'       => $request->nama_kasir ?? $request->kasir,
        ]);

        foreach ($items as $item) {
            TransactionItem::create([
                'transaction_id' => $trx->id,
                'service_id'     => $item['service_id'],
                'price'          => $item['price'],
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
            'error'   => $e->getMessage()
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

        $total = 0;
        foreach ($request->items as $item) {
            $menu = \App\Models\Menu::findOrFail($item['menu_id']);
            $total += $menu->harga * $item['qty'];
        }

        $bayar     = $request->bayar ?? 0;
        $kembalian = $request->payment === 'cash'
            ? $bayar - $total
            : 0;

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
            'error'   => $e->getMessage()
        ], 500);
    }
}

    public function index(Request $request)
    {
        $date = $request->date ?? today()->toDateString();

        $barberQuery = Transaction::query();
        $angkringanQuery = TransactionAngkringan::query();

        if ($date) {
            $barberQuery->whereDate('created_at', $date);
            $angkringanQuery->whereDate('tanggal', $date);
        }

        $totalBarber = $barberQuery->sum('total_price');
        $totalAngkringan = $angkringanQuery->sum('total');
        $grandTotal = $totalBarber + $totalAngkringan;

        $barberTrxCount = $barberQuery->count();
        $angkringanTrxCount = $angkringanQuery->count();
        $totalTrxCount = $barberTrxCount + $angkringanTrxCount;

        return response()->json([
            'success' => true,
            'status' => true,
            'data' => [
                'total_barber' => $totalBarber,
                'total_angkringan' => $totalAngkringan,
                'grand_total' => $grandTotal,
                'barber_count' => $barberTrxCount,
                'angkringan_count' => $angkringanTrxCount,
                'total_count' => $totalTrxCount,
            ]
        ]);
    }

}