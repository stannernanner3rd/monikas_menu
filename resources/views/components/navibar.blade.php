@php
    $currentPath = request()->path();

    $navItems = [
        ['path' => 'main',     'url' => '/main',     'label' => 'Beranda'],
        ['path' => 'menu',     'url' => '/menu',     'label' => 'Menu'],
        ['path' => 'orders',   'url' => '/orders',   'label' => 'Pesanan'],
        ['path' => 'about',    'url' => '/about',    'label' => 'Tentang'],
        ['path' => 'settings', 'url' => '/settings', 'label' => 'Settings'],
    ];

    $navIcons = [
        'main'   => ['outline' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                      'solid'  => 'M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z'],
        'menu'   => ['outline' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
                      'solid'  => 'M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z'],
        'orders' => ['outline' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
                      'solid'  => ''],
        'about'    => ['outline' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                        'solid'  => ''],
        'settings' => ['outline' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
                        'solid'  => ''],
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
            <p class="text-[11px] text-slate-400 font-medium mt-1">Masakan rumahan terbaik</p>
        </a>
    </div>

    {{-- Search (desktop) --}}
    <div class="px-4 py-3 border-b border-slate-50 relative">
        <div class="relative">
            <span class="absolute inset-y-0 left-3 flex items-center text-slate-400 pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </span>
            <input type="text" id="desktop-search"
                   placeholder="Cari menu..."
                   autocomplete="off"
                   class="w-full pl-9 pr-4 py-2.5 rounded-xl bg-slate-100 border-none focus:ring-2 focus:ring-orange-500 text-sm text-slate-700 placeholder-slate-400 transition-all outline-none">
        </div>
        {{-- Desktop search dropdown --}}
        <div id="desktop-search-dropdown" class="hidden absolute left-4 right-4 top-full mt-1 bg-white rounded-xl shadow-xl border border-slate-100 max-h-80 overflow-y-auto z-50"></div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-3 space-y-0.5 overflow-y-auto">
        <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest px-3 mb-2">Navigasi</p>
        @foreach($navItems as $item)
        @php $isActive = $currentPath === $item['path']; @endphp
        <a href="{{ url($item['url']) }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-sm transition-all duration-200
                  {{ $isActive
                       ? 'bg-orange-50 text-orange-600 font-bold shadow-sm'
                       : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
            @if($isActive && $navIcons[$item['path']]['solid'])
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path d="{{ $navIcons[$item['path']]['solid'] }}"/></svg>
            @else
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $navIcons[$item['path']]['outline'] }}"/></svg>
            @endif
            {{ $item['label'] }}
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
            <input type="text" id="mobile-search"
                   placeholder="Cari menu..."
                   autocomplete="off"
                   class="w-full pl-9 pr-4 py-2 rounded-full bg-slate-100 border-none focus:ring-2 focus:ring-orange-500 text-sm text-slate-700 placeholder-slate-400 transition-all outline-none">
            {{-- Mobile search dropdown --}}
            <div id="mobile-search-dropdown" class="hidden absolute left-0 right-0 top-full mt-2 bg-white rounded-xl shadow-xl border border-slate-100 max-h-72 overflow-y-auto z-50"></div>
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
<nav class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-slate-100 shadow-[0_-4px_20px_rgba(0,0,0,0.06)] safe-area-pad">
    <div class="flex items-center justify-around px-2 py-2">
        @foreach($navItems as $item)
        @php $isActive = $currentPath === $item['path']; @endphp
        <a href="{{ url($item['url']) }}"
           class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-2xl transition-all duration-200 min-w-[56px]
                  {{ $isActive ? 'text-orange-600' : 'text-slate-400' }}">
            <div class="relative">
                @if($isActive && $navIcons[$item['path']]['solid'])
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor"><path d="{{ $navIcons[$item['path']]['solid'] }}"/></svg>
                <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full bg-orange-500"></span>
                @else
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $navIcons[$item['path']]['outline'] }}"/></svg>
                @if($isActive)
                <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full bg-orange-500"></span>
                @endif
                @endif
            </div>
            <span class="text-[10px] font-{{ $isActive ? 'extrabold' : 'semibold' }} leading-none mt-1">{{ $item['label'] }}</span>
        </a>
        @endforeach
    </div>
</nav>

