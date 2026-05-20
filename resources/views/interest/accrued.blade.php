@extends('admin.layouts.admin_layout')

@section('title', __('interest.accrued'))

@section('content')
  <div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 mb-0 text-gray-800">{{ __('common.nav.accrued_interest') }}</h1>
        <nav aria-label="Close">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Interest</a></li>
            <li class="">Accrued Interest</li>
          </ol>
        </nav>
      </div>
      <div>
        @if (user_has_permission('calculate_accrued_interest') || user_is_super_admin())
          <button type="button" class="btn btn-primary" id="calculateBtn">
            <i class="fas fa-calculator me-1"></i> Calculate Accrued
          </button>
        @endif
      </div>
    </div>

    <!-- Info Card -->
    <div class="alert alert-info mb-4">
      <i class="fas fa-info-circle me-2"></i>
      <strong>Accrued Interest</strong> is the interest earned but not yet received on loans. This is calculated daily
      based on outstanding balances and interest rates.
    </div>

    <!-- Filters Card -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
          <i class="fas fa-filter me-2"></i>Filters
        </h6>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-4 mb-3">
            <label for="filterDateFrom" class="form-label">{{ __('common.general.date_from') }}</label>
            <input type="date" class="form-control" id="filterDateFrom">
          </div>
          <div class="col-md-4 mb-3">
            <label for="filterDateTo" class="form-label">{{ __('common.general.date_to') }}</label>
            <input type="date" class="form-control" id="filterDateTo">
          </div>
          <div class="col-md-4 mb-3 d-flex align-items-end">
            <div class="d-flex gap-2">
              <button type="button" class="btn btn-primary" id="applyFilters">
                <i class="fas fa-search me-1"></i> Apply
              </button>
              <button type="button" class="btn btn-secondary" id="resetFilters">
                <i class="fas fa-undo me-1"></i> Reset
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Accrued Interest List Card -->
    <div class="card shadow mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
          <i class="fas fa-list me-2"></i>Accrued Interest Records
        </h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped" id="accruedTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Account ID</th>
                <th>Last Accrued Date</th>
                <th>Last Accrued Interest</th>
                <th>Accrued Balance</th>
                <th width="80">{{ __('common.general.actions') }}</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Calculate Modal -->
  <div class="modal fade" id="calculateModal" tabindex="-1" aria-labelledby="calculateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="calculateModalLabel"><i class="fas fa-calculator me-2"></i>Calculate Accrued
            Interest</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="calculateForm">
          @csrf
          <div class="modal-body">
            <div class="mb-3">
              <label for="accrued_date" class="form-label">Accrued Date <span class="text-danger">*</span></label>
              <input type="date" class="form-control" id="accrued_date" name="accrued_date" required
                value="{{ date('Y-m-d') }}">
            </div>

            <div class="alert alert-warning">
              <i class="fas fa-exclamation-triangle me-2"></i>
              <strong>Note:</strong> This will calculate accrued interest for all active accounts with balances
              for the selected date.
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary"
              data-bs-dismiss="modal">{{ __('common.general.cancel') }}</button>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-calculator me-1"></i> Calculate
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
          <h5 class="modal-title" id="viewModalLabel">Accrued Interest Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <table class="table table-striped">
            <tr>
              <th width="40%">Account ID</th>
              <td id="viewAccountId"></td>
            </tr>
            <tr>
              <th>Last Accrued Date</th>
              <td id="viewAccruedDate"></td>
            </tr>
            <tr>
              <th>Last Accrued Interest</th>
              <td id="viewAccruedInt"></td>
            </tr>
            <tr>
              <th>Accrued Balance</th>
              <td id="viewBalance"></td>
            </tr>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary"
            data-bs-dismiss="modal">{{ __('common.general.close') }}</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    $(document).ready(function() {
      // Initialize DataTable
      var table = $('#accruedTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ route('interest.accrued-data') }}",
          type: 'GET',
          data: function(d) {
            d.date_from = $('#filterDateFrom').val();
            d.date_to = $('#filterDateTo').val();
          }
        },
        columns: [{
            data: 'acct_id',
            name: 'acct_id'
          },
          {
            data: 'last_accrued_date',
            name: 'last_accrued_date',
            render: function(data) {
              return data ? new Date(data).toLocaleDateString() : '-';
            }
          },
          {
            data: 'last_accrued_int',
            name: 'last_accrued_int',
            render: function(data) {
              return '$' + parseFloat(data || 0).toFixed(2);
            }
          },
          {
            data: 'accrued_int_balance',
            name: 'accrued_int_balance',
            render: function(data) {
              return '$' + parseFloat(data || 0).toFixed(2);
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

      // Apply filters
      $('#applyFilters').click(function() {
        table.ajax.reload();
      });

      // Reset filters
      $('#resetFilters').click(function() {
        $('#filterDateFrom').val('');
        $('#filterDateTo').val('');
        table.ajax.reload();
      });

      // Calculate button
      $('#calculateBtn').click(function() {
        $('#accrued_date').val(new Date().toISOString().split('T')[0]);
        $('#calculateModal').modal('show');
      });

      // Calculate form submit
      $('#calculateForm').on('submit', function(e) {
        e.preventDefault();

        Swal.fire({
          title: 'Processing',
          text: 'Calculating accrued interest for all active accounts...',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });

        $.ajax({
          url: "{{ route('interest.calculate-accrued') }}",
          type: 'POST',
          data: $(this).serialize(),
          success: function(response) {
            Swal.close();
            if (response.success) {
              $('#calculateModal').modal('hide');
              table.ajax.reload();
              toastr.success(response.message);
            } else {
              toastr.error(response.message || 'An error occurred');
            }
          },
          error: function(xhr) {
            Swal.close();
            toastr.error('An error occurred while calculating accrued interest');
          }
        });
      });

      // View button
      $(document).on('click', '.view-btn', function() {
        var row = table.row($(this).closest('tr')).data();

        $('#viewAccountId').text(row.acct_id || '-');
        $('#viewAccruedDate').text(row.last_accrued_date ? new Date(row.last_accrued_date).toLocaleDateString() :
          '-');
        $('#viewAccruedInt').text('$' + parseFloat(row.last_accrued_int || 0).toFixed(2));
        $('#viewBalance').text('$' + parseFloat(row.accrued_int_balance || 0).toFixed(2));
        $('#viewModal').modal('show');
      });
    });
  </script>
@endpush
