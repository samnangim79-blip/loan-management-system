@extends('admin.layouts.admin_layout')

@section('pageTitle', __('users.management'))

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
  <li class="breadcrumb-item active">List</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            <i class="fas fa-users me-2"></i>User List
          </h3>
          <div class="card-tools">
            @permission('user_add')
              <button type="button" class="btn btn-primary btn-sm" id="addUserBtn">
                <i class="fas fa-plus"></i> Add User
              </button>
            @endpermission
          </div>
        </div>
        <div class="card-body">
          <table id="usersTable" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>{{ __('common.general.login_name') }}</th>
                <th>{{ __('common.general.staff_name') }}</th>
                <th>Profile/Role</th>
                <th>{{ __('common.general.branch') }}</th>
                <th>{{ __('common.general.cash_limit') }}</th>
                <th>{{ __('common.general.status') }}</th>
                <th>{{ __('common.general.action') }}</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- User Modal -->
  <div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">
            <i class="fas fa-user-plus me-2"></i>Add User
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="userForm">
          @csrf
          <div class="modal-body">
            <input type="hidden" id="user_id" name="user_id">

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="staff_id" class="form-label">Staff <span class="text-danger">*</span></label>
                  <select id="staff_id" name="staff_id" class="form-control" required></select>
                  <small class="text-muted">Search for a staff member</small>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="login_name" class="form-label">Login Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="login_name" name="login_name" required maxlength="50">
                  <small class="text-muted">Username for system login</small>
                </div>
              </div>
            </div>

            <div class="row" id="passwordRow">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                  <input type="password" class="form-control" id="password" name="password" minlength="6"
                    maxlength="50">
                  <small class="text-muted">Minimum 6 characters</small>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="password_confirm" class="form-label">Confirm Password <span
                      class="text-danger">*</span></label>
                  <input type="password" class="form-control" id="password_confirm" name="password_confirm" minlength="6"
                    maxlength="50">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="profile_id" class="form-label">Access Profile <span class="text-danger">*</span></label>
                  <select class="form-select" id="profile_id" name="profile_id" required>
                    <option value="">{{ __('common.general.select_profile') }}</option>
                    @foreach ($profiles as $profile)
                      <option value="{{ $profile->profile_id }}">{{ $profile->profile }}</option>
                    @endforeach
                  </select>
                  <small class="text-muted">Determines user permissions</small>
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
                  <label for="sys_cash_limit" class="form-label">{{ __('common.form.system_cash_limit') }}</label>
                  <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" class="form-control" id="sys_cash_limit" name="sys_cash_limit"
                      step="0.01" min="0">
                  </div>
                  <small class="text-muted">Override profile limit (0 = use profile limit)</small>
                </div>
              </div>
              <div class="col-md-6" id="statusRow" style="display: none;">
                <div class="mb-3">
                  <label for="status" class="form-label">{{ __('common.general.status') }}</label>
                  <select class="form-select" id="status" name="status">
                    <option value="0">{{ __('common.form.active') }}</option>
                    <option value="1">{{ __('common.general.suspended') }}</option>
                    <option value="2">{{ __('common.general.deleted') }}</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i class="fas fa-times me-1"></i>Close
            </button>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save me-1"></i>Save User
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- View User Modal -->
  <div class="modal fade" id="viewUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            <i class="fas fa-user me-2"></i>User Details
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <table class="table table-striped">
            <tr>
              <th width="40%">User ID:</th>
              <td id="view_user_id"></td>
            </tr>
            <tr>
              <th>Login Name:</th>
              <td id="view_login_name"></td>
            </tr>
            <tr>
              <th>Staff Name:</th>
              <td id="view_staff_name"></td>
            </tr>
            <tr>
              <th>Access Profile:</th>
              <td id="view_profile"></td>
            </tr>
            <tr>
              <th>Branch:</th>
              <td id="view_branch"></td>
            </tr>
            <tr>
              <th>Cash Limit:</th>
              <td id="view_cash_limit"></td>
            </tr>
            <tr>
              <th>Status:</th>
              <td id="view_status"></td>
            </tr>
            <tr>
              <th>Failed Logins:</th>
              <td id="view_failed_log"></td>
            </tr>
            <tr>
              <th>Password Expires:</th>
              <td id="view_pwd_expire"></td>
            </tr>
            <tr>
              <th>Last Login IP:</th>
              <td id="view_log_ip"></td>
            </tr>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary"
            data-bs-dismiss="modal">{{ __('common.general.close') }}</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Reset Password Modal -->
  <div class="modal fade" id="resetPasswordModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            <i class="fas fa-key me-2"></i>Reset Password
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="resetPasswordForm">
          @csrf
          <div class="modal-body">
            <input type="hidden" id="reset_user_id" name="reset_user_id">
            <p class="text-muted mb-3">Reset password for user: <strong id="reset_user_name"></strong></p>

            <div class="mb-3">
              <label for="new_password" class="form-label">New Password <span class="text-danger">*</span></label>
              <input type="password" class="form-control" id="new_password" name="password" required minlength="6"
                maxlength="50">
            </div>
            <div class="mb-3">
              <label for="confirm_new_password" class="form-label">Confirm New Password <span
                  class="text-danger">*</span></label>
              <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password"
                required minlength="6" maxlength="50">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary"
              data-bs-dismiss="modal">{{ __('common.general.cancel') }}</button>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-key me-1"></i>Reset Password
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    $(document).ready(function() {
      // Initialize DataTable
      var table = $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('users.data') }}",
        columns: [{
            data: 'user_id',
            name: 'user_id'
          },
          {
            data: 'login_name',
            name: 'login_name'
          },
          {
            data: 'staff_name',
            name: 'staff.full_name'
          },
          {
            data: 'profile_name',
            name: 'profile.profile'
          },
          {
            data: 'branch_name',
            name: 'branch.branch_name'
          },
          {
            data: 'sys_cash_limit',
            name: 'sys_cash_limit',
            render: function(data) {
              return data ? '$' + parseFloat(data).toLocaleString('en-US', {
                minimumFractionDigits: 2
              }) : '-';
            }
          },
          {
            data: 'status_badge',
            name: 'status_badge'
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

      // Initialize Tom Select for Staff search
      var staffSelect = new TomSelect('#staff_id', {
        valueField: 'staff_id',
        labelField: 'full_name',
        searchField: ['full_name', 'ic_no'],
        placeholder: '{{ __('common.general.search_for_staff') }}',
        load: function(query, callback) {
          if (!query.length) return callback();
          $.ajax({
            url: '/api/staff/search',
            type: 'GET',
            data: {
              q: query
            },
            success: function(res) {
              callback(res);
            },
            error: function() {
              callback();
            }
          });
        },
        render: {
          option: function(item, escape) {
            return '<div>' + escape(item.full_name) + ' <small class="text-muted">(' + escape(item.ic_no ||
              'N/A') + ')</small></div>';
          },
          item: function(item, escape) {
            return '<div>' + escape(item.full_name) + '</div>';
          }
        }
      });

      // Add User Button
      $('#addUserBtn').click(function() {
        $('#userForm')[0].reset();
        $('#user_id').val('');
        $('#modalTitle').html('<i class="fas fa-user-plus me-2"></i>Add User');
        $('#passwordRow').show();
        $('#password').prop('required', true);
        $('#password_confirm').prop('required', true);
        $('#statusRow').hide();
        staffSelect.clear();
        staffSelect.clearOptions();
        $('#userModal').modal('show');
      });

      // Form Submit
      $('#userForm').on('submit', function(e) {
        e.preventDefault();

        // Password confirmation check
        var password = $('#password').val();
        var confirmPassword = $('#password_confirm').val();
        var userId = $('#user_id').val();

        if (!userId && password !== confirmPassword) {
          toastr.error('{{ __('common.general.passwords_do_not_match') }}');
          return;
        }

        var formData = $(this).serialize();
        var url = userId ? `/users/${userId}` : '/users';
        var method = userId ? 'PUT' : 'POST';

        $.ajax({
          url: url,
          type: method,
          data: formData,
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(response) {
            if (response.success) {
              $('#userModal').modal('hide');
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
        var userId = $(this).data('id');

        $.ajax({
          url: `/users/${userId}`,
          type: 'GET',
          success: function(user) {
            $('#user_id').val(user.user_id);
            $('#login_name').val(user.login_name);
            $('#profile_id').val(user.profile_id);
            $('#branch_id').val(user.branch_id);
            $('#sys_cash_limit').val(user.sys_cash_limit);
            $('#status').val(user.status);

            // Set staff select
            if (user.staff) {
              staffSelect.addOption({
                staff_id: user.staff_id,
                full_name: user.staff.full_name,
                ic_no: user.staff.ic_no
              });
              staffSelect.setValue(user.staff_id);
            }

            $('#modalTitle').html('<i class="fas fa-user-edit me-2"></i>Edit User');
            $('#passwordRow').hide();
            $('#password').prop('required', false);
            $('#password_confirm').prop('required', false);
            $('#statusRow').show();
            $('#userModal').modal('show');
          },
          error: function(xhr) {
            toastr.error('{{ __('common.pagination.failed_to_load_user_data') }}');
          }
        });
      });

      // View Button
      $(document).on('click', '.view-btn', function() {
        var userId = $(this).data('id');

        $.ajax({
          url: `/users/${userId}`,
          type: 'GET',
          success: function(user) {
            $('#view_user_id').text(user.user_id);
            $('#view_login_name').text(user.login_name);
            $('#view_staff_name').text(user.staff ? user.staff.full_name : 'N/A');
            $('#view_profile').text(user.profile ? user.profile.profile : 'N/A');
            $('#view_branch').text(user.branch ? user.branch.branch_name : 'N/A');
            $('#view_cash_limit').text(user.sys_cash_limit ? '$' + parseFloat(user.sys_cash_limit)
              .toLocaleString() : '-');

            var statusLabels = {
              0: '<span class="badge bg-success">{{ __('common.general.active') }}</span>',
              1: '<span class="badge bg-warning">{{ __('common.general.suspended') }}</span>',
              2: '<span class="badge bg-danger">{{ __('common.general.deleted') }}</span>'
            };
            $('#view_status').html(statusLabels[user.status] || '{{ __('common.general.unknown') }}');
            $('#view_failed_log').text(user.failed_log || 0);
            $('#view_pwd_expire').text(user.next_pwd_expire || '{{ __('common.general.not_set') }}');
            $('#view_log_ip').text(user.log_ip || '{{ __('common.general.never_logged_in') }}');

            $('#viewUserModal').modal('show');
          },
          error: function(xhr) {
            toastr.error('{{ __('common.pagination.failed_to_load_user_data') }}');
          }
        });
      });

      // Reset Password Button
      $(document).on('click', '.reset-pwd-btn', function() {
        var userId = $(this).data('id');

        $.ajax({
          url: `/users/${userId}`,
          type: 'GET',
          success: function(user) {
            $('#reset_user_id').val(user.user_id);
            $('#reset_user_name').text(user.login_name);
            $('#resetPasswordForm')[0].reset();
            $('#resetPasswordModal').modal('show');
          }
        });
      });

      // Reset Password Form Submit
      $('#resetPasswordForm').on('submit', function(e) {
        e.preventDefault();

        var newPassword = $('#new_password').val();
        var confirmPassword = $('#confirm_new_password').val();

        if (newPassword !== confirmPassword) {
          toastr.error('{{ __('common.general.passwords_do_not_match') }}');
          return;
        }

        var userId = $('#reset_user_id').val();

        $.ajax({
          url: `/users/${userId}/reset-password`,
          type: 'POST',
          data: {
            password: newPassword
          },
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(response) {
            if (response.success) {
              $('#resetPasswordModal').modal('hide');
              toastr.success(response.message);
            }
          },
          error: function(xhr) {
            if (xhr.responseJSON && xhr.responseJSON.errors) {
              $.each(xhr.responseJSON.errors, function(key, value) {
                toastr.error(value[0]);
              });
            } else {
              toastr.error('{{ __('common.general.failed_to_reset_password') }}');
            }
          }
        });
      });

      // Delete Button
      $(document).on('click', '.delete-btn', function() {
        var userId = $(this).data('id');

        Swal.fire({
          title: '{{ __('common.general.are_you_sure') }}',
          text: "This will mark the user as deleted. They won't be able to login.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: '{{ __('common.general.yes_delete_it') }}'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: `/users/${userId}`,
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
                Swal.fire('Error!', xhr.responseJSON.message ||
                  '{{ __('common.general.failed_to_delete_user') }}', 'error');
              }
            });
          }
        });
      });
    });
  </script>
@endpush
