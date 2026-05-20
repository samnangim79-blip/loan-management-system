@extends('admin.layouts.admin_layout')

@section('pageTitle', __('staff.management'))

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('staff.index') }}">Staff</a></li>
  <li class="breadcrumb-item active">List</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            <i class="fas fa-id-badge me-2"></i>Staff List
          </h3>
          <div class="card-tools">
            @if (user_has_permission('staff_add') || user_is_super_admin())
              <button type="button" class="btn btn-primary btn-sm" id="addStaffBtn">
                <i class="fas fa-plus"></i> Add Staff
              </button>
            @endif
          </div>
        </div>
        <div class="card-body">
          <table id="staffTable" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>{{ __('common.general.ic_no') }}</th>
                <th>{{ __('common.general.full_name') }}</th>
                <th>{{ __('common.general.gender') }}</th>
                <th>{{ __('common.general.position') }}</th>
                <th>{{ __('common.general.branch') }}</th>
                <th>{{ __('common.general.phone') }}</th>
                <th>{{ __('common.general.action') }}</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Staff Modal -->
  <div class="modal fade" id="staffModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">
            <i class="fas fa-user-plus me-2"></i>Add Staff
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="staffForm">
          @csrf
          <div class="modal-body">
            <input type="hidden" id="staff_id" name="staff_id">

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="ic_no" class="form-label">IC Number <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="ic_no" name="ic_no" required maxlength="30">
                  <small class="form-text text-muted">National ID or Passport number</small>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="full_name" name="full_name" required maxlength="50">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="mb-3">
                  <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                  <select class="form-select" id="gender" name="gender" required>
                    <option value="">{{ __('common.form.select_gender') }}</option>
                    <option value="M">{{ __('common.general.male') }}</option>
                    <option value="F">{{ __('common.general.female') }}</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="mb-3">
                  <label for="dob" class="form-label">{{ __('common.general.date_of_birth') }}</label>
                  <input type="text" class="form-control" id="dob" name="dob">
                </div>
              </div>
              <div class="col-md-4">
                <div class="mb-3">
                  <label for="phone" class="form-label">{{ __('common.general.phone') }}</label>
                  <input type="text" class="form-control" id="phone" name="phone" maxlength="50">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="position" class="form-label">{{ __('common.form.position') }}</label>
                  <input type="text" class="form-control" id="position" name="position" maxlength="30">
                  <small class="form-text text-muted">e.g., Loan Officer, Teller, Manager</small>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="branch_id" class="form-label">Branch <span class="text-danger">*</span></label>
                  <select class="form-select" id="branch_id" name="branch_id" required>
                    <option value="">{{ __('common.form.select_branch') }}</option>
                    @foreach ($branches as $branch)
                      <option value="{{ $branch->branch_id }}">{{ $branch->branch_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="pob" class="form-label">{{ __('common.pagination.place_of_birth') }}</label>
                  <input type="text" class="form-control" id="pob" name="pob" maxlength="100">
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="address" class="form-label">{{ __('common.general.address') }}</label>
                  <input type="text" class="form-control" id="address" name="address" maxlength="100">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i class="fas fa-times me-1"></i>Close
            </button>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save me-1"></i>Save Staff
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- View Staff Modal -->
  <div class="modal fade" id="viewStaffModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            <i class="fas fa-id-badge me-2"></i>Staff Details
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <table class="table table-striped">
            <tr>
              <th width="40%">Staff ID:</th>
              <td id="view_staff_id"></td>
            </tr>
            <tr>
              <th>IC Number:</th>
              <td id="view_ic_no"></td>
            </tr>
            <tr>
              <th>Full Name:</th>
              <td id="view_full_name"></td>
            </tr>
            <tr>
              <th>Gender:</th>
              <td id="view_gender"></td>
            </tr>
            <tr>
              <th>Date of Birth:</th>
              <td id="view_dob"></td>
            </tr>
            <tr>
              <th>Place of Birth:</th>
              <td id="view_pob"></td>
            </tr>
            <tr>
              <th>Phone:</th>
              <td id="view_phone"></td>
            </tr>
            <tr>
              <th>Position:</th>
              <td id="view_position"></td>
            </tr>
            <tr>
              <th>Branch:</th>
              <td id="view_branch"></td>
            </tr>
            <tr>
              <th>Address:</th>
              <td id="view_address"></td>
            </tr>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('common.general.close') }}</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    $(document).ready(function() {
      // Initialize DataTable
      var table = $('#staffTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('staff.data') }}",
        columns: [{
            data: 'staff_id',
            name: 'staff_id'
          },
          {
            data: 'ic_no',
            name: 'ic_no'
          },
          {
            data: 'full_name',
            name: 'full_name'
          },
          {
            data: 'gender',
            name: 'gender',
            render: function(data) {
              return data === 'M' ? '<span class="badge bg-primary">{{ __('common.general.male') }}</span>' :
                '<span class="badge bg-info">{{ __('common.general.female') }}</span>';
            }
          },
          {
            data: 'position',
            name: 'position'
          },
          {
            data: 'branch_name',
            name: 'branch.branch_name'
          },
          {
            data: 'phone',
            name: 'phone'
          },
          {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
          }
        ],
        order: [
          [0, 'desc']
        ],
        pageLength: 10,
        responsive: true
      });

      // Initialize Flatpickr for date fields
      flatpickr("#dob", {
        dateFormat: "Y-m-d",
        maxDate: "today"
      });

      // Add Staff Button
      $('#addStaffBtn').click(function() {
        $('#staffForm')[0].reset();
        $('#staff_id').val('');
        $('#modalTitle').html('<i class="fas fa-user-plus me-2"></i>Add Staff');
        $('#staffModal').modal('show');
      });

      // Form Submit
      $('#staffForm').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();
        var staffId = $('#staff_id').val();
        var url = staffId ? `/staff/${staffId}` : '/staff';
        var method = staffId ? 'PUT' : 'POST';

        $.ajax({
          url: url,
          type: method,
          data: formData,
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(response) {
            if (response.success) {
              $('#staffModal').modal('hide');
              table.ajax.reload();
              toastr.success(response.message);
            }
          },
          error: function(xhr) {
            if (xhr.responseJSON && xhr.responseJSON.errors) {
              $.each(xhr.responseJSON.errors, function(key, value) {
                toastr.error(value[0]);
              });
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
              toastr.error(xhr.responseJSON.message);
            } else {
              toastr.error('{{ __('common.messages.an_error_occurred_please_try_again') }}');
            }
          }
        });
      });

      // Edit Button
      $(document).on('click', '.edit-btn', function() {
        var staffId = $(this).data('id');

        $.ajax({
          url: `/staff/${staffId}`,
          type: 'GET',
          success: function(staff) {
            $('#staff_id').val(staff.staff_id);
            $('#ic_no').val(staff.ic_no);
            $('#full_name').val(staff.full_name);
            $('#gender').val(staff.gender);
            $('#dob').val(staff.dob);
            $('#pob').val(staff.pob);
            $('#phone').val(staff.phone);
            $('#position').val(staff.position);
            $('#branch_id').val(staff.branch_id);
            $('#address').val(staff.address);

            $('#modalTitle').html('<i class="fas fa-user-edit me-2"></i>Edit Staff');
            $('#staffModal').modal('show');
          },
          error: function(xhr) {
            toastr.error('{{ __('common.pagination.failed_to_load_staff_data') }}');
          }
        });
      });

      // View Button
      $(document).on('click', '.view-btn', function() {
        var staffId = $(this).data('id');

        $.ajax({
          url: `/staff/${staffId}`,
          type: 'GET',
          success: function(staff) {
            $('#view_staff_id').text(staff.staff_id);
            $('#view_ic_no').text(staff.ic_no);
            $('#view_full_name').text(staff.full_name);
            $('#view_gender').text(staff.gender === 'M' ? '{{ __('common.general.male') }}' : '{{ __('common.general.female') }}');
            $('#view_dob').text(staff.dob || '-');
            $('#view_pob').text(staff.pob || '-');
            $('#view_phone').text(staff.phone || '-');
            $('#view_position').text(staff.position || '-');
            $('#view_branch').text(staff.branch ? staff.branch.branch_name : '-');
            $('#view_address').text(staff.address || '-');

            $('#viewStaffModal').modal('show');
          },
          error: function(xhr) {
            toastr.error('{{ __('common.pagination.failed_to_load_staff_data') }}');
          }
        });
      });

      // Delete Button
      $(document).on('click', '.delete-btn', function() {
        var staffId = $(this).data('id');

        Swal.fire({
          title: '{{ __('common.general.are_you_sure') }}',
          text: "This will permanently delete the staff member. This action cannot be undone!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: '{{ __('common.general.yes_delete_it') }}'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: `/staff/${staffId}`,
              type: 'DELETE',
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success: function(response) {
                if (response.success) {
                  table.ajax.reload();
                  Swal.fire('Deleted!', response.message, 'success');
                }
              },
              error: function(xhr) {
                Swal.fire('Error!', xhr.responseJSON.message || '{{ __('common.general.failed_to_delete_staff') }}', 'error');
              }
            });
          }
        });
      });
    });
  </script>
@endpush
