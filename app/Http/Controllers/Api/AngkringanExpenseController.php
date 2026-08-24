<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AngkringanExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AngkringanExpenseController extends Controller
{
    // List all expenses
    public function index(Request $request)
    {
        $expenses = AngkringanExpense::latest('tanggal')->get();
        return response()->json([
            'success' => true,
            'data' => $expenses
        ]);
    }

    // Submit new expense (status 'pengajuan')
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_pengeluaran' => 'required|string|max:255',
            'jumlah'           => 'required|numeric|min:0',
            'tanggal'          => 'required|date',
            'nama_kasir'       => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        $expense = AngkringanExpense::create([
            'nama_pengeluaran' => $request->nama_pengeluaran,
            'jumlah'           => $request->jumlah,
            'tanggal'          => $request->tanggal,
            'nama_kasir'       => $request->nama_kasir,
            'status'           => 'pengajuan', // default status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengeluaran berhasil diajukan',
            'data'    => $expense
        ], 201);
    }

    // Upload receipt / photo and change status to 'approved'
    public function uploadReceipt(Request $request, $id)
    {
        $expense = AngkringanExpense::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'bukti_resi' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        if ($request->hasFile('bukti_resi')) {
            // Delete old file if exists
            if ($expense->bukti_resi && Storage::disk('public')->exists($expense->bukti_resi)) {
                Storage::disk('public')->delete($expense->bukti_resi);
            }

            $file = $request->file('bukti_resi');
            $path = $file->store('bukti_resi', 'public');

            $expense->update([
                'bukti_resi' => $path,
                'status'     => 'approved', // Auto approved on upload
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bukti resi berhasil diunggah, status disetujui',
                'data'    => $expense
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengunggah file resi'
        ], 400);
    }
}
