<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Monika's Kitchen</title>
    <meta name="description" content="Lihat semua menu lezat Monika's Kitchen — makanan, minuman, snack, dan pasta pilihan terbaik.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Menu card */
        .menu-card { transition: transform 0.25s ease, box-shadow 0.25s ease, opacity 0.3s ease; }
        .menu-card:hover { transform: translateY(-4px); box-shadow: 0 16px 32px rgba(249,115,22,0.15); }

        /* Add button */
        .add-btn { transition: background 0.2s, transform 0.2s; }
        .add-btn:hover { transform: scale(1.12); }

        /* Category pill active */
        .cat-pill { transition: all 0.2s ease; }
        .cat-pill.active { background: #ea580c; color: #fff; box-shadow: 0 4px 14px rgba(234,88,12,0.35); }

        /* Filter hidden */
        .menu-card.hidden-item { display: none; }

        /* Section fade-in */
        @keyframes fadeUp { from { opacity:0; transform:translateY(18px); } to { opacity:1; transform:translateY(0); } }
        .fade-up { animation: fadeUp 0.55s ease both; }

        /* Badge pulse */
        @keyframes pulse-badge { 0%,100% { transform:scale(1); } 50% { transform:scale(1.18); } }
        .badge-pulse { animation: pulse-badge 2s infinite; }

        /* Cart count scale */
        .scale-pop { animation: scalePop 0.2s ease; }
        @keyframes scalePop { 0% { transform:scale(1); } 50% { transform:scale(1.4); } 100% { transform:scale(1); } }
    </style>
</head>

<body class="bg-slate-50 antialiased">
<div class="min-h-screen pb-28 md:pb-10 md:pl-64">
    <x-navibar />

    <main class="max-w-lg mx-auto md:max-w-none md:mx-0 md:px-10 md:py-8">

        {{-- ===== PAGE HEADER ===== --}}
        <section class="px-4 mt-6 fade-up">
            <div class="flex items-end justify-between">
                <div>
                    <p class="text-orange-500 text-xs font-bold uppercase tracking-widest mb-1">Monika's Kitchen</p>
                    <h1 class="text-2xl font-extrabold text-slate-900">Semua Menu</h1>
                    <p class="text-slate-400 text-sm mt-0.5">{{ count($menus ?? []) > 0 ? count($menus) : 6 }} item tersedia hari ini</p>
                </div>
                {{-- Sort button --}}
                <button class="flex items-center gap-1.5 text-slate-600 text-sm font-semibold bg-white px-4 py-2 rounded-full shadow-sm border border-slate-100 hover:border-orange-400 hover:text-orange-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/></svg>
                    Urutkan
                </button>
            </div>
        </section>

        {{-- ===== CATEGORY FILTER ===== --}}
        <section class="px-4 mt-5 fade-up">
            <div class="flex gap-2.5 overflow-x-auto no-scrollbar pb-1">
                @php
                    $categories = ['Semua', 'Pizza', 'Nasi Ayam', 'Minuman', 'Snack', 'Pasta'];

                    $menus = [
                        ['name' => 'Pizza Margherita',    'price' => '65.000', 'cat' => 'Pizza',    'rating' => '4.8', 'sold' => '180+', 'img' => 'https://images.unsplash.com/photo-1604382354936-07c5d9983bd3?auto=format&fit=crop&w=400&q=80'],
                        ['name' => 'Nasi Ayam Bakar',     'price' => '35.000', 'cat' => 'Nasi Ayam','rating' => '4.9', 'sold' => '410+', 'img' => 'https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?auto=format&fit=crop&w=400&q=80'],
                        ['name' => 'Es Teh Manis',        'price' => '5.000',  'cat' => 'Minuman',  'rating' => '4.6', 'sold' => '320+', 'img' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=400&q=80'],
                        ['name' => 'French Fries',        'price' => '15.000', 'cat' => 'Snack',    'rating' => '4.7', 'sold' => '210+', 'img' => 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?auto=format&fit=crop&w=400&q=80'],
                        ['name' => 'Spaghetti Carbonara', 'price' => '45.000', 'cat' => 'Pasta',    'rating' => '4.8', 'sold' => '150+', 'img' => 'https://images.unsplash.com/photo-1612450800052-759c5509b58e?auto=format&fit=crop&w=400&q=80'],
                        ['name' => 'Pizza Meat Lovers',   'price' => '85.000', 'cat' => 'Pizza',    'rating' => '4.9', 'sold' => '290+', 'img' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?auto=format&fit=crop&w=400&q=80'],
                        ['name' => 'Nasi Ayam Geprek',    'price' => '28.000', 'cat' => 'Nasi Ayam','rating' => '4.8', 'sold' => '510+', 'img' => 'https://images.unsplash.com/photo-1632778149955-e80f8ceca2e8?auto=format&fit=crop&w=400&q=80'],
                        ['name' => 'Ice Matcha Latte',    'price' => '25.000', 'cat' => 'Minuman',  'rating' => '4.7', 'sold' => '190+', 'img' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=400&q=80'],
                    ];
                @endphp

                <button id="cat-all"
                    class="cat-pill active px-5 py-2 rounded-full bg-orange-600 text-white text-sm font-bold shadow whitespace-nowrap"
                    data-cat="Semua">
                    Semua
                </button>
                @foreach(array_slice($categories, 1) as $cat)
                <button class="cat-pill px-5 py-2 rounded-full bg-white text-slate-600 text-sm font-bold border border-slate-100 hover:border-orange-400 hover:text-orange-600 whitespace-nowrap shadow-sm"
                    data-cat="{{ $cat }}">
                    {{ $cat }}
                </button>
                @endforeach
            </div>
        </section>

        {{-- ===== MENU GRID ===== --}}
        <section class="px-4 mt-5 fade-up">
            <div id="menu-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach($menus as $i => $menu)
                <div class="menu-card bg-white rounded-2xl p-2.5 shadow-sm border border-slate-100 group cursor-pointer"
                     data-category="{{ $menu['cat'] }}">

                    {{-- Image --}}
                    <div class="relative mb-2.5 overflow-hidden rounded-xl">
                        <img src="{{ $menu['img'] }}" alt="{{ $menu['name'] }}"
                             class="w-full h-32 md:h-40 object-cover group-hover:scale-105 transition-transform duration-300">
                        {{-- Category badge --}}
                        <span class="absolute top-2 right-2 bg-white/90 backdrop-blur-sm text-orange-600 text-[10px] font-bold px-2 py-0.5 rounded-lg shadow-sm">
                            {{ $menu['cat'] }}
                        </span>
                        {{-- Rating --}}
                        <span class="absolute bottom-2 left-2 bg-black/50 backdrop-blur-sm text-yellow-400 text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-0.5">
                            ⭐ {{ $menu['rating'] }}
                        </span>
                    </div>

                    {{-- Info --}}
                    <h3 class="text-slate-800 font-bold text-sm leading-tight group-hover:text-orange-600 transition line-clamp-1 px-0.5">
                        {{ $menu['name'] }}
                    </h3>
                    <p class="text-slate-400 text-[11px] px-0.5 mt-0.5">{{ $menu['sold'] }} terjual</p>

                    {{-- Price + Add --}}
                    <div class="flex justify-between items-center mt-2 px-0.5">
                        <p class="text-orange-600 font-black text-sm">Rp {{ $menu['price'] }}</p>
                        <button id="add-menu-{{ $i }}" class="add-btn bg-slate-900 hover:bg-orange-600 p-2 rounded-xl text-white shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Empty state --}}
            <div id="empty-state" class="hidden text-center py-16">
                <div class="text-5xl mb-4">🍽️</div>
                <p class="text-slate-500 font-semibold">Tidak ada menu dalam kategori ini.</p>
                <p class="text-slate-400 text-sm mt-1">Coba pilih kategori lain.</p>
            </div>
        </section>

    </main>
</div>



{{-- ===== JAVASCRIPT ===== --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ---- Category Filter ----
    const pills     = document.querySelectorAll('.cat-pill');
    const cards     = document.querySelectorAll('.menu-card');
    const emptyEl   = document.getElementById('empty-state');

    pills.forEach(pill => {
        pill.addEventListener('click', function () {
            // Toggle active style
            pills.forEach(p => {
                p.classList.remove('active', 'bg-orange-600', 'text-white', 'shadow');
                p.classList.add('bg-white', 'text-slate-600', 'border', 'border-slate-100');
            });
            this.classList.add('active', 'bg-orange-600', 'text-white', 'shadow');
            this.classList.remove('bg-white', 'text-slate-600', 'border', 'border-slate-100');

            const selected = this.getAttribute('data-cat');
            let visible = 0;

            cards.forEach(card => {
                const match = selected === 'Semua' || card.getAttribute('data-category') === selected;
                if (match) {
                    card.style.display = '';
                    visible++;
                } else {
                    card.style.display = 'none';
                }
            });

            emptyEl.classList.toggle('hidden', visible > 0);
        });
    });

    // ---- Cart Counter ----
    let cartCount = 0;
    const cartCountEl = document.getElementById('cart-count');

    function addToCart(btn) {
        cartCount++;
        cartCountEl.textContent = cartCount;
        cartCountEl.classList.add('scale-pop');
        setTimeout(() => cartCountEl.classList.remove('scale-pop'), 200);

        // Green flash on button
        btn.style.background = '#16a34a';
        setTimeout(() => btn.style.background = '', 500);
    }

    document.querySelectorAll('[id^="add-menu-"]').forEach(btn => {
        btn.addEventListener('click', e => {
            e.stopPropagation();
            addToCart(btn);
        });
    });
});
</script>

</body>
</html>