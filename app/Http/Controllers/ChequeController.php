<?php

namespace App\Http\Controllers;

use DB;
use App\Models\ChequeStop;
use App\Models\AccountInfo;
use App\Models\ChequeClear;
use App\Models\ChequeIssue;
use App\Models\ChequeIssued;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\ChequeMaintenance;
use Illuminate\Support\Facades\Auth;

class ChequeController extends Controller
{
  // Cheque Issues
  public function index()
  {
    return view('cheques.index');
  }

  public function getIssuesData(Request $request)
  {
    $query = ChequeIssue::with('account.customer')->select('cheque_issues.*');

    // Filter by status
    if ($request->has('status') && $request->status !== '') {
      $query->where('status', $request->status);
    }

    // Filter by date range
    if ($request->has('date_from') && $request->date_from) {
      $query->whereDate('issue_date', '>=', $request->date_from);
    }

    if ($request->has('date_to') && $request->date_to) {
      $query->whereDate('issue_date', '<=', $request->date_to);
    }

    return DataTables::of($query)
      ->addColumn('customer_name', function ($row) {
        return $row->account->customer->name_en ?? 'N/A';
      })
      ->addColumn('account_no', function ($row) {
        return $row->account->acct_no ?? 'N/A';
      })
      ->addColumn('status_badge', function ($row) {
        $statuses = [
          0 => '<span class="badge bg-warning">Pending</span>',
          1 => '<span class="badge bg-success">Approved</span>',
          2 => '<span class="badge bg-danger">Rejected</span>'
        ];
        return $statuses[$row->status] ?? '<span class="badge bg-secondary">Unknown</span>';
      })
      ->addColumn('action', function ($row) {
        $btn = '<div class="btn-group" role="group">';
        $btn .= '<button type="button" class="btn btn-sm btn-info view-btn" data-id="' . $row->chq_issue_id . '"><i class="fas fa-eye"></i></button>';
        if ($row->status == 0) {
          $btn .= '<button type="button" class="btn btn-sm btn-success approve-btn" data-id="' . $row->chq_issue_id . '"><i class="fas fa-check"></i></button>';
        }
        $btn .= '</div>';
        return $btn;
      })
      ->rawColumns(['status_badge', 'action'])
      ->make(true);
  }

  public function storeIssue(Request $request)
  {
    $validated = $request->validate([
      'acct_id' => 'required|exists:account_infos,acct_id',
      'chq_from_no' => 'required|string|max:255',
      'chq_to_no' => 'required|string|max:255'
    ]);

    $validated['status'] = 0; // Pending
    $validated['issue_by'] = Auth::id() ?? 1;
    $validated['issue_date'] = now();

    $issue = ChequeIssue::create($validated);

    return response()->json([
      'success' => true,
      'message' => 'Cheque issue request created successfully',
      'data' => $issue
    ]);
  }

  public function approveIssue($id)
  {
    $issue = ChequeIssue::findOrFail($id);

    $issue->update([
      'status' => 1,
      'approved_by' => Auth::id() ?? 1,
      'approved_date' => now()
    ]);

    // Create issued cheques
    ChequeIssued::create([
      'chq_issued_id' => time(),
      'acct_id' => $issue->acct_id,
      'chq_no_from' => $issue->chq_from_no,
      'chq_no_to' => $issue->chq_to_no,
      'issued_date' => now(),
      'user_id' => Auth::id() ?? 1
    ]);

    return response()->json([
      'success' => true,
      'message' => 'Cheque issue approved successfully'
    ]);
  }

  // Cheque Maintenance
  public function maintenanceIndex()
  {
    return view('cheques.maintenance');
  }

  public function getMaintenanceData(Request $request)
  {
    $query = ChequeMaintenance::with('branch')->select('cheque_maintenances.*');

    // Filter by branch
    if ($request->has('branch_id') && $request->branch_id != '') {
      $query->where('branch_id', $request->branch_id);
    }

    // Filter by status
    if ($request->has('status') && $request->status != '') {
      if ($request->status == 'approved') {
        $query->whereNotNull('approved_date');
      } elseif ($request->status == 'pending') {
        $query->whereNull('approved_date');
      }
    }

    // Filter by date range
    if ($request->has('date_from') && $request->date_from) {
      $query->whereDate('tran_date', '>=', $request->date_from);
    }

    if ($request->has('date_to') && $request->date_to) {
      $query->whereDate('tran_date', '<=', $request->date_to);
    }

    return DataTables::of($query)
      ->addColumn('branch_name', function ($row) {
        return $row->branch->branch_name ?? 'N/A';
      })
      ->addColumn('action', function ($row) {
        $btn = '<div class="btn-group" role="group">';
        $btn .= '<button type="button" class="btn btn-sm btn-info view-btn" data-id="' . $row->chq_id . '"><i class="fas fa-eye"></i></button>';
        if (!$row->approved_date) {
          $btn .= '<button type="button" class="btn btn-sm btn-success approve-btn" data-id="' . $row->chq_id . '"><i class="fas fa-check"></i></button>';
        }
        $btn .= '</div>';
        return $btn;
      })
      ->rawColumns(['action'])
      ->make(true);
  }

