<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode_aset' => 'required|string|max:50|unique:assets,kode_aset',
            'nama_aset' => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'required|exists:locations,id',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'jumlah' => 'required|integer|min:1',
            'tanggal_perolehan' => 'nullable|date',
            'keterangan' => 'nullable|string',
        ];
    }
}
