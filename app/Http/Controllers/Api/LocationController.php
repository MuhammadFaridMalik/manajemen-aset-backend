<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLocationRequest;
use App\Http\Requests\UpdateLocationRequest;
use App\Http\Resources\LocationResource;
use App\Models\Location;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::withCount('assets')->latest()->get();

        return LocationResource::collection($locations);
    }

    public function store(StoreLocationRequest $request)
    {
        $location = Location::create($request->validated());

        return new LocationResource($location);
    }

    public function show(Location $location)
    {
        return new LocationResource($location->loadCount('assets'));
    }

    public function update(UpdateLocationRequest $request, Location $location)
    {
        $location->update($request->validated());

        return new LocationResource($location);
    }

    public function destroy(Location $location)
    {
        if ($location->assets()->exists()) {
            return response()->json([
                'message' => 'Ruangan tidak bisa dihapus karena masih dipakai oleh aset.',
            ], 422);
        }

        $location->delete();

        return response()->json(['message' => 'Ruangan berhasil dihapus.']);
    }
}
