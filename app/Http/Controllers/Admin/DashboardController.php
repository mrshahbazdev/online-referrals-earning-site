<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvestmentRequest;
use App\Models\KycSubmission;
use App\Models\User;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch real data from the database
        // Exclude admins from the total user count
        $totalUsers = User::where('role', '!=', 'admin')->count();
        $pendingKyc = KycSubmission::where('status', 'pending')->count();
        $pendingWithdrawals = WithdrawalRequest::where('status', 'pending')->count();

        // Calculate revenue from approved investment requests
        $totalRevenue = InvestmentRequest::where('status', 'approved')->sum('amount');

        // --- New Chart Data Logic ---
        $registrations = User::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->where('role', '!=', 'admin') // Also exclude admins from the chart
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $chartLabels = [];
        $chartData = [];
        // Prepare the last 7 days for the chart
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = Carbon::parse($date)->format('D'); // e.g., 'Mon'

            // Find the registration count for this date, or default to 0
            $registration = $registrations->firstWhere('date', $date);
            $chartData[] = $registration ? $registration->count : 0;
        }
        // --- End of New Chart Data Logic ---

        // Pass all data to the view
        return view('admin.dashboard', compact(
            'totalUsers',
            'pendingKyc',
            'pendingWithdrawals',
            'totalRevenue',
            'chartLabels',
            'chartData'
        ));
    }
}
