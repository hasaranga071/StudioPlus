<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudioOrder;
use App\Models\StudioOrderTypeItemMap;
use App\Models\StudioAppConfig;
use App\Models\StudioEdittype;
use App\Models\StudioLaminatingtype;
use App\Models\StudioOrderItemMapSS;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;



class StudioOrderController extends Controller
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
                    'orderid' => 'required|unique:studioorders,orderid',
                    'ordertypekey' => 'required|integer',
                    'customerkey' => 'required|integer',
                    'isurgent' => 'required|boolean',
                    'paidcost' => 'required|numeric',
                    'discount' => 'required|integer',
                    'deliverydate' => 'nullable|date',
                    'remarks' => 'nullable|string',
                    'ordertypeitemkey' => 'required|exists:studioordertypeitemmap,ordertypeitemkey', // Ensure it exists in the DB
                    'edittypekey ' => 'nullable|integer',
                    'lamtypekey ' => 'nullable|integer',
                ]);

                // Check if customer session exists
                $customerKey = Session::get('customer_key');
                if (!$customerKey) {
                    return response()->json(['status' => 'error', 'message' => 'Customer not selected!'], 400);
                }

                $totalCost = 0;
                $ssitemCost = 0;
                // \Log::info('Request Data:', $request->all());

                // Calculate total cost from unit prices for  hard copies

                    $orderTypeItemKey = $request->ordertypeitemkey;

                    // Fetch the UnitCost from StudioOrderItemMap table
                    $unitCost = StudioOrderTypeItemMap::where('ordertypeitemkey', $orderTypeItemKey)->value('unitprice');

                    if ($unitCost === null) {
                        return response()->json(['status' => 'error', 'message' => "Unit price is empty for selected item"], 400);
                    }

                    $itemTotal = $unitCost * $request->hardcopycount;
                    $totalCost += $itemTotal;


                // calculate soft copy from unit price

                    $studiokey = $request->studiokey;

                    // Fetch the UnitCost from StuidoAppconfig table
                    $unitCost = StudioAppConfig::where('studiokey', $studiokey)->value('softcopyunitprice');

                    if ($unitCost === null) {
                        return response()->json(['status' => 'error', 'message' => "Unit price is not configured for soft copies !"], 400);
                    }

                    $itemTotal = $unitCost * $request->softcopycount;
                    $totalCost += $itemTotal;
                    $ssitemCost = $totalCost;

                    \Log::info('orderTypeItemKey :', ['orderTypeItemKey' =>  $orderTypeItemKey]);
                // add cost for editing

                    $edittypekey = $request->edittypekey;

                    // Fetch the UnitCost from studiodittypes table
                    $unitCost = StudioEdittype::where('edittypekey', $edittypekey)->value('unitcost');

                    if ($unitCost === null) {
                        return response()->json(['status' => 'error', 'message' => "Unit price is not configured for this edit type !"], 400);
                    }

                    $totalCost += $unitCost;


                // add cost for Laminating

                    $lamtypekey =  $request->lamtypekey;

                    // Fetch the UnitCost from studiodittypes table
                    $unitCost = StudioLaminatingtype::where('lamtypekey', $lamtypekey)->value('unitcost');

                    if ($unitCost === null) {
                        return response()->json(['status' => 'error', 'message' => "Unit price is not configured for this laminating type !"], 400);
                    }

                    $totalCost += $unitCost;


                // apply discount for total price

                    $discount = $request->discount;
                    $discountAmount = ($totalCost * $discount) / 100;
                    $totalCost = $totalCost - $discountAmount;




                // Create a new order
                $order = StudioOrder::create([
                    'studiokey' => $request->studiokey, // Change this dynamically if needed
                    'ordertypekey' => $request->ordertypekey,
                    'customerkey' => $request->customerkey,
                    'orderid' => $request->orderid,
                    'isurgent' => $request->isurgent,
                    'createduserkey' => auth()->id(),
                    'updateduserkey' => auth()->id(),
                    'totalcost' => $totalCost,
                    'paidcost' => $request->paidcost,
                    'discount' => $request->discount,
                    'salestatus' => 'New',
                    'createdtime' => now(),
                    'updatedtime' => now(),
                    'deliverydate' => $request->deliverydate,
                    'remarks' => $request->comments,
                ]);

                // Insert data into StudioOrderItemMapSS table

                    StudioOrderItemMapSS::create([
                        'orderkey' => $order->orderkey,
                        'ordertypeitemkey' => $request->ordertypeitemkey,
                        'edittypekey' => $request->edittypekey,
                        'lamtypekey' => $request->lamtypekey,
                        'softcopyquantity' => $request->softcopycount,
                        'hardcopyquantity' => $request->hardcopycount,
                        'totalcost' => $ssitemCost,
                    ]);


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
