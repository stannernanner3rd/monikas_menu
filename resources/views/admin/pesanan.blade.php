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
    </style>
</head>
<body class="bg-slate-50 antialiased">

<x-admin-sidebar active="pesanan" />

<div class="md:pl-64 min-h-screen pb-20">
    <div class="px-4 md:px-10 py-6 md:py-10 max-w-6xl">

        {{-- Header --}}
        <div class="mb-8">
            <p class="text-orange-500 text-xs font-bold uppercase tracking-widest mb-1">Realtime</p>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900">Pesanan Masuk</h1>
            <p class="text-slate-400 text-sm mt-1">Kelola semua pesanan customer dari sini.</p>
        </div>

        {{-- Stats Row --}}
        @php
            $pending   = $pesanan->where('status', 'pending')->count();
            $diproses  = $pesanan->where('status', 'diproses')->count();
            $selesai   = $pesanan->where('status', 'selesai')->count();
            $totalHari = $pesanan->where('created_at', '>=', now()->startOfDay())->count();
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
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
                <p class="text-2xl font-black text-slate-700">{{ $totalHari }}</p>
                <p class="text-[11px] font-bold text-slate-400 uppercase mt-1">Hari Ini</p>
            </div>
        </div>

        {{-- Order List --}}
        @if($pesanan->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-12 text-center">
            <div class="text-5xl mb-3">📋</div>
            <p class="text-slate-500 font-semibold">Belum ada pesanan masuk.</p>
            <p class="text-slate-400 text-sm mt-1">Pesanan dari customer akan muncul di sini.</p>
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
                {{-- Order Header --}}
                <div class="px-5 py-4 flex flex-wrap items-center justify-between gap-3 border-b border-slate-50">
                    <div class="flex items-center gap-3">
                        <div>
                            <p class="font-extrabold text-slate-800">{{ $p->nama_pemesan }}</p>
                            <p class="text-[11px] text-slate-400">
                                📱 {{ $p->whatsapp }} · 
                                📅 {{ \Carbon\Carbon::parse($p->tanggal_ambil)->format('d M Y') }} · 
                                🕐 {{ $p->waktu_ambil }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-bold uppercase px-2.5 py-1 rounded-full border {{ $statusColors[$p->status] ?? '' }}">
                            {{ $statusIcons[$p->status] ?? '' }} {{ ucfirst($p->status) }}
                        </span>
                        <span class="text-xs font-bold {{ $p->tipe === 'catering' ? 'text-blue-600 bg-blue-50 px-2 py-0.5 rounded-lg' : 'text-slate-400' }}">
                            {{ $p->tipe === 'catering' ? '🍱 Catering' : '🍽️ Biasa' }}
                        </span>
                        <p class="font-black text-orange-600 text-sm">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</p>
                    </div>
                </div>

                {{-- Order Items --}}
                <div class="px-5 py-3 bg-slate-50/50">
                    <div class="space-y-1.5">
                        @foreach($p->detail as $d)
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-600">
                                {{ $d->menu ? $d->menu->nama : 'Menu Dihapus' }} 
                                <span class="text-slate-400">x{{ $d->qty }}</span>
                            </span>
                            <span class="font-bold text-slate-700">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                    @if($p->catatan)
                    <p class="text-xs text-slate-400 mt-2 italic">📝 {{ $p->catatan }}</p>
                    @endif
                </div>

                {{-- Action Buttons --}}
                <div class="px-5 py-3 flex gap-2 flex-wrap border-t border-slate-50">
                    @if($p->status === 'pending')
                    <button onclick="updateStatus({{ $p->id }}, 'diproses')" class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1.5 rounded-lg hover:bg-blue-100 transition">
                        🔥 Proses
                    </button>
                    <button onclick="updateStatus({{ $p->id }}, 'batal')" class="text-xs font-bold text-red-500 bg-red-50 px-3 py-1.5 rounded-lg hover:bg-red-100 transition">
                        ❌ Batalkan
                    </button>
                    @elseif($p->status === 'diproses')
                    <button onclick="updateStatus({{ $p->id }}, 'selesai')" class="text-xs font-bold text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-lg hover:bg-emerald-100 transition">
                        ✅ Selesai
                    </button>
                    <button onclick="updateStatus({{ $p->id }}, 'batal')" class="text-xs font-bold text-red-500 bg-red-50 px-3 py-1.5 rounded-lg hover:bg-red-100 transition">
                        ❌ Batalkan
                    </button>
                    @else
                    <span class="text-xs text-slate-400 font-semibold">{{ ucfirst($p->status) }} — {{ $p->created_at ? \Carbon\Carbon::parse($p->created_at)->diffForHumans() : '' }}</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif

    </div>
</div>

<script>
(function() {
    const BASE = "{{ url('/') }}";
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    window.updateStatus = function(id, status) {
        const labels = { diproses: 'memproses', selesai: 'menyelesaikan', batal: 'membatalkan' };
        if (!confirm(`Yakin ingin ${labels[status]} pesanan #${id}?`)) return;

        fetch(`${BASE}/admin/pesanan/update-status/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ status }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert('Gagal: ' + (data.message || 'Error'));
            }
        })
        .catch(err => alert('Error: ' + err.message));
    };
})();
</script>

</body>
</html>
