<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    </style>
</head>
<body class="bg-slate-50 antialiased relative">

{{-- Sidebar Admin — komponen reusable --}}
<x-admin-sidebar active="testo" />

{{-- ===== MAIN CONTENT ===== --}}
<div class="md:pl-64 min-h-screen pb-20">
    <div class="px-4 md:px-10 py-6 md:py-10 max-w-6xl">
        
        {{-- Header --}}
        <div class="mb-6 fade-up flex justify-between items-end flex-wrap gap-4">
            <div>
                <p class="text-orange-500 text-xs font-bold uppercase tracking-widest mb-1">Database-Driven</p>
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900">Kelola Menu</h1>
                <p class="text-slate-400 text-sm mt-1">Tambah, edit, hapus menu — data langsung ke database.</p>
            </div>
            <div class="flex gap-2">
                <button onclick="openModalKategori()" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-sm px-4 py-2.5 rounded-xl shadow-sm transition">
                    Kelola Kategori
                </button>
                <button onclick="openModalMenu()" class="bg-orange-500 hover:bg-orange-600 text-white font-bold text-sm px-4 py-2.5 rounded-xl shadow transition">
                    + Tambah Menu
                </button>
            </div>
        </div>

        {{-- Setting Poin Harian --}}
        @php $poinHarian = \DB::table('settings')->where('key','poin_harian')->value('value') ?? 100; @endphp
        <div class="bg-gradient-to-r from-purple-50 to-blue-50 border border-purple-200 rounded-2xl p-4 mb-6 fade-up flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm font-bold text-purple-800">⚡ Kapasitas Poin Harian</p>
                <p class="text-xs text-purple-500 mt-0.5">Total poin pesanan yang bisa diterima per hari</p>
            </div>
            <div class="flex items-center gap-2">
                <input type="number" id="input-poin-harian" value="{{ $poinHarian }}" min="1" class="w-24 px-3 py-2 rounded-lg border border-purple-300 text-center font-bold text-purple-800 focus:ring-2 focus:ring-purple-500 outline-none text-sm">
                <button onclick="savePoinHarian()" class="bg-purple-600 hover:bg-purple-700 text-white font-bold text-sm px-4 py-2 rounded-lg transition">Simpan</button>
            </div>
        </div>

        {{-- Filter --}}
        <div class="fade-up mb-4 flex flex-wrap gap-3">
            <div class="w-full md:w-48">
                <select id="admin-cat-select" class="w-full px-4 py-3 rounded-xl bg-white border border-slate-200 focus:ring-2 focus:ring-orange-500 text-sm outline-none transition shadow-sm font-semibold text-slate-700">
                    <option value="Semua">Semua Kategori</option>
                    @foreach($kategori as $k)
                        <option value="{{ $k->nama }}">{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-48">
                <select id="admin-status-select" class="w-full px-4 py-3 rounded-xl bg-white border border-slate-200 focus:ring-2 focus:ring-orange-500 text-sm outline-none transition shadow-sm font-semibold text-slate-700">
                    <option value="semua">📋 Semua Status</option>
                    <option value="aktif">✅ Aktif Saja</option>
                    <option value="nonaktif">⛔ Nonaktif Saja</option>
                    <option value="spesial">⭐ Spesial Saja</option>
                    <option value="promo">🏷️ Promo Saja</option>
                    <option value="preorder">🕐 Pre-order Saja</option>
                    <option value="catering">🍱 Catering Saja</option>
                </select>
            </div>
            <input type="text" id="admin-search" placeholder="Cari menu..." class="flex-1 min-w-[150px] px-4 py-3 rounded-xl bg-white border border-slate-200 focus:ring-2 focus:ring-orange-500 text-sm outline-none transition shadow-sm">
        </div>

        {{-- Tabel Menu --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden fade-up">
            <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-3 bg-slate-50 border-b border-slate-100 text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">
                <div class="col-span-1">Foto</div>
                <div class="col-span-2">Menu</div>
                <div class="col-span-2">Kategori</div>
                <div class="col-span-1">Harga</div>
                <div class="col-span-1">Poin</div>
                <div class="col-span-1">Spesial</div>
                <div class="col-span-1">Aktif</div>
                <div class="col-span-1">Promo</div>
                <div class="col-span-1">Catering</div>
                <div class="col-span-1 text-right">Aksi</div>
            </div>
            <div id="menu-list"></div>
            <div id="menu-empty" class="hidden text-center py-12">
                <div class="text-5xl mb-3">📋</div>
                <p class="text-slate-500 font-semibold">Tidak ada menu ditemukan.</p>
            </div>
        </div>
    </div>
</div>

{{-- ===== MODAL TAMBAH/EDIT MENU ===== --}}
<div id="modal-menu" class="modal-overlay hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-60 flex items-center justify-center p-4">
    <div class="modal-content bg-white rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="sticky top-0 bg-slate-50 px-6 py-4 border-b border-slate-100 flex justify-between items-center z-10">
            <h2 id="modal-menu-title" class="text-lg font-bold text-slate-800">Tambah Menu Baru</h2>
            <button onclick="closeModalMenu()" class="text-slate-400 hover:text-red-500 text-xl font-bold">&times;</button>
        </div>
        {{-- 
        ===================================================================
        FORM INI MENGGUNAKAN enctype="multipart/form-data"
        Artinya form ini BISA mengirim file (gambar) ke server.
        Tanpa enctype ini, file tidak akan terkirim!
        ===================================================================
        --}}
        <form id="form-menu" enctype="multipart/form-data" class="p-6 space-y-4">
            <input type="hidden" id="form-id" value="">

            {{-- Gambar --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Foto Menu</label>
                <input type="file" id="form-gambar" accept="image/*"
                       class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 cursor-pointer">
                <div id="img-preview" class="mt-2 hidden relative">
                    <img id="img-preview-el" class="w-full h-32 object-cover rounded-xl border border-slate-100" src="" alt="Preview">
                    <button type="button" id="btn-hapus-gambar" onclick="hapusGambar()" class="absolute top-1 right-1 bg-red-500 hover:bg-red-600 text-white text-[10px] font-bold px-2 py-1 rounded-lg shadow hidden">
                        ✕ Hapus Gambar
                    </button>
                </div>
            </div>

            {{-- Nama --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Nama Menu</label>
                <input type="text" id="form-nama" required class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-orange-500 outline-none">
            </div>

            <div class="grid grid-cols-2 gap-4">
                {{-- Kategori --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Kategori</label>
                    <select id="form-id_kategori" required class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-orange-500 outline-none">
                        @foreach($kategori as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Harga --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Harga (Rp)</label>
                    <input type="number" id="form-harga" required class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-orange-500 outline-none">
                </div>
            </div>

            {{-- Poin --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Poin (bobot per porsi)</label>
                    <input type="number" id="form-poin" class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-orange-500 outline-none" placeholder="misal: 5">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Pre-order (hari)</label>
                    <input type="number" id="form-preorder" min="0" class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-orange-500 outline-none" placeholder="0 = langsung">
                </div>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Deskripsi</label>
                <textarea id="form-deskripsi" rows="2" class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-orange-500 outline-none" placeholder="Deskripsi singkat menu ini..."></textarea>
            </div>

            <button type="submit" id="btn-submit-menu" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-xl mt-2 transition">
                Simpan Ke Database
            </button>
        </form>
    </div>
</div>

{{-- ===== MODAL KELOLA KATEGORI ===== --}}
<div id="modal-kategori" class="modal-overlay hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-60 flex items-center justify-center p-4">
    <div class="modal-content bg-white rounded-2xl w-full max-w-md shadow-2xl overflow-hidden flex flex-col" style="max-height:80vh">
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex justify-between items-center shrink-0">
            <h2 class="text-lg font-bold text-slate-800">Kelola Kategori</h2>
            <button onclick="closeModalKategori()" class="text-slate-400 hover:text-red-500 text-xl font-bold">&times;</button>
        </div>
        <div class="p-6 overflow-y-auto flex-1">
            <ul class="space-y-2 mb-6" id="kategori-list-ui">
                @foreach($kategori as $k)
                    <li class="flex justify-between items-center bg-slate-50 px-4 py-2 rounded-lg border border-slate-100">
                        <span class="font-semibold text-slate-700">{{ $k->nama }}</span>
                        <button onclick="deleteKategori({{ $k->id }})" class="text-red-500 hover:bg-red-50 p-1.5 rounded-md text-xs font-bold transition">Hapus</button>
                    </li>
                @endforeach
            </ul>
            <form id="form-kategori" class="flex gap-2">
                <input type="text" id="form-kategori-nama" placeholder="Nama kategori baru..." required maxlength="20" class="flex-1 px-4 py-2 rounded-lg border focus:ring-2 focus:ring-orange-500 outline-none text-sm">
                <button type="submit" class="bg-slate-800 hover:bg-black text-white px-4 py-2 rounded-lg font-bold text-sm transition">Tambah</button>
            </form>
            <p class="text-[10px] text-slate-400 mt-1">Maks 20 karakter</p>
        </div>
    </div>
</div>

{{-- ===== MODAL PROMO ===== --}}
<div id="modal-promo" class="modal-overlay hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-60 flex items-center justify-center p-4">
    <div class="modal-content bg-white rounded-2xl w-full max-w-sm shadow-2xl overflow-hidden">
        <div class="bg-red-50 px-6 py-4 border-b border-red-100 flex justify-between items-center">
            <h2 class="text-lg font-bold text-red-700">🏷️ Atur Promo</h2>
            <button onclick="closeModalPromo()" class="text-slate-400 hover:text-red-500 text-xl font-bold">&times;</button>
        </div>
        <div class="p-6">
            <input type="hidden" id="promo-menu-id">
            <p id="promo-menu-nama" class="font-bold text-slate-800 mb-1"></p>
            <p id="promo-harga-asli" class="text-sm text-slate-500 mb-4"></p>
            <label class="block text-sm font-bold text-slate-700 mb-1">Harga Promo (Rp)</label>
            <input type="number" id="form-harga-promo" placeholder="Kosongkan untuk hapus promo" class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-red-500 outline-none mb-1">
            <p id="promo-error" class="text-red-500 text-xs font-bold hidden">Harga promo harus lebih rendah dari harga asli!</p>
            <div class="flex gap-2 mt-4">
                <button onclick="savePromo()" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-bold py-2.5 rounded-xl transition">Simpan Promo</button>
                <button onclick="hapusPromo()" class="bg-slate-200 hover:bg-slate-300 text-slate-600 font-bold py-2.5 px-4 rounded-xl transition">Hapus</button>
            </div>
        </div>
    </div>
</div>

{{-- ===== MODAL CATERING PRICE ===== --}}
<div id="modal-catering" class="modal-overlay hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-60 flex items-center justify-center p-4">
    <div class="modal-content bg-white rounded-2xl w-full max-w-md shadow-2xl overflow-hidden">
        <div class="bg-blue-50 px-6 py-4 border-b border-blue-100 flex justify-between items-center">
            <h2 class="text-lg font-bold text-blue-700">🍱 Harga Catering</h2>
            <button onclick="closeCatering()" class="text-slate-400 hover:text-red-500 text-xl font-bold">&times;</button>
        </div>
        <div class="p-6">
            <input type="hidden" id="catering-menu-id">
            <p id="catering-menu-nama" class="font-bold text-slate-800 mb-1"></p>
            <p id="catering-harga-normal" class="text-sm text-slate-500 mb-4"></p>

            <div id="catering-tiers" class="space-y-2 mb-4 max-h-[300px] overflow-y-auto"></div>

            <button onclick="addCateringTier()" class="w-full py-2 rounded-lg border-2 border-dashed border-blue-300 text-blue-600 text-sm font-bold hover:bg-blue-50 transition mb-4">
                + Tambah Tier Harga
            </button>

            <button onclick="saveCateringPrices()" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2.5 rounded-xl transition">
                💾 Simpan Harga Catering
            </button>
        </div>
    </div>
</div>

<script>
(function() {
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
const BASE = '{{ url("/") }}';
const STORAGE = '{{ asset("storage") }}';

// ==================================================
// 1. DATA DARI DATABASE
// ==================================================
const rawMenu = @json($menu);

let menuItems = rawMenu.map(m => ({
    id: m.id,
    nama: m.nama,
    kategori: m.kategori ? m.kategori.nama : 'Tanpa Kategori',
    id_kategori: m.id_kategori,
    harga: m.harga,
    harga_promo: m.harga_promo || null,
    poin: m.poin || '',
    preorder_hari: m.preorder_hari || 0,
    deskripsi: m.deskripsi || '',
    gambar: m.gambar || '',
    is_spesial: m.is_spesial || 0,
    is_aktif: m.is_aktif ?? 1,
    catering_tersedia: m.catering_tersedia || 0,
    harga_catering: m.harga_catering || [],
}));

// ==================================================
// 2. RENDER TABEL
// ==================================================
const listEl = document.getElementById('menu-list');
const emptyEl = document.getElementById('menu-empty');

function render() {
    const q = document.getElementById('admin-search').value.toLowerCase();
    const cat = document.getElementById('admin-cat-select').value;
    const status = document.getElementById('admin-status-select').value;

    const filtered = menuItems.filter(item => {
        const matchCat = (cat === 'Semua' || item.kategori === cat);
        const matchName = (!q || item.nama.toLowerCase().includes(q));
        let matchStatus = true;
        if (status === 'aktif') matchStatus = item.is_aktif == 1;
        else if (status === 'nonaktif') matchStatus = item.is_aktif == 0;
        else if (status === 'spesial') matchStatus = item.is_spesial == 1;
        else if (status === 'promo') matchStatus = !!item.harga_promo;
        else if (status === 'preorder') matchStatus = item.preorder_hari > 0;
        else if (status === 'catering') matchStatus = item.catering_tersedia == 1;
        return matchCat && matchName && matchStatus;
    });

    if (filtered.length === 0) {
        listEl.innerHTML = '';
        emptyEl.classList.remove('hidden');
        return;
    }
    emptyEl.classList.add('hidden');

    listEl.innerHTML = filtered.map(item => {
        let poinHtml = '<span class="text-slate-300 text-xs">0</span>';
        if(item.poin) {
            poinHtml = `<span class="text-[10px] font-bold text-purple-700 bg-purple-100 px-2 py-0.5 rounded-full">⚡${item.poin}</span>`;
        }

        const imgSrc = item.gambar
            ? `${STORAGE}/${item.gambar}`
            : 'https://placehold.co/100x100/f1f5f9/94a3b8?text=No+Img';

        const starClass = item.is_spesial
            ? 'text-yellow-500 bg-yellow-50 border-yellow-200'
            : 'text-slate-300 bg-slate-50 border-slate-200';

        const aktifClass = item.is_aktif
            ? 'text-emerald-600 bg-emerald-50 border-emerald-200'
            : 'text-red-400 bg-red-50 border-red-200';
        const aktifIcon = item.is_aktif ? '✅' : '⛔';

        const promoClass = item.harga_promo
            ? 'text-red-600 bg-red-50 border-red-200'
            : 'text-slate-300 bg-slate-50 border-slate-200';
        const promoLabel = item.harga_promo ? '🏷️' : '—';

        let hargaHtml = `Rp ${Number(item.harga).toLocaleString('id-ID')}`;
        if (item.harga_promo) {
            hargaHtml = `<span class="line-through text-slate-400 text-[10px]">Rp ${Number(item.harga).toLocaleString('id-ID')}</span><br><span class="text-red-600">Rp ${Number(item.harga_promo).toLocaleString('id-ID')}</span>`;
        }

        const preorderBadge = item.preorder_hari > 0
            ? `<span class="text-[9px] font-bold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded-full">🕐 PO ${item.preorder_hari}h</span>`
            : '';

        const cateringClass = item.catering_tersedia
            ? 'text-blue-600 bg-blue-50 border-blue-200'
            : 'text-slate-300 bg-slate-50 border-slate-200';
        const cateringIcon = item.catering_tersedia ? '🍱' : '—';

        const rowOpacity = item.is_aktif ? '' : 'opacity-50';

        return `
        <div class="${rowOpacity}">
            {{-- MOBILE CARD (visible < md) --}}
            <div class="md:hidden px-4 py-3 border-b border-slate-50 bg-white">
                <div class="flex gap-3 items-start">
                    <img src="${imgSrc}" alt="${item.nama}" class="w-14 h-14 rounded-xl object-cover border border-slate-100 shrink-0">
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-sm text-slate-800 truncate">${item.nama}</p>
                        <div class="flex items-center gap-1.5 mt-0.5 flex-wrap">
                            <span class="text-[10px] font-semibold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-lg">${item.kategori}</span>
                            ${poinHtml}
                            ${preorderBadge}
                        </div>
                        <p class="font-bold text-xs text-slate-800 mt-1">${hargaHtml}</p>
                    </div>
                </div>
                <div class="flex items-center gap-1.5 mt-2.5 flex-wrap">
                    <button onclick="toggleAktif(${item.id})" class="p-1.5 rounded-lg border text-xs transition ${aktifClass}" title="Toggle Aktif">${aktifIcon}</button>
                    <button onclick="toggleSpesial(${item.id})" class="p-1.5 rounded-lg border text-xs transition ${starClass}" title="Toggle Spesial">⭐</button>
                    <button onclick="openPromo(${item.id})" class="p-1.5 rounded-lg border text-xs transition ${promoClass}" title="Atur Promo">${promoLabel}</button>
                    <button onclick="openCatering(${item.id})" class="p-1.5 rounded-lg border text-xs transition ${cateringClass}" title="Harga Catering">${cateringIcon}</button>
                    <div class="flex-1"></div>
                    <button onclick="editMenu(${item.id})" class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1.5 rounded-lg">Edit</button>
                    <button onclick="deleteMenu(${item.id})" class="text-xs font-bold text-red-600 bg-red-50 px-3 py-1.5 rounded-lg">Hapus</button>
                </div>
            </div>

            {{-- DESKTOP ROW (visible >= md) --}}
            <div class="hidden md:grid md:grid-cols-12 md:gap-4 md:items-center px-6 py-3 border-b border-slate-50 hover:bg-slate-50/50 transition bg-white">
                <div class="col-span-1">
                    <img src="${imgSrc}" alt="${item.nama}" class="w-10 h-10 rounded-lg object-cover border border-slate-100">
                </div>
                <div class="col-span-2">
                    <p class="font-bold text-sm text-slate-800 truncate">${item.nama}</p>
                    ${preorderBadge}
                </div>
                <div class="col-span-2 min-w-0">
                    <span class="inline-block max-w-full truncate text-[10px] font-semibold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-lg" title="${item.kategori}">
                        ${item.kategori}
                    </span>
                </div>
                <div class="col-span-1 font-bold text-slate-800 text-xs">${hargaHtml}</div>
                <div class="col-span-1">${poinHtml}</div>
                <div class="col-span-1">
                    <button onclick="toggleSpesial(${item.id})" class="p-1.5 rounded-lg border text-sm transition hover:scale-110 ${starClass}" title="Toggle Spesial">⭐</button>
                </div>
                <div class="col-span-1">
                    <button onclick="toggleAktif(${item.id})" class="p-1.5 rounded-lg border text-sm transition hover:scale-110 ${aktifClass}" title="Toggle Aktif">${aktifIcon}</button>
                </div>
                <div class="col-span-1">
                    <button onclick="openPromo(${item.id})" class="p-1.5 rounded-lg border text-sm transition hover:scale-110 ${promoClass}" title="Atur Promo">${promoLabel}</button>
                </div>
                <div class="col-span-1 flex gap-1">
                    <button onclick="openCatering(${item.id})" class="p-1.5 rounded-lg border text-sm transition hover:scale-110 ${cateringClass}" title="Harga Catering">${cateringIcon}</button>
                </div>
                <div class="col-span-1 flex justify-end gap-2">
                    <button onclick="editMenu(${item.id})" class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1.5 rounded-lg hover:bg-blue-100 transition">Edit</button>
                    <button onclick="deleteMenu(${item.id})" class="text-xs font-bold text-red-600 bg-red-50 px-3 py-1.5 rounded-lg hover:bg-red-100 transition">Hapus</button>
                </div>
            </div>
        </div>`;
    }).join('');
}

document.getElementById('admin-search').addEventListener('input', render);
document.getElementById('admin-cat-select').addEventListener('change', render);
document.getElementById('admin-status-select').addEventListener('change', render);

// ==================================================
// 3. FORM MENU (TAMBAH & EDIT) — DENGAN FILE UPLOAD
// ==================================================
const modalMenu = document.getElementById('modal-menu');
const formMenu = document.getElementById('form-menu');
const fileInput = document.getElementById('form-gambar');
const previewDiv = document.getElementById('img-preview');
const previewImg = document.getElementById('img-preview-el');

// Preview gambar saat file dipilih
fileInput.addEventListener('change', function() {
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { previewImg.src = e.target.result; previewDiv.classList.remove('hidden'); };
        reader.readAsDataURL(this.files[0]);
    }
});

window.openModalMenu = function() {
    document.getElementById('form-id').value = '';
    document.getElementById('form-nama').value = '';
    document.getElementById('form-harga').value = '';
    document.getElementById('form-poin').value = '';
    document.getElementById('form-preorder').value = '0';
    document.getElementById('form-deskripsi').value = '';
    fileInput.value = '';
    previewDiv.classList.add('hidden');
    document.getElementById('modal-menu-title').innerText = "Tambah Menu Baru";
    document.getElementById('btn-submit-menu').innerText = "Simpan Ke Database";
    modalMenu.classList.remove('hidden');
};

window.editMenu = function(id) {
    const item = menuItems.find(m => m.id === id);
    if (!item) return;
    document.getElementById('form-id').value = item.id;
    document.getElementById('form-nama').value = item.nama;
    document.getElementById('form-id_kategori').value = item.id_kategori;
    document.getElementById('form-harga').value = item.harga;
    document.getElementById('form-poin').value = item.poin;
    document.getElementById('form-preorder').value = item.preorder_hari || 0;
    document.getElementById('form-deskripsi').value = item.deskripsi;
    fileInput.value = '';
    if (item.gambar) {
        previewImg.src = `${STORAGE}/${item.gambar}`;
        previewDiv.classList.remove('hidden');
        document.getElementById('btn-hapus-gambar').classList.remove('hidden');
    } else {
        previewDiv.classList.add('hidden');
        document.getElementById('btn-hapus-gambar').classList.add('hidden');
    }
    document.getElementById('modal-menu-title').innerText = "Edit Menu";
    document.getElementById('btn-submit-menu').innerText = "Update Ke Database";
    modalMenu.classList.remove('hidden');
};

window.closeModalMenu = function() { modalMenu.classList.add('hidden'); };

formMenu.addEventListener('submit', function(e) {
    e.preventDefault();

    const id = document.getElementById('form-id').value;
    const isEdit = id !== '';
    const url = isEdit ? `${BASE}/admin/menu-test/update/${id}` : `${BASE}/admin/menu-test/store`;

    /*
    ===================================================================
    Menggunakan FormData agar bisa mengirim file gambar.
    Berbeda dengan JSON.stringify, FormData BISA membawa binary file.
    ===================================================================
    */
    const formData = new FormData();
    formData.append('nama', document.getElementById('form-nama').value);
    formData.append('id_kategori', document.getElementById('form-id_kategori').value);
    formData.append('harga', document.getElementById('form-harga').value);
    formData.append('poin', document.getElementById('form-poin').value || '');
    formData.append('preorder_hari', document.getElementById('form-preorder').value || '0');
    formData.append('deskripsi', document.getElementById('form-deskripsi').value || '');

    // Lampirkan file gambar HANYA jika admin memilih file baru
    const file = fileInput.files[0];
    if (file) formData.append('gambar', file);

    fetch(url, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken },
        // Jangan set 'Content-Type' header! Browser akan set otomatis untuk FormData
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            window.location.reload();
        } else {
            alert('Gagal: ' + (data.message || 'Terjadi kesalahan'));
        }
    })
    .catch(err => alert('Error jaringan: ' + err.message));
});

// ==================================================
// 4. HAPUS MENU
// ==================================================
window.deleteMenu = function(id) {
    if (!confirm('Yakin ingin menghapus menu ini?')) return;
    fetch(`${BASE}/admin/menu-test/delete/${id}`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken }
    })
    .then(res => res.json())
    .then(data => { alert(data.message); window.location.reload(); });
};

// ==================================================
// 5. TOGGLE SPESIAL MINGGU INI ⭐
// ==================================================
window.toggleSpesial = function(id) {
    fetch(`${BASE}/admin/menu-test/toggle-spesial/${id}`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Update data lokal tanpa reload
            const item = menuItems.find(m => m.id === id);
            if (item) item.is_spesial = data.is_spesial;
            render();
        }
    });
};

// ==================================================
// 6. TOGGLE AKTIF/NONAKTIF
// ==================================================
window.toggleAktif = function(id) {
    fetch(`${BASE}/admin/menu-test/toggle-aktif/${id}`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const item = menuItems.find(m => m.id === id);
            if (item) item.is_aktif = data.is_aktif;
            render();
        }
    });
};

// ==================================================
// 7. HAPUS GAMBAR SAJA
// ==================================================
window.hapusGambar = function() {
    const id = document.getElementById('form-id').value;
    if (!id) return;
    if (!confirm('Hapus gambar menu ini?')) return;
    fetch(`${BASE}/admin/menu-test/delete-gambar/${id}`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const item = menuItems.find(m => m.id == id);
            if (item) item.gambar = '';
            previewDiv.classList.add('hidden');
            document.getElementById('btn-hapus-gambar').classList.add('hidden');
            render();
        }
    });
};

// ==================================================
// 8. KATEGORI (TAMBAH & HAPUS)
// ==================================================
const modalKategori = document.getElementById('modal-kategori');
window.openModalKategori = function() { modalKategori.classList.remove('hidden'); };
window.closeModalKategori = function() { modalKategori.classList.add('hidden'); };

document.getElementById('form-kategori').addEventListener('submit', function(e) {
    e.preventDefault();
    const nama = document.getElementById('form-kategori-nama').value;
    fetch(`${BASE}/admin/kategori-test/store`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ nama })
    })
    .then(r => r.json())
    .then(() => { alert("Kategori ditambahkan!"); window.location.reload(); });
});

