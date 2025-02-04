<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudioCustomer;

class StudioCustomerController extends Controller
{
    public function store(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'phonenumber' => 'required|string|max:15',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:studiocustomers,email',
        ]);

        // Save the customer information
        StudioCustomer::create([
            'username' => $validated['username'],
            'phonenumber' => $validated['phonenumber'],
            'address' => $validated['address'] ?? '',
            'email' => $validated['email'] ?? null,
            'studiokey' => 1, // Replace with the appropriate studio key if applicable
            'createdtime' => now(),
        ]);

        // Redirect or return response
        return redirect()->back()->with('success', 'Customer registered successfully!');
    }

    public function neworder()
  {
    return view ('pages.todo.neworder');
  }
}
