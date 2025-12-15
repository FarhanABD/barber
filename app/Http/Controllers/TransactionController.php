<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use App\Models\Service;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB;
use App\Exports\TransactionsExport;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
{
    $query = Transaction::with('barber');

    // 🔍 SEARCH
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('transaction_code', 'like', "%$search%")
              ->orWhere('customer_name', 'like', "%$search%")
              ->orWhereHas('barber', function ($b) use ($search) {
                  $b->where('name', 'like', "%$search%");
              });
        });
    }

    // 📅 FILTER TANGGAL
    if ($request->filled('date')) {
        $query->whereDate('created_at', $request->date);
    }

    $transactions = $query
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view('admin.transaction.index', compact('transactions'));
}



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barbers  = Barber::all();
        $services = Service::all();

        return view('transactions.create', compact('barbers', 'services'));
    }

    /**
     * Store a newly created resource in storage.
     */
  public function store(Request $request)
{
    $request->validate([
        'customer_name' => 'required|string|max:255',
        'barber_id'     => 'required|exists:barbers,id',
        'services'      => 'required|array',
        'services.*'    => 'exists:services,id',
        'diskon'        => 'nullable|integer|min:0|max:100',
    ]);

    DB::transaction(function () use ($request) {

        // Generate kode transaksi
        $transactionCode = 'TRX-' . date('Ymd') . '-' . str_pad(
            Transaction::whereDate('created_at', now())->count() + 1,
            4,
            '0',
            STR_PAD_LEFT
        );

        // Simpan transaksi (total sementara 0)
        $transaction = Transaction::create([
            'transaction_code' => $transactionCode,
            'customer_name'    => $request->customer_name,
            'barber_id'        => $request->barber_id,
            'diskon'           => $request->diskon, // persen
            'total_price'      => 0,
        ]);

        $totalAwal = 0;

        // Simpan item layanan
        foreach ($request->services as $serviceId) {
            $service = Service::findOrFail($serviceId);

            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'service_id'     => $service->id,
                'price'          => $service->price,
            ]);

            $totalAwal += $service->price;
        }

        // Hitung diskon
        $diskonPersen = $request->diskon;
        $nilaiDiskon  = ($diskonPersen / 100) * $totalAwal;
        $totalAkhir   = $totalAwal - $nilaiDiskon;

        // Update total harga setelah diskon
        $transaction->update([
            'total_price' => $totalAkhir
        ]);
    });

    return redirect()
        ->route('admin.transactions.index')
        ->with('success', 'Transaksi berhasil disimpan');
}


    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load(['barber', 'items.service']);

        return view('transactions.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        $barbers  = Barber::all();
        $services = Service::all();

        $transaction->load('items');

        return view('transactions.edit', compact('transaction', 'barbers', 'services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'customer_name' => 'required',
            'barber_id'     => 'required|exists:barbers,id',
        ]);

        $transaction->update([
            'customer_name' => $request->customer_name,
            'barber_id'     => $request->barber_id,
        ]);

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaksi berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaksi berhasil dihapus');
    }

    public function export(Request $request)
{
    return Excel::download(
        new TransactionsExport($request),
        'data-transaksi.xlsx'
    );
}
}