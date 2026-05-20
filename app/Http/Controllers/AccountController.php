<?php
// app/Http/Controllers/AccountController.php
namespace App\Http\Controllers;

use App\Models\Trans;
use App\Models\Customer;
use App\Models\AccountInfo;
use App\Models\AccountType;
use App\Models\CustAcctTran;
use App\Models\AccountPhoto;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Traits\ImageUploadTrait;

class AccountController extends Controller
{
    use ImageUploadTrait;
    /**
     * Get a valid user ID that exists in user_logins table
     */
    private function getValidUserId()
    {
        $authId = Auth::id();
        if ($authId && \App\Models\UserLogin::find($authId)) {
            return $authId;
        }
        return 1; // Default fallback user
    }

    public function index()
    {
        $accountTypes = AccountType::all();
        return view('accounts.index', compact('accountTypes'));
    }

    public function getData()
    {
        $accounts = AccountInfo::with(['customer', 'accountType', 'branch'])
            ->select('account_infos.*');

        return DataTables::of($accounts)
            ->addColumn('customer_name', function ($row) {
                return $row->customer ? $row->customer->name_en : 'N/A';
            })
            ->addColumn('account_type_name', function ($row) {
                return $row->accountType ? $row->accountType->acct_type : 'N/A';
            })
            ->addColumn('balance', function ($row) {
                return '$' . number_format($row->getBalance(), 2);
            })
            ->addColumn('status_badge', function ($row) {
                $colors = [
                    1 => 'success',
                    2 => 'warning',
                    3 => 'danger',
                    4 => 'secondary'
                ];
                $color = $colors[$row->account_status] ?? 'primary';
                return '<span class="badge bg-' . $color . '">' . $row->status_text . '</span>';
            })
            ->addColumn('action', function ($row) {
                $btn = '<div class="btn-group" role="group">';
                $btn .= '<button type="button" class="btn btn-sm btn-info view-account" data-id="' . $row->acct_id . '" title="View">
                    <i class="fas fa-eye"></i>
                </button>';
                $btn .= '<button type="button" class="btn btn-sm btn-primary edit-account" data-id="' . $row->acct_id . '" title="Edit">
                    <i class="fas fa-edit"></i>
                </button>';
                $btn .= '<button type="button" class="btn btn-sm btn-success transaction-btn" data-id="' . $row->acct_id . '" title="Transaction">
                    <i class="fas fa-exchange-alt"></i>
                </button>';
                $btn .= '<button type="button" class="btn btn-sm btn-danger delete-account" data-id="' . $row->acct_id . '" title="Delete">
                    <i class="fas fa-trash"></i>
                </button>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['status_badge', 'action'])
            ->make(true);
    }

    public function create()
    {
        $customers = Customer::orderBy('name_en')->get();
        $accountTypes = AccountType::all();

        return view('accounts.create', compact('customers', 'accountTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cust_id' => 'required|exists:customer_infos,cust_id',
            'acct_name' => 'required|string|max:50',
            'acct_type_id' => 'required|exists:account_types,acct_type_id',
            'category' => 'nullable|string|max:50',
            'resident' => 'nullable|string|max:10',
            'currency_id' => 'nullable|string|max:10',
            'joint_flag' => 'nullable|integer',
            'extra_rate' => 'nullable|numeric',
            'mandatory' => 'nullable|string',
            'account_status' => 'nullable|integer',
            'remark' => 'nullable|string'
        ]);

        // Validate photos separately (multiple files)
        $request->validate([
            'account_photos' => 'nullable|array',
            'account_photos.*' => 'nullable|file|mimes:jpeg,jpg,png,webp,pdf,doc,docx|max:5120'
        ]);

        DB::beginTransaction();
        try {
            // Generate account number
            $validated['acct_no'] = $this->generateAccountNumber();
            $validated['account_status'] = $validated['account_status'] ?? AccountInfo::STATUS_ACTIVE;
            $validated['branch_id'] = 1; // Default branch for now
            $validated['opened_date'] = now();
            $validated['opened_by'] = $this->getValidUserId();

            $account = AccountInfo::create($validated);

            // Handle multiple file uploads to account_photos table using ImageUploadTrait
            if ($request->hasFile('account_photos')) {
                $filePaths = $this->uploadMultiImage(
                    $request,
                    'account_photos',
                    'accounts',
                    'account_' . $account->acct_id . '_',
                    'public',
                    ['jpg', 'jpeg', 'png', 'webp', 'pdf', 'doc', 'docx'],
                    5120 // 5MB
                );

                if ($filePaths && is_array($filePaths)) {
                    foreach ($filePaths as $filePath) {
                        // Extract just the filename from the path
                        $filename = basename($filePath);

                        // Get file extension from filename
                        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                        // Determine photo type based on file extension
                        $photoType = in_array($extension, ['pdf', 'doc', 'docx'])
                            ? AccountPhoto::PHOTO_TYPE_DOCUMENT
                            : AccountPhoto::PHOTO_TYPE_ACCOUNT;

                        AccountPhoto::create([
                            'acct_id' => $account->acct_id,
                            'file_name' => $filename,
                            'photo_type' => $photoType,
                            'date_added' => now(),
                            'status' => AccountPhoto::STATUS_ACTIVE,
                            'user_id' => $this->getValidUserId()
                        ]);
                    }
                }
            }

            // Create initial transaction record if needed
            $this->createInitialTransaction($account);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Account created successfully',
                'data' => $account
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error creating account: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $account = AccountInfo::with([
            'customer',
            'accountType',
            'branch',
            'photos'
        ])->findOrFail($id);

        // Check if request wants JSON (for AJAX/modal)
        if (request()->wantsJson() || request()->ajax()) {
            // Format photos for frontend
            $photosData = $account->photos->map(function ($photo) {
                return [
                    'id' => $photo->acct_photo_id,
                    'url' => asset('storage/accounts/' . $photo->file_name),
                    'name' => $photo->file_name,
                    'size' => 0, // File size not stored in DB
                    'type' => $photo->photo_type
                ];
            });

            $accountData = $account->toArray();
            $accountData['photos'] = $photosData;

            return response()->json([
                'success' => true,
                'data' => $accountData
            ]);
        }

        // Return view for regular page requests
        $balance = $account->getBalance();
        return view('accounts.show', compact('account', 'balance'));
    }

    public function details($id)
    {
        $account = AccountInfo::with([
            'customer',
            'accountType',
            'branch'
        ])->findOrFail($id);

        $balance = $account->getBalance();

        return response()->json([
            'success' => true,
            'data' => [
                'acct_id' => $account->acct_id,
                'acct_no' => $account->acct_no,
                'balance' => $balance,
                'account_type' => [
                    'acct_type' => $account->accountType->acct_type ?? 'N/A'
                ],
                'customer' => [
                    'name_en' => $account->customer->name_en ?? 'N/A',
                    'id_no' => $account->customer->id_no ?? 'N/A',
                    'phone1' => $account->customer->phone1 ?? 'N/A'
                ]
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $account = AccountInfo::findOrFail($id);

        $validated = $request->validate([
            'cust_id' => 'required|exists:customer_infos,cust_id',
            'acct_name' => 'required|string|max:50',
            'acct_type_id' => 'required|exists:account_types,acct_type_id',
            'category' => 'nullable|string|max:50',
            'resident' => 'nullable|string|max:10',
            'currency_id' => 'nullable|string|max:10',
            'joint_flag' => 'nullable|integer',
            'extra_rate' => 'nullable|numeric',
            'mandatory' => 'nullable|string',
            'account_status' => 'nullable|integer',
            'remark' => 'nullable|string'
        ]);

        // Validate photos separately (multiple files)
        $request->validate([
            'account_photos' => 'nullable|array',
            'account_photos.*' => 'nullable|file|mimes:jpeg,jpg,png,webp,pdf,doc,docx|max:5120',
            'deleted_photo_ids' => 'nullable|array',
            'deleted_photo_ids.*' => 'nullable|integer'
        ]);

        DB::beginTransaction();
        try {
            $validated['modify_by'] = $this->getValidUserId();
            $validated['modify_date'] = now();

            $account->update($validated);

            // Handle deletion of photos marked for deletion
            if ($request->has('deleted_photo_ids') && is_array($request->deleted_photo_ids)) {
                $photosToDelete = AccountPhoto::where('acct_id', $account->acct_id)
                    ->whereIn('acct_photo_id', $request->deleted_photo_ids)
                    ->get();

                foreach ($photosToDelete as $photo) {
                    // Delete file from storage
                    $this->deleteImage('accounts/' . $photo->file_name, 'public');
                    // Delete database record
                    $photo->delete();
                }
            }

            // Handle multiple file uploads to account_photos table using ImageUploadTrait
            if ($request->hasFile('account_photos')) {
                $filePaths = $this->uploadMultiImage(
                    $request,
                    'account_photos',
                    'accounts',
                    'account_' . $account->acct_id . '_',
                    'public',
                    ['jpg', 'jpeg', 'png', 'webp', 'pdf', 'doc', 'docx'],
                    5120 // 5MB
                );

                if ($filePaths && is_array($filePaths)) {
                    foreach ($filePaths as $filePath) {
                        // Extract just the filename from the path
                        $filename = basename($filePath);

                        // Get file extension from filename
                        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                        // Determine photo type based on file extension
                        $photoType = in_array($extension, ['pdf', 'doc', 'docx'])
                            ? AccountPhoto::PHOTO_TYPE_DOCUMENT
                            : AccountPhoto::PHOTO_TYPE_ACCOUNT;

                        AccountPhoto::create([
                            'acct_id' => $account->acct_id,
                            'file_name' => $filename,
                            'photo_type' => $photoType,
                            'date_added' => now(),
                            'status' => AccountPhoto::STATUS_ACTIVE,
                            'user_id' => $this->getValidUserId()
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Account updated successfully',
                'data' => $account
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error updating account: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $account = AccountInfo::findOrFail($id);

        DB::beginTransaction();
        try {
            // Check if account has transactions
            $transactionCount = CustAcctTran::where('acc_id', $id)->count();
            if ($transactionCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete account with existing transactions'
                ], 400);
            }

            $account->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Account deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error deleting account: ' . $e->getMessage()
            ], 500);
        }
    }

    public function transactions($id)
    {
        $account = AccountInfo::findOrFail($id);
        $transactions = CustAcctTran::with('transaction')
            ->where('acc_id', $id)
            ->orderBy('cust_tran_id', 'desc')
            ->paginate(20);

        return view('accounts.transactions', compact('account', 'transactions'));
    }

    public function deposit(Request $request, $id)
    {
        $account = AccountInfo::findOrFail($id);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:250'
        ]);

        DB::beginTransaction();
        try {
            $currentBalance = $account->getBalance();
            $newBalance = $currentBalance + $validated['amount'];

            // Create main transaction
            $trans = Trans::create([
                'branch_id' => $account->branch_id,
                'tran_date' => now(),
                'amount' => $validated['amount'],
                'ccy_id' => $account->accountType->ccy_id,
                'discription' => $validated['description'] ?? 'Deposit',
                'user_id' => $this->getValidUserId(),
                'done_date' => now(),
                'tran_type' => Trans::TYPE_DEPOSIT
            ]);

            // Create customer account transaction
            CustAcctTran::create([
                'tran_id' => $trans->tran_id,
                'acc_id' => $account->acct_id,
                'amt' => $validated['amount'],
                'dr_cr' => 'c',
                'os_bal' => $newBalance
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Deposit successful',
                'new_balance' => $newBalance
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error processing deposit: ' . $e->getMessage()
            ], 500);
        }
    }

    public function withdraw(Request $request, $id)
    {
        $account = AccountInfo::findOrFail($id);
        $currentBalance = $account->getBalance();

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $currentBalance,
            'description' => 'nullable|string|max:250'
        ]);

        DB::beginTransaction();
        try {
            $newBalance = $currentBalance - $validated['amount'];

            // Create main transaction
            $trans = Trans::create([
                'branch_id' => $account->branch_id,
                'tran_date' => now(),
                'amount' => $validated['amount'],
                'ccy_id' => $account->accountType->ccy_id,
                'discription' => $validated['description'] ?? 'Withdrawal',
                'user_id' => $this->getValidUserId(),
                'done_date' => now(),
                'tran_type' => Trans::TYPE_WITHDRAWAL
            ]);

            // Create customer account transaction
            CustAcctTran::create([
                'tran_id' => $trans->tran_id,
                'acc_id' => $account->acct_id,
                'amt' => $validated['amount'],
                'dr_cr' => 'd',
                'os_bal' => $newBalance
            ]);

            // Update last withdrawal date
            $account->last_withdraw_date = now();
            $account->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Withdrawal successful',
                'new_balance' => $newBalance
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error processing withdrawal: ' . $e->getMessage()
            ], 500);
        }
    }

    private function generateAccountNumber()
    {
        $lastAccount = AccountInfo::orderBy('acct_id', 'desc')->first();
        $sequence = $lastAccount ? intval(substr($lastAccount->acct_no, -7)) + 1 : 1;

        return str_pad($sequence, 7, '0', STR_PAD_LEFT);
    }

    private function createInitialTransaction($account)
    {
        // Create initial zero balance transaction
        CustAcctTran::create([
            'tran_id' => 0,
            'acc_id' => $account->acct_id,
            'amt' => 0,
            'dr_cr' => 'c',
            'os_bal' => 0
        ]);
    }

    public function searchAccounts(Request $request)
    {
        $query = $request->get('q', '');

        $accounts = AccountInfo::with('customer')
            ->where('acct_no', 'like', "%{$query}%")
            ->orWhereHas('customer', function ($q) use ($query) {
                $q->where('name_en', 'like', "%{$query}%")
                    ->orWhere('name_kh', 'like', "%{$query}%");
            })
            ->limit(20)
            ->get();

        return response()->json($accounts);
    }

    public function getCustomers()
    {
        $customers = Customer::select('cust_id', 'name_en', 'name_kh', 'id_no')
            ->orderBy('name_en')
            ->get();

        return response()->json($customers);
    }

    public function getAccountTypes()
    {
        $accountTypes = AccountType::select('acct_type_id', 'acct_type', 'category')
            ->orderBy('acct_type')
            ->get();

        return response()->json($accountTypes);
    }
}
