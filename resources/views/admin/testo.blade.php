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

        {{-- Filter --}}
        <div class="fade-up mb-4 flex gap-3">
            <div class="w-full md:w-64">
                <select id="admin-cat-select" class="w-full px-4 py-3 rounded-xl bg-white border border-slate-200 focus:ring-2 focus:ring-orange-500 text-sm outline-none transition shadow-sm font-semibold text-slate-700">
                    <option value="Semua">Semua Kategori</option>
                    @foreach($kategori as $k)
                        <option value="{{ $k->nama }}">{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>
            <input type="text" id="admin-search" placeholder="Cari menu..." class="flex-1 px-4 py-3 rounded-xl bg-white border border-slate-200 focus:ring-2 focus:ring-orange-500 text-sm outline-none transition shadow-sm">
        </div>

        {{-- Tabel Menu --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden fade-up">
            <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-3 bg-slate-50 border-b border-slate-100 text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">
                <div class="col-span-1">Foto</div>
                <div class="col-span-2">Menu</div>
                <div class="col-span-2">Kategori</div>
                <div class="col-span-1">Harga</div>
                <div class="col-span-1">Kuota</div>
                <div class="col-span-1">Spesial</div>
                <div class="col-span-1">Aktif</div>
                <div class="col-span-3 text-right">Aksi</div>
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

            {{-- Kuota --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Kuota Harian (Kosongkan = tak terbatas)</label>
                <input type="number" id="form-kuota" class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-orange-500 outline-none">
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
    <div class="modal-content bg-white rounded-2xl w-full max-w-md shadow-2xl overflow-hidden">
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
            <h2 class="text-lg font-bold text-slate-800">Kelola Kategori</h2>
            <button onclick="closeModalKategori()" class="text-slate-400 hover:text-red-500 text-xl font-bold">&times;</button>
        </div>
        <div class="p-6">
            <ul class="space-y-2 mb-6" id="kategori-list-ui">
                @foreach($kategori as $k)
                    <li class="flex justify-between items-center bg-slate-50 px-4 py-2 rounded-lg border border-slate-100">
                        <span class="font-semibold text-slate-700">{{ $k->nama }}</span>
                        <button onclick="deleteKategori({{ $k->id }})" class="text-red-500 hover:bg-red-50 p-1.5 rounded-md text-xs font-bold transition">Hapus</button>
                    </li>
                @endforeach
            </ul>
            <form id="form-kategori" class="flex gap-2">
                <input type="text" id="form-kategori-nama" placeholder="Nama kategori baru..." required class="flex-1 px-4 py-2 rounded-lg border focus:ring-2 focus:ring-orange-500 outline-none text-sm">
                <button type="submit" class="bg-slate-800 hover:bg-black text-white px-4 py-2 rounded-lg font-bold text-sm transition">Tambah</button>
            </form>
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
    kuota: m.kuota || '',
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

    const filtered = menuItems.filter(item => {
        const matchCat = (cat === 'Semua' || item.kategori === cat);
        const matchName = (!q || item.nama.toLowerCase().includes(q));
        return matchCat && matchName;
    });

    if (filtered.length === 0) {
        listEl.innerHTML = '';
        emptyEl.classList.remove('hidden');
        return;
    }
    emptyEl.classList.add('hidden');

    listEl.innerHTML = filtered.map(item => {
        let quotaHtml = '<span class="text-slate-300 text-xs">∞</span>';
        if(item.kuota) {
            quotaHtml = `<span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">Sisa ${item.kuota}</span>`;
        }

        const imgSrc = item.gambar
            ? `${STORAGE}/${item.gambar}`
            : 'https://placehold.co/100x100/f1f5f9/94a3b8?text=No+Img';

        const starClass = item.is_spesial
            ? 'text-yellow-500 bg-yellow-50 border-yellow-200'
            : 'text-slate-300 bg-slate-50 border-slate-200';

        // Toggle aktif styling
        const aktifClass = item.is_aktif
            ? 'text-emerald-600 bg-emerald-50 border-emerald-200'
            : 'text-red-400 bg-red-50 border-red-200';
        const aktifIcon = item.is_aktif ? '✅' : '⛔';

        // Row opacity jika nonaktif
        const rowOpacity = item.is_aktif ? '' : 'opacity-50';

        return `
        <div class="md:grid md:grid-cols-12 md:gap-4 md:items-center px-4 md:px-6 py-3 border-b border-slate-50 hover:bg-slate-50/50 transition bg-white ${rowOpacity}">
            <div class="col-span-1 hidden md:block">
                <img src="${imgSrc}" alt="${item.nama}" class="w-10 h-10 rounded-lg object-cover border border-slate-100">
            </div>
            <div class="col-span-2">
                <p class="font-bold text-sm text-slate-800 truncate">${item.nama}</p>
                <p class="text-[11px] text-slate-400 md:hidden">${item.kategori} · Rp ${Number(item.harga).toLocaleString('id-ID')}</p>
            </div>
            <div class="col-span-2 hidden md:block">
                <span class="text-xs font-semibold text-slate-600 bg-slate-100 px-2.5 py-0.5 rounded-lg">${item.kategori}</span>
            </div>
            <div class="col-span-1 hidden md:block font-bold text-slate-800 text-sm">Rp ${Number(item.harga).toLocaleString('id-ID')}</div>
            <div class="col-span-1 hidden md:block">${quotaHtml}</div>
            <div class="col-span-1 hidden md:block">
                <button onclick="toggleSpesial(${item.id})" class="p-1.5 rounded-lg border text-sm transition hover:scale-110 ${starClass}" title="Toggle Spesial">⭐</button>
            </div>
            <div class="col-span-1 hidden md:block">
                <button onclick="toggleAktif(${item.id})" class="p-1.5 rounded-lg border text-sm transition hover:scale-110 ${aktifClass}" title="Toggle Aktif">${aktifIcon}</button>
            </div>
            <div class="col-span-3 flex justify-end gap-2">
                <button onclick="editMenu(${item.id})" class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1.5 rounded-lg hover:bg-blue-100 transition">Edit</button>
                <button onclick="deleteMenu(${item.id})" class="text-xs font-bold text-red-600 bg-red-50 px-3 py-1.5 rounded-lg hover:bg-red-100 transition">Hapus</button>
            </div>
        </div>`;
    }).join('');
}

document.getElementById('admin-search').addEventListener('input', render);
document.getElementById('admin-cat-select').addEventListener('change', render);

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
    document.getElementById('form-kuota').value = '';
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
    document.getElementById('form-kuota').value = item.kuota;
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
    formData.append('kuota', document.getElementById('form-kuota').value || '');
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

// Render awal
render();
})();
</script>
</body>
</html>
