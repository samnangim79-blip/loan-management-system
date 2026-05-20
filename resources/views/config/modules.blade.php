@extends('admin.layouts.admin_layout')

@section('pageTitle', __('config.system_modules'))

@push('styles')
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <style>
    .module-card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    }

    .module-card .card-header {
      background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
      color: #fff;
      border-radius: 12px 12px 0 0 !important;
    }
  </style>
@endpush

@section('content')
  <div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="mb-1 fw-bold text-dark">
          <i class="fas fa-puzzle-piece me-2 text-purple"></i>System Modules
        </h4>
        <p class="text-muted mb-0">{{ __('common.general.manage_system_modules_and_access_control') }}</p>
      </div>
      <div>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary me-2">
          <i class="fas fa-arrow-left me-2"></i>Back to Settings
        </a>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#moduleModal"
          style="background: #8b5cf6; color: white;">
          <i class="fas fa-plus me-2"></i>Add Module
        </button>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <i class="fas fa-cubes me-2"></i>
        <h5 class="mb-0">{{ __('common.general.module_list') }}</h5>
      </div>
      <div class="card-body">
        <table id="modulesTable" class="table table-striped" style="width:100%">
          <thead>
            <tr>
              <th>ID</th>
              <th>{{ __('common.general.module_name') }}</th>
              <th>{{ __('common.general.control_name') }}</th>
              <th>{{ __('common.general.url') }}</th>
              <th>{{ __('common.general.type') }}</th>
              <th>{{ __('common.general.status') }}</th>
              <th>{{ __('common.general.action') }}</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>

  <!-- Module Modal -->
  <div class="modal fade" id="moduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header" style="background: #8b5cf6; color: white;">
          <h5 class="modal-title">
            <i class="fas fa-puzzle-piece me-2"></i><span id="modalTitle">{{ __('common.general.add_module') }}</span>
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="moduleForm">
          <div class="modal-body">
            <input type="hidden" id="moduleId" name="id">
            <div class="mb-3">
              <label for="module_id" class="form-label">Module ID <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="module_id" name="module_id" required>
            </div>
            <div class="mb-3">
              <label for="module" class="form-label">Module Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="module" name="module" required maxlength="50"
                placeholder="e.g., Customer Management">
            </div>
            <div class="mb-3">
              <label for="control_name" class="form-label">Control Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="control_name" name="control_name" required maxlength="25"
                placeholder="e.g., customer_mgt">
              <div class="form-text">{{ __('common.general.unique_identifier_used_for_permission_checking') }}</div>
            </div>
            <div class="mb-3">
              <label for="url" class="form-label">{{ __('common.form.url') }}</label>
              <input type="text" class="form-control" id="url" name="url" maxlength="50"
                placeholder="e.g., /customers">
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                <select class="form-control" id="type" name="type" required>
                  <option value="1">{{ __('common.form.all') }}</option>
                  <option value="2">{{ __('common.general.branch') }}</option>
                  <option value="3">{{ __('common.pagination.head_office') }}</option>
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                <select class="form-control" id="status" name="status" required>
                  <option value="0">{{ __('common.form.active') }}</option>
                  <option value="1">{{ __('common.general.inactive') }}</option>
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('common.general.cancel') }}</button>
            <button type="submit" class="btn btn-primary" style="background: #8b5cf6; color: white;">
              <i class="fas fa-save me-2"></i>Save Module
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script>
    $(document).ready(function() {
      // Initialize DataTable
      const table = $('#modulesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('config.modules-data') }}',
        columns: [{
            data: 'module_id',
            name: 'module_id'
          },
          {
            data: 'module',
            name: 'module'
          },
          {
            data: 'control_name',
            name: 'control_name'
          },
          {
            data: 'url',
            name: 'url'
          },
          {
            data: 'type_text',
            name: 'type_text'
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
          [0, 'asc']
        ]
      });

      // Form submit
      $('#moduleForm').submit(function(e) {
        e.preventDefault();

        const id = $('#moduleId').val();
        const url = id ? `{{ url('config/modules') }}/${id}` : '{{ route('config.store-module') }}';
        const method = id ? 'PUT' : 'POST';

        $.ajax({
          url: url,
          type: method,
          data: $(this).serialize() + '&_token={{ csrf_token() }}',
          success: function(response) {
            if (response.success) {
              toastr.success(response.message);
              $('#moduleModal').modal('hide');
              table.ajax.reload();
              resetForm();
            }
          },
          error: function(xhr) {
            if (xhr.responseJSON && xhr.responseJSON.errors) {
              Object.values(xhr.responseJSON.errors).forEach(function(error) {
                toastr.error(error[0]);
              });
            } else {
              toastr.error('{{ __('common.general.failed_to_save_module') }}');
            }
          }
        });
      });

      // Edit button
      $(document).on('click', '.edit-btn', function() {
        const id = $(this).data('id');

        // Find the row data from DataTable
        const rowData = table.row($(this).closest('tr')).data();

        $('#moduleId').val(id);
        $('#module_id').val(rowData.module_id).prop('disabled', true);
        $('#module').val(rowData.module);
        $('#control_name').val(rowData.control_name);
        $('#url').val(rowData.url);
        $('#type').val(rowData.type);
        $('#status').val(rowData.status);
        $('#modalTitle').text('{{ __('common.general.edit_module') }}');
        $('#moduleModal').modal('show');
      });

      // Reset form on modal close
      $('#moduleModal').on('hidden.bs.modal', function() {
        resetForm();
      });

      function resetForm() {
        $('#moduleForm')[0].reset();
        $('#moduleId').val('');
        $('#module_id').prop('disabled', false);
        $('#modalTitle').text('{{ __('common.general.add_module') }}');
      }
    });
  </script>
@endpush
