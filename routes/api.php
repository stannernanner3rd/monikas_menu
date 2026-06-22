<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// =====================================================================
// BOTPRESS API — for Chatbot Integration
// =====================================================================
Route::get('/botpress/menus', function (Request $request) {
    $menus = \App\Models\Menu::with('kategori')
        ->where('is_aktif', 1)
        ->get()
        ->map(function ($menu) {
            $is_promo = !empty($menu->harga_promo) && $menu->harga_promo > 0;
            $is_preorder = !empty($menu->preorder_hari) && $menu->preorder_hari > 0;
            
            return [
                'id' => $menu->id,
                'nama' => $menu->nama,
                'kategori' => $menu->kategori ? $menu->kategori->nama : '-',
                'harga_asli' => (int) $menu->harga,
                'harga_saat_ini' => $is_promo ? (int) $menu->harga_promo : (int) $menu->harga,
                'is_promo' => $is_promo,
                'is_preorder' => $is_preorder,
                'preorder_hari' => $is_preorder ? (int) $menu->preorder_hari : 0,
                'gambar' => $menu->gambar ? asset('storage/' . $menu->gambar) : null,
                'deskripsi_singkat' => $menu->deskripsi ?? '',
            ];
        });

    return response()->json([
        'success' => true,
        'count' => $menus->count(),
        'data' => $menus
    ]);
});
