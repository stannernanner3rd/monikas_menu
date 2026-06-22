<?php

use App\Models\Menu;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// =====================================================================
// SEARCH API — for navibar live search (active menu only)
// =====================================================================
Route::get('/api/search-menu', function (Request $request) {
    $q = $request->input('q', '');
    $query = Menu::with('kategori')->where('is_aktif', 1);
    if ($q) {
        $query->where(function ($w) use ($q) {
            $w->where('nama', 'LIKE', "%{$q}%")
              ->orWhereHas('kategori', function ($k) use ($q) {
                  $k->where('nama', 'LIKE', "%{$q}%");
              });
        });
    }
    $results = $query->limit(8)->get()->map(function ($m) {
        return [
            'id'    => $m->id,
            'nama'  => $m->nama,
            'kategori' => $m->kategori->nama ?? '-',
            'harga' => $m->harga,
            'harga_promo' => $m->harga_promo,
            'gambar' => $m->gambar,
        ];
    });
    return response()->json($results);
});

Route::get('/', function () {
    $spesial = Menu::with('kategori')->where('is_spesial', 1)->where('is_aktif', 1)->get();
    $kategoriDB = \App\Models\Kategori::whereHas('menu', function ($q) {
        $q->where('is_aktif', 1);
    })->withCount(['menu' => function ($q) {
        $q->where('is_aktif', 1);
    }])->get();

    // Menu Terlaris: hitung dari detail_pesanan
    $terlaris = DB::table('detail_pesanan')
        ->join('pesanan', 'detail_pesanan.id_pesanan', '=', 'pesanan.id')
        ->join('menu', 'detail_pesanan.id_menu', '=', 'menu.id')
        ->leftJoin('kategori', 'menu.id_kategori', '=', 'kategori.id')
        ->select('menu.*', 'kategori.nama as kategori_nama', DB::raw('SUM(detail_pesanan.qty) as total_terjual'))
        ->where('menu.is_aktif', 1)
        ->whereIn('pesanan.status', ['pending','diproses','selesai'])
        ->groupBy('menu.id')
        ->orderByDesc('total_terjual')
        ->limit(6)
        ->get();

    return view('main.main', compact('spesial', 'kategoriDB', 'terlaris'));
});

Route::get('/menu', function () {
    $menuDB = Menu::with('kategori')->where('is_aktif', 1)->get();
    $kategoriDB = \App\Models\Kategori::whereHas('menu', function ($q) {
        $q->where('is_aktif', 1);
    })
        ->withCount(['menu' => function ($q) {
            $q->where('is_aktif', 1);
        }])
        ->get();

    // Hitung sisa poin hari ini
    $today = now()->toDateString();
    $poinHarian = DB::table('settings')->where('key', 'poin_harian')->value('value') ?? 100;
    $poinTerpakai = DB::table('detail_pesanan')
        ->join('pesanan', 'detail_pesanan.id_pesanan', '=', 'pesanan.id')
        ->join('menu', 'detail_pesanan.id_menu', '=', 'menu.id')
        ->where('pesanan.tanggal_ambil', $today)
        ->whereIn('pesanan.status', ['pending', 'diproses', 'selesai'])
        ->sum(DB::raw('COALESCE(menu.poin, 0) * detail_pesanan.qty'));
    $sisaPoin = max(0, $poinHarian - $poinTerpakai);

    return view('main.menu', compact('menuDB', 'kategoriDB', 'sisaPoin', 'poinHarian'));
});



Route::get('/orders', function () {
    return view('main.orders');
});

Route::get('/about', function () {
    return view('main.about');
});

Route::get('/cart', function () {
    return redirect('/orders');
});

Route::get('/settings', function () {
    return view('main.settings');
});

