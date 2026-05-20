@extends('admin.layouts.admin_layout')

@section('pageTitle', __('common.loan_management.edit_loan'))

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('loans.index') }}">{{ __('common.nav.loans') }}</a></li>
  <li class="breadcrumb-item active">{{ __('common.loan_management.edit_loan') }}</li>
@endsection

@section('content')
  <form id="loanApplicationForm" action="{{ route('loans.update', $loan->loan_schedule_id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
      <div class="col-lg-8">
        <!-- Customer Selection -->
        <div class="card mb-4">
          <div class="card-header">
            <h5 class="card-title mb-0">{{ __('common.loan_management.customer_information') }}</h5>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-12 mb-3">
                <label for="acct_id" class="form-label">{{ __('common.loan_management.select_account') }} <span
                    class="text-danger">*</span></label>
                <select id="acct_id" name="acct_id" class="form-control" required>
                  <option value="">{{ __('common.loan_management.search_select_account') }}</option>
                  @foreach ($accounts as $item)
                    <option value="{{ $item->acct_id }}" {{ $item->acct_id == $loan->acct_id ? 'selected' : '' }}>
                      {{ $item->acct_no }} - {{ $item->customer->name_en }} ({{ $item->customer->ic_no }})
                    </option>
                  @endforeach
                </select>
                <div class="invalid-feedback">{{ __('common.loan_management.please_select_account') }}</div>
              </div>
            </div>

            <div id="customerDetails" class="{{ $loan->account ? '' : 'd-none' }}">
              <div class="row">
                <div class="col-md-6">
                  <table class="table table-sm">
                    <tr>
                      <th width="40%">{{ __('common.loan_management.customer_name') }}</th>
                      <td id="custName">{{ $loan->account->customer->name_en ?? '-' }}</td>
                    </tr>
                    <tr>
                      <th>{{ __('common.loan_management.ic_number') }}</th>
                      <td id="custIC">{{ $loan->account->customer->ic_no ?? '-' }}</td>
                    </tr>
                    <tr>
                      <th>{{ __('common.loan_management.phone') }}</th>
                      <td id="custPhone">{{ $loan->account->customer->phone1 ?? '-' }}</td>
                    </tr>
                  </table>
                </div>
                <div class="col-md-6">
                  <table class="table table-sm">
                    <tr>
                      <th width="40%">{{ __('common.loan_management.account_no') }}</th>
                      <td id="acctNo">{{ $loan->account->acct_no ?? '-' }}</td>
                    </tr>
                    <tr>
                      <th>{{ __('common.loan_management.account_type') }}</th>
                      <td id="acctType">{{ $loan->account->accountType->acct_type ?? '-' }}</td>
                    </tr>
                    <tr>
                      <th>{{ __('common.loan_management.balance') }}</th>
                      <td id="acctBalance">-</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Loan Details -->
        <div class="card mb-4">
          <div class="card-header">
            <h5 class="card-title mb-0">{{ __('common.loan_management.loan_details') }}</h5>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-4 mb-3">
                <label for="contract_no" class="form-label">{{ __('common.loan_management.contract_number') }}</label>
                <input type="text" class="form-control" id="contract_no" name="contract_no"
                  value="{{ $loan->contract_no }}" required>
              </div>

              <div class="col-md-4 mb-3">
                <label for="date_issue" class="form-label">{{ __('common.loan_management.issue_date') }} <span
                    class="text-danger">*</span></label>
                <input type="text" class="form-control" id="date_issue" name="date_issue"
                  value="{{ $loan->date_issue }}" required>
                <div class="invalid-feedback">{{ __('common.loan_management.please_select_issue_date') }}</div>
              </div>

              <div class="col-md-4 mb-3">
                <label for="purpose_id" class="form-label">{{ __('common.loan_management.loan_purpose') }} <span
                    class="text-danger">*</span></label>
                <select class="form-select" id="purpose_id" name="purpose_id" required>
                  <option value="">{{ __('common.loan_management.select_purpose') }}</option>
                  @foreach ($purposes as $purpose)
                    <option value="{{ $purpose->purpose_id }}"
                      {{ $purpose->purpose_id == $loan->purpose_id ? 'selected' : '' }}>
                      {{ $purpose->purpose_type }}
                    </option>
                  @endforeach
                </select>
                <div class="invalid-feedback">{{ __('common.loan_management.please_select_loan_purpose') }}</div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4 mb-3">
                <label for="amount" class="form-label">{{ __('common.loan_management.loan_amount') }} <span
                    class="text-danger">*</span></label>
                <input type="number" class="form-control" id="amount" name="amount" value="{{ $loan->amount }}"
                  step="0.01" min="1" required>
                <div class="invalid-feedback">{{ __('common.loan_management.please_enter_loan_amount') }}</div>
              </div>

              <div class="col-md-4 mb-3">
                <label for="int_rate" class="form-label">{{ __('common.loan_management.interest_rate_percent') }} <span
                    class="text-danger">*</span></label>
                <input type="number" class="form-control" id="int_rate" name="int_rate" value="{{ $loan->int_rate }}"
                  step="0.01" min="0" max="100" required>
                <div class="invalid-feedback">{{ __('common.loan_management.please_enter_interest_rate') }}</div>
              </div>

              <div class="col-md-4 mb-3">
                <label for="tenor" class="form-label">{{ __('common.loan_management.tenor_periods') }} <span
                    class="text-danger">*</span></label>
                <input type="number" class="form-control" id="tenor" name="tenor" value="{{ $loan->tenor }}"
                  min="1" max="120" required>
                <div class="invalid-feedback">{{ __('common.loan_management.please_enter_loan_tenor') }}</div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4 mb-3">
                <label for="frequency_id" class="form-label">{{ __('common.loan_management.payment_frequency') }} <span
                    class="text-danger">*</span></label>
                <select class="form-select" id="frequency_id" name="frequency_id" required>
                  <option value="">{{ __('common.general.frequency') }}</option>
                  @foreach ($frequencies as $frequency)
                    <option value="{{ $frequency->frequency_id }}"
                      {{ $frequency->frequency_id == $loan->frequency_id ? 'selected' : '' }}>
                      {{ $frequency->frequency }}
                    </option>
                  @endforeach
                </select>
                <div class="invalid-feedback">{{ __('common.loan_management.please_select_payment_frequency') }}</div>
              </div>

              <div class="col-md-4 mb-3">
                <label for="interest_mode" class="form-label">{{ __('common.loan_management.interest_mode') }} <span
                    class="text-danger">*</span></label>
                <select class="form-select" id="interest_mode" name="interest_mode" required>
                  <option value="0" {{ $loan->interest_mode == 0 ? 'selected' : '' }}>
                    {{ __('common.loan_management.flat_rate') }}</option>
                  <option value="1" {{ $loan->interest_mode == 1 ? 'selected' : '' }}>
                    {{ __('common.loan_management.declining_balance') }}</option>
                  <option value="2" {{ $loan->interest_mode == 2 ? 'selected' : '' }}>
                    {{ __('common.loan_management.compound_interest') }}</option>
                </select>
                <div class="invalid-feedback">{{ __('common.loan_management.please_select_interest_mode') }}</div>
              </div>

              <div class="col-md-4 mb-3">
                <label for="payment_mode" class="form-label">{{ __('common.loan_management.payment_mode') }} <span
                    class="text-danger">*</span></label>
                <select class="form-select" id="payment_mode" name="payment_mode" required>
                  <option value="0" {{ $loan->payment_mode == 0 ? 'selected' : '' }}>
                    {{ __('common.loan_management.equal_installment') }}</option>
                  <option value="1" {{ $loan->payment_mode == 1 ? 'selected' : '' }}>
                    {{ __('common.loan_management.equal_principal') }}</option>
                  <option value="2" {{ $loan->payment_mode == 2 ? 'selected' : '' }}>
                    {{ __('common.loan_management.custom_schedule') }}</option>
                </select>
                <div class="invalid-feedback">{{ __('common.loan_management.please_select_payment_mode') }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <!-- Loan Summary -->
        <div class="card mb-4">
          <div class="card-header">
            <h5 class="card-title mb-0">{{ __('common.loan_management.loan_summary') }}</h5>
          </div>
          <div class="card-body">
            <table class="table table-sm">
              <tr>
                <th width="50%">{{ __('common.loan_management.loan_amount') }}:</th>
                <td id="summaryAmount">${{ number_format($loan->amount, 2) }}</td>
              </tr>
              <tr>
                <th>{{ __('common.loan_management.interest_rate') }}:</th>
                <td id="summaryRate">{{ $loan->int_rate }}% p.a.</td>
              </tr>
              <tr>
                <th>{{ __('common.loan_management.tenor_months') }}:</th>
                <td id="summaryTenor">{{ $loan->tenor }} {{ __('common.general.months') }}</td>
              </tr>
              <tr>
                <th>{{ __('common.general.frequency') }}:</th>
                <td id="summaryFrequency">{{ $loan->frequency->frequency ?? '-' }}</td>
              </tr>
              <tr>
                <th>{{ __('common.loan_management.outstanding') }}:</th>
                <td id="summaryOutstanding">${{ number_format($loan->os_balance, 2) }}</td>
              </tr>
              <tr>
                <th>{{ __('common.loan_management.next_payment') }}:</th>
                <td id="summaryNextPayment">{{ $loan->next_pay_date ?? 'N/A' }}</td>
              </tr>
            </table>
          </div>
        </div>

        <!-- Actions -->
        <div class="card">
          <div class="card-header">
            <h5 class="card-title mb-0">{{ __('common.loan_management.actions') }}</h5>
          </div>
          <div class="card-body">
            <button type="submit" class="btn btn-primary w-100 mb-3">
              <i class="fas fa-save"></i> {{ __('common.general.update') }}
            </button>
            <a href="{{ route('loans.index') }}" class="btn btn-secondary w-100">
              <i class="fas fa-arrow-left"></i> {{ __('common.loan_management.back_to_loans') }}
            </a>
          </div>
        </div>
      </div>
    </div>
  </form>
@endsection

@push('scripts')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">

  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

  <script>
    $(document).ready(function() {
      // Initialize TomSelect for account search
      new TomSelect('#acct_id', {
        create: false,
        sortField: {
          field: "text",
          direction: "asc"
        },
        onChange: function(value) {
          if (value) {
            loadAccountDetails(value);
          } else {
            $('#customerDetails').addClass('d-none');
          }
        }
      });

      // Initialize Flatpickr for date
      flatpickr("#date_issue", {
        dateFormat: "Y-m-d",
        defaultDate: "{{ $loan->date_issue }}"
      });

      // Load account details
      function loadAccountDetails(acctId) {
        $.ajax({
          url: `/api/accounts/${acctId}/details`,
          type: 'GET',
          success: function(response) {
            if (response.success) {
              const account = response.data;
              $('#custName').text(account.customer.name_en);
              $('#custIC').text(account.customer.ic_no);
              $('#custPhone').text(account.customer.phone1 || '-');
              $('#acctNo').text(account.acct_no);
              $('#acctType').text(account.account_type.acct_type);
              $('#acctBalance').text('$' + parseFloat(account.balance || 0).toFixed(2));
              $('#customerDetails').removeClass('d-none');
            }
          },
          error: function() {
            toastr.error('Failed to load account details');
          }
        });
      }

      // Update summary when values change
      function updateSummary() {
        const amount = parseFloat($('#amount').val()) || 0;
        const rate = parseFloat($('#int_rate').val()) || 0;
        const tenor = parseInt($('#tenor').val()) || 0;
        const frequency = $('#frequency_id option:selected').text();

        $('#summaryAmount').text('$' + amount.toLocaleString('en-US', {
          minimumFractionDigits: 2
        }));
        $('#summaryRate').text(rate + '% p.a.');
        $('#summaryTenor').text(tenor + ' months');
        $('#summaryFrequency').text(frequency);
      }

      // Bind events
      $('#amount, #int_rate, #tenor').on('input', updateSummary);
      $('#frequency_id').on('change', updateSummary);

      // Form validation
      $('#loanApplicationForm').on('submit', function(e) {
        e.preventDefault();

        const form = this;
        if (!form.checkValidity()) {
          e.stopPropagation();
          form.classList.add('was-validated');
          return;
        }

        // Show loading
        const submitBtn = $(form).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Updating...').prop('disabled', true);

        // Submit form
        $.ajax({
          url: $(form).attr('action'),
          method: $(form).attr('method'),
          data: $(form).serialize(),
          success: function(response) {
            if (response.success) {
              toastr.success(response.message);
              window.location.href = '{{ route('loans.index') }}';
            } else {
              toastr.error(response.message || 'Update failed');
            }
          },
          error: function(xhr) {
            const errors = xhr.responseJSON?.errors;
            if (errors) {
              Object.keys(errors).forEach(key => {
                errors[key].forEach(message => {
                  toastr.error(message);
                });
              });
            } else {
              toastr.error(xhr.responseJSON?.message || 'Update failed');
            }
          },
          complete: function() {
            submitBtn.html(originalText).prop('disabled', false);
          }
        });
      });

      // Initialize summary display
      updateSummary();

      // Load current account details if account is selected
      const currentAcctId = $('#acct_id').val();
      if (currentAcctId) {
        loadAccountDetails(currentAcctId);
      }
    });
  </script>
@endpush
