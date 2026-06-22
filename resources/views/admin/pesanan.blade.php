<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pesanan Masuk - Admin Monika's Kitchen</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        @keyframes fadeUp { from{opacity:0;transform:translateY(18px);}to{opacity:1;transform:translateY(0);} }
        .fade-up { animation: fadeUp 0.5s ease both; }
        .filter-btn { transition: all 0.2s; }
        .filter-btn.active { background: #ea580c; color: white; box-shadow: 0 4px 12px rgba(234,88,12,0.3); }
        .modal-overlay { transition: opacity 0.25s; }
        .modal-overlay.hidden { opacity:0; pointer-events:none; }
    </style>
</head>
<body class="bg-slate-50 antialiased">

<x-admin-sidebar active="pesanan" />

<div class="md:pl-64 min-h-screen pb-20">
    <div class="px-4 md:px-10 py-6 md:py-10 max-w-6xl">

        {{-- Header --}}
        <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-orange-500 text-xs font-bold uppercase tracking-widest mb-1">Realtime</p>
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900">Pesanan Masuk</h1>
                <p class="text-slate-400 text-sm mt-1">Kelola semua pesanan customer dari sini.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ url('/admin/pesanan/export?filter=' . $filter . '&format=csv') }}"
                   class="text-xs font-bold text-emerald-600 bg-emerald-50 px-3 py-2 rounded-lg hover:bg-emerald-100 transition border border-emerald-200">
                    📥 Export CSV
                </a>
                <a href="{{ url('/admin/pesanan/export?filter=' . $filter . '&format=txt') }}"
                   class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-2 rounded-lg hover:bg-blue-100 transition border border-blue-200">
                    📄 Export TXT
                </a>
            </div>
        </div>

        {{-- Filter Bar --}}
        <div class="flex gap-2 mb-6 flex-wrap">
            @php
                $filters = [
                    'semua' => '📋 Semua',
                    'harian' => '📅 Hari Ini',
                    'mingguan' => '📆 Minggu Ini',
                    'bulanan' => '🗓️ Bulan Ini',
                ];
            @endphp
            @foreach($filters as $key => $label)
            <a href="{{ url('/admin/pesanan?filter=' . $key) }}"
               class="filter-btn px-4 py-2 rounded-xl text-xs font-bold border border-slate-200 {{ $filter === $key ? 'active' : 'text-slate-600 bg-white hover:bg-slate-50' }}">
                {{ $label }}
            </a>
            @endforeach
        </div>

        {{-- Stats Row --}}
        @php
            $pending   = $pesanan->where('status', 'pending')->count();
            $diproses  = $pesanan->where('status', 'diproses')->count();
            $selesai   = $pesanan->where('status', 'selesai')->count();
            $totalRevenue = $pesanan->where('status', '!=', 'batal')->sum('total_harga');
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-6">
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-center">
                <p class="text-2xl font-black text-yellow-600">{{ $pending }}</p>
                <p class="text-[11px] font-bold text-yellow-500 uppercase mt-1">Pending</p>
            </div>
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-center">
                <p class="text-2xl font-black text-blue-600">{{ $diproses }}</p>
                <p class="text-[11px] font-bold text-blue-500 uppercase mt-1">Diproses</p>
            </div>
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 text-center">
                <p class="text-2xl font-black text-emerald-600">{{ $selesai }}</p>
                <p class="text-[11px] font-bold text-emerald-500 uppercase mt-1">Selesai</p>
            </div>
            <div class="bg-slate-100 border border-slate-200 rounded-xl p-4 text-center">
                <p class="text-2xl font-black text-slate-700">{{ $pesanan->count() }}</p>
                <p class="text-[11px] font-bold text-slate-400 uppercase mt-1">Total</p>
            </div>
            <div class="bg-orange-50 border border-orange-200 rounded-xl p-4 text-center col-span-2 md:col-span-1">
                <p class="text-lg font-black text-orange-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                <p class="text-[11px] font-bold text-orange-400 uppercase mt-1">Revenue</p>
            </div>
        </div>

        {{-- Order List --}}
        @if($pesanan->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-12 text-center">
            <div class="text-5xl mb-3">📋</div>
            <p class="text-slate-500 font-semibold">Belum ada pesanan di periode ini.</p>
        </div>
        @else
        <div class="space-y-3">
            @foreach($pesanan as $p)
            @php
                $statusColors = [
                    'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                    'diproses' => 'bg-blue-100 text-blue-700 border-blue-200',
                    'selesai' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                    'batal' => 'bg-red-100 text-red-700 border-red-200',
                ];
                $statusIcons = ['pending' => '⏳', 'diproses' => '🔥', 'selesai' => '✅', 'batal' => '❌'];
            @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden fade-up" id="order-{{ $p->id }}">
                <div class="px-5 py-4 flex flex-wrap items-center justify-between gap-3 border-b border-slate-50">
                    <div>
                        <p class="font-extrabold text-slate-800">{{ $p->nama_pemesan }}</p>
                        <p class="text-[11px] text-slate-400">
                            📱 {{ $p->whatsapp }} · 📅 {{ \Carbon\Carbon::parse($p->tanggal_ambil)->format('d M Y') }} · 🕐 {{ $p->waktu_ambil }}
                        </p>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-[10px] font-bold uppercase px-2.5 py-1 rounded-full border {{ $statusColors[$p->status] ?? '' }}">
                            {{ $statusIcons[$p->status] ?? '' }} {{ ucfirst($p->status) }}
                        </span>
                        <span class="text-xs font-bold {{ $p->tipe === 'catering' ? 'text-blue-600 bg-blue-50 px-2 py-0.5 rounded-lg' : 'text-slate-400' }}">
                            {{ $p->tipe === 'catering' ? '🍱 Catering' : '🍽️ Biasa' }}
                        </span>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-lg {{ ($p->metode_bayar ?? 'cod') === 'transfer' ? 'text-purple-600 bg-purple-50' : 'text-green-600 bg-green-50' }}">
                            {{ ($p->metode_bayar ?? 'cod') === 'transfer' ? '💳 Transfer' : '💵 COD' }}
                        </span>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-lg {{ ($p->metode_kirim ?? 'pickup') === 'diantar' ? 'text-orange-600 bg-orange-50' : 'text-slate-500 bg-slate-50' }}">
                            {{ ($p->metode_kirim ?? 'pickup') === 'diantar' ? '🛵 Diantar' : '🏠 Pickup' }}
                        </span>
                        <p class="font-black text-orange-600 text-sm">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="px-5 py-3 bg-slate-50/50">
                    <div class="space-y-1.5">
                        @foreach($p->detail as $d)
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-600">
                                {{ $d->menu ? $d->menu->nama : 'Menu Dihapus' }}
                                <span class="text-slate-400">x{{ $d->qty }}</span>
                                @if($d->harga_satuan && $d->menu && $d->harga_satuan != $d->menu->harga && $d->harga_satuan != $d->menu->harga_promo)
                                <span class="text-red-400 text-[10px]">(custom)</span>
                                @endif
                            </span>
                            <span class="font-bold text-slate-700">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                    @if($p->catatan)
                    <p class="text-xs text-slate-400 mt-2 italic">📝 {{ $p->catatan }}</p>
                    @endif
                    @if(($p->metode_kirim ?? 'pickup') === 'diantar' && $p->alamat_kirim)
                    <p class="text-xs text-orange-500 mt-2 font-semibold">📍 {{ $p->alamat_kirim }}</p>
                    @endif
                </div>

                {{-- Action Buttons --}}
                <div class="px-5 py-3 flex gap-2 flex-wrap border-t border-slate-50">
                    @if($p->status === 'pending')
                    <button onclick="updateStatus({{ $p->id }}, 'diproses')" class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1.5 rounded-lg hover:bg-blue-100 transition">🔥 Proses</button>
                    <button onclick="updateStatus({{ $p->id }}, 'batal')" class="text-xs font-bold text-red-500 bg-red-50 px-3 py-1.5 rounded-lg hover:bg-red-100 transition">❌ Batalkan</button>
                    <button onclick="openEditModal({{ $p->id }})" class="text-xs font-bold text-amber-600 bg-amber-50 px-3 py-1.5 rounded-lg hover:bg-amber-100 transition">✏️ Edit</button>
                    @elseif($p->status === 'diproses')
                    <button onclick="updateStatus({{ $p->id }}, 'selesai')" class="text-xs font-bold text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-lg hover:bg-emerald-100 transition">✅ Selesai</button>
                    <button onclick="openEditModal({{ $p->id }})" class="text-xs font-bold text-amber-600 bg-amber-50 px-3 py-1.5 rounded-lg hover:bg-amber-100 transition">✏️ Edit</button>
                    <button onclick="updateStatus({{ $p->id }}, 'batal')" class="text-xs font-bold text-red-500 bg-red-50 px-3 py-1.5 rounded-lg hover:bg-red-100 transition">❌ Batalkan</button>
                    @else
                    <span class="text-xs text-slate-400 font-semibold">{{ ucfirst($p->status) }} — {{ $p->created_at ? \Carbon\Carbon::parse($p->created_at)->diffForHumans() : '' }}</span>
                    @endif
                    <button onclick="deletePesanan({{ $p->id }})" class="text-xs font-bold text-red-400 hover:text-red-600 ml-auto transition" title="Hapus Permanen">🗑️</button>
                </div>
            </div>
            @endforeach
        </div>
        @endif

    </div>
</div>

{{-- ===== EDIT PESANAN MODAL ===== --}}
<div id="modal-edit" class="modal-overlay hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-[60] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="sticky top-0 bg-amber-50 px-6 py-4 border-b border-amber-100 flex justify-between items-center z-10">
            <h2 class="text-lg font-bold text-amber-800">✏️ Edit Pesanan <span id="edit-order-id" class="text-amber-500"></span></h2>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-red-500 text-xl font-bold">&times;</button>
        </div>
        <div class="p-6">
            {{-- Item list --}}
            <div id="edit-items" class="space-y-2 mb-4"></div>

            {{-- Add item --}}
            <div class="flex gap-2 mb-4">
                <select id="edit-add-menu" class="flex-1 px-3 py-2 rounded-lg border text-sm font-semibold">
                    <option value="">+ Tambah menu...</option>
                    @foreach($menuList as $m)
                    <option value="{{ $m->id }}" data-nama="{{ $m->nama }}" data-harga="{{ $m->harga_promo ?: $m->harga }}">{{ $m->nama }} (Rp {{ number_format($m->harga_promo ?: $m->harga, 0, ',', '.') }})</option>
                    @endforeach
                </select>
                <button onclick="addEditItem()" class="bg-amber-500 hover:bg-amber-600 text-white font-bold text-sm px-4 py-2 rounded-lg transition">Tambah</button>
            </div>

            {{-- Catatan --}}
            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-600 mb-1">📝 Catatan</label>
                <textarea id="edit-catatan" rows="2" class="w-full px-3 py-2 rounded-lg border text-sm focus:ring-2 focus:ring-amber-500 outline-none"></textarea>
            </div>

            {{-- Total --}}
            <div class="flex justify-between items-center bg-amber-50 rounded-xl p-4 mb-4">
                <span class="font-bold text-slate-700">Total Baru</span>
                <span id="edit-total" class="text-xl font-black text-amber-600"></span>
            </div>

            <button onclick="saveEditPesanan()" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 rounded-xl transition">
                💾 Simpan Perubahan
            </button>
        </div>
    </div>
</div>

<script>
(function() {
    const BASE = "{{ url('/') }}";
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // All pesanan data for edit
    @php
        $pesananJson = $pesanan->map(function($p) {
            return [
                'id' => $p->id,
                'catatan' => $p->catatan,
                'detail' => $p->detail->map(function($d) {
                    return [
                        'id_menu' => $d->id_menu,
                        'nama' => $d->menu ? $d->menu->nama : 'Dihapus',
                        'qty' => $d->qty,
                        'harga_satuan' => $d->harga_satuan,
                    ];
                })->values(),
            ];
        })->values();
    @endphp
    const pesananData = {!! json_encode($pesananJson) !!};

    let editItems = [];
    let editOrderId = null;

    window.updateStatus = function(id, status) {
        const labels = { diproses: 'memproses', selesai: 'menyelesaikan', batal: 'membatalkan' };
        if (!confirm(`Yakin ingin ${labels[status]} pesanan #${id}?`)) return;
        fetch(`${BASE}/admin/pesanan/update-status/${id}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ status }),
        }).then(r => r.json()).then(data => {
            if (data.success) window.location.reload();
            else alert('Gagal: ' + (data.message || 'Error'));
        });
    };

    window.deletePesanan = function(id) {
        if (!confirm(`Yakin ingin MENGHAPUS PERMANEN pesanan #${id}? Data tidak bisa dikembalikan!`)) return;
        fetch(`${BASE}/admin/pesanan/delete/${id}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
        }).then(r => r.json()).then(data => {
            if (data.success) {
                document.getElementById('order-' + id).remove();
            } else alert('Gagal: ' + (data.message || 'Error'));
        });
    };

    // ===== EDIT MODAL =====
    function renderEditItems() {
        const el = document.getElementById('edit-items');
        el.innerHTML = editItems.map((item, i) => `
            <div class="flex items-center gap-2 bg-slate-50 p-3 rounded-xl border border-slate-100">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-slate-800 truncate">${item.nama}</p>
                </div>
                <input type="number" value="${item.qty}" min="1" class="w-16 px-2 py-1.5 text-center rounded-lg border text-sm font-bold"
                       onchange="updateEditQty(${i}, this.value)">
                <span class="text-[10px] text-slate-400">×</span>
                <input type="number" value="${item.harga_satuan}" min="0" class="w-24 px-2 py-1.5 rounded-lg border text-sm font-bold text-right"
                       onchange="updateEditPrice(${i}, this.value)">
                <span class="text-sm font-bold text-orange-600 w-24 text-right">Rp ${(item.qty * item.harga_satuan).toLocaleString('id-ID')}</span>
                <button onclick="removeEditItem(${i})" class="text-red-400 hover:text-red-600 p-1" title="Hapus">✕</button>
            </div>
        `).join('');
        const total = editItems.reduce((s, item) => s + item.qty * item.harga_satuan, 0);
        document.getElementById('edit-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    window.updateEditQty = function(i, val) { editItems[i].qty = Math.max(1, parseInt(val) || 1); renderEditItems(); };
    window.updateEditPrice = function(i, val) { editItems[i].harga_satuan = Math.max(0, parseInt(val) || 0); renderEditItems(); };
    window.removeEditItem = function(i) { editItems.splice(i, 1); renderEditItems(); };

    window.addEditItem = function() {
        const sel = document.getElementById('edit-add-menu');
        if (!sel.value) return;
        const opt = sel.options[sel.selectedIndex];
        editItems.push({
            id_menu: parseInt(sel.value),
            nama: opt.dataset.nama,
            qty: 1,
            harga_satuan: parseInt(opt.dataset.harga),
        });
        sel.value = '';
        renderEditItems();
    };

    window.openEditModal = function(id) {
        editOrderId = id;
        const order = pesananData.find(p => p.id === id);
        if (!order) return;
        editItems = order.detail.map(d => ({ ...d }));
        document.getElementById('edit-order-id').textContent = '#' + id;
        document.getElementById('edit-catatan').value = order.catatan || '';
        renderEditItems();
        document.getElementById('modal-edit').classList.remove('hidden');
    };

    window.closeEditModal = function() { document.getElementById('modal-edit').classList.add('hidden'); };

    window.saveEditPesanan = function() {
        if (editItems.length === 0) { alert('Minimal 1 item!'); return; }
        const payload = {
            items: editItems.map(item => ({
                id_menu: item.id_menu,
                qty: item.qty,
                harga_override: item.harga_satuan,
            })),
            catatan: document.getElementById('edit-catatan').value,
        };
        fetch(`${BASE}/admin/pesanan/edit/${editOrderId}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify(payload),
        }).then(r => r.json()).then(data => {
            if (data.success) { alert('Pesanan diperbarui!'); window.location.reload(); }
            else alert('Gagal: ' + (data.message || 'Error'));
        });
    };
})();
</script>

</body>
</html>