Route::get('/admin', function (Request $request) {
    if (!session('is_admin')) return redirect('/login');

    $period = $request->period ?? 'hari_ini';
    $periodLabel = match($period) {
        'kemarin' => 'Kemarin',
        '7hari' => '7 Hari Terakhir',
        'bulan_ini' => 'Bulan Ini',
        'semua' => 'Semua Waktu',
        default => 'Hari Ini',
    };

    // Date range berdasarkan period
    $dateFrom = match($period) {
        'kemarin' => now()->subDay()->startOfDay(),
        '7hari' => now()->subDays(6)->startOfDay(),
        'bulan_ini' => now()->startOfMonth(),
        'semua' => null,
        default => now()->startOfDay(),
    };
    $dateTo = match($period) {
        'kemarin' => now()->subDay()->endOfDay(),
        default => now()->endOfDay(),
    };

    // Stats query builder
    $statsQuery = DB::table('pesanan')->where('status','!=','batal');
    $countQuery = DB::table('pesanan');
    $custQuery = DB::table('pesanan');
    if ($dateFrom) {
        $statsQuery->where('created_at', '>=', $dateFrom)->where('created_at', '<=', $dateTo);
        $countQuery->where('created_at', '>=', $dateFrom)->where('created_at', '<=', $dateTo);
        $custQuery->where('created_at', '>=', $dateFrom)->where('created_at', '<=', $dateTo);
    }

    $totalPenjualan = (clone $statsQuery)->sum('total_harga');
    $jumlahPesanan = (clone $countQuery)->count();
    $produkAktif = DB::table('menu')->where('is_aktif', 1)->count();
    $pelangganHariIni = (clone $custQuery)->distinct('nama_pemesan')->count('nama_pemesan');

    // Chart 7 hari (selalu 7 hari terakhir)
    $chartData = [];
    $dayNames = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
    for ($i = 6; $i >= 0; $i--) {
        $d = now()->subDays($i);
        $rev = DB::table('pesanan')->where('status','!=','batal')->whereDate('created_at', $d->toDateString())->sum('total_harga');
        $ord = DB::table('pesanan')->whereDate('created_at', $d->toDateString())->count();
        $chartData[] = ['day' => $dayNames[$d->dayOfWeek], 'revenue' => $rev, 'orders' => $ord];
    }
    $maxRev = max(array_column($chartData, 'revenue')) ?: 1;

    $pesananTerbaru = Pesanan::with('detail.menu')->orderBy('created_at','desc')->limit(5)->get();

    $menuTerlaris = DB::table('detail_pesanan')
        ->join('pesanan', 'detail_pesanan.id_pesanan', '=', 'pesanan.id')
        ->join('menu', 'detail_pesanan.id_menu', '=', 'menu.id')
        ->select('menu.id','menu.nama','menu.gambar','menu.harga', DB::raw('SUM(detail_pesanan.qty) as sold'), DB::raw('SUM(detail_pesanan.subtotal) as revenue'))
        ->where('pesanan.status','!=','batal')
        ->where('pesanan.created_at','>=', now()->startOfWeek())
        ->groupBy('menu.id')
        ->orderByDesc('sold')
        ->limit(3)
        ->get();

    return view('admin.dashboard', compact('totalPenjualan','jumlahPesanan','produkAktif','pelangganHariIni','chartData','maxRev','pesananTerbaru','menuTerlaris','period','periodLabel'));
});

Route::get('/admin/menu', function () {
    if (!session('is_admin')) return redirect('/login');
    return view('admin.menu-manage');
});

Route::get('/admin/menu-test', function () {
    if (!session('is_admin')) return redirect('/login');
    $menu = Menu::with('kategori', 'hargaCatering')->get();
    $kategori = \App\Models\Kategori::all();
    return view('admin.testo', compact('menu', 'kategori'));
});

/**
 * =====================================================================
 * ROUTES UNTUK CRUD MENU (SIMPAN, UPDATE, HAPUS) DI TESTO
 * =====================================================================
 */


