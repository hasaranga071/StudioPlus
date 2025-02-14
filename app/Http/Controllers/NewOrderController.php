<?php

namespace App\Http\Controllers;

#use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudioOrder;
use Illuminate\Support\Facades\Session;
use App\Models\StudioOrderType;

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

        // Pass the data to the view
        return view('pages.todo.neworder', compact('orderTypes'));
  }
  public function orders()
  {
    // Fetch all order types from the database
    $orderTypes = StudioOrderType::all();
    return view ('pages.todo.orders', compact('orderTypes'));
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
            if (!empty($query)) {
                $q->where('orderno', 'LIKE', '%' . $query . '%')
                  ->orWhere('ordertypekey','=', $otype)
                // Order number search
                  ->orWhereHas('customer', function ($q) use ($query) { // Search in customer table
                      $q->where('username', 'LIKE', '%' . $query . '%')
                        ->orWhere('phonenumber', 'LIKE', '%' . $query . '%');
                  });
            }
        })
        ->when(!empty($start_date) && !empty($end_date), function ($q) use ($start_date, $end_date) {
            $q->whereBetween('createdtime', [$start_date, $end_date]);
        })
        
        ->with('customer') // Load customer data
        ->get();

        return response()->json($orders);

  }
}
