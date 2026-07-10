<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kutipan Derma</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #059669;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0 0 10px 0;
            color: #059669;
        }
        .header p {
            margin: 0;
            color: #555;
        }
        .summary-box {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .summary-item {
            border: 1px solid #ccc;
            padding: 15px;
            width: 30%;
            text-align: center;
            border-radius: 5px;
            background: #f9f9f9;
        }
        .summary-item strong {
            display: block;
            font-size: 1.2rem;
            margin-top: 5px;
            color: #059669;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
        }
        .amount {
            text-align: right;
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 0.8rem;
            color: #777;
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h1>Laporan Kutipan Derma</h1>
        <p>Janaan Sistem MyInfaq | Tarikh: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="summary-box">
        <div class="summary-item">
            Jumlah Kutipan Berjaya
            <strong>RM {{ number_format($summary['jumlah_kutipan'], 2) }}</strong>
        </div>
        <div class="summary-item">
            Transaksi Berjaya
            <strong>{{ $summary['transaksi_berjaya'] }}</strong>
        </div>
        <div class="summary-item">
            Keseluruhan Transaksi
            <strong>{{ $summary['jumlah_transaksi'] }}</strong>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Resit</th>
                <th>Tarikh</th>
                <th>Penderma</th>
                <th>Kempen</th>
                <th>Status</th>
                <th class="amount">Amaun (RM)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $row->no_resit ?? '-' }}</td>
                    <td>{{ date('d/m/Y H:i', strtotime($row->tarikh_derma)) }}</td>
                    <td>{{ $row->nama_penderma }}</td>
                    <td>
                        {{ $row->tajuk_kempen }}
                        <br><small style="color: #666;">{{ $row->nama_organisasi }}</small>
                    </td>
                    <td>{{ $row->status_bayaran }}</td>
                    <td class="amount">{{ number_format($row->amaun_derma, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">Tiada rekod dijumpai.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Laporan ini dijana secara automatik oleh sistem MyInfaq. Tiada tandatangan diperlukan.
    </div>

</body>
</html>
