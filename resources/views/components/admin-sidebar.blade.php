{{--
=============================================================================
KOMPONEN: Admin Sidebar
=============================================================================
Cara pakai di halaman admin mana saja:
    <x-admin-sidebar active="dashboard" />
    <x-admin-sidebar active="menu" />
    <x-admin-sidebar active="testo" />

Prop "active" menentukan link mana yang di-highlight oranye.
=============================================================================
--}}

@props(['active' => ''])

{{-- ===== SIDEBAR DESKTOP (muncul di layar >= md) ===== --}}
<aside class="hidden md:flex flex-col fixed inset-y-0 left-0 w-64 bg-slate-900 z-50">
    {{-- Logo --}}
    <div class="px-6 py-5 border-b border-slate-700">
        <a href="{{ url('/admin') }}" class="block">
            <span class="text-xl font-black text-white tracking-tighter leading-none">
                MONIKA<span class="text-orange-500">KITCHEN.</span>
            </span>
            <p class="text-[11px] text-slate-500 font-medium mt-1">Admin Panel</p>
        </a>
    </div>

    {{-- Navigasi --}}
    <nav class="flex-1 px-3 py-4 space-y-1">
        <p class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest px-3 mb-2">Menu</p>

        {{-- Link: Dashboard --}}
        <a href="{{ url('/admin') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition
           {{ $active === 'dashboard' ? 'bg-orange-500/15 text-orange-400 font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            Dashboard
        </a>

        {{-- Link: Kelola Menu (Testo / DB version) --}}
        <a href="{{ url('/admin/menu-test') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition
           {{ $active === 'testo' ? 'bg-orange-500/15 text-orange-400 font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            Kelola Menu
        </a>

        {{-- Link: Pesanan Masuk --}}
        <a href="{{ url('/admin/pesanan') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition
           {{ $active === 'pesanan' ? 'bg-orange-500/15 text-orange-400 font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            Pesanan
        </a>

    </nav>

    {{-- Footer: Kembali ke Toko --}}
    <div class="px-4 py-4 border-t border-slate-700">
        <a href="{{ url('/') }}" class="flex items-center gap-2 text-slate-400 hover:text-white text-sm font-semibold transition px-3 py-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
            Kembali ke Toko
        </a>
    </div>
</aside>

{{-- ===== HEADER MOBILE (muncul di layar < md) ===== --}}
<header class="md:hidden bg-slate-900 px-4 pt-4 pb-3 sticky top-0 z-50">
    <div class="flex items-center justify-between">
        <a href="{{ url('/admin') }}" class="text-white text-lg font-black tracking-tighter">
            MONIKA<span class="text-orange-500">KITCHEN.</span>
        </a>
        <div class="flex items-center gap-2">
            {{-- Active page badge --}}
            <span class="text-[10px] font-bold text-orange-400 bg-orange-500/15 px-2.5 py-1 rounded-full uppercase tracking-wider">
                @if($active === 'dashboard') Dashboard
                @elseif($active === 'testo') Menu
                @elseif($active === 'pesanan') Pesanan
                @else Admin
                @endif
            </span>
            {{-- Hamburger button --}}
            <button id="admin-mobile-toggle" class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition-all duration-200" aria-label="Toggle menu">
                <svg id="admin-hamburger-icon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg id="admin-close-icon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hidden transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Dropdown menu --}}
    <div id="admin-mobile-menu" class="hidden mt-3 bg-slate-800/95 backdrop-blur-lg rounded-2xl border border-slate-700/50 shadow-2xl overflow-hidden transition-all duration-300 origin-top">
        <nav class="p-2 space-y-0.5">
            <a href="{{ url('/admin') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200
               {{ $active === 'dashboard' ? 'bg-orange-500/15 text-orange-400' : 'text-slate-300 hover:bg-slate-700/60 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                Dashboard
                @if($active === 'dashboard')<span class="ml-auto w-1.5 h-1.5 rounded-full bg-orange-400"></span>@endif
            </a>
            <a href="{{ url('/admin/menu-test') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200
               {{ $active === 'testo' ? 'bg-orange-500/15 text-orange-400' : 'text-slate-300 hover:bg-slate-700/60 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                Kelola Menu
                @if($active === 'testo')<span class="ml-auto w-1.5 h-1.5 rounded-full bg-orange-400"></span>@endif
            </a>
            <a href="{{ url('/admin/pesanan') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200
               {{ $active === 'pesanan' ? 'bg-orange-500/15 text-orange-400' : 'text-slate-300 hover:bg-slate-700/60 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                Pesanan
                @if($active === 'pesanan')<span class="ml-auto w-1.5 h-1.5 rounded-full bg-orange-400"></span>@endif
            </a>
        </nav>
        <div class="border-t border-slate-700/50 p-2">
            <a href="{{ url('/') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-slate-700/60 text-sm font-semibold transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                Kembali ke Toko
            </a>
        </div>
    </div>
</header>

{{-- Admin mobile menu toggle script --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('admin-mobile-toggle');
    const menu = document.getElementById('admin-mobile-menu');
    const hamburger = document.getElementById('admin-hamburger-icon');
    const closeIcon = document.getElementById('admin-close-icon');
    if (!toggle || !menu) return;

    toggle.addEventListener('click', function() {
        const isOpen = !menu.classList.contains('hidden');
        if (isOpen) {
            menu.style.maxHeight = '0px';
            menu.style.opacity = '0';
            setTimeout(() => { menu.classList.add('hidden'); menu.style.maxHeight = ''; menu.style.opacity = ''; }, 200);
            hamburger.classList.remove('hidden');
            closeIcon.classList.add('hidden');
        } else {
            menu.classList.remove('hidden');
            menu.style.maxHeight = '0px';
            menu.style.opacity = '0';
            requestAnimationFrame(() => {
                menu.style.transition = 'max-height 0.3s ease, opacity 0.2s ease';
                menu.style.maxHeight = '400px';
                menu.style.opacity = '1';
            });
            hamburger.classList.add('hidden');
            closeIcon.classList.remove('hidden');
        }
    });
});
</script>

