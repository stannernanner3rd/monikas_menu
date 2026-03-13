<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang - Monika's Kitchen</title>
    <meta name="description" content="Lihat keranjang belanja dan checkout pesananmu di Monika's Kitchen.">
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

        /* Success modal */
        .success-overlay { transition: opacity 0.3s; }
        .success-overlay.hidden { opacity: 0; pointer-events: none; }
        .success-content { transition: transform 0.3s, opacity 0.3s; }
        .success-overlay.hidden .success-content { transform: translateY(20px) scale(0.95); opacity: 0; }

        @keyframes checkmark { 0%{transform:scale(0);} 50%{transform:scale(1.3);} 100%{transform:scale(1);} }
        .check-anim { animation: checkmark 0.5s ease both; }
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

            {{-- Empty state --}}
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
                    <div class="flex justify-between">
                        <span class="text-slate-500">Biaya Layanan</span>
                        <span class="font-semibold text-slate-600">Rp 2.000</span>
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
                <h2 class="font-extrabold text-slate-900 mb-1">Detail Pengambilan</h2>
                <p class="text-xs text-slate-400 mb-4">Wajib diisi sebelum checkout</p>

                <div class="space-y-4">
                    {{-- Date Picker --}}
                    <div>
                        <label for="pickup-date" class="block text-sm font-bold text-slate-700 mb-1.5">
                            <span class="inline-flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Tanggal Pengambilan
                            </span>
                        </label>
                        <input type="date" id="pickup-date" required
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm font-semibold text-slate-800 outline-none transition bg-slate-50">
                        <p id="date-error" class="text-red-500 text-xs font-semibold mt-1 hidden">Pilih tanggal pengambilan</p>
                    </div>

                    {{-- Time Slot Dropdown --}}
                    <div>
                        <label for="pickup-time" class="block text-sm font-bold text-slate-700 mb-1.5">
                            <span class="inline-flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Jam Pengambilan
                            </span>
                        </label>
                        <select id="pickup-time" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm font-semibold text-slate-800 outline-none transition bg-slate-50 appearance-none"
                                style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2220%22 height=%2220%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%2394a3b8%22 stroke-width=%222%22><path d=%22M6 9l6 6 6-6%22/></svg>'); background-repeat: no-repeat; background-position: right 12px center;">
                            <option value="">Pilih Jam Pengambilan</option>
                            <option value="08:00 - 10:00">08:00 - 10:00</option>
                            <option value="10:00 - 12:00">10:00 - 12:00</option>
                            <option value="12:00 - 14:00">12:00 - 14:00</option>
                            <option value="14:00 - 16:00">14:00 - 16:00</option>
                            <option value="16:00 - 18:00">16:00 - 18:00</option>
                            <option value="18:00 - 20:00">18:00 - 20:00</option>
                        </select>
                        <p id="time-error" class="text-red-500 text-xs font-semibold mt-1 hidden">Pilih jam pengambilan</p>
                    </div>
                </div>

                {{-- Checkout Button --}}
                <button id="checkout-btn" disabled
                        class="w-full mt-5 bg-orange-500 text-white font-bold py-3.5 rounded-xl shadow-lg text-sm flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Checkout Sekarang
                </button>

                <p id="checkout-hint" class="text-center text-[11px] text-slate-400 mt-2">
                    Isi tanggal dan jam pengambilan untuk melanjutkan
                </p>
            </div>
        </section>

        {{-- Bottom spacer --}}
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
        <p id="success-order-id" class="text-orange-600 font-bold text-sm mb-5"></p>
        <div class="flex gap-3">
            <a href="{{ url('/orders') }}" class="flex-1 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-bold text-sm shadow transition text-center">Lihat Pesanan</a>
            <a href="{{ url('/menu') }}" class="flex-1 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50 transition text-center">Pesan Lagi</a>
        </div>
    </div>
</div>

