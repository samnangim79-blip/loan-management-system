@extends('admin.layouts.admin_layout')

@section('pageTitle', 'Language Management')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('languages.index') }}">Languages</a></li>
  <li class="breadcrumb-item active">List</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-language me-2"></i>Language List</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-primary btn-sm" id="addLanguageBtn">
              <i class="fas fa-plus"></i> Add Language
            </button>
          </div>
        </div>
        <div class="card-body">
          <table id="languagesTable" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>Language</th>
                <th>Code</th>
                <th>Native Name</th>
                <th>Sort Order</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Language Modal -->
  <div class="modal fade" id="languageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Add Language</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="languageForm">
          <div class="modal-body">
            <input type="hidden" id="language_id" name="language_id">

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="name" class="form-label">Language Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="name" name="name" required
                    placeholder="e.g., English">
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="code" class="form-label">Language Code <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="code" name="code" required maxlength="5"
                    placeholder="e.g., en">
                  <small class="text-muted">ISO language code (2-5 characters)</small>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="native_name" class="form-label">Native Name</label>
                  <input type="text" class="form-control" id="native_name" name="native_name"
                    placeholder="e.g., English">
                  <small class="text-muted">Language name in its native script</small>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="flag" class="form-label">Flag</label>
                  <input type="text" class="form-control" id="flag" name="flag" maxlength="10"
                    placeholder="e.g., 🇬🇧">
                  <small class="text-muted">Flag emoji or icon</small>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="mb-3">
                  <label for="sort_order" class="form-label">Sort Order <span class="text-danger">*</span></label>
                  <input type="number" class="form-control" id="sort_order" name="sort_order" required min="0"
                    value="0">
                </div>
              </div>
              <div class="col-md-4">
                <div class="mb-3">
                  <label for="is_active" class="form-label">Status <span class="text-danger">*</span></label>
                  <select class="form-select" id="is_active" name="is_active" required>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="mb-3">
                  <label for="is_default" class="form-label">Default Language</label>
                  <select class="form-select" id="is_default" name="is_default" required>
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                  </select>
                  <small class="text-muted">Only one can be default</small>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Language</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- View Language Modal -->
  <div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Language Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="viewContent">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    $(document).ready(function() {
      // Initialize DataTable
      var table = $('#languagesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('languages.data') }}",
        columns: [{
            data: 'language_id',
            name: 'language_id'
          },
          {
            data: 'display',
            name: 'name'
          },
          {
            data: 'code',
            name: 'code'
          },
          {
            data: 'native_name',
            name: 'native_name',
            render: function(data) {
              return data || '-';
            }
          },
          {
            data: 'sort_order',
            name: 'sort_order'
          },
          {
            data: 'status',
            name: 'is_active'
          },
          {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
          }
        ],
        order: [
          [4, 'asc']
        ] // Sort by sort_order
      });

      // Add Language
      $('#addLanguageBtn').click(function() {
        $('#languageForm')[0].reset();
        $('#language_id').val('');
        $('#modalTitle').text('Add Language');
        $('#languageModal').modal('show');
      });

      // View Language
      $('#languagesTable').on('click', '.view-btn', function() {
        var id = $(this).data('id');
        $.get("{{ route('languages.show', ':id') }}".replace(':id', id))
          .done(function(response) {
            if (response.success) {
              var lang = response.data;
              var html = `
                <table class="table table-bordered">
                  <tr>
                    <th width="40%">ID</th>
                    <td>${lang.language_id}</td>
                  </tr>
                  <tr>
                    <th>Name</th>
                    <td>${lang.flag ? lang.flag + ' ' : ''}${lang.name}</td>
                  </tr>
                  <tr>
                    <th>Code</th>
                    <td><code>${lang.code}</code></td>
                  </tr>
                  <tr>
                    <th>Native Name</th>
                    <td>${lang.native_name || '-'}</td>
                  </tr>
                  <tr>
                    <th>Sort Order</th>
                    <td>${lang.sort_order}</td>
                  </tr>
                  <tr>
                    <th>Status</th>
                    <td>
                      <span class="badge bg-${lang.is_active ? 'success' : 'danger'}">
                        ${lang.is_active ? 'Active' : 'Inactive'}
                      </span>
                      ${lang.is_default ? '<span class="badge bg-primary ms-1">Default</span>' : ''}
                    </td>
                  </tr>
                  <tr>
                    <th>Created</th>
                    <td>${lang.created_date || '-'}</td>
                  </tr>
                  <tr>
                    <th>Modified</th>
                    <td>${lang.modify_date || '-'}</td>
                  </tr>
                </table>
              `;
              $('#viewContent').html(html);
              $('#viewModal').modal('show');
            }
          })
          .fail(function(xhr) {
            toastr.error(xhr.responseJSON?.message || 'Failed to load language details');
          });
      });

      // Edit Language
      $('#languagesTable').on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        $.get("{{ route('languages.show', ':id') }}".replace(':id', id))
          .done(function(response) {
            if (response.success) {
              var lang = response.data;
              $('#language_id').val(lang.language_id);
              $('#name').val(lang.name);
              $('#code').val(lang.code);
              $('#native_name').val(lang.native_name);
              $('#flag').val(lang.flag);
              $('#sort_order').val(lang.sort_order);
              $('#is_active').val(lang.is_active ? 1 : 0);
              $('#is_default').val(lang.is_default ? 1 : 0);
              $('#modalTitle').text('Edit Language');
              $('#languageModal').modal('show');
            }
          })
          .fail(function(xhr) {
            toastr.error(xhr.responseJSON?.message || 'Failed to load language details');
          });
      });

      // Save Language
      $('#languageForm').submit(function(e) {
        e.preventDefault();
        var id = $('#language_id').val();
        var url = id ?
          "{{ route('languages.update', ':id') }}".replace(':id', id) :
          "{{ route('languages.store') }}";
        var method = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            method: method,
            data: $(this).serialize(),
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
          })
          .done(function(response) {
            if (response.success) {
              toastr.success(response.message);
              $('#languageModal').modal('hide');
              table.ajax.reload();
            }
          })
          .fail(function(xhr) {
            if (xhr.status === 422) {
              // Validation errors
              var errors = xhr.responseJSON.errors;
              var errorMessage = Object.values(errors).flat().join('<br>');
              toastr.error(errorMessage);
            } else {
              toastr.error(xhr.responseJSON?.message || 'An error occurred');
            }
          });
      });

      // Delete Language
      $('#languagesTable').on('click', '.delete-btn', function() {
        var id = $(this).data('id');

        Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('languages.destroy', ':id') }}".replace(':id', id),
                method: 'DELETE',
                headers: {
                  'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
              })
              .done(function(response) {
                if (response.success) {
                  Swal.fire('Deleted!', response.message, 'success');
                  table.ajax.reload();
                }
              })
              .fail(function(xhr) {
                Swal.fire('Error!', xhr.responseJSON?.message || 'Failed to delete language', 'error');
              });
          }
        });
      });
    });
  </script>
@endpush
