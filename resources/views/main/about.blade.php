<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami & FAQ - Monika's Kitchen</title>
    <meta name="description" content="Kenali lebih dekat Monika's Kitchen — cerita kami, nilai kami, dan jawaban atas pertanyaan yang sering ditanyakan.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Animations */
        @keyframes fadeUp { from{opacity:0;transform:translateY(18px);}to{opacity:1;transform:translateY(0);} }
        .fade-up   { animation: fadeUp 0.55s ease both; }
        .fade-up-1 { animation: fadeUp 0.55s 0.1s ease both; }
        .fade-up-2 { animation: fadeUp 0.55s 0.2s ease both; }
        .fade-up-3 { animation: fadeUp 0.55s 0.3s ease both; }

        /* Hero gradient */
        .about-hero {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 60%, #1c1917 100%);
        }

        /* Value card */
        .val-card { transition: transform 0.25s ease, box-shadow 0.25s ease; }
        .val-card:hover { transform: translateY(-4px); box-shadow: 0 16px 32px rgba(249,115,22,0.15); }

        /* FAQ accordion */
        .faq-content { max-height: 0; overflow: hidden; transition: max-height 0.4s ease, padding 0.3s ease; }
        .faq-content.open { max-height: 400px; }
        .faq-icon { transition: transform 0.3s ease; }
        .faq-item.open .faq-icon { transform: rotate(45deg); }
        .faq-item { transition: box-shadow 0.2s ease; }
        .faq-item:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.06); }

        /* Team card */
        .team-card { transition: transform 0.25s ease; }
        .team-card:hover { transform: translateY(-4px); }

        /* Stat counter */
        @keyframes countUp { from { opacity:0; transform:scale(0.7); } to { opacity:1; transform:scale(1); } }
        .stat-num { animation: countUp 0.6s ease both; }
    </style>
</head>

