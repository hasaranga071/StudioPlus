<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudioOrder;
use Carbon\Carbon;

class OrderSummaryController extends Controller
{
    public function index()
    {
        return view('pages.todo.ordertypesummary');
    }

    public function getOrderTypeSummary(Request $request)
    {
        // Set default date range to today
        $startDate = Carbon::today();
        $endDate = Carbon::today();

        // Get filter type from request
        $filter = $request->input('filter', 'day');

        // Apply date range based on filter
        switch ($filter) {
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            case 'day':
                $startDate = Carbon::now()->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                break;
            case 'custom':
                $startDate = Carbon::parse($request->input('start_date'));
                $endDate = Carbon::parse($request->input('end_date'));
                break;
        }

     // Fetch orders with orderType relationship
     $orderitems = StudioOrder::with('orderType')
     ->whereBetween('createdtime', [$startDate, $endDate])
     ->get()
     ->groupBy('orderType.ordertype') // Group by order type name
     ->map(function ($orders, $type) {
         return [
             'orderType' => $type,
             'total' => $orders->count(),
         ];
     })
     ->values();

 return response()->json($orderitems);
    }
    public function getOrderEarningSummary(Request $request)
    {
        // Set default date range to today
        $startDate = Carbon::today();
        $endDate = Carbon::today();

        // Get filter type from request
        $filter = $request->input('filter', 'day');

        // Apply date range based on filter
        switch ($filter) {
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            case 'day':
                $startDate = Carbon::now()->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                break;
            case 'custom':
                $startDate = Carbon::parse($request->input('start_date'));
                $endDate = Carbon::parse($request->input('end_date'));
                break;
        }

     // Fetch orders with orderType relationship
     $orderitems = StudioOrder::with('orderType')
     ->whereBetween('createdtime', [$startDate, $endDate])
     ->get()
     ->groupBy('orderType.ordertype') // Group by order type name
     ->map(function ($orders, $type) {
         return [
             'orderType' => $type,
             'earnings' => $orders->sum('totalcost'),
         ];
     })
     ->values();

 return response()->json($orderitems);
    }
}


