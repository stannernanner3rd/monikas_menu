<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Monika's Kitchen</title>
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

        /* Toggle switch */
        .toggle-sw { position:relative; width:48px; height:26px; display:inline-block; }
        .toggle-sw input { opacity:0; width:0; height:0; }
        .toggle-sl { position:absolute; inset:0; background:#cbd5e1; border-radius:999px; transition:0.3s; cursor:pointer; }
        .toggle-sl::before { content:''; position:absolute; height:20px; width:20px; left:3px; bottom:3px; background:white; border-radius:50%; transition:0.3s; box-shadow:0 1px 3px rgba(0,0,0,0.15); }
        .toggle-sw input:checked + .toggle-sl { background:#ea580c; }
        .toggle-sw input:checked + .toggle-sl::before { transform:translateX(22px); }

        /* Lang button */
        .lang-btn { transition:all 0.2s; }
        .lang-btn.active { background:#ea580c; color:white; box-shadow:0 4px 12px rgba(234,88,12,0.3); }
    </style>
</head>

<body class="bg-slate-50 antialiased">
<div class="min-h-screen pb-28 md:pb-10 md:pl-64">

    <x-navibar />

    <main class="max-w-lg mx-auto px-4 md:max-w-none md:mx-0 md:px-10 md:py-8">

        {{-- Page Header --}}
        <section class="mt-6 fade-up">
            <p class="text-orange-500 text-xs font-bold uppercase tracking-widest mb-1">Preferensi</p>
            <h1 class="text-2xl font-extrabold text-slate-900">Settings</h1>
            <p class="text-slate-400 text-sm mt-0.5">Atur tampilan dan bahasa aplikasi.</p>
        </section>

        {{-- ===== APPEARANCE ===== --}}
        <section class="mt-6 fade-up">
            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-3">Tampilan</p>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 divide-y divide-slate-50">
                {{-- Dark Mode --}}
                <div class="flex items-center justify-between px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-900 flex items-center justify-center text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800" data-i18n="dark_mode">Mode Gelap</p>
                            <p class="text-xs text-slate-400" data-i18n="dark_mode_desc">Tampilan lebih nyaman di malam hari</p>
                        </div>
                    </div>
                    <label class="toggle-sw">
                        <input type="checkbox" id="dark-toggle">
                        <span class="toggle-sl"></span>
                    </label>
                </div>
            </div>
        </section>

        {{-- ===== LANGUAGE ===== --}}
        <section class="mt-5 fade-up-1">
            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-3">Bahasa / Language</p>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800" data-i18n="language">Pilih Bahasa</p>
                        <p class="text-xs text-slate-400" data-i18n="language_desc">Ubah bahasa tampilan aplikasi</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button id="lang-id" class="lang-btn flex-1 py-2.5 rounded-xl font-bold text-sm border border-slate-200 text-slate-600 text-center">
                        🇮🇩 Indonesia
                    </button>
                    <button id="lang-en" class="lang-btn flex-1 py-2.5 rounded-xl font-bold text-sm border border-slate-200 text-slate-600 text-center">
                        🇬🇧 English
                    </button>
                </div>
            </div>
        </section>

        {{-- ===== ACCOUNT (placeholder) ===== --}}
        <section class="mt-5 fade-up-1">
            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-3" data-i18n="account_section">Akun</p>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-slate-400" data-i18n="account_coming">Segera Hadir</p>
                        <p class="text-xs text-slate-300" data-i18n="account_coming_desc">Login, profil, dan pengaturan akun akan tersedia di versi mendatang.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== ABOUT APP ===== --}}
        <section class="mt-5 mb-4 fade-up-2">
            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-3" data-i18n="about_app_section">Tentang Aplikasi</p>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 divide-y divide-slate-50">
                <div class="flex items-center justify-between px-5 py-3.5">
                    <span class="text-sm font-semibold text-slate-600" data-i18n="version">Versi</span>
                    <span class="text-sm font-bold text-slate-800">1.0.0</span>
                </div>
                <a href="{{ url('/about') }}" class="flex items-center justify-between px-5 py-3.5 hover:bg-slate-50 transition">
                    <span class="text-sm font-semibold text-slate-600" data-i18n="about_us">Tentang Kami</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ url('/admin') }}" class="flex items-center justify-between px-5 py-3.5 hover:bg-slate-50 transition">
                    <span class="text-sm font-semibold text-slate-600" data-i18n="admin_panel">Admin Panel</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </section>

    </main>
</div>

<script>
(function() {
    // ==================== DARK MODE ====================
    const darkToggle = document.getElementById('dark-toggle');
    const html = document.documentElement;

    function applyDarkMode(isDark) {
        if (isDark) {
            html.classList.add('dark');
            html.setAttribute('data-theme', 'dark');
        } else {
            html.classList.remove('dark');
            html.removeAttribute('data-theme');
        }
    }

    // Load saved preference
    const savedDark = localStorage.getItem('mk-dark-mode') === 'true';
    darkToggle.checked = savedDark;
    applyDarkMode(savedDark);

    darkToggle.addEventListener('change', function() {
        const isDark = this.checked;
        localStorage.setItem('mk-dark-mode', isDark);
        applyDarkMode(isDark);
    });

    // ==================== LANGUAGE ====================
    const translations = {
        id: {
            dark_mode: 'Mode Gelap',
            dark_mode_desc: 'Tampilan lebih nyaman di malam hari',
            language: 'Pilih Bahasa',
            language_desc: 'Ubah bahasa tampilan aplikasi',
            account_section: 'Akun',
            account_coming: 'Segera Hadir',
            account_coming_desc: 'Login, profil, dan pengaturan akun akan tersedia di versi mendatang.',
            about_app_section: 'Tentang Aplikasi',
            version: 'Versi',
            about_us: 'Tentang Kami',
            admin_panel: 'Admin Panel',
        },
        en: {
            dark_mode: 'Dark Mode',
            dark_mode_desc: 'Easier on the eyes at night',
            language: 'Select Language',
            language_desc: 'Change the app display language',
            account_section: 'Account',
            account_coming: 'Coming Soon',
            account_coming_desc: 'Login, profile, and account settings will be available in a future version.',
            about_app_section: 'About App',
            version: 'Version',
            about_us: 'About Us',
            admin_panel: 'Admin Panel',
        }
    };

    const langIdBtn = document.getElementById('lang-id');
    const langEnBtn = document.getElementById('lang-en');

    function applyLanguage(lang) {
        const t = translations[lang];
        document.querySelectorAll('[data-i18n]').forEach(el => {
            const key = el.getAttribute('data-i18n');
            if (t[key]) el.textContent = t[key];
        });

        // Update button states
        if (lang === 'id') {
            langIdBtn.classList.add('active');
            langEnBtn.classList.remove('active');
        } else {
            langEnBtn.classList.add('active');
            langIdBtn.classList.remove('active');
        }
    }

    const savedLang = localStorage.getItem('mk-lang') || 'id';
    applyLanguage(savedLang);

    langIdBtn.addEventListener('click', function() {
        localStorage.setItem('mk-lang', 'id');
        applyLanguage('id');
    });
    langEnBtn.addEventListener('click', function() {
        localStorage.setItem('mk-lang', 'en');
        applyLanguage('en');
    });

})();
</script>

</body>
</html>
