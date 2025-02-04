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
}
