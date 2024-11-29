<?php

namespace App\Http\Controllers;

use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Illuminate\Http\Request;

class VehicleMakeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(VehicleMake $vehicleMake)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VehicleMake $vehicleMake)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VehicleMake $vehicleMake)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleMake $vehicleMake)
    {
        //
    }

    public function getVehicleModels($makeId)
    {
        $vehicle_models = VehicleModel::where('make_id', $makeId)->get();
        return response()->json($vehicle_models);
    }
}
