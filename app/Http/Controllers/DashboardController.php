<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\LoanSchedule;
use App\Models\AccountInfo;
use App\Models\Trans;
use Illuminate\Http\Request;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        $statistics = [
            'total_customers' => Customer::count(),
            'total_accounts' => AccountInfo::count(),
            'active_loans' => LoanSchedule::where('os_balance', '>', 0)->count(),
            'total_disbursed' => LoanSchedule::sum('amount'),
            'outstanding_balance' => LoanSchedule::sum('os_balance'),
            'today_payments' => Trans::whereDate('tran_date', today())->sum('amount')
        ];

        // Get recent transactions
        $recentTransactions = Trans::with(['user', 'glMap'])
            ->orderBy('tran_id', 'desc')
            ->limit(10)
            ->get();

        // Get loans due today
        $loansDueToday = LoanSchedule::with('account.customer')
            ->whereDate('next_pay_date', today())
            ->get();

        return view('dashboard', compact('statistics', 'recentTransactions', 'loansDueToday'));
    }
}
