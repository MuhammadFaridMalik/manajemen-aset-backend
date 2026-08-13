<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ["nama_ruangan", "lokasi_gedung"];

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }
}
