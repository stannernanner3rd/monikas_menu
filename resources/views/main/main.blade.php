<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Menu - Monika's Kitchen</title>
    <meta name="description" content="Jelajahi menu lezat Monika's Kitchen — dari spesial mingguan hingga menu terlaris pilihan pelanggan.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Greeting section gradient */
        .greeting-bg {
            background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 50%, #fed7aa 100%);
        }

        /* Carousel card gradient overlay */
        .card-overlay {
            background: linear-gradient(to top, rgba(0,0,0,0.75) 0%, rgba(0,0,0,0.1) 60%, transparent 100%);
        }

        /* Glow effect on hover for special cards */
        .special-card:hover {
            transform: translateY(-4px) scale(1.01);
            box-shadow: 0 20px 40px rgba(249, 115, 22, 0.25);
        }
        .special-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }

        /* Bestseller card hover */
        .best-card:hover { transform: translateY(-2px); box-shadow: 0 12px 24px rgba(0,0,0,0.08); }
        .best-card { transition: transform 0.25s ease, box-shadow 0.25s ease; }

        /* Category card hover */
        .cat-card:hover .cat-img { transform: scale(1.08); }
        .cat-img { transition: transform 0.4s ease; }
        .cat-card:hover { box-shadow: 0 16px 32px rgba(249,115,22,0.18); }
        .cat-card { transition: box-shadow 0.3s ease; }

        /* Floating cart button */
        .cart-btn:hover { transform: scale(1.08) translateY(-2px); box-shadow: 0 12px 24px rgba(15,23,42,0.3); }
        .cart-btn { transition: transform 0.25s ease, box-shadow 0.25s ease; }

        /* Badge pulse */
        @keyframes pulse-badge {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.15); }
        }
        .badge-pulse { animation: pulse-badge 2s infinite; }

        /* Fade-in sections on load */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp 0.6s ease forwards; }
        .fade-up-delay-1 { animation: fadeUp 0.6s 0.1s ease both; }
        .fade-up-delay-2 { animation: fadeUp 0.6s 0.2s ease both; }
        .fade-up-delay-3 { animation: fadeUp 0.6s 0.35s ease both; }
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
                <p class="text-orange-500 font-bold text-xs uppercase tracking-widest mb-1">Selamat Siang! 👋</p>
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
            {{-- Decorative blobs --}}
            <div class="absolute -bottom-6 -left-6 w-28 h-28 bg-orange-200 rounded-full opacity-40 blur-2xl pointer-events-none"></div>
            <div class="absolute -top-4 right-24 w-20 h-20 bg-orange-300 rounded-full opacity-30 blur-2xl pointer-events-none"></div>
        </section>

        {{-- ===== SPESIAL MINGGU INI ===== --}}
        <section class="px-4 mt-8 fade-up-delay-1">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <span class="bg-orange-100 text-orange-600 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">⭐ Spesial Minggu Ini</span>
                </div>
                <a href="#" class="text-xs text-slate-500 hover:text-orange-500 font-semibold transition flex items-center gap-1">
                    Lihat Semua
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            {{-- Carousel --}}
            <div id="carousel" class="flex overflow-x-auto snap-x snap-mandatory gap-4 pb-3 no-scrollbar scroll-smooth">
                @php
                    $specials = [
                        ['name' => 'Beef & Pineapple Pizza', 'price' => '71.000', 'tag' => 'Pizza', 'rating' => '4.9', 'img' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?auto=format&fit=crop&w=800&q=80'],
                        ['name' => 'Spicy Honey Chicken', 'price' => '45.000', 'tag' => 'Ayam', 'rating' => '4.8', 'img' => 'https://images.unsplash.com/photo-1567620832903-9fc6debc209f?auto=format&fit=crop&w=800&q=80'],
                        ['name' => 'Creamy Mushroom Pasta', 'price' => '55.000', 'tag' => 'Pasta', 'rating' => '4.7', 'img' => 'https://images.unsplash.com/photo-1556761223-4c4282c73f77?auto=format&fit=crop&w=800&q=80'],
                        ['name' => 'Double Cheeseburger', 'price' => '60.000', 'tag' => 'Burger', 'rating' => '4.9', 'img' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=800&q=80'],
                        ['name' => 'Grilled Salmon Steak', 'price' => '95.000', 'tag' => 'Seafood', 'rating' => '5.0', 'img' => 'https://images.unsplash.com/photo-1467003909585-2f8a7270028d?auto=format&fit=crop&w=800&q=80'],
                    ];
                @endphp

                @foreach($specials as $item)
                <div class="carousel-item special-card min-w-[78%] md:min-w-[38%] lg:min-w-[28%] rounded-3xl overflow-hidden shadow-lg cursor-pointer snap-center relative flex-shrink-0" data-index="{{ $loop->index }}">
                    <img src="{{ $item['img'] }}" alt="{{ $item['name'] }}" class="w-full h-56 md:h-64 object-cover block">
                    {{-- Gradient overlay --}}
                    <div class="card-overlay absolute inset-0"></div>
                    {{-- Tag badge --}}
                    <span class="absolute top-3 left-3 bg-white/20 backdrop-blur-sm text-white text-[10px] font-bold px-3 py-1 rounded-full border border-white/30">{{ $item['tag'] }}</span>
                    {{-- Rating --}}
                    <span class="absolute top-3 right-3 bg-black/40 backdrop-blur-sm text-yellow-400 text-[10px] font-bold px-2.5 py-1 rounded-full flex items-center gap-1">
                        ⭐ {{ $item['rating'] }}
                    </span>
                    {{-- Content --}}
                    <div class="absolute bottom-0 left-0 right-0 p-4 flex justify-between items-end">
                        <div>
                            <h3 class="text-white font-bold text-base leading-snug drop-shadow">{{ $item['name'] }}</h3>
                            <p class="text-orange-300 font-black text-xl mt-0.5">Rp {{ $item['price'] }}</p>
                        </div>
                        <button id="add-special-{{ $loop->index }}" class="bg-orange-500 hover:bg-orange-400 p-3 rounded-full text-white shadow-lg transition-all duration-200 hover:scale-110 flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Indicators --}}
            <div class="flex justify-center gap-2 mt-3">
                @foreach($specials as $index => $item)
                <div class="indicator-dot h-1.5 rounded-full transition-all duration-300 cursor-pointer {{ $index === 0 ? 'w-6 bg-orange-500' : 'w-1.5 bg-slate-300' }}" data-index="{{ $index }}"></div>
                @endforeach
            </div>
        </section>

        {{-- ===== MENU TERLARIS ===== --}}
        <section class="px-4 mt-10 fade-up-delay-2">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h2 class="text-xl font-extrabold text-slate-800">🏆 Menu Terlaris</h2>
                    <p class="text-xs text-slate-400 font-medium mt-0.5">Pilihan favorit pelanggan setia kami</p>
                </div>
                <a href="#" class="text-xs text-slate-500 hover:text-orange-500 font-semibold transition flex items-center gap-1">
                    Lihat Semua
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            @php
                $bestSellers = [
                    ['name' => 'Es Timun Selasih', 'cat' => 'Minuman', 'price' => '13.000', 'sold' => '230+', 'img' => 'https://images.unsplash.com/photo-1626078299046-9fbeade2891e?auto=format&fit=crop&w=200&q=80'],
                    ['name' => 'Nasi Ayam Geprek', 'cat' => 'Makanan', 'price' => '28.000', 'sold' => '410+', 'img' => 'https://images.unsplash.com/photo-1632778149955-e80f8ceca2e8?auto=format&fit=crop&w=200&q=80'],
                    ['name' => 'Mie Goreng Spesial', 'cat' => 'Makanan', 'price' => '22.000', 'sold' => '380+', 'img' => 'https://images.unsplash.com/photo-1569050467447-ce54b3bbc37d?auto=format&fit=crop&w=200&q=80'],
                    ['name' => 'Jus Alpukat Susu', 'cat' => 'Minuman', 'price' => '18.000', 'sold' => '175+', 'img' => 'https://images.unsplash.com/photo-1623065422902-30a2d299bbe4?auto=format&fit=crop&w=200&q=80'],
                    ['name' => 'Sate Ayam Bumbu', 'cat' => 'Makanan', 'price' => '35.000', 'sold' => '290+', 'img' => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=200&q=80'],
                    ['name' => 'Ice Matcha Latte', 'cat' => 'Minuman', 'price' => '25.000', 'sold' => '320+', 'img' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=200&q=80'],
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($bestSellers as $i => $item)
                <div class="best-card bg-white p-3 rounded-2xl shadow-sm flex items-center gap-3 border border-slate-100 group cursor-pointer">
                    <div class="relative flex-shrink-0">
                        <img src="{{ $item['img'] }}" alt="{{ $item['name'] }}" class="w-20 h-20 rounded-xl object-cover">
                        <span class="absolute -top-1.5 -left-1.5 bg-orange-500 text-white text-[9px] font-black w-5 h-5 flex items-center justify-center rounded-full shadow">{{ $i+1 }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-slate-800 group-hover:text-orange-600 transition text-sm truncate">{{ $item['name'] }}</h3>
                        <span class="inline-block bg-slate-100 text-slate-500 text-[9px] px-2 py-0.5 rounded-full font-bold mt-0.5">{{ $item['cat'] }}</span>
                        <div class="flex items-center gap-2 mt-1">
                            <p class="text-orange-600 font-black text-sm">Rp {{ $item['price'] }}</p>
                            <span class="text-slate-400 text-[10px]">• {{ $item['sold'] }} terjual</span>
                        </div>
                    </div>
                    <button id="add-best-{{ $i }}" class="bg-slate-100 p-2.5 rounded-full text-slate-700 hover:bg-orange-500 hover:text-white transition flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    </button>
                </div>
                @endforeach
            </div>
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
                $categories = [
                    ['name' => 'Makanan', 'emoji' => '🍽️', 'count' => '24 Item', 'img' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80', 'color' => 'from-orange-400 to-red-500'],
                    ['name' => 'Minuman', 'emoji' => '🥤', 'count' => '18 Item', 'img' => 'https://images.unsplash.com/photo-1544145945-f90425340c7e?auto=format&fit=crop&w=400&q=80', 'color' => 'from-blue-400 to-cyan-500'],
                    ['name' => 'Snack',   'emoji' => '🍟', 'count' => '12 Item', 'img' => 'https://images.unsplash.com/photo-1562967915-6ba607ff7d05?auto=format&fit=crop&w=400&q=80', 'color' => 'from-yellow-400 to-orange-400'],
                    ['name' => 'Dessert', 'emoji' => '🍰', 'count' => '10 Item', 'img' => 'https://images.unsplash.com/photo-1563729784474-d77dbb933a9e?auto=format&fit=crop&w=400&q=80', 'color' => 'from-pink-400 to-rose-500'],
                ];
            @endphp

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @foreach($categories as $cat)
                <a href="{{ url('/menu') }}" class="cat-card bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 cursor-pointer group block">
                    <div class="relative overflow-hidden h-32">
                        <img src="{{ $cat['img'] }}" alt="{{ $cat['name'] }}" class="cat-img w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t {{ $cat['color'] }} opacity-50 group-hover:opacity-65 transition-opacity duration-300"></div>
                        <span class="absolute top-2 left-2 text-xl">{{ $cat['emoji'] }}</span>
                    </div>
                    <div class="p-3">
                        <h3 class="font-bold text-slate-800 group-hover:text-orange-600 transition text-sm">{{ $cat['name'] }}</h3>
                        <p class="text-slate-400 text-[11px] font-medium mt-0.5">{{ $cat['count'] }}</p>
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
    const carousel   = document.getElementById('carousel');
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

    // ---- Cart Counter ----
    let cartCount = 0;
    const cartCountEl = document.getElementById('cart-count');

    function addToCart() {
        cartCount++;
        cartCountEl.textContent = cartCount;
        cartCountEl.classList.add('scale-125');
        setTimeout(() => cartCountEl.classList.remove('scale-125'), 200);
    }

    document.querySelectorAll('[id^="add-special-"], [id^="add-best-"]').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            addToCart();
            // Visual feedback
            btn.classList.add('bg-green-500');
            setTimeout(() => btn.classList.remove('bg-green-500'), 600);
        });
    });
});
</script>

</body>
</html>