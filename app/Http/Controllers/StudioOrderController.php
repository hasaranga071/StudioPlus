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

    // /**
    //  * Store a newly created order in storage.
    //  */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'studiokey' => 'required|integer',
    //         'ordertypekey' => 'required|integer',
    //         'customerkey' => 'required|integer',
    //         'isurgent' => 'required|boolean',
    //         'totalcost' => 'required|numeric',
    //         'paidcost' => 'required|numeric',
    //         'discount' => 'nullable|integer',
    //         'salestatus' => 'required|string',
    //         'deliverydate' => 'nullable|date',
    //         'remarks' => 'nullable|string',
    //     ]);

    //     $order = StudioOrder::create($request->all());
    //     return response()->json($order, 201);
    // }

    // this is for Studio sittings order store
    public function storeOrder_ss(Request $request)
        {
            DB::beginTransaction();
            try {
                // Validate request
                $request->validate([
                    'studiokey' => 'required|integer',
                    'orderid' => 'required|string',
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

                // Check if customer session exists
                $customerKey = Session::get('customer_key');
                if (!$customerKey) {
                    return response()->json(['status' => 'error', 'message' => 'Customer not selected!'], 400);
                }

                // Create a new order
                $order = StudioOrder::create([
                    'studiokey' => $request->studiokey, // Change this dynamically if needed
                    'ordertypekey' => $request->ordertypekey,
                    'customerkey' => $customerKey,
                    'orderid' => $request->orderid,
                    'isurgent' => $request->isurgent,
                    'createduserkey' => auth()->id(),
                    'updateduserkey' => auth()->id(),
                    'totalcost' => 0, // Will calculate later
                    'paidcost' => 0,
                    'discount' => 0,
                    'salestatus' => 'New',
                    'createdtime' => now(),
                    'updatedtime' => now(),
                    'deliverydate' => $request->delivery_date,
                    'remarks' => $request->comments,
                ]);

                // Insert data into StudioOrderItemMapSS table
                foreach ($request->items as $item) {
                    StudioOrderItemMapSS::create([
                        'orderkey' => $order->orderkey,
                        'itemname' => $item['name'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                    ]);
                }

                DB::commit();
                return response()->json(['status' => 'success', 'message' => 'Order created successfully!', 'order_id' => $order->orderkey]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['status' => 'error', 'message' => 'Error creating order', 'error' => $e->getMessage()], 500);
            }
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
            'orderid' => 'sometimes|string',
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
