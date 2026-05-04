<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'kode',
        'nama',
        'harga',
        'kategori',
        'foto',
        'stok',
    ];

    public function productions()
    {
        return $this->hasMany(Production::class);
    }
}
