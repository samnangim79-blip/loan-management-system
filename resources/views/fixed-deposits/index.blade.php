@extends('admin.layouts.admin_layout')

@section('title', __('fixed_deposits.management'))

@section('content')
  <div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 mb-0 text-gray-800">{{ __('common.nav.fixed_deposits') }}</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('common.nav.dashboard') }}</a></li>
            <li class="breadcrumb-item active">{{ __('common.nav.fixed_deposits') }}</li>
          </ol>
        </nav>
      </div>
      <div>
        <button type="button" class="btn btn-primary" id="createFdBtn">
          <i class="fas fa-plus me-1"></i> {{ __('common.actions.new_fd_account') }}
        </button>
      </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
      <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                  {{ __('common.nav.total_fd_accounts') }}</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalFdAccounts">0</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-piggy-bank fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                  {{ __('common.general.active_deposits') }}</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800" id="activeDeposits">0</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">{{ __('common.general.matured') }}
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800" id="maturedDeposits">0</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                  {{ __('common.pagination.total_value') }}</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalValue">$0.00</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
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
          <i class="fas fa-filter me-2"></i>{{ __('common.general.filters') }}
        </h6>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-3 mb-3">
            <label for="filterTerm" class="form-label">{{ __('common.form.term') }}</label>
            <select class="form-control" id="filterTerm">
              <option value="">{{ __('common.form.all_terms') }}</option>
              @foreach ($terms as $term)
                <option value="{{ $term->fd_term_id }}">{{ $term->term_name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3 mb-3">
            <label for="filterOption" class="form-label">{{ __('common.form.option') }}</label>
            <select class="form-control" id="filterOption">
              <option value="">{{ __('common.form.all_options') }}</option>
              @foreach ($options as $option)
                <option value="{{ $option->fd_option_id }}">{{ $option->fd_option }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3 mb-3">
            <label for="filterStatus" class="form-label">{{ __('common.general.status') }}</label>
            <select class="form-control" id="filterStatus">
              <option value="">{{ __('common.general.all_status') }}</option>
              <option value="active">{{ __('common.form.active') }}</option>
              <option value="matured">{{ __('common.form.matured') }}</option>
            </select>
          </div>
          <div class="col-md-3 mb-3">
            <label for="filterSearch" class="form-label">{{ __('common.general.search') }}</label>
            <input type="text" class="form-control" id="filterSearch"
              placeholder="{{ __('common.general.certificate_id_or_customer') }}">
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="button" class="btn btn-primary btn-sm me-2" id="applyFilters">
              <i class="fas fa-search me-1"></i> {{ __('common.actions.apply_filters') }}
            </button>
            <button type="button" class="btn btn-secondary btn-sm" id="resetFilters">
              <i class="fas fa-undo me-1"></i> {{ __('common.actions.reset_filters') }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Data Table Card -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
          <i class="fas fa-piggy-bank me-2"></i>{{ __('common.general.fixed_deposit_certificates') }}
        </h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped" id="fixedDepositsTable" width="100%">
            <thead class="table-dark">
              <tr>
                <th>{{ __('common.general.certificate_id') }}</th>
                <th>{{ __('common.pagination.customer') }}</th>
                <th>{{ __('common.general.account_no') }}</th>
                <th>{{ __('common.general.term') }}</th>
                <th>{{ __('common.general.issue_date') }}</th>
                <th>{{ __('common.general.maturity_date') }}</th>
                <th class="text-end">{{ __('common.general.amount') }}</th>
                <th>{{ __('common.general.rate') }}</th>
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

  <!-- New FD Modal -->
  <div class="modal fade" id="fdModal" tabindex="-1" aria-labelledby="fdModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="fdModalLabel">
            <i class="fas fa-piggy-bank me-2"></i>{{ __('common.actions.new_fixed_deposit') }}
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="fdForm">
          @csrf
          <input type="hidden" name="FD_CERT_ID" id="fdCertId">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="certNumber" class="form-label">{{ __('common.general.certificate_number') }} <span
                    class="text-danger">*</span></label>
                <input type="text" class="form-control" id="certNumber" name="FD_CERT_ID" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="accountId" class="form-label">{{ __('common.general.account') }} <span class="text-danger">*</span></label>
                <select class="form-control" id="accountId" name="ACCT_ID" required>
                  <option value="">{{ __('common.form.select_account') }}</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="issueDate" class="form-label">{{ __('common.general.issue_date') }} <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="issueDate" name="DATE_ISSUE"
                  value="{{ date('Y-m-d') }}" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="fdTerm" class="form-label">{{ __('common.general.term') }} <span class="text-danger">*</span></label>
                <select class="form-control" id="fdTerm" name="FD_TERM_ID" required>
                  <option value="">{{ __('common.form.select_term') }}</option>
                  @foreach ($terms as $term)
                    <option value="{{ $term->fd_term_id }}" data-months="{{ $term->num_of_month }}">
                      {{ $term->term_name }} ({{ $term->num_of_month }} {{ __('common.general.months') }})
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="fdOption" class="form-label">{{ __('common.form.option') }} <span class="text-danger">*</span></label>
                <select class="form-control" id="fdOption" name="FD_OPTION_ID" required>
                  <option value="">{{ __('common.form.select_option') }}</option>
                  @foreach ($options as $option)
                    <option value="{{ $option->fd_option_id }}">{{ $option->fd_option }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label for="amount" class="form-label">{{ __('common.general.amount') }} <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text">$</span>
                  <input type="number" class="form-control" id="amount" name="amount" step="0.01"
                    min="0" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 mb-3">
                <label for="intRate" class="form-label">{{ __('common.general.interest_rate_percent') }} <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="intRate" name="INT_RATE" step="0.01"
                  min="0" max="100" required>
              </div>
              <div class="col-md-4 mb-3">
                <label for="extraRate" class="form-label">{{ __('common.general.extra_rate_percent') }}</label>
                <input type="number" class="form-control" id="extraRate" name="EXTRA_RATE" step="0.01"
                  min="0" max="100" value="0">
              </div>
              <div class="col-md-4 mb-3">
                <label for="maturityDate" class="form-label">{{ __('common.general.maturity_date') }}</label>
                <input type="date" class="form-control" id="maturityDate" name="MATURED_DATE" readonly>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="acctForInt" class="form-label">{{ __('common.general.interest_credit_account') }}</label>
                <input type="text" class="form-control" id="acctForInt" name="ACCT_FOR_INT"
                  placeholder="{{ __('common.form.account_for_interest_payment') }}">
              </div>
              <div class="col-md-6 mb-3">
                <label for="acctForPrin" class="form-label">{{ __('common.general.principal_credit_account') }}</label>
                <input type="text" class="form-control" id="acctForPrin" name="ACCT_FOR_PRIN"
                  placeholder="{{ __('common.form.account_for_principal_payment') }}">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary"
              data-bs-dismiss="modal">{{ __('common.general.cancel') }}</button>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save me-1"></i> {{ __('common.actions.save') }}
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
          <h5 class="modal-title" id="viewModalLabel">{{ __('common.general.fixed_deposit_details') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="viewModalBody">
          <!-- Content loaded via AJAX -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary"
            data-bs-dismiss="modal">{{ __('common.general.close') }}</button>
          <button type="button" class="btn btn-primary" id="printCertBtn">
            <i class="fas fa-print me-1"></i> {{ __('common.actions.print_certificate') }}
          </button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('styles')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <style>
    .border-left-primary {
      border-left: 4px solid #4e73df !important;
    }

    .border-left-success {
      border-left: 4px solid #1cc88a !important;
    }

    .border-left-info {
      border-left: 4px solid #36b9cc !important;
    }

    .border-left-warning {
      border-left: 4px solid #f6c23e !important;
    }
  </style>
@endpush

@push('scripts')
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script>
    $(document).ready(function() {
      // Initialize DataTable
      var table = $('#fixedDepositsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '{{ route('fixed-deposits.data') }}',
          data: function(d) {
            d.term = $('#filterTerm').val();
            d.option = $('#filterOption').val();
            d.status = $('#filterStatus').val();
            d.search_term = $('#filterSearch').val();
          }
        },
        columns: [{
            data: 'fd_cert_id',
            name: 'fd_cert_id'
          },
          {
            data: 'customer_name',
            name: 'customer_name'
          },
          {
            data: 'account_no',
            name: 'account_no'
          },
          {
            data: 'term_name',
            name: 'term_name'
          },
          {
            data: 'date_issue',
            name: 'date_issue',
            render: function(data) {
              return data ? new Date(data).toLocaleDateString() : 'N/A';
            }
          },
          {
            data: 'matured_date',
            name: 'matured_date',
            render: function(data) {
              return data ? new Date(data).toLocaleDateString() : 'N/A';
            }
          },
          {
            data: 'formatted_amount',
            name: 'amount',
            className: 'text-end'
          },
          {
            data: 'int_rate',
            name: 'int_rate',
            render: function(data) {
              return data + '%';
            }
          },
          {
            data: 'status',
            name: 'status',
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

      // Apply filters
      $('#applyFilters').on('click', function() {
        table.draw();
      });

      // Reset filters
      $('#resetFilters').on('click', function() {
        $('#filterTerm, #filterOption, #filterStatus').val('');
        $('#filterSearch').val('');
        table.draw();
      });

      // New FD button
      $('#createFdBtn').on('click', function() {
        resetForm();
        $('#fdModalLabel').html('<i class="fas fa-piggy-bank me-2"></i>{{ __('common.actions.new_fixed_deposit') }}');
        loadAccounts();
        $('#fdModal').modal('show');
      });

      // Calculate maturity date when term or issue date changes
      $('#fdTerm, #issueDate').on('change', function() {
        calculateMaturityDate();
      });

      // Form submit
      $('#fdForm').on('submit', function(e) {
        e.preventDefault();
        var id = $('#fdCertId').val();
        var url = id ? '{{ url('fixed-deposits') }}/' + id : '{{ route('fixed-deposits.store') }}';
        var method = id ? 'PUT' : 'POST';

        $.ajax({
          url: url,
          type: method,
          data: $(this).serialize(),
          success: function(response) {
            if (response.success) {
              $('#fdModal').modal('hide');
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

      // View button
      $(document).on('click', '.view-btn', function() {
        var id = $(this).data('id');
        $.get('{{ url('fixed-deposits') }}/' + id, function(data) {
          var html = `
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-striped">
                                <tr><th width="40%">Certificate ID:</th><td>${data.fd_cert_id}</td></tr>
                                <tr><th>Customer:</th><td>${data.account?.customer?.name_en || 'N/A'}</td></tr>
                                <tr><th>Account:</th><td>${data.account?.acct_no || 'N/A'}</td></tr>
                                <tr><th>Term:</th><td>${data.fd_term?.term_name || 'N/A'}</td></tr>
                                <tr><th>Option:</th><td>${data.fd_option?.fd_option || 'N/A'}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-striped">
                                <tr><th width="40%">Amount:</th><td class="text-end">$${parseFloat(data.amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2})}</td></tr>
                                <tr><th>Interest Rate:</th><td>${data.int_rate}%</td></tr>
                                <tr><th>Extra Rate:</th><td>${data.extra_rate || 0}%</td></tr>
                                <tr><th>Issue Date:</th><td>${data.date_issue}</td></tr>
                                <tr><th>Maturity Date:</th><td>${data.matured_date}</td></tr>
                            </table>
                        </div>
                    </div>
                `;
          $('#viewModalBody').html(html);
          $('#viewModal').modal('show');
        });
      });

      // Edit button
      $(document).on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        $('#fdModalLabel').html('<i class="fas fa-edit me-2"></i>Edit Fixed Deposit');
        loadAccounts();

        $.get('{{ url('fixed-deposits') }}/' + id, function(data) {
          $('#fdCertId').val(data.fd_cert_id);
          $('#certNumber').val(data.fd_cert_id).prop('readonly', true);
          $('#accountId').val(data.acct_id);
          $('#issueDate').val(data.date_issue);
          $('#fdTerm').val(data.fd_term_id);
          $('#fdOption').val(data.fd_option_id);
          $('#amount').val(data.amount);
          $('#intRate').val(data.int_rate);
          $('#extraRate').val(data.extra_rate);
          $('#maturityDate').val(data.matured_date);
          $('#acctForInt').val(data.acct_for_int);
          $('#acctForPrin').val(data.acct_for_prin);
          $('#fdModal').modal('show');
        });
      });

      // Rollover button
      $(document).on('click', '.rollover-btn', function() {
        var id = $(this).data('id');
        Swal.fire({
          title: 'Rollover Fixed Deposit',
          html: `
            <div class="row">
              <div class="col-12 mb-3">
                <label for="rolloverDate" class="form-label">Rollover Date</label>
                <input type="date" id="rolloverDate" class="form-control" value="${new Date().toISOString().split('T')[0]}">
              </div>
              <div class="col-6 mb-3">
                <label for="rolloverAmount" class="form-label">Amount</label>
                <input type="number" id="rolloverAmount" class="form-control" step="0.01" min="0">
              </div>
              <div class="col-6 mb-3">
                <label for="rolloverRate" class="form-label">Interest Rate (%)</label>
                <input type="number" id="rolloverRate" class="form-control" step="0.01" min="0" max="100">
              </div>
            </div>
          `,
          showCancelButton: true,
          confirmButtonText: 'Rollover',
          cancelButtonText: 'Cancel',
          preConfirm: () => {
            const date = document.getElementById('rolloverDate').value;
            const amount = document.getElementById('rolloverAmount').value;
            const rate = document.getElementById('rolloverRate').value;

            if (!date || !amount || !rate) {
              Swal.showValidationMessage('All fields are required');
              return false;
            }

            return {
              roll_over_date: date,
              amount: amount,
              int_rate: rate
            };
          }
        }).then((result) => {
          if (result.isConfirmed) {
            $.post(`{{ url('fixed-deposits') }}/${id}/rollover`, result.value, function(response) {
              if (response.success) {
                Swal.fire('Success!', response.message, 'success');
                table.draw();
              } else {
                Swal.fire('Error!', response.message, 'error');
              }
            }).fail(function() {
              Swal.fire('Error!', 'Failed to rollover fixed deposit', 'error');
            });
          }
        });
      });

      // Withdraw button
      $(document).on('click', '.withdraw-btn', function() {
        var id = $(this).data('id');
        Swal.fire({
          title: 'Withdraw Fixed Deposit',
          text: 'Are you sure you want to withdraw this fixed deposit? This action cannot be undone.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, Withdraw',
          cancelButtonText: 'Cancel',
          confirmButtonColor: '#d33'
        }).then((result) => {
          if (result.isConfirmed) {
            $.post(`{{ url('fixed-deposits') }}/${id}/withdraw`, {}, function(response) {
              if (response.success) {
                Swal.fire('Success!', response.message, 'success');
                table.draw();
              } else {
                Swal.fire('Error!', response.message, 'error');
              }
            }).fail(function() {
              Swal.fire('Error!', 'Failed to withdraw fixed deposit', 'error');
            });
          }
        });
      });

      function resetForm() {
        $('#fdForm')[0].reset();
        $('#fdCertId').val('');
        $('#certNumber').prop('readonly', false);
        $('#issueDate').val('{{ date('Y-m-d') }}');
        $('#maturityDate').val('');
      }

      function loadAccounts() {
        $.get('{{ url('api/accounts/savings') }}', function(response) {
          var options = '<option value="">{{ __('common.form.select_account') }}</option>';
          $.each(response, function(i, account) {
            var customerName = account.customer?.NAME_EN || 'Unknown';
            options += '<option value="' + account.ACCT_ID + '">' + account.ACCT_NO + ' - ' + customerName +
              '</option>';
          });
          $('#accountId').html(options);
        });
      }

      function calculateMaturityDate() {
        var issueDate = $('#issueDate').val();
        var months = $('#fdTerm option:selected').data('months');

        if (issueDate && months) {
          var maturity = new Date(issueDate);
          maturity.setMonth(maturity.getMonth() + parseInt(months));
          $('#maturityDate').val(maturity.toISOString().split('T')[0]);
        }
      }
    });
  </script>
@endpush
