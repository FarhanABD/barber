<?php

namespace App\Http\Controllers;

use App\Models\MasterKaryawan;
use App\Models\PengeluaranGaji;
use App\Models\Service;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PengeluaranGajiController extends Controller
{
    public function index(Request $request)
    {
        $query = PengeluaranGaji::with('karyawan');

        // Filter Divisi
        if ($request->filled('divisi') && $request->divisi !== 'all') {
            $query->whereHas('karyawan', function ($q) use ($request) {
                $q->where('divisi', $request->divisi);
            });
        }

        // Filter Status Pembayaran
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter Bulan / Periode
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_bayar', date('m', strtotime($request->bulan)))
                  ->whereYear('tanggal_bayar', date('Y', strtotime($request->bulan)));
        }

        // Filter Pencarian (Nama Karyawan / Kode Slip)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_slip', 'like', "%{$search}%")
                  ->orWhereHas('karyawan', function ($sub) use ($search) {
                      $sub->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        $gajis = $query->orderBy('tanggal_bayar', 'desc')->orderBy('id', 'desc')->get();

        // Statistik Ringkas
        $totalPengeluaran = PengeluaranGaji::where('status', 'dibayar')->sum('total_gaji');
        $totalJamKerja = PengeluaranGaji::sum('total_jam_kerja');
        $totalFeeTraining = PengeluaranGaji::sum('total_fee_training');
        $totalSlip = PengeluaranGaji::count();

        return view('admin.pengeluaran-gaji.index', compact(
            'gajis',
            'totalPengeluaran',
            'totalJamKerja',
            'totalFeeTraining',
            'totalSlip'
        ));
    }

    public function create()
    {
        $karyawans = MasterKaryawan::where('status', 'aktif')->orderBy('nama')->get();
        $barberServices = Service::orderBy('id')->get();
        return view('admin.pengeluaran-gaji.create', compact('karyawans', 'barberServices'));
    }

    public function store(Request $request)
    {
        $karyawan = MasterKaryawan::findOrFail($request->karyawan_id);
        $skemaGaji = ($karyawan->divisi === 'barber' || $request->skema_gaji === 'komisi_barber') ? 'komisi_barber' : 'jam_kerja';

        $rules = [
            'karyawan_id'             => 'required|exists:karyawans,id',
            'periode_mulai'           => 'nullable|date',
            'periode_selesai'         => 'nullable|date|after_or_equal:periode_mulai',
            'tanggal_bayar'           => 'required|date',
            'metode_pembayaran'       => 'required|in:cash,transfer',
            'status'                  => 'required|in:dibayar,pending',
            'bonus'                   => 'nullable|numeric|min:0',
            'potongan'                => 'nullable|numeric|min:0',
            'catatan'                 => 'nullable|string',
        ];

        if ($skemaGaji === 'komisi_barber') {
            $rules['rincian_layanan'] = 'nullable|array';
            $rules['persentase_komisi'] = 'nullable|numeric|min:0|max:100';
        } else {
            $rules['total_jam_kerja'] = 'required|numeric|min:0';
            $rules['tarif_per_jam']   = 'nullable|numeric|min:0';
            $rules['jam_training']    = 'nullable|numeric|min:0';
            $rules['tarif_training_per_8jam'] = 'nullable|numeric|min:0';
        }

        $request->validate($rules, [
            'karyawan_id.required'     => 'Pilih karyawan terlebih dahulu.',
            'tanggal_bayar.required'   => 'Tanggal pembayaran wajib diisi.',
            'total_jam_kerja.required' => 'Total jam kerja wajib diisi.',
        ]);

        $bonus = (float)($request->bonus ?? 0);
        $potongan = (float)($request->potongan ?? 0);

        if ($skemaGaji === 'komisi_barber') {
            $persentaseKomisi = $request->filled('persentase_komisi') ? (float)$request->persentase_komisi : 45.00;
            $rincianLayananRaw = $request->input('rincian_layanan', []);
            $rincianLayanan = [];
            $totalKepala = 0;
            $totalOmset = 0;
            $totalKomisi = 0;

            if (is_array($rincianLayananRaw)) {
                foreach ($rincianLayananRaw as $item) {
                    $namaLayanan = trim($item['nama_layanan'] ?? '');
                    $tipeHari = $item['tipe_hari'] ?? 'all';
                    $tarif = (float)($item['tarif'] ?? 0);
                    $jumlah = (int)($item['jumlah_kepala'] ?? 0);
                    $komisiPersen = isset($item['persentase_komisi']) && $item['persentase_komisi'] !== '' ? (float)$item['persentase_komisi'] : $persentaseKomisi;

                    if ($jumlah > 0 && !empty($namaLayanan)) {
                        $subtotalOmset = $tarif * $jumlah;
                        $subtotalKomisi = $subtotalOmset * ($komisiPersen / 100);

                        $rincianLayanan[] = [
                            'nama_layanan'      => $namaLayanan,
                            'tipe_hari'         => $tipeHari,
                            'tarif'             => $tarif,
                            'jumlah_kepala'     => $jumlah,
                            'persentase_komisi' => $komisiPersen,
                            'subtotal_omset'    => $subtotalOmset,
                            'subtotal_komisi'   => $subtotalKomisi,
                        ];

                        $totalKepala += $jumlah;
                        $totalOmset += $subtotalOmset;
                        $totalKomisi += $subtotalKomisi;
                    }
                }
            }

            $totalGaji = max(0, ($totalKomisi + $bonus - $potongan));
            $totalJamKerja = 0;
            $tarifPerJam = 0;
            $totalUpahJam = 0;
            $jamTraining = 0;
            $tarifTrainingPer8Jam = 0;
            $totalFeeTraining = 0;
        } else {
            $totalJamKerja = (float)$request->total_jam_kerja;
            $tarifPerJam = $request->filled('tarif_per_jam') ? (float)$request->tarif_per_jam : 5000;
            $totalUpahJam = $totalJamKerja * $tarifPerJam;

            $jamTraining = (float)($request->jam_training ?? 0);
            $tarifTrainingPer8Jam = $request->filled('tarif_training_per_8jam') ? (float)$request->tarif_training_per_8jam : 20000;
            $totalFeeTraining = ($jamTraining / 8) * $tarifTrainingPer8Jam;

            $totalGaji = max(0, ($totalUpahJam + $totalFeeTraining + $bonus - $potongan));
            $totalKepala = 0;
            $totalOmset = 0;
            $persentaseKomisi = 0;
            $totalKomisi = 0;
            $rincianLayanan = null;
        }

        // Generate Kode Slip Unik
        $prefix = 'PAY-' . date('Ym') . '-';
        $count = PengeluaranGaji::whereYear('created_at', date('Y'))
                                ->whereMonth('created_at', date('m'))
                                ->count() + 1;
        $kodeSlip = $prefix . str_pad($count, 4, '0', STR_PAD_LEFT);

        while (PengeluaranGaji::where('kode_slip', $kodeSlip)->exists()) {
            $count++;
            $kodeSlip = $prefix . str_pad($count, 4, '0', STR_PAD_LEFT);
        }

        PengeluaranGaji::create([
            'kode_slip'               => $kodeSlip,
            'karyawan_id'             => $request->karyawan_id,
            'skema_gaji'              => $skemaGaji,
            'periode_mulai'           => $request->periode_mulai,
            'periode_selesai'         => $request->periode_selesai,
            'tanggal_bayar'           => $request->tanggal_bayar,
            'total_jam_kerja'         => $totalJamKerja,
            'tarif_per_jam'           => $tarifPerJam,
            'total_upah_jam'          => $totalUpahJam,
            'total_kepala'            => $totalKepala,
            'total_omset'             => $totalOmset,
            'persentase_komisi'       => $persentaseKomisi,
            'total_komisi'            => $totalKomisi,
            'rincian_layanan'         => $rincianLayanan,
            'jam_training'            => $jamTraining,
            'tarif_training_per_8jam' => $tarifTrainingPer8Jam,
            'total_fee_training'      => $totalFeeTraining,
            'bonus'                   => $bonus,
            'potongan'                => $potongan,
            'total_gaji'              => $totalGaji,
            'metode_pembayaran'       => $request->metode_pembayaran,
            'status'                  => $request->status,
            'catatan'                 => $request->catatan,
        ]);

        return redirect()->route('admin.pengeluaran-gaji.index')
            ->with('success', "Slip gaji {$kodeSlip} berhasil dibuat!");
    }

    public function show($id)
    {
        $gaji = PengeluaranGaji::with('karyawan')->findOrFail($id);
        return view('admin.pengeluaran-gaji.show', compact('gaji'));
    }

    public function print($id)
    {
        $gaji = PengeluaranGaji::with('karyawan')->findOrFail($id);
        return view('admin.pengeluaran-gaji.print', compact('gaji'));
    }

    public function downloadPdf($id)
    {
        $gaji = PengeluaranGaji::with('karyawan')->findOrFail($id);
        $pdf = Pdf::loadView('admin.pengeluaran-gaji.pdf', compact('gaji'))
                  ->setPaper('a4', 'portrait');
        return $pdf->stream('slip-gaji-' . $gaji->kode_slip . '.pdf');
    }

    public function edit($id)
    {
        $gaji = PengeluaranGaji::findOrFail($id);
        $karyawans = MasterKaryawan::orderBy('nama')->get();
        $barberServices = Service::orderBy('id')->get();
        return view('admin.pengeluaran-gaji.edit', compact('gaji', 'karyawans', 'barberServices'));
    }

    public function update(Request $request, $id)
    {
        $gaji = PengeluaranGaji::findOrFail($id);
        $karyawan = MasterKaryawan::findOrFail($request->karyawan_id);
        $skemaGaji = ($karyawan->divisi === 'barber' || $request->skema_gaji === 'komisi_barber') ? 'komisi_barber' : 'jam_kerja';

        $rules = [
            'karyawan_id'             => 'required|exists:karyawans,id',
            'periode_mulai'           => 'nullable|date',
            'periode_selesai'         => 'nullable|date|after_or_equal:periode_mulai',
            'tanggal_bayar'           => 'required|date',
            'metode_pembayaran'       => 'required|in:cash,transfer',
            'status'                  => 'required|in:dibayar,pending',
            'bonus'                   => 'nullable|numeric|min:0',
            'potongan'                => 'nullable|numeric|min:0',
            'catatan'                 => 'nullable|string',
        ];

        if ($skemaGaji === 'komisi_barber') {
            $rules['rincian_layanan'] = 'nullable|array';
            $rules['persentase_komisi'] = 'nullable|numeric|min:0|max:100';
        } else {
            $rules['total_jam_kerja'] = 'required|numeric|min:0';
            $rules['tarif_per_jam']   = 'nullable|numeric|min:0';
            $rules['jam_training']    = 'nullable|numeric|min:0';
            $rules['tarif_training_per_8jam'] = 'nullable|numeric|min:0';
        }

        $request->validate($rules, [
            'karyawan_id.required'     => 'Pilih karyawan terlebih dahulu.',
            'tanggal_bayar.required'   => 'Tanggal pembayaran wajib diisi.',
            'total_jam_kerja.required' => 'Total jam kerja wajib diisi.',
        ]);

        $bonus = (float)($request->bonus ?? 0);
        $potongan = (float)($request->potongan ?? 0);

        if ($skemaGaji === 'komisi_barber') {
            $persentaseKomisi = $request->filled('persentase_komisi') ? (float)$request->persentase_komisi : 45.00;
            $rincianLayananRaw = $request->input('rincian_layanan', []);
            $rincianLayanan = [];
            $totalKepala = 0;
            $totalOmset = 0;
            $totalKomisi = 0;

            if (is_array($rincianLayananRaw)) {
                foreach ($rincianLayananRaw as $item) {
                    $namaLayanan = trim($item['nama_layanan'] ?? '');
                    $tipeHari = $item['tipe_hari'] ?? 'all';
                    $tarif = (float)($item['tarif'] ?? 0);
                    $jumlah = (int)($item['jumlah_kepala'] ?? 0);
                    $komisiPersen = isset($item['persentase_komisi']) && $item['persentase_komisi'] !== '' ? (float)$item['persentase_komisi'] : $persentaseKomisi;

                    if ($jumlah > 0 && !empty($namaLayanan)) {
                        $subtotalOmset = $tarif * $jumlah;
                        $subtotalKomisi = $subtotalOmset * ($komisiPersen / 100);

                        $rincianLayanan[] = [
                            'nama_layanan'      => $namaLayanan,
                            'tipe_hari'         => $tipeHari,
                            'tarif'             => $tarif,
                            'jumlah_kepala'     => $jumlah,
                            'persentase_komisi' => $komisiPersen,
                            'subtotal_omset'    => $subtotalOmset,
                            'subtotal_komisi'   => $subtotalKomisi,
                        ];

                        $totalKepala += $jumlah;
                        $totalOmset += $subtotalOmset;
                        $totalKomisi += $subtotalKomisi;
                    }
                }
            }

            $totalGaji = max(0, ($totalKomisi + $bonus - $potongan));
            $totalJamKerja = 0;
            $tarifPerJam = 0;
            $totalUpahJam = 0;
            $jamTraining = 0;
            $tarifTrainingPer8Jam = 0;
            $totalFeeTraining = 0;
        } else {
            $totalJamKerja = (float)$request->total_jam_kerja;
            $tarifPerJam = $request->filled('tarif_per_jam') ? (float)$request->tarif_per_jam : 5000;
            $totalUpahJam = $totalJamKerja * $tarifPerJam;

            $jamTraining = (float)($request->jam_training ?? 0);
            $tarifTrainingPer8Jam = $request->filled('tarif_training_per_8jam') ? (float)$request->tarif_training_per_8jam : 20000;
            $totalFeeTraining = ($jamTraining / 8) * $tarifTrainingPer8Jam;

            $totalGaji = max(0, ($totalUpahJam + $totalFeeTraining + $bonus - $potongan));
            $totalKepala = 0;
            $totalOmset = 0;
            $persentaseKomisi = 0;
            $totalKomisi = 0;
            $rincianLayanan = null;
        }

        $gaji->update([
            'karyawan_id'             => $request->karyawan_id,
            'skema_gaji'              => $skemaGaji,
            'periode_mulai'           => $request->periode_mulai,
            'periode_selesai'         => $request->periode_selesai,
            'tanggal_bayar'           => $request->tanggal_bayar,
            'total_jam_kerja'         => $totalJamKerja,
            'tarif_per_jam'           => $tarifPerJam,
            'total_upah_jam'          => $totalUpahJam,
            'total_kepala'            => $totalKepala,
            'total_omset'             => $totalOmset,
            'persentase_komisi'       => $persentaseKomisi,
            'total_komisi'            => $totalKomisi,
            'rincian_layanan'         => $rincianLayanan,
            'jam_training'            => $jamTraining,
            'tarif_training_per_8jam' => $tarifTrainingPer8Jam,
            'total_fee_training'      => $totalFeeTraining,
            'bonus'                   => $bonus,
            'potongan'                => $potongan,
            'total_gaji'              => $totalGaji,
            'metode_pembayaran'       => $request->metode_pembayaran,
            'status'                  => $request->status,
            'catatan'                 => $request->catatan,
        ]);

        return redirect()->route('admin.pengeluaran-gaji.index')
            ->with('success', "Data slip gaji {$gaji->kode_slip} berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $gaji = PengeluaranGaji::findOrFail($id);
        $kode = $gaji->kode_slip;
        $gaji->delete();

        return redirect()->route('admin.pengeluaran-gaji.index')
            ->with('success', "Slip gaji {$kode} berhasil dihapus!");
    }
}
