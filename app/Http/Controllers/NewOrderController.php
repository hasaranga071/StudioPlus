<?php

namespace App\Http\Controllers;

#use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewOrderController extends Controller
{
  public function neworder()
  {
    return view ('pages.todo.neworder');
  }
  public function orders()
  {
    return view ('pages.todo.orders');
  }

  public function search(Request $request)
  {
      $query = StudioOrder::query();

      if ($request->otype) {
        ->join('studios', 'studiousers.studiokey', '=', 'studios.studiokey') // Joining 'orders' table
          $query->where('username', 'LIKE', '%' . $request->username . '%');
      }
      if ($request->phonenumber) {
          $query->where('phonenumber', 'LIKE', '%' . $request->phonenumber . '%');
      }

      $customers = $query->get();

      return response()->json([
          'status' => true,
          'customers' => $customers
      ]);
  }
}
