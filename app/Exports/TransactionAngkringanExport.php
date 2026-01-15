<?php

namespace App\Exports;

use App\Models\TransactionAngkringan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionAngkringanExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection(): Collection
    {
        $query = TransactionAngkringan::query();

        if ($this->request->filled('date')) {
            $query->whereDate('tanggal', $this->request->date);
        }

        if ($this->request->filled('search')) {
            $query->where('kode_transaksi', 'like', '%' . $this->request->search . '%');
        }

        return $query->get([
            'kode_transaksi',
            'tanggal',
            'metode_pembayaran',
            'total',
            'jumlah_bayar',
            'kembalian',
            'status'
        ]);
    }

    public function headings(): array
    {
        return [
            'Kode Transaksi',
            'Tanggal',
            'Metode Pembayaran',
            'Total',
            'Jumlah Bayar',
            'Kembalian',
            'Status'
        ];
    }
}