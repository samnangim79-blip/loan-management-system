@extends('admin.layouts.admin_layout')

@section('title', __('nationalities.management'))

@section('header-buttons')
  @permission('nationality_add')
    <button type="button" class="btn btn-primary btn-sm" id="addNationalityBtn">
      <i class="fas fa-plus me-1"></i> Add Nationality
    </button>
  @endpermission
@endsection

@section('content')
  <div class="card">
    <div class="card-header bg-white py-3">
      <div class="row">
        <div class="col">
          <h5 class="mb-0"><i class="fas fa-globe me-2 text-primary"></i>Nationalities</h5>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="nationalitiesTable" class="table table-striped table-hover w-100">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nationality (EN)</th>
              <th>Nationality (KH)</th>
              <th>{{ __('common.general.actions') }}</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Add/Edit Nationality Modal -->
  <div class="modal fade" id="nationalityModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="nationalityModalLabel">{{ __('common.general.add_nationality') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="nationalityForm">
          <div class="modal-body">
            <input type="hidden" id="nationalityId" name="nationality_id">
            <div class="mb-3">
              <label class="form-label">Nationality (English) <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="nationality" name="nationality" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Nationality (Khmer)</label>
              <input type="text" class="form-control" id="nationalityKh" name="nationality_kh">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('common.general.cancel') }}</button>
            <button type="submit" class="btn btn-primary" id="saveNationalityBtn">{{ __('common.general.save') }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- View Nationality Modal -->
  <div class="modal fade" id="viewNationalityModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ __('common.general.nationality_details') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="viewNationalityBody">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('common.general.close') }}</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    $(document).ready(function() {
      // Initialize DataTable
      var table = $('#nationalitiesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('nationalities.data') }}',
        columns: [{
            data: 'nationality_id',
            name: 'nationality_id'
          },
          {
            data: 'nationality',
            name: 'nationality'
          },
          {
            data: 'nationality_kh',
            name: 'nationality_kh',
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
        ]
      });

      // Add Nationality
      $('#addNationalityBtn').click(function() {
        $('#nationalityForm')[0].reset();
        $('#nationalityId').val('');
        $('#nationalityModalLabel').text('{{ __('common.general.add_nationality') }}');
        $('#nationalityModal').modal('show');
      });

      // Edit Nationality
      $('#nationalitiesTable').on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        $.get('/nationalities/' + id, function(data) {
          $('#nationalityId').val(data.nationality_id);
          $('#nationality').val(data.nationality);
          $('#nationalityKh').val(data.nationality_kh);
          $('#nationalityModalLabel').text('{{ __('common.general.edit_nationality') }}');
          $('#nationalityModal').modal('show');
        });
      });

      // Save Nationality
      $('#nationalityForm').submit(function(e) {
        e.preventDefault();
        var id = $('#nationalityId').val();
        var url = id ? '/nationalities/' + id : '{{ route('nationalities.store') }}';
        var method = id ? 'PUT' : 'POST';

        $.ajax({
          url: url,
          method: method,
          data: $(this).serialize(),
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          success: function(response) {
            if (response.success) {
              $('#nationalityModal').modal('hide');
              table.ajax.reload();
              toastr.success(response.message);
            }
          },
          error: function(xhr) {
            toastr.error(xhr.responseJSON?.message || '{{ __('common.messages.an_error_occurred') }}');
          }
        });
      });

      // Delete Nationality
      $('#nationalitiesTable').on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        Swal.fire({
          title: '{{ __('common.general.are_you_sure') }}',
          text: '{{ __('common.general.this_action_cannot_be_undone') }}',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          confirmButtonText: '{{ __('common.general.yes_delete_it') }}'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: '/nationalities/' + id,
              method: 'DELETE',
              headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
              },
              success: function(response) {
                if (response.success) {
                  table.ajax.reload();
                  toastr.success(response.message);
                }
              },
              error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || '{{ __('common.messages.an_error_occurred') }}');
              }
            });
          }
        });
      });
    });
  </script>
@endpush
