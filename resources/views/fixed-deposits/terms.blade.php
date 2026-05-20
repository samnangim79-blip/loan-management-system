@extends('admin.layouts.admin_layout')

@section('title', __('fixed_deposits.terms'))

@section('content')
  <div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 mb-0 text-gray-800">{{ __('common.nav.fd_terms') }}</h1>
        <nav aria-label="Close">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Fixed Deposits</a></li>
            <li class="">FD Terms</li>
          </ol>
        </nav>
      </div>
      <div>
        @if (user_has_permission('create_fd_term') || user_is_super_admin())
          <button type="button" class="btn btn-success" id="newTermBtn">
            <i class="fas fa-plus me-1"></i> Add New Term
          </button>
        @endif
      </div>
    </div>

    <!-- Terms List Card -->
    <div class="card shadow mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
          <i class="fas fa-list me-2"></i>Fixed Deposit Terms
        </h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped" id="termsTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>ID</th>
                <th>{{ __('common.general.term_name') }}</th>
                <th>{{ __('common.general.days') }}</th>
                <th>Interest Rate (%)</th>
                <th>Grace Period (Days)</th>
                <th>Break Term Fee (%)</th>
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

  <!-- Add/Edit Term Modal -->
  <div class="modal fade" id="termModal" tabindex="-1" aria-labelledby="termModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="termModalLabel">{{ __('common.general.add_new_term') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="termForm">
          @csrf
          <input type="hidden" id="editMode" value="0">
          <input type="hidden" id="editId">
          <div class="modal-body">
            <div class="mb-3">
              <label for="fd_term_id" class="form-label">Term ID <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="fd_term_id" name="fd_term_id" required>
            </div>

            <div class="mb-3">
              <label for="term_name" class="form-label">Term Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="term_name" name="term_name" required maxlength="50"
                placeholder="e.g., 3 Months, 6 Months, 1 Year">
            </div>

            <div class="mb-3">
              <label for="days_num" class="form-label">Number of Days <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="days_num" name="days_num" required min="1"
                placeholder="e.g., 90, 180, 365">
            </div>

            <div class="mb-3">
              <label for="int_rate" class="form-label">Interest Rate (%) <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="int_rate" name="int_rate" required min="0"
                max="100" step="0.01" placeholder="e.g., 5.50">
            </div>

            <div class="mb-3">
              <label for="grace_period" class="form-label">Grace Period (Days)</label>
              <input type="number" class="form-control" id="grace_period" name="grace_period" min="0"
                placeholder="{{ __('common.form.days_before_penalty_applies') }}">
            </div>

            <div class="mb-3">
              <label for="break_term_fee" class="form-label">Break Term Fee (%)</label>
              <input type="number" class="form-control" id="break_term_fee" name="break_term_fee" min="0"
                max="100" step="0.01" placeholder="{{ __('common.general.early_withdrawal_penalty') }}">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary"
              data-bs-dismiss="modal">{{ __('common.general.cancel') }}</button>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save me-1"></i> Save Term
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
      var table = $('#termsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ route('fixed-deposits.terms-data') }}",
          type: 'GET'
        },
        columns: [{
            data: 'fd_term_id',
            name: 'fd_term_id'
          },
          {
            data: 'term_name',
            name: 'term_name'
          },
          {
            data: 'days_num',
            name: 'days_num'
          },
          {
            data: 'int_rate',
            name: 'int_rate',
            render: function(data) {
              return parseFloat(data).toFixed(2) + '%';
            }
          },
          {
            data: 'grace_period',
            name: 'grace_period',
            render: function(data) {
              return data ? data : '-';
            }
          },
          {
            data: 'break_term_fee',
            name: 'break_term_fee',
            render: function(data) {
              return data ? parseFloat(data).toFixed(2) + '%' : '-';
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
          [0, '{{ __('common.general.asc') }}']
        ],
        responsive: true,
        language: {
          processing: '<i class="fas fa-spinner fa-spin fa-2x fa-fw"></i><span class="">Loading...</span>'
        }
      });

      // New Term button
      $('#newTermBtn').click(function() {
        $('#termForm')[0].reset();
        $('#editMode').val('0');
        $('#editId').val('');
        $('#fd_term_id').prop('disabled', false);
        $('#termModalLabel').text('{{ __('common.general.add_new_term') }}');
        $('#termModal').modal('{{ __('common.general.show') }}');
      });

      // Form submit
      $('#termForm').on('submit', function(e) {
        e.preventDefault();

        var editMode = $('#editMode').val() == '1';
        var url = editMode ?
          "{{ route('fixed-deposits.update-term', ':id') }}".replace(':id', $('#editId').val()) :
          "{{ route('fixed-deposits.store-term') }}";
        var method = editMode ? '{{ __('common.general.put') }}' : '{{ __('common.general.post') }}';

        $.ajax({
          url: url,
          type: method,
          data: $(this).serialize(),
          success: function(response) {
            if (response.success) {
              $('#termModal').modal('{{ __('common.general.hide') }}');
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
      $(document).on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        var row = table.row($(this).closest('tr')).data();

        $('#editMode').val('1');
        $('#editId').val(id);
        $('#fd_term_id').val(row.fd_term_id).prop('disabled', true);
        $('#term_name').val(row.term_name);
        $('#days_num').val(row.days_num);
        $('#int_rate').val(row.int_rate);
        $('#grace_period').val(row.grace_period);
        $('#break_term_fee').val(row.break_term_fee);
        $('#termModalLabel').text('{{ __('common.general.edit_term') }}');
        $('#termModal').modal('show');
      });

      // Delete button
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
              url: "{{ route('fixed-deposits.destroy-term', ':id') }}".replace(':id', id),
              type: 'DELETE',
              data: {
                _token: '{{ csrf_token() }}'
              },
              success: function(response) {
                if (response.success) {
                  table.ajax.reload();
                  toastr.success(response.message);
                } else {
                  toastr.error(response.message ||
                    '{{ __('common.general.cannot_delete_this_term') }}');
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
