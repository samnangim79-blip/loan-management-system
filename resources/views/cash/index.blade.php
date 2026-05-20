@extends('admin.layouts.admin_layout')

@section('title', __('common.general.cash_management'))

@section('content')
  <div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 mb-0 text-gray-800">{{ __('common.nav.cash_management') }}</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Transactions</a></li>
            <li class="breadcrumb-item active">Cash Management</li>
          </ol>
        </nav>
      </div>
      <div>
        <button type="button" class="btn btn-success me-2" id="cashInBtn">
          <i class="fas fa-arrow-down me-1"></i> Cash In
        </button>
        <button type="button" class="btn btn-danger" id="cashOutBtn">
          <i class="fas fa-arrow-up me-1"></i> Cash Out
        </button>
      </div>
    </div>

    <!-- Cash Balance Cards -->
    <div class="row mb-4">
      @foreach ($currencies as $currency)
        <div class="col-xl-3 col-md-6 mb-3">
          <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
              <div class="row">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                    {{ $currency->currency }} Balance
                  </div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800 currency-balance"
                    data-currency="{{ $currency->ccy_id }}">
                    Loading...
                  </div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endforeach
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
            <label for="filterDateFrom" class="form-label">{{ __('common.general.date_from') }}</label>
            <input type="date" class="form-control" id="filterDateFrom">
          </div>
          <div class="col-md-3 mb-3">
            <label for="filterDateTo" class="form-label">{{ __('common.general.date_to') }}</label>
            <input type="date" class="form-control" id="filterDateTo">
          </div>
          <div class="col-md-3 mb-3">
            <label for="filterType" class="form-label">{{ __('common.general.type') }}</label>
            <select class="form-control" id="filterType">
              <option value="">{{ __('common.form.all') }}</option>
              <option value="i">{{ __('common.form.cash_in') }}</option>
              <option value="o">{{ __('common.form.cash_out') }}</option>
            </select>
          </div>
          <div class="col-md-3 mb-3">
            <label for="filterCurrency" class="form-label">{{ __('common.form.currency') }}</label>
            <select class="form-control" id="filterCurrency">
              <option value="">{{ __('common.form.all_currencies') }}</option>
              @foreach ($currencies as $currency)
                <option value="{{ $currency->ccy_id }}">{{ $currency->currency }}</option>
              @endforeach
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

    <!-- Data Table Card -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
          <i class="fas fa-cash-register me-2"></i>Cash Transactions
        </h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped" id="cashTable" width="100%">
            <thead>
              <tr>
                <th>ID</th>
                <th>{{ __('common.general.date') }}</th>
                <th>{{ __('common.general.type') }}</th>
                <th>{{ __('common.general.currency') }}</th>
                <th class="text-end">{{ __('common.general.amount') }}</th>
                <th class="text-end">{{ __('common.general.balance') }}</th>
                <th>{{ __('common.general.remark') }}</th>
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

  <!-- Cash In/Out Modal -->
  <div class="modal fade" id="cashModal" tabindex="-1" aria-labelledby="cashModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cashModalLabel">{{ __('common.general.cash_transaction') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="cashForm">
          @csrf
          <input type="hidden" name="in_out" id="inOut">
          <div class="modal-body">
            <div class="mb-3">
              <label for="tranDate" class="form-label">Transaction Date <span class="text-danger">*</span></label>
              <input type="date" class="form-control" id="tranDate" name="tran_date" value="{{ date('Y-m-d') }}"
                required>
            </div>
            <div class="mb-3">
              <label for="currency" class="form-label">Currency <span class="text-danger">*</span></label>
              <select class="form-control" id="currency" name="ccy_id" required>
                <option value="">{{ __('common.form.select_currency') }}</option>
                @foreach ($currencies as $currency)
                  <option value="{{ $currency->ccy_id }}">{{ $currency->currency }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="number" class="form-control" id="amount" name="amount" step="0.01"
                  min="0.01" required>
              </div>
            </div>
            <div class="mb-3">
              <label for="remark" class="form-label">{{ __('common.form.remark') }}</label>
              <textarea class="form-control" id="remark" name="remark" rows="2" maxlength="50"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary"
              data-bs-dismiss="modal">{{ __('common.general.cancel') }}</button>
            <button type="submit" class="btn btn-primary" id="submitBtn">
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
          <h5 class="modal-title" id="viewModalLabel">{{ __('common.general.cash_transaction_details') }}</h5>
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
    .border-left-primary {
      border-left: 4px solid #4e73df !important;
    }

    .border-left-success {
      border-left: 4px solid #1cc88a !important;
    }

    .border-left-danger {
      border-left: 4px solid #e74a3b !important;
    }
  </style>
@endpush

@push('scripts')
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script>
    $(document).ready(function() {
      // Initialize DataTable
      var table = $('#cashTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '{{ route('cash.data') }}',
          data: function(d) {
            d.date_from = $('#filterDateFrom').val();
            d.date_to = $('#filterDateTo').val();
            d.type = $('#filterType').val();
            d.currency = $('#filterCurrency').val();
          }
        },
        columns: [{
            data: 'cash_mgt_id',
            name: 'cash_mgt_id'
          },
          {
            data: 'tran_date',
            name: 'tran_date'
          },
          {
            data: 'in_out_badge',
            name: 'in_out'
          },
          {
            data: 'currency_code',
            name: 'currency_code'
          },
          {
            data: 'formatted_amount',
            name: 'amount',
            className: 'text-end'
          },
          {
            data: 'formatted_balance',
            name: 'balance',
            className: 'text-end'
          },
          {
            data: 'remark',
            name: 'remark'
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

      // Load balances
      loadBalances();

      // Apply filters
      $('#applyFilters').on('click', function() {
        $(this).html('<i class="fas fa-spinner fa-spin me-1"></i> Applying...');
        $(this).prop('disabled', true);

        table.draw();

        setTimeout(() => {
          $(this).html('<i class="fas fa-search me-1"></i> Apply Filters');
          $(this).prop('disabled', false);
          toastr.success('Filters applied successfully');
        }, 500);
      });

      // Reset filters
      $('#resetFilters').on('click', function() {
        $(this).html('<i class="fas fa-spinner fa-spin me-1"></i> Resetting...');
        $(this).prop('disabled', true);

        $('#filterDateFrom, #filterDateTo').val('');
        $('#filterType, #filterCurrency').val('');

        table.draw();

        setTimeout(() => {
          $(this).html('<i class="fas fa-undo me-1"></i> Reset Filters');
          $(this).prop('disabled', false);
          toastr.info('Filters reset successfully');
        }, 500);
      });

      // Cash In button
      $('#cashInBtn').on('click', function() {
        resetForm();
        $('#cashModalLabel').html('<i class="fas fa-arrow-down text-success me-2"></i>Cash In');
        $('#inOut').val('i');
        $('#submitBtn').removeClass('btn-danger').addClass('btn-success').html(
          '<i class="fas fa-arrow-down me-1"></i> Record Cash In');
        $('#cashModal').modal('show');
      });

      // Cash Out button
      $('#cashOutBtn').on('click', function() {
        resetForm();
        $('#cashModalLabel').html('<i class="fas fa-arrow-up text-danger me-2"></i>Cash Out');
        $('#inOut').val('o');
        $('#submitBtn').removeClass('btn-success').addClass('btn-danger').html(
          '<i class="fas fa-arrow-up me-1"></i> Record Cash Out');
        $('#cashModal').modal('show');
      });

      // Form submit
      $('#cashForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
          url: '{{ route('cash.store') }}',
          type: 'POST',
          data: $(this).serialize(),
          success: function(response) {
            if (response.success) {
              $('#cashModal').modal('hide');
              toastr.success(response.message);
              table.draw();
              loadBalances();
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
        $.get('{{ url('cash') }}/' + id, function(response) {
          if (response.success) {
            var data = response.data;
            var typeClass = data.in_out == 'i' ? 'success' : 'danger';
            var typeName = data.in_out == 'i' ? 'Cash In' : 'Cash Out';

            var html = `
                    <table class="table table-striped">
                        <tr>
                            <th width="40%">Transaction ID:</th>
                            <td>${data.cash_mgt_id}</td>
                        </tr>
                        <tr>
                            <th>Date:</th>
                            <td>${data.tran_date}</td>
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
                            <td class="fw-bold text-${typeClass}">$${parseFloat(data.amount).toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                        </tr>
                        <tr>
                            <th>Balance After:</th>
                            <td>$${parseFloat(data.balance).toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                        </tr>
                        <tr>
                            <th>Remark:</th>
                            <td>${data.remark || 'N/A'}</td>
                        </tr>
                        <tr>
                            <th>Recorded At:</th>
                            <td>${data.date_done || 'N/A'}</td>
                        </tr>
                    </table>
                `;
            $('#viewModalBody').html(html);
            $('#viewModal').modal('show');
          }
        });
      });

      function resetForm() {
        $('#cashForm')[0].reset();
        $('#tranDate').val('{{ date('Y-m-d') }}');
      }

      function loadBalances() {
        $.get('{{ url('cash/balances') }}', function(response) {
          if (response.success) {
            $.each(response.data, function(ccyId, balance) {
              $('.currency-balance[data-currency="' + ccyId + '"]').text(
                '$' + parseFloat(balance).toLocaleString('en-US', {
                  minimumFractionDigits: 2
                })
              );
            });
          }
        }).fail(function() {
          $('.currency-balance').text('$0.00');
        });
      }
    });
  </script>
@endpush
