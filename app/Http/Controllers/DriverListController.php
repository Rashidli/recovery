<?php

namespace App\Http\Controllers;

use App\Models\DriverList;
use Illuminate\Http\Request;

class DriverListController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:list-driver_lists|create-driver_lists|edit-driver_lists|delete-driver_lists', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-driver_lists', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-driver_lists', ['only' => ['edit']]);
        $this->middleware('permission:delete-driver_lists', ['only' => ['destroy']]);
    }

    public function index()
    {
        $driver_lists = DriverList::query()->paginate(20);
        return view('admin.driver_lists.index', compact('driver_lists'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.driver_lists.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'plate_no' => 'required|string|max:255',
        ]);


        DriverList::query()->create([
            'title' => $request->title,
            'phone' => $request->phone,
            'plate_no' => $request->plate_no,
            'driver_type' => $request->driver_type,
        ]);

        return redirect()->route('driver_lists.index')->with('success', 'DriverList created successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(DriverList $driver_list)
    {
        return view('admin.driver_lists.show', compact('driver_list'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DriverList $driver_list)
    {
        return view('admin.driver_lists.edit', compact('driver_list'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DriverList $driver_list)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'plate_no' => 'required|string|max:255',
        ]);

        $driver_list->update([
            'title' => $request->title,
            'phone' => $request->phone,
            'plate_no' => $request->plate_no,
            'driver_type' => $request->driver_type
        ]);

        return redirect()->route('driver_lists.index')->with('success', 'DriverList updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DriverList $driver_list)
    {

        $driver_list->delete();
        return redirect()->route('driver_lists.index')->with('success', 'DriverList deleted successfully.');

    }
}
