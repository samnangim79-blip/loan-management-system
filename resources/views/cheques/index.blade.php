@extends('admin.layouts.admin_layout')

@section('title', __('common.general.cheque_issues'))

@section('content')
  <div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 mb-0 text-gray-800">{{ __('common.nav.cheque_management') }}</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('common.nav.dashboard') }}</a></li>
            <li class="breadcrumb-item active">{{ __('common.general.cheque_issues') }}</li>
          </ol>
        </nav>
      </div>
      <div>
        @if (user_has_permission('create_cheque_issue') || user_is_super_admin())
          <button type="button" class="btn btn-success" id="newIssueBtn">
            <i class="fas fa-plus me-1"></i> {{ __('common.actions.new_cheque_issue') }}
          </button>
        @endif
      </div>
    </div>

    <!-- Quick Links -->
    <div class="row mb-4">
      <div class="col-xl-3 col-md-6 mb-3">
        <a href="{{ route('cheques.index') }}" class="text-decoration-none">
          <div class="card border-left-primary shadow h-100 py-2 hover-lift">
            <div class="card-body">
              <div class="row">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{ __('common.general.cheque_issues') }}</div>
                  <div class="h6 mb-0 font-weight-bold text-gray-800">{{ __('common.general.issue_new_cheque_books') }}</div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-book fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>

      <div class="col-xl-3 col-md-6 mb-3">
        <a href="{{ route('cheques.maintenance') }}" class="text-decoration-none">
          <div class="card border-left-info shadow h-100 py-2 hover-lift">
            <div class="card-body">
              <div class="row">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                    {{ __('common.general.maintenance') }}</div>
                  <div class="h6 mb-0 font-weight-bold text-gray-800">{{ __('common.pagination.cheque_stock_management') }}</div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-tools fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>

      <div class="col-xl-3 col-md-6 mb-3">
        <a href="{{ route('cheques.stops') }}" class="text-decoration-none">
          <div class="card border-left-danger shadow h-100 py-2 hover-lift">
            <div class="card-body">
              <div class="row">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                    {{ __('common.pagination.stop_payments') }}</div>
                  <div class="h6 mb-0 font-weight-bold text-gray-800">{{ __('common.pagination.stopped_cheques') }}</div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-ban fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>

      <div class="col-xl-3 col-md-6 mb-3">
        <a href="{{ route('cheques.clearing') }}" class="text-decoration-none">
          <div class="card border-left-success shadow h-100 py-2 hover-lift">
            <div class="card-body">
              <div class="row">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                    {{ __('common.form.cheque_clearing') }}</div>
                  <div class="h6 mb-0 font-weight-bold text-gray-800">{{ __('common.form.clear_pending_cheques') }}</div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-check-double fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </a>
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
          <div class="col-md-4 mb-3">
            <label for="filterStatus" class="form-label">{{ __('common.general.status') }}</label>
            <select class="form-control" id="filterStatus">
              <option value="">{{ __('common.general.all_statuses') }}</option>
              <option value="0">{{ __('common.form.pending') }}</option>
              <option value="1">{{ __('common.form.approved') }}</option>
              <option value="2">{{ __('common.form.rejected') }}</option>
            </select>
          </div>
          <div class="col-md-4 mb-3">
            <label for="filterDateFrom" class="form-label">{{ __('common.general.issue_date_from') }}</label>
            <input type="date" class="form-control" id="filterDateFrom">
          </div>
          <div class="col-md-4 mb-3">
            <label for="filterDateTo" class="form-label">{{ __('common.general.issue_date_to') }}</label>
            <input type="date" class="form-control" id="filterDateTo">
          </div>
        </div>
        <div class="d-flex gap-2">
          <button type="button" class="btn btn-primary" id="applyFiltersBtn">
            <i class="fas fa-search me-1"></i> {{ __('common.actions.apply_filters') }}
          </button>
          <button type="button" class="btn btn-secondary" id="resetFiltersBtn">
            <i class="fas fa-undo me-1"></i> {{ __('common.actions.reset') }}
          </button>
        </div>
      </div>
    </div>

    <!-- Issues List Card -->
    <div class="card shadow mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
          <i class="fas fa-list me-2"></i>{{ __('common.general.cheque_issue_requests') }}
        </h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped" id="issuesTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>{{ __('common.general.issue_id') }}</th>
                <th>{{ __('common.pagination.customer') }}</th>
                <th>{{ __('common.general.account_no') }}</th>
                <th>{{ __('common.general.cheque_from') }}</th>
                <th>{{ __('common.pagination.cheque_to') }}</th>
                <th>{{ __('common.general.issue_date') }}</th>
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

  <!-- New Cheque Issue Modal -->
  <div class="modal fade" id="issueModal" tabindex="-1" aria-labelledby="issueModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="issueModalLabel">{{ __('common.general.new_cheque_issue_request') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="issueForm">
          @csrf
          <div class="modal-body">
            <div class="mb-3">
              <label for="acct_id" class="form-label">{{ __('common.general.account') }} <span class="text-danger">*</span></label>
              <select class="form-control" id="acct_id" name="acct_id" required>
                <option value="">{{ __('common.form.select_account') }}</option>
              </select>
              <div class="form-text">{{ __('common.general.search_for_customer_account') }}</div>
            </div>

            <div class="mb-3">
              <label for="chq_from_no" class="form-label">{{ __('common.general.cheque_number_from') }} <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="chq_from_no" name="chq_from_no" required
                placeholder="{{ __('common.general.eg_000001') }}">
            </div>

            <div class="mb-3">
              <label for="chq_to_no" class="form-label">{{ __('common.general.cheque_number_to') }} <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="chq_to_no" name="chq_to_no" required
                placeholder="{{ __('common.general.eg_000025') }}">
            </div>

            <div class="alert alert-info">
              <i class="fas fa-info-circle me-2"></i>
              <strong>{{ __('common.general.note') }}:</strong> {{ __('common.messages.request_needs_approval_before_issuance') }}
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary"
              data-bs-dismiss="modal">{{ __('common.general.cancel') }}</button>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save me-1"></i> {{ __('common.actions.submit_request') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- View Issue Details Modal -->
  <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewModalLabel">{{ __('common.general.issue_details') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <table class="table table-striped">
            <tr>
              <th width="40%">{{ __('common.general.issue_id') }}</th>
              <td id="viewIssueId"></td>
            </tr>
            <tr>
              <th>{{ __('common.pagination.customer') }}</th>
              <td id="viewCustomer"></td>
            </tr>
            <tr>
              <th>{{ __('common.general.account_no') }}</th>
              <td id="viewAccountNo"></td>
            </tr>
            <tr>
              <th>{{ __('common.general.cheque_range') }}</th>
              <td id="viewChequeRange"></td>
            </tr>
            <tr>
              <th>{{ __('common.general.issue_date') }}</th>
              <td id="viewIssueDate"></td>
            </tr>
            <tr>
              <th>{{ __('common.general.status') }}</th>
              <td id="viewStatus"></td>
            </tr>
            <tr>
              <th>{{ __('common.general.issued_by') }}</th>
              <td id="viewIssuedBy"></td>
            </tr>
            <tr>
              <th>{{ __('common.general.approved_by') }}</th>
              <td id="viewApprovedBy"></td>
            </tr>
            <tr>
              <th>{{ __('common.general.approved_date') }}</th>
              <td id="viewApprovedDate"></td>
            </tr>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary"
            data-bs-dismiss="modal">{{ __('common.general.close') }}</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('styles')
  <style>
    .hover-lift {
      transition: transform 0.2s, box-shadow 0.2s;
    }

    .hover-lift:hover {
      transform: translateY(-5px);
      box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.2) !important;
    }

    .border-left-primary {
      border-left: 4px solid #4e73df !important;
    }

    .border-left-info {
      border-left: 4px solid #36b9cc !important;
    }

    .border-left-danger {
      border-left: 4px solid #e74a3b !important;
    }

    .border-left-success {
      border-left: 4px solid #1cc88a !important;
    }
  </style>
