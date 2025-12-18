<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi Pembayaran #{{ $payment->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #FF6900;
        }

        .header h1 {
            color: #FF6900;
            font-size: 32px;
            margin-bottom: 5px;
        }

        .header .subtitle {
            color: #666;
            font-size: 14px;
        }

        .receipt-title {
            text-align: center;
            margin-bottom: 30px;
        }

        .receipt-title h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 5px;
        }

        .receipt-number {
            color: #666;
            font-size: 14px;
        }

        .info-section {
            margin-bottom: 30px;
        }

        .info-row {
            display: flex;
            margin-bottom: 10px;
        }

        .info-label {
            width: 200px;
            font-weight: bold;
            color: #555;
        }

        .info-value {
            flex: 1;
            color: #333;
        }

        .divider {
            height: 2px;
            background: #eee;
            margin: 30px 0;
        }

        .payment-details {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .payment-details h3 {
            color: #FF6900;
            margin-bottom: 15px;
            font-size: 18px;
        }

        .amount-section {
            background: #FF6900;
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 30px;
        }

        .amount-section .label {
            font-size: 14px;
            margin-bottom: 5px;
            opacity: 0.9;
        }

        .amount-section .amount {
            font-size: 32px;
            font-weight: bold;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-verified {
            background: #10b981;
            color: white;
        }

        .status-pending {
            background: #f59e0b;
            color: white;
        }

        .status-rejected {
            background: #ef4444;
            color: white;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #eee;
        }

        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 60px;
        }

        .signature-box {
            text-align: center;
            width: 45%;
        }

        .signature-line {
            border-top: 1px solid #333;
            margin-top: 80px;
            padding-top: 10px;
            font-size: 14px;
        }

        .notes {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            font-size: 14px;
        }

        .print-button {
            background: #FF6900;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            display: block;
            margin: 20px auto;
            transition: background 0.3s;
        }

        .print-button:hover {
            background: #e55d00;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .receipt-container {
                box-shadow: none;
                padding: 20px;
            }

            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="header">
            <h1>LIVORA</h1>
            <div class="subtitle">Live Better, Stay Better</div>
        </div>

        <!-- Receipt Title -->
        <div class="receipt-title">
            <h2>KWITANSI PEMBAYARAN</h2>
            <div class="receipt-number">No. Kwitansi: #{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</div>
        </div>

        <!-- Payment Info -->
        <div class="info-section">
            <div class="info-row">
                <div class="info-label">Tanggal Pembayaran:</div>
                <div class="info-value">{{ $payment->created_at->format('d F Y, H:i') }} WIB</div>
            </div>
            @if($payment->verified_at)
            <div class="info-row">
                <div class="info-label">Tanggal Verifikasi:</div>
                <div class="info-value">{{ $payment->verified_at->format('d F Y, H:i') }} WIB</div>
            </div>
            @endif
            <div class="info-row">
                <div class="info-label">Status:</div>
                <div class="info-value">
                    @if($payment->status === 'verified')
                        <span class="status-badge status-verified">Terverifikasi</span>
                    @elseif($payment->status === 'pending')
                        <span class="status-badge status-pending">Menunggu</span>
                    @else
                        <span class="status-badge status-rejected">Ditolak</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Payer Info -->
        <div class="info-section">
            <h3 style="color: #FF6900; margin-bottom: 15px;">Informasi Penyewa</h3>
            <div class="info-row">
                <div class="info-label">Nama:</div>
                <div class="info-value">{{ $payment->booking->user->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value">{{ $payment->booking->user->email }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Nomor Telepon:</div>
                <div class="info-value">{{ $payment->booking->tenant_phone ?? '-' }}</div>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Property Info -->
        <div class="payment-details">
            <h3>Detail Pemesanan</h3>
            <div class="info-row">
                <div class="info-label">Nama Kos:</div>
                <div class="info-value">{{ $payment->booking->room->boardingHouse->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Alamat:</div>
                <div class="info-value">{{ $payment->booking->room->boardingHouse->address }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tipe Kamar:</div>
                <div class="info-value">{{ $payment->booking->room->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Periode Sewa:</div>
                <div class="info-value">
                    {{ \Carbon\Carbon::parse($payment->booking->check_in_date)->format('d M Y') }} 
                    - 
                    {{ \Carbon\Carbon::parse($payment->booking->check_out_date)->format('d M Y') }}
                    ({{ \Carbon\Carbon::parse($payment->booking->check_in_date)->diffInDays(\Carbon\Carbon::parse($payment->booking->check_out_date)) }} hari)
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Tanggal Bayar:</div>
                <div class="info-value">{{ $payment->created_at->format('d F Y') }}</div>
            </div>
        </div>

        <!-- Amount -->
        <div class="amount-section">
            <div class="label">TOTAL PEMBAYARAN</div>
            <div class="amount">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
            @php
                try {
                    $terbilang = \App\Helpers\NumberToWords::convert($payment->amount);
                } catch (\Exception $e) {
                    $terbilang = '';
                }
            @endphp
            @if($terbilang)
            <div class="label" style="margin-top: 5px; font-style: italic;">
                ({{ $terbilang }} rupiah)
            </div>
            @endif
        </div>

        @if($payment->notes)
        <div class="notes">
            <strong>Catatan:</strong><br>
            {{ $payment->notes }}
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <div style="text-align: center; color: #666; font-size: 12px; margin-bottom: 20px;">
                Kwitansi ini dicetak secara otomatis oleh sistem LIVORA dan sah tanpa tanda tangan
            </div>

            <div class="signature-section">
                <div class="signature-box">
                    <div>Penyewa</div>
                    <div class="signature-line">
                        {{ $payment->booking->user->name }}
                    </div>
                </div>
                <div class="signature-box">
                    <div>Pemilik Kos</div>
                    <div class="signature-line">
                        {{ $payment->booking->room->boardingHouse->user->name }}
                    </div>
                </div>
            </div>

            <div style="text-align: center; margin-top: 30px; color: #999; font-size: 11px;">
                Dokumen ini dicetak pada {{ now()->format('d F Y, H:i') }} WIB<br>
                LIVORA - Platform Pencarian dan Booking Kost Terpercaya<br>
                www.livora.com | support@livora.com
            </div>
        </div>

        <!-- Print Button -->
        <button class="print-button" onclick="window.print()">
            🖨️ Cetak / Download PDF
        </button>
    </div>
</body>
</html>
