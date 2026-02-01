<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\TransactionAngkringan;
use App\Models\TransactionItemAngkringan;
use Illuminate\Http\Request;
use App\Exports\TransactionAngkringanExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class TransactionAngkringanController extends Controller
{
    /**
     * List transaksi
     */
    public function index(Request $request)
{
    $query = TransactionAngkringan::query();

    // Filter tanggal
    if ($request->filled('date')) {
        $query->whereDate('tanggal', $request->date);
    }

    // Filter kasir
    if ($request->filled('kasir')) {
        $query->where('nama_kasir', 'like', '%' . $request->kasir . '%');
    }

    // Filter metode pembayaran
    if ($request->filled('metode')) {
        $query->where('metode_pembayaran', $request->metode);
    }

    // Filter kode transaksi
    if ($request->filled('search')) {
        $query->where('kode_transaksi', 'like', '%' . $request->search . '%');
    }

    $transactions = $query
        ->orderBy('tanggal', 'desc')
        ->get();

    return view(
        'admin.transaction-angkringan.index',
        compact('transactions')
    );
}



    /**
     * Form transaksi (POS)
     */
    public function create()
    {
        $menus = Menu::where('status', true)->get();

        return view('frontend-angkringan.transactions.create', compact('menus'));
    }

    /**
     * Simpan transaksi + item
     */
    public function store(Request $request)
    {
     $request->validate([
    'items'              => 'required|array',
    'items.*.menu_id'    => 'required|exists:menus,id_menu',
    'items.*.qty'        => 'required|integer|min:1',
    'metode_pembayaran'  => 'required|in:cash,qris,transfer',
    'nama_kasir' => 'required|string|max:100',
    'jumlah_bayar'       => 'nullable|required_if:metode_pembayaran,cash|numeric|min:0',
]);


        DB::beginTransaction();

        try {
            // Generate kode transaksi
            $kode = 'TRX-' . now()->format('YmdHis');

            // Hitung total
            $total = 0;
            foreach ($request->items as $item) {
                $menu = Menu::findOrFail($item['menu_id']);
                $total += $menu->harga * $item['qty'];
            }

            $jumlahBayar = null;
            $kembalian   = null;

            if ($request->metode_pembayaran === 'cash') {
                $jumlahBayar = $request->jumlah_bayar;
                $kembalian   = $jumlahBayar - $total;
            }

            $firstMenu = Menu::findOrFail($request->items[0]['menu_id']);

            $transaction = TransactionAngkringan::create([
                'kode_transaksi'    => $kode,
                'tanggal'           => now(),
                'total'             => $total,
                'metode_pembayaran' => $request->metode_pembayaran,
                'jumlah_bayar'      => $jumlahBayar,
                'kembalian'         => $kembalian,
                'nama_kasir'        => $request->nama_kasir,
                'status'            => 'paid',
                'mitra_id'          => $firstMenu->mitra_id,
            ]);



            // Simpan item transaksi
            foreach ($request->items as $item) {
                $menu = Menu::findOrFail($item['menu_id']);
                $subtotal = $menu->harga * $item['qty'];

                TransactionItemAngkringan::create([
                    'transaction_id' => $transaction->id_transaction,
                    'menu_id'        => $menu->id_menu,
                    'harga'          => $menu->harga,
                    'qty'            => $item['qty'],
                    'subtotal'       => $subtotal,
                ]);
            }

            DB::commit();

          return redirect()
            ->route('admin.transaction-angkringan.index')
            ->with('print_transaction_id', $transaction->id_transaction)
            ->with('success', 'Transaksi berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Detail transaksi
     */
    public function show($id)
    {
        $transaction = TransactionAngkringan::with('items.menu')
            ->findOrFail($id);

        return view('admin.transaction-angkringan.show', compact('transaction'));
    }

    
public function export(Request $request)
{
    return Excel::download(
        new TransactionAngkringanExport($request),
        'transaksi-angkringan.xlsx'
    );
}

    public function destroy($id)
    {
        TransactionAngkringan::findOrFail($id)->delete();

        return redirect()
            ->route('admin.transaction-angkringan.index')
            ->with('success', 'Transaksi berhasil dihapus');
    }

    public function print($id)
{
    $transaction = TransactionAngkringan::with('items.menu')
        ->findOrFail($id);

    return view('admin.transaction-angkringan.print', compact('transaction'));
}
public function printData($id)
{
    $trx = TransactionAngkringan::with('items.menu')->findOrFail($id);

    return response()->json([
        'kode' => $trx->kode_transaksi,
        'kasir' => $trx->nama_kasir,
        'tanggal' => $trx->created_at->format('d-m-Y H:i'),
        'total' => number_format($trx->total,0,',','.'),
        'items' => $trx->items->map(function($i){
            return [
                'nama'=>$i->menu->nama,
                'qty'=>$i->qty,
                'harga'=>number_format($i->harga,0,',','.'),
                'subtotal'=>number_format($i->subtotal,0,',','.')
            ];
        })
    ]);
}




}