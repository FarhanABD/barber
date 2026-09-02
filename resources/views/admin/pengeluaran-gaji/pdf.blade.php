<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Gaji - {{ $gaji->kode_slip }}</title>
    <style>
        @page {
            size: a4 portrait;
            margin: 12mm 15mm 12mm 15mm;
        }
        body {
            font-family: DejaVu Sans, Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #222;
            line-height: 1.35;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 6px;
            margin-bottom: 10px;
        }
        .header h2 {
            margin: 0 0 3px 0;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header p {
            margin: 0;
            font-size: 9px;
            color: #555;
        }
        .info-table {
            width: 100%;
            margin-bottom: 10px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 2px 3px;
            font-size: 10px;
            vertical-align: top;
        }
        .label {
            width: 110px;
            color: #444;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            page-break-inside: avoid;
        }
        .details-table th, .details-table td {
            border: 1px solid #777;
            padding: 5px 6px;
            font-size: 10px;
        }
        .details-table th {
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
        .total-row {
            background-color: #f5f5f5;
            font-weight: bold;
            font-size: 10.5px;
        }
        .notes {
            font-size: 9.5px;
            color: #333;
            margin-bottom: 12px;
            padding: 4px 6px;
            border-left: 3px solid #666;
            background-color: #f9f9f9;
            page-break-inside: avoid;
        }
        .signatures {
            width: 100%;
            margin-top: 15px;
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
        <h2>BUKTI PEMBAYARAN GAJI</h2>
        <p>Sistem Penggajian Karyawan - Barbershop & Angkringan Antarsukha</p>
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
                    {{ $gaji->periode_mulai->format('d/m/Y') }} s/d {{ $gaji->periode_selesai->format('d/m/Y') }}
                @else
                    -
                @endif
            </td>
        </tr>
        <tr>
            <td class="label"><strong>Divisi</strong></td>
            <td>: {{ ucfirst($gaji->karyawan->divisi ?? '-') }} ({{ $gaji->skema_gaji === 'komisi_barber' ? 'Bagi Hasil 45%' : 'Upah Jam Kerja' }})</td>
            <td class="label"><strong>Metode Bayar</strong></td>
            <td>: {{ strtoupper($gaji->metode_pembayaran) }} ({{ ucfirst($gaji->status) }})</td>
        </tr>
    </table>

    <table class="details-table">
        <thead>
            @if($gaji->skema_gaji === 'komisi_barber')
            <tr>
                <th>Layanan / Paket Barber</th>
                <th class="text-center" width="70">Kepala</th>
                <th class="text-right" width="100">Tarif</th>
                <th class="text-center" width="70">Komisi</th>
                <th class="text-right" width="110">Subtotal</th>
            </tr>
            @else
            <tr>
                <th>Deskripsi Komponen Gaji</th>
                <th class="text-center" width="80">Kuantitas</th>
                <th class="text-right" width="120">Tarif Satuan</th>
                <th class="text-right" width="120">Subtotal</th>
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
                            <div style="font-size: 8.5px; color: #666;">{{ $item['tipe_hari'] ?? '-' }}</div>
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
                <tr style="background-color: #f7f7f7;">
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
                    <td class="text-right">+ Rp {{ number_format($gaji->total_fee_training, 0, ',', '.') }}</td>
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
    <div class="notes">
        <strong>Catatan:</strong> {{ $gaji->catatan }}
    </div>
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

</body>
</html>
