<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tes Tailwind - Monika Kitchen</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-slate-50 antialiased">

    <nav class="flex justify-between items-center px-10 py-6 bg-white shadow-sm">
        <div class="text-2xl font-black text-orange-600 tracking-tighter">
            MONIKA<span class="text-slate-800">KITCHEN.</span>
        </div>
        <div class="space-x-8 font-medium text-slate-600">
            <a href="#" class="hover:text-orange-500 transition">Menu</a>
            <a href="#" class="hover:text-orange-500 transition">Tentang Kami</a>
            <button class="bg-orange-500 text-white px-6 py-2 rounded-full hover:bg-orange-600 transition shadow-lg shadow-orange-200">
                Pesan Sekarang
            </button>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto mt-16 px-10">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            
            <div>
                <span class="bg-orange-100 text-orange-700 px-4 py-1 rounded-full text-sm font-bold uppercase tracking-widest">
                    Lapar Melanda?
                </span>
                <h1 class="text-6xl font-extrabold text-slate-900 leading-tight mt-6">
                    Rasakan Masakan <br> 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-600">
                        Penuh Cinta
                    </span> 
                    di Setiap Suapan.
                </h1>
                <p class="text-slate-500 text-lg mt-6 leading-relaxed">
                    Dari dapur Monika langsung ke meja makanmu. Bahan pilihan, bumbu rahasia, dan rasa yang bikin kangen rumah.
                </p>
                
                <div class="flex gap-4 mt-10">
                    <button class="bg-slate-900 text-white px-8 py-4 rounded-2xl font-bold hover:bg-slate-800 transition shadow-xl">
                        Lihat Menu Utama
                    </button>
                    <button class="border-2 border-slate-200 text-slate-700 px-8 py-4 rounded-2xl font-bold hover:bg-slate-50 transition">
                        Promo Hari Ini
                    </button>
                </div>
            </div>

            <div class="relative">
                <div class="w-full h-[400px] bg-gradient-to-tr from-orange-400 to-orange-200 rounded-[40px] shadow-2xl rotate-3 flex items-center justify-center border-8 border-white">
                    <p class="text-white font-bold text-xl -rotate-3 italic text-center px-10">
                        [ Nanti di sini kita taruh foto Ayam Bakar Madu yang menggoda ]
                    </p>
                </div>
                <div class="absolute -bottom-6 -left-6 bg-white p-6 rounded-3xl shadow-xl flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white">
                        ⭐
                    </div>
                    <div>
                        <p class="font-bold text-slate-800">4.9 / 5.0</p>
                        <p class="text-xs text-slate-500">2,000+ Ulasan Puas</p>
                    </div>
                </div>
            </div>

        </div>
    </main>
<footer class="bg-slate-900 text-white mt-20 pt-16 pb-8">
    <div class="max-w-6xl mx-auto px-10">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
            
            <div class="col-span-1 md:col-span-1">
                <div class="text-xl font-black text-orange-500 tracking-tighter mb-4">
                    MONIKA<span class="text-white">KITCHEN.</span>
                </div>
                <p class="text-slate-400 text-sm leading-relaxed">
                    Menyajikan kebahagiaan melalui masakan rumahan yang higienis dan penuh rasa sejak tahun 2024.
                </p>
            </div>

            <div>
                <h4 class="font-bold text-lg mb-6 text-orange-500">Tautan Cepat</h4>
                <ul class="space-y-4 text-slate-400 text-sm">
                    <li><a href="#" class="hover:text-white transition">Menu Utama</a></li>
                    <li><a href="#" class="hover:text-white transition">Promo Spesial</a></li>
                    <li><a href="#" class="hover:text-white transition">Cara Pesan</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-lg mb-6 text-orange-500">Hubungi Kami</h4>
                <ul class="space-y-4 text-slate-400 text-sm">
                    <li class="flex items-center gap-2">📍 Jakarta, Indonesia</li>
                    <li class="flex items-center gap-2">📞 +62 812 3456 789</li>
                    <li class="flex items-center gap-2">✉️ hello@monikakitchen.com</li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-lg mb-6 text-orange-500">Jam Buka</h4>
                <p class="text-slate-400 text-sm">Senin - Jumat: 09.00 - 21.00</p>
                <p class="text-slate-400 text-sm mt-2">Sabtu - Minggu: 10.00 - 22.00</p>
            </div>
        </div>

        <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-slate-500 text-xs">
                &copy; 2026 Monika Kitchen. Semua Hak Dilindungi.
            </p>
            <div class="flex gap-6">
                <span class="text-slate-500 hover:text-orange-500 cursor-pointer text-sm">Instagram</span>
                <span class="text-slate-500 hover:text-orange-500 cursor-pointer text-sm">WhatsApp</span>
                <span class="text-slate-500 hover:text-orange-500 cursor-pointer text-sm">TikTok</span>
            </div>
        </div>
    </div>
</footer>
</body>
</html>