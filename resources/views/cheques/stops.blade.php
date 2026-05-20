@extends('admin.layouts.admin_layout')

@section('title', __('common.pagination.stop_payments'))

@section('content')
  <div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 mb-0 text-gray-800">{{ __('common.nav.stop_payments') }}</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('common.nav.dashboard') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cheques.index') }}">{{ __('common.nav.cheques') }}</a></li>
            <li class="breadcrumb-item active">{{ __('common.nav.stop_payments') }}</li>
          </ol>
        </nav>
      </div>
      <div>
        @if (user_has_permission('create_cheque_stop') || user_is_super_admin())
          <button type="button" class="btn btn-danger" id="newStopBtn">
            <i class="fas fa-ban me-1"></i> {{ __('common.actions.stop_cheque') }}
          </button>
        @endif
      </div>
    </div>

    <!-- Info Card -->
    <div class="alert alert-warning mb-4">
      <i class="fas fa-exclamation-triangle me-2"></i>
      <strong>{{ __('common.nav.stop_payments') }}</strong> {{ __('common.messages.stop_payments_description') }}
    </div>

    <!-- Filters Card -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
          <i class="fas fa-filter me-2"></i>{{ __('common.general.filters') }}
        </h6>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-3">
            <label for="filterStatus" class="form-label">{{ __('common.general.status') }}</label>
            <select class="form-control" id="filterStatus">
              <option value="">{{ __('common.general.all_statuses') }}</option>
              <option value="stopped">{{ __('common.general.stopped') }}</option>
              <option value="released">{{ __('common.general.released') }}</option>
            </select>
          </div>
          <div class="col-md-3">
            <label for="filterReason" class="form-label">{{ __('common.general.reason') }}</label>
            <select class="form-control" id="filterReason">
              <option value="">{{ __('common.general.all_reasons') }}</option>
              <option value="lost">{{ __('common.general.lost') }}</option>
              <option value="stolen">{{ __('common.general.stolen') }}</option>
              <option value="damaged">{{ __('common.general.damaged') }}</option>
              <option value="fraud">{{ __('common.general.fraud') }}</option>
              <option value="other">{{ __('common.general.other') }}</option>
            </select>
          </div>
          <div class="col-md-3">
            <label for="filterDateFrom" class="form-label">{{ __('common.general.date_from') }}</label>
            <input type="date" class="form-control" id="filterDateFrom">
          </div>
          <div class="col-md-3">
            <label for="filterDateTo" class="form-label">{{ __('common.general.date_to') }}</label>
            <input type="date" class="form-control" id="filterDateTo">
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-12">
            <button type="button" class="btn btn-primary" id="applyFilters">
              <i class="fas fa-filter me-1"></i> {{ __('common.actions.apply_filters') }}
            </button>
            <button type="button" class="btn btn-secondary" id="resetFilters">
              <i class="fas fa-undo me-1"></i> {{ __('common.actions.reset_filters') }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Stops List Card -->
    <div class="card shadow mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
          <i class="fas fa-list me-2"></i>{{ __('common.general.stopped_cheques') }}
        </h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped" id="stopsTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>{{ __('common.general.stop_id') }}</th>
                <th>{{ __('common.general.cheque_number') }}</th>
                <th>{{ __('common.general.reason') }}</th>
                <th>{{ __('common.general.note') }}</th>
                <th>{{ __('common.general.stopped_date') }}</th>
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

  <!-- Stop Cheque Modal -->
  <div class="modal fade" id="stopModal" tabindex="-1" aria-labelledby="stopModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="stopModalLabel"><i class="fas fa-ban me-2"></i>{{ __('common.actions.stop_cheque_payment') }}</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="stopForm">
          @csrf
          <div class="modal-body">
            <div class="mb-3">
              <label for="chq_no" class="form-label">{{ __('common.general.cheque_number') }} <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="chq_no" name="chq_no" required
                placeholder="{{ __('common.general.enter_cheque_number') }}">
            </div>

            <div class="mb-3">
              <label for="reason" class="form-label">{{ __('common.general.reason') }} <span class="text-danger">*</span></label>
              <select class="form-control" id="reason" name="reason" required>
                <option value="">{{ __('common.general.select_reason') }}</option>
                <option value="lost">{{ __('common.general.lost') }}</option>
                <option value="stolen">{{ __('common.general.stolen') }}</option>
                <option value="damaged">{{ __('common.general.damaged') }}</option>
                <option value="fraud">{{ __('common.general.fraud') }}</option>
                <option value="other">{{ __('common.general.other') }}</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="note" class="form-label">{{ __('common.general.additional_notes') }}</label>
              <textarea class="form-control" id="note" name="note" rows="3"
                placeholder="{{ __('common.messages.stop_payment_notes_placeholder') }}"></textarea>
            </div>

            <div class="alert alert-warning mb-0">
              <i class="fas fa-exclamation-circle me-2"></i>
              <strong>{{ __('common.general.warning') }}:</strong> {{ __('common.messages.stop_payment_warning') }}
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary"
              data-bs-dismiss="modal">{{ __('common.actions.cancel') }}</button>
            <button type="submit" class="btn btn-danger">
              <i class="fas fa-ban me-1"></i> {{ __('common.actions.stop_cheque') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- View Details Modal -->
  <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewModalLabel">{{ __('common.general.stop_payment_details') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <table class="table table-striped">
            <tr>
              <th width="40%">{{ __('common.general.stop_id') }}</th>
              <td id="viewStopId"></td>
            </tr>
            <tr>
              <th>{{ __('common.general.cheque_number') }}</th>
              <td id="viewChequeNo"></td>
            </tr>
            <tr>
              <th>{{ __('common.general.reason') }}</th>
              <td id="viewReason"></td>
            </tr>
            <tr>
              <th>{{ __('common.general.note') }}</th>
              <td id="viewNote"></td>
            </tr>
            <tr>
              <th>{{ __('common.general.stopped_by') }}</th>
              <td id="viewStoppedBy"></td>
            </tr>
            <tr>
              <th>{{ __('common.general.stopped_date') }}</th>
              <td id="viewStoppedDate"></td>
            </tr>
            <tr>
              <th>{{ __('common.general.status') }}</th>
              <td id="viewStatus"></td>
            </tr>
            <tr id="releasedRow" style="display: none;">
              <th>{{ __('common.general.released_by') }}</th>
              <td id="viewReleasedBy"></td>
            </tr>
            <tr id="releasedDateRow" style="display: none;">
              <th>{{ __('common.general.released_date') }}</th>
              <td id="viewReleasedDate"></td>
            </tr>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary"
            data-bs-dismiss="modal">{{ __('common.actions.close') }}</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    $(document).ready(function() {
      // Initialize DataTable
      var table = $('#stopsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ route('cheques.stops-data') }}",
          type: 'GET',
          data: function(d) {
            d.status = $('#filterStatus').val();
            d.reason = $('#filterReason').val();
            d.date_from = $('#filterDateFrom').val();
            d.date_to = $('#filterDateTo').val();
          }
        },
        columns: [{
            data: 'chq_stop_id',
            name: 'chq_stop_id'
          },
          {
            data: 'chq_no',
            name: 'chq_no'
          },
          {
            data: 'reason',
            name: 'reason'
          },
          {
            data: 'note',
            name: 'note',
            render: function(data) {
              return data ? (data.length > 30 ? data.substring(0, 30) + '...' : data) : '-';
            }
          },
          {
            data: 'stopped_date',
            name: 'stopped_date',
            render: function(data) {
              return data ? new Date(data).toLocaleDateString() : '-';
            }
          },
          {
            data: 'status',
            name: 'status'
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
          processing: '<i class="fas fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">{{ __('common.general.loading') }}</span>'
        }
      });

      // Apply filters
      $('#applyFilters').on('click', function() {
        table.draw();
      });

      // Reset filters
      $('#resetFilters').on('click', function() {
        $('#filterStatus').val('');
        $('#filterReason').val('');
        $('#filterDateFrom').val('');
        $('#filterDateTo').val('');
        table.draw();
      });

      // New Stop button
      $('#newStopBtn').on('click', function() {
        $('#stopForm')[0].reset();
        $('#stopModal').modal('show');
      });

      // Form submit
      $('#stopForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
          url: "{{ route('cheques.store-stop') }}",
          type: 'POST',
          data: $(this).serialize(),
          success: function(response) {
            if (response.success) {
              $('#stopModal').modal('hide');
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
      $(document).on('click', '.view-btn', function() {
        var row = table.row($(this).closest('tr')).data();

        $('#viewStopId').text(row.chq_stop_id);
        $('#viewChequeNo').text(row.chq_no);
        $('#viewReason').text(row.reason);
        $('#viewNote').text(row.note || '-');
        $('#viewStoppedBy').text(row.stopped_by || '-');
        $('#viewStoppedDate').text(row.stopped_date ? new Date(row.stopped_date).toLocaleDateString() : '-');
        $('#viewStatus').html(row.status);

        if (row.released_date) {
          $('#releasedRow').show();
          $('#releasedDateRow').show();
          $('#viewReleasedBy').text(row.released_by || '-');
          $('#viewReleasedDate').text(new Date(row.released_date).toLocaleDateString());
        } else {
          $('#releasedRow').hide();
          $('#releasedDateRow').hide();
        }

        $('#viewModal').modal('show');
      });

      // Release button
      $(document).on('click', '.release-btn', function() {
        var id = $(this).data('id');

        Swal.fire({
          title: '{{ __('common.general.release_stop_payment') }}',
          text: "{{ __('common.messages.release_stop_payment_confirmation') }}",
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#28a745',
          cancelButtonColor: '#6c757d',
          confirmButtonText: '{{ __('common.actions.yes_release') }}'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: "{{ route('cheques.release-stop', ':id') }}".replace(':id', id),
              type: 'POST',
              data: {
                _token: '{{ csrf_token() }}'
              },
              success: function(response) {
                if (response.success) {
                  table.ajax.reload();
                  toastr.success(response.message);
                } else {
                  toastr.error(response.message || '{{ __('common.messages.cannot_release') }}');
                }
              },
              error: function(xhr) {
                toastr.error('{{ __('common.messages.an_error_occurred_while_releasing') }}');
              }
            });
          }
        });
      });
    });
  </script>
@endpush
