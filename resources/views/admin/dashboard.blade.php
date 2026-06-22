<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Monika's Kitchen</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

<x-admin-sidebar active="dashboard" />

<div class="md:pl-64 min-h-screen">
    <div class="px-4 md:px-10 py-6 md:py-10 max-w-6xl">

        {{-- Header --}}
        <div class="fade-up mb-8 flex items-center justify-between">
            <div>
                <p class="text-orange-500 text-xs font-bold uppercase tracking-widest mb-1">Overview</p>
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900">Dashboard</h1>
                <p class="text-slate-400 text-sm mt-1">Halo, {{ session('admin_name', 'Admin') }}! Ini ringkasan toko.</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <select id="period-select" onchange="window.location.href='{{ url('/admin') }}?period='+this.value"
                        class="px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 focus:ring-2 focus:ring-orange-500 outline-none shadow-sm">
                    <option value="hari_ini" {{ $period === 'hari_ini' ? 'selected' : '' }}>📅 Hari Ini</option>
                    <option value="kemarin" {{ $period === 'kemarin' ? 'selected' : '' }}>⏪ Kemarin</option>
                    <option value="7hari" {{ $period === '7hari' ? 'selected' : '' }}>📆 7 Hari</option>
                    <option value="bulan_ini" {{ $period === 'bulan_ini' ? 'selected' : '' }}>🗓️ Bulan Ini</option>
                    <option value="semua" {{ $period === 'semua' ? 'selected' : '' }}>📊 Semua</option>
                </select>
                <form method="POST" action="{{ url('/logout') }}">
                    @csrf
                    <button class="text-xs font-bold text-slate-400 hover:text-red-500 transition px-3 py-1.5 rounded-lg border border-slate-200 hover:border-red-200">
                        🚪 Logout
                    </button>
                </form>
            </div>
        </div>

        {{-- Stats Cards — REAL DATA --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 fade-up">
            @php
                $stats = [
                    ['label' => 'Total Penjualan', 'value' => 'Rp ' . number_format($totalPenjualan, 0, ',', '.'), 'icon' => '💰', 'color' => 'from-orange-400 to-red-400'],
                    ['label' => 'Jumlah Pesanan', 'value' => $jumlahPesanan, 'icon' => '📦', 'color' => 'from-blue-400 to-cyan-400'],
                    ['label' => 'Produk Aktif', 'value' => $produkAktif, 'icon' => '🍽️', 'color' => 'from-green-400 to-emerald-400'],
                    ['label' => 'Pelanggan Hari Ini', 'value' => $pelangganHariIni, 'icon' => '👥', 'color' => 'from-purple-400 to-violet-400'],
                ];
            @endphp
            @foreach($stats as $stat)
            <div class="bg-white rounded-2xl p-4 md:p-5 shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $stat['color'] }} flex items-center justify-center text-lg shadow-sm">{{ $stat['icon'] }}</div>
                    <span class="text-slate-400 bg-slate-50 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $periodLabel }}</span>
                </div>
                <p class="text-xl md:text-2xl font-black text-slate-900">{{ $stat['value'] }}</p>
                <p class="text-slate-400 text-[11px] font-semibold mt-0.5">{{ $stat['label'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Chart + Recent Orders --}}
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-4 mt-6 fade-up-1">

            {{-- Sales Chart — REAL DATA --}}
            <div class="lg:col-span-3 bg-white rounded-2xl p-5 md:p-6 shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h2 class="font-extrabold text-slate-900">Penjualan 7 Hari</h2>
                        @php
                            $totalWeekRev = array_sum(array_column($chartData, 'revenue'));
                            $totalWeekOrd = array_sum(array_column($chartData, 'orders'));
                        @endphp
                        <p class="text-slate-400 text-xs mt-0.5">Total: Rp {{ number_format($totalWeekRev, 0, ',', '.') }} · {{ $totalWeekOrd }} pesanan</p>
                    </div>
                    <span class="text-orange-500 bg-orange-50 text-xs font-bold px-3 py-1 rounded-full">7 hari</span>
                </div>
                <div class="flex items-end justify-between gap-2" style="height: 200px;">
                    @foreach($chartData as $d)
                    @php $pct = $maxRev > 0 ? max(4, round(($d['revenue'] / $maxRev) * 100)) : 4; @endphp
                    <div class="flex-1 flex flex-col items-center group relative h-full">
                        {{-- Tooltip on hover --}}
                        <span class="text-[9px] font-bold text-orange-600 opacity-0 group-hover:opacity-100 transition whitespace-nowrap mb-1">
                            Rp {{ number_format($d['revenue'], 0, ',', '.') }}
                        </span>
                        {{-- Bar wrapper: takes remaining space, aligns bar to bottom --}}
                        <div class="flex-1 w-full flex items-end justify-center">
                            <div class="w-full max-w-[40px] rounded-xl transition-all duration-300 group-hover:shadow-lg group-hover:scale-105 cursor-pointer"
                                 style="height: {{ $pct }}%; min-height: 6px; background: linear-gradient(to top, #ea580c, #f97316);"
                                 title="{{ $d['day'] }}: Rp {{ number_format($d['revenue'], 0, ',', '.') }} ({{ $d['orders'] }} pesanan)"></div>
                        </div>
                        {{-- Labels --}}
                        <span class="text-[10px] font-bold text-slate-400 mt-1.5">{{ $d['day'] }}</span>
                        <span class="text-[9px] font-semibold text-slate-300">{{ $d['orders'] }}x</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Recent Orders — REAL DATA --}}
            <div class="lg:col-span-2 bg-white rounded-2xl p-5 md:p-6 shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-extrabold text-slate-900">Pesanan Terbaru</h2>
                    <a href="{{ url('/admin/pesanan') }}" class="text-orange-500 text-xs font-bold hover:underline">Lihat Semua</a>
                </div>
                <div class="space-y-3">
                    @forelse($pesananTerbaru as $order)
                    @php
                        $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-700',
                            'diproses' => 'bg-blue-100 text-blue-700',
                            'selesai' => 'bg-green-100 text-green-700',
                            'batal' => 'bg-red-100 text-red-700',
                        ];
                        $firstItem = $order->detail->first();
                        $itemLabel = $firstItem && $firstItem->menu ? $firstItem->menu->nama : 'Item';
                        if ($order->detail->count() > 1) $itemLabel .= ' +' . ($order->detail->count()-1);
                    @endphp
                    <div class="flex items-center justify-between py-2 border-b border-slate-50 last:border-0">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-bold text-slate-800 truncate">{{ $itemLabel }}</p>
                            <p class="text-[11px] text-slate-400">#{{ $order->id }} · {{ $order->nama_pemesan }} · Rp {{ number_format($order->total_harga, 0, ',', '.') }}</p>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full flex-shrink-0 {{ $statusColors[$order->status] ?? 'bg-slate-100 text-slate-500' }}">{{ ucfirst($order->status) }}</span>
                    </div>
                    @empty
                    <p class="text-sm text-slate-400 text-center py-4">Belum ada pesanan.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Popular Items — REAL DATA --}}
        <div class="mt-6 bg-white rounded-2xl p-5 md:p-6 shadow-sm border border-slate-100 fade-up-2">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-extrabold text-slate-900">Menu Terlaris Minggu Ini</h2>
                <a href="{{ url('/admin/menu-test') }}" class="text-orange-500 text-xs font-bold hover:underline">Kelola Menu</a>
            </div>
            @if($menuTerlaris->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                @foreach($menuTerlaris as $idx => $p)
                @php
                    $imgUrl = $p->gambar ? asset('storage/' . $p->gambar) : 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=200&q=80';
                @endphp
                <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="relative flex-shrink-0">
                        <img src="{{ $imgUrl }}" class="w-12 h-12 rounded-xl object-cover" alt="{{ $p->nama }}">
                        <span class="absolute -top-1 -left-1 w-5 h-5 bg-orange-500 text-white text-[10px] font-black rounded-full flex items-center justify-center">{{ $idx + 1 }}</span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-bold text-slate-800 truncate">{{ $p->nama }}</p>
                        <p class="text-[11px] text-slate-400">{{ $p->sold }} terjual · Rp {{ number_format($p->revenue, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-sm text-slate-400 text-center py-4">Belum ada data penjualan minggu ini.</p>
            @endif
        </div>

    </div>
</div>

</body>
</html>
