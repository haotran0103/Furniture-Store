<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserActivityLog;

class DashboardController extends Controller
{
    public function getData(Request $request)
    {
        $logs = UserActivityLog::all();

        $userActivityCounts = $logs->groupBy('user_id')->map->count();

        $labels = $userActivityCounts->keys()->toArray();
        $values = $userActivityCounts->values()->toArray();
    
        return response()->json([
            'labels' => $labels,
            'values' => $values,
        ]);
    }
}