window.deleteKategori = function(id) {
    if (!confirm('Yakin hapus kategori ini?')) return;
    fetch(`${BASE}/admin/kategori-test/delete/${id}`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken }
    })
    .then(r => r.json())
    .then(data => {
        alert(data.message);
        if (data.success) window.location.reload();
    });
};
// ==================================================
// 9. PROMO
// ==================================================
const modalPromo = document.getElementById('modal-promo');
window.openPromo = function(id) {
    const item = menuItems.find(m => m.id === id);
    if (!item) return;
    document.getElementById('promo-menu-id').value = item.id;
    document.getElementById('promo-menu-nama').textContent = item.nama;
    document.getElementById('promo-harga-asli').textContent = 'Harga asli: Rp ' + Number(item.harga).toLocaleString('id-ID');
    document.getElementById('form-harga-promo').value = item.harga_promo || '';
    document.getElementById('promo-error').classList.add('hidden');
    modalPromo.classList.remove('hidden');
};
window.closeModalPromo = function() { modalPromo.classList.add('hidden'); };

window.savePromo = function() {
    const id = document.getElementById('promo-menu-id').value;
    const item = menuItems.find(m => m.id == id);
    const hargaPromo = document.getElementById('form-harga-promo').value;

    if (hargaPromo && Number(hargaPromo) >= Number(item.harga)) {
        document.getElementById('promo-error').classList.remove('hidden');
        return;
    }
    document.getElementById('promo-error').classList.add('hidden');

    fetch(`${BASE}/admin/menu-test/set-promo/${id}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ harga_promo: hargaPromo || null })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            item.harga_promo = data.harga_promo;
            render();
            closeModalPromo();
        }
    });
};

window.hapusPromo = function() {
    const id = document.getElementById('promo-menu-id').value;
    fetch(`${BASE}/admin/menu-test/set-promo/${id}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ harga_promo: null })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const item = menuItems.find(m => m.id == id);
            if (item) item.harga_promo = null;
            render();
            closeModalPromo();
        }
    });
};

