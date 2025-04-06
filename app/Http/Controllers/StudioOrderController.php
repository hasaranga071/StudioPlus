<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudioOrder;
use App\Models\StudioOrderTypeItemMap;
use App\Models\StudioOrderType;
use App\Models\StudioAppConfig;
use App\Models\StudioEdittype;
use App\Models\StudioLaminatingtype;
use App\Models\StudioOrderItemMapSS;
use App\Models\StudioOrderItemMapEC;
use App\Models\StudioOrderItemMapME;
use App\Models\StudioOrderItemMapFR;
use App\Models\StudioFramesize;
use App\Models\StudioFrametype;
use App\Models\StudioSubframetype;
use App\Models\StudioSubframesize;
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
                    'orderid' => 'required|string',
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
                // $customerKey = Session::get('customer_key');
                // if (!$customerKey) {
                //     return response()->json(['status' => 'error', 'message' => 'Customer not selected!'], 400);
                // }

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

                    // \Log::info('orderTypeItemKey :', ['orderTypeItemKey' =>  $orderTypeItemKey]);
                // add cost for editing

                    $edittypekey = $request->edittypekey;
                    if ($edittypekey > 0)
                    {
                         // Fetch the UnitCost from studiodittypes table
                    $unitCost = StudioEdittype::where('edittypekey', $edittypekey)->value('unitcost');

                    if ($unitCost === null) {
                        return response()->json(['status' => 'error', 'message' => "Unit price is not configured for this edit type !"], 400);
                    }
                    }


                    $totalCost += $unitCost;


                // add cost for Laminating

                    $lamtypekey =  $request->lamtypekey;

                    // Fetch the UnitCost from studiodittypes table
                    $unitCost = StudioLaminatingtype::where('lamtypekey', $lamtypekey)->value('unitcost');

                    if ($unitCost === null) {
                        $unitCost = 0;
                       // return response()->json(['status' => 'error', 'message' => "Unit price is not configured for this laminating type !"], 400);
                    }

                    $totalCost += $unitCost;
                    $ssitemCost = $totalCost;

                // apply discount for total price

                    $discount = $request->discount;
                    $discountAmount = ($totalCost * $discount) / 100;
                    $totalCost = $totalCost - $discountAmount;



                // Check if order already exists
                $order = StudioOrder::where('orderid', $request->orderid)->first();
                $sorderkey = 0;
                if ($order) {
                    // update the order
                    $sorderkey = $order -> orderkey;
                    $lamTypeKey = $request->lamtypekey ?? 0;  // If null, assign 0

                    $ordertype = $request->ordertype;
                     // Insert / update data into StudioOrderItemMapSS table
                     $totalitemCost ;
                    if ($ordertype=='Studio Sittings') {
                        StudioOrderItemMapSS::updateOrCreate(
                            [
                                'orderkey' =>  $sorderkey,
                                'ordertypeitemkey' => $request->ordertypeitemkey
                            ],
                            [
                                'edittypekey' => $request->edittypekey,
                                'lamtypekey' => $request->lamtypekey,
                                'softcopyquantity' => $request->softcopycount,
                                'hardcopyquantity' => $request->hardcopycount,
                                'totalcost' => $ssitemCost,
                            ]
                        );
                        $totalitemCost = StudioOrderItemMapSS::where('orderkey', $sorderkey)->sum('totalcost');
                     }

                     if ($ordertype=='Media') {
                        StudioOrderItemMapME::updateOrCreate(
                            [
                                'orderkey' =>  $sorderkey,
                                'ordertypeitemkey' => $request->ordertypeitemkey
                            ],
                            [
                                'edittypekey' => $request->edittypekey,
                                'lamtypekey' => $request->lamtypekey,
                                'softcopyquantity' => $request->softcopycount,
                                'hardcopyquantity' => $request->hardcopycount,
                                'totalcost' => $ssitemCost,
                            ]
                        );
                        $totalitemCost = StudioOrderItemMapME::where('orderkey', $sorderkey)->sum('totalcost');
                     }

                     if ($ordertype=='Extra Copy') {
                        StudioOrderItemMapEC::updateOrCreate(
                            [
                                'orderkey' =>  $sorderkey,
                                'ordertypeitemkey' => $request->ordertypeitemkey
                            ],
                            [
                                'edittypekey' => $request->edittypekey,
                                'hardcopyquantity' => $request->hardcopycount,
                                'originalorderkey' => $request->originalorderkey,
                                'totalcost' => $ssitemCost,
                            ]
                        );
                        $totalitemCost = StudioOrderItemMapEC::where('orderkey', $sorderkey)->sum('totalcost');
                    }

                    // calculating all item cost for the order
                    $discount = $request->discount;
                    $discountAmount = ($totalitemCost * $discount) / 100;
                    $totalitemCost = $totalitemCost - $discountAmount;

                    $order->update([
                        'studiokey' => $request->studiokey,
                        'ordertypekey' => $request->ordertypekey,
                        'customerkey' => $request->customerkey,
                        'isurgent' => $request->isurgent,
                        'salestatus' => 'New',
                        'updatedtime' => now(),
                        'deliverydate' => $request->deliverydate,
                        'remarks' => $request->remarks,
                        'updateduserkey' => auth()->id(),
                        'updatedtime' => now(),
                        'totalcost' => $totalitemCost,
                        'paidcost' => $request->paidcost,
                        'discount' => $request->discount,
                    ]);

                    $message = 'Order Updated Successfully!';
                }
                else {
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
                        'remarks' => $request->remarks,
                    ]);

                    $sorderkey = $order->orderkey;
                    $ordertype = $request->ordertype;
                    if ($ordertype=='Studio Sittings') {
                        StudioOrderItemMapSS::updateOrCreate(
                            [
                                'orderkey' =>  $sorderkey,
                                'ordertypeitemkey' => $request->ordertypeitemkey
                            ],
                            [
                                'edittypekey' => $request->edittypekey,
                                'lamtypekey' => $request->lamtypekey,
                                'softcopyquantity' => $request->softcopycount,
                                'hardcopyquantity' => $request->hardcopycount,
                                'totalcost' => $ssitemCost,
                                'iscompleted' => $request->iscompleted,
                            ]
                        );
                    }

                    if ($ordertype=='Extra Copy') {
                        StudioOrderItemMapEC::updateOrCreate(
                            [
                                'orderkey' =>  $sorderkey,
                                'ecorderitemmapkey' => $request->ecorderitemmapkey
                            ],
                            [
                                'edittypekey' => $request->edittypekey,
                                'hardcopyquantity' => $request->hardcopycount,
                                'originalorderkey' => $request->originalorderkey,
                                'totalcost' => $ssitemCost,
                                'iscompleted' => $request->iscompleted,
                                'ordertypeitemkey' => $request->ordertypeitemkey,
                            ]
                        );
                    }
                    if ($ordertype=='Media') {
                        StudioOrderItemMapME::updateOrCreate(
                            [
                                'orderkey' =>  $sorderkey,
                                'ordertypeitemkey' => $request->ordertypeitemkey
                            ],
                            [
                                'edittypekey' => $request->edittypekey,
                                'lamtypekey' => $request->lamtypekey,
                                'softcopyquantity' => $request->softcopycount,
                                'hardcopyquantity' => $request->hardcopycount,
                                'totalcost' => $ssitemCost,
                                'iscompleted' => $request->iscompleted,
                            ]
                        );
                    }
                    $message = 'Order Created Successfully!';

                 }



                DB::commit();
                return response()->json(['status' => 'success', 'message' => $message, 'order_id' => $order->orderkey]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['status' => 'error', 'message' => 'Error creating order', 'error' => $e->getMessage()], 500);
            }
        }

        public function storeOrder_fr(Request $request)
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
                    'paidcost' => 'required|numeric',
                    'discount' => 'required|integer',
                    'deliverydate' => 'nullable|date',
                    'remarks' => 'nullable|string',
                    'quantity' => 'required|integer',

                ]);

                // Check if customer session exists
                // $customerKey = Session::get('customer_key');
                // if (!$customerKey) {
                //     return response()->json(['status' => 'error', 'message' => 'Customer not selected!'], 400);
                // }

                $totalCost = 0;
                $ssitemCost = 0;
                $frunitCost = 0;
                $subfrunitCost = 0;
                // \Log::info('Request Data:', $request->all());




                // calculate soft copy from unit price

                    $studiokey = $request->studiokey;

                    $framesizekey = $request->framesizekey;
                    $subframesizekey = $request->subframesizekey;
                    $fquantity = $request->quantity;
                    if(!$fquantity){$fquantity =1;}


                    if ($framesizekey > 0)
                    {
                        // Fetch the UnitCost from framesize table
                        $frunitCost = StudioFramesize::where('framesizekey', $framesizekey)->value('unitprice');

                        if ($frunitCost === null) {
                            return response()->json(['status' => 'error', 'message' => "Unit price is not configured for this frame size !"], 400);
                        }
                    }
                    if ($subframesizekey > 0)
                    {
                        // Fetch the UnitCost from framesize table
                        $subfrunitCost = StudioSubframesize::where('subframesizekey', $subframesizekey)->value('unitprice');

                        if ($subfrunitCost === null) {
                            return response()->json(['status' => 'error', 'message' => "Unit price is not configured for this sub frame size !"], 400);
                        }
                    }

                    $totalcost= ($frunitCost * $fquantity)  + ($subfrunitCost * $fquantity);

                    $discount = $request->discount;
                    $discountAmount = ($totalcost * $discount) / 100;
                    $totalcost = $totalcost - $discountAmount;



                // Check if order already exists
                $order = StudioOrder::where('orderid', $request->orderid)->first();
                $sorderkey = 0;
                if ($order) {
                    // update the order
                    $sorderkey = $order -> orderkey;
                    $lamTypeKey = $request->lamtypekey ?? 0;  // If null, assign 0

                    $ordertype = $request->ordertype;
                     // Insert / update data into StudioOrderItemMapSS table
                     $totalitemCost ;



                     StudioOrderItemMapFR::updateOrCreate(
                        [
                            'orderkey' =>  $sorderkey,
                            'frorderitemmapkey' => $request->frorderitemmapkey
                        ],
                        [
                            'framesizekey' => $request->framesizekey,
                            'frametypekey' => $request->frametypekey,
                            'subframesizekey' => $request->subframesizekey,
                            'subframetypekey' => $request->subframetypekey,
                            'totalcost' => $totalcost,
                            'quantity' => $request->quantity,
                        ]
                    );
                    $totalitemCost = StudioOrderItemMapFR::where('orderkey', $sorderkey)->sum('totalcost');



                    $order->update([
                        'studiokey' => $request->studiokey,
                        'ordertypekey' => $request->ordertypekey,
                        'customerkey' => $request->customerkey,
                        'isurgent' => $request->isurgent,
                        'salestatus' => 'New',
                        'updatedtime' => now(),
                        'deliverydate' => $request->deliverydate,
                        'remarks' => $request->remarks,
                        'updateduserkey' => auth()->id(),
                        'updatedtime' => now(),
                        'totalcost' => $totalitemCost,
                        'paidcost' => $request->paidcost,
                        'discount' => $request->discount,
                    ]);

                    $message = 'Order Updated Successfully!';
                }
                else {
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
                        'remarks' => $request->remarks,
                    ]);

                    $sorderkey = $order->orderkey;
                    $ordertype = $request->ordertype;

                    $framesizekey = $request->framesizekey;
                    $subframesizekey = $request->subframesizekey;
                    $fquantity = $request->quantity;
                    if(!$fquantity){$fquantity =1;}
                    if ($framesizekey > 0)
                    {
                        // Fetch the UnitCost from framesize table
                        $frunitCost = StudioFramesize::where('framesizekey', $framesizekey)->value('unitprice');

                        if ($frunitCost === null) {
                            return response()->json(['status' => 'error', 'message' => "Unit price is not configured for this frame size !"], 400);
                        }
                    }
                    if ($subframesizekey > 0)
                    {
                        // Fetch the UnitCost from framesize table
                        $subfrunitCost = StudioSubframesize::where('subframesizekey', $subframesizekey)->value('unitprice');

                        if ($subfrunitCost === null) {
                            return response()->json(['status' => 'error', 'message' => "Unit price is not configured for this sub frame size !"], 400);
                        }

                        $totalcost= ($frunitCost * $fquantity)  + ($subfrunitCost * $fquantity);
                    }

                    $discount = $request->discount;
                    $discountAmount = ($totalcost * $discount) / 100;
                    $totalcost = $totalcost - $discountAmount;

                    StudioOrderItemMapFR::updateOrCreate(
                        [
                            'orderkey' =>  $sorderkey,
                            'frametypekey' => $request->frametypekey,
                            'framesizekey' => $request->framesizekey
                        ],
                        [
                            'framesizekey' => $request->framesizekey,
                            'frametypekey' => $request->frametypekey,
                            'subframesizekey' => $request->subframesizekey,
                            'subframetypekey' => $request->subframetypekey,
                            'quantity' => $request->quantity,
                            'totalcost' => $totalcost,
                        ]
                    );
                    $totalitemCost = StudioOrderItemMapFR::where('orderkey', $sorderkey)->sum('totalcost');

                    $message = 'Order Created Successfully!';

                 }



                DB::commit();
                return response()->json(['status' => 'success', 'message' => $message, 'order_id' => $order->orderkey]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['status' => 'error', 'message' => 'Error creating order', 'error' => $e->getMessage()], 500);
            }
        }

        public function deleteOrderItem($id, Request $request) {

            $orderkey = $request->input('orderkey');

            $ordertypekey = StudioOrder::where('orderkey',  $orderkey)->value('ordertypekey');
            $ordertype = StudioOrderType::where('ordertypekey',  $ordertypekey)->value('ordertype');

            $orderItem = false; // Default to false in case no delete query runs
           // \Log::info('Order Type:', ['ordertype' => $orderkey]);

            if ($ordertype == 'Studio Sittings') {
                $orderItem = DB::table('studioorderitemmapss')->where('ssorderitemmapkey', $id)->delete();
            }
            if ($ordertype == 'Extra Copy') {
                $orderItem = DB::table('studioorderitemmapec')->where('ecorderitemmapkey', $id)->delete();
            }
            if ($ordertype == 'Media') {
                $orderItem = DB::table('studioorderitemmapme')->where('meorderitemmapkey', $id)->delete();
            }
            if ($ordertype == 'Frames') {
                $orderItem = DB::table('studioorderitemmapfr')->where('frorderitemmapkey', $id)->delete();
            }

            $this->updateordertotal($orderkey);

            if ($orderItem) {
                session()->flash('success', 'The order item has been deleted successfully.');
                return response()->json(['success' => true]);
            }

            return response()->json(['error' => 'Failed to delete order item'], 500);
        }

        public function updateordertotal($orderkey)
        {
            $order = StudioOrder::where('orderkey',  $orderkey);
            $ordertypekey = StudioOrder::where('orderkey',  $orderkey)->value('ordertypekey');
            $discount = StudioOrder::where('orderkey',  $orderkey)->value('discount');
            $ordertype = StudioOrderType::where('ordertypekey',  $ordertypekey)->value('ordertype');
            $totalcost = 0;
            $orderitemtotal = 0;
            if ($ordertype == 'Studio Sittings') {
                $orderitemtotal = StudioOrderItemMapSS::where('orderkey', $orderkey)->sum('totalcost');
            }
            if ($ordertype == 'Extra Copy') {
                $orderitemtotal = StudioOrderItemMapEC::where('orderkey', $orderkey)->sum('totalcost');
            }
            if ($ordertype == 'Media') {
                $orderitemtotal = StudioOrderItemMapME::where('orderkey', $orderkey)->sum('totalcost');
            }
            if ($ordertype == 'Frames') {
                $orderitemtotal = StudioOrderItemMapFR::where('orderkey', $orderkey)->sum('totalcost');
            }

            $discountAmount = ($orderitemtotal * $discount) / 100;
            $totalCost = $orderitemtotal - $discountAmount;

            $order->update([
                'updatedtime' => now(),
                'updateduserkey' => auth()->id(),
                'updatedtime' => now(),
                'totalcost' => $totalCost
            ]);


        }

        public function checkAllItemsCompleted($orderkey)
        {
            //$orderkey = $request->input('orderkey');

            $ordertypekey = StudioOrder::where('orderkey',  $orderkey)->value('ordertypekey');
            $ordertype = StudioOrderType::where('ordertypekey',  $ordertypekey)->value('ordertype');

            $orderItem = false;

            if ($ordertype == 'Studio Sittings') {
                $incomplete = DB::table('studioorderitemmapss')
                    ->where('orderkey', $orderkey)
                    ->where(function($query) {
                        $query->where('iscompleted', '!=', 1)
                            ->orWhereNull('iscompleted');
                    })
                    ->count();

                return response()->json(['allCompleted' => $incomplete === 0]);
            }
            if ($ordertype == 'Extra Copy') {
                $incomplete = DB::table('studioorderitemmapec')
                    ->where('orderkey', $orderkey)
                    ->where(function($query) {
                        $query->where('iscompleted', '!=', 1)
                            ->orWhereNull('iscompleted');
                    })
                    ->count();

                return response()->json(['allCompleted' => $incomplete === 0]);
            }
            if ($ordertype == 'Media') {
                $incomplete = DB::table('studioorderitemmapme')
                    ->where('orderkey', $orderkey)
                    ->where(function($query) {
                        $query->where('iscompleted', '!=', 1)
                            ->orWhereNull('iscompleted');
                    })
                    ->count();

                return response()->json(['allCompleted' => $incomplete === 0]);
            }
            if ($ordertype == 'Frames') {
                $incomplete = DB::table('studioorderitemmapfr')
                    ->where('orderkey', $orderkey)
                    ->where(function($query) {
                        $query->where('iscompleted', '!=', 1)
                            ->orWhereNull('iscompleted');
                    })
                    ->count();

                return response()->json(['allCompleted' => $incomplete === 0]);
            }
            
            
        }

        public function updateOrderStatus(Request $request)
        {
            $order = StudioOrder::where('orderkey', $request->orderkey)->first();
            if ($order) {
                $order->salestatus = 'Completed'; // or any logic you use
                $order->updatedtime=now();
                $order->updateduserkey=auth()->id();
                $order->save();
                return response()->json(['status' => 'success']);
            }
            return response()->json(['status' => 'error']);
        }

        public function markAsCompleted($id, Request $request) {

            $orderkey = $request->input('orderkey');

            $ordertypekey = StudioOrder::where('orderkey',  $orderkey)->value('ordertypekey');
            $ordertype = StudioOrderType::where('ordertypekey',  $ordertypekey)->value('ordertype');

            $orderItem = false;

            if ($ordertype == 'Studio Sittings') {
                $orderItem = DB::table('studioorderitemmapss')->where('ssorderitemmapkey', $id)->update([
                    'iscompleted' => 1
                ]);
            }
            if ($ordertype == 'Extra Copy') {
                $orderItem = DB::table('studioorderitemmapec')->where('ecorderitemmapkey', $id)->update([
                    'iscompleted' => 1
                ]);
            }
            if ($ordertype == 'Media') {
                $orderItem = DB::table('studioorderitemmapme')->where('meorderitemmapkey', $id)->update([
                    'iscompleted' => 1
                ]);
            }
            if ($ordertype == 'Frames') {
                $orderItem = DB::table('studioorderitemmapfr')->where('frorderitemmapkey', $id)->update([
                    'iscompleted' => 1
                ]);
            }

            if ($orderItem) {
                session()->flash('success', 'The order item has been updated successfully.');
                return response()->json(['success' => true]);
            }

            return response()->json(['error' => 'Failed to delete order item'], 500);
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

    // public function getOrderItemSummary($orderkey)
    // {
    //     $orderItems = DB::table('studioorderitemmapss as soim')
    //         ->join('studioordertypeitemmap as sotim', 'soim.ordertypeitemkey', '=', 'sotim.ordertypeitemkey')
    //         ->join('studioordertypes as sot', 'sotim.ordertypekey', '=', 'sot.ordertypekey')
    //         ->join('studioorders as so', 'soim.orderkey', '=', 'so.orderkey') // Fixed join condition
    //         ->leftJoin('studioedittypes as set', 'soim.edittypekey', '=', 'set.edittypekey')
    //         ->where('soim.orderkey', $orderkey)
    //         ->select(
    //             'sot.ordertype as ordertype',
    //             'sotim.itemname as itemname',
    //             'soim.softcopyquantity',
    //             'soim.hardcopyquantity',
    //             'soim.totalcost',
    //             'so.isurgent',
    //             'so.deliverydate',
    //             'so.remarks',
    //             'so.totalcost as ordercost',
    //             'so.paidcost',
    //             'so.discount',
    //             'soim.ssorderitemmapkey as ssorderitemmapkey',
    //             'soim.orderkey',
    //             'set.edittype'
    //         )
    //         ->get();

    //     return response()->json([
    //         'status' => 'success',
    //         'orderItems' => $orderItems
    //     ]);
    // }

    // public function getOrderItemDetails($ssorderitemmapkey)
    // {
    //     $orderItems = DB::table('studioorderitemmapss as soim')
    //         ->join('studioordertypeitemmap as sotim', 'soim.ordertypeitemkey', '=', 'sotim.ordertypeitemkey')
    //         ->join('studioordertypes as sot', 'sotim.ordertypekey', '=', 'sot.ordertypekey')
    //         ->join('studioorders as so', 'soim.orderkey', '=', 'so.orderkey')
    //         ->leftJoin('studioedittypes as set', 'soim.edittypekey', '=', 'set.edittypekey')
    //         ->where('soim.ssorderitemmapkey', $ssorderitemmapkey)
    //         ->select(
    //             'sot.ordertype as ordertype',
    //             'sotim.itemname as itemname',
    //             'soim.softcopyquantity',
    //             'soim.hardcopyquantity',
    //             'soim.totalcost',
    //             'so.isurgent',
    //             'so.deliverydate',
    //             'sotim.ordertypekey',
    //             'soim.ordertypeitemkey',
    //             'soim.edittypekey',
    //             'soim.lamtypekey',
    //             'so.paidcost',
    //             'so.discount',
    //             'set.edittype',
    //             'so.remarks'

    //         )
    //         ->get();

    //     return response()->json([
    //         'status' => 'success',
    //         'orderItems' => $orderItems
    //     ]);
    // }



    /**
     * Remove the specified order from storage.
     */
    public function destroy($id)
    {
        StudioOrder::destroy($id);
        return response()->json(['message' => 'Order deleted successfully']);
    }
}
