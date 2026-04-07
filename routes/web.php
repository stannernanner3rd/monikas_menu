<?php

<<<<<<< HEAD
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
=======
>>>>>>> 74acaec9651d928d6d75935fedd887b7404207ff
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('main.welcome');
});

Route::get('/menu', function () {
<<<<<<< HEAD
    // Ambil menu AKTIF saja, beserta kategorinya
    $menuDB = Menu::with('kategori')->where('is_aktif', 1)->get();
    $kategoriDB = \App\Models\Kategori::withCount(['menu' => function($q) {
        $q->where('is_aktif', 1); // Hitung hanya menu aktif
    }])->get();

    // Hitung sisa kuota hari ini untuk setiap menu
    $today = now()->toDateString();
    foreach ($menuDB as $m) {
        if ($m->kuota) {
            $terjualHariIni = DB::table('detail_pesanan')
                ->join('pesanan', 'detail_pesanan.id_pesanan', '=', 'pesanan.id')
                ->where('detail_pesanan.id_menu', $m->id)
                ->where('pesanan.tanggal_ambil', $today)
                ->whereIn('pesanan.status', ['pending','diproses','selesai'])
                ->sum('detail_pesanan.qty');
            $m->sisa_kuota = max(0, $m->kuota - $terjualHariIni);
        } else {
            $m->sisa_kuota = null; // null = tanpa batas
        }
    }

    return view('main.menu', compact('menuDB', 'kategoriDB'));
});

Route::get('/main', function () {
    // Ambil menu spesial yang AKTIF
    $spesial = Menu::with('kategori')->where('is_spesial', 1)->where('is_aktif', 1)->get();
    $kategoriDB = \App\Models\Kategori::withCount(['menu' => fn($q) => $q->where('is_aktif', 1)])->get();

    return view('main.main', compact('spesial', 'kategoriDB'));
=======
    return view('main.menu');
});

Route::get('/main', function () {
    return view('main.main');
>>>>>>> 74acaec9651d928d6d75935fedd887b7404207ff
});

Route::get('/orders', function () {
    return view('main.orders');
});

Route::get('/about', function () {
    return view('main.about');
});

Route::get('/cart', function () {
    return view('main.cart');
});

Route::get('/settings', function () {
    return view('main.settings');
});

Route::get('/admin', function () {
    return view('admin.dashboard');
});

Route::get('/admin/menu', function () {
    return view('admin.menu-manage');
});
<<<<<<< HEAD

Route::get('/admin/menu-test', function () {
    // Ambil semua menu (termasuk nonaktif) + kategori + harga catering
    $menu = Menu::with('kategori', 'hargaCatering')->get();
    $kategori = \App\Models\Kategori::all();

    return view('admin.testo', compact('menu', 'kategori'));
});

/**
 * =====================================================================
 * ROUTES UNTUK CRUD MENU (SIMPAN, UPDATE, HAPUS) DI TESTO
 * =====================================================================
 */

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

    // Data yang akan dimasukkan ke tabel menu
    $data = [
        'nama' => $request->nama,
        'harga' => $request->harga,
        'id_kategori' => $request->id_kategori,
        'kuota' => $request->kuota ?: null,
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
        'kuota' => $request->kuota ?: null,
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
    if (!$menu) return response()->json(['success' => false]);

    $newVal = $menu->is_spesial ? 0 : 1;
    DB::table('menu')->where('id', $id)->update(['is_spesial' => $newVal]);

    return response()->json([
        'success' => true,
        'is_spesial' => $newVal,
        'message' => $newVal ? '⭐ Menu ditandai Spesial!' : 'Menu dicopot dari Spesial.'
    ]);
});

