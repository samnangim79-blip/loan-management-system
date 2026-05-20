<?php
// app/Http/Controllers/CollateralController.php
namespace App\Http\Controllers;

use App\Models\Collateral;
use App\Models\LoanSchedule;
use Illuminate\Http\Request;
use App\Models\CollateralType;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CollateralController extends Controller
{
    public function index()
    {
        return view('collaterals.index');
    }

    public function getData(Request $request)
    {
        $query = Collateral::with(['loanSchedule.account.customer', 'collateralType'])
            ->select('collaterals.*');

        // Filter by collateral type
        if ($request->has('type_filter') && $request->type_filter) {
            $query->where('collateral_type_id', $request->type_filter);
        }

        // Filter by status (active/released)
        if ($request->has('status_filter') && $request->status_filter) {
            if ($request->status_filter == 'active') {
                $query->whereDoesntHave('releases');
            } elseif ($request->status_filter == 'released') {
                $query->whereHas('releases');
            }
        }

        // Filter by date range (date_issue)
        if ($request->has('date_from') && $request->date_from) {
            $query->where('date_issue', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->where('date_issue', '<=', $request->date_to);
        }

        return DataTables::of($query)
            ->addColumn('id', function ($row) {
                return $row->collateral_id;
            })
            ->addColumn('customer_name', function ($row) {
                return $row->loanSchedule->account->customer->name_en ?? 'N/A';
            })
            ->addColumn('loan_contract', function ($row) {
                return $row->loanSchedule->contract_no ?? 'N/A';
            })
            ->addColumn('type', function ($row) {
                return $row->collateralType->collateral_type ?? 'N/A';
            })
            ->addColumn('description', function ($row) {
                return $row->remarks ?? '-';
            })
            ->addColumn('value', function ($row) {
                return '$' . number_format($row->collateral_value, 2);
            })
            ->addColumn('status', function ($row) {
                if ($row->isReleased()) {
                    return '<span class="badge bg-secondary">Released</span>';
                }
                return '<span class="badge bg-success">Active</span>';
            })
            ->addColumn('action', function ($row) {
                $btn = '<div class="btn-group" role="group">';
                $btn .= '<button type="button" class="btn btn-sm btn-info view-btn" data-id="' . $row->collateral_id . '">
                    <i class="fas fa-eye"></i>
                </button>';

                if (!$row->isReleased()) {
                    $btn .= '<button type="button" class="btn btn-sm btn-primary edit-btn" data-id="' . $row->collateral_id . '">
                        <i class="fas fa-edit"></i>
                    </button>';
                    $btn .= '<button type="button" class="btn btn-sm btn-warning release-btn" data-id="' . $row->collateral_id . '">
                        <i class="fas fa-unlock"></i>
                    </button>';
                }

                $btn .= '</div>';
                return $btn;
            })
            ->order(function ($query) {
                $query->orderBy('collateral_id', 'desc');
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function create($loanId = null)
    {
        $collateralTypes = CollateralType::all();
        $loans = LoanSchedule::with('account.customer')
            ->where('os_balance', '>', 0)
            ->get();

        return view('collaterals.create', compact('collateralTypes', 'loans', 'loanId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'loan_schedule_id' => 'required|exists:loan_schedules,loan_schedule_id',
            'collateral_type_id' => 'required|exists:collateral_types,collateral_type_id',
            'collateral_value' => 'required|numeric|min:0',
            'collateral_no' => 'nullable|string|max:50',
            'date_issue' => 'required|date',
            'remarks' => 'nullable|string|max:255'
        ]);

        DB::beginTransaction();
        try {
            $collateral = Collateral::create($validated);

            // If there are additional details based on collateral type
            if ($request->has('details')) {
                foreach ($request->details as $detailId => $value) {
                    $collateral->details()->create([
                        'col_detail_id' => $detailId,
                        'col_value' => $value
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Collateral added successfully',
                'data' => $collateral
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error adding collateral: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
        $collateral = Collateral::with([
            'loanSchedule.account.customer',
            'collateralType',
            'releases'
        ])->findOrFail($id);

        // Force JSON response for AJAX requests
        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $collateral
            ]);
        }

        // Otherwise return view (for non-AJAX requests)
        return view('collaterals.show', compact('collateral'));
    }

    public function edit($id)
    {
        $collateral = Collateral::with([
            'loanSchedule.account.customer',
            'collateralType'
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $collateral
        ]);
    }

    public function update(Request $request, $id)
    {
        $collateral = Collateral::findOrFail($id);

        $validated = $request->validate([
            'loan_schedule_id' => 'required|exists:loan_schedules,loan_schedule_id',
            'collateral_type_id' => 'required|exists:collateral_types,collateral_type_id',
            'collateral_value' => 'required|numeric|min:0',
            'collateral_no' => 'nullable|string|max:50',
            'date_issue' => 'nullable|date',
            'remarks' => 'nullable|string|max:255'
        ]);

        $validated['modify_by'] = Auth::id() ?? 1;
        $validated['modify_date'] = now();

        DB::beginTransaction();
        try {
            $collateral->update($validated);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Collateral updated successfully',
                'data' => $collateral
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error updating collateral: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $collateral = Collateral::findOrFail($id);

        // Check if collateral is released
        if ($collateral->isReleased()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete released collateral'
            ], 400);
        }

        // Check if there are any related records that prevent deletion
        if ($collateral->releases()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete collateral with existing release records'
            ], 400);
        }

        DB::beginTransaction();
        try {
            $collateral->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Collateral deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error deleting collateral: ' . $e->getMessage()
            ], 500);
        }
    }

    public function release(Request $request, $id)
    {
        $collateral = Collateral::findOrFail($id);

        if ($collateral->isReleased()) {
            return response()->json([
                'success' => false,
                'message' => 'Collateral is already released'
            ], 400);
        }

        // Check if loan is fully paid
        $loan = $collateral->loanSchedule;
        if ($loan->os_balance > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot release collateral. Loan still has outstanding balance.'
            ], 400);
        }

        DB::beginTransaction();
        try {
            $collateral->releases()->create([
                'tran_date' => now(),
                'release_by' => Auth::id() ?? 1,
                'release_date' => now(),
                'approved_by' => $request->approved_by ?? Auth::id() ?? 1,
                'approved_date' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Collateral released successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error releasing collateral: ' . $e->getMessage()
            ], 500);
        }
    }

    public function summary()
    {
        try {
            // Get collateral statistics
            $totalCollaterals = Collateral::count();
            $totalValue = Collateral::sum('collateral_value');

            // Get collaterals by type
            $collateralsByType = Collateral::with('collateralType')
                ->selectRaw('collateral_type_id, COUNT(*) as count, SUM(collateral_value) as total_value')
                ->groupBy('collateral_type_id')
                ->get()
                ->map(function ($item) {
                    return [
                        'type' => $item->collateralType->collateral_type ?? 'Unknown',
                        'count' => $item->count,
                        'total_value' => $item->total_value
                    ];
                });

            // Get recent collaterals
            $recentCollaterals = Collateral::with(['loanSchedule.account.customer', 'collateralType'])
                ->latest('collateral_id')
                ->limit(5)
                ->get();

            // Get collaterals by status (active/released)
            $statusStats = [
                'active' => Collateral::whereDoesntHave('releases')->count(),
                'released' => Collateral::whereHas('releases')->count()
            ];

            $data = [
                'total_collaterals' => $totalCollaterals,
                'total_value' => $totalValue,
                'collaterals_by_type' => $collateralsByType,
                'recent_collaterals' => $recentCollaterals,
                'status_stats' => $statusStats
            ];

            return view('collaterals.summary', compact('data'));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading summary: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getCollateralTypes()
    {
        try {
            $types = CollateralType::select('collateral_type_id', 'collateral_type')
                ->orderBy('collateral_type')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $types
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading collateral types: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getActiveLoans()
    {
        try {
            $loans = LoanSchedule::with(['account.customer'])
                ->where('os_balance', '>', 0)
                ->orderBy('contract_no')
                ->get()
                ->map(function ($loan) {
                    return [
                        'loan_schedule_id' => $loan->loan_schedule_id,
                        'contract_no' => $loan->contract_no,
                        'customer_name' => $loan->account->customer->name_en ?? 'N/A',
                        'label' => $loan->contract_no . ' - ' . ($loan->account->customer->name_en ?? 'N/A')
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $loans
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading active loans: ' . $e->getMessage()
            ], 500);
        }
    }
}