// ==================================================
// 10. SAVE POIN HARIAN
// ==================================================
window.savePoinHarian = function() {
    const val = document.getElementById('input-poin-harian').value;
    if (!val || val < 1) { alert('Poin harian harus minimal 1'); return; }
    fetch(`${BASE}/admin/settings/poin-harian`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ value: val })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) alert('Poin harian disimpan: ' + val);
        else alert('Gagal menyimpan');
    });
};

// ==================================================
// 11. CATERING PRICES
// ==================================================
let cateringTiers = [];
let cateringMenuId = null;

window.openCatering = function(id) {
    const item = menuItems.find(m => m.id === id);
    if (!item) return;
    cateringMenuId = id;
    document.getElementById('catering-menu-id').value = id;
    document.getElementById('catering-menu-nama').textContent = item.nama;
    document.getElementById('catering-harga-normal').textContent = 'Harga normal: Rp ' + Number(item.harga).toLocaleString('id-ID');

    // First, toggle catering on if not already
    if (!item.catering_tersedia) {
        fetch(`${BASE}/admin/menu-test/toggle-catering/${id}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken }
        }).then(r => r.json()).then(data => {
            if (data.success) { item.catering_tersedia = data.catering_tersedia; render(); }
        });
    }

    // Load existing tiers
    fetch(`${BASE}/admin/menu-test/catering-prices/${id}`)
        .then(r => r.json())
        .then(data => {
            cateringTiers = data.map(t => ({ min_porsi: t.min_porsi, max_porsi: t.max_porsi, harga_per_porsi: t.harga_per_porsi }));
            if (cateringTiers.length === 0) {
                cateringTiers.push({ min_porsi: 20, max_porsi: 50, harga_per_porsi: Math.round(item.harga * 0.9) });
            }
            renderCateringTiers();
            document.getElementById('modal-catering').classList.remove('hidden');
        });
};

window.closeCatering = function() { document.getElementById('modal-catering').classList.add('hidden'); };

function renderCateringTiers() {
    const el = document.getElementById('catering-tiers');
    el.innerHTML = cateringTiers.map((t, i) => `
        <div class="flex items-center gap-2 bg-blue-50/50 border border-blue-100 rounded-xl p-3">
            <div class="flex-1 grid grid-cols-3 gap-2">
                <div>
                    <label class="text-[9px] font-bold text-slate-500 uppercase">Min</label>
                    <input type="number" value="${t.min_porsi}" min="1" class="w-full px-2 py-1.5 rounded-lg border text-sm font-bold text-center" onchange="cateringTiers[${i}].min_porsi=parseInt(this.value)">
                </div>
                <div>
                    <label class="text-[9px] font-bold text-slate-500 uppercase">Max</label>
                    <input type="number" value="${t.max_porsi || ''}" min="0" placeholder="∞" class="w-full px-2 py-1.5 rounded-lg border text-sm font-bold text-center" onchange="cateringTiers[${i}].max_porsi=parseInt(this.value)||null">
                </div>
                <div>
                    <label class="text-[9px] font-bold text-slate-500 uppercase">Harga/porsi</label>
                    <input type="number" value="${t.harga_per_porsi}" min="0" class="w-full px-2 py-1.5 rounded-lg border text-sm font-bold text-center" onchange="cateringTiers[${i}].harga_per_porsi=parseInt(this.value)">
                </div>
            </div>
            <button onclick="cateringTiers.splice(${i},1);renderCateringTiers()" class="text-red-400 hover:text-red-600 p-1 text-lg" title="Hapus tier">✕</button>
        </div>
    `).join('');
}

window.addCateringTier = function() {
    const last = cateringTiers[cateringTiers.length - 1];
    const minNext = last ? (last.max_porsi ? last.max_porsi + 1 : 100) : 20;
    cateringTiers.push({ min_porsi: minNext, max_porsi: null, harga_per_porsi: 0 });
    renderCateringTiers();
};

window.saveCateringPrices = function() {
    fetch(`${BASE}/admin/menu-test/save-catering-prices/${cateringMenuId}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ tiers: cateringTiers }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert('Harga catering tersimpan!');
            closeCatering();
        }
    });
};

// Render awal
render();
})();
</script>
</body>
</html>