{{-- ============================================================ --}}
{{-- SEARCH FUNCTIONALITY (shared JS)                            --}}
{{-- ============================================================ --}}
<script>
(function() {
    // ---- Hardcoded menu database (akan diganti dari DB nanti) ----
    const menuDatabase = [
        { name: 'Pizza Margherita',    cat: 'Pizza',     price: 'Rp 65.000', img: 'https://images.unsplash.com/photo-1604382354936-07c5d9983bd3?auto=format&fit=crop&w=100&q=80' },
        { name: 'Pizza Meat Lovers',   cat: 'Pizza',     price: 'Rp 85.000', img: 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?auto=format&fit=crop&w=100&q=80' },
        { name: 'Beef & Pineapple Pizza', cat: 'Pizza',  price: 'Rp 71.000', img: 'https://images.unsplash.com/photo-1513104890138-7c749659a591?auto=format&fit=crop&w=100&q=80' },
        { name: 'Nasi Ayam Bakar',     cat: 'Nasi Ayam', price: 'Rp 35.000', img: 'https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?auto=format&fit=crop&w=100&q=80' },
        { name: 'Nasi Ayam Geprek',    cat: 'Nasi Ayam', price: 'Rp 28.000', img: 'https://images.unsplash.com/photo-1632778149955-e80f8ceca2e8?auto=format&fit=crop&w=100&q=80' },
        { name: 'Spicy Honey Chicken', cat: 'Ayam',      price: 'Rp 45.000', img: 'https://images.unsplash.com/photo-1567620832903-9fc6debc209f?auto=format&fit=crop&w=100&q=80' },
        { name: 'Spaghetti Carbonara', cat: 'Pasta',     price: 'Rp 45.000', img: 'https://images.unsplash.com/photo-1612450800052-759c5509b58e?auto=format&fit=crop&w=100&q=80' },
        { name: 'Creamy Mushroom Pasta', cat: 'Pasta',   price: 'Rp 55.000', img: 'https://images.unsplash.com/photo-1556761223-4c4282c73f77?auto=format&fit=crop&w=100&q=80' },
        { name: 'Double Cheeseburger', cat: 'Burger',    price: 'Rp 60.000', img: 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=100&q=80' },
        { name: 'Grilled Salmon Steak', cat: 'Seafood',  price: 'Rp 95.000', img: 'https://images.unsplash.com/photo-1467003909585-2f8a7270028d?auto=format&fit=crop&w=100&q=80' },
        { name: 'Es Teh Manis',        cat: 'Minuman',   price: 'Rp 5.000',  img: 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=100&q=80' },
        { name: 'Es Timun Selasih',    cat: 'Minuman',   price: 'Rp 13.000', img: 'https://images.unsplash.com/photo-1626078299046-9fbeade2891e?auto=format&fit=crop&w=100&q=80' },
        { name: 'Ice Matcha Latte',    cat: 'Minuman',   price: 'Rp 25.000', img: 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=100&q=80' },
        { name: 'Jus Alpukat Susu',    cat: 'Minuman',   price: 'Rp 18.000', img: 'https://images.unsplash.com/photo-1623065422902-30a2d299bbe4?auto=format&fit=crop&w=100&q=80' },
        { name: 'French Fries',        cat: 'Snack',     price: 'Rp 15.000', img: 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?auto=format&fit=crop&w=100&q=80' },
        { name: 'Sate Ayam Bumbu',     cat: 'Makanan',   price: 'Rp 35.000', img: 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=100&q=80' },
        { name: 'Mie Goreng Spesial',  cat: 'Makanan',   price: 'Rp 22.000', img: 'https://images.unsplash.com/photo-1569050467447-ce54b3bbc37d?auto=format&fit=crop&w=100&q=80' },
    ];

    function renderDropdown(results, query) {
        if (!query || results.length === 0) {
            if (!query) return '';
            return '<div class="p-4 text-center text-slate-400 text-sm">Tidak ditemukan menu untuk "<span class="font-bold text-slate-500">' + query + '</span>"</div>';
        }

        let html = '<div class="py-1">';
        results.forEach(item => {
            const highlighted = item.name.replace(
                new RegExp('(' + query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi'),
                '<span class="text-orange-600 font-extrabold">$1</span>'
            );
            html += `
                <a href="/menu?search=${encodeURIComponent(item.name)}"
                   class="flex items-center gap-3 px-4 py-2.5 hover:bg-orange-50 transition cursor-pointer">
                    <img src="${item.img}" alt="${item.name}" class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-800 truncate">${highlighted}</p>
                        <p class="text-xs text-slate-400">${item.cat} · ${item.price}</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>`;
        });
        html += '</div>';

        if (results.length > 0) {
            html += `<a href="/menu?search=${encodeURIComponent(query)}"
                        class="block text-center text-xs font-bold text-orange-600 hover:text-orange-700 py-2.5 border-t border-slate-100 transition">
                        Lihat semua hasil untuk "${query}" →
                     </a>`;
        }
        return html;
    }

    function searchMenu(query) {
        if (!query || query.length < 1) return [];
        const q = query.toLowerCase();
        return menuDatabase.filter(item =>
            item.name.toLowerCase().includes(q) || item.cat.toLowerCase().includes(q)
        ).slice(0, 6);
    }

    function setupSearch(inputId, dropdownId) {
        const input = document.getElementById(inputId);
        const dropdown = document.getElementById(dropdownId);
        if (!input || !dropdown) return;

        let debounceTimer;

        input.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const query = this.value.trim();
                if (query.length === 0) {
                    dropdown.classList.add('hidden');
                    return;
                }
                const results = searchMenu(query);
                dropdown.innerHTML = renderDropdown(results, query);
                dropdown.classList.remove('hidden');
            }, 150);
        });

        input.addEventListener('focus', function() {
            const query = this.value.trim();
            if (query.length > 0) {
                const results = searchMenu(query);
                dropdown.innerHTML = renderDropdown(results, query);
                dropdown.classList.remove('hidden');
            }
        });

        document.addEventListener('click', function(e) {
            if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                const query = this.value.trim();
                if (query) window.location.href = '/menu?search=' + encodeURIComponent(query);
            }
            if (e.key === 'Escape') dropdown.classList.add('hidden');
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        setupSearch('desktop-search', 'desktop-search-dropdown');
        setupSearch('mobile-search', 'mobile-search-dropdown');
    });
})();
</script>

<style>
    @@supports (padding-bottom: env(safe-area-inset-bottom)) {
        .safe-area-pad { padding-bottom: env(safe-area-inset-bottom); }
    }
</style>

{{-- Dark mode persistence (loads on all pages via navibar) --}}
<script>
(function(){
    if (localStorage.getItem('mk-dark-mode') === 'true') {
        document.documentElement.classList.add('dark');
        document.documentElement.setAttribute('data-theme', 'dark');
    }
})();
</script>