// =====================================================================
// 5. TOGGLE AKTIF/NONAKTIF MENU
// =====================================================================
Route::post('/admin/menu-test/toggle-aktif/{id}', function ($id) {
    $menu = DB::table('menu')->where('id', $id)->first();
    if (!$menu) return response()->json(['success' => false]);
    $newVal = $menu->is_aktif ? 0 : 1;
    DB::table('menu')->where('id', $id)->update(['is_aktif' => $newVal]);
    return response()->json([
        'success' => true, 'is_aktif' => $newVal,
        'message' => $newVal ? '✅ Menu diaktifkan!' : '⛔ Menu dinonaktifkan.'
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
    if (!$menu) return response()->json(['success' => false]);
    $newVal = $menu->catering_tersedia ? 0 : 1;
    DB::table('menu')->where('id', $id)->update(['catering_tersedia' => $newVal]);
    return response()->json(['success' => true, 'catering_tersedia' => $newVal]);
});

// =====================================================================
// 8. SIMPAN HARGA CATERING TIER
// =====================================================================
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

/**
 * =====================================================================
 * ROUTES UNTUK CRUD KATEGORI
 * =====================================================================
 */
Route::post('/admin/kategori-test/store', function (Request $request) {
    $request->validate(['nama' => 'required']);
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
 * ROUTES UNTUK CHECKOUT (CUSTOMER)
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
    $detailToInsert = [];

    // Proses setiap item di keranjang
    foreach ($items as $item) {
        $menu = DB::table('menu')->where('id', $item['id_menu'])->first();
        if (!$menu || !$menu->is_aktif) {
            return response()->json(['success' => false, 'message' => 'Menu "'.($menu->nama ?? 'Unknown').'" tidak tersedia.']);
        }

        // Cek kuota harian (hanya untuk pesanan biasa)
        if ($request->tipe === 'biasa' && $menu->kuota) {
            $terjualHariIni = DB::table('detail_pesanan')
                ->join('pesanan', 'detail_pesanan.id_pesanan', '=', 'pesanan.id')
                ->where('detail_pesanan.id_menu', $menu->id)
                ->where('pesanan.tanggal_ambil', $today)
                ->whereIn('pesanan.status', ['pending','diproses','selesai'])
                ->sum('detail_pesanan.qty');
            $sisaKuota = $menu->kuota - $terjualHariIni;
            if ($item['qty'] > $sisaKuota) {
                return response()->json([
                    'success' => false,
                    'message' => '"'.$menu->nama.'" sisa kuota hari itu hanya '.$sisaKuota.' porsi.'
                ]);
            }
        }

        // Hitung harga
        $hargaSatuan = $menu->harga;
        if ($request->tipe === 'catering') {
            // Cari tier harga catering yang sesuai
            $tier = DB::table('harga_catering')
                ->where('id_menu', $menu->id)
                ->where('min_porsi', '<=', $item['qty'])
                ->where(function($q) use ($item) {
                    $q->whereNull('max_porsi')->orWhere('max_porsi', '>=', $item['qty']);
                })
                ->orderBy('min_porsi', 'desc')
                ->first();
            if ($tier) $hargaSatuan = $tier->harga_per_porsi;
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

    // Simpan pesanan
    $pesananId = DB::table('pesanan')->insertGetId([
        'nama_pemesan' => $request->nama_pemesan,
        'whatsapp' => $request->whatsapp,
        'tanggal_ambil' => $request->tanggal_ambil,
        'waktu_ambil' => $request->waktu_ambil,
        'tipe' => $request->tipe,
        'total_harga' => $totalHarga,
        'status' => 'pending',
        'catatan' => $request->catatan,
        'created_at' => now(),
    ]);

    // Simpan detail
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
 * ROUTES ADMIN PESANAN
 * =====================================================================
 */
Route::get('/admin/pesanan', function () {
    $pesanan = Pesanan::with('detail.menu')->orderBy('created_at', 'desc')->get();
    return view('admin.pesanan', compact('pesanan'));
});

Route::post('/admin/pesanan/update-status/{id}', function (Request $request, $id) {
    DB::table('pesanan')->where('id', $id)->update(['status' => $request->status]);
    return response()->json(['success' => true, 'message' => 'Status diperbarui!']);
});

// API: Ambil sisa kuota menu untuk tanggal tertentu
Route::get('/api/sisa-kuota', function (Request $request) {
    $tanggal = $request->tanggal ?: now()->toDateString();
    $menus = Menu::where('is_aktif', 1)->whereNotNull('kuota')->get();
    $result = [];
    foreach ($menus as $m) {
        $terjual = DB::table('detail_pesanan')
            ->join('pesanan', 'detail_pesanan.id_pesanan', '=', 'pesanan.id')
            ->where('detail_pesanan.id_menu', $m->id)
            ->where('pesanan.tanggal_ambil', $tanggal)
            ->whereIn('pesanan.status', ['pending','diproses','selesai'])
            ->sum('detail_pesanan.qty');
        $result[$m->id] = max(0, $m->kuota - $terjual);
    }
    return response()->json($result);
});

// API: Ambil harga catering untuk sebuah menu
Route::get('/api/harga-catering/{id}', function ($id) {
    $tiers = DB::table('harga_catering')->where('id_menu', $id)->orderBy('min_porsi')->get();
    return response()->json($tiers);
});
=======
>>>>>>> 74acaec9651d928d6d75935fedd887b7404207ff