// =====================================================================
// 1. SIMPAN MENU BARU (dengan upload gambar)
// =====================================================================
Route::post('/admin/menu-test/store', function (Request $request) {
    $request->validate([
        'nama' => 'required',
        'harga' => 'required',
        'id_kategori' => 'required',
        'gambar' => 'nullable|image|max:2048', // Maks 2MB, harus file gambar
    ]);

    $data = [
        'nama' => $request->nama,
        'harga' => $request->harga,
        'id_kategori' => $request->id_kategori,
        'poin' => $request->poin ?: null,
        'preorder_hari' => $request->preorder_hari ?: 0,
        'deskripsi' => $request->deskripsi ?: null,
        'slug' => strtolower(str_replace(' ', '-', $request->nama)),
    ];

    // Jika admin meng-upload gambar, simpan file-nya ke folder storage
    if ($request->hasFile('gambar')) {
        // File akan tersimpan di: storage/app/public/menu-images/namafile.jpg
        // Dan bisa diakses publik lewat: /storage/menu-images/namafile.jpg
        $data['gambar'] = $request->file('gambar')->store('menu-images', 'public');
    }

    DB::table('menu')->insert($data);

    return response()->json(['success' => true, 'message' => 'Menu berhasil ditambah!']);
});

// =====================================================================
// 2. UPDATE MENU (dengan upload gambar baru — opsional)
// =====================================================================
Route::post('/admin/menu-test/update/{id}', function (Request $request, $id) {
    $request->validate([
        'gambar' => 'nullable|image|max:2048',
    ]);

    $data = [
        'nama' => $request->nama,
        'harga' => $request->harga,
        'id_kategori' => $request->id_kategori,
        'poin' => $request->poin ?: null,
        'preorder_hari' => $request->preorder_hari ?: 0,
        'deskripsi' => $request->deskripsi ?: null,
    ];

    // Jika upload gambar baru, hapus gambar lama lalu simpan yang baru
    if ($request->hasFile('gambar')) {
        $menuLama = DB::table('menu')->where('id', $id)->first();
        if ($menuLama && $menuLama->gambar) {
            Storage::disk('public')->delete($menuLama->gambar); // Hapus file lama
        }
        $data['gambar'] = $request->file('gambar')->store('menu-images', 'public');
    }

    DB::table('menu')->where('id', $id)->update($data);

    return response()->json(['success' => true, 'message' => 'Menu berhasil diupdate!']);
});

// =====================================================================
// 3. HAPUS MENU (sekaligus hapus file gambarnya dari server)
// =====================================================================
Route::post('/admin/menu-test/delete/{id}', function ($id) {
    $menu = DB::table('menu')->where('id', $id)->first();
    if ($menu && $menu->gambar) {
        Storage::disk('public')->delete($menu->gambar); // Hapus file gambar
    }
    DB::table('menu')->where('id', $id)->delete();

    return response()->json(['success' => true, 'message' => 'Menu berhasil dihapus!']);
});

// =====================================================================
// 4. TOGGLE SPESIAL MINGGU INI ⭐ (flip 0 <-> 1)
// =====================================================================
Route::post('/admin/menu-test/toggle-spesial/{id}', function ($id) {
    $menu = DB::table('menu')->where('id', $id)->first();
    if (! $menu) {
        return response()->json(['success' => false]);
    }

    $newVal = $menu->is_spesial ? 0 : 1;
    DB::table('menu')->where('id', $id)->update(['is_spesial' => $newVal]);

    return response()->json([
        'success' => true,
        'is_spesial' => $newVal,
        'message' => $newVal ? '⭐ Menu ditandai Spesial!' : 'Menu dicopot dari Spesial.',
    ]);
});

