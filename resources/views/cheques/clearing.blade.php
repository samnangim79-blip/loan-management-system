@extends('admin.layouts.admin_layout')

@section('title', __('common.form.cheque_clearing'))

@section('content')
  <div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 mb-0 text-gray-800">{{ __('common.nav.cheque_clearing') }}</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('common.nav.dashboard') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cheques.index') }}">{{ __('common.nav.cheques') }}</a></li>
            <li class="breadcrumb-item active">{{ __('common.nav.clearing') }}</li>
          </ol>
        </nav>
      </div>
      <div>
        @if (user_has_permission('create_cheque_clearing') || user_is_super_admin())
          <button type="button" class="btn btn-success" id="newClearBtn">
            <i class="fas fa-check-double me-1"></i> {{ __('common.actions.clear_cheque') }}
          </button>
        @endif
      </div>
    </div>

    <!-- Info Card -->
    <div class="alert alert-success mb-4">
      <i class="fas fa-info-circle me-2"></i>
      <strong>{{ __('common.nav.cheque_clearing') }}</strong> {{ __('common.messages.cheque_clearing_description') }}
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
          <div class="col-md-4">
            <label for="filterChequeNo" class="form-label">{{ __('common.general.cheque_number') }}</label>
            <input type="text" class="form-control" id="filterChequeNo" placeholder="{{ __('common.general.search_by_cheque_number') }}">
          </div>
          <div class="col-md-4">
            <label for="filterDateFrom" class="form-label">{{ __('common.general.date_from') }}</label>
            <input type="date" class="form-control" id="filterDateFrom">
          </div>
          <div class="col-md-4">
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

    <!-- Clearing List Card -->
    <div class="card shadow mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
          <i class="fas fa-list me-2"></i>{{ __('common.general.cleared_cheques') }}
        </h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped" id="clearingTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>{{ __('common.general.clear_id') }}</th>
                <th>{{ __('common.general.cheque_number') }}</th>
                <th>{{ __('common.general.transaction_id') }}</th>
                <th>{{ __('common.general.clear_date') }}</th>
                <th>{{ __('common.general.cleared_by') }}</th>
                <th width="80">{{ __('common.general.actions') }}</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Clear Cheque Modal -->
  <div class="modal fade" id="clearModal" tabindex="-1" aria-labelledby="clearModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title" id="clearModalLabel"><i class="fas fa-check-double me-2"></i>{{ __('common.actions.clear_cheque') }}</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="clearForm">
          @csrf
          <div class="modal-body">
            <div class="mb-3">
              <label for="chq_no" class="form-label">{{ __('common.general.cheque_number') }} <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="chq_no" name="chq_no" required
                placeholder="{{ __('common.general.enter_cheque_number') }}">
              <div class="form-text">{{ __('common.messages.enter_cheque_number_to_verify') }}</div>
            </div>

            <div class="mb-3">
              <label for="tran_id" class="form-label">{{ __('common.general.transaction') }} <span class="text-danger">*</span></label>
              <select class="form-control" id="tran_id" name="tran_id" required>
                <option value="">{{ __('common.general.select_transaction') }}</option>
              </select>
              <div class="form-text">{{ __('common.messages.link_cheque_to_transaction') }}</div>
            </div>

            <div class="alert alert-info mb-0">
              <i class="fas fa-info-circle me-2"></i>
              <strong>{{ __('common.general.note') }}:</strong> {{ __('common.messages.verify_cheque_not_stopped') }}
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary"
              data-bs-dismiss="modal">{{ __('common.actions.cancel') }}</button>
            <button type="submit" class="btn btn-success">
              <i class="fas fa-check me-1"></i> {{ __('common.actions.clear_cheque') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- View Details Modal -->
  <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewModalLabel">{{ __('common.general.clearing_details') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <table class="table table-striped">
            <tr>
              <th width="40%">{{ __('common.general.clear_id') }}</th>
              <td id="viewClearId"></td>
            </tr>
            <tr>
              <th>{{ __('common.general.cheque_number') }}</th>
              <td id="viewChequeNo"></td>
            </tr>
            <tr>
              <th>{{ __('common.general.transaction_id') }}</th>
              <td id="viewTranId"></td>
            </tr>
            <tr>
              <th>{{ __('common.general.cleared_by') }}</th>
              <td id="viewClearedBy"></td>
            </tr>
            <tr>
              <th>{{ __('common.general.clear_date') }}</th>
              <td id="viewClearDate"></td>
            </tr>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary"
            data-bs-dismiss="modal">{{ __('common.actions.close') }}</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    $(document).ready(function() {
      // Initialize Tom Select for transaction dropdown
      var tranSelect = new TomSelect('#tran_id', {
        valueField: 'tran_id',
        labelField: 'display',
        searchField: ['tran_id'],
        load: function(query, callback) {
          if (!query.length) return callback();
          $.ajax({
            url: "{{ route('dashboard') }}",
            type: 'GET',
            dataType: 'json',
            data: {
              q: query
            },
            error: function() {
              callback();
            },
            success: function(res) {
              var items = res.map(function(item) {
                return {
                  tran_id: item.tran_id,
                  display: 'TXN#' + item.tran_id + ' - $' + parseFloat(item.amount || 0).toFixed(2)
                };
              });
              callback(items);
            }
          });
        }
      });

      // Initialize DataTable
      var table = $('#clearingTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ route('cheques.clearing-data') }}",
          type: 'GET',
          data: function(d) {
            d.chq_no = $('#filterChequeNo').val();
            d.date_from = $('#filterDateFrom').val();
            d.date_to = $('#filterDateTo').val();
          }
        },
        columns: [{
            data: 'chq_clear_id',
            name: 'chq_clear_id'
          },
          {
            data: 'chq_no',
            name: 'chq_no'
          },
          {
            data: 'tran_id',
            name: 'tran_id'
          },
          {
            data: 'clear_date',
            name: 'clear_date',
            render: function(data) {
              return data ? new Date(data).toLocaleDateString() : '-';
            }
          },
          {
            data: 'clear_by',
            name: 'clear_by'
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
        $('#filterChequeNo').val('');
        $('#filterDateFrom').val('');
        $('#filterDateTo').val('');
        table.draw();
      });

      // New Clear button
      $('#newClearBtn').click(function() {
        $('#clearForm')[0].reset();
        tranSelect.clear();
        $('#clearModal').modal('show');
      });

      // Form submit
      $('#clearForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
          url: "{{ route('cheques.store-clear') }}",
          type: 'POST',
          data: $(this).serialize(),
          success: function(response) {
            if (response.success) {
              $('#clearModal').modal('hide');
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
              toastr.error('{{ __('common.messages.an_error_occurred_while_clearing') }}');
            }
          }
        });
      });

      // View button
      $(document).on('click', '.view-btn', function() {
        var row = table.row($(this).closest('tr')).data();

        $('#viewClearId').text(row.chq_clear_id);
        $('#viewChequeNo').text(row.chq_no);
        $('#viewTranId').text(row.tran_id);
        $('#viewClearedBy').text(row.clear_by || '-');
        $('#viewClearDate').text(row.clear_date ? new Date(row.clear_date).toLocaleDateString() : '-');
        $('#viewModal').modal('show');
      });
    });
  </script>
@endpush
