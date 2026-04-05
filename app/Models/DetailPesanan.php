<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    protected $table = 'detail_pesanan';
    protected $guarded = [];
    public $timestamps = false;

    // Relasi: detail ini milik pesanan mana
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan');
    }

    // Relasi: detail ini untuk menu apa
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu');
    }
}
