@extends('admin.layouts.admin_layout')

@section('title', __('passbooks.maintenance'))

@section('content')
  <div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 mb-0 text-gray-800">{{ __('common.nav.passbook_maintenance') }}</h1>
        <nav aria-label="Close">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('passbooks.index') }}">Passbooks</a></li>
            <li class="">Maintenance</li>
          </ol>
        </nav>
      </div>
      <div>
        @if (user_has_permission('create_passbook_maintenance') || user_is_super_admin())
          <button type="button" class="" id="">
            <i class="fas fa-plus me-1"></i> Add Stock
          </button>
        @endif
      </div>
    </div>

    <!-- Info Card -->
    <div class="alert alert-info mb-4">
      <i class="fas fa-info-circle me-2"></i>
      <strong>Passbook Maintenance</strong> allows you to manage passbook stock for each branch. Add new stock and track
      inventory.
    </div>

    <!-- Maintenance List Card -->
    <div class="card shadow mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
          <i class="fas fa-list me-2"></i>Passbook Stock Records
        </h6>
      </div>
      <div class="card-body">
        <div class="table table-striped">
          <table class="table table-bordered table-striped" id="" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>ID</th>
                <th>{{ __('common.general.branch') }}</th>
                <th>{{ __('common.general.transaction_date') }}</th>
                <th>{{ __('common.general.quantity') }}</th>
                <th>{{ __('common.general.passbook_from') }}</th>
                <th>{{ __('common.pagination.passbook_to') }}</th>
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

  <!-- Add Stock Modal -->
  <div class="modal fade" id="" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal fade">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="">{{ __('common.general.add_passbook_stock') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="">
          @csrf
          <div class="">
            <div class="mb-3">
              <label for="branch_id" class="form-label">Branch <span class="text-danger">*</span></label>
              <select class="form-control" id="branch_id" name="branch_id" required>
                <option value="">Select Branch...</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="tran_date" class="form-label">Transaction Date <span class="text-danger">*</span></label>
              <input type="date" class="form-control" id="tran_date" name="tran_date" required
                value="{{ date(__('common.form.ymd')) }}">
            </div>

            <div class="mb-3">
              <label for="qty" class="form-label">Quantity <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="" name="qty" required min="1"
                placeholder="e.g., 100">
            </div>

            <div class="mb-3">
              <label for="pass_from_no" class="form-label">Passbook Number From <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="pass_from_no" name="pass_from_no" required
                placeholder="e.g., PB000001">
            </div>

            <div class="mb-3">
              <label for="pass_to_no" class="form-label">Passbook Number To <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="pass_to_no" name="pass_to_no" required
                placeholder="e.g., PB000100">
            </div>
          </div>
          <div class="modal fade">
            <button type="button" class="btn btn-primary"
              data-bs-dismiss="modal">{{ __('common.general.cancel') }}</button>
            <button type="submit" class="">
              <i class="fas fa-save me-1"></i> Add Stock
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
      // Load branches
      $.ajax({
        url: "{{ route('api.branches') }}",
        type: 'GET',
        success: function(response) {
          if (response && response.length > 0) {
            response.forEach(function(branch) {
              $('#branch_id').append('<option value="' + branch.branch_id + '">' + branch.branch_name +
                '</option>');
            });
          }
        }
      });

      // Initialize DataTable
      var table = $('#maintenanceTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ route('passbooks.maintenance-data') }}",
          type: 'GET'
        },
        columns: [{
            data: 'pass_id',
            name: 'pass_id'
          },
          {
            data: 'branch_name',
            name: 'branch_name'
          },
          {
            data: 'tran_date',
            name: 'tran_date',
            render: function(data) {
              return data ? new Date(data).toLocaleDateString() : '-';
            }
          },
          {
            data: '{{ __('common.general.qty') }}',
            name: '{{ __('common.general.qty') }}'
          },
          {
            data: 'pass_from_no',
            name: 'pass_from_no'
          },
          {
            data: 'pass_to_no',
            name: 'pass_to_no'
          },
          {
            data: null,
            render: function(data, type, row) {
              if (row.approved_date) {
                return '<span class="">{{ __('common.general.approved') }}</span>';
              }
              return '<span class="">{{ __('common.general.pending') }}</span>';
            }
          },
          {
            data: '{{ __('common.general.action') }}',
            name: '{{ __('common.general.action') }}',
            orderable: false,
            searchable: false
          }
        ],
        order: [
          [0, '{{ __('common.general.desc') }}']
        ],
        responsive: true,
        language: {
          processing: '<i class="fas fa-spinner fa-spin fa-2x fa-fw"></i><span class="">Loading...</span>'
        }
      });

      // New Maintenance button
      $('#newMaintenanceBtn').click(function() {
        $('#maintenanceForm')[0].reset();
        $('#tran_date').val(new Date().toISOString().split('T')[0]);
        $('#maintenanceModal').modal('{{ __('common.general.show') }}');
      });

      // Form submit
      $('#maintenanceForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
          url: "{{ route('passbooks.store-maintenance') }}",
          type: 'POST',
          data: $(this).serialize(),
          success: function(response) {
            if (response.success) {
              $('#maintenanceModal').modal('{{ __('common.general.hide') }}');
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
      $(document).on('submit', '.view-btn', function() {
        var row = table.row($(this).closest('{{ __('common.general.tr') }}')).data();
        Swal.fire({
          title: '{{ __('common.pagination.stock_entry_details') }}',
          html: `
            <table class="table table-striped">
              <tr><th>ID:</th><td>${row.pass_id}</td></tr>
              <tr><th>Branch:</th><td>${row.branch_name}</td></tr>
              <tr><th>Date:</th><td>${row.tran_date ? new Date(row.tran_date).toLocaleDateString() : '-'}</td></tr>
              <tr><th>Quantity:</th><td>${row.qty}</td></tr>
              <tr><th>From:</th><td>${row.pass_from_no}</td></tr>
              <tr><th>To:</th><td>${row.pass_to_no}</td></tr>
              <tr><th>Status:</th><td>${row.approved_date ? '<span class="">{{ __('common.general.approved') }}</span>' : '<span class="">{{ __('common.general.pending') }}</span>'}</td></tr>
            </table>
          `,
          showCloseButton: true,
          showConfirmButton: false
        });
      });

      // Approve button
      $(document).on('submit', '.approve-btn', function() {
        var id = $(this).data('id');

        Swal.fire({
          title: '{{ __('common.pagination.approve_stock_entry') }}',
          text: "{{ __('common.pagination.this_will_approve_the_passbook_stock_entry') }}",
          icon: '{{ __('common.general.question') }}',
          showCancelButton: true,
          confirmButtonColor: '#28a745',
          cancelButtonColor: '#6c757d',
          confirmButtonText: '{{ __('common.general.yes_approve_it') }}'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: "{{ route('passbooks.approve-maintenance', ':id') }}".replace(':id', id),
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
