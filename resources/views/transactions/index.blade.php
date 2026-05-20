@extends('admin.layouts.admin_layout')

@section('title', __('common.transaction_management.title'))

@section('content')
  <div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 mb-0 text-gray-800">{{ __('common.transaction_management.transactions') }}</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('common.nav.dashboard') }}</a>
            </li>
            <li class="breadcrumb-item active">{{ __('common.transaction_management.transactions') }}</li>
          </ol>
        </nav>
      </div>
    </div>

    <!-- Filters Card -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
          <i class="fas fa-filter me-2"></i>{{ __('common.transaction_management.filters') }}
        </h6>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-3 mb-3">
            <label for="filterDateFrom" class="form-label">{{ __('common.transaction_management.date_from') }}</label>
            <input type="date" class="form-control" id="filterDateFrom">
          </div>
          <div class="col-md-3 mb-3">
            <label for="filterDateTo" class="form-label">{{ __('common.transaction_management.date_to') }}</label>
            <input type="date" class="form-control" id="filterDateTo">
          </div>
          <div class="col-md-3 mb-3">
            <label for="filterType" class="form-label">{{ __('common.transaction_management.transaction_type') }}</label>
            <select class="form-control" id="filterType">
              <option value="">{{ __('common.transaction_management.all_types') }}</option>
              <option value="1">{{ __('common.transaction_management.deposit') }}</option>
              <option value="2">{{ __('common.transaction_management.withdrawal') }}</option>
              <option value="3">{{ __('common.transaction_management.transfer') }}</option>
              <option value="4">{{ __('common.transaction_management.loan_disbursement') }}</option>
              <option value="5">{{ __('common.transaction_management.loan_payment') }}</option>
              <option value="6">{{ __('common.transaction_management.interest') }}</option>
              <option value="7">{{ __('common.transaction_management.fee') }}</option>
            </select>
          </div>
          <div class="col-md-3 mb-3">
            <label for="filterBranch" class="form-label">{{ __('common.form.branch') }}</label>
            <select class="form-control" id="filterBranch">
              <option value="">{{ __('common.form.all_branches') }}</option>
            </select>
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

    <!-- Summary Cards -->
    <div class="row mb-4">
      <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                  {{ __('common.pagination.total_deposits') }}</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalDeposits">$0.00</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-arrow-down fa-2x text-success"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-warning shadow h-100 py-2">
          <div class="card-body">
            <div class="row">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                  {{ __('common.pagination.total_withdrawals') }}</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalWithdrawals">$0.00</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-arrow-up fa-2x text-warning"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                  {{ __('common.general.loan_disbursed') }}</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalDisbursed">$0.00</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-hand-holding-usd fa-2x text-primary"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-info shadow h-100 py-2">
          <div class="card-body">
            <div class="row">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                  {{ __('common.general.loan_payments') }}</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalPayments">$0.00</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-credit-card fa-2x text-info"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Data Table Card -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
          <i class="fas fa-exchange-alt me-2"></i>Transaction History
        </h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped" id="transactionsTable" width="100%">
            <thead class="table-dark">
              <tr>
                <th>ID</th>
                <th>{{ __('common.general.date') }}</th>
                <th>{{ __('common.general.type') }}</th>
                <th>{{ __('common.general.branch') }}</th>
                <th>{{ __('common.general.description') }}</th>
                <th class="text-end">{{ __('common.general.amount') }}</th>
                <th>{{ __('common.general.currency') }}</th>
                <th>{{ __('common.general.user') }}</th>
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

  <!-- View Transaction Modal -->
  <div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">{{ __('common.general.transaction_details') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="transactionDetails">
          <!-- Content loaded via AJAX -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary"
            data-bs-dismiss="modal">{{ __('common.general.close') }}</button>
          <button type="button" class="btn btn-primary" id="printTransaction">
            <i class="fas fa-print me-1"></i> Print
          </button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('styles')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <style>
    .border-left-success {
      border-left: 4px solid #1cc88a !important;
    }

    .border-left-warning {
      border-left: 4px solid #f6c23e !important;
    }

    .border-left-primary {
      border-left: 4px solid #4e73df !important;
    }

    .border-left-info {
      border-left: 4px solid #36b9cc !important;
    }
  </style>
@endpush

