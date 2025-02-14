<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudioOrderType;

class StudioOrderTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orderTypes = StudioOrderType::all();
        return response()->json($orderTypes);
    }

    // public function newOrder()
    // {
    //     // Fetch all order types from the database
    //     $orderTypes = StudioOrderType::all();

    //     // Pass the data to the view
    //     return view('pages.todo.neworder', compact('orderTypes'));
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ordertype' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $orderType = StudioOrderType::create($request->all());
        return response()->json($orderType, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $orderType = StudioOrderType::findOrFail($id);
        return response()->json($orderType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'OrderType' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $orderType = StudioOrderType::findOrFail($id);
        $orderType->update($request->all());
        return response()->json($orderType);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        StudioOrderType::destroy($id);
        return response()->json(['message' => 'order type deleted successfully']);
    }
}
