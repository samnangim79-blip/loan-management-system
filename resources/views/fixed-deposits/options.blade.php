@extends('admin.layouts.admin_layout')

@section('title', __('fixed_deposits.options'))

@section('content')
  <div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 mb-0 text-gray-800">{{ __('common.nav.fd_options') }}</h1>
        <nav aria-label="Close">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Fixed Deposits</a></li>
            <li class="">FD Options</li>
          </ol>
        </nav>
      </div>
      <div>
        @if (user_has_permission('create_fd_option') || user_is_super_admin())
          <button type="button" class="btn btn-success" id="newOptionBtn">
            <i class="fas fa-plus me-1"></i> Add New Option
          </button>
        @endif
      </div>
    </div>

    <!-- Info Card -->
    <div class="alert alert-info mb-4">
      <i class="fas fa-info-circle me-2"></i>
      <strong>FD Options</strong> define how interest and principal are handled at maturity (e.g., Auto Rollover,
      Transfer to Savings, Pay Interest Monthly).
    </div>

    <!-- Options List Card -->
    <div class="card shadow mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
          <i class="fas fa-cogs me-2"></i>Fixed Deposit Options
        </h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped" id="optionsTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th width="100">ID</th>
                <th>{{ __('common.general.option_name') }}</th>
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

  <!-- Add/Edit Option Modal -->
  <div class="modal fade" id="optionModal" tabindex="-1" aria-labelledby="optionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="optionModalLabel">{{ __('common.general.add_new_option') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="optionForm">
          @csrf
          <input type="hidden" id="editMode" value="0">
          <input type="hidden" id="editId">
          <div class="modal-body">
            <div class="mb-3">
              <label for="fd_option_id" class="form-label">Option ID <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="fd_option_id" name="fd_option_id" required>
            </div>

            <div class="mb-3">
              <label for="fd_option" class="form-label">Option Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="fd_option" name="fd_option" required maxlength="50"
                placeholder="{{ __('common.pagination.eg_auto_rollover_transfer_to_savings') }}">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary"
              data-bs-dismiss="modal">{{ __('common.general.cancel') }}</button>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save me-1"></i> Save Option
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
      var table = $('#optionsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ route('fixed-deposits.options-data') }}",
          type: 'GET'
        },
        columns: [{
            data: 'fd_option_id',
            name: 'fd_option_id'
          },
          {
            data: 'fd_option',
            name: 'fd_option'
          },
          {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
          }
        ],
        order: [
          [0, 'asc']
        ],
        responsive: true,
        language: {
          processing: '<i class="fas fa-spinner fa-spin fa-2x fa-fw"></i><span class="">Loading...</span>'
        }
      });

      // New Option button
      $('#newOptionBtn').click(function() {
        $('#optionForm')[0].reset();
        $('#editMode').val('0');
        $('#editId').val('');
        $('#fd_option_id').prop('disabled', false);
        $('#optionModalLabel').text('{{ __('common.general.add_new_option') }}');
        $('#optionModal').modal('show');
      });

      // Form submit
      $('#optionForm').on('submit', function(e) {
        e.preventDefault();

        var editMode = $('#editMode').val() == '1';
        var url = editMode ?
          "{{ route('fixed-deposits.update-option', ':id') }}".replace(':id', $('#editId').val()) :
          "{{ route('fixed-deposits.store-option') }}";
        var method = editMode ? '{{ __('common.general.put') }}' : '{{ __('common.general.post') }}';

        $.ajax({
          url: url,
          type: method,
          data: $(this).serialize(),
          success: function(response) {
            if (response.success) {
              $('#optionModal').modal('hide');
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
        $('#fd_option_id').val(row.fd_option_id).prop('disabled', true);
        $('#fd_option').val(row.fd_option);
        $('#optionModalLabel').text('{{ __('common.general.edit_option') }}');
        $('#optionModal').modal('show');
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
              url: "{{ route('fixed-deposits.destroy-option', ':id') }}".replace(':id', id),
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
                    '{{ __('common.general.cannot_delete_this_option') }}');
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
