<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudioCustomer;
use Illuminate\Support\Facades\Session;


class StudioCustomerController extends Controller
{
    public function store(Request $request)
    {
        $validator = validator($request->all(), [
            'username' => 'required|string|max:255|unique:studiocustomers,username',
            'phonenumber' => 'required|string|max:15|unique:studiocustomers,phonenumber',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:studiocustomers,email',
        ], [
            'username.unique' => 'The username is already taken.',
            'phonenumber.unique' => 'The phone number is already registered.',
            'email.unique' => 'The email address is already in use.',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $customer = StudioCustomer::create([
                'username' => $request->username,
                'phonenumber' => $request->phonenumber,
                'address' => $request->address_text ?? '',
                'email' => $request->email ?? null,
                'studiokey' => 1,
                'createdtime' => now(),
            ]);

           // Store customer details in the session with primary key
    Session::put('customer_id', $customer->id);  // Store primary key
    Session::put('customer_name', $customer->username);
    Session::put('customer_key', 'CUST-' . $customer->id); // Example key format

            if ($request->ajax()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Customer registered successfully!',
                    'customer' => $customer
                ]);
            }

            return redirect()->back()->with('success', 'Customer registered successfully!');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Error registering customer'
                ], 500);
            }
            return redirect()->back()->with('error', 'Error registering customer')->withInput();
        }
    }

    public function search(Request $request)
    {
        $query = StudioCustomer::query();

        if ($request->username) {
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

    public function getCustomerSession()
    {
        return response()->json([
            'customer_name' => Session::get('customer_name'),
            'customer_id' => Session::get('customer_id')
        ]);
    }

    public function setCustomerSession(Request $request)
        {
            Session::put('customer_id', $request->customer_id);
            Session::put('customer_name', $request->customer_name);
            Session::put('customer_key', 'CUST-' . $request->customer_id); // Example format

            return response()->json(['status' => 'success', 'message' => 'Customer session updated']);
        }

    public function setOrderSession(Request $request)
        {
            // Generate Order ID with timestamp
            $orderId = 'ORD-' . now()->format('YmdHis'); // Example: ORD-20240206153045

            // Store in session
            Session::put('order_id', $orderId);

            return response()->json([
                'status' => 'success',
                'order_id' => $orderId,
                'message' => 'Order session updated'
            ]);
        }


}
