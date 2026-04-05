<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HargaCatering extends Model
{
    protected $table = 'harga_catering';
    protected $guarded = [];
    public $timestamps = false;

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu');
    }
}
