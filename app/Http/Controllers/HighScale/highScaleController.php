<?php

namespace App\Http\Controllers\HighScale;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use PDF;


class highScaleController extends Controller
{
    public function dashboard()
    {

        return view('high-scale-system.high_scale_dashboard');
    }

    public function addScale()
    {

        return view('high-scale-system.add_scale');
    }
    public function report()
    {

        return view('high-scale-system.high_scale_report');
    }
    public function userProfile()
    {

        return view('Auth.user_profile');
    }
    public function changePassword()
    {

        return view('Auth.fl_change_password');
    }
    public function exportPdf(Request $request)
    {
        // Retrieve the data from the session
        $weights = session('export_weights_data');

        // Generate and stream the PDF
        $pdf = PDF::loadView('Exports.weight_pdf', ['weights' => $weights]);

        return $pdf->stream('weights_report.pdf');
    }
    public function printExport(Request $request)
    {
        // Retrieve the data from the session
        $weights = session('export_weights_data');

        return view('high-scale-system.print_export', [
            'weights' => $weights
        ]);
    }
}
