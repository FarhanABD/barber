<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Exports\TransactionsExport;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
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

    public function create()
    {
        $barbers  = Barber::all();
        $services = Service::all();

        return view('transactions.create', compact('barbers', 'services'));
    }

    
  public function store(Request $request)
{
    $request->validate([
        'customer_name' => 'required|string|max:255',
        'barber_id'     => 'required|exists:barbers,id',
        'services'      => 'required|array',
        'services.*'    => 'exists:services,id',
        'diskon'        => 'nullable|integer|min:0|max:100',
        'booking_id'    => 'nullable|exists:bookings,id',
    ]);


    $transaction = null;

        DB::transaction(function () use ($request, &$transaction) {

        $noAntrian = $this->generateNoAntrian();

        $transaction = Transaction::create([
            'transaction_code' => 'TRX-' . now()->format('Ymd') . '-' . str_pad(
                Transaction::whereDate('created_at', now())->count() + 1,
                4,
                '0',
                STR_PAD_LEFT
            ),
            'no_antrian'    => $noAntrian,
            'customer_name'=> $request->customer_name,
            'barber_id'    => $request->barber_id,
            'diskon'       => $request->diskon,
            'total_price'  => 0,
            'booking_id'   => $request->booking_id, // 🔥
        ]);

        $totalAwal = 0;

        foreach ($request->services as $serviceId) {
            $service = Service::findOrFail($serviceId);

            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'service_id'     => $service->id,
                'price'          => $service->price,
            ]);

            $totalAwal += $service->price;
        }

        $diskon = ($request->diskon / 100) * $totalAwal;

        $transaction->update([
            'total_price' => $totalAwal - $diskon
        ]);

        // 🔥 UPDATE STATUS BOOKING
        if ($request->booking_id) {
            Booking::where('id', $request->booking_id)
                ->where('status', 'confirmed')
                ->update([
                    'status' => 'completed'
                ]);
        }
    });

    return redirect()
        ->route('admin.transactions.index')
        ->with('print_transaction_id', $transaction->id)
        ->with('success', 'Transaksi berhasil disimpan');
}



    /**
     * Display the specified resource.
     */
   public function show(Transaction $transaction)
{
    $transaction->load(['barber', 'items.service']);

    return view('admin.transaction.show', compact('transaction'));
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
     
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
       
    }

    public function destroy($id)
    {
        Transaction::findOrFail($id)->delete();

        return redirect()
            ->route('admin.transactions.index')
            ->with('success', 'Transaksi berhasil dihapus');
    }

    public function export(Request $request)
{
    return Excel::download(
        new TransactionsExport($request),
        'data-transaksi.xlsx'
    );
}

public function downloadPdf(Transaction $transaction)
{
    $transaction->load(['barber', 'items.service']);

    $pdf = Pdf::loadView(
        'admin.transaction.pdf',
        compact('transaction')
    );

    return $pdf->download(
        'resi-' . $transaction->transaction_code . '.pdf'
    );
}

public function print(Transaction $transaction)
{
    $transaction->load(['barber', 'items.service']);
    return view('admin.transaction.print', compact('transaction'));
}

private function generateNoAntrian()
{
    $today = now()->toDateString();

    $lastAntrian = Transaction::whereDate('created_at', $today)
        ->max('no_antrian');

    return $lastAntrian ? $lastAntrian + 1 : 1;
}


}