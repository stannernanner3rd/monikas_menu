<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang - Monika's Kitchen</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        @keyframes fadeUp { from{opacity:0;transform:translateY(18px);}to{opacity:1;transform:translateY(0);} }
        .fade-up { animation: fadeUp 0.5s ease both; }
        .fade-up-1 { animation: fadeUp 0.5s 0.1s ease both; }
        .fade-up-2 { animation: fadeUp 0.5s 0.2s ease both; }
        .qty-btn { transition: all 0.15s; }
        .qty-btn:hover { background: #ea580c; color: white; }
        .qty-btn:active { transform: scale(0.9); }
        .cart-item { transition: opacity 0.3s, transform 0.3s; }
        .cart-item.removing { opacity: 0; transform: translateX(-30px); }
        #checkout-btn:disabled { opacity: 0.45; cursor: not-allowed; }
        #checkout-btn:not(:disabled):hover { background: #c2410c; transform: translateY(-1px); box-shadow: 0 8px 20px rgba(234,88,12,0.35); }
        #checkout-btn { transition: all 0.25s; }
        .success-overlay { transition: opacity 0.3s; }
        .success-overlay.hidden { opacity: 0; pointer-events: none; }
        .success-content { transition: transform 0.3s, opacity 0.3s; }
        .success-overlay.hidden .success-content { transform: translateY(20px) scale(0.95); opacity: 0; }
        @keyframes checkmark { 0%{transform:scale(0);} 50%{transform:scale(1.3);} 100%{transform:scale(1);} }
        .check-anim { animation: checkmark 0.5s ease both; }
        .tipe-btn { transition: all 0.2s; }
        .tipe-btn.active { background: #ea580c; color: white; box-shadow: 0 4px 14px rgba(234,88,12,0.35); border-color: #ea580c; }
    </style>
</head>

<body class="bg-slate-50 antialiased">
<div class="min-h-screen pb-28 md:pb-10 md:pl-64">
    <x-navibar />

    <main class="max-w-lg mx-auto px-4 md:max-w-2xl md:mx-auto md:px-10 md:py-8">

        {{-- Page Header --}}
        <section class="mt-6 fade-up">
            <p class="text-orange-500 text-xs font-bold uppercase tracking-widest mb-1">Monika's Kitchen</p>
            <h1 class="text-2xl font-extrabold text-slate-900">Keranjang Saya</h1>
            <p id="cart-subtitle" class="text-slate-400 text-sm mt-0.5"></p>
        </section>

        {{-- ===== CART ITEMS ===== --}}
        <section class="mt-5 fade-up" id="cart-section">
            <div id="cart-items" class="space-y-3"></div>
            <div id="cart-empty" class="hidden text-center py-16">
                <div class="text-6xl mb-4">🛒</div>
                <p class="text-slate-500 font-bold text-lg">Keranjang masih kosong</p>
                <p class="text-slate-400 text-sm mt-1">Yuk tambahkan menu favorit kamu!</p>
                <a href="{{ url('/menu') }}" class="inline-block mt-5 bg-orange-500 hover:bg-orange-600 text-white font-bold text-sm px-6 py-3 rounded-xl shadow transition">
                    Lihat Menu
                </a>
            </div>
        </section>

        {{-- ===== ORDER SUMMARY ===== --}}
        <section id="summary-section" class="mt-6 fade-up-1 hidden">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <h2 class="font-extrabold text-slate-900 mb-4">Ringkasan Pesanan</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Subtotal</span>
                        <span id="subtotal" class="font-bold text-slate-800"></span>
                    </div>
                    <div class="border-t border-slate-100 pt-2 mt-2 flex justify-between">
                        <span class="font-extrabold text-slate-900">Total</span>
                        <span id="total" class="font-black text-orange-600 text-lg"></span>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== CHECKOUT FORM ===== --}}
        <section id="checkout-section" class="mt-5 fade-up-2 hidden">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <h2 class="font-extrabold text-slate-900 mb-1">Detail Pemesanan</h2>
                <p class="text-xs text-slate-400 mb-4">Wajib diisi sebelum checkout</p>

                <div class="space-y-4">
                    {{-- Tipe Pesanan --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Tipe Pesanan</label>
                        <div class="flex gap-2">
                            <button type="button" class="tipe-btn active flex-1 py-2.5 rounded-xl border-2 border-slate-200 text-sm font-bold text-center" data-tipe="biasa">
                                🍽️ Biasa
                            </button>
                            <button type="button" class="tipe-btn flex-1 py-2.5 rounded-xl border-2 border-slate-200 text-sm font-bold text-center text-slate-600" data-tipe="catering">
                                🍱 Catering
                            </button>
                        </div>
                    </div>

                    {{-- Nama Pemesan --}}
                    <div>
                        <label for="nama-pemesan" class="block text-sm font-bold text-slate-700 mb-1.5">
                            <span class="inline-flex items-center gap-1.5">👤 Nama Pemesan</span>
                        </label>
                        <input type="text" id="nama-pemesan" required placeholder="Masukkan nama Anda"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm font-semibold text-slate-800 outline-none transition bg-slate-50">
                    </div>

                    {{-- WhatsApp --}}
                    <div>
                        <label for="whatsapp" class="block text-sm font-bold text-slate-700 mb-1.5">
                            <span class="inline-flex items-center gap-1.5">📱 No. WhatsApp</span>
                        </label>
                        <input type="tel" id="whatsapp" required placeholder="08xxxxxxxxxx"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm font-semibold text-slate-800 outline-none transition bg-slate-50">
                    </div>

                    {{-- Tanggal --}}
                    <div>
                        <label for="pickup-date" class="block text-sm font-bold text-slate-700 mb-1.5">
                            <span class="inline-flex items-center gap-1.5">📅 Tanggal Pengambilan</span>
                        </label>
                        <input type="date" id="pickup-date" required
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm font-semibold text-slate-800 outline-none transition bg-slate-50">
                    </div>

                    {{-- Waktu --}}
                    <div>
                        <label for="pickup-time" class="block text-sm font-bold text-slate-700 mb-1.5">
                            <span class="inline-flex items-center gap-1.5">🕐 Jam Pengambilan</span>
                        </label>
                        <select id="pickup-time" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm font-semibold text-slate-800 outline-none transition bg-slate-50 appearance-none"
                                style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2220%22 height=%2220%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%2394a3b8%22 stroke-width=%222%22><path d=%22M6 9l6 6 6-6%22/></svg>'); background-repeat: no-repeat; background-position: right 12px center;">
                            <option value="">Pilih Jam</option>
                            <option value="08:00 - 10:00">08:00 - 10:00</option>
                            <option value="10:00 - 12:00">10:00 - 12:00</option>
                            <option value="12:00 - 14:00">12:00 - 14:00</option>
                            <option value="14:00 - 16:00">14:00 - 16:00</option>
                            <option value="16:00 - 18:00">16:00 - 18:00</option>
                            <option value="18:00 - 20:00">18:00 - 20:00</option>
                        </select>
                    </div>

                    {{-- Catatan --}}
                    <div>
                        <label for="catatan" class="block text-sm font-bold text-slate-700 mb-1.5">📝 Catatan (opsional)</label>
                        <textarea id="catatan" rows="2" placeholder="Catatan untuk pesanan..."
                                  class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm font-semibold text-slate-800 outline-none transition bg-slate-50"></textarea>
                    </div>
                </div>

                {{-- Checkout Button --}}
                <button id="checkout-btn" disabled
                        class="w-full mt-5 bg-orange-500 text-white font-bold py-3.5 rounded-xl shadow-lg text-sm flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span id="checkout-btn-text">Checkout Sekarang</span>
                </button>
                <p id="checkout-hint" class="text-center text-[11px] text-slate-400 mt-2">
                    Lengkapi semua data untuk melanjutkan
                </p>
            </div>
        </section>

        <div class="h-6"></div>
    </main>
</div>

{{-- ===== SUCCESS MODAL ===== --}}
<div id="success-modal" class="success-overlay hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-[80] flex items-center justify-center p-4">
    <div class="success-content bg-white rounded-2xl w-full max-w-sm p-8 shadow-2xl text-center">
        <div class="w-20 h-20 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-4 check-anim">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
        </div>
        <h3 class="text-xl font-extrabold text-slate-900 mb-1">Pesanan Berhasil!</h3>
        <p id="success-details" class="text-slate-500 text-sm mb-1"></p>
        <p id="success-total" class="text-orange-600 font-bold text-sm mb-5"></p>
        <div class="flex gap-3">
            <a href="{{ url('/') }}" class="flex-1 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-bold text-sm shadow transition text-center">Kembali</a>
            <a href="{{ url('/menu') }}" class="flex-1 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50 transition text-center">Pesan Lagi</a>
        </div>
    </div>
</div>

<script>
(function() {
    const BASE = "{{ url('/') }}";
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    function getCart() {
        try { return JSON.parse(localStorage.getItem('mk-cart')) || []; }
        catch { return []; }
    }
    function saveCart(cart) { localStorage.setItem('mk-cart', JSON.stringify(cart)); }

    const itemsEl      = document.getElementById('cart-items');
    const emptyEl      = document.getElementById('cart-empty');
    const summaryEl    = document.getElementById('summary-section');
    const checkoutEl   = document.getElementById('checkout-section');
    const subtotalEl   = document.getElementById('subtotal');
    const totalEl      = document.getElementById('total');
    const subtitle     = document.getElementById('cart-subtitle');
    const checkoutBtn  = document.getElementById('checkout-btn');
    const hintEl       = document.getElementById('checkout-hint');
    const dateInput    = document.getElementById('pickup-date');
    const timeInput    = document.getElementById('pickup-time');
    const namaInput    = document.getElementById('nama-pemesan');
    const waInput      = document.getElementById('whatsapp');

    let selectedTipe = 'biasa';

    // Min date = today
    const today = new Date();
    dateInput.setAttribute('min', today.toISOString().slice(0,10));

    // Tipe selector
    document.querySelectorAll('.tipe-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.tipe-btn').forEach(b => {
                b.classList.remove('active');
                b.classList.add('text-slate-600');
            });
            this.classList.add('active');
            this.classList.remove('text-slate-600');
            selectedTipe = this.dataset.tipe;
            validateCheckout();
        });
    });

    function formatRp(num) { return 'Rp ' + num.toLocaleString('id-ID'); }

    function render() {
        const cart = getCart();
        if (cart.length === 0) {
            itemsEl.innerHTML = '';
            emptyEl.classList.remove('hidden');
            summaryEl.classList.add('hidden');
            checkoutEl.classList.add('hidden');
            subtitle.textContent = 'Belum ada item di keranjang.';
            return;
        }

        emptyEl.classList.add('hidden');
        summaryEl.classList.remove('hidden');
        checkoutEl.classList.remove('hidden');

        const totalItems = cart.reduce((s, i) => s + i.qty, 0);
        subtitle.textContent = totalItems + ' item di keranjang';

        itemsEl.innerHTML = cart.map((item, i) => {
            const price = parseInt(item.price) || 0;
            const lineTotal = price * item.qty;

            return `
            <div class="cart-item bg-white rounded-2xl shadow-sm border border-slate-100 p-3 flex gap-3 items-start" data-idx="${i}">
                <img src="${item.img}" alt="${item.name}" class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover shrink-0 border border-slate-100">
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <h3 class="text-sm font-bold text-slate-800 truncate">${item.name}</h3>
                            <span class="text-[10px] font-semibold text-slate-400 bg-slate-50 px-2 py-0.5 rounded">${item.cat}</span>
                        </div>
                        <button onclick="removeItem(${i})" class="text-slate-300 hover:text-red-500 transition p-1 shrink-0" title="Hapus">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                    <div class="flex items-center justify-between mt-2">
                        <div class="flex items-center gap-0.5">
                            <button onclick="changeQty(${i}, -1)" class="qty-btn w-7 h-7 flex items-center justify-center rounded-lg bg-slate-100 text-slate-600 text-sm font-bold">−</button>
                            <span class="w-8 text-center text-sm font-bold text-slate-800">${item.qty}</span>
                            <button onclick="changeQty(${i}, 1)" class="qty-btn w-7 h-7 flex items-center justify-center rounded-lg bg-slate-100 text-slate-600 text-sm font-bold">+</button>
                        </div>
                        <p class="text-sm font-black text-orange-600">${formatRp(lineTotal)}</p>
                    </div>
                </div>
            </div>`;
        }).join('');

        const sub = cart.reduce((s, item) => s + (parseInt(item.price) || 0) * item.qty, 0);
        subtotalEl.textContent = formatRp(sub);
        totalEl.textContent = formatRp(sub);

        updateCartBadge();
        validateCheckout();
    }

    function updateCartBadge() {
        const cart = getCart();
        const total = cart.reduce((s, i) => s + i.qty, 0);
        document.querySelectorAll('.cart-badge-count').forEach(el => {
            el.textContent = total;
            el.parentElement.style.display = total > 0 ? '' : 'none';
        });
    }

    window.changeQty = function(idx, delta) {
        let cart = getCart();
        if (!cart[idx]) return;
        cart[idx].qty += delta;
        if (cart[idx].qty <= 0) cart.splice(idx, 1);
        saveCart(cart);
        render();
    };

    window.removeItem = function(idx) {
        const el = document.querySelector(`.cart-item[data-idx="${idx}"]`);
        if (el) {
            el.classList.add('removing');
            setTimeout(() => {
                let cart = getCart();
                cart.splice(idx, 1);
                saveCart(cart);
                render();
            }, 300);
        }
    };

    function validateCheckout() {
        const cart = getCart();
        const valid = namaInput.value.trim() !== '' &&
                      waInput.value.trim() !== '' &&
                      dateInput.value !== '' &&
                      timeInput.value !== '' &&
                      cart.length > 0;

        checkoutBtn.disabled = !valid;

        if (valid) {
            hintEl.textContent = '✓ Siap checkout!';
            hintEl.className = 'text-center text-[11px] text-green-600 mt-2';
        } else {
            hintEl.textContent = 'Lengkapi semua data untuk melanjutkan';
            hintEl.className = 'text-center text-[11px] text-slate-400 mt-2';
        }
    }

    [namaInput, waInput, dateInput, timeInput].forEach(el => {
        el.addEventListener('input', validateCheckout);
        el.addEventListener('change', validateCheckout);
    });

    // ---- CHECKOUT → KIRIM KE DATABASE ----
    checkoutBtn.addEventListener('click', async function() {
        validateCheckout();
        if (this.disabled) return;

        const cart = getCart();
        if (cart.length === 0) return;

        // Disable tombol sementara
        this.disabled = true;
        document.getElementById('checkout-btn-text').textContent = 'Memproses...';

        const payload = {
            nama_pemesan: namaInput.value.trim(),
            whatsapp: waInput.value.trim(),
            tanggal_ambil: dateInput.value,
            waktu_ambil: timeInput.value,
            tipe: selectedTipe,
            catatan: document.getElementById('catatan').value.trim(),
            items: cart.map(item => ({
                id_menu: parseInt(item.id),
                qty: item.qty,
            })),
        };

        try {
            const res = await fetch(`${BASE}/checkout`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload),
            });

            const data = await res.json();

            if (data.success) {
                // Format tanggal
                const d = new Date(dateInput.value);
                const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
                const formatted = d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear();

                document.getElementById('success-details').textContent = 'Ambil: ' + formatted + ', ' + timeInput.value;
                document.getElementById('success-total').textContent = 'Pesanan #' + data.pesanan_id + ' • ' + formatRp(data.total);
                document.getElementById('success-modal').classList.remove('hidden');

                // Clear cart
                localStorage.removeItem('mk-cart');
                render();
            } else {
                alert('Gagal: ' + (data.message || 'Terjadi kesalahan'));
                this.disabled = false;
                document.getElementById('checkout-btn-text').textContent = 'Checkout Sekarang';
            }
        } catch (err) {
            alert('Error jaringan: ' + err.message);
            this.disabled = false;
            document.getElementById('checkout-btn-text').textContent = 'Checkout Sekarang';
        }
    });

    render();
})();
</script>

</body>
</html>

