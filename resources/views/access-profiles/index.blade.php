@extends('admin.layouts.admin_layout')

@section('pageTitle', '{{ __('common.nav.access_profiles') }}')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Access Profiles</a></li>
  <li class="breadcrumb-item active">List</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            <i class="fas fa-user-shield me-2"></i>Access Profiles (Roles)
          </h3>
          <div class="card-tools">
            @if (user_has_permission('access_profile_add') || user_is_super_admin())
              <button type="button" class="btn btn-primary btn-sm" id="">
                <i class="fas fa-plus"></i> Add Profile
              </button>
            @endif
          </div>
        </div>
        <div class="card-body">
          <table id="" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>{{ __('common.general.profile_name') }}</th>
                <th>{{ __('common.general.transaction_limits') }}</th>
                <th>{{ __('common.general.action') }}</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Profile Modal -->
  <div class="modal fade" id="" tabindex="-1">
    <div class="modal fade">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">
            <i class="fas fa-user-shield me-2"></i>Add Access Profile
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="">
          @csrf
          <div class="">
            <input type="hidden" id="profile_id" name="profile_id">

            <div class="mb-3">
              <label for="profile" class="form-label">Profile Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="profile" name="profile" required maxlength="50">
              <small class="">e.g., Branch Manager, Loan Officer, Teller</small>
            </div>

            <h6 class="fw-bold text-primary mt-4 mb-3">{{ __('common.general.transaction_limits') }}</h6>
            <small class="text-muted d-block mb-3">Set to 0 for unlimited access</small>

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="DEPOSIT_LIMIT" class="form-label">{{ __('common.form.deposit_limit') }}</label>
                  <div class="">
                    <span class="">$</span>
                    <input type="number" class="form-control" id="deposit_limit" name="deposit_limit" step="0.01"
                      min="0" value="0">
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="WITHDRAWAL_LIMIT" class="form-label">{{ __('common.form.withdrawal_limit') }}</label>
                  <div class="">
                    <span class="">$</span>
                    <input type="number" class="form-control" id="withdrawal_limit" name="withdrawal_limit"
                      step="0.01" min="0" value="0">
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="LOAN_LIMIT" class="form-label">{{ __('common.form.loan_limit') }}</label>
                  <div class="">
                    <span class="">$</span>
                    <input type="number" class="form-control" id="loan_limit" name="loan_limit" step="0.01"
                      min="0" value="0">
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="NON_CASH_LIMIT" class="form-label">Non-Cash Limit</label>
                  <div class="">
                    <span class="">$</span>
                    <input type="number" class="form-control" id="non_cash_limit" name="non_cash_limit" step="0.01"
                      min="0" value="0">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal fade">
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
              <i class="fas fa-times me-1"></i>Close
            </button>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save me-1"></i>Save Profile
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- View Profile Modal -->
  <div class="modal fade" id="" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            <i class="fas fa-user-shield me-2"></i>Profile Details
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="">
          <div class="row">
            <div class="col-md-6">
              <h6 class="fw-bold text-primary mb-3">{{ __('common.nav.profile_information') }}</h6>
              <table class="table table-striped">
                <tr>
                  <th width="50%">Profile ID:</th>
                  <td id="view_profile_id"></td>
                </tr>
                <tr>
                  <th>Profile Name:</th>
                  <td id="view_profile_name"></td>
                </tr>
              </table>
            </div>
            <div class="col-md-6">
              <h6 class="fw-bold text-primary mb-3">{{ __('common.general.transaction_limits') }}</h6>
              <table class="table table-striped">
                <tr>
                  <th width="50%">Deposit Limit:</th>
                  <td id="view_deposit_limit"></td>
                </tr>
                <tr>
                  <th>Withdrawal Limit:</th>
                  <td id="view_withdrawal_limit"></td>
                </tr>
                <tr>
                  <th>Loan Limit:</th>
                  <td id="view_loan_limit"></td>
                </tr>
                <tr>
                  <th>Non-Cash Limit:</th>
                  <td id="view_non_cash_limit"></td>
                </tr>
              </table>
            </div>
          </div>
          <hr>
          <h6 class="fw-bold text-primary mb-3">{{ __('common.general.assigned_permissions') }}</h6>
          <div id="view_permissions" class="row"></div>
        </div>
        <div class="modal fade">
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">{{ __('common.general.close') }}</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Permissions Modal -->
  <div class="modal fade" id="" tabindex="-1">
    <div class="modal fade">
      <div class="modal-content">
        <div class="">
          <h5 class="modal-title">
            <i class="fas fa-shield-alt me-2"></i>Manage Permissions: <span id="permissions_profile_name"></span>
          </h5>
          <button type="button" class="" data-bs-dismiss="modal"></button>
        </div>
        <form id="">
          @csrf
          <div class="">
            <input type="hidden" id="permissions_profile_id" name="permissions_profile_id">

            <div class="mb-3">
              <button type="button" class="btn btn-sm btn-outline-primary me-2" id="">
                <i class=""></i> Select All
              </button>
              <button type="button" class="btn btn-primary" id="">
                <i class=""></i> Deselect All
              </button>
            </div>

            <div class="row" id="">
              @php
                $moduleGroups = [
                    'Dashboard & Reports' => $modules->whereIn('module_id', range(1, 9)),
                    '{{ __('common.pagination.customer_management') }}' => $modules->whereIn('module_id', range(10, 19)),
                    '{{ __('common.general.account_management') }}' => $modules->whereIn('module_id', range(20, 29)),
                    '{{ __('common.general.loan_management') }}' => $modules->whereIn('module_id', range(30, 49)),
                    '{{ __('common.general.transactions') }}' => $modules->whereIn('module_id', range(50, 69)),
                    '{{ __('common.general.collateral') }}' => $modules->whereIn('module_id', range(70, 79)),
                    '{{ __('common.general.fixed_deposit') }}' => $modules->whereIn('module_id', range(80, 89)),
                    '{{ __('common.general.general_ledger') }}' => $modules->whereIn('module_id', range(90, 99)),
                    '{{ __('common.general.fixed_assets') }}' => $modules->whereIn('module_id', range(100, 109)),
                    '{{ __('common.general.staff_management') }}' => $modules->whereIn('module_id', range(110, 119)),
                    '{{ __('common.general.user_management') }}' => $modules->whereIn('module_id', range(120, 129)),
                    '{{ __('common.nav.access_profiles') }}' => $modules->whereIn('module_id', range(130, 139)),
                    '{{ __('common.general.branch_management') }}' => $modules->whereIn('module_id', range(140, 149)),
                    '{{ __('common.nav.system_settings') }}' => $modules->whereIn('module_id', range(150, 169)),
                    '{{ __('common.general.location_management') }}' => $modules->whereIn('module_id', range(170, 179)),
                    'Audit & Logs' => $modules->whereIn('module_id', range(180, 189)),
                    'Backup & Maintenance' => $modules->whereIn('module_id', range(190, 199)),
                ];
              @endphp

              @foreach ($moduleGroups as $groupName => $groupModules)
                @if ($groupModules->count() > 0)
                  <div class="col-md-4 mb-4">
                    <div class="card h-100">
                      <div class="card-header bg-light py-2">
                        <div class="">
                          <input class="form-control" type="text"
                            id="group_{{ Str::slug($groupName) }}" data-group="{{ Str::slug($groupName) }}">
                          <label class="form-control" for="group_{{ Str::slug($groupName) }}">
                            {{ $groupName }}
                          </label>
                        </div>
                      </div>
                      <div class="card-body py-2">
                        @foreach ($groupModules as $module)
                          <div class="">
                            <input class="form-check-input module-checkbox module-{{ Str::slug($groupName) }}"
                              type="text" name="modules[]" value="{{ $module->module_id }}"
                              id="module_{{ $module->module_id }}">
                            <label class="form-control" for="module_{{ $module->module_id }}">
                              {{ $module->module }}
                              <small class="">{{ $module->control_name }}</small>
                            </label>
                          </div>
                        @endforeach
                      </div>
                    </div>
                  </div>
                @endif
              @endforeach
            </div>
          </div>
          <div class="modal fade">
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
              <i class="fas fa-times me-1"></i>Cancel
            </button>
            <button type="submit" class="">
              <i class="fas fa-save me-1"></i>Save Permissions
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
      var table = $('#profilesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('dashboard') }}",
        columns: [{
            data: 'profile_id',
            name: 'profile_id'
          },
          {
            data: '{{ __('common.nav.profile') }}',
            name: '{{ __('common.nav.profile') }}'
          },
          {
            data: '{{ __('common.general.limits') }}',
            name: '{{ __('common.general.limits') }}',
            orderable: false,
            searchable: false
          },
          {
            data: '{{ __('common.general.action') }}',
            name: '{{ __('common.general.action') }}',
            orderable: false,
            searchable: false
          }
        ],
        order: [
          [0, '{{ __('common.general.asc') }}']
        ],
        pageLength: 10,
        responsive: true
      });

      // Add Profile Button
      $('#addProfileBtn').click(function() {
        $('#profileForm')[0].reset();
        $('#profile_id').val('');
        $('#DEPOSIT_LIMIT').val(0);
        $('#WITHDRAWAL_LIMIT').val(0);
        $('#LOAN_LIMIT').val(0);
        $('#NON_CASH_LIMIT').val(0);
        $('#modalTitle').html('<i class="fas fa-user-shield me-2"></i>Add Access Profile');
        $('#profileModal').modal('{{ __('common.general.show') }}');
      });

      // Form Submit
      $('#profileForm').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();
        var profileId = $('#profile_id').val();
        var url = profileId ? `/access-profiles/${profileId}` : '/access-profiles';
        var method = profileId ? '{{ __('common.general.put') }}' : '{{ __('common.general.post') }}';

        $.ajax({
          url: url,
          type: method,
          data: formData,
          success: function(response) {
            if (response.success) {
              $('#profileModal').modal('{{ __('common.general.hide') }}');
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
      $(document).on('submit', '.edit-btn', function() {
        var profileId = $(this).data('id');

        $.ajax({
          url: `/access-profiles/${profileId}`,
          type: 'GET',
          success: function(profile) {
            $('#profile_id').val(profile.profile_id);
            $('#profile').val(profile.profile);
            $('#deposit_limit').val(profile.deposit_limit || 0);
            $('#withdrawal_limit').val(profile.withdrawal_limit || 0);
            $('#loan_limit').val(profile.loan_limit || 0);
            $('#non_cash_limit').val(profile.non_cash_limit || 0);

            $('#modalTitle').html('<i class="fas fa-user-shield me-2"></i>Edit Access Profile');
            $('#profileModal').modal('{{ __('common.general.show') }}');
          },
          error: function(xhr) {
            toastr.error('{{ __('common.nav.failed_to_load_profile_data') }}');
          }
        });
      });

      // View Button
      $(document).on('submit', '.view-btn', function() {
        var profileId = $(this).data('id');

        $.ajax({
          url: `/access-profiles/${profileId}`,
          type: 'GET',
          success: function(profile) {
            $('#view_profile_id').text(profile.profile_id);
            $('#view_profile_name').text(profile.profile);

            var formatLimit = function(val) {
              return val == 0 ? '<span class="">{{ __('common.general.unlimited') }}</span>' : '$' + parseFloat(val)
                .toLocaleString('{{ __('common.general.enus') }}', {
                  minimumFractionDigits: 2
                });
            };

            $('#view_deposit_limit').html(formatLimit(profile.deposit_limit));
            $('#view_withdrawal_limit').html(formatLimit(profile.withdrawal_limit));
            $('#view_loan_limit').html(formatLimit(profile.loan_limit));
            $('#view_non_cash_limit').html(formatLimit(profile.non_cash_limit));

            // Display permissions
            var permissionsHtml = '';
            if (profile.modules && profile.modules.length > 0) {
              profile.modules.forEach(function(module) {
                permissionsHtml +=
                  '<div class="col-md-4 mb-1"><small><i class="fas fa-check text-success me-1"></i>' +
                  module.module + '</small></div>';
              });
            } else {
              permissionsHtml =
                '<div class="col-12"><p class="text-muted">{{ __('common.general.no_permissions_assigned') }}</p></div>';
            }
            $('#view_permissions').html(permissionsHtml);

            $('#viewProfileModal').modal('{{ __('common.general.show') }}');
          },
          error: function(xhr) {
            toastr.error('{{ __('common.nav.failed_to_load_profile_data') }}');
          }
        });
      });

      // Permissions Button
      $(document).on('submit', '.permissions-btn', function() {
        var profileId = $(this).data('id');

        $.ajax({
          url: `/access-profiles/${profileId}/permissions`,
          type: 'GET',
          success: function(data) {
            $('#permissions_profile_id').val(profileId);

            // Get profile name
            $.ajax({
              url: `/access-profiles/${profileId}`,
              type: 'GET',
              success: function(profile) {
                $('#permissions_profile_name').text(profile.profile);
              }
            });

            // Reset all checkboxes
            $('.module-checkbox').prop('checked', false);
            $('.group-checkbox').prop('checked', false);

            // Check assigned modules
            data.assigned.forEach(function(moduleId) {
              $('#module_' + moduleId).prop('checked', true);
            });

            // Update group checkboxes
            updateGroupCheckboxes();

            $('#permissionsModal').modal('{{ __('common.general.show') }}');
          },
          error: function(xhr) {
            toastr.error('{{ __('common.pagination.failed_to_load_permissions') }}');
          }
        });
      });

      // Group checkbox functionality
      $(document).on('submit', '.group-checkbox', function() {
        var group = $(this).data('{{ __('common.general.group') }}');
        var isChecked = $(this).prop('checked');
        $('.module-' + group).prop('checked', isChecked);
      });

      // Update group checkbox when individual modules change
      $(document).on('submit', '.module-checkbox', function() {
        updateGroupCheckboxes();
      });

      function updateGroupCheckboxes() {
        $('.group-checkbox').each(function() {
          var group = $(this).data('{{ __('common.general.group') }}');
          var total = $('.module-' + group).length;
          var checked = $('.module-' + group + ':checked').length;
          $(this).prop('checked', total === checked);
          $(this).prop('indeterminate', checked > 0 && checked < total);
        });
      }

      // Select All
      $('#selectAllBtn').click(function() {
        $('.module-checkbox').prop('checked', true);
        $('.group-checkbox').prop('checked', true).prop('indeterminate', false);
      });

      // Deselect All
      $('#deselectAllBtn').click(function() {
        $('.module-checkbox').prop('checked', false);
        $('.group-checkbox').prop('checked', false).prop('indeterminate', false);
      });

      // Permissions Form Submit
      $('#permissionsForm').on('submit', function(e) {
        e.preventDefault();

        var profileId = $('#permissions_profile_id').val();
        var modules = [];
        $('.module-checkbox:checked').each(function() {
          modules.push($(this).val());
        });

        $.ajax({
          url: `/access-profiles/${profileId}/permissions`,
          type: 'PUT',
          data: {
            _token: $('input[name="_token"]').val(),
            modules: modules
          },
          success: function(response) {
            if (response.success) {
              $('#permissionsModal').modal('{{ __('common.general.hide') }}');
              toastr.success(response.message);
            }
          },
          error: function(xhr) {
            if (xhr.responseJSON && xhr.responseJSON.message) {
              toastr.error(xhr.responseJSON.message);
            } else {
              toastr.error('{{ __('common.general.failed_to_update_permissions') }}');
            }
          }
        });
      });

      // Delete Button
      $(document).on('submit', '.delete-btn', function() {
        var profileId = $(this).data('id');

        Swal.fire({
          title: '{{ __('common.general.are_you_sure') }}',
          text: "This will permanently delete the access profile. Profiles with existing users cannot be deleted.",
          icon: '{{ __('common.messages.warning') }}',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: '{{ __('common.general.yes_delete_it') }}'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: `/access-profiles/${profileId}`,
              type: 'DELETE',
              data: {
                _token: $('input[name="_token"]').val()
              },
              success: function(response) {
                if (response.success) {
                  table.ajax.reload();
                  Swal.fire('{{ __('common.general.deleted') }}', response.message, '{{ __('common.messages.success') }}');
                }
              },
              error: function(xhr) {
                Swal.fire('{{ __('common.messages.error') }}', xhr.responseJSON.message || '{{ __('common.general.failed_to_delete_profile') }}', '{{ __('common.messages.error') }}');
              }
            });
          }
        });
      });
    });
  </script>
@endpush
