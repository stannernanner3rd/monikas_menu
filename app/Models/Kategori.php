<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    // Beri tahu Laravel nama tabelnya yang benar
    protected $table = 'kategori';

    protected $guarded = [];

    // Relasi: satu kategori punya banyak menu
    public function menu()
    {
        return $this->hasMany(Menu::class, 'id_kategori');
    }
}