// =====================================================================
// 5. TOGGLE AKTIF/NONAKTIF MENU
// =====================================================================
Route::post('/admin/menu-test/toggle-aktif/{id}', function ($id) {
    $menu = DB::table('menu')->where('id', $id)->first();
    if (! $menu) {
        return response()->json(['success' => false]);
    }
    $newVal = $menu->is_aktif ? 0 : 1;
    DB::table('menu')->where('id', $id)->update(['is_aktif' => $newVal]);

    return response()->json([
        'success' => true, 'is_aktif' => $newVal,
        'message' => $newVal ? '✅ Menu diaktifkan!' : '⛔ Menu dinonaktifkan.',
    ]);
});

// =====================================================================
// 6. HAPUS GAMBAR SAJA (tanpa hapus menu)
// =====================================================================
Route::post('/admin/menu-test/delete-gambar/{id}', function ($id) {
    $menu = DB::table('menu')->where('id', $id)->first();
    if ($menu && $menu->gambar) {
        Storage::disk('public')->delete($menu->gambar);
        DB::table('menu')->where('id', $id)->update(['gambar' => null]);
    }

    return response()->json(['success' => true, 'message' => 'Gambar dihapus!']);
});

// =====================================================================
// 7. TOGGLE CATERING TERSEDIA
// =====================================================================
Route::post('/admin/menu-test/toggle-catering/{id}', function ($id) {
    $menu = DB::table('menu')->where('id', $id)->first();
    if (! $menu) {
        return response()->json(['success' => false]);
    }
    $newVal = $menu->catering_tersedia ? 0 : 1;
    DB::table('menu')->where('id', $id)->update(['catering_tersedia' => $newVal]);

    return response()->json(['success' => true, 'catering_tersedia' => $newVal]);
});

// =====================================================================
// 8. HARGA CATERING (GET + SAVE)
// =====================================================================
Route::get('/admin/menu-test/catering-prices/{id}', function ($id) {
    $tiers = DB::table('harga_catering')->where('id_menu', $id)->orderBy('min_porsi')->get();
    return response()->json($tiers);
});

Route::post('/admin/menu-test/save-catering-prices/{id}', function (Request $request, $id) {
    // Hapus tier lama, replace dengan yang baru
    DB::table('harga_catering')->where('id_menu', $id)->delete();
    $tiers = $request->input('tiers', []);
    foreach ($tiers as $tier) {
        DB::table('harga_catering')->insert([
            'id_menu' => $id,
            'min_porsi' => $tier['min_porsi'],
            'max_porsi' => $tier['max_porsi'] ?: null,
            'harga_per_porsi' => $tier['harga_per_porsi'],
        ]);
    }

    return response()->json(['success' => true, 'message' => 'Harga catering tersimpan!']);
});

// =====================================================================
// SET PROMO HARGA
// =====================================================================
Route::post('/admin/menu-test/set-promo/{id}', function (Request $request, $id) {
    $hargaPromo = $request->harga_promo;
    DB::table('menu')->where('id', $id)->update(['harga_promo' => $hargaPromo]);

    return response()->json(['success' => true, 'harga_promo' => $hargaPromo]);
});

// =====================================================================
// SAVE POIN HARIAN SETTING
// =====================================================================
Route::post('/admin/settings/poin-harian', function (Request $request) {
    DB::table('settings')->updateOrInsert(
        ['key' => 'poin_harian'],
        ['value' => $request->value]
    );

    return response()->json(['success' => true]);
});

/**
 * =====================================================================
 * ROUTES ADMIN PESANAN
 * =====================================================================
 */
Route::get('/admin/pesanan', function (Request $request) {
    if (!session('is_admin')) return redirect('/login');
    $filter = $request->filter ?? 'semua';
    $query = Pesanan::with('detail.menu')->orderBy('created_at', 'desc');
    if ($filter === 'harian') $query->whereDate('created_at', now()->toDateString());
    elseif ($filter === 'mingguan') $query->where('created_at', '>=', now()->startOfWeek());
    elseif ($filter === 'bulanan') $query->where('created_at', '>=', now()->startOfMonth());
    $pesanan = $query->get();
    $menuList = Menu::where('is_aktif', 1)->get();
    return view('admin.pesanan', compact('pesanan', 'filter', 'menuList'));
});

