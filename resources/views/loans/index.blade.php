@extends('admin.layouts.admin_layout')

@section('pageTitle', __('common.nav.loans'))

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('loans.index') }}">{{ __('common.nav.loans') }}</a></li>
  <li class="breadcrumb-item active">{{ __('common.general.list') }}</li>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-md-3">
      <div class="card bg-primary text-white">
        <div class="card-body">
          <h5 class="card-title">{{ __('common.loan_management.total_loans') }}</h5>
          <h3 id="totalLoans">0</h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-success text-white">
        <div class="card-body">
          <h5 class="card-title">{{ __('common.loan_management.active_loans') }}</h5>
          <h3 id="activeLoans">0</h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-warning text-white">
        <div class="card-body">
          <h5 class="card-title">{{ __('common.loan_management.total_disbursed') }}</h5>
          <h3 id="totalDisbursed">$0</h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-info text-white">
        <div class="card-body">
          <h5 class="card-title">{{ __('common.loan_management.outstanding_balance') }}</h5>
          <h3 id="outstandingBalance">$0</h3>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ __('common.loan_management.loan_list') }}</h3>
      <div class="card-tools">
        <a href="{{ route('loans.create') }}" class="btn btn-primary btn-sm">
          <i class="fas fa-plus"></i> {{ __('common.loan_management.new_loan') }}
        </a>
      </div>
    </div>
    <div class="card-body">
      <table id="loansTable" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>{{ __('common.loan_management.contract_no') }}</th>
            <th>{{ __('common.customer_management.customer') }}</th>
            <th>{{ __('common.loan_management.amount') }}</th>
            <th>{{ __('common.loan_management.outstanding') }}</th>
            <th>{{ __('common.loan_management.interest_rate') }}</th>
            <th>{{ __('common.loan_management.issue_date') }}</th>
            <th>{{ __('common.loan_management.next_payment') }}</th>
            <th>{{ __('common.general.status') }}</th>
            <th>{{ __('common.general.action') }}</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>

  <!-- Payment Modal -->
  <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="paymentModalLabel">{{ __('common.loan_management.make_payment') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="paymentForm">
          <div class="modal-body">
            <input type="hidden" id="loan_id" name="loan_id">

            <div class="mb-3">
              <label class="form-label">{{ __('common.loan_management.outstanding_balance') }}</label>
              <input type="text" class="form-control" id="outstanding_display" readonly>
            </div>

            <div class="mb-3">
              <label for="payment_amount" class="form-label">{{ __('common.loan_management.payment_amount') }} <span
                  class="text-danger">*</span></label>
              <input type="number" class="form-control" id="payment_amount" name="payment_amount" step="0.01"
                required>
            </div>

            <div class="mb-3">
              <label for="payment_date" class="form-label">{{ __('common.loan_management.payment_date') }} <span
                  class="text-danger">*</span></label>
              <input type="text" class="form-control" id="payment_date" name="payment_date" required>
            </div>

            <div class="mb-3">
              <label for="payment_method" class="form-label">{{ __('common.general.payment_method') }} <span
                  class="text-danger">*</span></label>
              <select class="form-select" id="payment_method" name="payment_method" required>
                <option value="">{{ __('common.general.select_method') }}</option>
                <option value="cash">{{ __('common.general.cash') }}</option>
                <option value="transfer">{{ __('common.general.bank_transfer') }}</option>
                <option value="check">{{ __('common.general.check') }}</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary"
              data-bs-dismiss="modal">{{ __('common.general.cancel') }}</button>
            <button type="submit" class="btn btn-success">{{ __('common.general.process_payment') }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    $(document).ready(function() {
      // Initialize modal instance
      var paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'), {
        backdrop: 'static',
        keyboard: false
      });

      // Initialize DataTable
      var table = $('#loansTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('loans.data') }}",
        language: {
          url: "{{ asset('assets/js/datatables-' . app()->getLocale() . '.json') }}"
        },
        columns: [{
            data: 'contract_no',
            name: 'contract_no'
          },
          {
            data: 'customer_name',
            name: 'account.customer.name_en'
          },
          {
            data: 'amount',
            name: 'amount'
          },
          {
            data: 'os_balance',
            name: 'os_balance'
          },
          {
            data: 'int_rate',
            name: 'int_rate'
          },
          {
            data: 'date_issue',
            name: 'date_issue'
          },
          {
            data: 'next_pay_date',
            name: 'next_pay_date'
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
          [5, 'desc']
        ],
        pageLength: 10,
        responsive: true,
        drawCallback: function() {
          // Update statistics
          updateStatistics();
        }
      });

      // Initialize Flatpickr
      flatpickr("#payment_date", {
        dateFormat: "Y-m-d",
        defaultDate: "today"
      });

      // Payment button click
      $(document).on('click', '.payment-btn', function() {
        var loanId = $(this).data('id');

        $.ajax({
          url: `/loans/${loanId}`,
          type: 'get',
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          },
          success: function(response) {
            if (response.success) {
              $('#loan_id').val(response.loan_schedule_id);

              // Parse outstanding balance with proper fallback
              var osBalance = parseFloat(response.os_balance) || 0;
              $('#outstanding_display').val('$' + osBalance.toFixed(2));
              $('#payment_amount').attr('max', osBalance);

              // Show modal using Bootstrap 5 API
              paymentModal.show();
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load loan details'
              });
            }
          },
          error: function(xhr) {
            console.error('Payment modal error:', xhr.responseText);
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Failed to load loan details'
            });
          }
        });
      });

      // Payment form submit
      $('#paymentForm').on('submit', function(e) {
        e.preventDefault();

        var loanId = $('#loan_id').val();
        var formData = $(this).serialize();

        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        $.ajax({
          url: `/loans/${loanId}/payment`,
          type: 'post',
          data: formData,
          success: function(response) {
            if (response.success) {
              paymentModal.hide();
              table.ajax.reload();

              Swal.fire({
                icon: 'success',
                title: 'Payment Successful',
                text: response.message
              });
            }
          },
          error: function(xhr) {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: xhr.responseJSON?.message || 'Payment failed'
            });
          }
        });
      });

      // View loan button
      $(document).on('click', '.view-loan', function() {
        var loanId = $(this).data('id');
        window.location.href = `/loans/${loanId}`;
      });

      // Edit loan button
      $(document).on('click', '.edit-loan', function() {
        var loanId = $(this).data('id');
        window.location.href = `/loans/${loanId}/edit`;
      });

      // Delete loan button
      $(document).on('click', '.delete-loan', function() {
        var loanId = $(this).data('id');

        Swal.fire({
          title: 'Are you sure?',
          text: 'You will not be able to recover this loan record!',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });

            $.ajax({
              url: `/loans/${loanId}`,
              type: 'DELETE',
              success: function(response) {
                if (response.success) {
                  table.ajax.reload();
                  Swal.fire({
                    icon: 'success',
                    title: 'Deleted!',
                    text: response.message
                  });
                }
              },
              error: function(xhr) {
                Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: xhr.responseJSON?.message || 'Failed to delete loan'
                });
              }
            });
          }
        });
      });

      // View schedule button
      $(document).on('click', '.schedule-btn', function() {
        var loanId = $(this).data('id');
        window.location.href = `/loans/${loanId}/schedule`;
      });

      // Update statistics
      function updateStatistics() {
        $.ajax({
          url: '{{ route('loans.statistics') }}',
          type: 'get',
          success: function(response) {
            if (response.success && response.data) {
              $('#totalLoans').text(response.data.totalLoans);
              $('#activeLoans').text(response.data.activeLoans);
              $('#totalDisbursed').text('$' + response.data.totalDisbursed);
              $('#outstandingBalance').text('$' + response.data.totalOutstanding);
            }
          },
          error: function(xhr) {
            console.log('Failed to load statistics');
          }
        });
      }

      // Initial statistics load
      updateStatistics();
    });
  </script>
@endpush
