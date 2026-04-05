<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menu';

    protected $guarded = [];

    // Relasi ke Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    // Relasi ke harga catering (banyak tier harga per menu)
    public function hargaCatering()
    {
        return $this->hasMany(HargaCatering::class, 'id_menu')->orderBy('min_porsi');
    }
}
