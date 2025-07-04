<?php

namespace App\Http\Controllers;

#use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudioOrder;
use App\Models\StudioOrderItemMapSS;
use App\Models\StudioOrderItemMapEC;
use App\Models\StudioOrderItemMapME;
use App\Models\StudioOrderItemMapFR;
use Illuminate\Support\Facades\Session;
use App\Models\StudioOrderType;
use App\Models\StudioOrderTypeItemMap;
use App\Models\StudioEdittype;
use App\Models\StudioLaminatingtype;
use App\Models\StudioFrametype;
use App\Models\StudioFramesize;
use App\Models\StudioSubframetype;
use App\Models\StudioSubframesize;


class NewOrderController extends Controller
{
  public function neworder()
  {
    // clear sessions when page reloads
    Session::forget('customer_name');
    Session::forget('customer_id');
    Session::forget('order_id');

        // Fetch all order types from the database
        $orderTypes = StudioOrderType::all();

        // Fetch all edit types from the database
        $editTypes = StudioEdittype::all();

        // Fetch all laminate types from the database
        $lamTypes = StudioLaminatingtype::all();

        // Fetch all frame types from the database
        $frameTypes = StudioFrametype::all();

        // Fetch all frame size from the database
        $frameSizes = StudioFramesize::all();

        // Fetch all frame types from the database
        $frameSubTypes = StudioSubframetype::all();

        // Fetch all frame size from the database
        $frameSubSizes = StudioSubframesize::all();

        // Pass the data to the view
        return view('pages.todo.neworder', compact('editTypes', 'orderTypes', 'lamTypes', 'frameTypes', 'frameSizes', 'frameSubTypes', 'frameSubSizes'));
  }
  public function orders()
  {
    // Fetch all order types from the database
    $orderTypes = StudioOrderType::all();
    $editTypes = StudioEdittype::all();
    $lamTypes = StudioLaminatingtype::all();
    $frameTypes = StudioFrametype::all();
    $frameSizes = StudioFramesize::all();
    $frameSubSizes = StudioSubframesize::all();
    $frameSubTypes = StudioSubframetype::all();
    return view ('pages.todo.orders', compact('orderTypes','editTypes','lamTypes','frameTypes','frameSizes','frameSubSizes','frameSubTypes'));
  }

  public function orderssearch()
  {
    // Fetch all order types from the database
    $orderTypes = StudioOrderType::all();
    $editTypes = StudioEdittype::all();
    $lamTypes = StudioLaminatingtype::all();
    $frameTypes = StudioFrametype::all();
    $frameSizes = StudioFramesize::all();
    $frameSubSizes = StudioSubframesize::all();
    $frameSubTypes = StudioSubframetype::all();
    return view ('pages.todo.orderssearch', compact('orderTypes','editTypes','lamTypes','frameTypes','frameSizes','frameSubSizes','frameSubTypes'));
  }

  public function ordertypeitems($ordertypekey)
  {
      // Fetch items where the ordertypekey matches
      $items = StudioOrderTypeItemMap::where('ordertypekey', $ordertypekey)->get();

      // Return as JSON response
      return response()->json($items);
  }

