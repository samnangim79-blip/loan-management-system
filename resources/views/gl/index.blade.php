@extends('admin.layouts.admin_layout')

@section('pageTitle', 'General Ledger Chart of Accounts')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('gl.index') }}">{{ __('common.nav.chart_of_accounts') }}</a></li>
  <li class="breadcrumb-item active">{{ __('common.general.list') }}</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">General Ledger Chart of Accounts</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-primary btn-sm" id="addGlBtn">
              <i class="fas fa-plus"></i> Add New Account
            </button>
            <button type="button" class="btn btn-info btn-sm" id="viewTreeBtn">
              <i class="fas fa-sitemap"></i> View Tree
            </button>
          </div>
        </div>
        <div class="card-body">
          <table id="glTable" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>GL Code</th>
                <th>GL Account Name</th>
                <th>GL Account Name (Khmer)</th>
                <th>Level 4 Category</th>
                <th>Actions</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- GL Account Modal -->
  <div class="modal fade" id="glModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Add New GL Account</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="glForm">
          <div class="modal-body">
            <input type="hidden" id="gl_id" name="gl_id">

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="gl_code" class="form-label">GL Code <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="gl_code" name="gl_code" required maxlength="11"
                    placeholder="e.g., 1001001001">
                  <div class="form-text">Maximum 11 characters</div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="l4_id" class="form-label">Level 4 Category <span class="text-danger">*</span></label>
                  <select class="form-control" id="l4_id" name="l4_id" required>
                    <option value="">Select Level 4 Category</option>
                    @foreach ($l4s as $l4)
                      <option value="{{ $l4->l4_id }}">{{ $l4->l4_desc }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="gl_name" class="form-label">Account Name (English) <span
                      class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="gl_name" name="gl_name" required maxlength="80">
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="gl_name_kh" class="form-label">Account Name (Khmer)</label>
                  <input type="text" class="form-control" id="gl_name_kh" name="gl_name_kh" maxlength="80">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Account</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Tree View Modal -->
  <div class="modal fade" id="treeModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Chart of Accounts Tree View</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div id="treeContainer" class="tree-container">
            <div class="d-flex justify-content-center">
              <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('styles')
  <style>
    .tree-container {
      max-height: 500px;
      overflow-y: auto;
    }

    .tree-item {
      margin-left: 20px;
      margin-bottom: 5px;
    }

    .tree-level-1 {
      font-weight: bold;
      color: #007bff;
    }

    .tree-level-2 {
      font-weight: bold;
      color: #28a745;
      margin-left: 20px;
    }

    .tree-level-3 {
      font-weight: bold;
      color: #ffc107;
      margin-left: 40px;
    }

    .tree-level-4 {
      font-weight: bold;
      color: #dc3545;
      margin-left: 60px;
    }

    .tree-gl {
      color: #6c757d;
      margin-left: 80px;
    }
  </style>
@endpush

@push('scripts')
  <script>
    $(document).ready(function() {
      // Initialize DataTable
      var table = $('#glTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('gl.data') }}',
        columns: [{
            data: 'gl_code',
            name: 'gl_code'
          },
          {
            data: 'gl_name',
            name: 'gl_name'
          },
          {
            data: 'gl_name_kh',
            name: 'gl_name_kh',
            render: function(data) {
              return data || '-';
            }
          },
          {
            data: 'level4_desc',
            name: 'level4_desc',
            orderable: false,
            searchable: false
          },
          {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
          }
        ],
        responsive: true,
        autoWidth: false,
      });

      // Add GL Account Button
      $('#addGlBtn').click(function() {
        $('#modalTitle').text('Add New GL Account');
        $('#glForm')[0].reset();
        $('#gl_id').val('');
        $('#glModal').modal('show');
      });

      // GL Form Submission
      $('#glForm').submit(function(e) {
        e.preventDefault();

        var glId = $('#gl_id').val();
        var url = glId ? `/gl/${glId}` : '{{ route('gl.store') }}';
        var method = glId ? 'PUT' : 'POST';

        $.ajax({
          url: url,
          type: method,
          data: $(this).serialize(),
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(response) {
            if (response.success) {
              $('#glModal').modal('hide');
              table.ajax.reload();
              toastr.success(response.message);
            }
          },
          error: function(xhr) {
            var errors = xhr.responseJSON.errors;
            if (errors) {
              $.each(errors, function(key, value) {
                toastr.error(value[0]);
              });
            } else {
              toastr.error('An error occurred');
            }
          }
        });
      });

      // Edit Button
      $(document).on('click', '.edit-btn', function() {
        var glId = $(this).data('id');

        $.get(`/gl/${glId}`, function(data) {
          $('#modalTitle').text('Edit GL Account');
          $('#gl_id').val(data.gl_id);
          $('#gl_code').val(data.gl_code);
          $('#gl_name').val(data.gl_name);
          $('#gl_name_kh').val(data.gl_name_kh);
          $('#l4_id').val(data.l4_id);
          $('#glModal').modal('show');
        });
      });

      // View Button
      $(document).on('click', '.view-btn', function() {
        var glId = $(this).data('id');

        $.get(`/gl/${glId}`, function(data) {
          var info = `
            <div class="table-responsive">
              <table class="table table-striped">
                <tr><th>GL Code:</th><td>${data.gl_code}</td></tr>
                <tr><th>Account Name (EN):</th><td>${data.gl_name}</td></tr>
                <tr><th>Account Name (KH):</th><td>${data.gl_name_kh || '-'}</td></tr>
                <tr><th>Level 4 Category:</th><td>${data.level4?.l4_desc || '-'}</td></tr>
              </table>
            </div>
          `;

          Swal.fire({
            title: 'GL Account Details',
            html: info,
            icon: 'info',
            confirmButtonText: 'Close'
          });
        });
      });

      // Delete Button
      $(document).on('click', '.delete-btn', function() {
        var glId = $(this).data('id');

        Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: `/gl/${glId}`,
              type: 'DELETE',
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success: function(response) {
                if (response.success) {
                  table.ajax.reload();
                  toastr.success(response.message);
                }
              },
              error: function() {
                toastr.error('An error occurred while deleting');
              }
            });
          }
        });
      });

      // View Tree Button
      $('#viewTreeBtn').click(function() {
        $('#treeModal').modal('show');
        loadTreeView();
      });

      function loadTreeView() {
        $.get('{{ route('gl.tree') }}', function(data) {
          var html = '<div class="tree-view">';

          data.forEach(function(l1) {
            html += `<div class="tree-level-1"><i class="fas fa-folder"></i> ${l1.l1_desc}</div>`;

            if (l1.level2s) {
              l1.level2s.forEach(function(l2) {
                html +=
                `<div class="tree-level-2 ml-3"><i class="fas fa-folder"></i> ${l2.l2_desc}</div>`;

                if (l2.level3s) {
                  l2.level3s.forEach(function(l3) {
                    html +=
                      `<div class="tree-level-3 ml-5"><i class="fas fa-folder"></i> ${l3.l3_desc}</div>`;

                    if (l3.level4s) {
                      l3.level4s.forEach(function(l4) {
                        html +=
                          `<div class="tree-level-4 ml-7"><i class="fas fa-folder"></i> ${l4.l4_desc}</div>`;

                        if (l4.gls) {
                          l4.gls.forEach(function(gl) {
                            html +=
                              `<div class="tree-gl ml-9"><i class="fas fa-file"></i> ${gl.gl_code} - ${gl.gl_name}</div>`;
                          });
                        }
                      });
                    }
                  });
                }
              });
            }
          });

          html += '</div>';
          $('#treeContainer').html(html);
        }).fail(function() {
          $('#treeContainer').html('<div class="alert alert-danger">Failed to load tree view</div>');
        });
      }
    });
  </script>
@endpush
