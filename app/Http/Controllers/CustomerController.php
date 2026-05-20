<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustPhoto;
use App\Models\Village;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Traits\ImageUploadTrait;

class CustomerController extends Controller
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
        return view('customers.index');
    }

    public function getData()
    {
        $customers = Customer::with('village')->select('customer_infos.*');

        return DataTables::of($customers)
            ->addColumn('full_name', function ($row) {
                return $row->name_en . ' (' . $row->name_kh . ')';
            })
            ->addColumn('contact', function ($row) {
                return $row->phone1 . '<br>' . $row->email;
            })
            ->addColumn('action', function ($row) {
                $btn = '<div class="btn-group" role="group">';
                $btn .= '<button type="button" class="btn btn-sm btn-info view-btn" data-id="' . $row->cust_id . '"><i class="fas fa-eye"></i></button>';
                $btn .= '<button type="button" class="btn btn-sm btn-primary edit-btn" data-id="' . $row->cust_id . '"><i class="fas fa-edit"></i></button>';
                $btn .= '<button type="button" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->cust_id . '"><i class="fas fa-trash"></i></button>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['contact', 'action'])
            ->make(true);
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_no' => 'required|unique:customer_infos,id_no',
            'name_en' => 'required|string|max:50',
            'name_kh' => 'nullable|string|max:50',
            'gender' => 'required|in:M,F',
            'marital_status' => 'required|in:0,1',
            'dob' => 'required|date',
            'pob' => 'nullable|string|max:100',
            'phone1' => 'required|string|max:50',
            'phone2' => 'nullable|string|max:50',
            'phone3' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:50',
            'address' => 'required|string|max:100',
            'country_id' => 'required|exists:countries,country_id',
            'province_id' => 'required|exists:provinces,id',
            'district_id' => 'required|exists:districts,id',
            'commune_id' => 'required|exists:communes,id',
            'village_id' => 'required|exists:villages,id',
            'occupation' => 'nullable|string|max:50',
            'spouse_id_no' => 'nullable|string|max:30',
            'spouse_name_en' => 'nullable|string|max:50',
            'spouse_name_kh' => 'nullable|string|max:50',
            'spouse_dob' => 'nullable|date',
            'guarantor_id_no' => 'nullable|string|max:30',
            'guarantor_name_en' => 'nullable|string|max:50',
            'guarantor_name_kh' => 'nullable|string|max:50',
            'guarantor_dob' => 'nullable|date',
            'family_book' => 'nullable|string|max:150',
            'staff_id' => 'nullable|exists:staffs,staff_id',
            'remark' => 'nullable|string',
            'nationality_id' => 'nullable|exists:nationalitys,nationality_id'
        ]);

        // Validate photos/documents separately (multiple files)
        $request->validate([
            'customer_photos' => 'nullable|array',
            'customer_photos.*' => 'nullable|file|mimes:jpeg,jpg,png,webp,pdf,doc,docx|max:5120'
        ]);

        $validated['created_by'] = $this->getValidUserId();
        $validated['created_date'] = now();

        $customer = Customer::create($validated);

        // Handle multiple file uploads to cust_photos table using ImageUploadTrait
        if ($request->hasFile('customer_photos')) {
            $filePaths = $this->uploadMultiImage(
                $request,
                'customer_photos',
                'customers',
                'customer_' . $customer->cust_id . '_',
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
                        ? CustPhoto::PHOTO_TYPE_DOCUMENT
                        : CustPhoto::PHOTO_TYPE_CUSTOMER;

                    CustPhoto::create([
                        'cust_id' => $customer->cust_id,
                        'file_name' => $filename,
                        'photo_type' => $photoType,
                        'date_added' => now(),
                        'status' => CustPhoto::STATUS_ACTIVE,
                        'user_id' => $this->getValidUserId()
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Customer created successfully',
            'data' => $customer
        ]);
    }

    public function show(Request $request, $id)
    {
        $customer = Customer::with(['village.commune.district.province', 'nationality'])->findOrFail($id);

        // Force JSON response for AJAX requests
        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json($customer);
        }

        // Otherwise return view (for non-AJAX requests)
        return view('customers.show', compact('customer'));
    }

    public function edit($id)
    {
        $customer = Customer::with(['village.commune.district.province', 'nationality'])->findOrFail($id);

        // Get all customer photos/documents
        $photos = CustPhoto::where('cust_id', $customer->cust_id)
            ->where('status', CustPhoto::STATUS_ACTIVE)
            ->get();

        // Add photos information to customer data
        $customerData = $customer->toArray();
        if ($photos->isNotEmpty()) {
            $customerData['photos'] = $photos->map(function ($photo) {
                return [
                    'url' => asset('storage/customers/' . $photo->file_name),
                    'name' => $photo->file_name,
                    'size' => 0, // Size not stored in DB, set to 0
                    'id' => $photo->cust_photo_id,
                    'type' => $photo->photo_type
                ];
            })->toArray();
        }

        return response()->json($customerData);
    }


    /**
     * Get customer data for AJAX (dedicated API endpoint)
     */
    public function getCustomer($id)
    {
        $customer = Customer::with(['village.commune.district.province', 'nationality'])->findOrFail($id);
        return response()->json($customer);
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'id_no' => 'required|unique:customer_infos,id_no,' . $id . ',cust_id',
            'name_en' => 'required|string|max:50',
            'name_kh' => 'nullable|string|max:50',
            'gender' => 'required|in:M,F',
            'marital_status' => 'required|in:0,1',
            'dob' => 'required|date',
            'pob' => 'nullable|string|max:100',
            'phone1' => 'required|string|max:50',
            'phone2' => 'nullable|string|max:50',
            'phone3' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:50',
            'address' => 'required|string|max:100',
            'country_id' => 'required|exists:countries,country_id',
            'province_id' => 'required|exists:provinces,id',
            'district_id' => 'required|exists:districts,id',
            'commune_id' => 'required|exists:communes,id',
            'village_id' => 'required|exists:villages,id',
            'occupation' => 'nullable|string|max:50',
            'spouse_id_no' => 'nullable|string|max:30',
            'spouse_name_en' => 'nullable|string|max:50',
            'spouse_name_kh' => 'nullable|string|max:50',
            'spouse_dob' => 'nullable|date',
            'guarantor_id_no' => 'nullable|string|max:30',
            'guarantor_name_en' => 'nullable|string|max:50',
            'guarantor_name_kh' => 'nullable|string|max:50',
            'guarantor_dob' => 'nullable|date',
            'family_book' => 'nullable|string|max:150',
            'staff_id' => 'nullable|exists:staffs,staff_id',
            'remark' => 'nullable|string',
            'nationality_id' => 'nullable|exists:nationalitys,nationality_id'
        ]);

        // Validate photos/documents separately (multiple files)
        $request->validate([
            'customer_photos' => 'nullable|array',
            'customer_photos.*' => 'nullable|file|mimes:jpeg,jpg,png,webp,pdf,doc,docx|max:5120',
            'deleted_photo_ids' => 'nullable|array',
            'deleted_photo_ids.*' => 'nullable|integer'
        ]);

        $validated['modify_by'] = $this->getValidUserId();
        $validated['modify_date'] = now();

        $customer->update($validated);

        // Handle deletion of photos marked for deletion
        if ($request->has('deleted_photo_ids') && is_array($request->deleted_photo_ids)) {
            $photosToDelete = CustPhoto::where('cust_id', $customer->cust_id)
                ->whereIn('cust_photo_id', $request->deleted_photo_ids)
                ->get();

            foreach ($photosToDelete as $photo) {
                // Delete file from storage
                $this->deleteImage('customers/' . $photo->file_name, 'public');
                // Delete database record
                $photo->delete();
            }
        }

        // Handle multiple file uploads to cust_photos table using ImageUploadTrait
        if ($request->hasFile('customer_photos')) {
            $filePaths = $this->uploadMultiImage(
                $request,
                'customer_photos',
                'customers',
                'customer_' . $customer->cust_id . '_',
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
                        ? CustPhoto::PHOTO_TYPE_DOCUMENT
                        : CustPhoto::PHOTO_TYPE_CUSTOMER;

                    CustPhoto::create([
                        'cust_id' => $customer->cust_id,
                        'file_name' => $filename,
                        'photo_type' => $photoType,
                        'date_added' => now(),
                        'status' => CustPhoto::STATUS_ACTIVE,
                        'user_id' => $this->getValidUserId()
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Customer updated successfully',
            'data' => $customer
        ]);
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);

        // Check if customer has active accounts
        if ($customer->accounts()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete customer with active accounts'
            ], 400);
        }

        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Customer deleted successfully'
        ]);
    }

    /**
     * Search customer by CID
     */
    public function searchByCid(Request $request)
    {
        $cid = $request->input('cid');

        if (!$cid) {
            return response()->json([
                'success' => false,
                'message' => 'CID is required'
            ], 400);
        }

        // Search for customer by cust_id
        $customer = Customer::where('cust_id', $cid)->first();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $customer
        ]);
    }
}
