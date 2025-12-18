<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi Pembayaran #{{ $payment->id }} - LIVORA</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background: #f5f5f5;
        }
        
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border: 2px solid #2563eb;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #2563eb;
            font-size: 32px;
            margin-bottom: 5px;
        }
        
        .header p {
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
        
        .receipt-id {
            color: #666;
            font-size: 14px;
        }
        
        .info-section {
            margin-bottom: 30px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .info-label {
            font-weight: 600;
            color: #374151;
            width: 200px;
        }
        
        .info-value {
            color: #1f2937;
            flex: 1;
            text-align: right;
        }
        
        .amount-section {
            background: #f3f4f6;
            padding: 20px;
            border-radius: 8px;
            margin: 30px 0;
        }
        
        .amount-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .amount-row:last-child {
            margin-bottom: 0;
        }
        
        .total-amount {
            border-top: 2px solid #2563eb;
            padding-top: 15px;
            margin-top: 15px;
        }
        
        .total-amount .info-label {
            font-size: 18px;
            color: #2563eb;
        }
        
        .total-amount .info-value {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status-verified {
            background: #d1fae5;
            color: #065f46;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
        }
        
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 60px;
        }
        
        .signature-box {
            text-align: center;
            width: 200px;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 80px;
            padding-top: 10px;
        }
        
        .print-button {
            background: #2563eb;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            margin-bottom: 20px;
        }
        
        .print-button:hover {
            background: #1d4ed8;
        }
        
        @media print {
            body {
                padding: 0;
                background: white;
            }
            
            .receipt-container {
                border: none;
                box-shadow: none;
                max-width: 100%;
            }
            
            .print-button {
                display: none;
            }
        }
        
        .verified-stamp {
            position: absolute;
            top: 150px;
            right: 50px;
            transform: rotate(-15deg);
            border: 4px solid #059669;
            color: #059669;
            padding: 10px 20px;
            font-size: 24px;
            font-weight: bold;
            border-radius: 8px;
            opacity: 0.7;
        }
    </style>
</head>
<body>
    <div style="text-align: center; margin-bottom: 20px;">
        <button class="print-button" onclick="window.print()">
            🖨️ Print / Download PDF
        </button>
    </div>
    
    <div class="receipt-container" style="position: relative;">
        <div class="verified-stamp">VERIFIED</div>
        
        <!-- Header -->
        <div class="header">
            <h1>LIVORA</h1>
            <p>Sistem Manajemen Kost & Boarding House</p>
            <p>livora.kost@example.com | www.livora.com</p>
        </div>
        
        <!-- Receipt Title -->
        <div class="receipt-title">
            <h2>KWITANSI PEMBAYARAN</h2>
            <p class="receipt-id">No: {{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>
        
        <!-- Payment Information -->
        <div class="info-section">
            <h3 style="margin-bottom: 15px; color: #374151;">Informasi Pembayaran</h3>
            
            <div class="info-row">
                <span class="info-label">Tanggal Bayar</span>
                <span class="info-value">{{ $payment->created_at->format('d F Y') }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Tanggal Verifikasi</span>
                <span class="info-value">{{ $payment->verified_at ? $payment->verified_at->format('d F Y') : '-' }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Status</span>
                <span class="info-value">
                    <span class="status-badge status-verified">✓ TERVERIFIKASI</span>
                </span>
            </div>
        </div>
        
        <!-- Tenant Information -->
        <div class="info-section">
            <h3 style="margin-bottom: 15px; color: #374151;">Data Penyewa</h3>
            
            <div class="info-row">
                <span class="info-label">Nama Penyewa</span>
                <span class="info-value">{{ $payment->booking->user->name }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Email</span>
                <span class="info-value">{{ $payment->booking->user->email }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">No. Telepon</span>
                <span class="info-value">{{ $payment->booking->user->phone ?? '-' }}</span>
            </div>
        </div>
        
        <!-- Booking Information -->
        <div class="info-section">
            <h3 style="margin-bottom: 15px; color: #374151;">Detail Booking</h3>
            
            <div class="info-row">
                <span class="info-label">ID Booking</span>
                <span class="info-value">#{{ $payment->booking->id }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Nama Kost</span>
                <span class="info-value">{{ $payment->booking->room->boardingHouse->name ?? 'N/A' }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Nama Kamar</span>
                <span class="info-value">{{ $payment->booking->room->name ?? 'N/A' }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Periode Check-in</span>
                <span class="info-value">
                    {{ $payment->booking->check_in_date ? \Carbon\Carbon::parse($payment->booking->check_in_date)->format('d F Y') : '-' }}
                </span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Periode Check-out</span>
                <span class="info-value">
                    {{ $payment->booking->check_out_date ? \Carbon\Carbon::parse($payment->booking->check_out_date)->format('d F Y') : '-' }}
                </span>
            </div>
        </div>
        
        <!-- Amount Section -->
        <div class="amount-section">
            <div class="amount-row">
                <span class="info-label">Total Harga Booking</span>
                <span class="info-value">Rp {{ number_format($payment->booking->total_price, 0, ',', '.') }}</span>
            </div>
            
            <div class="amount-row total-amount">
                <span class="info-label">Total Pembayaran</span>
                <span class="info-value">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
            </div>
        </div>
        
        <div style="background: #e0f2fe; padding: 15px; border-left: 4px solid #0284c7; margin: 20px 0;">
            <p style="margin: 0; color: #0c4a6e; font-size: 14px;">
                <strong>Terbilang:</strong> 
                {{ ucwords(terbilang($payment->amount)) }} Rupiah
            </p>
        </div>
        
        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-box">
                <p style="margin-bottom: 10px;">Penyewa,</p>
                <div class="signature-line">
                    {{ $payment->booking->user->name }}
                </div>
            </div>
            
            <div class="signature-box">
                <p style="margin-bottom: 10px;">Pemilik Kost,</p>
                <div class="signature-line">
                    {{ $payment->booking->room->boardingHouse->user->name ?? 'Mitra LIVORA' }}
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p style="color: #6b7280; font-size: 12px;">
                Dokumen ini dicetak secara otomatis oleh sistem LIVORA<br>
                Tanggal Cetak: {{ now()->format('d F Y, H:i:s') }}
            </p>
            <p style="color: #6b7280; font-size: 12px; margin-top: 10px;">
                Kwitansi ini sah dan dapat digunakan sebagai bukti pembayaran
            </p>
        </div>
    </div>
</body>
</html>

@php
function terbilang($angka) {
    $angka = abs($angka);
    $baca = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
    $terbilang = "";
    
    if ($angka < 12) {
        $terbilang = " " . $baca[$angka];
    } else if ($angka < 20) {
        $terbilang = terbilang($angka - 10) . " Belas";
    } else if ($angka < 100) {
        $terbilang = terbilang($angka / 10) . " Puluh" . terbilang($angka % 10);
    } else if ($angka < 200) {
        $terbilang = " Seratus" . terbilang($angka - 100);
    } else if ($angka < 1000) {
        $terbilang = terbilang($angka / 100) . " Ratus" . terbilang($angka % 100);
    } else if ($angka < 2000) {
        $terbilang = " Seribu" . terbilang($angka - 1000);
    } else if ($angka < 1000000) {
        $terbilang = terbilang($angka / 1000) . " Ribu" . terbilang($angka % 1000);
    } else if ($angka < 1000000000) {
        $terbilang = terbilang($angka / 1000000) . " Juta" . terbilang($angka % 1000000);
    } else if ($angka < 1000000000000) {
        $terbilang = terbilang($angka / 1000000000) . " Milyar" . terbilang(fmod($angka, 1000000000));
    } else if ($angka < 1000000000000000) {
        $terbilang = terbilang($angka / 1000000000000) . " Trilyun" . terbilang(fmod($angka, 1000000000000));
    }
    
    return trim($terbilang);
}
@endphp
