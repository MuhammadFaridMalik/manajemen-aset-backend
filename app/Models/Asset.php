<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        "kode_aset",
        "nama_aset",
        "category_id",
        "location_id",
        "kondisi",
        "jumlah",
        "tanggal_perolehan",
        "keterangan",
        "created_by"
    ];

    protected $casts = [
        'tanggal_perolehan' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateKodeAset(): string
    {
        $lastAsset = self::orderBy('id', 'desc')->first();
        $lastNumber = $lastAsset ? (int) substr($lastAsset->kode_aset, 4) : 0;
        $nextNumber = $lastNumber + 1;

        return 'AST-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
