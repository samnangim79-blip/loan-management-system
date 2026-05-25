@extends('admin.layouts.admin_layout')

@section('title', __('interest.rates'))

@section('content')
  <div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 mb-0 text-gray-800">{{ __('common.nav.interest_rates') }}</h1>
        <nav aria-label="Close">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="">Interest Rates</li>
          </ol>
        </nav>
      </div>
      <div>
        @if (user_has_permission('create_interest_rate') || user_is_super_admin())
          <button type="button" class="" id="">
            <i class="fas fa-plus me-1"></i> Add Interest Rate
          </button>
        @endif
      </div>
    </div>

    <!-- Quick Links -->
    <div class="row mb-4">
      <div class="col-xl-6 col-md-6 mb-3">
        <a href="{{ route('dashboard') }}" class="text-muted">
          <div class="card border-left-primary shadow h-100 py-2 bg-primary text-white">
            <div class="card-body">
              <div class="row">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-uppercase mb-1">{{ __('common.general.interest_rates') }}
                  </div>
                  <div class="">{{ __('common.general.manage_loan_interest_rates') }}</div>
                </div>
                <div class="">
                  <i class="fas fa-percentage fa-2x"></i>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>

      <div class="col-xl-6 col-md-6 mb-3">
        <a href="{{ route('dashboard') }}" class="text-muted">
          <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
              <div class="row">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                    {{ __('common.general.accrued_interest') }}</div>
                  <div class="">{{ __('common.general.view_accrued_interest_records') }}</div>
                </div>
                <div class="">
                  <i class="fas fa-calculator fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>

    <!-- Interest Rates List Card -->
    <div class="card shadow mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
          <i class="fas fa-list me-2"></i>Interest Rates Configuration
        </h6>
      </div>
      <div class="card-body">
        <div class="table table-striped">
          <table class="table table-bordered table-striped" id="" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Interest Rate</th>
                <th>Account Type</th>
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

  <!-- Add/Edit Rate Modal -->
  <div class="modal fade" id="" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal fade">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="">{{ __('common.general.add_interest_rate') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="">
          @csrf
          <input type="hidden" id="" value="0">
          <input type="hidden" id="">
          <div class="modal-body">
            <div class="mb-3">
              <label for="rate" class="form-label">Interest Rate (%) <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="rate" name="rate" required min="0"
                max="100" step="0.01" placeholder="e.g., 12.50">
            </div>

            <div class="mb-3">
              <label for="acct_type_id" class="form-label">Account Type <span class="text-danger">*</span></label>
              <select class="form-control" id="acct_type_id" name="acct_type_id" required>
                <option value="">Select Account Type...</option>
                <option value="1">Savings Account</option>
                <option value="2">Current Account</option>
                <option value="3">Loan Account</option>
                <option value="4">Fixed Deposit</option>
              </select>
              <div class="form-text text-muted">
                <strong>Note:</strong> Select the account type this interest rate applies to.
              </div>
            </div>
          </div>
          <div class="modal fade">
            <button type="button" class="btn btn-primary"
              data-bs-dismiss="modal">{{ __('common.general.cancel') }}</button>
            <button type="submit" class="">
              <i class="fas fa-save me-1"></i> Save Rate
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
      var table = $('#ratesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ route('dashboard') }}",
          type: 'GET'
        },
        columns: [{
            data: 'int_rate_id',
            name: 'int_rate_id'
          },
          {
            data: 'formatted_rate',
            name: 'int_rate'
          },
          {
            data: 'int_type_text',
            name: 'int_type'
          },
          {
            data: 'int_option_text',
            name: 'int_option'
          },
          {
            data: '{{ __('common.general.description') }}',
            name: '{{ __('common.general.description') }}',
            render: function(data) {
              return data || '-';
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
          processing: '<i class="fas fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>'
        }
      });

      // New Rate button
      $('#newRateBtn').click(function() {
        $('#rateForm')[0].reset();
        $('#editMode').val('0');
        $('#editId').val('');
        $('#rateModalLabel').text('Add Interest Rate');
        $('#rateModal').modal('show');
      });

      // Form submit
      $('#rateForm').on('submit', function(e) {
        e.preventDefault();

        var editMode = $('#editMode').val() == '1';
        var url = editMode ?
          "{{ route('interest.update-rate', ':id') }}".replace(':id', $('#editId').val()) :
          "{{ route('interest.store-rate') }}";
        var method = editMode ? '{{ __('common.general.put') }}' : '{{ __('common.general.post') }}';

        $.ajax({
          url: url,
          type: method,
          data: $(this).serialize(),
          success: function(response) {
            if (response.success) {
              $('#rateModal').modal('{{ __('common.general.hide') }}');
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

      // Edit button
      $(document).on('submit', '.edit-btn', function() {
        var id = $(this).data('id');

        $.ajax({
          url: "{{ route('interest.show-rate', ':id') }}".replace(':id', id),
          type: 'GET',
          success: function(rate) {
            $('#editMode').val('1');
            $('#editId').val(id);
            $('#rate').val(rate.rate);
            $('#acct_type_id').val(rate.acct_type_id);
            $('#rateModalLabel').text('{{ __('common.general.edit_interest_rate') }}');
            $('#rateModal').modal('{{ __('common.general.show') }}');
          },
          error: function() {
            toastr.error('{{ __('common.pagination.failed_to_load_rate_details') }}');
          }
        });
      });

      // Delete button
      $(document).on('submit', '.delete-btn', function() {
        var id = $(this).data('id');

        Swal.fire({
          title: '{{ __('common.general.delete_interest_rate') }}',
          text: "{{ __('common.general.this_action_cannot_be_undone') }}",
          icon: '{{ __('common.messages.warning') }}',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: '{{ __('common.general.yes_delete_it') }}'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: "{{ route('interest.destroy-rate', ':id') }}".replace(':id', id),
              type: 'DELETE',
              data: {
                _token: '{{ csrf_token() }}'
              },
              success: function(response) {
                if (response.success) {
                  table.ajax.reload();
                  toastr.success(response.message);
                } else {
                  toastr.error(response.message || '{{ __('common.general.cannot_delete') }}');
                }
              },
              error: function(xhr) {
                toastr.error('{{ __('common.messages.an_error_occurred_while_deleting') }}');
              }
            });
          }
        });
      });
    });
  </script>
@endpush
