<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudioOrder;

class StudioOrdersController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index()
    {
        $orders = StudioOrder::all();
        return response()->json($orders);
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'studiokey' => 'required|integer',
            'ordertypekey' => 'required|integer',
            'customerkey' => 'required|integer',
            'isurgent' => 'required|boolean',
            'totalcost' => 'required|numeric',
            'paidcost' => 'required|numeric',
            'discount' => 'nullable|integer',
            'salestatus' => 'required|string',
            'deliverydate' => 'nullable|date',
            'remarks' => 'nullable|string',
        ]);

        $order = StudioOrder::create($request->all());
        return response()->json($order, 201);
    }

    /**
     * Display the specified order.
     */
    public function show($id)
    {
        $order = StudioOrder::findOrFail($id);
        return response()->json($order);
    }

    /**
     * Update the specified order in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'studiokey' => 'sometimes|integer',
            'ordertypekey' => 'sometimes|integer',
            'customerkey' => 'sometimes|integer',
            'isurgent' => 'sometimes|boolean',
            'totalcost' => 'sometimes|numeric',
            'paidcost' => 'sometimes|numeric',
            'discount' => 'sometimes|integer',
            'salestatus' => 'sometimes|string',
            'deliverydate' => 'sometimes|date',
            'remarks' => 'sometimes|string',
        ]);

        $order = StudioOrder::findOrFail($id);
        $order->update($request->all());
        return response()->json($order);
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy($id)
    {
        StudioOrder::destroy($id);
        return response()->json(['message' => 'Order deleted successfully']);
    }
}
