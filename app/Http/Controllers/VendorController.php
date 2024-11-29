<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:list-vendors|create-vendors|edit-vendors|delete-vendors', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-vendors', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-vendors', ['only' => ['edit']]);
        $this->middleware('permission:delete-vendors', ['only' => ['destroy']]);
    }
    public function index()
    {
        $vendors = Vendor::query()->paginate(20);
        return view('admin.vendors.index', compact('vendors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.vendors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:255'
        ]);


        Vendor::query()->create([
            'title' => $request->title,
        ]);

        return redirect()->route('vendors.index')->with('success', 'Vendor created successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Vendor $vendor)
    {
        return view('admin.vendors.show', compact('vendor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vendor $vendor)
    {
        return view('admin.vendors.edit', compact('vendor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vendor $vendor)
    {

        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $vendor->update([
            'title' => $request->title,
        ]);

        return redirect()->route('vendors.index')->with('success', 'Vendor updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vendor $vendor)
    {
        $vendor->delete();

        return redirect()->route('vendors.index')->with('success', 'Vendor deleted successfully.');
    }
}
