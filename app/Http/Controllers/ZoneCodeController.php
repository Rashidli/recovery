<?php

namespace App\Http\Controllers;

use App\Models\ZoneCode;
use Illuminate\Http\Request;

class ZoneCodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:list-zone_codes|create-zone_codes|edit-zone_codes|delete-zone_codes', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-zone_codes', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-zone_codes', ['only' => ['edit']]);
        $this->middleware('permission:delete-zone_codes', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $query = ZoneCode::query();

        if($request->zone_code){
            $query->where('zone_code', 'like', '%' . $request->zone_code . '%');
        }
        $isFatime = auth()->user()->id == 4;
        $zone_codes = $query->orderByDesc('id')->paginate(50);
        return view('admin.zone_codes.index', compact('zone_codes','isFatime'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.zone_codes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'zone_code' => 'required|string|max:255',
            'price' => 'nullable|numeric',
            'distance' => 'nullable|numeric|max:255',
        ]);


        ZoneCode::query()->create([
            'zone_code' => $request->zone_code,
            'price' => $request->price,
            'distance' => $request->distance,
        ]);

        return redirect()->route('zone_codes.index')->with('success', 'ZoneCode created successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(ZoneCode $zone_code)
    {
        return view('admin.zone_codes.show', compact('zone_code'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ZoneCode $zone_code)
    {
        return view('admin.zone_codes.edit', compact('zone_code'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ZoneCode $zone_code)
    {

        $request->validate([
            'zone_code' => 'required|string|max:255',
            'price' => 'nullable|numeric',
            'distance' => 'nullable|numeric|max:255',
        ]);

        $zone_code->update([
            'zone_code' => $request->zone_code,
            'price' => $request->price,
            'distance' => $request->distance,
        ]);

        return redirect()->route('zone_codes.index')->with('success', 'ZoneCode updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ZoneCode $zone_code)
    {

        $zone_code->delete();
        return redirect()->route('zone_codes.index')->with('success', 'ZoneCode deleted successfully.');

    }
}
