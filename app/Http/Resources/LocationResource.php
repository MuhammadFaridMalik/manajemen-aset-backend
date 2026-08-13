<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama_ruangan' => $this->nama_ruangan,
            'lokasi_gedung' => $this->lokasi_gedung,
            'total_aset' => $this->whenCounted('assets'),
            'created_at' => $this->created_at,
        ];
    }
}
