<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Laba Rugi - {{ $startDate ? $startDate . ' s/d ' . ($endDate ?? date('Y-m-d')) : 'All Time' }}</title>
    <style>
        @page {
            size: a4 portrait;
            margin: 12mm 15mm 12mm 15mm;
        }
        body {
            font-family: DejaVu Sans, Helvetica, Arial, sans-serif;
            font-size: 10.5px;
            color: #222;
            line-height: 1.35;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }
        .header h2 {
            margin: 0 0 4px 0;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header p {
            margin: 0;
            font-size: 9.5px;
            color: #555;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 12px;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 2px 4px;
            font-size: 10.5px;
        }
        .summary-box {
            width: 100%;
            margin-bottom: 14px;
            border-collapse: collapse;
        }
        .summary-box td {
            border: 1px solid #ccc;
            padding: 8px 10px;
            text-align: center;
            width: 25%;
            background-color: #f9f9f9;
        }
        .summary-box .val {
            font-size: 13px;
            font-weight: bold;
            margin-top: 3px;
        }
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .table-data th, .table-data td {
            border: 1px solid #777;
            padding: 5px 8px;
            font-size: 10px;
        }
        .table-data th {
            background-color: #f0f0f0;
            text-align: left;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .section-row {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        .total-inflow {
            background-color: #e6f4ea;
            font-weight: bold;
        }
        .total-outflow {
            background-color: #fce8e6;
            font-weight: bold;
        }
        .net-profit-row {
            background-color: #e8f0fe;
            font-weight: bold;
            font-size: 12px;
        }
        .signatures {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            page-break-inside: avoid;
        }
        .signatures td {
            width: 50%;
            text-align: center;
            font-size: 10.5px;
            vertical-align: top;
        }
        .signature-space {
            height: 45px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>LAPORAN LABA & RUGI (PROFIT & LOSS)</h2>
        <p>Antarsukha Barbershop & Angkringan - Pembukuan Finansial Resmi</p>
    </div>

    <table class="meta-table">
        <tr>
            <td width="15%"><strong>Periode Laporan</strong></td>
            <td width="35%">: 
                @if($startDate && $endDate)
                    {{ date('d/m/Y', strtotime($startDate)) }} s/d {{ date('d/m/Y', strtotime($endDate)) }}
                @elseif($startDate)
                    Mulai {{ date('d/m/Y', strtotime($startDate)) }}
                @elseif($endDate)
                    Hingga {{ date('d/m/Y', strtotime($endDate)) }}
                @else
                    Seluruh Waktu (All Time)
                @endif
            </td>
            <td width="15%"><strong>Tanggal Cetak</strong></td>
            <td width="35%">: {{ date('d/m/Y H:i') }} WIB</td>
        </tr>
        <tr>
            <td><strong>Status Perusahaan</strong></td>
            <td>: 
                @if($netProfit > 0)
                    <strong style="color: #16a34a;">SURPLUS / PROFIT</strong>
                @elseif($netProfit < 0)
                    <strong style="color: #dc2626;">DEFISIT / RUGI</strong>
                @else
                    <strong>IMPAS (BREAK EVEN)</strong>
                @endif
            </td>
            <td><strong>Dicetak Oleh</strong></td>
            <td>: {{ auth()->user()->name ?? 'Administrator' }}</td>
        </tr>
    </table>

    {{-- SUMMARY METRICS --}}
    <table class="summary-box">
        <tr>
            <td>
                <div style="font-size: 9.5px; color: #555;">TOTAL PEMASUKAN</div>
                <div class="val" style="color: #16a34a;">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
            </td>
            <td>
                <div style="font-size: 9.5px; color: #555;">TOTAL PENGELUARAN</div>
                <div class="val" style="color: #dc2626;">Rp {{ number_format($totalExpense, 0, ',', '.') }}</div>
            </td>
            <td style="background-color: {{ $netProfit >= 0 ? '#e6f4ea' : '#fce8e6' }};">
                <div style="font-size: 9.5px; color: #555;">LABA / RUGI BERSIH</div>
                <div class="val" style="color: {{ $netProfit >= 0 ? '#16a34a' : '#dc2626' }};">
                    {{ $netProfit < 0 ? '- ' : '' }}Rp {{ number_format(abs($netProfit), 0, ',', '.') }}
                </div>
            </td>
            <td>
                <div style="font-size: 9.5px; color: #555;">PROFIT MARGIN</div>
                <div class="val" style="color: #0284c7;">{{ $profitMargin }}%</div>
            </td>
        </tr>
    </table>

    {{-- FINANCIAL STATEMENT TABLE --}}
    <table class="table-data">
        <thead>
            <tr>
                <th width="50%">Deskripsi Pos Keuangan</th>
                <th width="20%" class="text-center">Kuantitas</th>
                <th width="30%" class="text-right">Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            {{-- I. PENDAPATAN --}}
            <tr class="section-row">
                <td colspan="3">I. PENDAPATAN OPERASIONAL (REVENUE)</td>
            </tr>
            <tr>
                <td style="padding-left: 15px;">1. Penjualan Jasa Barbershop</td>
                <td class="text-center">{{ $countTransactionBarber }} Transaksi</td>
                <td class="text-right">Rp {{ number_format($totalIncomeBarber, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="padding-left: 15px;">2. Penjualan Makanan & Minuman Angkringan</td>
                <td class="text-center">{{ $countTransactionAngkringan }} Transaksi</td>
                <td class="text-right">Rp {{ number_format($totalIncomeAngkringan, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-inflow">
                <td colspan="2" class="text-right"><strong>TOTAL PENDAPATAN OPERASIONAL (A):</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($totalIncome, 0, ',', '.') }}</strong></td>
            </tr>

            {{-- II. BEBAN PENGELUARAN --}}
            <tr class="section-row">
                <td colspan="3">II. BEBAN & PENGELUARAN OPERASIONAL (EXPENSES)</td>
            </tr>
            <tr>
                <td style="padding-left: 15px;">1. Beban Gaji Karyawan Divisi Barber</td>
                <td class="text-center">-</td>
                <td class="text-right">Rp {{ number_format($gajiBarber, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="padding-left: 15px;">2. Beban Gaji Karyawan Divisi Angkringan</td>
                <td class="text-center">-</td>
                <td class="text-right">Rp {{ number_format($gajiAngkringan, 0, ',', '.') }}</td>
            </tr>
            @if($gajiLainnya > 0)
            <tr>
                <td style="padding-left: 15px;">3. Beban Gaji Karyawan Lainnya</td>
                <td class="text-center">-</td>
                <td class="text-right">Rp {{ number_format($gajiLainnya, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr>
                <td style="padding-left: 15px;">{{ $gajiLainnya > 0 ? '4' : '3' }}. Beban Belanja Bahan Baku Angkringan</td>
                <td class="text-center">{{ $countExpenseItems }} Item</td>
                <td class="text-right">Rp {{ number_format($totalBahanBaku, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-outflow">
                <td colspan="2" class="text-right"><strong>TOTAL BEBAN PENGELUARAN (B):</strong></td>
                <td class="text-right"><strong>- Rp {{ number_format($totalExpense, 0, ',', '.') }}</strong></td>
            </tr>

            {{-- III. LABA / RUGI BERSIH --}}
            <tr class="net-profit-row">
                <td colspan="2" class="text-right" style="font-size: 11.5px;">
                    <strong>LABA / RUGI BERSIH PERUSAHAAN (A - B):</strong>
                </td>
                <td class="text-right" style="font-size: 12px; color: {{ $netProfit >= 0 ? '#16a34a' : '#dc2626' }};">
                    <strong>{{ $netProfit < 0 ? '- ' : '' }}Rp {{ number_format(abs($netProfit), 0, ',', '.') }}</strong>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="signatures">
        <tr>
            <td>
                <p>Bagian Keuangan / Admin,</p>
                <div class="signature-space"></div>
                <p><strong>({{ auth()->user()->name ?? 'Administrator' }})</strong></p>
            </td>
            <td>
                <p>Owner Antarsukha,</p>
                <div class="signature-space"></div>
                <p><strong>(....................................)</strong></p>
            </td>
        </tr>
    </table>

</body>
</html>
