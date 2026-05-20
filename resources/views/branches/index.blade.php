@extends('admin.layouts.admin_layout')

@section('pageTitle', __('common.general.branch_management'))

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('branches.index') }}">Branches</a></li>
  <li class="breadcrumb-item active">List</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            <i class="fas fa-building me-2"></i>Branch List
          </h3>
          <div class="card-tools">
            @if (user_has_permission('branch_add') || user_is_super_admin())
              <button type="button" class="btn btn-primary btn-sm" id="addBranchBtn">
                <i class="fas fa-plus"></i> Add Branch
              </button>
            @endif
          </div>
        </div>
        <div class="card-body">
          <table id="branchesTable" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>{{ __('common.general.branch_id') }}</th>
                <th>{{ __('common.general.branch_name') }}</th>
                <th>{{ __('common.general.phone') }}</th>
                <th>{{ __('common.general.email') }}</th>
                <th>{{ __('common.general.website') }}</th>
                <th>{{ __('common.general.action') }}</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Branch Modal -->
  <div class="modal fade" id="branchModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">
            <i class="fas fa-building me-2"></i>Add Branch
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="branchForm">
          @csrf
          <div class="modal-body">
            <input type="hidden" id="branch_id" name="branch_id">
            <input type="hidden" id="is_edit" name="is_edit" value="0">

            <div class="mb-3" id="branchIdRow">
              <label for="branchIdInput" class="form-label">Branch ID <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="branchIdInput" name="branch_id_input" required>
              <small class="form-text text-muted">Unique identifier for this branch</small>
            </div>

            <div class="mb-3">
              <label for="branchName" class="form-label">Branch Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="branchName" name="branch_name" required maxlength="255">
            </div>

            <div class="mb-3">
              <label for="phone" class="form-label">{{ __('common.general.phone') }}</label>
              <input type="text" class="form-control" id="phone" name="phone" maxlength="50">
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">{{ __('common.general.email') }}</label>
              <input type="email" class="form-control" id="email" name="email" maxlength="100">
            </div>

            <div class="mb-3">
              <label for="website" class="form-label">{{ __('common.form.website') }}</label>
              <input type="text" class="form-control" id="website" name="website" maxlength="100"
                placeholder="https://...">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i class="fas fa-times me-1"></i>Close
            </button>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save me-1"></i>Save Branch
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- View Branch Modal -->
  <div class="modal fade" id="viewBranchModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            <i class="fas fa-building me-2"></i>Branch Details
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <h6 class="fw-bold text-primary mb-3">{{ __('common.messages.branch_information') }}</h6>
              <table class="table table-striped">
                <tr>
                  <th width="40%">Branch ID:</th>
                  <td id="view_branch_id"></td>
                </tr>
                <tr>
                  <th>Branch Name:</th>
                  <td id="view_branch_name"></td>
                </tr>
                <tr>
                  <th>Phone:</th>
                  <td id="view_phone"></td>
                </tr>
                <tr>
                  <th>Email:</th>
                  <td id="view_email"></td>
                </tr>
                <tr>
                  <th>Website:</th>
                  <td id="view_website"></td>
                </tr>
              </table>
            </div>
            <div class="col-md-6">
              <h6 class="fw-bold text-primary mb-3">{{ __('common.general.statistics') }}</h6>
              <div class="row">
                <div class="col-6 mb-3">
                  <div class="card">
                    <div class="card-body text-center py-3">
                      <h4 class="mb-0" id="view_staff_count">0</h4>
                      <small class="text-muted">Staff Members</small>
                    </div>
                  </div>
                </div>
                <div class="col-6 mb-3">
                  <div class="card">
                    <div class="card-body text-center py-3">
                      <h4 class="mb-0" id="view_accounts_count">0</h4>
                      <small class="text-muted">Accounts</small>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
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
      var table = $('#branchesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('branches.data') }}",
        columns: [{
            data: 'branch_id',
            name: 'branch_id'
          },
          {
            data: 'branch_name',
            name: 'branch_name'
          },
          {
            data: 'phone',
            name: 'phone',
            defaultContent: '-'
          },
          {
            data: 'email',
            name: 'email',
            defaultContent: '-'
          },
          {
            data: 'website',
            name: 'website',
            render: function(data) {
              if (data) {
                return '<a href="' + data + '" target="_blank" class="text-muted">' + data + '</a>';
              }
              return '-';
            }
          },
          {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
          }
        ],
        order: [
          [0, 'asc']
        ],
        pageLength: 10,
        responsive: true
      });

      // Add Branch Button
      $('#addBranchBtn').click(function() {
        $('#branchForm')[0].reset();
        $('#branch_id').val('');
        $('#is_edit').val('0');
        $('#branchIdRow').show();
        $('#branchIdInput').prop('disabled', false).prop('required', true);
        $('#modalTitle').html('<i class="fas fa-building me-2"></i>Add Branch');
        $('#branchModal').modal('show');
      });

      // Form Submit
      $('#branchForm').on('submit', function(e) {
        e.preventDefault();

        var isEdit = $('#is_edit').val() === '1';
        var branchId = $('#branch_id').val();
        var url = isEdit ? `/branches/${branchId}` : '/branches';
        var method = isEdit ? 'PUT' : 'POST';

        // Build form data manually to handle disabled field
        var formData = {
          _token: $('input[name="_token"]').val(),
          branch_name: $('#branchName').val(),
          phone: $('#phone').val(),
          email: $('#email').val(),
          website: $('#website').val()
        };

        if (!isEdit) {
          formData.branch_id = $('#branchIdInput').val();
        }

        $.ajax({
          url: url,
          type: method,
          data: formData,
          success: function(response) {
            if (response.success) {
              $('#branchModal').modal('hide');
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
        var branchId = $(this).data('id');

        $.ajax({
          url: `/branches/${branchId}`,
          type: 'GET',
          success: function(branch) {
            $('#branch_id').val(branch.branch_id);
            $('#is_edit').val('1');
            $('#branchIdInput').val(branch.branch_id).prop('disabled', true).prop('required', false);
            $('#branchIdRow').hide();
            $('#branchName').val(branch.branch_name);
            $('#phone').val(branch.phone);
            $('#email').val(branch.email);
            $('#website').val(branch.website);

            $('#modalTitle').html('<i class="fas fa-building me-2"></i>Edit Branch');
            $('#branchModal').modal('show');
          },
          error: function(xhr) {
            toastr.error('{{ __('common.pagination.failed_to_load_branch_data') }}');
          }
        });
      });

      // View Button
      $(document).on('click', '.view-btn', function() {
        var branchId = $(this).data('id');

        $.ajax({
          url: `/branches/${branchId}`,
          type: 'GET',
          success: function(branch) {
            $('#view_branch_id').text(branch.branch_id);
            $('#view_branch_name').text(branch.branch_name);
            $('#view_phone').text(branch.phone || '-');
            $('#view_email').text(branch.email || '-');

            if (branch.website) {
              $('#view_website').html('<a href="' + branch.website + '" target="_blank">' + branch.website +
                '</a>');
            } else {
              $('#view_website').text('-');
            }

            $('#view_staff_count').text(branch.staff ? branch.staff.length : 0);
            $('#view_accounts_count').text(branch.accounts ? branch.accounts.length : 0);

            $('#viewBranchModal').modal('show');
          },
          error: function(xhr) {
            toastr.error('{{ __('common.pagination.failed_to_load_branch_data') }}');
          }
        });
      });

      // Delete Button
      $(document).on('click', '.delete-btn', function() {
        var branchId = $(this).data('id');

        Swal.fire({
          title: '{{ __('common.general.are_you_sure') }}',
          text: "This will permanently delete the branch. Branches with existing staff or accounts cannot be deleted.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: '{{ __('common.general.yes_delete_it') }}'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: `/branches/${branchId}`,
              type: 'DELETE',
              data: {
                _token: $('input[name="_token"]').val()
              },
              success: function(response) {
                if (response.success) {
                  table.ajax.reload();
                  Swal.fire('Deleted!', response.message, 'success');
                }
              },
              error: function(xhr) {
                Swal.fire('Error!', xhr.responseJSON.message ||
                  '{{ __('common.general.failed_to_delete_branch') }}', 'error');
              }
            });
          }
        });
      });
    });
  </script>
@endpush
