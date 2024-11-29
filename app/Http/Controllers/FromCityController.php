<?php

namespace App\Http\Controllers;

use App\Models\FromArea;
use App\Models\FromCity;
use Illuminate\Http\Request;

class FromCityController extends Controller
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
    public function show(FromCity $fromCity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FromCity $fromCity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FromCity $fromCity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FromCity $fromCity)
    {
        //
    }

    public function getAreas($cityId)
    {
        $areas = FromArea::where('city_id', $cityId)->get(['id', 'name']);
        return response()->json($areas);
    }
}