Route::post('/admin/pesanan/update-status/{id}', function (Request $request, $id) {
    DB::table('pesanan')->where('id', $id)->update(['status' => $request->status]);
    return response()->json(['success' => true, 'message' => 'Status diperbarui!']);
});

Route::post('/admin/pesanan/delete/{id}', function ($id) {
    if (!session('is_admin')) return response()->json(['success' => false], 403);
    DB::table('detail_pesanan')->where('id_pesanan', $id)->delete();
    DB::table('pesanan')->where('id', $id)->delete();
    return response()->json(['success' => true, 'message' => 'Pesanan dihapus!']);
});

Route::post('/admin/pesanan/edit/{id}', function (Request $request, $id) {
    if (!session('is_admin')) return response()->json(['success' => false], 403);
    $items = $request->items;
    if (!$items || count($items) === 0) return response()->json(['success' => false, 'message' => 'Minimal 1 item.']);

    // Hapus detail lama
    DB::table('detail_pesanan')->where('id_pesanan', $id)->delete();

    $totalHarga = 0;
    foreach ($items as $item) {
        $menu = DB::table('menu')->where('id', $item['id_menu'])->first();
        if (!$menu) continue;
        $harga = isset($item['harga_override']) && $item['harga_override'] > 0
            ? $item['harga_override']
            : ($menu->harga_promo ?: $menu->harga);
        $subtotal = $harga * $item['qty'];
        $totalHarga += $subtotal;
        DB::table('detail_pesanan')->insert([
            'id_pesanan' => $id,
            'id_menu' => $item['id_menu'],
            'qty' => $item['qty'],
            'harga_satuan' => $harga,
            'subtotal' => $subtotal,
        ]);
    }

    DB::table('pesanan')->where('id', $id)->update([
        'total_harga' => $totalHarga,
        'catatan' => $request->catatan,
    ]);

    return response()->json(['success' => true, 'message' => 'Pesanan diperbarui!', 'total' => $totalHarga]);
});

// API: Ambil sisa poin hari ini
Route::get('/api/sisa-poin', function (Request $request) {
    $tanggal = $request->tanggal ?: now()->toDateString();
    $poinHarian = DB::table('settings')->where('key', 'poin_harian')->value('value') ?? 100;
    $poinTerpakai = DB::table('detail_pesanan')
        ->join('pesanan', 'detail_pesanan.id_pesanan', '=', 'pesanan.id')
        ->join('menu', 'detail_pesanan.id_menu', '=', 'menu.id')
        ->where('pesanan.tanggal_ambil', $tanggal)
        ->whereIn('pesanan.status', ['pending', 'diproses', 'selesai'])
        ->sum(DB::raw('COALESCE(menu.poin, 0) * detail_pesanan.qty'));

    return response()->json([
        'poin_harian' => (int) $poinHarian,
        'poin_terpakai' => (int) $poinTerpakai,
        'sisa_poin' => max(0, $poinHarian - $poinTerpakai),
    ]);
});

// API: Ambil harga catering untuk sebuah menu
Route::get('/api/harga-catering/{id}', function ($id) {
    $tiers = DB::table('harga_catering')->where('id_menu', $id)->orderBy('min_porsi')->get();

    return response()->json($tiers);
});

/**
 * =====================================================================
 * ROUTES UNTUK CRUD KATEGORI
 * =====================================================================
 */
Route::post('/admin/kategori-test/store', function (Request $request) {
    $request->validate(['nama' => 'required|max:20']);
    $id = DB::table('kategori')->insertGetId(['nama' => $request->nama]);

    return response()->json(['success' => true, 'id' => $id, 'nama' => $request->nama]);
});

