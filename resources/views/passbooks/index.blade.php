@extends('admin.layouts.admin_layout')

@section('title', __('passbooks.issues'))

@section('content')
  <div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 mb-0 text-gray-800">{{ __('common.nav.passbook_management') }}</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Passbook Issues</li>
          </ol>
        </nav>
      </div>
      <div>
        @if (user_has_permission('create_passbook_issue') || user_is_super_admin())
          <button type="button" class="btn btn-primary" id="newIssueBtn">
            <i class="fas fa-plus me-1"></i> New Passbook Issue
          </button>
        @endif
      </div>
    </div>

    <!-- Quick Links -->
    <div class="row mb-4">
      <div class="col-xl-4 col-md-6 mb-3">
        <a href="{{ route('passbooks.index') }}" class="text-muted">
          <div class="card border-left-primary shadow h-100 py-2 bg-primary text-white">
            <div class="card-body">
              <div class="row">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-uppercase mb-1">{{ __('common.general.passbook_issues') }}
                  </div>
                  <div class="h5 mb-0 font-weight-bold text-white">{{ __('common.general.issue_new_passbooks') }}</div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-book fa-2x text-white-50"></i>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>

      <div class="col-xl-4 col-md-6 mb-3">
        <a href="{{ route('passbooks.maintenance') }}" class="text-muted">
          <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
              <div class="row">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                    {{ __('common.general.maintenance') }}</div>
                  <div class="h5 mb-0 font-weight-bold text-info">{{ __('common.pagination.passbook_stock_management') }}
                  </div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-tools fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>

      <div class="col-xl-4 col-md-6 mb-3">
        <a href="{{ route('passbooks.list') }}" class="text-muted">
          <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
              <div class="row">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                    {{ __('common.general.passbook_list') }}</div>
                  <div class="">View & print passbooks</div>
                </div>
                <div class="">
                  <i class="fas fa-list fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>

    <!-- Issues List Card -->
    <div class="card shadow mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
          <i class="fas fa-list me-2"></i>Passbook Issue Requests
        </h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped" id="issuesTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>{{ __('common.general.issue_id') }}</th>
                <th>{{ __('common.pagination.customer') }}</th>
                <th>Account No.</th>
                <th>Passbook No.</th>
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

  <!-- New Passbook Issue Modal -->
  <div class="modal fade" id="issueModal" tabindex="-1" aria-labelledby="issueModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="issueModalLabel">{{ __('common.general.new_passbook_issue_request') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="issueForm">
          @csrf
          <div class="modal-body">
            <div class="mb-3">
              <label for="acct_id" class="form-label">Account <span class="text-danger">*</span></label>
              <select class="form-control" id="acct_id" name="acct_id" required>
                <option value="">Select Account...</option>
              </select>
              <div class="form-text">{{ __('common.general.search_for_customer_account') }}</div>
            </div>

            <div class="mb-3">
              <label for="passbook_no" class="form-label">Passbook Number <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="passbook_no" name="passbook_no" required
                placeholder="{{ __('common.form.enter_passbook_number') }}">
            </div>

            <div class="alert alert-info">
              <i class="fas fa-info-circle me-2"></i>
              <strong>Note:</strong> This request will need approval before the passbook is issued.
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary"
              data-bs-dismiss="modal">{{ __('common.general.cancel') }}</button>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save me-1"></i> Submit Request
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
          url: "{{ route('passbooks.issues-data') }}",
          type: 'GET'
        },
        columns: [{
            data: 'pass_issue_id',
            name: 'pass_issue_id'
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
            data: 'passbook_no',
            name: 'passbook_no'
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
          processing: '<i class="fas fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>'
        }
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
          url: "{{ route('passbooks.store-issue') }}",
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
        Swal.fire({
          title: '{{ __('common.general.passbook_issue_details') }}',
          html: `
            <table class="table table-striped">
              <tr><th>Issue ID:</th><td>${row.pass_issue_id}</td></tr>
              <tr><th>Customer:</th><td>${row.customer_name}</td></tr>
              <tr><th>Account:</th><td>${row.account_no}</td></tr>
              <tr><th>Passbook No:</th><td>${row.passbook_no}</td></tr>
              <tr><th>Issue Date:</th><td>${row.issue_date ? new Date(row.issue_date).toLocaleDateString() : '-'}</td></tr>
              <tr><th>Status:</th><td>${row.status_badge}</td></tr>
            </table>
          `,
          showCloseButton: true,
          showConfirmButton: false
        });
      });

      // Approve button
      $(document).on('click', '.approve-btn', function() {
        var id = $(this).data('id');

        Swal.fire({
          title: '{{ __('common.general.approve_passbook_issue') }}',
          text: "{{ __('common.general.this_will_approve_the_passbook_issuance') }}",
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#28a745',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Yes, approve it!'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: "{{ route('passbooks.approve-issue', ':id') }}".replace(':id', id),
              type: 'POST',
              data: {
                _token: '{{ csrf_token() }}'
              },
              success: function(response) {
                if (response.success) {
                  table.ajax.reload();
                  toastr.success(response.message);
                } else {
                  toastr.error(response.message || '{{ __('common.general.cannot_approve') }}');
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
