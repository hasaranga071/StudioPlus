<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudioOrder;
use App\Models\StudioOrderItemMapSS;
use App\Models\StudioOrderItemMapEC;
use App\Models\StudioOrderItemMapME;
use App\Models\StudioOrderItemMapFR;
use Carbon\Carbon;

class OrderSummaryController extends Controller
{
    public function index()
    {
        return view('pages.todo.ordertypesummary');
    }

public function getOrderTypeSummary(Request $request)
{


    $startDate = Carbon::today();
    $endDate = Carbon::today();

    $filter = $request->input('filter', 'day');

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

    $models = [
        'Studio Sittings' => \App\Models\StudioOrderItemMapSS::class,
        'Extra Copy'      => \App\Models\StudioOrderItemMapEC::class,
        'Media'           => \App\Models\StudioOrderItemMapME::class,
        'Frames'          => \App\Models\StudioOrderItemMapFR::class,
    ];

    $summary = [];

    foreach ($models as $type => $modelClass) {
        $count = $modelClass::whereHas('order', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('createdtime', [$startDate, $endDate]);
        })->count();

        $summary[] = [
            'orderType' => $type,
            'total'     => $count,
        ];
    }

    return response()->json($summary);
}

    public function order()
        {
            return $this->belongsTo(\App\Models\StudioOrder::class, 'orderkey', 'orderkey');
        }
    
    public function getOrderEarningSummary(Request $request)
    {


        $startDate = Carbon::today();
        $endDate = Carbon::today();

        $filter = $request->input('filter', 'day');

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

        // Map order types to their models
        $models = [
            'Studio Sittings' => \App\Models\StudioOrderItemMapSS::class,
            'Extra Copy'      => \App\Models\StudioOrderItemMapEC::class,
            'Media'           => \App\Models\StudioOrderItemMapME::class,
            'Frames'          => \App\Models\StudioOrderItemMapFR::class,
        ];

        $summary = [];

        foreach ($models as $type => $modelClass) {
            $orderkeys = $modelClass::pluck('orderkey')->unique();

            $earnings = StudioOrder::whereIn('orderkey', $orderkeys)
                ->whereBetween('createdtime', [$startDate, $endDate])
                ->sum('totalcost');

            $summary[] = [
                'orderType' => $type,
                'earnings'  => $earnings,
            ];
        }

        return response()->json($summary);
    }

}