  public function storeMaintenance(Request $request)
  {
    $validated = $request->validate([
      'tran_date' => 'required|date',
      'branch_id' => 'required|exists:branchs,branch_id',
      'qty' => 'required|integer|min:1',
      'chq_from_no' => 'required|string|max:255',
      'chq_to_no' => 'required|string|max:255'
    ]);

    $validated['main_by'] = Auth::id() ?? 1;
    $validated['main_date'] = now();

    $maintenance = ChequeMaintenance::create($validated);

    return response()->json([
      'success' => true,
      'message' => 'Cheque maintenance record created successfully',
      'data' => $maintenance
    ]);
  }

  public function approveMaintenance($id)
  {
    $maintenance = ChequeMaintenance::findOrFail($id);

    $maintenance->update([
      'approved_by' => Auth::id() ?? 1,
      'approved_date' => now()
    ]);

    return response()->json([
      'success' => true,
      'message' => 'Cheque maintenance approved successfully'
    ]);
  }

  // Cheque Stops
  public function stopsIndex()
  {
    return view('cheques.stops');
  }

  public function getStopsData(Request $request)
  {
    $query = ChequeStop::select('cheque_stops.*');

    // Filter by status
    if ($request->has('status') && $request->status != '') {
      if ($request->status == 'released') {
        $query->whereNotNull('released_date');
      } elseif ($request->status == 'stopped') {
        $query->whereNull('released_date');
      }
    }

    // Filter by reason
    if ($request->has('reason') && $request->reason != '') {
      $query->where('reason', $request->reason);
    }

    // Filter by date range
    if ($request->has('date_from') && $request->date_from) {
      $query->whereDate('stopped_date', '>=', $request->date_from);
    }

    if ($request->has('date_to') && $request->date_to) {
      $query->whereDate('stopped_date', '<=', $request->date_to);
    }

    return DataTables::of($query)
      ->addColumn('status', function ($row) {
        if ($row->released_date) {
          return '<span class="badge bg-success">Released</span>';
        }
        return '<span class="badge bg-danger">Stopped</span>';
      })
      ->addColumn('action', function ($row) {
        $btn = '<div class="btn-group" role="group">';
        $btn .= '<button type="button" class="btn btn-sm btn-info view-btn" data-id="' . $row->chq_stop_id . '"><i class="fas fa-eye"></i></button>';
        if (!$row->released_date) {
          $btn .= '<button type="button" class="btn btn-sm btn-success release-btn" data-id="' . $row->chq_stop_id . '"><i class="fas fa-unlock"></i></button>';
        }
        $btn .= '</div>';
        return $btn;
      })
      ->rawColumns(['status', 'action'])
      ->make(true);
  }

  public function storeStop(Request $request)
  {
    $validated = $request->validate([
      'chq_no' => 'required|string|max:255',
      'reason' => 'required|string',
      'note' => 'nullable|string'
    ]);

    $validated['stopped_by'] = Auth::id() ?? 1;
    $validated['stopped_date'] = now();

    $stop = ChequeStop::create($validated);

    return response()->json([
      'success' => true,
      'message' => 'Cheque stop created successfully',
      'data' => $stop
    ]);
  }

  public function releaseStop($id)
  {
    $stop = ChequeStop::findOrFail($id);

    $stop->update([
      'released_by' => Auth::id() ?? 1,
      'released_date' => now()
    ]);

    return response()->json([
      'success' => true,
      'message' => 'Cheque stop released successfully'
    ]);
  }

  // Cheque Clearing
  public function clearingIndex()
  {
    return view('cheques.clearing');
  }

  public function getClearingData(Request $request)
  {
    $query = ChequeClear::with('transaction')->select('cheque_clears.*');

    // Filter by cheque number
    if ($request->has('chq_no') && $request->chq_no != '') {
      $query->where('chq_no', 'like', '%' . $request->chq_no . '%');
    }

    // Filter by date range
    if ($request->has('date_from') && $request->date_from) {
      $query->whereDate('clear_date', '>=', $request->date_from);
    }

    if ($request->has('date_to') && $request->date_to) {
      $query->whereDate('clear_date', '<=', $request->date_to);
    }

    return DataTables::of($query)
      ->addColumn('action', function ($row) {
        $btn = '<div class="btn-group" role="group">';
        $btn .= '<button type="button" class="btn btn-sm btn-info view-btn" data-id="' . $row->chq_clear_id . '"><i class="fas fa-eye"></i></button>';
        $btn .= '</div>';
        return $btn;
      })
      ->rawColumns(['action'])
      ->make(true);
  }

  public function storeClear(Request $request)
  {
    $validated = $request->validate([
      'chq_no' => 'required|string|max:255',
      'tran_id' => 'required|exists:trans,tran_id'
    ]);

    $validated['clear_by'] = Auth::id() ?? 1;
    $validated['clear_date'] = now();

    $clear = ChequeClear::create($validated);

    return response()->json([
      'success' => true,
      'message' => 'Cheque cleared successfully',
      'data' => $clear
    ]);
  }
}
