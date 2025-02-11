<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudioSalesType;

class StudioSaleTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $saleTypes = StudioSaleType::all();
        return response()->json($saleTypes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'SalesType' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $saleType = StudioSaleType::create($request->all());
        return response()->json($saleType, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $saleType = StudioSaleType::findOrFail($id);
        return response()->json($saleType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'SalesType' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $saleType = StudioSaleType::findOrFail($id);
        $saleType->update($request->all());
        return response()->json($saleType);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        StudioSaleType::destroy($id);
        return response()->json(['message' => 'Sale type deleted successfully']);
    }
}
