<?php

namespace App\Http\Controllers;

#use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class NewOrderController extends Controller
{
  public function neworder()
  {
    // clear sessions when page reloads
    Session::forget('customer_name');
    Session::forget('customer_id');
    Session::forget('order_id');

    return view ('pages.todo.neworder');
  }
  public function orders()
  {
    return view ('pages.todo.orders');
  }
}
