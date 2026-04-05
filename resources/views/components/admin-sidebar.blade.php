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
        <a href="{{ url('/main') }}" class="flex items-center gap-2 text-slate-400 hover:text-white text-sm font-semibold transition px-3 py-2">
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
            <a href="{{ url('/admin') }}"
               class="px-3 py-1.5 rounded-lg text-xs font-bold {{ $active === 'dashboard' ? 'bg-orange-500 text-white' : 'text-slate-400' }}">
                Dashboard
            </a>
            <a href="{{ url('/admin/menu-test') }}"
               class="px-3 py-1.5 rounded-lg text-xs font-bold {{ $active === 'testo' ? 'bg-orange-500 text-white' : 'text-slate-400' }}">
                Menu
            </a>
            <a href="{{ url('/admin/pesanan') }}"
               class="px-3 py-1.5 rounded-lg text-xs font-bold {{ $active === 'pesanan' ? 'bg-orange-500 text-white' : 'text-slate-400' }}">
                Pesanan
            </a>
            <a href="{{ url('/main') }}" class="text-slate-400 ml-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
            </a>
        </div>
    </div>
</header>
