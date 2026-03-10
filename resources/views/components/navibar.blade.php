@php
    $currentPath = request()->path();

    $navItems = [
        [
            'path'  => 'main',
            'url'   => url('/main'),
            'label' => 'Beranda',
            'icon_outline' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
            'icon_solid'   => '<path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>',
        ],
        [
            'path'  => 'menu',
            'url'   => url('/menu'),
            'label' => 'Menu',
            'icon_outline' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>',
            'icon_solid'   => '<path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>',
        ],
        [
            'path'  => 'orders',
            'url'   => url('/orders'),
            'label' => 'Pesanan',
            'icon_outline' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>',
            'icon_solid'   => '<path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>',
        ],
        [
            'path'  => 'about',
            'url'   => url('/about'),
            'label' => 'Tentang',
            'icon_outline' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            'icon_solid'   => '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>',
        ],
    ];
@endphp

{{-- ============================================================ --}}
{{-- DESKTOP SIDEBAR — visible md+ only                          --}}
{{-- ============================================================ --}}
<aside class="hidden md:flex flex-col fixed inset-y-0 left-0 w-64 bg-white border-r border-slate-100 shadow-sm z-50">

    {{-- Brand --}}
    <div class="px-6 py-5 border-b border-slate-100">
        <a href="{{ url('/main') }}" class="block">
            <span class="text-xl font-black text-slate-900 tracking-tighter leading-none">
                MONIKA<span class="text-orange-600">KITCHEN.</span>
            </span>
            <p class="text-[11px] text-slate-400 font-medium mt-1">Masakan rumahan terbaik 🍳</p>
        </a>
    </div>

    {{-- Search --}}
    <div class="px-4 py-3 border-b border-slate-50">
        <div class="relative">
            <span class="absolute inset-y-0 left-3 flex items-center text-slate-400 pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </span>
            <input type="text"
                   placeholder="Cari menu..."
                   class="w-full pl-9 pr-4 py-2.5 rounded-xl bg-slate-100 border-none focus:ring-2 focus:ring-orange-500 text-sm text-slate-700 placeholder-slate-400 transition-all outline-none">
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-3 space-y-0.5 overflow-y-auto">
        <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest px-3 mb-2">Navigasi</p>
        @foreach($navItems as $item)
        @php $isActive = $currentPath === $item['path']; @endphp
        <a href="{{ $item['url'] }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-sm transition-all duration-200
                  {{ $isActive
                       ? 'bg-orange-50 text-orange-600 font-bold shadow-sm'
                       : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
            {{-- Icon --}}
            @if($isActive)
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                {!! $item['icon_solid'] !!}
            </svg>
            @else
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                {!! $item['icon_outline'] !!}
            </svg>
            @endif
            {{ $item['label'] }}
            {{-- Active bar --}}
            @if($isActive)
            <span class="ml-auto w-1.5 h-1.5 rounded-full bg-orange-500 flex-shrink-0"></span>
            @endif
        </a>
        @endforeach
    </nav>

    {{-- Sidebar Footer --}}
    <div class="px-4 py-4 border-t border-slate-100 space-y-3">
        <a href="{{ url('/menu') }}"
           class="flex items-center justify-center gap-2 w-full bg-orange-500 hover:bg-orange-600 text-white font-bold text-sm px-4 py-2.5 rounded-xl shadow transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            Pesan Sekarang
        </a>
        <div class="bg-slate-50 rounded-xl px-3 py-2.5">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Jam Buka</p>
            <p class="text-xs text-slate-600 font-semibold">Sen–Jum: 09.00–21.00</p>
            <p class="text-xs text-slate-600 font-semibold">Sab–Min: 10.00–22.00</p>
        </div>
    </div>
</aside>

{{-- ============================================================ --}}
{{-- MOBILE TOP HEADER — visible below md only                    --}}
{{-- ============================================================ --}}
<header class="md:hidden bg-white px-4 pt-4 pb-3 shadow-sm sticky top-0 z-50">
    <div class="flex items-center gap-2.5">
        <a href="{{ url('/main') }}" class="text-slate-900 text-xl font-black tracking-tighter flex-shrink-0 leading-none">
            MONIKA<span class="text-orange-600">KITCHEN.</span>
        </a>

        <div class="relative flex-1">
            <span class="absolute inset-y-0 left-3 flex items-center text-slate-400 pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </span>
            <input type="text"
                   placeholder="Cari menu..."
                   class="w-full pl-9 pr-4 py-2 rounded-full bg-slate-100 border-none focus:ring-2 focus:ring-orange-500 text-sm text-slate-700 placeholder-slate-400 transition-all outline-none">
        </div>

        <a href="{{ url('/orders') }}"
           class="relative bg-slate-900 p-2.5 rounded-full text-white shadow-lg hover:bg-slate-800 transition flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
        </a>
    </div>
</header>

{{-- ============================================================ --}}
{{-- MOBILE BOTTOM NAV — visible below md only                    --}}
{{-- ============================================================ --}}
<nav class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-slate-100 shadow-[0_-4px_20px_rgba(0,0,0,0.06)]">
    <div class="flex items-center justify-around px-2 py-2">
        @foreach($navItems as $item)
        @php $isActive = $currentPath === $item['path']; @endphp
        <a href="{{ $item['url'] }}"
           class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-2xl transition-all duration-200 min-w-[56px]
                  {{ $isActive ? 'text-orange-600' : 'text-slate-400' }}">
            <div class="relative">
                @if($isActive)
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                    {!! $item['icon_solid'] !!}
                </svg>
                <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full bg-orange-500"></span>
                @else
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    {!! $item['icon_outline'] !!}
                </svg>
                @endif
            </div>
            <span class="text-[10px] font-{{ $isActive ? 'extrabold' : 'semibold' }} leading-none mt-1">{{ $item['label'] }}</span>
        </a>
        @endforeach
    </div>
</nav>

<style>
    @@supports (padding-bottom: env(safe-area-inset-bottom)) {
        .safe-area-pad { padding-bottom: env(safe-area-inset-bottom); }
    }
</style>