<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CacheController extends Controller
{
    public function store(Request $request)
    {
        // Get data from request
        $key = $request->input('key');         // e.g., 'user_name'
        $value = $request->input('value');     // e.g., 'JohnDoe'
        #$minutes = $request->input('minutes', 10); // Optional, default to 10 mins

        // Save data to cache
        #Cache::put($key, $value, now()->addMinutes($minutes));
        Cache::put($key, $value);

        return response()->json(['message' => 'Data cached successfully!']);
    }


    public function get_cached_data(Request $request)
    {
        if (Cache::has($request->input('key'))) {
            $studiokey = Cache::get($request->input('key'));
            return response()->json([$request->input('key') => $studiokey]);
        } else {
            return response()->json(['message' => 'Not found in cache.']);
        }
    }
}


