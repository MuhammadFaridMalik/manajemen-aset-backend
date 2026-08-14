<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAssetRequest;
use App\Http\Requests\UpdateAssetRequest;
use App\Http\Resources\AssetResource;
use App\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $assets = Asset::with(['category', 'location', 'creator'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_aset', 'like', "%{$search}%")
                      ->orWhere('kode_aset', 'like', "%{$search}%");
                });
            })
            ->when($request->category_id, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($request->location_id, function ($query, $locationId) {
                $query->where('location_id', $locationId);
            })
            ->latest()
            ->paginate(10);

        return AssetResource::collection($assets);
    }

    public function store(StoreAssetRequest $request)
    {
        $asset = Asset::create([
            ...$request->validated(),
            'kode_aset' => Asset::generateKodeAset(),
            'created_by' => $request->user()->id,
        ]);

        return new AssetResource($asset->load(['category', 'location', 'creator']));
    }

    public function show(Asset $asset)
    {
        return new AssetResource($asset->load(['category', 'location', 'creator']));
    }

    public function update(UpdateAssetRequest $request, Asset $asset)
    {
        $asset->update($request->validated());

        return new AssetResource($asset->load(['category', 'location', 'creator']));
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();

        return response()->json(['message' => 'Aset berhasil dihapus.']);
    }
}
