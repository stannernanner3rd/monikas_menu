<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Monika's Kitchen</title>
    <meta name="description" content="Lihat semua menu lezat Monika's Kitchen — pesan langsung dari database kami.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .menu-card { transition: transform 0.25s ease, box-shadow 0.25s ease; }
        .menu-card:hover { transform: translateY(-4px); box-shadow: 0 16px 32px rgba(249,115,22,0.15); }
        .add-btn { transition: background 0.2s, transform 0.2s; }
        .add-btn:hover { transform: scale(1.12); }
        @keyframes fadeUp { from { opacity:0; transform:translateY(18px); } to { opacity:1; transform:translateY(0); } }
        .fade-up { animation: fadeUp 0.55s ease both; }
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
                    <p class="text-slate-400 text-sm mt-0.5">{{ $menuDB->count() }} item tersedia</p>
                </div>
            </div>
        </section>

        {{-- Poin section removed — hidden from customer --}}

        {{-- ===== FILTER: DROPDOWN KATEGORI ===== --}}
        <section class="px-4 mt-4 fade-up">
            <div class="flex gap-3 items-center">
                <select id="cat-dropdown" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm font-bold text-slate-700 focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none shadow-sm appearance-none pr-10"
                        style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2220%22 height=%2220%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%2394a3b8%22 stroke-width=%222%22><path d=%22M6 9l6 6 6-6%22/></svg>'); background-repeat: no-repeat; background-position: right 8px center;">
                    <option value="Semua">Semua Kategori</option>
                    @foreach($kategoriDB as $k)
                        <option value="{{ $k->nama }}">{{ $k->nama }} ({{ $k->menu_count }})</option>
                    @endforeach
                </select>
                <input type="text" id="search-input" placeholder="Cari menu..."
                       class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none shadow-sm">
            </div>
        </section>

        {{-- ===== MENU GRID — DATA DARI DATABASE ===== --}}
        <section class="px-4 mt-5 fade-up">
            <div id="menu-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach($menuDB as $i => $menu)
                @php
                    $imgUrl = $menu->gambar
                        ? asset('storage/' . $menu->gambar)
                        : 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80';
                    $kategoriNama = $menu->kategori ? $menu->kategori->nama : 'Menu';
                    $hasPromo = !empty($menu->harga_promo);
                    $displayPrice = $hasPromo ? $menu->harga_promo : $menu->harga;
                @endphp
                <div class="menu-card bg-white rounded-2xl p-2.5 shadow-sm border border-slate-100 group cursor-pointer"
                     data-category="{{ $kategoriNama }}" data-name="{{ $menu->nama }}">

                    {{-- Image --}}
                    <div class="relative mb-2.5 overflow-hidden rounded-xl">
                        <img src="{{ $imgUrl }}" alt="{{ $menu->nama }}"
                             class="w-full h-32 md:h-40 object-cover group-hover:scale-105 transition-transform duration-300">

                        {{-- Category badge --}}
                        <span class="absolute top-2 right-2 bg-white/90 backdrop-blur-sm text-orange-600 text-[10px] font-bold px-2 py-0.5 rounded-lg shadow-sm">
                            {{ $kategoriNama }}
                        </span>

                        {{-- Promo badge --}}
                        @if($hasPromo)
                        <span class="absolute top-2 left-2 bg-red-500 text-white text-[9px] font-black px-2 py-0.5 rounded-lg shadow-sm animate-pulse">
                            🏷️ PROMO
                        </span>
                        @endif

                        {{-- Pre-order badge --}}
                        @if($menu->preorder_hari > 0)
                        <span class="absolute bottom-2 left-2 bg-blue-500/90 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full">
                            🕐 PO {{ $menu->preorder_hari }} hari
                        </span>
                        @endif

                        {{-- Catering badge --}}
                        @if($menu->catering_tersedia)
                        <span class="absolute bottom-2 right-2 bg-blue-500/90 backdrop-blur-sm text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full">
                            🍱 Catering
                        </span>
                        @endif
                    </div>

                    {{-- Info --}}
                    <h3 class="text-slate-800 font-bold text-sm leading-tight group-hover:text-orange-600 transition line-clamp-1 px-0.5">
                        {{ $menu->nama }}
                    </h3>
                    @if($menu->deskripsi)
                    <p class="text-slate-400 text-[11px] mt-0.5 px-0.5 line-clamp-1">{{ $menu->deskripsi }}</p>
                    @endif

                    {{-- Price + Add --}}
                    <div class="flex justify-between items-center mt-2 px-0.5">
                        <div>
                            @if($hasPromo)
                            <p class="text-slate-400 text-[10px] line-through">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                            <p class="text-red-600 font-black text-sm">Rp {{ number_format($menu->harga_promo, 0, ',', '.') }}</p>
                            @else
                            <p class="text-orange-600 font-black text-sm">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                            @endif
                        </div>
                        <button class="add-btn bg-slate-900 hover:bg-orange-600 p-2 rounded-xl text-white shadow-md"
                                data-item-id="{{ $menu->id }}"
                                data-item-name="{{ $menu->nama }}"
                                data-item-price="{{ $displayPrice }}"
                                data-item-img="{{ $imgUrl }}"
                                data-item-cat="{{ $kategoriNama }}"
                                data-item-poin="{{ $menu->poin ?? 0 }}"
                                data-item-catering="{{ $menu->catering_tersedia ? '1' : '0' }}"
                                data-item-preorder="{{ $menu->preorder_hari ?? 0 }}">
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
    const dropdown    = document.getElementById('cat-dropdown');
    const searchInput = document.getElementById('search-input');
    const cards       = document.querySelectorAll('.menu-card');
    const emptyEl     = document.getElementById('empty-state');

    const urlParams   = new URLSearchParams(window.location.search);
    const searchQuery = urlParams.get('search') || '';
    const catQuery    = urlParams.get('cat') || '';
    if (searchQuery) searchInput.value = searchQuery;
    if (catQuery) {
        // Set dropdown to matching category
        for (let opt of dropdown.options) {
            if (opt.value === catQuery) { dropdown.value = catQuery; break; }
        }
    }

    function filterCards() {
        const cat = dropdown.value;
        const q = searchInput.value.toLowerCase();
        let visible = 0;
        cards.forEach(card => {
            const catMatch = cat === 'Semua' || card.dataset.category === cat;
            const nameMatch = !q || card.dataset.name.toLowerCase().includes(q);
            const show = catMatch && nameMatch;
            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        emptyEl.classList.toggle('hidden', visible > 0);
    }

    dropdown.addEventListener('change', filterCards);
    searchInput.addEventListener('input', filterCards);
    if (searchQuery || catQuery) filterCards();

    // ---- Cart (localStorage) ----
    function getCart() {
        try { return JSON.parse(localStorage.getItem('mk-cart')) || []; }
        catch { return []; }
    }
    function saveCart(cart) { localStorage.setItem('mk-cart', JSON.stringify(cart)); }
    function updateCartBadge() {
        const cart = getCart();
        const total = cart.reduce((s, i) => s + i.qty, 0);
        document.querySelectorAll('.cart-badge-count').forEach(el => {
            el.textContent = total;
            el.parentElement.style.display = total > 0 ? '' : 'none';
        });
    }

    function addToCart(btn) {
        const id = btn.dataset.itemId;
        const name = btn.dataset.itemName;
        const price = btn.dataset.itemPrice;
        const img = btn.dataset.itemImg;
        const cat = btn.dataset.itemCat;
        const poin = btn.dataset.itemPoin;
        const catering = btn.dataset.itemCatering;
        const preorder = parseInt(btn.dataset.itemPreorder) || 0;

        let cart = getCart();
        const existing = cart.find(i => i.id == id);
        if (existing) {
            existing.qty++;
        } else {
            cart.push({ id, name, price, img, cat, poin, catering, preorder, qty: 1 });
        }
        saveCart(cart);
        updateCartBadge();

        btn.style.background = '#16a34a';
        setTimeout(() => btn.style.background = '', 500);
        showToast(name + ' ditambahkan ke keranjang');
    }

    function showToast(msg) {
        let t = document.getElementById('cart-toast');
        if (!t) {
            t = document.createElement('div');
            t.id = 'cart-toast';
            t.style.cssText = 'position:fixed;bottom:100px;left:50%;transform:translateX(-50%);background:#1e293b;color:white;padding:10px 20px;border-radius:12px;font-size:13px;font-weight:700;z-index:9999;opacity:0;transition:opacity 0.3s;pointer-events:none;white-space:nowrap;box-shadow:0 8px 24px rgba(0,0,0,0.2);';
            document.body.appendChild(t);
        }
        t.textContent = '✓ ' + msg;
        t.style.opacity = '1';
        clearTimeout(t._timer);
        t._timer = setTimeout(() => t.style.opacity = '0', 2000);
    }

    document.querySelectorAll('.add-btn').forEach(btn => {
        btn.addEventListener('click', e => {
            e.stopPropagation();
            addToCart(btn);
        });
    });

    updateCartBadge();
});
</script>

</body>
</html>