@endpush

@push('scripts')
  <script>
    $(document).ready(function() {
      // Initialize Tom Select for account dropdown
      var accountSelect = new TomSelect('#acct_id', {
        valueField: 'acct_id',
        labelField: 'display',
        searchField: ['acct_no', 'customer_name'],
        load: function(query, callback) {
          if (!query.length) return callback();
          $.ajax({
            url: "{{ route('api.accounts.search') }}",
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
                  acct_id: item.acct_id,
                  display: item.acct_no + ' - ' + (item.customer ? item.customer.name_en : 'N/A')
                };
              });
              callback(items);
            }
          });
        }
      });

      // Initialize DataTable
      var table = $('#issuesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ route('cheques.issues-data') }}",
          type: 'GET',
          data: function(d) {
            d.status = $('#filterStatus').val();
            d.date_from = $('#filterDateFrom').val();
            d.date_to = $('#filterDateTo').val();
          }
        },
        columns: [{
            data: 'chq_issue_id',
            name: 'chq_issue_id'
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
            data: 'chq_from_no',
            name: 'chq_from_no'
          },
          {
            data: 'chq_to_no',
            name: 'chq_to_no'
          },
          {
            data: 'issue_date',
            name: 'issue_date',
            render: function(data) {
              return data ? new Date(data).toLocaleDateString() : '-';
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
        responsive: true,
        language: {
          processing: '<i class="fas fa-spinner fa-spin fa-2x fa-fw"></i><span class="ms-2">{{ __('common.general.loading') }}</span>'
        }
      });

      // Apply filters
      $('#applyFiltersBtn').click(function() {
        table.ajax.reload();
      });

      // Reset filters
      $('#resetFiltersBtn').click(function() {
        $('#filterStatus').val('');
        $('#filterDateFrom').val('');
        $('#filterDateTo').val('');
        table.ajax.reload();
      });

      // New Issue button
      $('#newIssueBtn').click(function() {
        $('#issueForm')[0].reset();
        accountSelect.clear();
        $('#issueModal').modal('show');
      });

      // Form submit
      $('#issueForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
          url: "{{ route('cheques.store-issue') }}",
          type: 'POST',
          data: $(this).serialize(),
          success: function(response) {
            if (response.success) {
              $('#issueModal').modal('hide');
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

      // View button
      $(document).on('click', '.view-btn', function() {
        var row = table.row($(this).closest('tr')).data();

        $('#viewIssueId').text(row.chq_issue_id);
        $('#viewCustomer').text(row.customer_name);
        $('#viewAccountNo').text(row.account_no);
        $('#viewChequeRange').text(row.chq_from_no + ' - ' + row.chq_to_no);
        $('#viewIssueDate').text(row.issue_date ? new Date(row.issue_date).toLocaleDateString() : '-');
        $('#viewStatus').html(row.status_badge);
        $('#viewIssuedBy').text(row.issue_by || '-');
        $('#viewApprovedBy').text(row.approved_by || '-');
        $('#viewApprovedDate').text(row.approved_date ? new Date(row.approved_date).toLocaleDateString() : '-');
        $('#viewModal').modal('show');
      });

      // Approve button
      $(document).on('click', '.approve-btn', function() {
        var id = $(this).data('id');

        Swal.fire({
          title: '{{ __('common.general.approve_cheque_issue') }}',
          text: "{{ __('common.messages.approve_cheque_book_message') }}",
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#28a745',
          cancelButtonColor: '#6c757d',
          confirmButtonText: '{{ __('common.general.yes_approve_it') }}'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: "{{ route('cheques.approve-issue', ':id') }}".replace(':id', id),
              type: 'POST',
              data: {
                _token: '{{ csrf_token() }}'
              },
              success: function(response) {
                if (response.success) {
                  table.ajax.reload();
                  toastr.success(response.message);
                } else {
                  toastr.error(response.message ||
                    '{{ __('common.general.cannot_approve_this_issue') }}');
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
