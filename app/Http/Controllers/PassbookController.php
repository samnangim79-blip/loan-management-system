<?php

namespace App\Http\Controllers;

use App\Models\Passbook;
use App\Models\PassbookIssue;
use App\Models\PassbookMaintenance;
use App\Models\AccountInfo;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PassbookController extends Controller
{
  // Passbook Issues
  public function index()
  {
    return view('passbooks.index');
  }

  public function getIssuesData()
  {
    $issues = PassbookIssue::with('account.customer')->select('passbook_issues.*');

    return DataTables::of($issues)
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
        $btn .= '<button type="button" class="btn btn-sm btn-info view-btn" data-id="' . $row->pass_issue_id . '"><i class="fas fa-eye"></i></button>';
        if ($row->status == 0) {
          $btn .= '<button type="button" class="btn btn-sm btn-success approve-btn" data-id="' . $row->pass_issue_id . '"><i class="fas fa-check"></i></button>';
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
      'passbook_no' => 'required|string|max:255'
    ]);

    $validated['status'] = 0; // Pending
    $validated['last_printed_page'] = 0;
    $validated['last_printed_line'] = 0;
    $validated['issue_by'] = auth()->id() ?? 1;
    $validated['issue_date'] = now();

    $issue = PassbookIssue::create($validated);

    return response()->json([
      'success' => true,
      'message' => 'Passbook issue request created successfully',
      'data' => $issue
    ]);
  }

  public function approveIssue($id)
  {
    $issue = PassbookIssue::findOrFail($id);

    $issue->update([
      'status' => 1,
      'approved_by' => auth()->id() ?? 1,
      'approved_date' => now()
    ]);

    // Create passbook record
    Passbook::create([
      'acct_id' => $issue->acct_id,
      'passbook_no' => $issue->passbook_no,
      'last_printed_page' => 0,
      'last_printed_line' => 0,
      'status' => 'active'
    ]);

    return response()->json([
      'success' => true,
      'message' => 'Passbook issue approved successfully'
    ]);
  }

  // Passbook Maintenance
  public function maintenanceIndex()
  {
    return view('passbooks.maintenance');
  }

  public function getMaintenanceData()
  {
    $maintenance = PassbookMaintenance::with('branch')->select('passbook_maintenances.*');

    return DataTables::of($maintenance)
      ->addColumn('branch_name', function ($row) {
        return $row->branch->branch_name ?? 'N/A';
      })
      ->addColumn('action', function ($row) {
        $btn = '<div class="btn-group" role="group">';
        $btn .= '<button type="button" class="btn btn-sm btn-info view-btn" data-id="' . $row->pass_id . '"><i class="fas fa-eye"></i></button>';
        if (!$row->approved_date) {
          $btn .= '<button type="button" class="btn btn-sm btn-success approve-btn" data-id="' . $row->pass_id . '"><i class="fas fa-check"></i></button>';
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
      'pass_from_no' => 'required|string|max:255',
      'pass_to_no' => 'required|string|max:255'
    ]);

    $validated['main_by'] = auth()->id() ?? 1;
    $validated['main_date'] = now();

    $maintenance = PassbookMaintenance::create($validated);

    return response()->json([
      'success' => true,
      'message' => 'Passbook maintenance record created successfully',
      'data' => $maintenance
    ]);
  }

  public function approveMaintenance($id)
  {
    $maintenance = PassbookMaintenance::findOrFail($id);

    $maintenance->update([
      'approved_by' => auth()->id() ?? 1,
      'approved_date' => now()
    ]);

    return response()->json([
      'success' => true,
      'message' => 'Passbook maintenance approved successfully'
    ]);
  }

  // Passbook List
  public function passbooksList()
  {
    return view('passbooks.list');
  }

  public function getPassbooksData()
  {
    $passbooks = Passbook::with('account.customer')->select('passbooks.*');

    return DataTables::of($passbooks)
      ->addColumn('customer_name', function ($row) {
        return $row->account->customer->name_en ?? 'N/A';
      })
      ->addColumn('account_no', function ($row) {
        return $row->account->acct_no ?? 'N/A';
      })
      ->addColumn('action', function ($row) {
        $btn = '<div class="btn-group" role="group">';
        $btn .= '<button type="button" class="btn btn-sm btn-info view-btn" data-id="' . $row->passbook_id . '"><i class="fas fa-eye"></i></button>';
        $btn .= '<button type="button" class="btn btn-sm btn-primary print-btn" data-id="' . $row->passbook_id . '"><i class="fas fa-print"></i></button>';
        $btn .= '</div>';
        return $btn;
      })
      ->rawColumns(['action'])
      ->make(true);
  }

  public function updatePrintStatus(Request $request, $id)
  {
    $passbook = Passbook::findOrFail($id);

    $validated = $request->validate([
      'last_printed_page' => 'required|integer|min:0',
      'last_printed_line' => 'required|integer|min:0'
    ]);

    $passbook->update($validated);

    return response()->json([
      'success' => true,
      'message' => 'Print status updated successfully',
      'data' => $passbook
    ]);
  }
}
