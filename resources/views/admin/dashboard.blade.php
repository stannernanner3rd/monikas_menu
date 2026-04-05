<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Monika's Kitchen</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        @keyframes fadeUp { from{opacity:0;transform:translateY(18px);}to{opacity:1;transform:translateY(0);} }
        .fade-up { animation: fadeUp 0.5s ease both; }
        .fade-up-1 { animation: fadeUp 0.5s 0.1s ease both; }
        .fade-up-2 { animation: fadeUp 0.5s 0.2s ease both; }
    </style>
</head>
<body class="bg-slate-50 antialiased">

{{-- Sidebar Admin — sekarang pakai komponen reusable --}}
<x-admin-sidebar active="dashboard" />

{{-- ===== MAIN CONTENT ===== --}}
<div class="md:pl-64 min-h-screen">
    <div class="px-4 md:px-10 py-6 md:py-10 max-w-6xl">

        {{-- Header --}}
        <div class="fade-up mb-8">
            <p class="text-orange-500 text-xs font-bold uppercase tracking-widest mb-1">Overview</p>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900">Dashboard</h1>
            <p class="text-slate-400 text-sm mt-1">Ringkasan penjualan dan performa toko hari ini.</p>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 fade-up">
            @php
                $stats = [
                    ['label' => 'Total Penjualan', 'value' => 'Rp 4.250.000', 'change' => '+12%', 'icon' => '💰', 'color' => 'from-orange-400 to-red-400'],
                    ['label' => 'Jumlah Pesanan', 'value' => '127', 'change' => '+8%', 'icon' => '📦', 'color' => 'from-blue-400 to-cyan-400'],
                    ['label' => 'Produk Aktif', 'value' => '42', 'change' => '+3', 'icon' => '🍽️', 'color' => 'from-green-400 to-emerald-400'],
                    ['label' => 'Rating Rata-Rata', 'value' => '4.8', 'change' => '+0.2', 'icon' => '⭐', 'color' => 'from-yellow-400 to-amber-400'],
                ];
            @endphp
            @foreach($stats as $stat)
            <div class="bg-white rounded-2xl p-4 md:p-5 shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $stat['color'] }} flex items-center justify-center text-lg shadow-sm">{{ $stat['icon'] }}</div>
                    <span class="text-green-600 bg-green-50 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $stat['change'] }}</span>
                </div>
                <p class="text-xl md:text-2xl font-black text-slate-900">{{ $stat['value'] }}</p>
                <p class="text-slate-400 text-[11px] font-semibold mt-0.5">{{ $stat['label'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Chart + Recent Orders --}}
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-4 mt-6 fade-up-1">

            {{-- Sales Chart (CSS-only) --}}
            <div class="lg:col-span-3 bg-white rounded-2xl p-5 md:p-6 shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h2 class="font-extrabold text-slate-900">Penjualan Minggu Ini</h2>
                        <p class="text-slate-400 text-xs mt-0.5">Total: Rp 29.750.000 · 127 pesanan</p>
                    </div>
                    <span class="text-orange-500 bg-orange-50 text-xs font-bold px-3 py-1 rounded-full">7 hari</span>
                </div>
                @php
                    $days = [
                        ['day' => 'Sen', 'val' => 85, 'revenue' => '4.2 jt', 'orders' => 18],
                        ['day' => 'Sel', 'val' => 62, 'revenue' => '3.1 jt', 'orders' => 14],
                        ['day' => 'Rab', 'val' => 90, 'revenue' => '4.5 jt', 'orders' => 21],
                        ['day' => 'Kam', 'val' => 55, 'revenue' => '2.8 jt', 'orders' => 11],
                        ['day' => 'Jum', 'val' => 100,'revenue' => '5.1 jt', 'orders' => 24],
                        ['day' => 'Sab', 'val' => 78, 'revenue' => '3.9 jt', 'orders' => 19],
                        ['day' => 'Min', 'val' => 95, 'revenue' => '4.8 jt', 'orders' => 20],
                    ];
                @endphp
                <div class="flex items-end justify-between gap-2 h-44">
                    @foreach($days as $d)
                    <div class="flex-1 flex flex-col items-center gap-1 group relative">
                        {{-- Value label --}}
                        <span class="text-[9px] font-bold text-orange-600 opacity-0 group-hover:opacity-100 transition whitespace-nowrap">Rp {{ $d['revenue'] }}</span>
                        {{-- Bar --}}
                        <div class="w-full rounded-xl transition-all group-hover:shadow-lg group-hover:scale-105 cursor-pointer"
                             style="height: {{ $d['val'] }}%; background: linear-gradient(to top, #ea580c, #f97316);"
                             title="{{ $d['day'] }}: Rp {{ $d['revenue'] }} ({{ $d['orders'] }} pesanan)"></div>
                        {{-- Day label --}}
                        <span class="text-[10px] font-bold text-slate-400">{{ $d['day'] }}</span>
                        {{-- Order count --}}
                        <span class="text-[9px] font-semibold text-slate-300">{{ $d['orders'] }}x</span>
                    </div>
                    @endforeach
                </div>
                {{-- Legend --}}
                <div class="flex items-center justify-center gap-6 mt-4 pt-3 border-t border-slate-50">
                    <div class="flex items-center gap-1.5">
                        <div class="w-2.5 h-2.5 rounded bg-gradient-to-t from-orange-600 to-orange-400"></div>
                        <span class="text-[10px] font-semibold text-slate-400">Revenue per hari</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="text-[10px] font-semibold text-slate-300">Nx</span>
                        <span class="text-[10px] font-semibold text-slate-400">Jumlah pesanan</span>
                    </div>
                </div>
            </div>

            {{-- Recent Orders --}}
            <div class="lg:col-span-2 bg-white rounded-2xl p-5 md:p-6 shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-extrabold text-slate-900">Pesanan Terbaru</h2>
                    <a href="{{ url('/orders') }}" class="text-orange-500 text-xs font-bold hover:underline">Lihat Semua</a>
                </div>
                <div class="space-y-3">
                    @php
                        $orders = [
                            ['id' => '#MK-2147', 'item' => 'Pizza Margherita x2', 'total' => 'Rp 130.000', 'status' => 'Diproses',    'color' => 'bg-orange-100 text-orange-700'],
                            ['id' => '#MK-2146', 'item' => 'Nasi Ayam Geprek',    'total' => 'Rp 28.000',  'status' => 'Dikirim',     'color' => 'bg-blue-100 text-blue-700'],
                            ['id' => '#MK-2145', 'item' => 'Spaghetti Carbonara', 'total' => 'Rp 45.000',  'status' => 'Selesai',     'color' => 'bg-green-100 text-green-700'],
                            ['id' => '#MK-2144', 'item' => 'Ice Matcha Latte x3', 'total' => 'Rp 75.000',  'status' => 'Selesai',     'color' => 'bg-green-100 text-green-700'],
                            ['id' => '#MK-2143', 'item' => 'French Fries x4',     'total' => 'Rp 60.000',  'status' => 'Dibatalkan',  'color' => 'bg-red-100 text-red-700'],
                        ];
                    @endphp
                    @foreach($orders as $order)
                    <div class="flex items-center justify-between py-2 border-b border-slate-50 last:border-0">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-bold text-slate-800 truncate">{{ $order['item'] }}</p>
                            <p class="text-[11px] text-slate-400">{{ $order['id'] }} · {{ $order['total'] }}</p>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full flex-shrink-0 {{ $order['color'] }}">{{ $order['status'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Popular Items --}}
        <div class="mt-6 bg-white rounded-2xl p-5 md:p-6 shadow-sm border border-slate-100 fade-up-2">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-extrabold text-slate-900">Menu Terlaris Minggu Ini</h2>
                <a href="{{ url('/admin/menu') }}" class="text-orange-500 text-xs font-bold hover:underline">Kelola Menu</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                @php
                    $popular = [
                        ['name' => 'Nasi Ayam Geprek', 'sold' => 142, 'revenue' => 'Rp 3.976.000', 'img' => 'https://images.unsplash.com/photo-1632778149955-e80f8ceca2e8?auto=format&fit=crop&w=200&q=80'],
                        ['name' => 'Pizza Meat Lovers', 'sold' => 98, 'revenue' => 'Rp 8.330.000', 'img' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?auto=format&fit=crop&w=200&q=80'],
                        ['name' => 'Ice Matcha Latte', 'sold' => 87, 'revenue' => 'Rp 2.175.000', 'img' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=200&q=80'],
                    ];
                @endphp
                @foreach($popular as $idx => $p)
                <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="relative flex-shrink-0">
                        <img src="{{ $p['img'] }}" class="w-12 h-12 rounded-xl object-cover" alt="{{ $p['name'] }}">
                        <span class="absolute -top-1 -left-1 w-5 h-5 bg-orange-500 text-white text-[10px] font-black rounded-full flex items-center justify-center">{{ $idx + 1 }}</span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-bold text-slate-800 truncate">{{ $p['name'] }}</p>
                        <p class="text-[11px] text-slate-400">{{ $p['sold'] }} terjual · {{ $p['revenue'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

</body>
</html>
