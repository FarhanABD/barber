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
            'customer_name'     => 'nullable',
            'barber_id'          => 'required',
            'diskon'             => 'nullable|integer',
            'metode_pembayaran' => 'nullable',
            'items'              => 'nullable|array',
            'services'           => 'nullable|array',
        ]);

        DB::beginTransaction();

        try {
            $items = $request->items ?? $request->services ?? [];

            $today = now()->format('Ymd');

            // ambil transaksi terakhir hari ini
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

            // hitung total
            $total = $request->total ?? $request->total_price ?? collect($items)->sum('price');

            $diskon = $request->diskon ?? 0;

            if ($diskon > 0 && !$request->has('total')) {
                $total = $total - ($total * $diskon / 100);
            }

            $transaction = Transaction::create([
                'transaction_code' => $transactionCode,
                'no_antrian'       => $noAntrian,
                'customer_name'    => $request->customer_name,
                'barber_id'        => $request->barber_id,
                'diskon'           => $diskon,
                'total_price'      => $total,
                'metode_pembayaran'=> $request->metode_pembayaran ?? $request->payment,
                'nama_kasir'       => $request->nama_kasir ?? $request->kasir,
            ]);

            foreach ($items as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'service_id'     => $item['service_id'],
                    'price'          => $item['price']
                ]);
            }

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Transaksi barber berhasil',
                'data'    => $transaction->load('items.service')
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function index(Request $request)
    {
        $query = Transaction::with(['barber', 'items.service']);

        $date = $request->date ?? $request->tanggal;
        if ($date) {
            $query->whereDate('created_at', $date);
        }

        if ($request->filled('kasir')) {
            $query->where('nama_kasir', $request->kasir);
        }

        $transactions = $query->latest()->get();

        return response()->json([
            'success' => true,
            'status'  => true,
            'data'    => $transactions
        ]);
    }
}