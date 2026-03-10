<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya - Monika's Kitchen</title>
    <meta name="description" content="Lihat riwayat dan status pesanan makananmu di Monika's Kitchen.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Fade-in animation */
        @keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
        .fade-up   { animation: fadeUp 0.5s ease both; }
        .fade-up-1 { animation: fadeUp 0.5s 0.08s ease both; }
        .fade-up-2 { animation: fadeUp 0.5s 0.16s ease both; }
        .fade-up-3 { animation: fadeUp 0.5s 0.24s ease both; }

        /* Tab pill */
        .tab-pill { transition: all 0.2s ease; }
        .tab-pill.active { background: #ea580c; color: #fff; box-shadow: 0 4px 12px rgba(234,88,12,0.3); }

        /* Order card */
        .order-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .order-card:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(0,0,0,0.07); }

        /* Status badges */
        .badge-process  { background:#fff7ed; color:#c2410c; border:1px solid #fed7aa; }
        .badge-delivery { background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; }
        .badge-done     { background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; }
        .badge-canceled { background:#fff1f2; color:#be123c; border:1px solid #fecdd3; }

        /* Stepper dot */
        .step-done   { background:#ea580c; }
        .step-active { background:#ea580c; box-shadow:0 0 0 4px rgba(234,88,12,0.2); }
        .step-idle   { background:#e2e8f0; }
        .step-line-done { background:#ea580c; }
        .step-line-idle { background:#e2e8f0; }

        /* Reorder button */
        .reorder-btn { transition: background 0.2s, transform 0.2s; }
        .reorder-btn:hover { background:#ea580c; transform:scale(1.03); }

        /* Empty state */
        @keyframes floatBowl { 0%,100%{transform:translateY(0);} 50%{transform:translateY(-10px);} }
        .float-bowl { animation: floatBowl 3s ease-in-out infinite; }

        /* Card hidden */
        .order-card.hidden-card { display: none; }
    </style>
</head>

<body class="bg-slate-50 antialiased">
<div class="min-h-screen pb-28 md:pb-10 md:pl-64">

    <x-navibar />

    <main class="max-w-lg mx-auto px-4 md:max-w-none md:mx-0 md:px-10 md:py-8">

        {{-- ===== PAGE HEADER ===== --}}
        <section class="mt-6 fade-up">
            <p class="text-orange-500 text-xs font-bold uppercase tracking-widest mb-1">Monika's Kitchen</p>
            <h1 class="text-2xl font-extrabold text-slate-900">Pesanan Saya 🧾</h1>
            <p class="text-slate-400 text-sm mt-0.5">Pantau status dan riwayat pesananmu di sini.</p>
        </section>

        {{-- ===== SUMMARY CARDS ===== --}}
        <section class="mt-5 grid grid-cols-3 gap-3 fade-up-1">
            @php
                $stats = [
                    ['label' => 'Aktif',     'val' => '2',  'icon' => '🔥', 'bg' => 'bg-orange-50',  'text' => 'text-orange-600'],
                    ['label' => 'Selesai',   'val' => '12', 'icon' => '✅', 'bg' => 'bg-green-50',   'text' => 'text-green-600'],
                    ['label' => 'Dibatal',   'val' => '1',  'icon' => '❌', 'bg' => 'bg-slate-100',  'text' => 'text-slate-500'],
                ];
            @endphp
            @foreach($stats as $s)
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 text-center">
                <div class="text-2xl mb-1">{{ $s['icon'] }}</div>
                <p class="text-xl font-black {{ $s['text'] }}">{{ $s['val'] }}</p>
                <p class="text-xs text-slate-400 font-semibold mt-0.5">{{ $s['label'] }}</p>
            </div>
            @endforeach
        </section>

        {{-- ===== TAB FILTER ===== --}}
        <section class="mt-6 fade-up-2">
            <div class="flex gap-2 overflow-x-auto no-scrollbar pb-1">
                @foreach(['Semua', 'Diproses', 'Dikirim', 'Selesai', 'Dibatalkan'] as $tab)
                <button class="tab-pill {{ $loop->first ? 'active' : '' }} px-4 py-1.5 rounded-full text-sm font-bold bg-white border border-slate-100 text-slate-500 whitespace-nowrap shadow-sm"
                        data-tab="{{ $tab }}">
                    {{ $tab }}
                </button>
                @endforeach
            </div>
        </section>

        {{-- ===== ORDER CARDS ===== --}}
        <section class="mt-4 flex flex-col gap-4 fade-up-3" id="orders-list">

            @php
                $orders = [
                    [
                        'id'       => 'MK-20260309-001',
                        'date'     => '9 Mar 2026, 12:30',
                        'status'   => 'Diproses',
                        'badge'    => 'badge-process',
                        'step'     => 1,
                        'items'    => [
                            ['name' => 'Beef & Pineapple Pizza', 'qty' => 1, 'price' => '71.000', 'img' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?auto=format&fit=crop&w=100&q=80'],
                            ['name' => 'Ice Matcha Latte',       'qty' => 2, 'price' => '25.000', 'img' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=100&q=80'],
                        ],
                        'total'    => '121.000',
                        'eta'      => '~15 menit',
                    ],
                    [
                        'id'       => 'MK-20260309-002',
                        'date'     => '9 Mar 2026, 11:05',
                        'status'   => 'Dikirim',
                        'badge'    => 'badge-delivery',
                        'step'     => 2,
                        'items'    => [
                            ['name' => 'Nasi Ayam Geprek', 'qty' => 2, 'price' => '28.000', 'img' => 'https://images.unsplash.com/photo-1632778149955-e80f8ceca2e8?auto=format&fit=crop&w=100&q=80'],
                            ['name' => 'Es Teh Manis',     'qty' => 2, 'price' => '5.000',  'img' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=100&q=80'],
                        ],
                        'total'    => '66.000',
                        'eta'      => '~5 menit',
                    ],
                    [
                        'id'       => 'MK-20260307-019',
                        'date'     => '7 Mar 2026, 19:45',
                        'status'   => 'Selesai',
                        'badge'    => 'badge-done',
                        'step'     => 3,
                        'items'    => [
                            ['name' => 'Spaghetti Carbonara', 'qty' => 1, 'price' => '45.000', 'img' => 'https://images.unsplash.com/photo-1612450800052-759c5509b58e?auto=format&fit=crop&w=100&q=80'],
                            ['name' => 'French Fries',        'qty' => 1, 'price' => '15.000', 'img' => 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?auto=format&fit=crop&w=100&q=80'],
                        ],
                        'total'    => '60.000',
                        'eta'      => null,
                    ],
                    [
                        'id'       => 'MK-20260305-011',
                        'date'     => '5 Mar 2026, 13:10',
                        'status'   => 'Selesai',
                        'badge'    => 'badge-done',
                        'step'     => 3,
                        'items'    => [
                            ['name' => 'Double Cheeseburger', 'qty' => 1, 'price' => '60.000', 'img' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=100&q=80'],
                            ['name' => 'Jus Alpukat Susu',    'qty' => 1, 'price' => '18.000', 'img' => 'https://images.unsplash.com/photo-1623065422902-30a2d299bbe4?auto=format&fit=crop&w=100&q=80'],
                        ],
                        'total'    => '78.000',
                        'eta'      => null,
                    ],
                    [
                        'id'       => 'MK-20260301-003',
                        'date'     => '1 Mar 2026, 10:22',
                        'status'   => 'Dibatalkan',
                        'badge'    => 'badge-canceled',
                        'step'     => 0,
                        'items'    => [
                            ['name' => 'Grilled Salmon Steak', 'qty' => 1, 'price' => '95.000', 'img' => 'https://images.unsplash.com/photo-1467003909585-2f8a7270028d?auto=format&fit=crop&w=100&q=80'],
                        ],
                        'total'    => '95.000',
                        'eta'      => null,
                    ],
                ];

                $steps = ['Pesanan Masuk', 'Diproses', 'Dikirim', 'Selesai'];
            @endphp

            @foreach($orders as $order)
            <div class="order-card bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden"
                 data-status="{{ $order['status'] }}">

                {{-- Card Header --}}
                <div class="flex items-center justify-between px-4 py-3 border-b border-slate-50">
                    <div>
                        <p class="text-xs text-slate-400 font-medium">{{ $order['date'] }}</p>
                        <p class="text-sm font-bold text-slate-700 mt-0.5">{{ $order['id'] }}</p>
                    </div>
                    <span class="text-[11px] font-bold px-3 py-1 rounded-full {{ $order['badge'] }}">
                        {{ $order['status'] }}
                    </span>
                </div>

                {{-- Items --}}
                <div class="px-4 py-3 space-y-2.5">
                    @foreach($order['items'] as $item)
                    <div class="flex items-center gap-3">
                        <img src="{{ $item['img'] }}" alt="{{ $item['name'] }}"
                             class="w-12 h-12 rounded-xl object-cover flex-shrink-0">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-slate-800 truncate">{{ $item['name'] }}</p>
                            <p class="text-xs text-slate-400">{{ $item['qty'] }}x &nbsp;·&nbsp; Rp {{ $item['price'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Progress Stepper (only for active) --}}
                @if($order['step'] > 0 && $order['step'] < 3)
                <div class="px-4 pb-3">
                    <div class="flex items-center gap-0">
                        @foreach($steps as $si => $stepLabel)
                            {{-- Dot --}}
                            <div class="flex flex-col items-center relative z-10">
                                <div class="w-3 h-3 rounded-full flex-shrink-0
                                    {{ $si < $order['step'] ? 'step-done' : ($si === $order['step'] ? 'step-active' : 'step-idle') }}">
                                </div>
                                <p class="text-[9px] text-center mt-1 font-semibold w-14
                                    {{ $si <= $order['step'] ? 'text-orange-600' : 'text-slate-300' }}">
                                    {{ $stepLabel }}
                                </p>
                            </div>
                            {{-- Line (between dots) --}}
                            @if(!$loop->last)
                            <div class="flex-1 h-0.5 mb-4
                                {{ $si < $order['step'] ? 'step-line-done' : 'step-line-idle' }}">
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Card Footer --}}
                <div class="flex items-center justify-between px-4 py-3 bg-slate-50 border-t border-slate-100">
                    <div>
                        <p class="text-xs text-slate-400">Total Pesanan</p>
                        <p class="text-base font-black text-orange-600">Rp {{ $order['total'] }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($order['eta'])
                        <span class="text-xs text-slate-500 font-semibold bg-white border border-slate-200 px-3 py-1.5 rounded-full">
                            ⏱ ETA {{ $order['eta'] }}
                        </span>
                        @endif
                        @if($order['status'] === 'Selesai' || $order['status'] === 'Dibatalkan')
                        <a href="{{ url('/menu') }}"
                           class="reorder-btn bg-slate-900 text-white text-xs font-bold px-4 py-2 rounded-full shadow transition">
                            🔄 Pesan Lagi
                        </a>
                        @endif
                    </div>
                </div>

            </div>
            @endforeach

        </section>

        {{-- ===== EMPTY STATE ===== --}}
        <div id="empty-orders" class="hidden text-center py-20 fade-up">
            <div class="text-6xl float-bowl mb-5">🍜</div>
            <h3 class="text-slate-700 font-extrabold text-lg">Tidak ada pesanan</h3>
            <p class="text-slate-400 text-sm mt-1">Belum ada pesanan di kategori ini.</p>
            <a href="{{ url('/menu') }}"
               class="inline-block mt-6 bg-orange-500 text-white font-bold px-6 py-3 rounded-2xl shadow-lg hover:bg-orange-600 transition">
                🍽️ Mulai Pesan Sekarang
            </a>
        </div>

    </main>
</div>

{{-- ===== JAVASCRIPT ===== --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const tabs      = document.querySelectorAll('.tab-pill');
    const cards     = document.querySelectorAll('.order-card');
    const emptyEl   = document.getElementById('empty-orders');
    const listEl    = document.getElementById('orders-list');

    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            // Active tab style
            tabs.forEach(t => {
                t.classList.remove('active', 'bg-orange-600', 'text-white');
                t.classList.add('bg-white', 'text-slate-500', 'border-slate-100');
            });
            this.classList.add('active');
            this.classList.remove('bg-white', 'text-slate-500');

            const selected = this.getAttribute('data-tab');
            let visible = 0;

            cards.forEach(card => {
                const status = card.getAttribute('data-status');
                const match  = selected === 'Semua' || status === selected;
                card.style.display = match ? '' : 'none';
                if (match) visible++;
            });

            emptyEl.classList.toggle('hidden', visible > 0);
            listEl.style.display = visible > 0 ? '' : 'none';
        });
    });

});
</script>

</body>
</html>
