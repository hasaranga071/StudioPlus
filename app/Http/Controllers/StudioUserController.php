<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\StudioUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;


class StudioUserController extends Controller
{
  public function neworder()
  {
    return view ('pages.todo.neworder');
  }

  public function create(): View
    {
        return view('auth.register');
    }


  public function store(Request $request)
  {
      // Validate the request
    $validatedData = $request->validate([
      'username' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:StudioUsers',
      'password' => 'required|string|min:6',
      'phonenumber' => 'required|string|max:255',
      'address' => 'nullable|string|max:255',
  ]);

  // Save the data with hardcoded values
  StudioUser::create([
    //'studiokey' => session('studiokey'), // Set studiokey from session
    'username' => $request->username,
    'email' => $request->email,
    'password' => Hash::make($request->password),
    'phonenumber' => $request->phonenumber,
    'address' => $request->address,
    'isactive' => 1,
]);

  return redirect()->route('login')->with('status', 'Registration successful. Please log in.');
  }
}


