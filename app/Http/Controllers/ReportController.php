<?php

namespace App\Http\Controllers;

use App\Models\LoanSchedule;
use App\Models\Trans;
use App\Models\Customer;
use App\Models\AccountInfo;
use App\Models\LoanTran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function loanReport(Request $request)
    {
        $query = LoanSchedule::with(['account.customer', 'frequency', 'purpose']);

        // Apply filters
        if ($request->has('date_from')) {
            $query->where('date_issue', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->where('date_issue', '<=', $request->date_to);
        }

        if ($request->has('status')) {
            if ($request->status == 'active') {
                $query->where('os_balance', '>', 0);
            } elseif ($request->status == 'closed') {
                $query->where('os_balance', '=', 0);
            }
        }

        $loans = $query->orderBy('date_issue', 'desc')->get();

        $summary = [
            'total_loans' => $loans->count(),
            'total_disbursed' => $loans->sum('amount'),
            'outstanding_balance' => $loans->sum('os_balance'),
            'average_interest_rate' => $loans->avg('int_rate'),
            'paid_amount' => $loans->sum('amount') - $loans->sum('os_balance')
        ];

        // PDF/Excel export can be added when packages are installed
        // For now, return JSON for API or view for web
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'summary' => $summary,
                'data' => $loans
            ]);
        }

        return view('reports.loans', compact('loans', 'summary'));
    }

    public function paymentReport(Request $request)
    {
        $query = Trans::with(['user', 'branch', 'currency'])
            ->whereIn('tran_type', [Trans::TYPE_LOAN_PAYMENT]);

        // Apply filters
        if ($request->has('date_from')) {
            $query->where('tran_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->where('tran_date', '<=', $request->date_to);
        }

        if ($request->has('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        $payments = $query->orderBy('tran_date', 'desc')->get();

        $summary = [
            'total_payments' => $payments->count(),
            'total_amount' => $payments->sum('amount'),
            'by_currency' => $payments->groupBy('ccy_id')->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'amount' => $group->sum('amount')
                ];
            })
        ];

        return view('reports.payments', compact('payments', 'summary'));
    }

    public function customerReport(Request $request)
    {
        $query = Customer::with(['accounts', 'village.commune.district.province']);

        // Apply filters
        if ($request->has('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->has('village_id')) {
            $query->where('village_id', $request->village_id);
        }

        $customers = $query->get();

        $summary = [
            'total_customers' => $customers->count(),
            'by_gender' => $customers->groupBy('gender')->map->count(),
            'by_marital_status' => $customers->groupBy('marital_status')->map->count(),
            'with_active_loans' => $customers->filter(function ($customer) {
                return $customer->accounts->flatMap->loans->where('os_balance', '>', 0)->count() > 0;
            })->count()
        ];

        return view('reports.customers', compact('customers', 'summary'));
    }

    public function dailyReport(Request $request)
    {
        $date = $request->date ?? today();

        // Get all transactions for the day
        $transactions = Trans::with(['user', 'currency'])
            ->whereDate('tran_date', $date)
            ->get();

        // Group by transaction type
        $byType = $transactions->groupBy('tran_type')->map(function ($group) {
            return [
                'count' => $group->count(),
                'amount' => $group->sum('amount')
            ];
        });

        // New loans disbursed
        $newLoans = LoanSchedule::whereDate('date_issue', $date)->get();

        // Loan payments received
        $loanPayments = LoanTran::with('loanSchedule')
            ->whereHas('transaction', function ($q) use ($date) {
                $q->whereDate('tran_date', $date);
            })
            ->where('loan_tran_type_id', LoanTran::TYPE_PAYMENT)
            ->get();

        $summary = [
            'total_transactions' => $transactions->count(),
            'total_amount' => $transactions->sum('amount'),
            'deposits' => $byType[Trans::TYPE_DEPOSIT] ?? ['count' => 0, 'amount' => 0],
            'withdrawals' => $byType[Trans::TYPE_WITHDRAWAL] ?? ['count' => 0, 'amount' => 0],
            'new_loans' => [
                'count' => $newLoans->count(),
                'amount' => $newLoans->sum('amount')
            ],
            'loan_payments' => [
                'count' => $loanPayments->count(),
                'amount' => $loanPayments->sum('amount')
            ]
        ];

        return view('reports.daily', compact('date', 'transactions', 'summary'));
    }

    public function outstandingLoans(Request $request)
    {
        $query = LoanSchedule::with(['account.customer', 'collaterals'])
            ->where('os_balance', '>', 0);

        // Filter overdue loans
        if ($request->has('overdue')) {
            $query->where('next_pay_date', '<', today());
        }

        $loans = $query->orderBy('os_balance', 'desc')->get();

        // Group by risk level
        $byRisk = [
            'current' => $loans->where('next_pay_date', '>=', today()),
            'overdue_30' => $loans->where('next_pay_date', '<', today())
                ->where('next_pay_date', '>=', today()->subDays(30)),
            'overdue_60' => $loans->where('next_pay_date', '<', today()->subDays(30))
                ->where('next_pay_date', '>=', today()->subDays(60)),
            'overdue_90_plus' => $loans->where('next_pay_date', '<', today()->subDays(60))
        ];

        $summary = [
            'total_outstanding' => $loans->sum('os_balance'),
            'total_loans' => $loans->count(),
            'current_amount' => $byRisk['current']->sum('os_balance'),
            'overdue_30_amount' => $byRisk['overdue_30']->sum('os_balance'),
            'overdue_60_amount' => $byRisk['overdue_60']->sum('os_balance'),
            'overdue_90_plus_amount' => $byRisk['overdue_90_plus']->sum('os_balance')
        ];

        return view('reports.outstanding-loans', compact('loans', 'byRisk', 'summary'));
    }
}
