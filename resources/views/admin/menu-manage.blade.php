<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Menu - Admin Monika's Kitchen</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        @keyframes fadeUp { from{opacity:0;transform:translateY(18px);}to{opacity:1;transform:translateY(0);} }
        .fade-up { animation: fadeUp 0.5s ease both; }

        .modal-overlay { transition: opacity 0.25s ease; }
        .modal-content { transition: transform 0.25s ease, opacity 0.25s ease; }
        .modal-overlay.hidden { opacity:0; pointer-events:none; }
        .modal-overlay.hidden .modal-content { transform:translateY(20px) scale(0.95); opacity:0; }

        .toggle-switch { position:relative; width:44px; height:24px; }
        .toggle-switch input { opacity:0; width:0; height:0; }
        .toggle-slider { position:absolute; inset:0; background:#cbd5e1; border-radius:999px; transition:0.3s; cursor:pointer; }
        .toggle-slider::before { content:''; position:absolute; height:18px; width:18px; left:3px; bottom:3px; background:white; border-radius:50%; transition:0.3s; }
        .toggle-switch input:checked + .toggle-slider { background:#ea580c; }
        .toggle-switch input:checked + .toggle-slider::before { transform:translateX(20px); }

        .cat-filter { transition: all 0.2s; }
        .cat-filter.active { background:#ea580c !important; color:white !important; box-shadow:0 4px 12px rgba(234,88,12,0.3); }
    </style>
</head>
<body class="bg-slate-50 antialiased">

{{-- ===== ADMIN SIDEBAR ===== --}}
<aside class="hidden md:flex flex-col fixed inset-y-0 left-0 w-64 bg-slate-900 z-50">
    <div class="px-6 py-5 border-b border-slate-700">
        <a href="{{ url('/admin') }}" class="block">
            <span class="text-xl font-black text-white tracking-tighter leading-none">MONIKA<span class="text-orange-500">KITCHEN.</span></span>
            <p class="text-[11px] text-slate-500 font-medium mt-1">Admin Panel</p>
        </a>
    </div>
    <nav class="flex-1 px-3 py-4 space-y-1">
        <p class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest px-3 mb-2">Menu</p>
        <a href="{{ url('/admin') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-400 hover:bg-slate-800 hover:text-white transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            Dashboard
        </a>
        <a href="{{ url('/admin/menu') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold bg-orange-500/15 text-orange-400 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            Kelola Menu
        </a>

        <p class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest px-3 mb-2 mt-5">Pengaturan</p>
        <button onclick="toggleAdminDarkMode()" id="admin-dark-btn" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-400 hover:bg-slate-800 hover:text-white transition w-full text-left">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
            <span id="admin-dark-label">Mode Gelap</span>
        </button>
        <button onclick="toggleAdminLang()" id="admin-lang-btn" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-400 hover:bg-slate-800 hover:text-white transition w-full text-left">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
            <span id="admin-lang-label">🇮🇩 Indonesia</span>
        </button>
    </nav>
    <div class="px-4 py-4 border-t border-slate-700">
        <a href="{{ url('/main') }}" class="flex items-center gap-2 text-slate-400 hover:text-white text-sm font-semibold transition px-3 py-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
            Kembali ke Toko
        </a>
    </div>
</aside>

{{-- MOBILE ADMIN HEADER --}}
<header class="md:hidden bg-slate-900 px-4 pt-4 pb-3 sticky top-0 z-50">
    <div class="flex items-center justify-between">
        <a href="{{ url('/admin') }}" class="text-white text-lg font-black tracking-tighter">MONIKA<span class="text-orange-500">KITCHEN.</span></a>
        <div class="flex items-center gap-2">
            <a href="{{ url('/admin') }}" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-400">Dashboard</a>
            <a href="{{ url('/admin/menu') }}" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-orange-500 text-white">Menu</a>
            <a href="{{ url('/main') }}" class="text-slate-400 ml-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
            </a>
        </div>
    </div>
</header>

{{-- ===== MAIN CONTENT ===== --}}
<div class="md:pl-64 min-h-screen">
    <div class="px-4 md:px-10 py-6 md:py-10 max-w-6xl">

        {{-- Header --}}
        <div class="fade-up mb-6 flex items-end justify-between gap-4 flex-wrap">
            <div>
                <p class="text-orange-500 text-xs font-bold uppercase tracking-widest mb-1">Manajemen</p>
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900">Kelola Menu</h1>
                <p class="text-slate-400 text-sm mt-1">Tambah, edit, dan atur menu — termasuk promo & catering.</p>
            </div>
            <button onclick="openModal()" class="flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white font-bold text-sm px-5 py-2.5 rounded-xl shadow transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Tambah Menu
            </button>
        </div>

        {{-- Search & Filter --}}
        <div class="fade-up mb-4 space-y-3">
            <div class="relative">
                <span class="absolute inset-y-0 left-4 flex items-center text-slate-400 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input type="text" id="admin-search" placeholder="Cari menu berdasarkan nama..." autocomplete="off"
                       class="w-full pl-11 pr-4 py-3 rounded-xl bg-white border border-slate-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm outline-none transition shadow-sm">
            </div>
            <div class="flex gap-2 overflow-x-auto pb-1" id="admin-cat-filters">
                <button class="cat-filter active px-4 py-1.5 rounded-full bg-orange-600 text-white text-xs font-bold whitespace-nowrap" data-cat="Semua">Semua</button>
                <button class="cat-filter px-4 py-1.5 rounded-full bg-white text-slate-600 text-xs font-bold border border-slate-200 whitespace-nowrap" data-cat="Pizza">Pizza</button>
                <button class="cat-filter px-4 py-1.5 rounded-full bg-white text-slate-600 text-xs font-bold border border-slate-200 whitespace-nowrap" data-cat="Nasi Ayam">Nasi Ayam</button>
                <button class="cat-filter px-4 py-1.5 rounded-full bg-white text-slate-600 text-xs font-bold border border-slate-200 whitespace-nowrap" data-cat="Pasta">Pasta</button>
                <button class="cat-filter px-4 py-1.5 rounded-full bg-white text-slate-600 text-xs font-bold border border-slate-200 whitespace-nowrap" data-cat="Minuman">Minuman</button>
                <button class="cat-filter px-4 py-1.5 rounded-full bg-white text-slate-600 text-xs font-bold border border-slate-200 whitespace-nowrap" data-cat="Snack">Snack</button>
                <button class="cat-filter px-4 py-1.5 rounded-full bg-white text-slate-600 text-xs font-bold border border-slate-200 whitespace-nowrap" data-cat="Makanan">Makanan</button>
                <button class="cat-filter px-4 py-1.5 rounded-full bg-white text-slate-600 text-xs font-bold border border-slate-200 whitespace-nowrap" data-cat="promo">🔥 Promo</button>
                <button class="cat-filter px-4 py-1.5 rounded-full bg-white text-slate-600 text-xs font-bold border border-slate-200 whitespace-nowrap" data-cat="catering">🍱 Catering</button>
            </div>
            <p id="result-count" class="text-xs text-slate-400 font-semibold"></p>
        </div>

        {{-- Menu Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden fade-up">
            <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-3 bg-slate-50 border-b border-slate-100 text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">
                <div class="col-span-3">Menu</div>
                <div class="col-span-2">Kategori</div>
                <div class="col-span-2">Harga</div>
                <div class="col-span-1">Promo</div>
                <div class="col-span-1">Catering</div>
                <div class="col-span-1">Kuota</div>
                <div class="col-span-2 text-right">Aksi</div>
            </div>
            <div id="menu-list"></div>
            <div id="menu-empty" class="hidden text-center py-12">
                <div class="text-5xl mb-3">📋</div>
                <p class="text-slate-500 font-semibold">Tidak ditemukan menu.</p>
                <p class="text-slate-400 text-sm mt-1">Coba ubah filter atau kata kunci pencarian.</p>
            </div>
        </div>

    </div>
</div>

{{-- ===== ADD/EDIT MODAL ===== --}}
<div id="menu-modal" class="modal-overlay hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-60 flex items-end md:items-center justify-center p-4">
    <div class="modal-content bg-white rounded-t-3xl md:rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="sticky top-0 bg-white px-6 pt-5 pb-3 border-b border-slate-100 flex items-center justify-between z-10">
            <h2 id="modal-title" class="text-lg font-extrabold text-slate-900">Tambah Menu Baru</h2>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 transition p-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="menu-form" class="px-6 py-5 space-y-4">
            <input type="hidden" id="edit-index" value="-1">

            {{-- Image URL --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1.5">URL Gambar</label>
                <input type="url" id="form-img" placeholder="https://images.unsplash.com/..." required
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm outline-none transition">
                <div id="img-preview" class="mt-2 hidden">
                    <img id="img-preview-el" class="w-full h-32 object-cover rounded-xl border border-slate-100" src="" alt="Preview">
                </div>
            </div>

            {{-- Name --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1.5">Nama Menu</label>
                <input type="text" id="form-name" placeholder="Contoh: Nasi Ayam Geprek" required
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm outline-none transition">
            </div>

            {{-- Price --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1.5">Harga (Rp)</label>
                <input type="text" id="form-price" placeholder="Contoh: 28.000" required
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm outline-none transition">
            </div>

            {{-- Category --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1.5">Kategori</label>
                <select id="form-cat" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm outline-none transition bg-white">
                    <option value="">Pilih Kategori</option>
                    <option>Pizza</option><option>Nasi Ayam</option><option>Pasta</option>
                    <option>Minuman</option><option>Snack</option><option>Burger</option>
                    <option>Seafood</option><option>Makanan</option>
                </select>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Rating (1-5)</label>
                    <input type="number" id="form-rating" min="1" max="5" step="0.1" placeholder="4.8" required
                           class="w-full px-3 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Terjual</label>
                    <input type="text" id="form-sold" placeholder="180+" required
                           class="w-full px-3 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Batas Porsi</label>
                    <input type="number" id="form-quota" placeholder="∞"
                           class="w-full px-3 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm outline-none transition" title="Kosongkan jika tanpa batas">
                </div>
            </div>

            {{-- Special toggle --}}
            <div class="flex items-center justify-between py-2 border-t border-slate-50">
                <div>
                    <p class="text-sm font-bold text-slate-700">Spesial Minggu Ini</p>
                    <p class="text-xs text-slate-400 mt-0.5">Tampilkan di carousel spesial</p>
                </div>
                <label class="toggle-switch"><input type="checkbox" id="form-special"><span class="toggle-slider"></span></label>
            </div>

            {{-- ===== PROMO SECTION ===== --}}
            <div class="border-t border-slate-100 pt-4">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-sm font-bold text-orange-600">🔥 Promo / Diskon</p>
                        <p class="text-xs text-slate-400">Set harga promo untuk menu ini</p>
                    </div>
                    <label class="toggle-switch"><input type="checkbox" id="form-promo-active" onchange="togglePromoFields()"><span class="toggle-slider"></span></label>
                </div>
                <div id="promo-fields" class="hidden space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Diskon (%)</label>
                            <input type="number" id="form-discount" min="1" max="99" placeholder="20"
                                   class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-orange-500 text-sm outline-none transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Label Promo</label>
                            <input type="text" id="form-promo-label" placeholder="Promo Maret"
                                   class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-orange-500 text-sm outline-none transition">
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== CATERING SECTION ===== --}}
            <div class="border-t border-slate-100 pt-4">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-sm font-bold text-emerald-600">🍱 Catering / Bulk</p>
                        <p class="text-xs text-slate-400">Semakin banyak, semakin murah</p>
                    </div>
                    <label class="toggle-switch"><input type="checkbox" id="form-catering-active" onchange="toggleCateringFields()"><span class="toggle-slider"></span></label>
                </div>
                <div id="catering-fields" class="hidden space-y-2">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Tier Harga</p>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 mb-1">Min. 10 pcs</label>
                            <input type="text" id="form-cater-10" placeholder="Rp/pcs"
                                   class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm outline-none transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 mb-1">Min. 25 pcs</label>
                            <input type="text" id="form-cater-25" placeholder="Rp/pcs"
                                   class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm outline-none transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 mb-1">Min. 50 pcs</label>
                            <input type="text" id="form-cater-50" placeholder="Rp/pcs"
                                   class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm outline-none transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 mb-1">Min. 100 pcs</label>
                            <input type="text" id="form-cater-100" placeholder="Rp/pcs"
                                   class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm outline-none transition">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-xl shadow transition text-sm mt-2">
                Simpan Menu
            </button>
        </form>
    </div>
</div>

{{-- ===== DELETE CONFIRM MODAL ===== --}}
<div id="delete-modal" class="modal-overlay hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-70 flex items-center justify-center p-4">
    <div class="modal-content bg-white rounded-2xl w-full max-w-sm p-6 shadow-2xl text-center">
        <div class="text-5xl mb-3">🗑️</div>
        <h3 class="text-lg font-extrabold text-slate-900 mb-1">Hapus Menu?</h3>
        <p class="text-slate-400 text-sm mb-5">Tindakan ini tidak bisa dibatalkan.</p>
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()" class="flex-1 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50 transition">Batal</button>
            <button id="confirm-delete-btn" class="flex-1 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white font-bold text-sm shadow transition">Hapus</button>
        </div>
    </div>
</div>

<script>
(function() {
    // ---- Menu data store ----
    let menuItems = [
        { name: 'Pizza Margherita',    price: '65.000', cat: 'Pizza',     rating: '4.8', sold: '180+', quota: 20, soldToday: 5,  img: 'https://images.unsplash.com/photo-1604382354936-07c5d9983bd3?auto=format&fit=crop&w=400&q=80', special: true,  promo: true,  discount: 20.0, promoLabel: 'Promo Maret', catering: false, cater10: '', cater25: '', cater50: '', cater100: '' },
        { name: 'Nasi Ayam Bakar',     price: '35.000', cat: 'Nasi Ayam', rating: '4.9', sold: '410+', quota: 50, soldToday: 48, img: 'https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?auto=format&fit=crop&w=400&q=80', special: false, promo: false, discount: 0,    promoLabel: '',             catering: true,  cater10: '30.000', cater25: '27.000', cater50: '24.000', cater100: '20.000' },
        { name: 'Es Teh Manis',        price: '5.000',  cat: 'Minuman',   rating: '4.6', sold: '320+', quota: '', soldToday: 0,  img: 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=400&q=80', special: false, promo: false, discount: 0,    promoLabel: '',             catering: true,  cater10: '4.000', cater25: '3.500', cater50: '3.000', cater100: '2.500' },
        { name: 'French Fries',        price: '15.000', cat: 'Snack',     rating: '4.7', sold: '210+', quota: '', soldToday: 0,  img: 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?auto=format&fit=crop&w=400&q=80', special: false, promo: false, discount: 0,    promoLabel: '',             catering: false, cater10: '', cater25: '', cater50: '', cater100: '' },
        { name: 'Spaghetti Carbonara', price: '45.000', cat: 'Pasta',     rating: '4.8', sold: '150+', quota: 15, soldToday: 13, img: 'https://images.unsplash.com/photo-1612450800052-759c5509b58e?auto=format&fit=crop&w=400&q=80', special: true,  promo: true,  discount: 15.0, promoLabel: 'Hemat 15%',    catering: false, cater10: '', cater25: '', cater50: '', cater100: '' },
        { name: 'Pizza Meat Lovers',   price: '85.000', cat: 'Pizza',     rating: '4.9', sold: '290+', quota: 10, soldToday: 10, img: 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?auto=format&fit=crop&w=400&q=80', special: true,  promo: false, discount: 0,    promoLabel: '',             catering: true,  cater10: '75.000', cater25: '68.000', cater50: '60.000', cater100: '52.000' },
        { name: 'Nasi Ayam Geprek',    price: '28.000', cat: 'Nasi Ayam', rating: '4.8', sold: '510+', quota: 50, soldToday: 20, img: 'https://images.unsplash.com/photo-1632778149955-e80f8ceca2e8?auto=format&fit=crop&w=400&q=80', special: false, promo: true,  discount: 10.0, promoLabel: 'Flash Sale',   catering: true,  cater10: '25.000', cater25: '22.000', cater50: '19.000', cater100: '16.000' },
        { name: 'Ice Matcha Latte',    price: '25.000', cat: 'Minuman',   rating: '4.7', sold: '190+', quota: '', soldToday: 0,  img: 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=400&q=80', special: false, promo: false, discount: 0,    promoLabel: '',             catering: false, cater10: '', cater25: '', cater50: '', cater100: '' },
    ];

    const listEl = document.getElementById('menu-list');
    const emptyEl = document.getElementById('menu-empty');
    const searchInput = document.getElementById('admin-search');
    const resultCount = document.getElementById('result-count');
    let activeCat = 'Semua';

    // ---- Filter + Render ----
    function getFiltered() {
        const q = searchInput.value.trim().toLowerCase();
        return menuItems.filter((item, i) => {
            item._idx = i;
            const catMatch = activeCat === 'Semua' || item.cat === activeCat
                || (activeCat === 'promo' && item.promo)
                || (activeCat === 'catering' && item.catering);
            const nameMatch = !q || item.name.toLowerCase().includes(q);
            return catMatch && nameMatch;
        });
    }

    function render() {
        const filtered = getFiltered();
        resultCount.textContent = `Menampilkan ${filtered.length} dari ${menuItems.length} menu`;

        if (filtered.length === 0) {
            listEl.innerHTML = '';
            emptyEl.classList.remove('hidden');
            return;
        }
        emptyEl.classList.add('hidden');

        listEl.innerHTML = filtered.map(item => {
            const i = item._idx;
            const promoPrice = item.promo ? Math.round(parseInt(item.price.replace(/\./g,'')) * (1 - item.discount/100)).toLocaleString('id-ID') : '';

            // Quota logic
            let quotaHtml = '<span class="text-slate-300 text-xs">—</span>';
            if(item.quota) {
                const remaining = parseInt(item.quota) - parseInt(item.soldToday || 0);
                if(remaining <= 0) {
                    quotaHtml = `<span class="text-[10px] font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-full border border-red-200">Habis (0)</span>`;
                } else if(remaining < 5) {
                    quotaHtml = `<span class="text-[10px] font-bold text-red-700 bg-red-100 px-2 py-0.5 rounded-full border border-red-300">Sisa ${remaining}</span>`;
                } else {
                    quotaHtml = `<span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">Sisa ${remaining}</span>`;
                }
            }

            return `
            <div class="md:grid md:grid-cols-12 md:gap-4 md:items-center px-4 md:px-6 py-3 border-b border-slate-50 hover:bg-slate-50/50 transition">
                <div class="col-span-3 flex items-center gap-3 mb-2 md:mb-0">
                    <div class="relative flex-shrink-0">
                        <img src="${item.img}" alt="${item.name}" class="w-12 h-12 rounded-xl object-cover border border-slate-100">
                        ${item.special ? '<span class="absolute -top-1 -right-1 w-4 h-4 bg-orange-500 text-white text-[8px] font-black rounded-full flex items-center justify-center">★</span>' : ''}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-slate-800 truncate">${item.name}</p>
                        <p class="text-[11px] text-slate-400 md:hidden">${item.cat} · Rp ${item.price}</p>
                        ${item.quota ? `
                            <div class="md:hidden mt-1">
                                ${parseInt(item.quota) - parseInt(item.soldToday||0) <= 0
                                    ? '<span class="text-[9px] font-bold text-red-600 bg-red-50 px-1.5 py-0.5 rounded-full border border-red-200">Habis</span>'
                                    : (parseInt(item.quota) - parseInt(item.soldToday||0) < 5
                                        ? '<span class="text-[9px] font-bold text-red-700 bg-red-100 px-1.5 py-0.5 rounded-full border border-red-300">Sisa ' + (parseInt(item.quota)-parseInt(item.soldToday||0)) + '</span>'
                                        : '<span class="text-[9px] font-bold text-emerald-700 bg-emerald-100 px-1.5 py-0.5 rounded-full">Sisa ' + (parseInt(item.quota)-parseInt(item.soldToday||0)) + '</span>'
                                    )
                                }
                            </div>
                        ` : ''}
                    </div>
                </div>
                <div class="col-span-2 hidden md:block">
                    <span class="text-xs font-semibold text-slate-600 bg-slate-100 px-2.5 py-0.5 rounded-lg">${item.cat}</span>
                </div>
                <div class="col-span-2 hidden md:block">
                    ${item.promo
                        ? `<span class="text-xs text-slate-400 line-through">Rp ${item.price}</span>
                           <span class="text-sm font-bold text-red-600 block">Rp ${promoPrice}</span>`
                        : `<span class="text-sm font-bold text-slate-800">Rp ${item.price}</span>`}
                </div>
                <div class="col-span-1 hidden md:block">
                    ${item.promo
                        ? `<span class="text-[10px] font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-full">${item.discount}%</span>`
                        : '<span class="text-slate-300 text-xs">—</span>'}
                </div>
                <div class="col-span-1 hidden md:block">
                    ${item.catering
                        ? `<span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full" title="Tersedia Harga Grosir">✓</span>`
                        : '<span class="text-slate-300 text-xs">—</span>'}
                </div>
                <div class="col-span-1 hidden md:block">
                    ${quotaHtml}
                </div>
                <div class="col-span-2 flex items-center justify-end gap-1">
                    ${item.promo ? '<span class="md:hidden text-[10px] font-bold text-red-600 bg-red-50 px-1.5 py-0.5 rounded-full mr-1">-' + item.discount + '%</span>' : ''}
                    ${item.catering ? '<span class="md:hidden text-[10px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-full mr-auto">🍱</span>' : '<span class="mr-auto md:hidden"></span>'}
                    <button onclick="editItem(${i})" class="p-2 rounded-lg text-slate-400 hover:bg-blue-50 hover:text-blue-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    <button onclick="deleteItem(${i})" class="p-2 rounded-lg text-slate-400 hover:bg-red-50 hover:text-red-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>`;
        }).join('');
    }

    // ---- Search ----
    searchInput.addEventListener('input', () => render());

    // ---- Category filter ----
    document.querySelectorAll('.cat-filter').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.cat-filter').forEach(b => {
                b.classList.remove('active');
                b.style.background = ''; b.style.color = '';
            });
            this.classList.add('active');
            activeCat = this.getAttribute('data-cat');
            render();
        });
    });

    // ---- Promo/Catering field toggles ----
    window.togglePromoFields = function() {
        document.getElementById('promo-fields').classList.toggle('hidden', !document.getElementById('form-promo-active').checked);
    };
    window.toggleCateringFields = function() {
        document.getElementById('catering-fields').classList.toggle('hidden', !document.getElementById('form-catering-active').checked);
    };

    // ---- Modal ----
    const modal = document.getElementById('menu-modal');
    const form = document.getElementById('menu-form');
    const imgInput = document.getElementById('form-img');
    const previewDiv = document.getElementById('img-preview');
    const previewImg = document.getElementById('img-preview-el');

    window.openModal = function(editIdx) {
        const isEdit = editIdx !== undefined && editIdx >= 0;
        document.getElementById('modal-title').textContent = isEdit ? 'Edit Menu' : 'Tambah Menu Baru';
        document.getElementById('edit-index').value = isEdit ? editIdx : -1;

        if (isEdit) {
            const item = menuItems[editIdx];
            document.getElementById('form-name').value = item.name;
            document.getElementById('form-price').value = item.price;
            document.getElementById('form-cat').value = item.cat;
            document.getElementById('form-rating').value = item.rating;
            document.getElementById('form-sold').value = item.sold;
            document.getElementById('form-quota').value = item.quota || '';
            document.getElementById('form-img').value = item.img;
            document.getElementById('form-special').checked = item.special;
            // Promo
            document.getElementById('form-promo-active').checked = item.promo;
            document.getElementById('form-discount').value = item.discount || '';
            document.getElementById('form-promo-label').value = item.promoLabel || '';
            togglePromoFields();
            // Catering
            document.getElementById('form-catering-active').checked = item.catering;
            document.getElementById('form-cater-10').value = item.cater10 || '';
            document.getElementById('form-cater-25').value = item.cater25 || '';
            document.getElementById('form-cater-50').value = item.cater50 || '';
            document.getElementById('form-cater-100').value = item.cater100 || '';
            toggleCateringFields();
            previewImg.src = item.img;
            previewDiv.classList.remove('hidden');
        } else {
            form.reset();
            previewDiv.classList.add('hidden');
            document.getElementById('promo-fields').classList.add('hidden');
            document.getElementById('catering-fields').classList.add('hidden');
        }
        modal.classList.remove('hidden');
    };

    window.closeModal = function() { modal.classList.add('hidden'); };
    window.editItem = function(i) { openModal(i); };

    imgInput.addEventListener('input', function() {
        const url = this.value.trim();
        if (url) { previewImg.src = url; previewDiv.classList.remove('hidden'); }
        else { previewDiv.classList.add('hidden'); }
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const idx = parseInt(document.getElementById('edit-index').value);
        const item = {
            name:       document.getElementById('form-name').value.trim(),
            price:      document.getElementById('form-price').value.trim(),
            cat:        document.getElementById('form-cat').value,
            rating:     document.getElementById('form-rating').value,
            sold:       document.getElementById('form-sold').value.trim(),
            quota:      document.getElementById('form-quota').value.trim(),
            soldToday:  idx > -1 ? menuItems[idx].soldToday : 0,
            img:        document.getElementById('form-img').value.trim(),
            special:    document.getElementById('form-special').checked,
            promo:      document.getElementById('form-promo-active').checked,
            discount:   parseFloat(document.getElementById('form-discount').value) || 0,
            promoLabel: document.getElementById('form-promo-label').value.trim(),
            catering:   document.getElementById('form-catering-active').checked,
            cater10:    document.getElementById('form-cater-10').value.trim(),
            cater25:    document.getElementById('form-cater-25').value.trim(),
            cater50:    document.getElementById('form-cater-50').value.trim(),
            cater100:   document.getElementById('form-cater-100').value.trim(),
        };
        if (idx >= 0) { menuItems[idx] = item; } else { menuItems.push(item); }
        render();
        closeModal();
    });

    // ---- Delete ----
    let deleteIdx = -1;
    const deleteModal = document.getElementById('delete-modal');

    window.deleteItem = function(i) { deleteIdx = i; deleteModal.classList.remove('hidden'); };
    window.closeDeleteModal = function() { deleteModal.classList.add('hidden'); deleteIdx = -1; };
    document.getElementById('confirm-delete-btn').addEventListener('click', function() {
        if (deleteIdx >= 0) { menuItems.splice(deleteIdx, 1); render(); }
        closeDeleteModal();
    });

    // ---- Init ----
    render();

    // ---- Admin Dark Mode ----
    window.toggleAdminDarkMode = function() {
        const isDark = localStorage.getItem('mk-dark-mode') === 'true';
        localStorage.setItem('mk-dark-mode', !isDark);
        if (!isDark) {
            document.documentElement.setAttribute('data-theme', 'dark');
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.removeAttribute('data-theme');
            document.documentElement.classList.remove('dark');
        }
        updateDarkLabel();
    };
    function updateDarkLabel() {
        const isDark = localStorage.getItem('mk-dark-mode') === 'true';
        const el = document.getElementById('admin-dark-label');
        if (el) el.textContent = isDark ? '☀️ Mode Terang' : '🌙 Mode Gelap';
    }
    // Apply on load
    if (localStorage.getItem('mk-dark-mode') === 'true') {
        document.documentElement.setAttribute('data-theme', 'dark');
        document.documentElement.classList.add('dark');
    }
    updateDarkLabel();

    // ---- Admin Language Toggle ----
    window.toggleAdminLang = function() {
        const current = localStorage.getItem('mk-lang') || 'id';
        const next = current === 'id' ? 'en' : 'id';
        localStorage.setItem('mk-lang', next);
        updateLangLabel();
    };
    function updateLangLabel() {
        const lang = localStorage.getItem('mk-lang') || 'id';
        const el = document.getElementById('admin-lang-label');
        if (el) el.textContent = lang === 'id' ? '🇮🇩 Indonesia' : '🇬🇧 English';
    }
    updateLangLabel();
})();
</script>

</body>
</html>
