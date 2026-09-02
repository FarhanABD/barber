<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - {{ $gaji->kode_slip }} - {{ $gaji->karyawan->nama ?? 'Karyawan' }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .slip-container {
            max-width: 650px;
            margin: 0 auto;
            background: #fff;
            padding: 25px 30px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }
        .header h2 {
            margin: 0 0 5px 0;
            font-size: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header p {
            margin: 0;
            font-size: 12px;
            color: #666;
        }
        .slip-title {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            margin: 10px 0 15px 0;
            text-decoration: underline;
        }
        .info-table {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 4px 6px;
            vertical-align: top;
        }
        .info-table .label {
            width: 130px;
            color: #555;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .details-table th, .details-table td {
            border: 1px solid #ccc;
            padding: 8px 10px;
        }
        .details-table th {
            background-color: #f2f2f2;
            text-align: left;
            font-size: 12px;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-row {
            background-color: #f9f9f9;
            font-size: 14px;
            font-weight: bold;
        }
        .signatures {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }
        .signatures td {
            width: 50%;
            text-align: center;
            padding-top: 10px;
        }
        .signature-space {
            height: 60px;
        }
        .no-print-bar {
            text-align: center;
            margin-bottom: 15px;
        }
        .btn-print {
            background: #28a745;
            color: #fff;
            border: none;
            padding: 8px 18px;
            font-size: 14px;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-print:hover {
            background: #218838;
        }
        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .slip-container {
                box-shadow: none;
                border: none;
                padding: 10px;
                max-width: 100%;
            }
            .no-print-bar {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="no-print-bar">
        <button class="btn-print" onclick="window.print()">🖨️ Cetak / Print Slip Gaji</button>
        <a href="{{ route('admin.pengeluaran-gaji.pdf', $gaji->id) }}" class="btn-print" style="background-color: #dc3545; text-decoration: none; display: inline-block; margin-left: 8px;">📄 Download Format PDF</a>
    </div>

    <div class="slip-container">
        <div class="header">
            <h2>BUKTI PEMBAYARAN GAJI</h2>
            <p>Sistem Penggajian Karyawan</p>
        </div>

        <table class="info-table">
            <tr>
                <td class="label"><strong>No. Slip</strong></td>
                <td>: <strong>{{ $gaji->kode_slip }}</strong></td>
                <td class="label"><strong>Tanggal Bayar</strong></td>
                <td>: {{ $gaji->tanggal_bayar ? $gaji->tanggal_bayar->format('d/m/Y') : '-' }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Nama Karyawan</strong></td>
                <td>: <strong>{{ $gaji->karyawan->nama ?? '-' }}</strong></td>
                <td class="label"><strong>Periode Kerja</strong></td>
                <td>: 
                    @if($gaji->periode_mulai && $gaji->periode_selesai)
                        {{ $gaji->periode_mulai->format('d/m/Y') }} - {{ $gaji->periode_selesai->format('d/m/Y') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label"><strong>Divisi</strong></td>
                <td>: {{ ucfirst($gaji->karyawan->divisi ?? '-') }}</td>
                <td class="label"><strong>Metode Bayar</strong></td>
                <td>: {{ strtoupper($gaji->metode_pembayaran) }} ({{ ucfirst($gaji->status) }})</td>
            </tr>
        </table>

        <table class="details-table">
            <thead>
                @if($gaji->skema_gaji === 'komisi_barber')
                <tr>
                    <th>Layanan / Paket Barber</th>
                    <th class="text-center" width="80">Kepala</th>
                    <th class="text-right" width="100">Tarif</th>
                    <th class="text-center" width="70">Komisi</th>
                    <th class="text-right" width="120">Subtotal</th>
                </tr>
                @else
                <tr>
                    <th>Komponen Gaji</th>
                    <th class="text-center" width="100">Kuantitas</th>
                    <th class="text-right" width="130">Tarif</th>
                    <th class="text-right" width="140">Jumlah</th>
                </tr>
                @endif
            </thead>
            <tbody>
                @if($gaji->skema_gaji === 'komisi_barber')
                    @if(!empty($gaji->rincian_layanan) && is_array($gaji->rincian_layanan))
                        @foreach($gaji->rincian_layanan as $item)
                        <tr>
                            <td>
                                <strong>{{ $item['nama_layanan'] }}</strong>
                                <div style="font-size: 10px; color: #666;">{{ $item['tipe_hari'] ?? '-' }}</div>
                            </td>
                            <td class="text-center">{{ $item['jumlah_kepala'] }}</td>
                            <td class="text-right">Rp {{ number_format($item['tarif'] ?? 0, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $item['persentase_komisi'] ?? $gaji->persentase_komisi ?? 45 }}%</td>
                            <td class="text-right">Rp {{ number_format($item['subtotal_komisi'] ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td><strong>Total Bagi Hasil Barber</strong></td>
                            <td class="text-center">{{ $gaji->total_kepala }}</td>
                            <td class="text-right">Rp {{ number_format($gaji->total_omset, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $gaji->persentase_komisi }}%</td>
                            <td class="text-right">Rp {{ number_format($gaji->total_komisi, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                    <tr style="background-color: #f5f5f5;">
                        <td colspan="4" class="text-right"><strong>Subtotal Komisi ({{ $gaji->total_kepala }} Kepala):</strong></td>
                        <td class="text-right"><strong>Rp {{ number_format($gaji->total_komisi, 0, ',', '.') }}</strong></td>
                    </tr>
                @else
                    <tr>
                        <td>Upah Jam Kerja Reguler</td>
                        <td class="text-center">{{ $gaji->total_jam_kerja }} Jam</td>
                        <td class="text-right">Rp {{ number_format($gaji->tarif_per_jam, 0, ',', '.') }} / jam</td>
                        <td class="text-right">Rp {{ number_format($gaji->total_upah_jam, 0, ',', '.') }}</td>
                    </tr>
                    @if($gaji->jam_training > 0)
                    <tr>
                        <td>Fee Training Anggota Baru</td>
                        <td class="text-center">{{ $gaji->jam_training }} Jam</td>
                        <td class="text-right">Rp {{ number_format($gaji->tarif_training_per_8jam, 0, ',', '.') }} / 8 jam</td>
                        <td class="text-right">Rp {{ number_format($gaji->total_fee_training, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                @endif

                @if($gaji->bonus > 0)
                <tr>
                    <td colspan="{{ $gaji->skema_gaji === 'komisi_barber' ? 4 : 3 }}">Bonus / Insentif Tambahan</td>
                    <td class="text-right">+ Rp {{ number_format($gaji->bonus, 0, ',', '.') }}</td>
                </tr>
                @endif
                @if($gaji->potongan > 0)
                <tr>
                    <td colspan="{{ $gaji->skema_gaji === 'komisi_barber' ? 4 : 3 }}">Potongan / Kasbon</td>
                    <td class="text-right">- Rp {{ number_format($gaji->potongan, 0, ',', '.') }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td colspan="{{ $gaji->skema_gaji === 'komisi_barber' ? 4 : 3 }}" class="text-right"><strong>TOTAL GAJI BERSIH (TAKE HOME PAY):</strong></td>
                    <td class="text-right"><strong>Rp {{ number_format($gaji->total_gaji, 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>

        @if($gaji->catatan)
        <p style="font-size: 12px; color: #555; margin-bottom: 20px;">
            <strong>Catatan:</strong> {{ $gaji->catatan }}
        </p>
        @endif

        <table class="signatures">
            <tr>
                <td>
                    <p>Penerima / Karyawan,</p>
                    <div class="signature-space"></div>
                    <p><strong>({{ $gaji->karyawan->nama ?? '................................' }})</strong></p>
                </td>
                <td>
                    <p>Owner,</p>
                    <div class="signature-space"></div>
                    <p><strong>(....................................)</strong></p>
                </td>
            </tr>
        </table>
    </div>

    <script>
        window.onload = function() {
            // Uncomment jika ingin langsung membuka dialog print saat dibuka
            // window.print();
        };
    </script>
</body>
</html>
