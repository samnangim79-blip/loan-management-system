@extends('admin.layouts.admin_layout')

@section('pageTitle', __('common.loan_management.loan_details'))

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('loans.index') }}">{{ __('common.nav.loans') }}</a></li>
  <li class="breadcrumb-item active">{{ __('common.loan_management.view') }}</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">{{ __('common.loan_management.loan_information') }}</h3>
        </div>
        <div class="card-body">
          <table class="table table-borderless">
            <tr>
              <th>{{ __('common.loan_management.contract_no') }}:</th>
              <td>{{ $loan->contract_no }}</td>
            </tr>
            <tr>
              <th>{{ __('common.customer_management.customer') }}:</th>
              <td>{{ $loan->account->customer->name_en ?? 'N/A' }}</td>
            </tr>
            <tr>
              <th>{{ __('common.account_management.account') }}:</th>
              <td>{{ $loan->account->acct_no ?? 'N/A' }}</td>
            </tr>
            <tr>
              <th>{{ __('common.loan_management.issue_date') }}:</th>
              <td>{{ $loan->date_issue?->format('Y-m-d') }}</td>
            </tr>
            <tr>
              <th>{{ __('common.loan_management.end_date') }}:</th>
              <td>{{ $loan->end_pay_date?->format('Y-m-d') }}</td>
            </tr>
            <tr>
              <th>{{ __('common.loan_management.purpose') }}:</th>
              <td>{{ $loan->purpose->purpose_type ?? 'N/A' }}</td>
            </tr>
          </table>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Financial Details</h3>
        </div>
        <div class="card-body">
          <table class="table table-borderless">
            <tr>
              <th>Loan Amount:</th>
              <td class="text-success fw-bold">${{ number_format($loan->amount, 2) }}</td>
            </tr>
            <tr>
              <th>Outstanding Balance:</th>
              <td class="text-danger fw-bold">${{ number_format($loan->os_balance, 2) }}</td>
            </tr>
            <tr>
              <th>Interest Rate:</th>
              <td>{{ $loan->int_rate }}%</td>
            </tr>
            <tr>
              <th>Payment Frequency:</th>
              <td>{{ $loan->frequency->frequency ?? 'N/A' }}</td>
            </tr>
            <tr>
              <th>Tenor:</th>
              <td>{{ $loan->tenor }} periods</td>
            </tr>
            <tr>
              <th>Next Payment Date:</th>
              <td>{{ $loan->next_pay_date?->format('Y-m-d') }}</td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="row mt-3">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Collaterals</h3>
        </div>
        <div class="card-body">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Type</th>
                <th>Collateral No</th>
                <th>Value</th>
                <th>Issue Date</th>
                <th>Remarks</th>
              </tr>
            </thead>
            <tbody>
              @forelse($loan->collaterals as $collateral)
                <tr>
                  <td>{{ $collateral->collateralType->collateral_type ?? 'N/A' }}</td>
                  <td>{{ $collateral->collateral_no }}</td>
                  <td>${{ number_format($collateral->collateral_value, 2) }}</td>
                  <td>{{ $collateral->date_issue?->format('Y-m-d') }}</td>
                  <td>{{ $collateral->remarks }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center">No collaterals found</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="row mt-3">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Payment History</h3>
        </div>
        <div class="card-body">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Balance After</th>
              </tr>
            </thead>
            <tbody>
              @forelse($loan->transactions as $tran)
                <tr>
                  <td>{{ $tran->due_date?->format('Y-m-d') }}</td>
                  <td>{{ $tran->loanTranType->loan_tran_type ?? 'Payment' }}</td>
                  <td>${{ number_format($tran->amount, 2) }}</td>
                  <td>${{ number_format($tran->os_balance, 2) }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center">No transactions found</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="mt-3">
    <button type="button" class="btn btn-success" id="makePaymentBtn">
      <i class="fas fa-dollar-sign"></i> Make Payment
    </button>
    <a href="{{ route('loans.edit', $loan->loan_schedule_id) }}" class="btn btn-primary">
      <i class="fas fa-edit"></i> Edit Loan
    </a>
    <a href="{{ route('loans.index') }}" class="btn btn-secondary">
      <i class="fas fa-arrow-left"></i> Back to List
    </a>
  </div>

  <!-- Payment Modal -->
  <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="paymentModalLabel">Make Payment</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="paymentForm">
          <div class="modal-body">
            <input type="hidden" id="loan_id" name="loan_id" value="{{ $loan->loan_schedule_id }}">

            <div class="mb-3">
              <label class="form-label">Outstanding Balance</label>
              <input type="text" class="form-control" id="outstanding_display"
                value="${{ number_format($loan->os_balance, 2) }}" readonly>
            </div>

            <div class="mb-3">
              <label for="payment_amount" class="form-label">Payment Amount <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="payment_amount" name="payment_amount" step="0.01"
                max="{{ $loan->os_balance }}" required>
            </div>

            <div class="mb-3">
              <label for="payment_date" class="form-label">Payment Date <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="payment_date" name="payment_date" required>
            </div>

            <div class="mb-3">
              <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
              <select class="form-select" id="payment_method" name="payment_method" required>
                <option value="">Select Method</option>
                <option value="cash">Cash</option>
                <option value="transfer">Bank Transfer</option>
                <option value="check">Check</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success">Process Payment</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

  <script>
    $(document).ready(function() {
      // Initialize modal instance
      var paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'), {
        backdrop: 'static',
        keyboard: false
      });

      // Initialize Flatpickr for payment date
      flatpickr("#payment_date", {
        dateFormat: "Y-m-d",
        defaultDate: "today"
      });

      // Make Payment button click
      $('#makePaymentBtn').on('click', function() {
        paymentModal.show();
      });

      // Payment form submit
      $('#paymentForm').on('submit', function(e) {
        e.preventDefault();

        var loanId = $('#loan_id').val();
        var formData = $(this).serialize();

        // Show loading
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Processing...').prop('disabled', true);

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

              Swal.fire({
                icon: 'success',
                title: 'Payment Successful',
                text: response.message
              }).then(() => {
                // Reload the page to update the loan details
                window.location.reload();
              });
            }
          },
          error: function(xhr) {
            var errorMessage = 'Payment failed';

            if (xhr.responseJSON && xhr.responseJSON.errors) {
              var errors = xhr.responseJSON.errors;
              errorMessage = Object.keys(errors).map(key => errors[key].join(', ')).join('; ');
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
              errorMessage = xhr.responseJSON.message;
            }

            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: errorMessage
            });
          },
          complete: function() {
            submitBtn.html(originalText).prop('disabled', false);
          }
        });
      });
    });
  </script>
@endpush
