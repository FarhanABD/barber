<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Transaction::with('barber');

        // 🔍 SEARCH
        if ($this->request->filled('search')) {
            $search = $this->request->search;
            $query->where(function ($q) use ($search) {
                $q->where('transaction_code', 'like', "%$search%")
                  ->orWhere('customer_name', 'like', "%$search%")
                  ->orWhereHas('barber', function ($b) use ($search) {
                      $b->where('name', 'like', "%$search%");
                  });
            });
        }

        // 📅 FILTER TANGGAL
        if ($this->request->filled('start_date') && $this->request->filled('end_date')) {
            $query->whereBetween('created_at', [$this->request->start_date . ' 00:00:00', $this->request->end_date . ' 23:59:59']);
        } elseif ($this->request->filled('start_date')) {
            $query->where('created_at', '>=', $this->request->start_date . ' 00:00:00');
        } elseif ($this->request->filled('end_date')) {
            $query->where('created_at', '<=', $this->request->end_date . ' 23:59:59');
        } elseif ($this->request->filled('date')) {
            $query->whereDate('created_at', $this->request->date);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Kode Transaksi',
            'No Antrian',
            'Tanggal',
            'Nama Customer',
            'Nama Barber',
            'Nama Kasir',
            'Diskon',
            'Total Harga'
        ];
    }

    public function map($row): array
    {
        return [
            $row->transaction_code,
            $row->no_antrian,
            $row->created_at ? Carbon::parse($row->created_at)->format('d-m-Y H:i') : '-',
            $row->customer_name,
            $row->barber->name ?? '-',
            $row->nama_kasir ?? '-',
            $row->diskon . '%',
            'Rp ' . number_format($row->total_price, 0, ',', '.'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Styling Header (Bold, White Text, Stisla Blue Background)
        $sheet->getStyle('A1:H1')->applyFromArray([
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
            $sheet->getStyle('B2:B' . $highestRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // No Antrian
            $sheet->getStyle('C2:C' . $highestRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // Tanggal
            $sheet->getStyle('G2:G' . $highestRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // Diskon
            $sheet->getStyle('H2:H' . $highestRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT); // Total Harga

            // Thin borders
            $sheet->getStyle('A1:H' . $highestRow)->applyFromArray([
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