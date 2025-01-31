<?php

namespace App\Http\Controllers;

#use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudioUserController extends Controller
{
  public function neworder()
  {
    return view ('pages.todo.neworder');
  }

  public function store(Request $request)
  {
      // Validate the request
    $validatedData = $request->validate([
      'username' => 'required|string|max:255',
      'usertypekey' => 'required|integer',
      'email' => 'required|string|email|max:255|unique:StudioUsers',
      'password' => 'required|string|min:6',
      'phonenumber' => 'required|string|max:255',
      'address' => 'nullable|string|max:255',
      'profileimage' => 'nullable|string|max:255',
  ]);

  // Save the data with hardcoded values
  $user = StudioUser::create([
      'studiokey' => 1, // Hardcoded value
      'username' => $validatedData['username'],
      'usertypekey' => $validatedData['usertypekey'],
      'email' => $validatedData['email'],
      'password' => Hash::make($validatedData['password']),
      'rolekey' => 1, // Hardcoded value
      'phonenumber' => $validatedData['phonenumber'],
      'address' => $validatedData['address'] ?? null,
      'isactive' => 1, // Hardcoded value
      'profileimage' => $validatedData['profileimage'] ?? null,
  ]);

  return redirect()->back()->with('success', 'User created successfully!');
  }
}


