<?php

namespace App\Http\Controllers;

#use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudioOrder;
use App\Models\StudioOrderItemMapSS;
use Illuminate\Support\Facades\Session;
use App\Models\StudioOrderType;
use App\Models\StudioOrderTypeItemMap;
use App\Models\StudioEdittype;
use App\Models\StudioLaminatingtype;


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

        // Pass the data to the view
        return view('pages.todo.neworder', compact('editTypes', 'orderTypes', 'lamTypes'));
  }
  public function orders()
  {
    // Fetch all order types from the database
    $orderTypes = StudioOrderType::all();
    return view ('pages.todo.orders', compact('orderTypes'));
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
        $orders = StudioOrder::where(function ($q) use ($query,$otype) {
            // if (!empty($query)) {
            //     $q->where('ordertypekey','=', $otype)
            //       ->orWhere('orderno', 'LIKE', '%' . $query . '%')
            //     // Order number search
            //       ->orWhereHas('customer', function ($q) use ($query) { // Search in customer table
            //           $q->where('username', 'LIKE', '%' . $query . '%')
            //             ->orWhere('phonenumber', 'LIKE', '%' . $query . '%');
            //       });
            // }
            if (!empty($otype)) {
              $q->where('studioorders.ordertypekey', $otype);
            }
      
            if (!empty($query)) {
              $q->Where('orderno', 'LIKE', '%' . $query . '%');
            }
        })
        ->when(!empty($start_date) && !empty($end_date), function ($q) use ($start_date, $end_date) {
            $q->whereBetween('studioorders.createdtime', [$start_date, $end_date]);
        })
        ->join('studioordertypes', 'studioorders.ordertypekey', '=', 'studioordertypes.ordertypekey') // Join order types
        ->join('studiocustomers', 'studioorders.customerkey', '=', 'studiocustomers.customerkey') 
        //->select('studioorders.ordertypekey','studioorders.orderno', 'studioordertypes.ordertype') // Select required fields
        ->get();

        return response()->json($orders);

  }
  public function itemsearch(Request $request)
  {

        // Get input values
    //     $orderkey      = $request->input('orderkey');

      
    //     // Search order items based on orderkey
    //     $orderitems = StudioOrderItemMapSS::where(function ($q) use ($orderkey) {

    //         if (!empty($orderkey)) {
    //           $q->where('StudioOrderItemMapSS.orderkey', $orderkey);
              
    //         }
    
    //     })
    //  ->get();

    //     return response()->json($orderitems);
    // Get input values
    $orderkey = $request->input('orderkey');

    // Search order items based on orderkey and include item type from related model
    $orderitems = StudioOrderItemMapSS::with('editType','lamType'.'orderTypeItem') // Assuming 'itemType' is the relationship method
        ->when(!empty($orderkey), function ($query) use ($orderkey) {
            $query->where('StudioOrderItemMapSS.orderkey', $orderkey);
        })
        ->get();

    return response()->json($orderitems);
  }
  
}