<body class="bg-slate-50 antialiased">
<div class="min-h-screen pb-28 md:pb-10 md:pl-64">

    <x-navibar />

    {{-- ===== HERO ===== --}}
    <section class="about-hero text-white px-6 py-14 fade-up">
        <div class="max-w-lg mx-auto md:max-w-none md:px-10">
            <div class="md:grid md:grid-cols-2 gap-10 items-center">
                <div>
                    <span class="inline-block bg-orange-500/20 border border-orange-500/40 text-orange-400 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-widest mb-4">
                        Tentang Kami
                    </span>
                    <h1 class="text-3xl md:text-4xl font-extrabold leading-tight mb-4">
                        Masak dengan <span class="text-orange-400">Cinta,</span><br>Disajikan dengan <span class="text-orange-400">Hati.</span>
                    </h1>
                    <p class="text-slate-400 leading-relaxed text-sm md:text-base">
                        Monika's Kitchen lahir dari kecintaan mendalam terhadap masakan rumahan yang hangat dan autentik.
                        Setiap hidangan kami dibuat dari bahan-bahan pilihan, tanpa penyedap buatan, dan penuh kasih sayang.
                    </p>
                    <a href="{{ url('/menu') }}" class="inline-block mt-6 bg-orange-500 hover:bg-orange-400 text-white font-bold px-6 py-3 rounded-2xl shadow-lg transition">
                        🍽️ Lihat Menu Kami
                    </a>
                </div>
                <div class="mt-8 md:mt-0 grid grid-cols-2 gap-4">
                    @php
                        $stats = [
                            ['num' => '2+',   'label' => 'Tahun Beroperasi', 'icon' => '📅'],
                            ['num' => '2rb+', 'label' => 'Pelanggan Puas',  'icon' => '😊'],
                            ['num' => '50+',  'label' => 'Menu Pilihan',     'icon' => '🍴'],
                            ['num' => '4.9',  'label' => 'Rating Rata-rata', 'icon' => '⭐'],
                        ];
                    @endphp
                    @foreach($stats as $s)
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-4 text-center backdrop-blur-sm">
                        <div class="text-2xl mb-1">{{ $s['icon'] }}</div>
                        <p class="text-2xl font-black text-orange-400 stat-num">{{ $s['num'] }}</p>
                        <p class="text-slate-400 text-xs mt-0.5 font-medium">{{ $s['label'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <main class="max-w-lg mx-auto px-4 md:max-w-none md:mx-0 md:px-10 md:py-8">

        {{-- ===== NILAI KAMI ===== --}}
        <section class="mt-12 fade-up-1">
            <div class="text-center mb-8">
                <p class="text-orange-500 text-xs font-bold uppercase tracking-widest mb-1">Yang Kami Percaya</p>
                <h2 class="text-2xl font-extrabold text-slate-900">Nilai-Nilai Kami 💛</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @php
                    $values = [
                        ['icon' => '🫙', 'title' => 'Bahan Segar Setiap Hari',  'desc' => 'Kami hanya menggunakan bahan-bahan segar yang dipilih langsung dari pasar setiap pagi, tanpa bahan pengawet atau pewarna buatan.'],
                        ['icon' => '❤️', 'title' => 'Dimasak dengan Cinta',       'desc' => 'Setiap porsi adalah karya tangan yang dikerjakan sepenuh hati, seperti masakan ibu untuk keluarga tercinta.'],
                        ['icon' => '🚀', 'title' => 'Cepat & Tepat Waktu',        'desc' => 'Pesananmu kami proses dalam 15 menit dan diantarkan langsung ke mejamu. Tidak ada kata tunggu terlalu lama!'],
                    ];
                @endphp
                @foreach($values as $v)
                <div class="val-card bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                    <div class="text-4xl mb-4">{{ $v['icon'] }}</div>
                    <h3 class="font-extrabold text-slate-800 mb-2">{{ $v['title'] }}</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">{{ $v['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </section>

        {{-- ===== CERITA KAMI ===== --}}
        <section class="mt-12 fade-up-2">
            <div class="bg-gradient-to-br from-orange-50 to-amber-50 rounded-3xl p-6 md:p-10 border border-orange-100">
                <div class="md:grid md:grid-cols-2 gap-10 items-center">
                    <div>
                        <p class="text-orange-500 text-xs font-bold uppercase tracking-widest mb-2">Cerita Kami</p>
                        <h2 class="text-2xl font-extrabold text-slate-900 mb-4">Dari Dapur Kecil Menuju Ribuan Piring 🍳</h2>
                        <p class="text-slate-600 text-sm leading-relaxed mb-3">
                            Berawal dari dapur rumahan di tahun 2024, Monika memulai usahanya dengan satu keyakinan sederhana:
                            <strong>makanan yang enak tidak harus mahal.</strong>
                        </p>
                        <p class="text-slate-600 text-sm leading-relaxed mb-3">
                            Hari demi hari, pesanan terus bertambah. Pelanggan tidak hanya kembali karena rasanya,
                            tapi juga karena kehangatan yang dirasakan dalam setiap suapan.
                        </p>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            Kini, Monika's Kitchen telah melayani ribuan pelanggan dan terus berkembang —
                            namun satu hal tidak pernah berubah: <strong>rasa kasih sayang di setiap masakan.</strong>
                        </p>
                    </div>
                    <div class="mt-6 md:mt-0">
                        <div class="relative">
                            <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?auto=format&fit=crop&w=600&q=80"
                                 alt="Monika's Kitchen Dapur"
                                 class="w-full h-64 object-cover rounded-2xl shadow-lg">
                            <div class="absolute -bottom-4 -right-4 bg-white rounded-2xl p-4 shadow-xl border border-slate-100">
                                <p class="text-3xl font-black text-orange-600">4.9 ⭐</p>
                                <p class="text-xs text-slate-500 font-semibold">dari 2,000+ ulasan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== TIM KAMI ===== --}}
        <section class="mt-12 fade-up-3">
            <div class="text-center mb-8">
                <p class="text-orange-500 text-xs font-bold uppercase tracking-widest mb-1">Di Balik Dapur</p>
                <h2 class="text-2xl font-extrabold text-slate-900">Tim Kami 👨‍🍳</h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @php
                    $team = [
                        ['name' => 'Monika',  'role' => 'Head Chef & Founder',  'emoji' => '👩‍🍳', 'color' => 'from-orange-400 to-red-400'],
                        ['name' => 'Budi',    'role' => 'Sous Chef',            'emoji' => '👨‍🍳', 'color' => 'from-amber-400 to-orange-400'],
                        ['name' => 'Sari',    'role' => 'Pastry & Dessert',     'emoji' => '🧁',   'color' => 'from-pink-400 to-rose-400'],
                        ['name' => 'Raka',    'role' => 'Beverages Specialist', 'emoji' => '🥤',   'color' => 'from-blue-400 to-cyan-400'],
                    ];
                @endphp
                @foreach($team as $member)
                <div class="team-card bg-white rounded-2xl p-5 shadow-sm border border-slate-100 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br {{ $member['color'] }} mx-auto flex items-center justify-center text-3xl mb-3 shadow-md">
                        {{ $member['emoji'] }}
                    </div>
                    <h3 class="font-extrabold text-slate-800 text-sm">{{ $member['name'] }}</h3>
                    <p class="text-slate-400 text-[11px] mt-0.5 font-medium">{{ $member['role'] }}</p>
                </div>
                @endforeach
            </div>
        </section>

        {{-- ===== KONTAK ===== --}}
        <section class="mt-12 fade-up-3">
            <div class="bg-slate-900 rounded-3xl p-6 md:p-10 text-white">
                <div class="md:grid md:grid-cols-2 gap-8 items-center">
                    <div>
                        <p class="text-orange-400 text-xs font-bold uppercase tracking-widest mb-2">Hubungi Kami</p>
                        <h2 class="text-2xl font-extrabold mb-4">Ada Pertanyaan? <br>Kami Siap Membantu! 💬</h2>
                        <div class="space-y-3">
                            @php
                                $contacts = [
                                    ['icon' => '📍', 'label' => 'Lokasi',    'val' => 'Jakarta, Indonesia'],
                                    ['icon' => '📞', 'label' => 'Telepon',   'val' => '+62 812 3456 789'],
                                    ['icon' => '✉️', 'label' => 'Email',     'val' => 'hello@monikakitchen.com'],
                                    ['icon' => '🕐', 'label' => 'Jam Buka',  'val' => 'Sen–Jum 09.00–21.00 · Sab–Min 10.00–22.00'],
                                ];
                            @endphp
                            @foreach($contacts as $c)
                            <div class="flex items-start gap-3">
                                <span class="text-lg flex-shrink-0">{{ $c['icon'] }}</span>
                                <div>
                                    <p class="text-slate-400 text-[11px] font-bold uppercase tracking-wide">{{ $c['label'] }}</p>
                                    <p class="text-white text-sm font-semibold">{{ $c['val'] }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="mt-6 md:mt-0 flex flex-col gap-3">
                        <a href="https://wa.me/6281234567890" class="flex items-center gap-3 bg-green-500 hover:bg-green-400 text-white font-bold px-5 py-3.5 rounded-2xl transition shadow-lg">
                            <span class="text-xl">💬</span> Chat via WhatsApp
                        </a>
                        <a href="https://instagram.com" class="flex items-center gap-3 bg-white/10 hover:bg-white/20 text-white font-bold px-5 py-3.5 rounded-2xl transition border border-white/20">
                            <span class="text-xl">📸</span> Follow Instagram Kami
                        </a>
                        <a href="{{ url('/menu') }}" class="flex items-center gap-3 bg-orange-500 hover:bg-orange-400 text-white font-bold px-5 py-3.5 rounded-2xl transition shadow-lg">
                            <span class="text-xl">🍽️</span> Pesan Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== FAQ ===== --}}
        <section class="mt-12 mb-4 fade-up-3">
            <div class="text-center mb-8">
                <p class="text-orange-500 text-xs font-bold uppercase tracking-widest mb-1">Pertanyaan Umum</p>
                <h2 class="text-2xl font-extrabold text-slate-900">FAQ ❓</h2>
                <p class="text-slate-400 text-sm mt-1">Jawaban untuk pertanyaan yang paling sering ditanyakan.</p>
            </div>

            @php
                $faqs = [
                    ['q' => 'Berapa lama waktu pengiriman pesanan?',
                     'a' => 'Pesanan biasanya siap dalam 10–15 menit setelah dikonfirmasi. Waktu pengiriman tergantung jarak, namun rata-rata tidak lebih dari 30 menit untuk area sekitar.'],

                    ['q' => 'Apakah bisa pesan untuk acara atau katering?',
                     'a' => 'Tentu! Kami menerima pesanan katering untuk berbagai acara seperti arisan, ulang tahun, rapat kantor, dan lainnya. Hubungi kami via WhatsApp minimal H-3 untuk koordinasi lebih lanjut.'],

                    ['q' => 'Apakah ada pilihan menu untuk vegetarian?',
                     'a' => 'Ya! Kami memiliki beberapa pilihan menu berbasis sayuran dan tahu/tempe. Kamu bisa filter atau tanyakan langsung kepada kami untuk rekomendasi menu vegetarian.'],

                    ['q' => 'Bagaimana cara membatalkan pesanan?',
                     'a' => 'Pembatalan pesanan hanya bisa dilakukan dalam 5 menit pertama setelah pesanan ditempatkan. Hubungi kami segera melalui WhatsApp jika ingin membatalkan.'],

                    ['q' => 'Apakah ada promo atau diskon?',
                     'a' => 'Ya, kami rutin mengadakan promo mingguan dan diskon spesial di hari-hari tertentu. Ikuti Instagram kami atau cek halaman utama aplikasi untuk info promo terbaru!'],

                    ['q' => 'Metode pembayaran apa saja yang diterima?',
                     'a' => 'Kami menerima pembayaran tunai, transfer bank, GoPay, OVO, Dana, dan QRIS. Pembayaran dilakukan saat pesanan tiba atau bisa juga melalui aplikasi.'],
                ];
            @endphp

            <div id="faq-list" class="flex flex-col gap-3">
                @foreach($faqs as $i => $faq)
                <div class="faq-item bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden" id="faq-{{ $i }}">
                    <button class="faq-trigger w-full flex items-center justify-between px-5 py-4 text-left gap-4"
                            onclick="toggleFaq({{ $i }})">
                        <span class="font-bold text-slate-800 text-sm pr-2">{{ $faq['q'] }}</span>
                        <span class="faq-icon text-orange-500 flex-shrink-0 text-xl font-light leading-none">+</span>
                    </button>
                    <div class="faq-content px-5" id="faq-content-{{ $i }}">
                        <p class="text-slate-500 text-sm leading-relaxed pb-4">{{ $faq['a'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

    </main>
</div>

<script>
function toggleFaq(index) {
    const item    = document.getElementById('faq-' + index);
    const content = document.getElementById('faq-content-' + index);
    const isOpen  = content.classList.contains('open');

    // Close all
    document.querySelectorAll('.faq-content').forEach(c => c.classList.remove('open'));
    document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));

    // Open clicked if it was closed
    if (!isOpen) {
        content.classList.add('open');
        item.classList.add('open');
    }
}
</script>

</body>
</html>
