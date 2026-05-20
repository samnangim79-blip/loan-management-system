@extends('admin.layouts.admin_layout')

@section('pageTitle', __('common.general.public_holidays'))

@push('styles')
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
  <style>
    .holiday-card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    }

    .holiday-card .card-header {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      color: #fff;
      border-radius: 12px 12px 0 0 !important;
    }

    .badge-repeat {
      font-size: 0.75rem;
    }
  </style>
@endpush

@section('content')
  <div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="mb-1 fw-bold text-dark">
          <i class="fas fa-calendar-alt me-2 text-success"></i>Public Holidays
        </h4>
        <p class="text-muted mb-0">{{ __('common.general.manage_public_holidays_and_bank_holidays') }}</p>
      </div>
      <div>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary me-2">
          <i class="fas fa-arrow-left me-2"></i>Back to Settings
        </a>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#holidayModal">
          <i class="fas fa-plus me-2"></i>Add Holiday
        </button>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <i class="fas fa-calendar-check me-2"></i>
        <h5 class="mb-0">{{ __('common.general.holiday_list') }}</h5>
      </div>
      <div class="card-body">
        <table id="holidaysTable" class="table table-striped" style="width:100%">
          <thead>
            <tr>
              <th>#</th>
              <th>{{ __('common.general.date') }}</th>
              <th>{{ __('common.general.description') }}</th>
              <th>{{ __('common.general.repeat') }}</th>
              <th>{{ __('common.general.action') }}</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>

  <!-- Holiday Modal -->
  <div class="modal fade" id="holidayModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            <i class="fas fa-calendar-plus me-2"></i><span id="modalTitle">{{ __('common.general.add_holiday') }}</span>
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="holidayForm">
          <div class="modal-body">
            <input type="hidden" id="holidayId" name="id">
            <div class="mb-3">
              <label for="holiday_date" class="form-label">Holiday Date <span class="text-danger">*</span></label>
              <input type="text" class="form-control flatpickr-date" id="holiday_date" name="holiday_date" required>
            </div>
            <div class="mb-3">
              <label for="description" class="form-label">{{ __('common.general.description') }}</label>
              <input type="text" class="form-control" id="description" name="description"
                placeholder="e.g., New Year's Day" maxlength="250">
            </div>
            <div class="mb-3">
              <label for="repeat" class="form-label">{{ __('common.form.repeat') }}</label>
              <select class="form-control" id="repeat" name="repeat">
                <option value="">{{ __('common.general.no_repeat') }}</option>
                <option value="m">{{ __('common.form.monthly') }}</option>
                <option value="y">{{ __('common.general.yearly') }}</option>
              </select>
              <div class="form-text">{{ __('common.general.select_if_this_holiday_repeats_annually_or_monthly') }}</div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('common.general.cancel') }}</button>
            <button type="submit" class="btn btn-success">
              <i class="fas fa-save me-2"></i>Save Holiday
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
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script>
    $(document).ready(function() {
      // Initialize Flatpickr
      flatpickr('.flatpickr-date', {
        dateFormat: 'Y-m-d'
      });

      // Initialize DataTable
      const table = $('#holidaysTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('config.holidays-data') }}',
        columns: [{
            data: 'DT_RowIndex',
            name: 'DT_RowIndex',
            orderable: false,
            searchable: false
          },
          {
            data: 'holiday_date',
            name: 'holiday_date'
          },
          {
            data: 'description',
            name: 'description'
          },
          {
            data: 'repeat_text',
            name: 'repeat_text'
          },
          {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
          }
        ],
        order: [
          [1, 'asc']
        ]
      });

      // Form submit
      $('#holidayForm').submit(function(e) {
        e.preventDefault();

        const id = $('#holidayId').val();
        const url = id ? `{{ url('config/holidays') }}/${id}` : '{{ route('config.store-holiday') }}';
        const method = id ? 'PUT' : 'POST';

        $.ajax({
          url: url,
          type: method,
          data: $(this).serialize() + '&_token={{ csrf_token() }}',
          success: function(response) {
            if (response.success) {
              toastr.success(response.message);
              $('#holidayModal').modal('hide');
              table.ajax.reload();
              resetForm();
            }
          },
          error: function(xhr) {
            toastr.error('{{ __('common.general.failed_to_save_holiday') }}');
          }
        });
      });

      // Edit button
      $(document).on('click', '.edit-btn', function() {
        const id = $(this).data('id');

        $.get(`{{ url('config/holidays') }}/${id}`, function(holiday) {
          $('#holidayId').val(holiday.holiday_id);
          $('#holiday_date').val(holiday.holiday_date);
          $('#description').val(holiday.description);
          $('#repeat').val(holiday.repeat);
          $('#modalTitle').text('{{ __('common.general.edit_holiday') }}');
          $('#holidayModal').modal('show');
        });
      });

      // Delete button
      $(document).on('click', '.delete-btn', function() {
        const id = $(this).data('id');

        Swal.fire({
          title: '{{ __('common.general.delete_holiday') }}',
          text: '{{ __('common.general.this_action_cannot_be_undone') }}',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#dc3545',
          confirmButtonText: '{{ __('common.general.yes_delete_it') }}'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: `{{ url('config/holidays') }}/${id}`,
              type: 'DELETE',
              data: {
                _token: '{{ csrf_token() }}'
              },
              success: function(response) {
                if (response.success) {
                  toastr.success(response.message);
                  table.ajax.reload();
                }
              },
              error: function() {
                toastr.error('{{ __('common.general.failed_to_delete_holiday') }}');
              }
            });
          }
        });
      });

      // Reset form on modal close
      $('#holidayModal').on('hidden.bs.modal', function() {
        resetForm();
      });

      function resetForm() {
        $('#holidayForm')[0].reset();
        $('#holidayId').val('');
        $('#modalTitle').text('{{ __('common.general.add_holiday') }}');
      }
    });
  </script>
@endpush
