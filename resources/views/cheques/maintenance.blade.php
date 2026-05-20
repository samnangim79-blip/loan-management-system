@extends('admin.layouts.admin_layout')

@section('title', __('common.general.cheque_maintenance'))

@section('content')
  <div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 mb-0 text-gray-800">{{ __('common.nav.cheque_maintenance') }}</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('common.nav.dashboard') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cheques.index') }}">{{ __('common.nav.cheques') }}</a></li>
            <li class="breadcrumb-item active">{{ __('common.nav.maintenance') }}</li>
          </ol>
        </nav>
      </div>
      <div>
        @if (user_has_permission('create_cheque_maintenance') || user_is_super_admin())
          <button type="button" class="btn btn-primary" id="newMaintenanceBtn">
            <i class="fas fa-plus me-1"></i> {{ __('common.actions.add_stock') }}
          </button>
        @endif
      </div>
    </div>

    <!-- Info Card -->
    <div class="alert alert-info mb-4">
      <i class="fas fa-info-circle me-2"></i>
      <strong>{{ __('common.general.cheque_maintenance') }}</strong> {{ __('common.messages.cheque_maintenance_description') }}
    </div>

    <!-- Filters Card -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
          <i class="fas fa-filter me-2"></i>{{ __('common.general.filters') }}
        </h6>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-3">
            <label for="filterBranch" class="form-label">{{ __('common.general.branch') }}</label>
            <select class="form-control" id="filterBranch">
              <option value="">{{ __('common.general.all_branches') }}</option>
            </select>
          </div>
          <div class="col-md-3">
            <label for="filterStatus" class="form-label">{{ __('common.general.status') }}</label>
            <select class="form-control" id="filterStatus">
              <option value="">{{ __('common.general.all_statuses') }}</option>
              <option value="pending">{{ __('common.general.pending') }}</option>
              <option value="approved">{{ __('common.general.approved') }}</option>
            </select>
          </div>
          <div class="col-md-3">
            <label for="filterDateFrom" class="form-label">{{ __('common.general.date_from') }}</label>
            <input type="date" class="form-control" id="filterDateFrom">
          </div>
          <div class="col-md-3">
            <label for="filterDateTo" class="form-label">{{ __('common.general.date_to') }}</label>
            <input type="date" class="form-control" id="filterDateTo">
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-12">
            <button type="button" class="btn btn-primary" id="applyFilters">
              <i class="fas fa-filter me-1"></i> {{ __('common.actions.apply_filters') }}
            </button>
            <button type="button" class="btn btn-secondary" id="resetFilters">
              <i class="fas fa-undo me-1"></i> {{ __('common.actions.reset_filters') }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Maintenance List Card -->
    <div class="card shadow mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
          <i class="fas fa-list me-2"></i>{{ __('common.general.cheque_stock_records') }}
        </h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped" id="maintenanceTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>{{ __('common.general.id') }}</th>
                <th>{{ __('common.general.branch') }}</th>
                <th>{{ __('common.general.transaction_date') }}</th>
                <th>{{ __('common.general.quantity') }}</th>
                <th>{{ __('common.general.cheque_from') }}</th>
                <th>{{ __('common.general.cheque_to') }}</th>
                <th>{{ __('common.general.status') }}</th>
                <th width="100">{{ __('common.general.actions') }}</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Add Stock Modal -->
  <div class="modal fade" id="maintenanceModal" tabindex="-1" aria-labelledby="maintenanceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="maintenanceModalLabel">{{ __('common.general.add_cheque_stock') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="maintenanceForm">
          @csrf
          <div class="modal-body">
            <div class="mb-3">
              <label for="branch_id" class="form-label">{{ __('common.general.branch') }} <span class="text-danger">*</span></label>
              <select class="form-control" id="branch_id" name="branch_id" required>
                <option value="">{{ __('common.general.select_branch') }}</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="tran_date" class="form-label">{{ __('common.general.transaction_date') }} <span class="text-danger">*</span></label>
              <input type="date" class="form-control" id="tran_date" name="tran_date" required
                value="{{ date('Y-m-d') }}">
            </div>

            <div class="mb-3">
              <label for="qty" class="form-label">{{ __('common.general.quantity_books') }} <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="qty" name="qty" required min="1"
                placeholder="{{ __('common.general.eg_10') }}">
            </div>

            <div class="mb-3">
              <label for="chq_from_no" class="form-label">{{ __('common.general.cheque_number_from') }} <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="chq_from_no" name="chq_from_no" required
                placeholder="{{ __('common.general.eg_000001') }}">
            </div>

            <div class="mb-3">
              <label for="chq_to_no" class="form-label">{{ __('common.general.cheque_number_to') }} <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="chq_to_no" name="chq_to_no" required
                placeholder="{{ __('common.general.eg_000250') }}">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary"
              data-bs-dismiss="modal">{{ __('common.actions.cancel') }}</button>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save me-1"></i> {{ __('common.actions.add_stock') }}
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
      // Load branches for both filter and modal
      $.ajax({
        url: "{{ route('api.branches') }}",
        type: 'GET',
        success: function(response) {
          if (response && response.length > 0) {
            response.forEach(function(branch) {
              $('#branch_id').append('<option value="' + branch.branch_id + '">' + branch.branch_name +
                '</option>');
              $('#filterBranch').append('<option value="' + branch.branch_id + '">' + branch.branch_name +
                '</option>');
            });
          }
        }
      });

      // Initialize DataTable
      var table = $('#maintenanceTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ route('cheques.maintenance-data') }}",
          type: 'GET',
          data: function(d) {
            d.branch_id = $('#filterBranch').val();
            d.status = $('#filterStatus').val();
            d.date_from = $('#filterDateFrom').val();
            d.date_to = $('#filterDateTo').val();
          }
        },
        columns: [{
            data: 'chq_id',
            name: 'chq_id'
          },
          {
            data: 'branch_name',
            name: 'branch_name'
          },
          {
            data: 'tran_date',
            name: 'tran_date',
            render: function(data) {
              return data ? new Date(data).toLocaleDateString() : '-';
            }
          },
          {
            data: 'qty',
            name: 'qty'
          },
          {
            data: 'chq_from_no',
            name: 'chq_from_no'
          },
          {
            data: 'chq_to_no',
            name: 'chq_to_no'
          },
          {
            data: null,
            render: function(data, type, row) {
              if (row.approved_date) {
                return '<span class="badge bg-success">{{ __('common.general.approved') }}</span>';
              }
              return '<span class="badge bg-warning">{{ __('common.general.pending') }}</span>';
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
          [0, 'desc']
        ],
        responsive: true,
        language: {
          processing: '<i class="fas fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">{{ __('common.general.loading') }}</span>'
        }
      });

      // Apply filters
      $('#applyFilters').on('click', function() {
        table.draw();
      });

      // Reset filters
      $('#resetFilters').on('click', function() {
        $('#filterBranch').val('');
        $('#filterStatus').val('');
        $('#filterDateFrom').val('');
        $('#filterDateTo').val('');
        table.draw();
      });

      // New Maintenance button
      $('#newMaintenanceBtn').on('click', function() {
        $('#maintenanceForm')[0].reset();
        $('#tran_date').val(new Date().toISOString().split('T')[0]);
        $('#maintenanceModal').modal('show');
      });

      // Form submit
      $('#maintenanceForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
          url: "{{ route('cheques.store-maintenance') }}",
          type: 'POST',
          data: $(this).serialize(),
          success: function(response) {
            if (response.success) {
              $('#maintenanceModal').modal('hide');
              table.ajax.reload();
              toastr.success(response.message);
            } else {
              toastr.error(response.message || '{{ __('common.messages.an_error_occurred') }}');
            }
          },
          error: function(xhr) {
            if (xhr.responseJSON && xhr.responseJSON.errors) {
              var errors = xhr.responseJSON.errors;
              var errorMessage = '';
              for (var key in errors) {
                errorMessage += errors[key][0] + '\n';
              }
              toastr.error(errorMessage);
            } else {
              toastr.error('{{ __('common.messages.an_error_occurred_while_saving') }}');
            }
          }
        });
      });

      // Approve button
      $(document).on('click', '.approve-btn', function() {
        var id = $(this).data('id');

        Swal.fire({
          title: '{{ __('common.general.approve_stock_entry') }}',
          text: "{{ __('common.messages.approve_stock_entry_confirmation') }}",
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#28a745',
          cancelButtonColor: '#6c757d',
          confirmButtonText: '{{ __('common.actions.yes_approve') }}'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: "{{ route('cheques.approve-maintenance', ':id') }}".replace(':id', id),
              type: 'POST',
              data: {
                _token: '{{ csrf_token() }}'
              },
              success: function(response) {
                if (response.success) {
                  table.ajax.reload();
                  toastr.success(response.message);
                } else {
                  toastr.error(response.message || '{{ __('common.messages.cannot_approve') }}');
                }
              },
              error: function(xhr) {
                toastr.error('{{ __('common.messages.an_error_occurred_while_approving') }}');
              }
            });
          }
        });
      });
    });
  </script>
@endpush