  public function search(Request $request)
  {

        // Validate input
        $request->validate([
            'otype'      => 'nullable|string|max:255',
            'query'      => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        // Get input values
        $otype      = $request->input('otype');
        $query      = $request->input('query');
        $start_date = $request->input('start_date');
        $end_date   = $request->input('end_date');

        // Search orders based on multiple criteria
        if ($otype=='1')
        {
        $orders = StudioOrder::where(function ($q) use ($query, $otype, $start_date, $end_date) {
            if (!empty($otype)) {
               // $q->where('studioorders.ordertypekey', $otype);
            }

            if (!empty($query)) {
                $q->where(function ($subQuery) use ($query) {
                    $subQuery->where('studioorders.orderid', 'LIKE', '%' . $query . '%')
                             ->orWhere('studiocustomers.username', 'LIKE', '%' . $query . '%')
                             ->orWhere('StudioOrderItemMapSS.jobid', 'LIKE', '%' . $query . '%');
                });
            }

            // Apply date filter inside the same function
            if (!empty($start_date) && !empty($end_date)) {
                $q->whereBetween('studioorders.createdtime', [$start_date, $end_date]);
            }
            })
            //->join('studioordertypes', 'studioorders.ordertypekey', '=', 'studioordertypes.ordertypekey') // Join order types
            ->join('studiocustomers', 'studioorders.customerkey', '=', 'studiocustomers.customerkey')
            ->join('StudioOrderItemMapSS', 'studioorders.orderkey', '=', 'StudioOrderItemMapSS.orderkey')
            ->get();


            return response()->json($orders);
        }

        if ($otype=='2')
        {
        $orders = StudioOrder::where(function ($q) use ($query, $otype, $start_date, $end_date) {
            if (!empty($otype)) {
                //$q->where('studioorders.ordertypekey', $otype);
            }

            if (!empty($query)) {
                $q->where(function ($subQuery) use ($query) {
                    $subQuery->where('studioorders.orderid', 'LIKE', '%' . $query . '%')
                             ->orWhere('studiocustomers.username', 'LIKE', '%' . $query . '%')
                             ->orWhere('StudioOrderItemMapEC.jobid', 'LIKE', '%' . $query . '%');
                            });
            }

            // Apply date filter inside the same function
            if (!empty($start_date) && !empty($end_date)) {
                $q->whereBetween('studioorders.createdtime', [$start_date, $end_date]);
            }
            })
            ->join('studioordertypes', 'studioorders.ordertypekey', '=', 'studioordertypes.ordertypekey') // Join order types
            ->join('studiocustomers', 'studioorders.customerkey', '=', 'studiocustomers.customerkey')
            ->join('StudioOrderItemMapEC', 'studioorders.orderkey', '=', 'StudioOrderItemMapEC.orderkey')
            ->get();


            return response()->json($orders);
        }

        if ($otype=='3')
        {
        $orders = StudioOrder::where(function ($q) use ($query, $otype, $start_date, $end_date) {
            if (!empty($otype)) {
               // $q->where('studioorders.ordertypekey', $otype);
            }

            if (!empty($query)) {
                $q->where(function ($subQuery) use ($query) {
                    $subQuery->where('studioorders.orderid', 'LIKE', '%' . $query . '%')
                             ->orWhere('studiocustomers.username', 'LIKE', '%' . $query . '%')
                              ->orWhere('StudioOrderItemMapME.jobid', 'LIKE', '%' . $query . '%');
                            });
            }

            // Apply date filter inside the same function
            if (!empty($start_date) && !empty($end_date)) {
                $q->whereBetween('studioorders.createdtime', [$start_date, $end_date]);
            }
            })
            ->join('studioordertypes', 'studioorders.ordertypekey', '=', 'studioordertypes.ordertypekey') // Join order types
            ->join('studiocustomers', 'studioorders.customerkey', '=', 'studiocustomers.customerkey')
            ->join('StudioOrderItemMapME', 'studioorders.orderkey', '=', 'StudioOrderItemMapME.orderkey')
            ->get();


            return response()->json($orders);
        }

        if ($otype=='4')
        {
        $orders = StudioOrder::where(function ($q) use ($query, $otype, $start_date, $end_date) {
            if (!empty($otype)) {
                //$q->where('studioorders.ordertypekey', $otype);
            }

            if (!empty($query)) {
                $q->where(function ($subQuery) use ($query) {
                    $subQuery->where('studioorders.orderid', 'LIKE', '%' . $query . '%')
                             ->orWhere('studiocustomers.username', 'LIKE', '%' . $query . '%')
                             ->orWhere('StudioOrderItemMapFR.jobid', 'LIKE', '%' . $query . '%');
                            });
            }

            // Apply date filter inside the same function
            if (!empty($start_date) && !empty($end_date)) {
                $q->whereBetween('studioorders.createdtime', [$start_date, $end_date]);
            }
            })
            ->join('studioordertypes', 'studioorders.ordertypekey', '=', 'studioordertypes.ordertypekey') // Join order types
            ->join('studiocustomers', 'studioorders.customerkey', '=', 'studiocustomers.customerkey')
            ->join('StudioOrderItemMapFR', 'studioorders.orderkey', '=', 'StudioOrderItemMapFR.orderkey')
            ->get();


            return response()->json($orders);
        }

  }

  public function getOrderDetails(Request $request,$orderkey)
  {
        // Search orders based on multiple criteria
        $orders = StudioOrder::where('studioorders.orderkey',$orderkey)
        ->join('studioordertypes', 'studioorders.ordertypekey', '=', 'studioordertypes.ordertypekey') // Join order types
        ->join('studiocustomers', 'studioorders.customerkey', '=', 'studiocustomers.customerkey')
        ->select(
            'studioorders.*',
            'studioordertypes.ordertype as order_type',
            'studiocustomers.username as customer_name'
        )
        ->get();
        return response()->json($orders);
  }

  public function itemsearch(Request $request)
  {
    $orderkey = $request->input('orderkey');

    // Search order items based on orderkey and include item type from related model
    $orderitems = StudioOrderItemMapSS::with('editType','orderTypeItem') // Assuming 'itemType' is the relationship method
        ->when(!empty($orderkey), function ($query) use ($orderkey) {
            $query->where('StudioOrderItemMapSS.orderkey', $orderkey);
        })
        ->get();

    return response()->json($orderitems);
  }
  public function itemsearch_EC(Request $request)
  {
    $orderkey = $request->input('orderkey');

    // Search order items based on orderkey and include item type from related model
    $orderitems = StudioOrderItemMapEC::with('editType','orderTypeItem','originalOrder') // Assuming 'itemType' is the relationship method
        ->when(!empty($orderkey), function ($query) use ($orderkey) {
            $query->where('StudioOrderItemMapEC.orderkey', $orderkey);
        })
        ->get();

    return response()->json($orderitems);
  }
  public function getOrderItemSummary($orderkey,Request $request)
  {

    $ordertypekey = StudioOrder::where('orderkey',  $orderkey)->value('ordertypekey');
    $ordertype = $request->query('ordertype');
    //$ordertype = StudioOrderType::where('ordertypekey',  $ordertypekey)->value('ordertype');

    if ($ordertype=='Studio Sittings')
    {
        // Search order items based on orderkey
        $orderitems = StudioOrderItemMapSS::with('editType','lamType','orderTypeItem','order.orderType') // Assuming 'itemType' is the relationship method
        ->when(!empty($orderkey), function ($query) use ($orderkey) {
            $query->where('StudioOrderItemMapSS.orderkey', $orderkey);
        })
        ->get();
        return response()->json([
            'status' => 'success',
            'orderItems' => $orderitems
        ]);
    }

    else if ($ordertype=='Extra Copy')

    {
        // Search order items based on orderkey
        $orderitems = StudioOrderItemMapEC::with('editType','lamType','orderTypeItem','order.orderType','originalOrder') // Assuming 'itemType' is the relationship method
        ->when(!empty($orderkey), function ($query) use ($orderkey) {
            $query->where('StudioOrderItemMapEC.orderkey', $orderkey);
        })
        ->get();
        return response()->json([
            'status' => 'success',
            'orderItems' => $orderitems
        ]);
    }

    else if ($ordertype=='Media')
    {
        // Search order items based on orderkey
        $orderitems = StudioOrderItemMapME::with('editType','lamType','orderTypeItem','order.orderType') // Assuming 'itemType' is the relationship method
        ->when(!empty($orderkey), function ($query) use ($orderkey) {
            $query->where('StudioOrderItemMapME.orderkey', $orderkey);
        })
        ->get();
        return response()->json([
            'status' => 'success',
            'orderItems' => $orderitems
        ]);
    }

    else
    {
        // Search order items based on orderkey
        $orderitems = StudioOrderItemMapFR::with('frameSize','subframeType','frameType','subframeSize','order.orderType') // Assuming 'itemType' is the relationship method
        ->when(!empty($orderkey), function ($query) use ($orderkey) {
            $query->where('StudioOrderItemMapFR.orderkey', $orderkey);
        })
        ->get();
        return response()->json([
            'status' => 'success',
            'orderItems' => $orderitems
        ]);
    }


  }

  public function getOrderTotalSummary($orderkey)
{
    $discountamount = StudioOrder::where('orderkey',  $orderkey)->value('discount');
    $paidamount = StudioOrder::where('orderkey',  $orderkey)->value('paidcost');
    $totalSS = \App\Models\StudioOrderItemMapSS::where('orderkey', $orderkey)->sum('totalcost');
    $totalEC = \App\Models\StudioOrderItemMapEC::where('orderkey', $orderkey)->sum('totalcost');
    $totalME = \App\Models\StudioOrderItemMapME::where('orderkey', $orderkey)->sum('totalcost');
    $totalFR = \App\Models\StudioOrderItemMapFR::where('orderkey', $orderkey)->sum('totalcost');

    $grandTotal = $totalSS + $totalEC + $totalME + $totalFR;

    return response()->json([
        'status' => 'success',
        'orderkey' => $orderkey,
        'total_SS' => $totalSS,
        'total_EC' => $totalEC,
        'total_ME' => $totalME,
        'total_FR' => $totalFR,
        'dicount' => $discountamount,
        'paidamount' => $paidamount,
        'grandtotal' => $grandTotal
    ]);
}


  public function getOrderItemDetails($orderitemmapkey,Request $request)
  {

    $ordertype = $request->query('ordertype');
    if ($ordertype=='Studio Sittings')
    {
        // Search order items based on orderkey
        $orderitems = StudioOrderItemMapSS::with('editType','lamType','orderTypeItem','order.orderType','order') // Assuming 'itemType' is the relationship method
        ->when(!empty($orderitemmapkey), function ($query) use ($orderitemmapkey) {
            $query->where('StudioOrderItemMapSS.ssorderitemmapkey', $orderitemmapkey);
        })
        ->get();
        return response()->json([
            'status' => 'success',
            'orderItems' => $orderitems
        ]);
    }

    else if ($ordertype=='Extra Copy')
    {
        // Search order items based on orderkey
        $orderitems = StudioOrderItemMapEC::with('editType','lamType','orderTypeItem','order.orderType','order') // Assuming 'itemType' is the relationship method
        ->when(!empty($orderitemmapkey), function ($query) use ($orderitemmapkey) {
            $query->where('StudioOrderItemMapEC.ecorderitemmapkey', $orderitemmapkey);
        })
        ->get();
        return response()->json([
            'status' => 'success',
            'orderItems' => $orderitems
        ]);
    }

    else if ($ordertype=='Media')
    {
        // Search order items based on orderkey
        $orderitems = StudioOrderItemMapME::with('editType','lamType','orderTypeItem','order.orderType','order') // Assuming 'itemType' is the relationship method
        ->when(!empty($orderitemmapkey), function ($query) use ($orderitemmapkey) {
            $query->where('StudioOrderItemMapME.meorderitemmapkey', $orderitemmapkey);
        })
        ->get();
        return response()->json([
            'status' => 'success',
            'orderItems' => $orderitems
        ]);
    }

    else
    {
        // Search order items based on orderkey
        $orderitems = StudioOrderItemMapFR::with('frameSize','subframeType','frameType','subframeSize','order.orderType','order') // Assuming 'itemType' is the relationship method
        ->when(!empty($orderitemmapkey), function ($query) use ($orderitemmapkey) {
            $query->where('StudioOrderItemMapFR.frorderitemmapkey', $orderitemmapkey);
        })
        ->get();
        return response()->json([
            'status' => 'success',
            'orderItems' => $orderitems
        ]);
    }


  }

}