Route::post('/admin/kategori-test/delete/{id}', function ($id) {
    $menuTerpakai = DB::table('menu')->where('id_kategori', $id)->count();
    if ($menuTerpakai > 0) {
        return response()->json(['success' => false, 'message' => 'Ada '.$menuTerpakai.' menu yang masih memakai kategori ini.']);
    }
    DB::table('kategori')->where('id', $id)->delete();

    return response()->json(['success' => true, 'message' => 'Kategori dihapus!']);
});

/**
 * =====================================================================
 * ROUTES UNTUK CHECKOUT (CUSTOMER) — VALIDASI POIN
 * =====================================================================
 */
Route::post('/checkout', function (Request $request) {
    $request->validate([
        'nama_pemesan' => 'required|max:100',
        'whatsapp' => 'required|max:20',
        'tanggal_ambil' => 'required|date|after_or_equal:today',
        'waktu_ambil' => 'required',
        'tipe' => 'required|in:biasa,catering',
        'items' => 'required|array|min:1',
    ]);

    $items = $request->items;
    $today = $request->tanggal_ambil;
    $totalHarga = 0;
    $totalPoinPesanan = 0;
    $detailToInsert = [];

    // Hitung total poin yang sudah terpakai hari itu
    $poinHarian = DB::table('settings')->where('key', 'poin_harian')->value('value') ?? 100;
    $poinTerpakai = DB::table('detail_pesanan')
        ->join('pesanan', 'detail_pesanan.id_pesanan', '=', 'pesanan.id')
        ->join('menu', 'detail_pesanan.id_menu', '=', 'menu.id')
        ->where('pesanan.tanggal_ambil', $today)
        ->whereIn('pesanan.status', ['pending', 'diproses', 'selesai'])
        ->sum(DB::raw('COALESCE(menu.poin, 0) * detail_pesanan.qty'));
    $sisaPoin = max(0, $poinHarian - $poinTerpakai);

    foreach ($items as $item) {
        $menu = DB::table('menu')->where('id', $item['id_menu'])->first();
        if (! $menu || ! $menu->is_aktif) {
            return response()->json(['success' => false, 'message' => 'Menu "'.($menu->nama ?? 'Unknown').'" tidak tersedia.']);
        }

        // Pre-order date validation
        if (($menu->preorder_hari ?? 0) > 0) {
            $minDate = now()->addDays($menu->preorder_hari)->toDateString();
            if ($request->tanggal_ambil < $minDate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Menu "'.$menu->nama.'" membutuhkan pre-order minimal '.$menu->preorder_hari.' hari sebelumnya. Pilih tanggal '.$minDate.' atau setelahnya.',
                ]);
            }
        }

        // Hitung poin pesanan ini
        $poinMenu = ($menu->poin ?? 0) * $item['qty'];
        $totalPoinPesanan += $poinMenu;

        // Hitung harga (gunakan harga promo jika ada)
        $hargaSatuan = $menu->harga_promo ?: $menu->harga;
        if ($request->tipe === 'catering') {
            $tier = DB::table('harga_catering')
                ->where('id_menu', $menu->id)
                ->where('min_porsi', '<=', $item['qty'])
                ->where(function ($q) use ($item) {
                    $q->whereNull('max_porsi')->orWhere('max_porsi', '>=', $item['qty']);
                })
                ->orderBy('min_porsi', 'desc')
                ->first();
            if ($tier) {
                $hargaSatuan = $tier->harga_per_porsi;
            }
        }

        $subtotal = $hargaSatuan * $item['qty'];
        $totalHarga += $subtotal;

        $detailToInsert[] = [
            'id_menu' => $menu->id,
            'qty' => $item['qty'],
            'harga_satuan' => $hargaSatuan,
            'subtotal' => $subtotal,
        ];
    }

    // Validasi poin harian
    if ($totalPoinPesanan > $sisaPoin) {
        return response()->json([
            'success' => false,
            'message' => 'Maaf, kapasitas/kuota dapur untuk hari ini sudah penuh.',
        ]);
    }

    // Simpan pesanan
    $pesananId = DB::table('pesanan')->insertGetId([
        'nama_pemesan' => $request->nama_pemesan,
        'whatsapp' => $request->whatsapp,
        'tanggal_ambil' => $request->tanggal_ambil,
        'waktu_ambil' => $request->waktu_ambil,
        'tipe' => $request->tipe,
        'metode_bayar' => $request->metode_bayar ?? 'cod',
        'metode_kirim' => $request->metode_kirim ?? 'pickup',
        'alamat_kirim' => $request->alamat_kirim,
        'total_harga' => $totalHarga,
        'status' => 'pending',
        'catatan' => $request->catatan,
        'created_at' => now(),
    ]);

    foreach ($detailToInsert as &$d) {
        $d['id_pesanan'] = $pesananId;
        DB::table('detail_pesanan')->insert($d);
    }

    return response()->json([
        'success' => true,
        'message' => 'Pesanan berhasil!',
        'pesanan_id' => $pesananId,
        'total' => $totalHarga,
    ]);
});

