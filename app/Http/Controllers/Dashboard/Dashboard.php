<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class Dashboard extends Controller
{
    public function Afghanistan_map()
    {
        return view('Dashboard-system.Afghanistan_map');
    }


    public function Afghanistan_map_data()
    {
        $map_data = DB::connection('scale_system')->table('scale_map')
            ->select(
                'scale_name',
                'scale_image',
                'scale_model',
                'scale_company',
                'transferred_mineral',
                'location',
                'status',
                'scale_employee',
                'employee_phone',
                'description',
                'latitude',
                'longitude'
            )->get();

        return response()->json(['data' => $map_data]); // Return JSON response
    }
}
