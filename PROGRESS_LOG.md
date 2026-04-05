# 📋 Progress Log — Monika's Kitchen

> Dokumentasi pribadi untuk mencatat apa saja yang sudah dikerjakan, pelajaran penting, dan rencana ke depan.  
> Terakhir diupdate: **30 Maret 2026**

---

## 🏗️ Arsitektur Proyek

| Komponen       | Teknologi                  |
|----------------|----------------------------|
| Framework      | Laravel 10.x               |
| Database       | MySQL (`monikas_kitchen`)  |
| Server Lokal   | Laragon                    |
| CSS            | Tailwind CSS (via Vite)    |
| Font           | Plus Jakarta Sans (Google) |

### Struktur Database Saat Ini

| Tabel      | Kolom Utama                                        |
|------------|----------------------------------------------------|
| `menu`     | `id`, `id_kategori`, `nama`, `harga`, `slug`, `deskripsi`, `kuota` |
| `kategori` | `id`, `nama`                                       |
| `pesanan`  | *(belum diisi)*                                    |
| `users`    | *(belum diisi)*                                    |

---

## ✅ Fitur yang Sudah Selesai

### 1. Halaman Customer
- [x] Halaman Menu (`/menu`) — menampilkan daftar menu dengan tombol (+) tambah ke keranjang
- [x] Halaman Keranjang (`/cart`) — localStorage based, ada form tanggal & jam pengambilan
- [x] Tombol Checkout disabled sampai tanggal & jam diisi
- [x] Badge jumlah item di navbar

### 2. Halaman Admin — Kelola Menu (`/admin/menu`)
- [x] Tabel menu dengan kolom: Nama, Kategori, Harga, Promo, Catering, Kuota, Aksi
- [x] Input "Batas Porsi" (kuota harian) di form modal
- [x] Badge warna kondisional untuk sisa kuota (Hijau ≥5, Merah <5, Merah Tua = Habis)
- [x] **Catatan:** Halaman ini masih menggunakan data HARDCODE (belum terhubung ke database)

### 3. Halaman Testo / Sandbox (`/admin/menu-test`) ⭐ BARU
- [x] Tabel menu yang **100% mengambil data dari database**
- [x] Filter kategori menggunakan `<select>` dropdown (data dari tabel `kategori`)
- [x] Pencarian menu berdasarkan nama
- [x] **CRUD Fungsional:**
  - ✅ **Tambah** menu baru → langsung masuk ke database
  - ✅ **Edit** menu → update data di database
  - ✅ **Hapus** menu → delete dari database
  - ✅ **Tambah Kategori** baru
  - ✅ **Hapus Kategori** (dengan proteksi: tidak bisa dihapus jika masih dipakai menu)

---

## 📚 Pelajaran Penting (Bug & Solusi)

### 1. Nama Kolom Database Harus Konsisten
**Masalah:** Di kode JS ditulis `m.kapasitas`, tapi kolom di database sebenarnya bernama `kuota`.  
**Solusi:** Selalu cek nama kolom di phpMyAdmin sebelum menulis kode. Pastikan nama di:
- Route (web.php) → `'kuota' => $request->kuota`
- JavaScript (blade) → `m.kuota`
- HTML (input ID) → `id="form-kuota"`

Semuanya harus **sama persis** dengan nama kolom di database.

### 2. URL di JavaScript Harus Pakai `url()` Laravel
**Masalah:** Fetch API ditulis `/admin/menu-test/store`, tapi karena aplikasi berjalan di subdirectory `/monikas-menu/public/`, browser mengirim ke alamat yang salah → **404 Error**.  
**Solusi:** Selalu gunakan helper Laravel untuk membuat URL:
```javascript
// ❌ SALAH — hardcode path
fetch('/admin/menu-test/store', { ... })

// ✅ BENAR — pakai BASE URL dari Laravel
const BASE = '{{ url("/") }}';
fetch(`${BASE}/admin/menu-test/store`, { ... })
```

### 3. IIFE (Immediately Invoked Function Expression)
**Masalah:** Ada `})();` di akhir script tapi tidak ada `(function() {` di awal → seluruh JavaScript crash.  
**Penjelasan:** IIFE adalah pola JavaScript untuk membungkus kode agar variabelnya tidak "bocor" ke luar. Formatnya:
```javascript
(function() {
    // semua kode di sini
})();
```
Jika lupa salah satu bagiannya, semua kode akan error.

### 4. Eloquent Relationship (belongsTo)
**Penjelasan:** Daripada mengirim 2 variabel terpisah (`$menu` dan `$kategori`), kita bisa pakai "sihir" Eloquent:
```php
// Di Model Menu.php — definisikan relasi
public function kategori() {
    return $this->belongsTo(Kategori::class, 'id_kategori');
}

// Di Route — ambil menu BESERTA kategorinya sekaligus
$menu = Menu::with('kategori')->get();
```
Lalu di JavaScript, nama kategori otomatis tersedia di `m.kategori.nama`.

### 5. CSRF Token untuk POST Request
**Penjelasan:** Laravel membutuhkan token keamanan untuk setiap request POST. Caranya:
1. Taruh meta tag di `<head>`: `<meta name="csrf-token" content="{{ csrf_token() }}">`
2. Ambil di JS: `const csrfToken = document.querySelector('meta[name="csrf-token"]').content;`
3. Kirim di header: `'X-CSRF-TOKEN': csrfToken`

---

## 📁 File-File Penting

| File | Fungsi |
|------|--------|
| `routes/web.php` | Semua route + CRUD endpoint untuk menu & kategori |
| `resources/views/admin/testo.blade.php` | Halaman sandbox CRUD (terhubung DB) |
| `resources/views/admin/menu-manage.blade.php` | Halaman admin menu utama (masih hardcode) |
| `app/Models/Menu.php` | Model Eloquent untuk tabel `menu` |
| `app/Models/Kategori.php` | Model Eloquent untuk tabel `kategori` |
| `.env` | Konfigurasi database (`DB_DATABASE=monikas_kitchen`) |

---

## 🗺️ Rencana Ke Depan

### Prioritas Tinggi
- [ ] Pindahkan logika CRUD dari Testo ke halaman `menu-manage.blade.php` utama
- [ ] Buat tabel `customers` dan `orders` di database
- [ ] Implementasi sistem checkout yang menyimpan pesanan ke database

### Prioritas Menengah
- [ ] Sistem autentikasi berbasis nomor WhatsApp (passwordless)
- [ ] Halaman pesanan yang hanya bisa dilihat oleh pemilik pesanan
- [ ] Dynamic quota: stok berkurang otomatis saat ada pesanan, pulih saat cancel

### Prioritas Rendah
- [ ] Halaman detail menu (`/menu/{slug}`) dengan galeri foto & review
- [ ] Sistem review/rating untuk member yang sudah daftar akun
- [ ] Integrasi chatbot WhatsApp

---

> 💡 **Tips:** File ini bisa di-update kapan saja. Gunakan sebagai catatan pribadi agar tidak lupa apa yang sudah dikerjakan dan pelajaran apa yang sudah didapat!
