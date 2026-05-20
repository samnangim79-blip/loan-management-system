<?php

namespace App\Http\Controllers;

use App\Models\FdCert;
use App\Models\FdTerm;
use App\Models\FdTran;
use App\Models\FdOption;
use App\Models\FdRollOver;
use App\Models\AccountInfo;
use App\Models\FdFutureDep;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FdController extends Controller
{
  public function index()
  {
    $terms = FdTerm::all();
    $options = FdOption::all();
    return view('fixed-deposits.index', compact('terms', 'options'));
  }

  public function getData(Request $request)
  {
    $query = FdCert::with(['account.customer', 'fdTerm', 'fdOption'])->select('fd_certs.*');

    // Filter by term
    if ($request->has('term') && $request->term != '') {
      $query->where('fd_term_id', $request->term);
    }

    // Filter by option
    if ($request->has('option') && $request->option != '') {
      $query->where('fd_option_id', $request->option);
    }

    // Filter by status
    if ($request->has('status') && $request->status != '') {
      $today = now()->toDateString();
      if ($request->status == 'active') {
        $query->where('matured_date', '>=', $today);
      } elseif ($request->status == 'matured') {
        $query->where('matured_date', '<', $today);
      }
    }

    // Search by certificate ID or customer name
    if ($request->has('search_term') && $request->search_term != '') {
      $searchTerm = $request->search_term;
      $query->where(function($q) use ($searchTerm) {
        $q->where('fd_cert_id', 'like', '%' . $searchTerm . '%')
          ->orWhereHas('account.customer', function($q2) use ($searchTerm) {
            $q2->where('name_en', 'like', '%' . $searchTerm . '%')
               ->orWhere('name_kh', 'like', '%' . $searchTerm . '%');
          });
      });
    }

    return DataTables::of($query)
      ->addColumn('customer_name', function ($row) {
        return $row->account->customer->name_en ?? 'N/A';
      })
      ->addColumn('account_no', function ($row) {
        return $row->account->acct_no ?? 'N/A';
      })
      ->addColumn('term_name', function ($row) {
        return $row->fdTerm->term_name ?? 'N/A';
      })
      ->addColumn('option_name', function ($row) {
        return $row->fdOption->fd_option ?? 'N/A';
      })
      ->addColumn('formatted_amount', function ($row) {
        return '$' . number_format($row->amount ?? 0, 2);
      })
      ->addColumn('status', function ($row) {
        $today = now()->toDateString();
        if ($row->matured_date < $today) {
          return '<span class="badge bg-success">Matured</span>';
        }
        return '<span class="badge bg-primary">Active</span>';
      })
      ->addColumn('action', function ($row) {
        $btn = '<div class="btn-group" role="group">';
        $btn .= '<button type="button" class="btn btn-sm btn-info view-btn" data-id="' . $row->fd_cert_id . '"><i class="fas fa-eye"></i></button>';
        $btn .= '<button type="button" class="btn btn-sm btn-primary edit-btn" data-id="' . $row->fd_cert_id . '"><i class="fas fa-edit"></i></button>';
        $btn .= '<button type="button" class="btn btn-sm btn-success rollover-btn" data-id="' . $row->fd_cert_id . '"><i class="fas fa-redo"></i></button>';
        $btn .= '<button type="button" class="btn btn-sm btn-warning withdraw-btn" data-id="' . $row->fd_cert_id . '"><i class="fas fa-money-bill-wave"></i></button>';
        $btn .= '</div>';
        return $btn;
      })
      ->rawColumns(['status', 'action'])
      ->make(true);
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'fd_cert_id' => 'required|unique:fd_certs,fd_cert_id',
      'acct_id' => 'required|exists:account_infos,acct_id',
      'date_issue' => 'required|date',
      'amount' => 'required|numeric|min:0',
      'int_rate' => 'required|numeric|min:0|max:100',
      'extra_rate' => 'nullable|numeric|min:0|max:100',
      'fd_option_id' => 'required|exists:fd_options,fd_option_id',
      'fd_term_id' => 'required|exists:fd_terms,fd_term_id',
      'acct_for_int' => 'nullable|string|max:50',
      'acct_for_prin' => 'nullable|string|max:50'
    ]);

    // Calculate matured date based on term
    $term = FdTerm::find($validated['fd_term_id']);
    $validated['matured_date'] = date('Y-m-d', strtotime($validated['date_issue'] . ' + ' . $term->days_num . ' days'));
    $validated['done_by'] = Auth::id() ?? 1;

    $cert = FdCert::create($validated);

    return response()->json([
      'success' => true,
      'message' => 'Fixed deposit created successfully',
      'data' => $cert
    ]);
  }

  public function show($id)
  {
    $cert = FdCert::with(['account.customer', 'fdTerm', 'fdOption', 'rollOvers', 'futureDeps'])->findOrFail($id);
    return response()->json($cert);
  }

  public function update(Request $request, $id)
  {
    $cert = FdCert::findOrFail($id);

    $validated = $request->validate([
      'int_rate' => 'required|numeric|min:0|max:100',
      'extra_rate' => 'nullable|numeric|min:0|max:100',
      'fd_option_id' => 'required|exists:fd_options,fd_option_id',
      'acct_for_int' => 'nullable|string|max:50',
      'acct_for_prin' => 'nullable|string|max:50'
    ]);

    $cert->update($validated);

    return response()->json([
      'success' => true,
      'message' => 'Fixed deposit updated successfully',
      'data' => $cert
    ]);
  }

  public function rollover(Request $request, $id)
  {
    $cert = FdCert::findOrFail($id);

    $validated = $request->validate([
      'roll_over_date' => 'required|date',
      'amount' => 'required|numeric|min:0',
      'int_rate' => 'required|numeric|min:0|max:100'
    ]);

    DB::beginTransaction();
    try {
      // Calculate new matured date
      $term = FdTerm::find($cert->fd_term_id);
      $maturedDate = date('Y-m-d', strtotime($validated['roll_over_date'] . ' + ' . $term->days_num . ' days'));

      // Create rollover record
      FdRollOver::create([
        'fd_cert_id' => $id,
        'roll_over_date' => $validated['roll_over_date'],
        'matured_date' => $maturedDate,
        'amount' => $validated['amount'],
        'int_rate' => $validated['int_rate']
      ]);

      // Update certificate
      $cert->update([
        'date_issue' => $validated['roll_over_date'],
        'matured_date' => $maturedDate,
        'amount' => $validated['amount'],
        'int_rate' => $validated['int_rate']
      ]);

      DB::commit();

      return response()->json([
        'success' => true,
        'message' => 'Fixed deposit rolled over successfully',
        'data' => $cert
      ]);
    } catch (\Exception $e) {
      DB::rollback();
      return response()->json([
        'success' => false,
        'message' => 'Error rolling over: ' . $e->getMessage()
      ], 500);
    }
  }

  public function withdraw(Request $request, $id)
  {
    $cert = FdCert::findOrFail($id);

    DB::beginTransaction();
    try {
      // Create transaction record
      FdTran::create([
        'fd_tran_id' => time(),
        'fd_cert_id' => $id,
        'status' => 'withdrawn'
      ]);

      DB::commit();

      return response()->json([
        'success' => true,
        'message' => 'Fixed deposit withdrawn successfully'
      ]);
    } catch (\Exception $e) {
      DB::rollback();
      return response()->json([
        'success' => false,
        'message' => 'Error withdrawing: ' . $e->getMessage()
      ], 500);
    }
  }

  // FD Terms - getTerms is the route handler, termsIndex for legacy
  public function getTerms()
  {
    return view('fixed-deposits.terms');
  }

  public function termsIndex()
  {
    return $this->getTerms();
  }

  public function getTermsData()
  {
    $terms = FdTerm::select('fd_terms.*');

    return DataTables::of($terms)
      ->addColumn('action', function ($row) {
        $btn = '<div class="btn-group" role="group">';
        $btn .= '<button type="button" class="btn btn-sm btn-primary edit-btn" data-id="' . $row->fd_term_id . '"><i class="fas fa-edit"></i></button>';
        $btn .= '<button type="button" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->fd_term_id . '"><i class="fas fa-trash"></i></button>';;
        $btn .= '</div>';
        return $btn;
      })
      ->rawColumns(['action'])
      ->make(true);
  }

  public function storeTerm(Request $request)
  {
    $validated = $request->validate([
      'fd_term_id' => 'required|integer|unique:fd_terms,fd_term_id',
      'term_name' => 'required|string|max:50',
      'days_num' => 'required|integer|min:1',
      'int_rate' => 'required|numeric|min:0|max:100',
      'grace_period' => 'nullable|integer|min:0',
      'break_term_fee' => 'nullable|numeric|min:0'
    ]);

    $term = FdTerm::create($validated);

    return response()->json([
      'success' => true,
      'message' => 'FD Term created successfully',
      'data' => $term
    ]);
  }

  public function updateTerm(Request $request, $id)
  {
    $term = FdTerm::findOrFail($id);

    $validated = $request->validate([
      'term_name' => 'required|string|max:50',
      'days_num' => 'required|integer|min:1',
      'int_rate' => 'required|numeric|min:0|max:100',
      'grace_period' => 'nullable|integer|min:0',
      'break_term_fee' => 'nullable|numeric|min:0'
    ]);

    $term->update($validated);

    return response()->json([
      'success' => true,
      'message' => 'FD Term updated successfully',
      'data' => $term
    ]);
  }

  // FD Options - getOptions is the route handler
  public function getOptions()
  {
    return view('fixed-deposits.options');
  }

  public function optionsIndex()
  {
    return $this->getOptions();
  }

  public function getOptionsData()
  {
    $options = FdOption::select('fd_options.*');

    return DataTables::of($options)
      ->addColumn('action', function ($row) {
        $btn = '<div class="btn-group" role="group">';
        $btn .= '<button type="button" class="btn btn-sm btn-primary edit-btn" data-id="' . $row->fd_option_id . '"><i class="fas fa-edit"></i></button>';
        $btn .= '<button type="button" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->fd_option_id . '"><i class="fas fa-trash"></i></button>';;
        $btn .= '</div>';
        return $btn;
      })
      ->rawColumns(['action'])
      ->make(true);
  }

  public function storeOption(Request $request)
  {
    $validated = $request->validate([
      'fd_option_id' => 'required|integer|unique:fd_options,fd_option_id',
      'fd_option' => 'required|string|max:50'
    ]);

    $option = FdOption::create($validated);

    return response()->json([
      'success' => true,
      'message' => 'FD Option created successfully',
      'data' => $option
    ]);
  }

  public function updateOption(Request $request, $id)
  {
    $option = FdOption::findOrFail($id);

    $validated = $request->validate([
      'fd_option' => 'required|string|max:50'
    ]);

    $option->update($validated);

    return response()->json([
      'success' => true,
      'message' => 'FD Option updated successfully',
      'data' => $option
    ]);
  }

  public function destroyOption($id)
  {
    $option = FdOption::findOrFail($id);

    // Check if option is being used
    if ($option->fdCerts()->count() > 0) {
      return response()->json([
        'success' => false,
        'message' => 'Cannot delete option. It is being used by existing FD certificates.'
      ], 400);
    }

    $option->delete();

    return response()->json([
      'success' => true,
      'message' => 'FD Option deleted successfully'
    ]);
  }

  public function destroyTerm($id)
  {
    $term = FdTerm::findOrFail($id);

    // Check if term is being used
    if ($term->fdCerts()->count() > 0) {
      return response()->json([
        'success' => false,
        'message' => 'Cannot delete term. It is being used by existing FD certificates.'
      ], 400);
    }

    $term->delete();

    return response()->json([
      'success' => true,
      'message' => 'FD Term deleted successfully'
    ]);
  }
}
