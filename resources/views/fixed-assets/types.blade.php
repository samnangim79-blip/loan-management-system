@extends('admin.layouts.admin_layout')

@section('pageTitle', 'Fixed Asset Types Management')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('fixed-assets.index') }}">{{ __('common.nav.fixed_assets') }}</a></li>
  <li class="breadcrumb-item active">Asset Types</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-tags me-2"></i>Fixed Asset Types</h3>
          <div class="card-tools">
            <a href="{{ route('fixed-assets.index') }}" class="btn btn-secondary btn-sm me-2">
              <i class="fas fa-arrow-left"></i> Back to Assets
            </a>
            <button type="button" class="btn btn-primary btn-sm" id="addTypeBtn">
              <i class="fas fa-plus"></i> Add Asset Type
            </button>
          </div>
        </div>
        <div class="card-body">
          <table id="typesTable" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>{{ __('common.general.id') }}</th>
                <th>Asset Type</th>
                <th>GL Account</th>
                <th>Depreciation GL</th>
                <th>Expense GL</th>
                <th>Disposal GL</th>
                <th>Assets Count</th>
                <th>{{ __('common.general.action') }}</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Add/Edit Type Modal -->
  <div class="modal fade" id="typeModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Add Asset Type</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="typeForm">
          <div class="modal-body">
            <input type="hidden" id="type_id" name="type_id">

            <div class="mb-3">
              <label for="fa_type" class="form-label">Asset Type Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="fa_type" name="fa_type" required>
            </div>

            <div class="mb-3">
              <label for="gl_id" class="form-label">GL Account</label>
              <input type="number" class="form-control" id="gl_id" name="gl_id" placeholder="GL Account ID">
              <small class="text-muted">Main asset GL account</small>
            </div>

            <div class="mb-3">
              <label for="depre_gl" class="form-label">Depreciation GL</label>
              <input type="number" class="form-control" id="depre_gl" name="depre_gl"
                placeholder="Depreciation GL Account ID">
              <small class="text-muted">GL account for depreciation</small>
            </div>

            <div class="mb-3">
              <label for="exp_gl" class="form-label">Expense GL</label>
              <input type="number" class="form-control" id="exp_gl" name="exp_gl"
                placeholder="Expense GL Account ID">
              <small class="text-muted">GL account for expenses</small>
            </div>

            <div class="mb-3">
              <label for="dispose_gl" class="form-label">Disposal GL</label>
              <input type="number" class="form-control" id="dispose_gl" name="dispose_gl"
                placeholder="Disposal GL Account ID">
              <small class="text-muted">GL account for asset disposal</small>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary"
              data-bs-dismiss="modal">{{ __('common.general.cancel') }}</button>
            <button type="submit" class="btn btn-primary">{{ __('common.general.save') }}</button>
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
      const table = $('#typesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('fixed-assets.types-data') }}',
        columns: [{
            data: 'fa_type_id',
            name: 'fa_type_id'
          },
          {
            data: 'fa_type',
            name: 'fa_type'
          },
          {
            data: 'gl_id',
            name: 'gl_id',
            render: function(data) {
              return data || '<span class="text-muted">N/A</span>';
            }
          },
          {
            data: 'depre_gl',
            name: 'depre_gl',
            render: function(data) {
              return data || '<span class="text-muted">N/A</span>';
            }
          },
          {
            data: 'exp_gl',
            name: 'exp_gl',
            render: function(data) {
              return data || '<span class="text-muted">N/A</span>';
            }
          },
          {
            data: 'dispose_gl',
            name: 'dispose_gl',
            render: function(data) {
              return data || '<span class="text-muted">N/A</span>';
            }
          },
          {
            data: 'fa_type_id',
            name: 'assets_count',
            orderable: false,
            searchable: false,
            render: function(data) {
              return '<span class="badge bg-info">0</span>';
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
        ]
      });

      // Add Type Button
      $('#addTypeBtn').click(function() {
        $('#typeForm')[0].reset();
        $('#type_id').val('');
        $('#modalTitle').text('Add Asset Type');
        $('#typeModal').modal('show');
      });

      // Submit Type Form
      $('#typeForm').submit(function(e) {
        e.preventDefault();
        const typeId = $('#type_id').val();
        const url = typeId ? `/fixed-assets/types/${typeId}` : '{{ route('fixed-assets.store-type') }}';
        const method = typeId ? 'PUT' : 'POST';

        $.ajax({
          url: url,
          type: method,
          data: $(this).serialize(),
          success: function(response) {
            if (response.success) {
              $('#typeModal').modal('hide');
              table.ajax.reload();
              toastr.success(response.message);
            }
          },
          error: function(xhr) {
            if (xhr.status === 422) {
              const errors = xhr.responseJSON.errors;
              let errorMsg = '';
              $.each(errors, function(key, value) {
                errorMsg += value[0] + '<br>';
              });
              toastr.error(errorMsg);
            } else {
              toastr.error(xhr.responseJSON?.message || 'An error occurred');
            }
          }
        });
      });

      // Edit Type Button
      $(document).on('click', '.edit-btn', function() {
        const typeId = $(this).data('id');

        // Get the row data from DataTable
        const rowData = table.row($(this).closest('tr')).data();

        $('#type_id').val(rowData.fa_type_id);
        $('#fa_type').val(rowData.fa_type);
        $('#gl_id').val(rowData.gl_id || '');
        $('#depre_gl').val(rowData.depre_gl || '');
        $('#exp_gl').val(rowData.exp_gl || '');
        $('#dispose_gl').val(rowData.dispose_gl || '');

        $('#modalTitle').text('Edit Asset Type');
        $('#typeModal').modal('show');
      });

      // Delete Type Button
      $(document).on('click', '.delete-btn', function() {
        const typeId = $(this).data('id');

        Swal.fire({
          title: 'Are you sure?',
          text: "This will permanently delete the asset type!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: `/fixed-assets/types/${typeId}`,
              type: 'DELETE',
              success: function(response) {
                if (response.success) {
                  table.ajax.reload();
                  Swal.fire('Deleted!', response.message, 'success');
                }
              },
              error: function(xhr) {
                Swal.fire('Error!', xhr.responseJSON?.message || 'An error occurred', 'error');
              }
            });
          }
        });
      });
    });
  </script>
@endpush
