<?php

namespace App\Exports;

use App\Models\TransactionAngkringan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class TransactionAngkringanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection(): Collection
    {
        $query = TransactionAngkringan::query();

        // Filter tanggal range
        if ($this->request->filled('start_date') && $this->request->filled('end_date')) {
            $query->whereBetween('tanggal', [$this->request->start_date . ' 00:00:00', $this->request->end_date . ' 23:59:59']);
        } elseif ($this->request->filled('start_date')) {
            $query->where('tanggal', '>=', $this->request->start_date . ' 00:00:00');
        } elseif ($this->request->filled('end_date')) {
            $query->where('tanggal', '<=', $this->request->end_date . ' 23:59:59');
        } elseif ($this->request->filled('date')) {
            $query->whereDate('tanggal', $this->request->date);
        }

        // Filter kasir
        if ($this->request->filled('kasir')) {
            $query->where('nama_kasir', 'like', '%' . $this->request->kasir . '%');
        }

        // Filter metode pembayaran
        if ($this->request->filled('metode')) {
            $query->where('metode_pembayaran', $this->request->metode);
        }

        // Filter search / kode transaksi
        if ($this->request->filled('search')) {
            $query->where('kode_transaksi', 'like', '%' . $this->request->search . '%');
        }

        return $query->get();
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

    public function map($row): array
    {
        return [
            $row->kode_transaksi,
            $row->tanggal ? Carbon::parse($row->tanggal)->format('d-m-Y H:i') : '-',
            strtoupper($row->metode_pembayaran),
            'Rp ' . number_format($row->total, 0, ',', '.'),
            'Rp ' . number_format($row->jumlah_bayar, 0, ',', '.'),
            'Rp ' . number_format($row->kembalian, 0, ',', '.'),
            ucfirst($row->status),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Styling Header (Bold, White Text, Stisla Blue Background)
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '6777EF'], // Premium Stisla blue (#6777EF)
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ]
        ]);

        // Row height for header
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Styling data cells alignment and borders
        $highestRow = $sheet->getHighestRow();
        if ($highestRow > 1) {
            // Apply alignment
            $sheet->getStyle('B2:B' . $highestRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('C2:C' . $highestRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('D2:F' . $highestRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('G2:G' . $highestRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Thin borders
            $sheet->getStyle('A1:G' . $highestRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => 'E3E6F0'],
                    ],
                ],
            ]);
        }
    }
}