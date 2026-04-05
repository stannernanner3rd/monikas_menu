<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $guarded = [];

    // Relasi: satu pesanan punya banyak detail item
    public function detail()
    {
        return $this->hasMany(DetailPesanan::class, 'id_pesanan');
    }
}
