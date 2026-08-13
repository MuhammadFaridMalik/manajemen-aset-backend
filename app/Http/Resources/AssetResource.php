<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'kode_aset' => $this->kode_aset,
            'nama_aset' => $this->nama_aset,
            'kondisi' => $this->kondisi,
            'jumlah' => $this->jumlah,
            'tanggal_perolehan' => $this->tanggal_perolehan?->format('Y-m-d'),
            'keterangan' => $this->keterangan,
            'category' => [
                'id' => $this->category->id,
                'nama_kategori' => $this->category->nama_kategori,
            ],
            'location' => [
                'id' => $this->location->id,
                'nama_ruangan' => $this->location->nama_ruangan,
            ],
            'created_by' => $this->creator->name,
            'created_at' => $this->created_at,
        ];
    }
}
