<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Resit Derma - {{ $derma->no_resit }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #0d9488;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #0d9488;
            margin-bottom: 5px;
        }
        .logo span {
            color: #1e293b;
        }
        .tagline {
            font-size: 12px;
            color: #64748b;
        }
        .receipt-title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .details-table th, .details-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        .details-table th {
            width: 40%;
            color: #64748b;
            font-weight: normal;
        }
        .details-table td {
            font-weight: bold;
            color: #1e293b;
        }
        .amount-row th, .amount-row td {
            border-top: 2px solid #0d9488;
            border-bottom: none;
            font-size: 18px;
            padding-top: 15px;
        }
        .amount-row td {
            color: #0d9488;
        }
        .footer {
            text-align: center;
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            font-size: 14px;
            color: #64748b;
        }
        .heart {
            color: #10b981;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">My<span>Infaq</span></div>
        <div class="tagline">Platform pengurusan derma & kempen kebajikan yang telus dan dipercayai.</div>
    </div>

    <div class="receipt-title">Resit Rasmi</div>

    <table class="details-table">
        <tr>
            <th>No. Resit</th>
            <td>{{ $derma->no_resit }}</td>
        </tr>
        <tr>
            <th>Tarikh Transaksi</th>
            <td>{{ $derma->tarikh_derma->format('d M Y, h:i A') }}</td>
        </tr>
        <tr>
            <th>Penderma</th>
            <td>{{ $derma->penderma?->nama_penderma ?? 'Hamba Allah' }}</td>
        </tr>
        <tr>
            <th>Kempen Disokong</th>
            <td>{{ $derma->kempen->tajuk_kempen }}</td>
        </tr>
        <tr>
            <th>Organisasi</th>
            <td>{{ $derma->kempen->organisasi?->nama_organisasi ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Kaedah Bayaran</th>
            <td>{{ $derma->kaedah_bayaran }}</td>
        </tr>
        <tr class="amount-row">
            <th>Jumlah Derma</th>
            <td>RM {{ number_format($derma->amaun_derma, 2) }}</td>
        </tr>
    </table>

    <div class="footer">
   
        <p>Terima kasih atas sumbangan ikhlas anda.</p>
        <p>Semoga sumbangan ini memberi manfaat dan mendapat ganjaran yang berlipat ganda.</p>
        <p style="font-size: 11px; margin-top: 20px; color: #94a3b8;">Resit ini dijana secara automatik oleh sistem MyInfaq dan tidak memerlukan tandatangan.</p>
    </div>
</body>
</html>
