<?php

namespace App\Http\Controllers;

use App\Models\AccountInfo;
use App\Models\PurposeLoan;
use App\Models\LoanSchedule;
use Illuminate\Http\Request;
use App\Models\PaymentFrequency;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    public function index()
    {
        return view('loans.index');
    }

    public function getData()
    {
        $loans = LoanSchedule::with(['account.customer', 'frequency', 'purpose'])
            ->select('loan_schedules.*');

        return DataTables::of($loans)
            ->addColumn('customer_name', function ($row) {
                return $row->account->customer->name_en ?? 'N/A';
            })
            ->addColumn('status', function ($row) {
                $percentage = $row->os_balance > 0 ?
                    (($row->amount - $row->os_balance) / $row->amount) * 100 : 100;

                $color = $percentage >= 100 ? 'success' : ($percentage >= 50 ? 'warning' : 'danger');

                return '<div class="progress">
                    <div class="progress-bar bg-' . $color . '" style="width: ' . $percentage . '%">
                        ' . number_format($percentage, 1) . '%
                    </div>
                </div>';
            })
            ->addColumn('action', function ($row) {
                $btn = '<div class="btn-group" role="group">';
                $btn .= '<button type="button" class="btn btn-sm btn-info view-loan" data-id="' . $row->loan_schedule_id . '">
                    <i class="fas fa-eye"></i>
                </button>';
                $btn .= '<button type="button" class="btn btn-sm btn-primary edit-loan" data-id="' . $row->loan_schedule_id . '">
                    <i class="fas fa-edit"></i>
                </button>';
                $btn .= '<button type="button" class="btn btn-sm btn-success payment-btn" data-id="' . $row->loan_schedule_id . '">
                    <i class="fas fa-dollar-sign"></i>
                </button>';
                $btn .= '<button type="button" class="btn btn-sm btn-warning schedule-btn" data-id="' . $row->loan_schedule_id . '">
                    <i class="fas fa-calendar"></i>
                </button>';
                $btn .= '</div>';
                return $btn;
            })
            ->editColumn('amount', function ($row) {
                return '$' . number_format($row->amount, 2);
            })
            ->editColumn('os_balance', function ($row) {
                return '$' . number_format($row->os_balance, 2);
            })
            ->editColumn('int_rate', function ($row) {
                return $row->int_rate . '%';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function create()
    {
        $accounts = AccountInfo::with('customer')->where('account_status', 1)->get();
        $frequencies = PaymentFrequency::all();
        $purposes = PurposeLoan::all();

        return view('loans.create', compact('accounts', 'frequencies', 'purposes'));
    }

    public function store(Request $request)
    {
        // Debug: Log the received frequency_id
        Log::info('Loan creation attempt with frequency_id: ' . $request->input('frequency_id'));

        $validated = $request->validate([
            'contract_no' => 'nullable|unique:loan_schedules,contract_no',
            'acct_id' => 'required|exists:account_infos,acct_id',
            'date_issue' => 'required|date',
            'frequency_id' => 'required|exists:payment_frequencys,frequency_id',
            'tenor' => 'required|integer|min:1',
            'amount' => 'required|numeric|min:0',
            'int_rate' => 'required|numeric|min:0|max:100',
            'interest_mode' => 'required|in:0,1,2',
            'payment_mode' => 'required|in:0,1,2',
            'purpose_id' => 'required|exists:purpose_loans,purpose_id'
        ]);

        DB::beginTransaction();
        try {
            // Generate contract number if not provided
            if (empty($validated['contract_no'])) {
                $validated['contract_no'] = $this->generateContractNumber();
            }

            // Set initial values
            $validated['os_balance'] = $validated['amount'];
            $validated['user_id'] = Auth::id() ?? 1;
            $validated['next_pay_date'] = $this->calculateNextPaymentDate(
                $validated['date_issue'],
                $validated['frequency_id']
            );

            // Calculate end payment date
            $validated['end_pay_date'] = $this->calculateEndPaymentDate(
                $validated['date_issue'],
                $validated['frequency_id'],
                $validated['tenor']
            );

            $loan = LoanSchedule::create($validated);

            // Generate payment schedule
            $this->generatePaymentSchedule($loan);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Loan created successfully',
                'data' => $loan
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error creating loan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $loan = LoanSchedule::with([
            'account.customer',
            'frequency',
            'purpose',
            'collaterals',
            'transactions'
        ])->findOrFail($id);

        // Return JSON for AJAX requests
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'loan_schedule_id' => $loan->loan_schedule_id,
                'contract_no' => $loan->contract_no,
                'amount' => $loan->amount,
                'os_balance' => $loan->os_balance,
                'int_rate' => $loan->int_rate,
                'date_issue' => $loan->date_issue,
                'next_pay_date' => $loan->next_pay_date,
                'customer_name' => $loan->account->customer->name_en ?? 'N/A',
                'account_no' => $loan->account->acct_no ?? 'N/A'
            ]);
        }

        return view('loans.show', compact('loan'));
    }

    public function getPaymentSchedule($id)
    {
        $loan = LoanSchedule::findOrFail($id);
        $schedule = $this->generatePaymentSchedule($loan, false);

        return response()->json($schedule);
    }

    private function generateContractNumber()
    {
        $year = date('y');
        $lastLoan = LoanSchedule::whereYear('date_issue', $year)
            ->orderBy('loan_schedule_id', 'desc')
            ->first();

        $sequence = $lastLoan ?
            intval(substr($lastLoan->contract_no, -5)) + 1 : 1;

        return 'ln' . $year . str_pad($sequence, 5, '0', STR_PAD_LEFT);
    }

    private function calculateNextPaymentDate($startDate, $frequencyId)
    {
        $frequency = PaymentFrequency::find($frequencyId);

        if (!$frequency) {
            throw new \Exception("Payment frequency with ID {$frequencyId} not found");
        }

        $date = \Carbon\Carbon::parse($startDate);

        return $date->addDays($frequency->num_days)->format('Y-m-d');
    }

    private function calculateEndPaymentDate($startDate, $frequencyId, $tenor)
    {
        $frequency = PaymentFrequency::find($frequencyId);

        if (!$frequency) {
            throw new \Exception("Payment frequency with ID {$frequencyId} not found");
        }

        $date = \Carbon\Carbon::parse($startDate);

        return $date->addDays($frequency->num_days * $tenor)->format('Y-m-d');
    }

    private function calculateInterest($loan, $paymentDate)
    {
        $daysSinceLastPayment = \Carbon\Carbon::parse($loan->last_pay_date ?? $loan->date_issue)
            ->diffInDays(\Carbon\Carbon::parse($paymentDate));

        $dailyInterestRate = $loan->int_rate / 365 / 100;

        return $loan->os_balance * $dailyInterestRate * $daysSinceLastPayment;
    }

    private function generatePaymentSchedule($loan, $save = true)
    {
        $schedule = [];
        $balance = $loan->amount;
        $paymentDate = \Carbon\Carbon::parse($loan->date_issue);
        $frequency = PaymentFrequency::find($loan->frequency_id);

        if (!$frequency) {
            throw new \Exception("Payment frequency with ID {$loan->frequency_id} not found");
        }

        // Calculate monthly payment (simplified)
        $monthlyRate = $loan->int_rate / 12 / 100;
        $monthlyPayment = $loan->amount *
            ($monthlyRate * pow(1 + $monthlyRate, $loan->tenor)) /
            (pow(1 + $monthlyRate, $loan->tenor) - 1);

        for ($i = 1; $i <= $loan->tenor; $i++) {
            $paymentDate->addDays($frequency->num_days);
            $interest = $balance * $monthlyRate;
            $principal = $monthlyPayment - $interest;
            $balance -= $principal;

            $schedule[] = [
                'payment_no' => $i,
                'payment_date' => $paymentDate->format('Y-m-d'),
                'principal' => round($principal, 2),
                'interest' => round($interest, 2),
                'total_payment' => round($monthlyPayment, 2),
                'balance' => round($balance, 2)
            ];
        }

        return $schedule;
    }

    /**
     * Display loan statistics dashboard
     */
    public function statistics()
    {
        // Total loans count by status
        $totalLoans = LoanSchedule::count();
        $activeLoans = LoanSchedule::where('os_balance', '>', 0)->count();
        $completedLoans = LoanSchedule::where('os_balance', '<=', 0)->count();

        // Financial totals
        $totalDisbursed = LoanSchedule::sum('amount');
        $totalOutstanding = LoanSchedule::sum('os_balance');
        $totalCollected = $totalDisbursed - $totalOutstanding;

        // Loans by purpose
        $loansByPurpose = LoanSchedule::select('purpose_id', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total_amount'))
            ->whereNotNull('purpose_id')
            ->groupBy('purpose_id')
            ->with('purpose')
            ->get()
            ->map(function ($item) {
                return [
                    'purpose' => $item->purpose->purpose_type ?? 'Unknown',
                    'count' => $item->count,
                    'total_amount' => $item->total_amount
                ];
            });

        // Loans by frequency
        $loansByFrequency = LoanSchedule::select('frequency_id', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total_amount'))
            ->whereNotNull('frequency_id')
            ->groupBy('frequency_id')
            ->with('frequency')
            ->get()
            ->map(function ($item) {
                return [
                    'frequency' => $item->frequency->frequency ?? 'Unknown',
                    'count' => $item->count,
                    'total_amount' => $item->total_amount
                ];
            });

        // Monthly disbursement trend (last 12 months)
        $monthlyDisbursements = LoanSchedule::select(
            DB::raw("DATE_FORMAT(date_issue, '%Y-%m') as month"),
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(amount) as total_amount')
        )
            ->where('date_issue', '>=', now()->subMonths(12))
            ->whereNotNull('date_issue')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Average loan metrics
        $avgLoanAmount = LoanSchedule::avg('amount') ?? 0;
        $avgInterestRate = LoanSchedule::avg('int_rate') ?? 0;
        $avgTenor = LoanSchedule::avg('tenor') ?? 0;

        // Collection rate
        $collectionRate = $totalDisbursed > 0 ? ($totalCollected / $totalDisbursed) * 100 : 0;

        // Return JSON response for AJAX calls
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'totalLoans' => $totalLoans,
                    'activeLoans' => $activeLoans,
                    'completedLoans' => $completedLoans,
                    'totalDisbursed' => number_format($totalDisbursed, 2),
                    'totalOutstanding' => number_format($totalOutstanding, 2),
                    'totalCollected' => number_format($totalCollected, 2),
                    'loansByPurpose' => $loansByPurpose,
                    'loansByFrequency' => $loansByFrequency,
                    'monthlyDisbursements' => $monthlyDisbursements,
                    'avgLoanAmount' => number_format($avgLoanAmount, 2),
                    'avgInterestRate' => number_format($avgInterestRate, 2),
                    'avgTenor' => $avgTenor,
                    'collectionRate' => number_format($collectionRate, 2)
                ]
            ]);
        }

        return view('loans.statistics', compact(
            'totalLoans',
            'activeLoans',
            'completedLoans',
            'totalDisbursed',
            'totalOutstanding',
            'totalCollected',
            'loansByPurpose',
            'loansByFrequency',
            'monthlyDisbursements',
            'avgLoanAmount',
            'avgInterestRate',
            'avgTenor',
            'collectionRate'
        ));
    }

    public function edit($id)
    {
        $loan = LoanSchedule::with(['account.customer', 'frequency', 'purpose'])->findOrFail($id);
        $accounts = AccountInfo::with('customer')->get();
        $frequencies = PaymentFrequency::all();
        $purposes = PurposeLoan::all();

        return view('loans.edit', compact('loan', 'accounts', 'frequencies', 'purposes'));
    }

    public function update(Request $request, $id)
    {
        $loan = LoanSchedule::findOrFail($id);

        $validated = $request->validate([
            'contract_no' => 'required|unique:loan_schedules,contract_no,' . $id . ',loan_schedule_id',
            'acct_id' => 'required|exists:account_infos,acct_id',
            'date_issue' => 'required|date',
            'frequency_id' => 'required|exists:payment_frequencys,frequency_id',
            'tenor' => 'required|integer|min:1',
            'amount' => 'required|numeric|min:0',
            'int_rate' => 'required|numeric|min:0|max:100',
            'interest_mode' => 'required|in:0,1,2',
            'payment_mode' => 'required|in:0,1,2',
            'purpose_id' => 'required|exists:purpose_loans,purpose_id'
        ]);

        DB::beginTransaction();
        try {
            // Recalculate dates if frequency or date changed
            if ($request->frequency_id != $loan->frequency_id || $request->date_issue != $loan->date_issue) {
                $validated['next_pay_date'] = $this->calculateNextPaymentDate(
                    $validated['date_issue'],
                    $validated['frequency_id']
                );
                $validated['end_pay_date'] = $this->calculateEndPaymentDate(
                    $validated['date_issue'],
                    $validated['frequency_id'],
                    $validated['tenor']
                );
            }

            $loan->update($validated);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Loan updated successfully',
                    'data' => $loan
                ]);
            }

            return redirect()->route('loans.index')->with('success', 'Loan updated successfully');
        } catch (\Exception $e) {
            DB::rollback();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating loan: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Error updating loan: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $loan = LoanSchedule::findOrFail($id);

            // Check if loan has any payments
            if ($loan->os_balance < $loan->amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete loan with existing payments'
                ], 400);
            }

            DB::beginTransaction();

            // Delete related collaterals first
            $loan->collaterals()->delete();

            // Delete loan
            $loan->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Loan deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error deleting loan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function makePayment(Request $request, $id)
    {
        $loan = LoanSchedule::findOrFail($id);

        $validated = $request->validate([
            'payment_amount' => 'required|numeric|min:0.01|max:' . $loan->os_balance,
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,transfer,check'
        ]);

        DB::beginTransaction();
        try {
            // Update loan balance
            $loan->os_balance -= $validated['payment_amount'];
            $loan->last_pay_date = $validated['payment_date'];

            // Calculate next payment date if not fully paid
            if ($loan->os_balance > 0) {
                $loan->next_pay_date = $this->calculateNextPaymentDate(
                    $validated['payment_date'],
                    $loan->frequency_id
                );
            } else {
                $loan->next_pay_date = null;
            }

            $loan->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment processed successfully',
                'remaining_balance' => $loan->os_balance
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error processing payment: ' . $e->getMessage()
            ], 500);
        }
    }
}