@push('scripts')
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script>
    $(document).ready(function() {
      // Initialize DataTable
      var table = $('#transactionsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '{{ route('transactions.data') }}',
          data: function(d) {
            d.date_from = $('#filterDateFrom').val();
            d.date_to = $('#filterDateTo').val();
            d.tran_type = $('#filterType').val();
            d.branch_id = $('#filterBranch').val();
          }
        },
        columns: [{
            data: 'tran_id',
            name: 'tran_id'
          },
          {
            data: 'formatted_date',
            name: 'tran_date'
          },
          {
            data: 'type_badge',
            name: 'tran_type',
            orderable: false,
            searchable: false
          },
          {
            data: 'branch_name',
            name: 'branch.branch_name'
          },
          {
            data: 'discription',
            name: 'discription'
          },
          {
            data: 'formatted_amount',
            name: 'amount',
            className: 'text-end'
          },
          {
            data: 'currency_code',
            name: 'currency.currency'
          },
          {
            data: 'user_name',
            name: 'user.name'
          },
          {
            data: 'status_badge',
            name: 'approved_by',
            orderable: false,
            searchable: false
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

      // Load branches for filter
      loadBranches();

      // Apply filters
      $('#applyFilters').on('click', function() {
        table.draw();
        loadSummary();
      });

      // Reset filters
      $('#resetFilters').on('click', function() {
        $('#filterDateFrom, #filterDateTo').val('');
        $('#filterType, #filterBranch').val('');
        table.draw();
        loadSummary();
      });

      // View transaction details
      $(document).on('click', '.view-btn', function() {
        var id = $(this).data('id');
        $.get('{{ url('transactions') }}/' + id, function(data) {
          var html = `
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-striped">
                                <tr>
                                    <th width="40%">Transaction ID:</th>
                                    <td>${data.tran_id}</td>
                                </tr>
                                <tr>
                                    <th>Date:</th>
                                    <td>${data.tran_date}</td>
                                </tr>
                                <tr>
                                    <th>Type:</th>
                                    <td>${getTypeName(data.tran_type)}</td>
                                </tr>
                                <tr>
                                    <th>User:</th>
                                    <td>${data.user?.first_name || 'N/A'} ${data.user?.last_name || ''}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-striped">
                                <tr>
                                    <th width="40%">Amount:</th>
                                    <td class="text-end">$${parseFloat(data.amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                                </tr>
                                <tr>
                                    <th>Currency:</th>
                                    <td>${data.currency?.currency || 'USD'}</td>
                                </tr>
                                <tr>
                                    <th>Branch:</th>
                                    <td>${data.branch?.branch_name || 'N/A'}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>${data.approved_by ? '<span class="badge bg-success">Approved</span>' : '<span class="badge bg-warning">Pending</span>'}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <strong>Description:</strong>
                            <p class="mb-0">${data.discription || '{{ __('common.general.no_description') }}'}</p>
                        </div>
                    </div>
                `;

          // Add transaction details if available
          if (data.tran_details && data.tran_details.length > 0) {
            html += `
                        <div class="row mt-4">
                            <div class="col-12">
                                <h6 class="">{{ __('common.general.transaction_details') }}</h6>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ __('common.general.gl_account') }}</th>
                                            <th class="text-end">{{ __('common.general.debit') }}</th>
                                            <th class="text-end">{{ __('common.general.credit') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                    `;
            data.tran_details.forEach(function(detail) {
              html += `
                            <tr>
                                <td>${detail.gl?.gl_name || detail.gl_id}</td>
                                <td class="text-end">${detail.debit_amount ? '$' + parseFloat(detail.debit_amount).toLocaleString('en-US', {minimumFractionDigits: 2}) : '-'}</td>
                                <td class="text-end">${detail.credit_amount ? '$' + parseFloat(detail.credit_amount).toLocaleString('en-US', {minimumFractionDigits: 2}) : '-'}</td>
                            </tr>
                        `;
            });
            html += `
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    `;
          }

          $('#transactionDetails').html(html);

          // Show modal with Bootstrap 5 syntax
          var modalElement = document.getElementById('transactionModal');
          if (modalElement) {
            var modal = new bootstrap.Modal(modalElement);
            modal.show();
          }
        }).fail(function() {
          toastr.error('Failed to load transaction details');
        });
      });

      // Print transaction from modal
      $('#printTransaction').on('click', function() {
        window.print();
      });

      // Handle print button clicks in DataTable rows
      $(document).on('click', '.print-btn', function() {
        var id = $(this).data('id');
        // Open transaction details in a new window for printing
        var printUrl = '{{ url('transactions') }}/' + id + '/print';
        var printWindow = window.open(printUrl, '_blank', 'width=800,height=600');
        printWindow.focus();
      });

      // Load initial summary
      loadSummary();

      function loadBranches() {
        $.get('{{ url('api/branches') }}', function(response) {
          var options = '<option value="">{{ __('common.form.all_branches') }}</option>';
          $.each(response, function(i, branch) {
            options += '<option value="' + branch.branch_id + '">' + branch.branch_name + '</option>';
          });
          $('#filterBranch').html(options);
        }).fail(function() {
          console.error('Failed to load branches');
          toastr.error('Failed to load branches');
        });
      }

      function loadSummary() {
        $.get('{{ url('transactions/summary') }}', {
          date_from: $('#filterDateFrom').val(),
          date_to: $('#filterDateTo').val()
        }, function(response) {
          if (response.success) {
            $('#totalDeposits').text('$' + parseFloat(response.data.deposits || 0).toLocaleString(
              'en-US', {
                minimumFractionDigits: 2
              }));
            $('#totalWithdrawals').text('$' + parseFloat(response.totalWithdrawals || 0).toLocaleString(
              'en-US', {
                minimumFractionDigits: 2
              }));
            $('#totalDisbursed').text('$' + parseFloat(response.totalDisbursed || 0).toLocaleString(
              'en-US', {
                minimumFractionDigits: 2
              }));
            $('#totalPayments').text('$' + parseFloat(response.totalPayments || 0).toLocaleString(
              'en-US', {
                minimumFractionDigits: 2
              }));
          }
        }).fail(function() {
          console.error('Failed to load transaction summary');
        });
      }

      function getTypeName(type) {
        var types = {
          1: 'Deposit',
          2: 'Loan Disbursement',
          3: 'Withdrawal',
          4: 'Loan Payment',
          5: 'Interest Payment',
          6: 'Fixed Deposit',
          7: 'Service Fee',
          8: 'Wire Transfer',
          9: 'Currency Exchange',
          10: 'Penalty Fee',
          11: 'FD Maturity',
          12: 'Incoming Wire'
        };
        return types[type] || 'Unknown Type';
      }
    });
  </script>
@endpush
