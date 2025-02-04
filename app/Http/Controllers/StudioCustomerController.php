<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudioCustomer;

class StudioCustomerController extends Controller
{
    public function store(Request $request)
    {
        // Validate the form data
        // $validated = $request->validate([
        //     'username' => 'required|string|max:255',
        //     'phonenumber' => 'required|string|max:15',
        //     'address' => 'nullable|string|max:255',
        //     'email' => 'nullable|email|unique:studiocustomers,email',
        // ]);

        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:studiocustomers,username',
            'phonenumber' => 'required|string|max:15|unique:studiocustomers,phonenumber',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:studiocustomers,email',
        ], [
            'username.unique' => 'The username is already taken.',
            'phonenumber.unique' => 'The phone number is already registered.',
            'email.unique' => 'The email address is already in use.',
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


}
