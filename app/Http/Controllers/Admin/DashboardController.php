<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ConversionHistory;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalConversions = ConversionHistory::count();
        $successfulConversions = ConversionHistory::where('status', 'success')->count();
        $failedConversions = ConversionHistory::where('status', 'failed')->count();
        $todayConversions = ConversionHistory::whereDate('created_at', Carbon::today())->count();

        return view('admin.dashboard', compact(
            'totalConversions',
            'successfulConversions',
            'failedConversions',
            'todayConversions'
        ));
    }
}