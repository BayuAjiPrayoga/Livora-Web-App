<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan LIVORA - {{ date('d/m/Y') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #FF6900;
        }
        
        .header h1 {
            color: #FF6900;
            font-size: 28px;
            margin-bottom: 5px;
        }
        
        .header p {
            color: #666;
            font-size: 14px;
        }
        
        .info-box {
            background: #f9f9f9;
            padding: 10px;
            margin-bottom: 10px;
            border-left: 4px solid #FF6900;
        }
        
        .info-box strong {
            color: #FF6900;
        }
        
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        
        .stat-card {
            display: table-cell;
            width: 25%;
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
            background: #f9f9f9;
        }
        
        .stat-card .label {
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .stat-card .value {
            font-size: 20px;
            font-weight: bold;
            color: #FF6900;
        }
        
        .stat-card .growth {
            font-size: 10px;
            margin-top: 3px;
        }
        
        .stat-card .growth.positive {
            color: #10B981;
        }
        
        .stat-card .growth.negative {
            color: #EF4444;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin: 25px 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #FF6900;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        table th {
            background: #FF6900;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 11px;
        }
        
        table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }
        
        table tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        .revenue-trend {
            margin-top: 20px;
        }
        
        .trend-item {
            padding: 8px;
            border-bottom: 1px solid #eee;
            display: table;
            width: 100%;
        }
        
        .trend-item .month {
            display: table-cell;
            width: 30%;
            color: #666;
        }
        
        .trend-item .amount {
            display: table-cell;
            font-weight: bold;
            text-align: right;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>LIVORA</h1>
        <p>Laporan & Analitik Properti</p>
        <p>Periode: {{ date('F Y') }}</p>
    </div>
    
    <!-- Information Box -->
    <div class="info-box">
        <strong>Tanggal Cetak:</strong> {{ date('d F Y, H:i') }} WIB
    </div>
    
    <!-- Statistics Cards -->
    <h2 class="section-title">Ringkasan Statistik</h2>
    <div class="stats-grid">
        <div class="stat-card">
            <div class="label">Pendapatan Bulan Ini</div>
            <div class="value">Rp {{ number_format($stats['current_revenue'], 0, ',', '.') }}</div>
            @if($stats['revenue_growth'] > 0)
                <div class="growth positive">+{{ number_format($stats['revenue_growth'], 1) }}%</div>
            @elseif($stats['revenue_growth'] < 0)
                <div class="growth negative">{{ number_format($stats['revenue_growth'], 1) }}%</div>
            @endif
        </div>
        
        <div class="stat-card">
            <div class="label">Booking Bulan Ini</div>
            <div class="value">{{ $stats['current_bookings'] }}</div>
            @if($stats['booking_growth'] > 0)
                <div class="growth positive">+{{ number_format($stats['booking_growth'], 1) }}%</div>
            @elseif($stats['booking_growth'] < 0)
                <div class="growth negative">{{ number_format($stats['booking_growth'], 1) }}%</div>
            @endif
        </div>
        
        <div class="stat-card">
            <div class="label">Tingkat Okupansi</div>
            <div class="value">{{ number_format($stats['occupancy_rate'], 1) }}%</div>
            <div class="growth">{{ $stats['occupied_rooms'] }} / {{ $stats['total_rooms'] }} kamar</div>
        </div>
        
        <div class="stat-card">
            <div class="label">Total Properti</div>
            <div class="value">{{ $stats['total_properties'] }}</div>
            <div class="growth">{{ $stats['total_rooms'] }} kamar total</div>
        </div>
    </div>
    
    <!-- Top Properties -->
    <h2 class="section-title">Properti Terbaik</h2>
    @if(count($topProperties) > 0)
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="35%">Nama Properti</th>
                    <th width="15%">Total Kamar</th>
                    <th width="15%">Booking</th>
                    <th width="30%">Total Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topProperties as $index => $property)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $property->name }}</td>
                        <td>{{ $property->total_rooms ?? 0 }}</td>
                        <td>{{ $property->bookings_count ?? 0 }}</td>
                        <td>Rp {{ number_format($property->total_revenue ?? 0, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="text-align: center; color: #999; padding: 20px;">Belum ada data properti</p>
    @endif
    
    <!-- Revenue Trend -->
    <h2 class="section-title">Tren Pendapatan (6 Bulan Terakhir)</h2>
    <div class="revenue-trend">
        @if(isset($revenueTrend) && is_array($revenueTrend))
            @foreach($revenueTrend as $month => $revenue)
                <div class="trend-item">
                    <span class="month">{{ $month }}</span>
                    <span class="amount">Rp {{ number_format((float)$revenue, 0, ',', '.') }}</span>
                </div>
            @endforeach
        @else
            <p style="text-align: center; color: #999; padding: 20px;">Belum ada data revenue</p>
        @endif
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini digenerate otomatis oleh sistem LIVORA</p>
        <p>&copy; {{ date('Y') }} LIVORA - Platform Manajemen Kos Modern</p>
    </div>
</body>
</html>
