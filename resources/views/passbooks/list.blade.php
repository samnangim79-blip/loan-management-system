@extends('admin.layouts.admin_layout')

@section('title', __('passbooks.list'))

@section('content')
  <div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 mb-0 text-gray-800">{{ __('common.general.passbook_list') }}</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('passbooks.index') }}">Passbooks</a></li>
            <li class="breadcrumb-item active">Passbook List</li>
          </ol>
        </nav>
      </div>
    </div>

    <!-- Info Card -->
    <div class="alert alert-info mb-4">
      <i class="fas fa-info-circle me-2"></i>
      <strong>Passbook List</strong> shows all issued passbooks. You can view details and print passbook entries.
    </div>

    <!-- Passbook List Card -->
    <div class="card shadow mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
          <i class="fas fa-book me-2"></i>All Passbooks
        </h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped" id="passbooksTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>{{ __('common.general.passbook_id') }}</th>
                <th>{{ __('common.pagination.customer') }}</th>
                <th>Account No.</th>
                <th>Passbook No.</th>
                <th>{{ __('common.general.last_printed_page') }}</th>
                <th>{{ __('common.general.last_printed_line') }}</th>
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

  <!-- View/Print Modal -->
  <div class="modal fade" id="printModal" tabindex="-1" aria-labelledby="printModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="printModalLabel"><i class="fas fa-print me-2"></i>Print Passbook</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="printForm">
          @csrf
          @method('PUT')
          <input type="hidden" id="printPassbookId">
          <div class="modal-body">
            <div class="mb-3">
              <p class="text-muted">Update the print status after printing passbook entries.</p>
            </div>

            <div class="mb-3">
              <label for="last_printed_page" class="form-label">Last Printed Page <span
                  class="text-danger">*</span></label>
              <input type="number" class="form-control" id="last_printed_page" name="last_printed_page" required
                min="0">
            </div>

            <div class="mb-3">
              <label for="last_printed_line" class="form-label">Last Printed Line <span
                  class="text-danger">*</span></label>
              <input type="number" class="form-control" id="last_printed_line" name="last_printed_line" required
                min="0">
            </div>

            <div class="alert alert-warning">
              <i class="fas fa-exclamation-triangle me-2"></i>
              <strong>Important:</strong> Update these values after printing to track the last printed position.
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary"
              data-bs-dismiss="modal">{{ __('common.general.cancel') }}</button>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save me-1"></i> Update Print Status
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
      var table = $('#passbooksTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ route('passbooks.list-data') }}",
          type: 'GET'
        },
        columns: [{
            data: 'passbook_id',
            name: 'passbook_id'
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
            data: 'last_printed_page',
            name: 'last_printed_page',
            render: function(data) {
              return data || 0;
            }
          },
          {
            data: 'last_printed_line',
            name: 'last_printed_line',
            render: function(data) {
              return data || 0;
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

      // View button
      $(document).on('click', '.view-btn', function() {
        var row = table.row($(this).closest('tr')).data();
        Swal.fire({
          title: '{{ __('common.general.passbook_details') }}',
          html: `
            <table class="table table-striped">
              <tr><th>Passbook ID:</th><td>${row.passbook_id}</td></tr>
              <tr><th>Customer:</th><td>${row.customer_name}</td></tr>
              <tr><th>Account:</th><td>${row.account_no}</td></tr>
              <tr><th>Passbook No:</th><td>${row.passbook_no || '-'}</td></tr>
              <tr><th>Last Printed Page:</th><td>${row.last_printed_page || 0}</td></tr>
              <tr><th>Last Printed Line:</th><td>${row.last_printed_line || 0}</td></tr>
            </table>
          `,
          showCloseButton: true,
          showConfirmButton: false
        });
      });

      // Print button
      $(document).on('click', '.print-btn', function() {
        var row = table.row($(this).closest('tr')).data();

        $('#printPassbookId').val(row.passbook_id);
        $('#last_printed_page').val(row.last_printed_page || 0);
        $('#last_printed_line').val(row.last_printed_line || 0);
        $('#printModal').modal('show');
      });

      // Print form submit
      $('#printForm').on('submit', function(e) {
        e.preventDefault();
        var id = $('#printPassbookId').val();

        $.ajax({
          url: "{{ route('passbooks.update-print-status', ':id') }}".replace(':id', id),
          type: 'PUT',
          data: $(this).serialize(),
          success: function(response) {
            if (response.success) {
              $('#printModal').modal('hide');
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
              toastr.error('{{ __('common.messages.an_error_occurred_while_updating') }}');
            }
          }
        });
      });
    });
  </script>
@endpush
