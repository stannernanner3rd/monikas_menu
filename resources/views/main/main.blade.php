<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Menu - Monika's Kitchen</title>
    <meta name="description" content="Jelajahi menu lezat Monika's Kitchen — dari spesial mingguan hingga menu terlaris pilihan pelanggan.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .greeting-bg { background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 50%, #fed7aa 100%); }
        .card-overlay { background: linear-gradient(to top, rgba(0,0,0,0.75) 0%, rgba(0,0,0,0.1) 60%, transparent 100%); }
        .special-card:hover { transform: translateY(-4px) scale(1.01); box-shadow: 0 20px 40px rgba(249, 115, 22, 0.25); }
        .special-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .best-card:hover { transform: translateY(-2px); box-shadow: 0 12px 24px rgba(0,0,0,0.08); }
        .best-card { transition: transform 0.25s ease, box-shadow 0.25s ease; }
        .cat-card:hover .cat-img { transform: scale(1.08); }
        .cat-img { transition: transform 0.4s ease; }
        .cat-card:hover { box-shadow: 0 16px 32px rgba(249,115,22,0.18); }
        .cat-card { transition: box-shadow 0.3s ease; }
        @keyframes pulse-badge { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.15); } }
        .badge-pulse { animation: pulse-badge 2s infinite; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .fade-up { animation: fadeUp 0.6s ease forwards; }
        .fade-up-delay-1 { animation: fadeUp 0.6s 0.1s ease both; }
        .fade-up-delay-2 { animation: fadeUp 0.6s 0.2s ease both; }
        .fade-up-delay-3 { animation: fadeUp 0.6s 0.35s ease both; }
        .add-btn:hover { transform: scale(1.12); }
        .add-btn { transition: all 0.2s; }
    </style>
</head>

<body class="bg-slate-50 antialiased">
<div class="min-h-screen pb-28 md:pb-10 md:pl-64">

    {{-- ===== NAVBAR ===== --}}
    <x-navibar />

    <main class="max-w-lg mx-auto md:max-w-none md:mx-0 md:px-10 md:py-8">

        {{-- ===== GREETING SECTION ===== --}}
        <section class="mx-4 mt-5 greeting-bg rounded-3xl px-6 py-6 fade-up flex justify-between items-center overflow-hidden relative">
            <div>
                <p class="text-orange-500 font-bold text-xs uppercase tracking-widest mb-1">Selamat Datang! 👋</p>
                <h2 class="text-slate-900 font-extrabold text-2xl leading-tight">Mau makan apa <br>hari ini?</h2>
                <p class="text-slate-500 text-sm mt-1.5">Pilih dari menu spesial & terlaris kami.</p>
            </div>
            <div class="relative flex-shrink-0">
                <div class="w-24 h-24 rounded-2xl overflow-hidden shadow-lg rotate-3 border-4 border-white">
                    <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=200&q=80"
                         alt="Makanan Lezat" class="w-full h-full object-cover -rotate-3 scale-110">
                </div>
                <span class="absolute -top-2 -right-2 bg-orange-500 text-white text-[10px] font-black px-2 py-1 rounded-full badge-pulse shadow">HOT 🔥</span>
            </div>
            <div class="absolute -bottom-6 -left-6 w-28 h-28 bg-orange-200 rounded-full opacity-40 blur-2xl pointer-events-none"></div>
            <div class="absolute -top-4 right-24 w-20 h-20 bg-orange-300 rounded-full opacity-30 blur-2xl pointer-events-none"></div>
        </section>

        {{-- ===== SPESIAL MINGGU INI ===== --}}
        <section class="px-4 mt-8 fade-up-delay-1">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <span class="bg-orange-100 text-orange-600 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">⭐ Spesial Minggu Ini</span>
                </div>
                <a href="{{ url('/menu') }}" class="text-xs text-slate-500 hover:text-orange-500 font-semibold transition flex items-center gap-1">
                    Lihat Semua
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            @if($spesial->count() > 0)
            <div id="carousel" class="flex overflow-x-auto snap-x snap-mandatory gap-4 pb-3 no-scrollbar scroll-smooth">
                @foreach($spesial as $item)
                @php
                    $imgUrl = $item->gambar
                        ? asset('storage/' . $item->gambar)
                        : 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=800&q=80';
                    $displayPrice = $item->harga_promo ?: $item->harga;
                @endphp
                <div class="carousel-item special-card min-w-[78%] md:min-w-[38%] lg:min-w-[28%] rounded-3xl overflow-hidden shadow-lg cursor-pointer snap-center relative shrink-0" data-index="{{ $loop->index }}">
                    <img src="{{ $imgUrl }}" alt="{{ $item->nama }}" class="w-full h-56 md:h-64 object-cover block">
                    <div class="card-overlay absolute inset-0"></div>
                    <span class="absolute top-3 left-3 bg-white/20 backdrop-blur-sm text-white text-[10px] font-bold px-3 py-1 rounded-full border border-white/30">
                        {{ $item->kategori ? $item->kategori->nama : 'Menu' }}
                    </span>
                    @if($item->harga_promo)
                    <span class="absolute top-3 right-3 bg-red-500 text-white text-[9px] font-black px-2 py-0.5 rounded-lg animate-pulse">🏷️ PROMO</span>
                    @endif
                    <div class="absolute bottom-0 left-0 right-0 p-4 flex justify-between items-end">
                        <div>
                            <h3 class="text-white font-bold text-base leading-snug drop-shadow">{{ $item->nama }}</h3>
                            @if($item->harga_promo)
                            <p class="text-slate-300 text-xs line-through">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                            <p class="text-orange-300 font-black text-xl mt-0.5">Rp {{ number_format($item->harga_promo, 0, ',', '.') }}</p>
                            @else
                            <p class="text-orange-300 font-black text-xl mt-0.5">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                            @endif
                        </div>
                        <button class="add-btn bg-orange-500 hover:bg-orange-400 p-3 rounded-full text-white shadow-lg shrink-0"
                                data-item-id="{{ $item->id }}"
                                data-item-name="{{ $item->nama }}"
                                data-item-price="{{ $displayPrice }}"
                                data-item-img="{{ $imgUrl }}"
                                data-item-cat="{{ $item->kategori ? $item->kategori->nama : 'Menu' }}"
                                data-item-poin="{{ $item->poin ?? 0 }}"
                                data-item-catering="{{ $item->catering_tersedia ? '1' : '0' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="flex justify-center gap-2 mt-3">
                @foreach($spesial as $index => $item)
                <div class="indicator-dot h-1.5 rounded-full transition-all duration-300 cursor-pointer {{ $loop->first ? 'w-6 bg-orange-500' : 'w-1.5 bg-slate-300' }}" data-index="{{ $loop->index }}"></div>
                @endforeach
            </div>
            @else
            <div class="bg-orange-50 rounded-2xl p-8 text-center">
                <p class="text-3xl mb-2">⭐</p>
                <p class="text-slate-500 font-semibold text-sm">Belum ada menu spesial minggu ini.</p>
                <p class="text-slate-400 text-xs mt-1">Admin bisa menandai menu lewat panel admin.</p>
            </div>
            @endif
        </section>

        {{-- ===== MENU TERLARIS — DARI DATABASE ===== --}}
        <section class="px-4 mt-10 fade-up-delay-2">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h2 class="text-xl font-extrabold text-slate-800">🏆 Menu Terlaris</h2>
                    <p class="text-xs text-slate-400 font-medium mt-0.5">Pilihan favorit pelanggan setia kami</p>
                </div>
                <a href="{{ url('/menu') }}" class="text-xs text-slate-500 hover:text-orange-500 font-semibold transition flex items-center gap-1">
                    Lihat Semua
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            @if($terlaris->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($terlaris as $i => $item)
                @php
                    $imgUrl = $item->gambar
                        ? asset('storage/' . $item->gambar)
                        : 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=200&q=80';
                    $catName = $item->kategori_nama ?? 'Menu';
                    $displayPrice = $item->harga_promo ?: $item->harga;
                @endphp
                <div class="best-card bg-white p-3 rounded-2xl shadow-sm flex items-center gap-3 border border-slate-100 group cursor-pointer">
                    <div class="relative flex-shrink-0">
                        <img src="{{ $imgUrl }}" alt="{{ $item->nama }}" class="w-20 h-20 rounded-xl object-cover">
                        <span class="absolute -top-1.5 -left-1.5 bg-orange-500 text-white text-[9px] font-black w-5 h-5 flex items-center justify-center rounded-full shadow">{{ $i+1 }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-slate-800 group-hover:text-orange-600 transition text-sm truncate">{{ $item->nama }}</h3>
                        <span class="inline-block bg-slate-100 text-slate-500 text-[9px] px-2 py-0.5 rounded-full font-bold mt-0.5">{{ $catName }}</span>
                        <div class="flex items-center gap-2 mt-1">
                            <p class="text-orange-600 font-black text-sm">Rp {{ number_format($displayPrice, 0, ',', '.') }}</p>
                            <span class="text-slate-400 text-[10px]">• {{ $item->total_terjual }} terjual</span>
                        </div>
                    </div>
                    <button class="add-btn bg-slate-100 p-2.5 rounded-full text-slate-700 hover:bg-orange-500 hover:text-white transition flex-shrink-0"
                            data-item-id="{{ $item->id }}"
                            data-item-name="{{ $item->nama }}"
                            data-item-price="{{ $displayPrice }}"
                            data-item-img="{{ $imgUrl }}"
                            data-item-cat="{{ $catName }}"
                            data-item-poin="{{ $item->poin ?? 0 }}"
                            data-item-catering="{{ $item->catering_tersedia ? '1' : '0' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    </button>
                </div>
                @endforeach
            </div>
            @else
            <div class="bg-slate-50 rounded-2xl p-8 text-center border border-slate-100">
                <p class="text-3xl mb-2">📊</p>
                <p class="text-slate-500 font-semibold text-sm">Belum ada data penjualan.</p>
                <p class="text-slate-400 text-xs mt-1">Data akan muncul setelah ada pesanan masuk.</p>
            </div>
            @endif
        </section>

        {{-- ===== KATEGORI MENU ===== --}}
        <section class="px-4 mt-10 pb-4 fade-up-delay-3">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h2 class="text-xl font-extrabold text-slate-800">🗂️ Kategori Menu</h2>
                    <p class="text-xs text-slate-400 font-medium mt-0.5">Temukan makanan sesuai selera</p>
                </div>
                <a href="{{ url('/menu') }}" class="text-xs text-slate-500 hover:text-orange-500 font-semibold transition flex items-center gap-1">
                    Lihat Semua
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            @php
                $gradients = [
                    'from-orange-400 to-red-500', 'from-blue-400 to-cyan-500',
                    'from-yellow-400 to-orange-400', 'from-pink-400 to-rose-500',
                    'from-emerald-400 to-teal-500', 'from-violet-400 to-purple-500',
                ];
            @endphp

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @foreach($kategoriDB as $i => $cat)
                <a href="{{ url('/menu?cat=' . urlencode($cat->nama)) }}" class="cat-card bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 cursor-pointer group block">
                    <div class="relative overflow-hidden h-32">
                        <div class="w-full h-full bg-gradient-to-br {{ $gradients[$i % count($gradients)] }} flex items-center justify-center">
                            <span class="text-4xl">🍽️</span>
                        </div>
                    </div>
                    <div class="p-3">
                        <h3 class="font-bold text-slate-800 group-hover:text-orange-600 transition text-sm">{{ $cat->nama }}</h3>
                        <p class="text-slate-400 text-[11px] font-medium mt-0.5">{{ $cat->menu_count }} Item</p>
                    </div>
                </a>
                @endforeach
            </div>
        </section>

    </main>
</div>

{{-- ===== JAVASCRIPT ===== --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ---- Carousel Indicators ----
    const carousel = document.getElementById('carousel');
    if (carousel) {
        const indicators = document.querySelectorAll('.indicator-dot');

        function updateIndicators() {
            const containerCenter = carousel.getBoundingClientRect().left + carousel.offsetWidth / 2;
            let closestItem = null, minDist = Infinity;
            carousel.querySelectorAll('.carousel-item').forEach(item => {
                const dist = Math.abs(item.getBoundingClientRect().left + item.offsetWidth / 2 - containerCenter);
                if (dist < minDist) { minDist = dist; closestItem = item; }
            });
            if (closestItem) {
                const activeIndex = closestItem.getAttribute('data-index');
                indicators.forEach(dot => {
                    const isActive = dot.getAttribute('data-index') === activeIndex;
                    dot.classList.toggle('w-6', isActive);
                    dot.classList.toggle('bg-orange-500', isActive);
                    dot.classList.toggle('w-1.5', !isActive);
                    dot.classList.toggle('bg-slate-300', !isActive);
                });
            }
        }

        carousel.addEventListener('scroll', () => window.requestAnimationFrame(updateIndicators));
        indicators.forEach(dot => {
            dot.addEventListener('click', () => {
                const target = carousel.querySelector(`.carousel-item[data-index="${dot.getAttribute('data-index')}"]`);
                if (target) target.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            });
        });
    }

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
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const id = this.dataset.itemId;
            const name = this.dataset.itemName;
            const price = this.dataset.itemPrice;
            const img = this.dataset.itemImg;
            const cat = this.dataset.itemCat;
            const poin = this.dataset.itemPoin;
            const catering = this.dataset.itemCatering;

            let cart = getCart();
            const existing = cart.find(i => i.id == id);
            if (existing) { existing.qty++; }
            else { cart.push({ id, name, price, img, cat, poin, catering, qty: 1 }); }
            saveCart(cart);
            updateCartBadge();

            this.style.background = '#16a34a';
            setTimeout(() => this.style.background = '', 500);
            showToast(name + ' ditambahkan ke keranjang');
        });
    });

    updateCartBadge();
});
</script>

</body>
</html>