<script>
(function() {
    // ---- Cart helpers ----
    function getCart() {
        try { return JSON.parse(localStorage.getItem('mk-cart')) || []; }
        catch { return []; }
    }
    function saveCart(cart) { localStorage.setItem('mk-cart', JSON.stringify(cart)); }

    const itemsEl = document.getElementById('cart-items');
    const emptyEl = document.getElementById('cart-empty');
    const summaryEl = document.getElementById('summary-section');
    const checkoutEl = document.getElementById('checkout-section');
    const subtotalEl = document.getElementById('subtotal');
    const totalEl = document.getElementById('total');
    const subtitle = document.getElementById('cart-subtitle');
    const checkoutBtn = document.getElementById('checkout-btn');
    const hintEl = document.getElementById('checkout-hint');
    const dateInput = document.getElementById('pickup-date');
    const timeInput = document.getElementById('pickup-time');
    const dateError = document.getElementById('date-error');
    const timeError = document.getElementById('time-error');

    // Set min date to today
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');
    dateInput.setAttribute('min', `${yyyy}-${mm}-${dd}`);

    function parsePrice(str) {
        return parseInt(String(str).replace(/\./g, '').replace(/,/g, '')) || 0;
    }
    function formatRp(num) {
        return 'Rp ' + num.toLocaleString('id-ID');
    }

    // ---- Render cart ----
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

        // Render items
        itemsEl.innerHTML = cart.map((item, i) => {
            const price = parsePrice(item.price);
            const lineTotal = price * item.qty;
            const originalPrice = item.promo ? parsePrice(item.original) : 0;

            return `
            <div class="cart-item bg-white rounded-2xl shadow-sm border border-slate-100 p-3 flex gap-3 items-start" data-idx="${i}">
                <img src="${item.img}" alt="${item.name}" class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover flex-shrink-0 border border-slate-100">
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <h3 class="text-sm font-bold text-slate-800 truncate">${item.name}</h3>
                            <span class="text-[10px] font-semibold text-slate-400 bg-slate-50 px-2 py-0.5 rounded">${item.cat}</span>
                        </div>
                        <button onclick="removeItem(${i})" class="text-slate-300 hover:text-red-500 transition p-1 flex-shrink-0" title="Hapus">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                    <div class="flex items-center justify-between mt-2">
                        <div class="flex items-center gap-0.5">
                            <button onclick="changeQty(${i}, -1)" class="qty-btn w-7 h-7 flex items-center justify-center rounded-lg bg-slate-100 text-slate-600 text-sm font-bold">−</button>
                            <span class="w-8 text-center text-sm font-bold text-slate-800">${item.qty}</span>
                            <button onclick="changeQty(${i}, 1)" class="qty-btn w-7 h-7 flex items-center justify-center rounded-lg bg-slate-100 text-slate-600 text-sm font-bold">+</button>
                        </div>
                        <div class="text-right">
                            ${item.promo ? `<p class="text-[10px] text-slate-400 line-through">${formatRp(originalPrice * item.qty)}</p>` : ''}
                            <p class="text-sm font-black ${item.promo ? 'text-red-600' : 'text-orange-600'}">${formatRp(lineTotal)}</p>
                        </div>
                    </div>
                </div>
            </div>`;
        }).join('');

        // Subtotal & total
        const sub = cart.reduce((s, item) => s + parsePrice(item.price) * item.qty, 0);
        subtotalEl.textContent = formatRp(sub);
        totalEl.textContent = formatRp(sub + 2000);

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

    // ---- Qty change ----
    window.changeQty = function(idx, delta) {
        let cart = getCart();
        if (!cart[idx]) return;
        cart[idx].qty += delta;
        if (cart[idx].qty <= 0) cart.splice(idx, 1);
        saveCart(cart);
        render();
    };

    // ---- Remove ----
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

    // ---- Validate checkout form ----
    function validateCheckout() {
        const hasDate = dateInput.value !== '';
        const hasTime = timeInput.value !== '';
        const cart = getCart();
        const valid = hasDate && hasTime && cart.length > 0;

        checkoutBtn.disabled = !valid;

        // Show/hide hint
        if (valid) {
            hintEl.textContent = '✓ Siap checkout!';
            hintEl.classList.remove('text-slate-400');
            hintEl.classList.add('text-green-600');
        } else {
            const missing = [];
            if (!hasDate) missing.push('tanggal');
            if (!hasTime) missing.push('jam');
            hintEl.textContent = missing.length > 0
                ? 'Isi ' + missing.join(' dan ') + ' pengambilan untuk melanjutkan'
                : 'Isi tanggal dan jam pengambilan untuk melanjutkan';
            hintEl.classList.add('text-slate-400');
            hintEl.classList.remove('text-green-600');
        }

        // Error states
        dateError.classList.toggle('hidden', hasDate || !dateInput.dataset.touched);
        timeError.classList.toggle('hidden', hasTime || !timeInput.dataset.touched);
    }

    dateInput.addEventListener('change', function() {
        this.dataset.touched = '1';
        validateCheckout();
    });
    timeInput.addEventListener('change', function() {
        this.dataset.touched = '1';
        validateCheckout();
    });

    // ---- Checkout ----
    checkoutBtn.addEventListener('click', function() {
        // Mark as touched for validation visuals
        dateInput.dataset.touched = '1';
        timeInput.dataset.touched = '1';
        validateCheckout();

        if (this.disabled) return;

        const cart = getCart();
        const sub = cart.reduce((s, item) => s + parsePrice(item.price) * item.qty, 0);
        const orderId = 'MK-' + new Date().toISOString().slice(0,10).replace(/-/g,'') + '-' + String(Math.floor(Math.random()*900)+100);
        const dateVal = dateInput.value;
        const timeVal = timeInput.value;

        // Format date
        const d = new Date(dateVal);
        const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        const formatted = d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear();

        // Show success
        document.getElementById('success-details').textContent = 'Ambil: ' + formatted + ', ' + timeVal;
        document.getElementById('success-order-id').textContent = orderId + ' • ' + formatRp(sub + 2000);
        document.getElementById('success-modal').classList.remove('hidden');

        // Clear cart
        localStorage.removeItem('mk-cart');
        render();
    });

    // ---- Init ----
    render();
})();
</script>

</body>
</html>
