@extends('admin.layouts.admin_layout')

@section('title', __('common.general.cash_transfers'))

@section('content')
  <div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 mb-0 text-gray-800">{{ __('common.nav.cash_transfers') }}</h1>
        <nav aria-label="Close">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Transactions</a></li>
            <li class="breadcrumb-item active">Cash Transfers</li>
          </ol>
        </nav>
      </div>
      <div>
        <button type="button" class="btn btn-primary" id="newTransferBtn">
          <i class="fas fa-exchange-alt me-1"></i> New Transfer
        </button>
      </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
      <div class="col-xl-4 col-md-6 mb-3">
        <div class="card border-left-warning shadow h-100 py-2">
          <div class="card-body">
            <div class="row">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                  {{ __('common.general.pending_transfers') }}</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800" id="pendingCount">0</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-clock fa-2x text-warning"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-4 col-md-6 mb-3">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                  {{ __('common.pagination.completed_today') }}</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800" id="completedCount">0</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-check-circle fa-2x text-success"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-4 col-md-6 mb-3">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                  {{ __('common.general.pending_amount') }}</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800" id="pendingAmount">$0.00</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-dollar-sign fa-2x text-primary"></i>
              </div>
            </div>
          </div>
        </div>
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
            <label for="filterStatus" class="form-label">{{ __('common.general.status') }}</label>
            <select class="form-control" id="filterStatus">
              <option value="">{{ __('common.general.all_status') }}</option>
              <option value="0">{{ __('common.form.pending') }}</option>
              <option value="1">{{ __('common.form.received') }}</option>
            </select>
          </div>
          <div class="col-md-3 mb-3">
            <label for="filterType" class="form-label">{{ __('common.general.type') }}</label>
            <select class="form-control" id="filterType">
              <option value="">{{ __('common.general.all_types') }}</option>
              <option value="i">{{ __('common.form.transfer_in') }}</option>
              <option value="o">{{ __('common.form.transfer_out') }}</option>
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
            <button type="button" class="btn btn-primary btn-sm me-2" id="applyFilters">
              <i class="fas fa-search me-1"></i> Apply Filters
            </button>
            <button type="button" class="btn btn-secondary btn-sm" id="resetFilters">
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
          <i class="fas fa-exchange-alt me-2"></i>Cash Transfers List
        </h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped" id="transfersTable" width="100%">
            <thead>
              <tr>
                <th>ID</th>
                <th>{{ __('common.general.date') }}</th>
                <th>{{ __('common.general.type') }}</th>
                <th>{{ __('common.general.currency') }}</th>
                <th class="text-end">{{ __('common.general.amount') }}</th>
                <th>From/To</th>
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

  <!-- New Transfer Modal -->
  <div class="modal fade" id="transferModal" tabindex="-1" aria-labelledby="transferModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="transferModalLabel">
            <i class="fas fa-exchange-alt me-2"></i>New Cash Transfer
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="transferForm">
          @csrf
          <div class="modal-body">
            <div class="mb-3">
              <label for="transferDate" class="form-label">Transfer Date <span class="text-danger">*</span></label>
              <input type="date" class="form-control" id="transferDate" name="TRAN_DATE"
                value="{{ date(__('common.form.ymd')) }}" required>
            </div>
            <div class="mb-3">
              <label for="transferType" class="form-label">Transfer Type <span class="text-danger">*</span></label>
              <select class="form-control" id="transferType" name="IN_OU" required>
                <option value="">{{ __('common.general.select_type') }}</option>
                <option value="i">Transfer In (Receiving)</option>
                <option value="o">Transfer Out (Sending)</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="transferCurrency" class="form-label">Currency <span class="text-danger">*</span></label>
              <select class="form-control" id="transferCurrency" name="CCY_ID" required>
                <option value="">{{ __('common.form.select_currency') }}</option>
                @foreach ($currencies as $currency)
                  <option value="{{ $currency->ccy_id }}">{{ $currency->currency }} - {{ $currency->description }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label for="transferAmount" class="form-label">Amount <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="number" class="form-control" id="transferAmount" name="amount" step="0.01"
                  min="0.01" required>
              </div>
            </div>
            <div class="mb-3">
              <label for="transferBranch" class="form-label">From/To Branch</label>
              <input type="text" class="form-control" id="transferBranch" name="FROM_TO"
                placeholder="{{ __('common.general.enter_branch_name') }}">
            </div>
            <div class="mb-3">
              <label for="transferRemark" class="form-label">{{ __('common.form.remark') }}</label>
              <textarea class="form-control" id="transferRemark" name="remark" rows="2"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary"
              data-bs-dismiss="modal">{{ __('common.general.cancel') }}</button>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save me-1"></i> Create Transfer
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
          <h5 class="modal-title" id="viewModalLabel">{{ __('common.general.transfer_details') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="viewModalBody">
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
  <style>
    .border-left-warning {
      border-left: 4px solid #f6c23e !important;
    }

    .border-left-success {
      border-left: 4px solid #1cc88a !important;
    }

    .border-left-primary {
      border-left: 4px solid #4e73df !important;
    }
  </style>
@endpush

@push('scripts')
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script>
    $(document).ready(function() {
      // Initialize DataTable
      var table = $('#transfersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '{{ route('cash.transfers-data') }}',
          data: function(d) {
            d.status = $('#filterStatus').val();
            d.type = $('#filterType').val();
            d.date_from = $('#filterDateFrom').val();
            d.date_to = $('#filterDateTo').val();
          }
        },
        columns: [{
            data: 'pendding_cash_transfer_id',
            name: 'pendding_cash_transfer_id'
          },
          {
            data: 'sent_date',
            name: 'sent_date'
          },
          {
            data: 'in_out_badge',
            name: 'in_ou'
          },
          {
            data: 'currency_code',
            name: 'ccy_id'
          },
          {
            data: 'formatted_amount',
            name: 'amount',
            className: 'text-end'
          },
          {
            data: 'remark',
            name: 'remark'
          },
          {
            data: 'status_badge',
            name: 'status_id'
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

      // Load summary
      loadSummary();

      // Apply filters
      $('#applyFilters').on('click', function() {
        table.draw();
      });

      // Reset filters
      $('#resetFilters').on('click', function() {
        $('#filterStatus, #filterType').val('');
        $('#filterDateFrom, #filterDateTo').val('');
        table.draw();
      });

      // New transfer button
      $('#newTransferBtn').on('click', function() {
        $('#transferForm')[0].reset();
        $('#transferDate').val('{{ date(__('common.form.ymd')) }}');
        $('#transferModal').modal('show');
      });

      // Form submit
      $('#transferForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
          url: '{{ route('cash.store-transfer') }}',
          type: 'POST',
          data: $(this).serialize(),
          success: function(response) {
            if (response.success) {
              $('#transferModal').modal('hide');
              toastr.success(response.message);
              table.draw();
              loadSummary();
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

      // View button
      $(document).on('click', '.view-btn', function() {
        var id = $(this).data('id');
        $.get('{{ url('cash/transfers') }}/' + id, function(response) {
          if (response.success) {
            var data = response.data;
            var statusClass = data.status_id == 0 ? 'warning' : 'success';
            var statusName = data.status_id == 0 ? 'Pending' : 'Received';
            var typeClass = data.in_ou == 'i' ? 'info' : 'secondary';
            var typeName = data.in_ou == 'i' ? 'Transfer In' : 'Transfer Out';

            var html = `
                    <table class="table table-striped">
                        <tr>
                            <th width="40%">Transfer ID:</th>
                            <td>${data.pendding_cash_transfer_id}</td>
                        </tr>
                        <tr>
                            <th>Date:</th>
                            <td>${data.sent_date}</td>
                        </tr>
                        <tr>
                            <th>Type:</th>
                            <td><span class="badge bg-${typeClass}">${typeName}</span></td>
                        </tr>
                        <tr>
                            <th>Currency:</th>
                            <td>${data.currency?.currency || 'N/A'}</td>
                        </tr>
                        <tr>
                            <th>Amount:</th>
                            <td class="text-end">$${parseFloat(data.amount).toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                        </tr>
                        <tr>
                            <th>From/To:</th>
                            <td>${data.from_to || 'N/A'}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td><span class="badge bg-${statusClass}">${statusName}</span></td>
                        </tr>
                        <tr>
                            <th>Remark:</th>
                            <td>${data.remark || 'N/A'}</td>
                        </tr>
                    </table>
                `;
            $('#viewModalBody').html(html);
            $('#viewModal').modal('show');
          }
        });
      });

      // Receive button
      $(document).on('click', '.receive-btn', function() {
        var id = $(this).data('id');
        Swal.fire({
          title: 'Confirm Receipt?',
          text: "Mark this transfer as received?",
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#1cc88a',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Yes, Confirm!'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: '{{ url('cash/transfers') }}/' + id + '/receive',
              type: 'POST',
              data: {
                _token: '{{ csrf_token() }}'
              },
              success: function(response) {
                if (response.success) {
                  toastr.success(response.message);
                  table.draw();
                  loadSummary();
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

      function loadSummary() {
        $.get('{{ route('cash.transfers-summary') }}', function(response) {
          if (response.success) {
            $('#pendingCount').text(response.data.pending_count || 0);
            $('#completedCount').text(response.data.completed_today || 0);
            $('#pendingAmount').text('$' + parseFloat(response.data.pending_amount || 0).toLocaleString(
              'en-US', {
                minimumFractionDigits: 2
              }));
          }
        });
      }
    });
  </script>
@endpush
