<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Barber;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index()
{
    $bookings = Booking::with(['barber', 'service'])
        ->where('status', 'pending')                     // 🔹 belum dikonfirmasi
        ->orderBy('start_time', 'asc')                   // 🔹 urut jam
        ->get();

    return view('admin.bookings.index', compact('bookings'));
}


    public function create()
    {
        return view('frontend.booking', [
            'barbers'  => Barber::all(),
            'services' => Service::all(),
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'nama_customer' => 'required|string|max:100',
            'barber_id'     => 'required|exists:barbers,id',
            'service_id'    => 'required|exists:services,id',
            'start_time'    => 'required|date',
            'end_time'      => 'required|date|after:start_time',
        ]);

        // VALIDASI BENTROK JAM
        $isBentrok = Booking::where('barber_id', $request->barber_id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($query) use ($request) {
                $query->where('start_time', '<', $request->end_time)
                    ->where('end_time', '>', $request->start_time);
            })
            ->exists();

        if ($isBentrok) {
            return back()
                ->withInput()
                ->withErrors([
                    'start_time' => 'Jadwal barber bentrok, silakan pilih jam lain'
                ]);
        }

        $service = Service::findOrFail($request->service_id);
        $barber  = Barber::findOrFail($request->barber_id);

        // nomor antrian harian
        $noAntrian = $this->generateNoAntrian();

        $booking = Booking::create([
            'no_antrian'    => $noAntrian,
            'nama_customer' => $request->nama_customer,
            'barber_id'     => $request->barber_id,
            'service_id'    => $request->service_id,
            'start_time'    => $request->start_time,
            'end_time'      => $request->end_time,
            'total_price'   => $service->price,
            'status'        => 'pending',
        ]);

        // ===============================
        // PESAN WHATSAPP
        // ===============================
        $pesan = "
Halo Admin Antarshuka Barbershop 👋

Saya ingin melakukan *booking potong rambut* dengan detail berikut:

━━━━━━━━━━━━━━━━
📌 *Detail Booking*
━━━━━━━━━━━━━━━━
👤 Nama Customer : {$booking->nama_customer}
🔢 Nomor Antrian  : {$booking->no_antrian}
💈 Barber         : {$barber->name}
✂️ Layanan       : {$service->name}
💰 Harga          : Rp " . number_format($service->price, 0, ',', '.') . "

━━━━━━━━━━━━━━━━
⏰ *Jadwal*
━━━━━━━━━━━━━━━━
📅 Tanggal : " . Carbon::parse($booking->start_time)->format('d M Y') . "
🕒 Waktu   : " . Carbon::parse($booking->start_time)->format('H:i') . " - " .
Carbon::parse($booking->end_time)->format('H:i') . "

Mohon konfirmasi ketersediaan jadwal tersebut.
Terima kasih 🙏
";

$waNumber = '6281353401336'; // WA Admin
$waUrl = 'https://wa.me/' . $waNumber . '?text=' . urlencode($pesan);

return redirect()->away($waUrl);
}


    public function confirm($id)
{
    $booking = Booking::findOrFail($id);

    // hanya bisa confirm dari status pending
    if ($booking->status !== 'pending') {
        return back()->withErrors('Booking tidak bisa dikonfirmasi');
    }

    $booking->update([
        'status' => 'confirmed'
    ]);

    return back()->with('success', 'Booking berhasil dikonfirmasi');
}

public function complete($id)
{
    DB::transaction(function () use ($id) {

        $booking = Booking::findOrFail($id);

        if ($booking->status !== 'confirmed') {
            abort(403, 'Booking belum dikonfirmasi');
        }

        $booking->update([
            'status' => 'completed'
        ]);

        $noAntrian = $this->generateNoAntrian();

        Transaction::create([
            'transaction_code' => 'TRX-' . now()->format('Ymd') . '-' . $booking->id,
            'no_antrian'       => $noAntrian,
            'customer_name'    => $booking->nama_customer,
            'diskon'           => 0,
            'barber_id'        => $booking->barber_id,
            'total_price'      => $booking->total_price,
            'created_at'       => now(),
        ]);
    });

    return back()->with('success', 'Booking selesai & masuk antrian');
}



public function cancel($id)
{
    $booking = Booking::findOrFail($id);

    // booking selesai tidak boleh dibatalkan
    if ($booking->status === 'completed') {
        return back()->withErrors('Booking yang sudah selesai tidak bisa dibatalkan');
    }

    $booking->update([
        'status' => 'canceled'
    ]);

    return back()->with('success', 'Booking berhasil dibatalkan');
}

private function generateNoAntrian()
{
    $today = now()->toDateString();

    $lastAntrian = Booking::whereDate('created_at', $today)
        ->max('no_antrian');

    return $lastAntrian ? $lastAntrian + 1 : 1;
}



}