/**
 * =====================================================================
 * EXPORT PESANAN (CSV / TXT)
 * =====================================================================
 */
Route::get('/admin/pesanan/export', function (Request $request) {
    if (!session('is_admin')) return redirect('/login');
    $filter = $request->filter ?? 'semua';
    $format = $request->format ?? 'csv';

    $query = Pesanan::with('detail.menu')->orderBy('created_at', 'desc');
    if ($filter === 'harian') $query->whereDate('created_at', now()->toDateString());
    elseif ($filter === 'mingguan') $query->where('created_at', '>=', now()->startOfWeek());
    elseif ($filter === 'bulanan') $query->where('created_at', '>=', now()->startOfMonth());
    $pesanan = $query->get();

    $sep = $format === 'csv' ? ',' : "\t";
    $lines = [];
    $lines[] = implode($sep, ['ID','Nama','WA','Tanggal','Waktu','Tipe','Bayar','Kirim','Status','Total','Item','Catatan']);
    foreach ($pesanan as $p) {
        $items = $p->detail->map(function($d) {
            return ($d->menu ? $d->menu->nama : 'Dihapus') . ' x' . $d->qty;
        })->implode('; ');
        $lines[] = implode($sep, [
            $p->id, '"'.$p->nama_pemesan.'"', $p->whatsapp,
            $p->tanggal_ambil, $p->waktu_ambil, $p->tipe,
            $p->metode_bayar ?? 'cod', $p->metode_kirim ?? 'pickup',
            $p->status, $p->total_harga, '"'.$items.'"', '"'.($p->catatan ?? '').'"',
        ]);
    }

    $content = implode("\n", $lines);
    $ext = $format === 'csv' ? 'csv' : 'txt';
    $filename = 'pesanan_' . $filter . '_' . now()->format('Ymd_His') . '.' . $ext;

    return response($content, 200, [
        'Content-Type' => $format === 'csv' ? 'text/csv' : 'text/plain',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    ]);
});

/**
 * =====================================================================
 * AUTH: LOGIN / LOGOUT
 * =====================================================================
 */
Route::get('/login', function () {
    if (session('is_admin')) return redirect('/admin');
    return view('auth.login');
});

Route::post('/login', function (Request $request) {
    $request->validate(['email' => 'required|email', 'password' => 'required']);
    $user = DB::table('users')->where('email', $request->email)->where('role', 'admin')->first();
    if ($user && password_verify($request->password, $user->password)) {
        session(['is_admin' => true, 'admin_name' => $user->nama, 'admin_id' => $user->id]);
        return redirect('/admin');
    }
    return back()->with('error', 'Email atau password salah.');
});

Route::post('/logout', function () {
    session()->forget(['is_admin', 'admin_name', 'admin_id']);
    return redirect('/login');
});
