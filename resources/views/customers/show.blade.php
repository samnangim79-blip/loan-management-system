@extends('admin.layouts.admin_layout')

@section('pageTitle', __('customers.details'))

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Customers</a></li>
  <li class="breadcrumb-item active">View</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">{{ __('common.messages.customer_information') }}</h3>
        </div>
        <div class="card-body">
          <div class="text-center mb-3">
            <img src="{{ assetUrl() }}img/avatar.png" class="" width="100"
              alt="{{ __('common.pagination.customer_photo') }}">
          </div>
          <table class="table table-striped">
            <tr>
              <th>Customer ID:</th>
              <td>{{ $customer->cust_id }}</td>
            </tr>
            <tr>
              <th>IC No:</th>
              <td>{{ $customer->ic_no }}</td>
            </tr>
            <tr>
              <th>Name (EN):</th>
              <td>{{ $customer->name_en }}</td>
            </tr>
            <tr>
              <th>Name (KH):</th>
              <td>{{ $customer->name_kh }}</td>
            </tr>
            <tr>
              <th>Gender:</th>
              <td>{{ $customer->gender == 'M' ? __('common.general.male') : __('common.general.female') }}</td>
            </tr>
            <tr>
              <th>Date of Birth:</th>
              <td>{{ $customer->dob?->format('Y-m-d') }}</td>
            </tr>
            <tr>
              <th>Marital Status:</th>
              <td>{{ $customer->marital_status == 1 ? __('common.general.married') : __('common.general.single') }}</td>
            </tr>
          </table>
        </div>
      </div>
    </div>

    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Contact & Address</h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <table class="table table-striped">
                <tr>
                  <th>Phone 1:</th>
                  <td>{{ $customer->phone1 }}</td>
                </tr>
                <tr>
                  <th>Phone 2:</th>
                  <td>{{ $customer->phone2 ?? 'N/A' }}</td>
                </tr>
                <tr>
                  <th>Email:</th>
                  <td>{{ $customer->email ?? 'N/A' }}</td>
                </tr>
              </table>
            </div>
            <div class="col-md-6">
              <table class="table table-striped">
                <tr>
                  <th>Address:</th>
                  <td>{{ $customer->address }}</td>
                </tr>
                <tr>
                  <th>Village:</th>
                  <td>{{ $customer->village->village ?? 'N/A' }}</td>
                </tr>
                <tr>
                  <th>Province:</th>
                  <td>{{ $customer->village->commune->district->province->province ?? 'N/A' }}</td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="card mt-3">
        <div class="card-header">
          <h3 class="card-title">{{ __('common.nav.accounts') }}</h3>
        </div>
        <div class="card-body">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>{{ __('common.general.account_no') }}</th>
                <th>{{ __('common.general.account_name') }}</th>
                <th>{{ __('common.general.type') }}</th>
                <th>{{ __('common.general.status') }}</th>
                <th>{{ __('common.general.action') }}</th>
              </tr>
            </thead>
            <tbody>
              @forelse($customer->accounts as $account)
                <tr>
                  <td>{{ $account->acct_no }}</td>
                  <td>{{ $account->acct_name }}</td>
                  <td>{{ $account->accountType->acct_type ?? 'N/A' }}</td>
                  <td>
                    <span class="badge bg-{{ $account->account_status == 1 ? 'success' : 'warning' }}">
                      {{ $account->status_text }}
                    </span>
                  </td>
                  <td>
                    <a href="{{ route('accounts.show', $account->acct_id) }}" class="">
                      <i class=""></i>
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="">{{ __('common.general.no_accounts_found') }}</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="mt-3">
    <button type="button" class="btn btn-primary" id="editCustomerBtn" data-customer-id="{{ $customer->cust_id }}">
      <i class="fas fa-edit"></i> Edit Customer
    </button>
    <a href="{{ route('customers.index') }}" class="btn btn-secondary">
      <i class="fas fa-arrow-left"></i> Back to List
    </a>
  </div>

  <!-- Edit Customer Modal -->
  <div class="modal fade" id="editCustomerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Customer</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="editCustomerForm">
          <div class="modal-body">
            <input type="hidden" id="edit_customer_id" name="customer_id" value="{{ $customer->cust_id }}">

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="edit_ic_no" class="form-label">IC Number <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="edit_ic_no" name="ic_no"
                    value="{{ $customer->ic_no }}" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="edit_id_no" class="form-label">ID Number</label>
                  <input type="text" class="form-control" id="edit_id_no" name="id_no"
                    value="{{ $customer->id_no }}">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="edit_name_en" class="form-label">Name (English) <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="edit_name_en" name="name_en"
                    value="{{ $customer->name_en }}" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="edit_name_kh" class="form-label">Name (Khmer)</label>
                  <input type="text" class="form-control" id="edit_name_kh" name="name_kh"
                    value="{{ $customer->name_kh }}">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="mb-3">
                  <label for="edit_gender" class="form-label">Gender <span class="text-danger">*</span></label>
                  <select class="form-select" id="edit_gender" name="gender" required>
                    <option value="">Select Gender</option>
                    <option value="M" {{ $customer->gender == 'M' ? 'selected' : '' }}>Male</option>
                    <option value="F" {{ $customer->gender == 'F' ? 'selected' : '' }}>Female</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="mb-3">
                  <label for="edit_marital_status" class="form-label">Marital Status <span
                      class="text-danger">*</span></label>
                  <select class="form-select" id="edit_marital_status" name="marital_status" required>
                    <option value="">Select Status</option>
                    <option value="0" {{ $customer->marital_status == 0 ? 'selected' : '' }}>Single</option>
                    <option value="1" {{ $customer->marital_status == 1 ? 'selected' : '' }}>Married</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="mb-3">
                  <label for="edit_dob" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                  <input type="date" class="form-control" id="edit_dob" name="dob"
                    value="{{ $customer->dob?->format('Y-m-d') }}" required>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="mb-3">
                  <label for="edit_phone1" class="form-label">Primary Phone <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="edit_phone1" name="phone1"
                    value="{{ $customer->phone1 }}" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="mb-3">
                  <label for="edit_phone2" class="form-label">Secondary Phone</label>
                  <input type="text" class="form-control" id="edit_phone2" name="phone2"
                    value="{{ $customer->phone2 }}">
                </div>
              </div>
              <div class="col-md-4">
                <div class="mb-3">
                  <label for="edit_email" class="form-label">Email</label>
                  <input type="email" class="form-control" id="edit_email" name="email"
                    value="{{ $customer->email }}">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="edit_pob" class="form-label">Place of Birth</label>
                  <input type="text" class="form-control" id="edit_pob" name="pob"
                    value="{{ $customer->pob }}">
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="edit_occupation" class="form-label">Occupation</label>
                  <input type="text" class="form-control" id="edit_occupation" name="occupation"
                    value="{{ $customer->occupation }}">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-12">
                <div class="mb-3">
                  <label for="edit_address" class="form-label">Address <span class="text-danger">*</span></label>
                  <textarea class="form-control" id="edit_address" name="address" rows="2" required>{{ $customer->address }}</textarea>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="edit_village_id" class="form-label">Village <span class="text-danger">*</span></label>
                  <select class="form-control" id="edit_village_id" name="village_id" required>
                    <option value="">Select Village</option>
                    @foreach (\App\Models\Village::all() as $village)
                      <option value="{{ $village->id }}"
                        {{ $customer->village_id == $village->id ? 'selected' : '' }}>
                        {{ $village->village ?? ($village->name_en ?? $village->village_kh) }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="edit_nationality_id" class="form-label">Nationality</label>
                  <select class="form-control" id="edit_nationality_id" name="nationality_id">
                    <option value="">Select Nationality</option>
                    @foreach (\App\Models\Nationality::all() as $nationality)
                      <option value="{{ $nationality->nationality_id }}"
                        {{ $customer->nationality_id == $nationality->nationality_id ? 'selected' : '' }}>
                        {{ $nationality->nationality ?? ($nationality->name_en ?? $nationality->nationality_kh) }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Update Customer</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    $(document).ready(function() {
      // Edit Customer Button
      $('#editCustomerBtn').click(function() {
        $('#editCustomerModal').modal('show');
      });

      // Edit Customer Form Submission
      $('#editCustomerForm').submit(function(e) {
        e.preventDefault();

        var customerId = $('#edit_customer_id').val();
        var formData = $(this).serialize();

        $.ajax({
          url: `/customers/${customerId}`,
          type: 'PUT',
          data: formData,
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(response) {
            if (response.success) {
              $('#editCustomerModal').modal('hide');
              toastr.success(response.message || 'Customer updated successfully');
              // Reload the page to show updated information
              location.reload();
            }
          },
          error: function(xhr) {
            var errors = xhr.responseJSON.errors;
            if (errors) {
              $.each(errors, function(key, value) {
                toastr.error(value[0]);
              });
            } else {
              toastr.error(xhr.responseJSON.message || 'An error occurred while updating customer');
            }
          }
        });
      });
    });
  </script>
@endpush
