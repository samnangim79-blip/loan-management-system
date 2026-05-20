@extends('admin.layouts.admin_layout')

@section('title', 'Collateral Management')

@section('content')
  <div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 mb-0 text-gray-800">{{ __('common.nav.collaterals') }}</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Collaterals</li>
          </ol>
        </nav>
      </div>
      <div>
        <button type="button" class="btn btn-primary" id="addCollateralBtn">
          <i class="fas fa-plus me-1"></i> Add Collateral
        </button>
      </div>
    </div>

    <!-- Filters Card -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
          <i class="fas fa-filter me-2"></i>Filters
        </h6>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-3 mb-3">
            <label for="filterType" class="form-label">{{ __('common.general.collateral_type') }}</label>
            <select class="form-control" id="filterType">
              <option value="">{{ __('common.general.all_types') }}</option>
            </select>
          </div>
          <div class="col-md-3 mb-3">
            <label for="filterStatus" class="form-label">{{ __('common.general.status') }}</label>
            <select class="form-control" id="filterStatus">
              <option value="">{{ __('common.general.all_status') }}</option>
              <option value="active">{{ __('common.form.active') }}</option>
              <option value="released">{{ __('common.form.released') }}</option>
            </select>
          </div>
          <div class="col-md-3 mb-3">
            <label for="filterDateFrom" class="form-label">{{ __('common.general.date_from') }}</label>
            <input type="date" class="form-control" id="filterDateFrom">
          </div>
          <div class="col-md-3 mb-3">
            <label for="filterDateTo" class="form-label">{{ __('common.general.date_to') }}</label>
            <input type="date" class="form-control" id="filterDateTo">
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="button" class="btn btn-secondary" id="resetFilters">
              <i class="fas fa-undo me-1"></i> Reset Filters
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Data Table Card -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
          <i class="fas fa-shield-alt me-2"></i>Collaterals List
        </h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped" id="collateralsTable" width="100%">
            <thead>
              <tr>
                <th>ID</th>
                <th>{{ __('common.pagination.customer') }}</th>
                <th>{{ __('common.general.loan_contract') }}</th>
                <th>{{ __('common.general.type') }}</th>
                <th>{{ __('common.general.description') }}</th>
                <th>{{ __('common.general.value') }}</th>
                <th>{{ __('common.general.status') }}</th>
                <th>{{ __('common.general.actions') }}</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Add/Edit Modal -->
  <div class="modal fade" id="collateralModal" tabindex="-1" aria-labelledby="collateralModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="collateralModalLabel">{{ __('common.general.add_collateral') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="collateralForm">
          @csrf
          <input type="hidden" name="collateral_id" id="collateralId">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="loanScheduleId" class="form-label">Loan Contract <span class="text-danger">*</span></label>
                <select class="form-control" id="loanScheduleId" name="loan_schedule_id" required>
                  <option value="">{{ __('common.form.select_loan') }}</option>
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label for="collateralTypeId" class="form-label">Collateral Type <span
                    class="text-danger">*</span></label>
                <select class="form-control" id="collateralTypeId" name="collateral_type_id" required>
                  <option value="">{{ __('common.general.select_type') }}</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 mb-3">
                <label for="collateralNo" class="form-label">Collateral No</label>
                <input type="text" class="form-control" id="collateralNo" name="collateral_no">
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="collateralValue" class="form-label">Collateral Value <span
                    class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text">$</span>
                  <input type="number" class="form-control" id="collateralValue" name="collateral_value"
                    step="0.01" min="0" required>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label for="dateIssue" class="form-label">Date Issue <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="dateIssue" name="date_issue" required>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12 mb-3">
                <label for="remarks" class="form-label">{{ __('common.general.remarks') }}</label>
                <textarea class="form-control" id="remarks" name="remarks" rows="2"></textarea>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary"
              data-bs-dismiss="modal">{{ __('common.general.cancel') }}</button>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save me-1"></i> Save
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- View Modal -->
  <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewModalLabel">{{ __('common.general.collateral_details') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="viewContent">
          <!-- Content loaded via AJAX -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary"
            data-bs-dismiss="modal">{{ __('common.general.close') }}</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('styles')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush

@push('scripts')
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script>
    $(document).ready(function() {
      // Initialize DataTable
      var table = $('#collateralsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '{{ route('collaterals.data') }}',
          data: function(d) {
            d.status_filter = $('#filterStatus').val();
            d.type_filter = $('#filterType').val();
            d.date_from = $('#filterDateFrom').val();
            d.date_to = $('#filterDateTo').val();
          },
          error: function(xhr, error, code) {
            console.log('DataTable AJAX Error:', error, code);
            toastr.error('Failed to load collateral data. Please check if the server is running.');
          }
        },
        columns: [{
            data: 'id',
            name: 'id'
          },
          {
            data: 'customer_name',
            name: 'customer_name'
          },
          {
            data: 'loan_contract',
            name: 'loan_contract'
          },
          {
            data: 'type',
            name: 'type'
          },
          {
            data: 'description',
            name: 'remarks',
            defaultContent: '-'
          },
          {
            data: 'value',
            name: 'value'
          },
          {
            data: 'status',
            name: 'status'
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
        pageLength: 25
      });

      // Load collateral types for filter
      loadCollateralTypes();

      // Filter change handlers
      $('#filterType, #filterStatus, #filterDateFrom, #filterDateTo').on('change', function() {
        table.draw();
      });

      // Reset filters
      $('#resetFilters').on('click', function() {
        $('#filterType, #filterStatus').val('');
        $('#filterDateFrom, #filterDateTo').val('');
        table.draw();
      });

      // Add Collateral button
      $('#addCollateralBtn').on('click', function() {
        console.log('Add Collateral button clicked'); // Debug log
        try {
          resetForm();
          $('#collateralModalLabel').text('{{ __('common.general.add_collateral') }}');

          // Load data first
          loadLoans();
          loadCollateralTypesForSelect();

          // Small delay to ensure data is loaded
          setTimeout(function() {
            console.log('Attempting to show modal'); // Debug log
            var modalElement = document.getElementById('collateralModal');
            if (modalElement) {
              var modal = new bootstrap.Modal(modalElement);
              modal.show();
              console.log('Modal shown successfully'); // Debug log
            } else {
              console.error('Modal element not found');
            }
          }, 100);
        } catch (error) {
          console.error('Error in Add Collateral button handler:', error);
        }
      });

      // Edit button handler
      $(document).on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        $('#collateralModalLabel').text('{{ __('common.general.edit_collateral') }}');
        loadLoans();
        loadCollateralTypesForSelect();

        $.get('{{ url('collaterals') }}/' + id, function(response) {
          if (response.success) {
            var data = response.data;
            $('#collateralId').val(data.collateral_id);
            $('#loanScheduleId').val(data.loan_schedule_id);
            $('#collateralTypeId').val(data.collateral_type_id);
            $('#collateralNo').val(data.collateral_no);
            $('#collateralValue').val(data.collateral_value);
            $('#dateIssue').val(data.date_issue);
            $('#remarks').val(data.remarks);

            // Show modal with Bootstrap 5 syntax
            var modalElement = document.getElementById('collateralModal');
            if (modalElement) {
              var modal = new bootstrap.Modal(modalElement);
              modal.show();
            }
          } else {
            toastr.error(response.message || 'Failed to load collateral data');
          }
        }).fail(function(xhr) {
          toastr.error('Error loading collateral data');
          console.error('AJAX error:', xhr);
        });
      });

      // View button handler
      $(document).on('click', '.view-btn', function() {
        var id = $(this).data('id');
        $.get('{{ url('collaterals') }}/' + id, function(response) {
          if (response.success) {
            var data = response.data;
            var html = `
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Customer:</strong> ${data.loan_schedule?.account?.customer?.name_en || 'N/A'}</p>
                            <p><strong>Loan Contract:</strong> ${data.loan_schedule?.contract_no || 'N/A'}</p>
                            <p><strong>Type:</strong> ${data.collateral_type?.collateral_type || 'N/A'}</p>
                            <p><strong>Collateral No:</strong> ${data.collateral_no || 'N/A'}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Value:</strong> $${parseFloat(data.collateral_value || 0).toLocaleString('en-US', {minimumFractionDigits: 2})}</p>
                            <p><strong>Date Issue:</strong> ${data.date_issue || 'N/A'}</p>
                            <p><strong>Remarks:</strong> ${data.remarks || 'N/A'}</p>
                        </div>
                    </div>
                `;
            $('#viewContent').html(html);

            // Show modal with Bootstrap 5 syntax
            var modalElement = document.getElementById('viewModal');
            if (modalElement) {
              var modal = new bootstrap.Modal(modalElement);
              modal.show();
            }
          }
        });
      });

      // Delete button handler
      $(document).on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        Swal.fire({
          title: '{{ __('common.general.are_you_sure') }}',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: '{{ __('common.general.yes_delete_it') }}'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: '{{ url('collaterals') }}/' + id,
              type: 'DELETE',
              data: {
                _token: '{{ csrf_token() }}'
              },
              success: function(response) {
                if (response.success) {
                  toastr.success(response.message);
                  table.draw();
                } else {
                  toastr.error(response.message);
                }
              },
              error: function(xhr) {
                toastr.error('{{ __('common.messages.an_error_occurred') }}');
              }
            });
          }
        });
      });

      // Release button handler
      $(document).on('click', '.release-btn', function() {
        var id = $(this).data('id');
        Swal.fire({
          title: '{{ __('common.general.release_collateral') }}',
          text: "{{ __('common.general.this_will_mark_the_collateral_as_released') }}",
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#6c757d',
          confirmButtonText: '{{ __('common.general.yes_release_it') }}'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: '{{ url('collaterals') }}/' + id + '/release',
              type: 'POST',
              data: {
                _token: '{{ csrf_token() }}'
              },
              success: function(response) {
                if (response.success) {
                  toastr.success(response.message);
                  table.draw();
                } else {
                  toastr.error(response.message);
                }
              },
              error: function(xhr) {
                toastr.error('{{ __('common.messages.an_error_occurred') }}');
              }
            });
          }
        });
      });

      // Form submit handler
      $('#collateralForm').on('submit', function(e) {
        e.preventDefault();
        var id = $('#collateralId').val();
        var url = id ? '{{ url('collaterals') }}/' + id : '{{ route('collaterals.store') }}';
        var method = id ? 'PUT' : 'POST';

        $.ajax({
          url: url,
          type: method,
          data: $(this).serialize(),
          success: function(response) {
            if (response.success) {
              // Hide modal with Bootstrap 5 syntax
              var modalElement = document.getElementById('collateralModal');
              if (modalElement) {
                var modal = bootstrap.Modal.getInstance(modalElement);
                if (modal) {
                  modal.hide();
                }
              }
              toastr.success(response.message);
              table.draw();
            } else {
              toastr.error(response.message);
            }
          },
          error: function(xhr) {
            if (xhr.status === 422) {
              var errors = xhr.responseJSON.errors;
              $.each(errors, function(key, value) {
                toastr.error(value[0]);
              });
            } else {
              toastr.error('{{ __('common.messages.an_error_occurred') }}');
            }
          }
        });
      });

      function resetForm() {
        $('#collateralForm')[0].reset();
        $('#collateralId').val('');
      }

      function loadCollateralTypes() {
        $.get('{{ route('api.collateral-types') }}', function(response) {
          if (response.success) {
            var options = '<option value="">{{ __('common.general.all_types') }}</option>';
            $.each(response.data, function(index, type) {
              options += '<option value="' + type.collateral_type_id + '">' + type.collateral_type +
                '</option>';
            });
            $('#filterType').html(options);
          }
        }).fail(function() {
          console.error('Failed to load collateral types');
          toastr.error('Failed to load collateral types');
        });
      }

      function loadCollateralTypesForSelect() {
        $.get('{{ route('api.collateral-types') }}', function(response) {
          if (response.success) {
            var options = '<option value="">{{ __('common.general.select_type') }}</option>';
            $.each(response.data, function(index, type) {
              options += '<option value="' + type.collateral_type_id + '">' + type.collateral_type +
                '</option>';
            });
            $('#collateralTypeId').html(options);
          }
        }).fail(function() {
          console.error('Failed to load collateral types');
          toastr.error('Failed to load collateral types');
        });
      }

      function loadLoans() {
        $.get('{{ route('api.loans.active') }}', function(response) {
          if (response.success) {
            var options = '<option value="">{{ __('common.form.select_loan') }}</option>';
            $.each(response.data, function(index, loan) {
              options += '<option value="' + loan.loan_schedule_id + '">' + loan.label + '</option>';
            });
            $('#loanScheduleId').html(options);
          }
        }).fail(function() {
          console.error('Failed to load active loans');
          toastr.error('Failed to load active loans');
        });
      }
    });
  </script>
@endpush
