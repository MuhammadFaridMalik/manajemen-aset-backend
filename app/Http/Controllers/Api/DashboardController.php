<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Category;
use App\Models\Location;

class DashboardController extends Controller
{
    public function stats()
    {
        return response()->json([
            'total_aset' => (int) Asset::sum('jumlah'),
            'total_item' => (int) Asset::count(),
            'total_kategori' => (int) Category::count(),
            'total_lokasi' => (int) Location::count(),
            'kondisi' => [
                'baik' => (int) Asset::where('kondisi', 'baik')->sum('jumlah'),
                'rusak_ringan' => (int) Asset::where('kondisi', 'rusak_ringan')->sum('jumlah'),
                'rusak_berat' => (int) Asset::where('kondisi', 'rusak_berat')->sum('jumlah'),
            ],
            'aset_per_kategori' => Category::withSum('assets', 'jumlah')
                ->get()
                ->map(fn ($cat) => [
                    'nama_kategori' => $cat->nama_kategori,
                    'total' => (int) $cat->assets_sum_jumlah ?? 0,
                ]),
        ]);
    }
}
