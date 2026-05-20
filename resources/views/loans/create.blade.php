@extends('admin.layouts.admin_layout')

@section('pageTitle', __('common.loan_management.new_application'))

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('loans.index') }}">{{ __('common.nav.loans') }}</a></li>
  <li class="breadcrumb-item active">{{ __('common.loan_management.new_application') }}</li>
@endsection

@section('content')
  <form id="loanApplicationForm" action="{{ route('loans.store') }}" method="POST">
    @csrf

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
                    <option value="{{ $item->acct_id }}">
                      {{ $item->acct_no }} - {{ $item->customer->name_en }} ({{ $item->customer->ic_no }})
                    </option>
                  @endforeach
                </select>
                <div class="invalid-feedback">{{ __('common.loan_management.please_select_account') }}</div>
              </div>
            </div>

            <div id="customerDetails" class="d-none">
              <div class="row">
                <div class="col-md-6">
                  <table class="table table-sm">
                    <tr>
                      <th width="40%">{{ __('common.loan_management.customer_name') }}</th>
                      <td id="custName">-</td>
                    </tr>
                    <tr>
                      <th>{{ __('common.loan_management.ic_number') }}</th>
                      <td id="custIC">-</td>
                    </tr>
                    <tr>
                      <th>{{ __('common.loan_management.phone') }}</th>
                      <td id="custPhone">-</td>
                    </tr>
                  </table>
                </div>
                <div class="col-md-6">
                  <table class="table table-sm">
                    <tr>
                      <th width="40%">{{ __('common.loan_management.account_no') }}</th>
                      <td id="acctNo">-</td>
                    </tr>
                    <tr>
                      <th>{{ __('common.loan_management.account_type') }}</th>
                      <td id="acctType">-</td>
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
                  placeholder="{{ __('common.loan_management.auto_generated') }}" readonly>
              </div>

              <div class="col-md-4 mb-3">
                <label for="date_issue" class="form-label">{{ __('common.loan_management.issue_date') }} <span
                    class="text-danger">*</span></label>
                <input type="text" class="form-control" id="date_issue" name="date_issue" required>
                <div class="invalid-feedback">{{ __('common.loan_management.please_select_issue_date') }}</div>
              </div>

              <div class="col-md-4 mb-3">
                <label for="purpose_id" class="form-label">{{ __('common.loan_management.loan_purpose') }} <span
                    class="text-danger">*</span></label>
                <select class="form-select" id="purpose_id" name="purpose_id" required>
                  <option value="">{{ __('common.loan_management.select_purpose') }}</option>
                  @foreach (\App\Models\PurposeLoan::all() as $purpose)
                    <option value="{{ $purpose->purpose_id }}">{{ $purpose->purpose_type }}</option>
                  @endforeach
                </select>
                <div class="invalid-feedback">{{ __('common.loan_management.please_select_loan_purpose') }}</div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4 mb-3">
                <label for="amount" class="form-label">{{ __('common.loan_management.loan_amount') }} <span
                    class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text">$</span>
                  <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="100"
                    required>
                </div>
                <div class="invalid-feedback">{{ __('common.loan_management.please_enter_loan_amount') }}</div>
              </div>

              <div class="col-md-4 mb-3">
                <label for="int_rate" class="form-label">{{ __('common.loan_management.interest_rate_percent') }} <span
                    class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="number" class="form-control" id="int_rate" name="int_rate" step="0.01" min="0"
                    max="100" required>
                  <span class="input-group-text">%</span>
                </div>
                <div class="invalid-feedback">{{ __('common.loan_management.please_enter_interest_rate') }}</div>
              </div>

              <div class="col-md-4 mb-3">
                <label for="extra_rate" class="form-label">{{ __('common.loan_management.extra_rate_percent') }}</label>
                <div class="input-group">
                  <input type="number" class="form-control" id="extra_rate" name="extra_rate" step="0.01"
                    min="0" max="100" value="0">
                  <span class="input-group-text">%</span>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4 mb-3">
                <label for="frequency_id" class="form-label">{{ __('common.loan_management.payment_frequency') }} <span
                    class="text-danger">*</span></label>
                <select class="form-select" id="frequency_id" name="frequency_id" required>
                  <option value="">{{ __('common.general.frequency') }}</option>
                  @foreach (\App\Models\PaymentFrequency::all() as $frequency)
                    <option value="{{ $frequency->frequency_id }}" data-days="{{ $frequency->num_days }}">
                      {{ $frequency->frequency }} ({{ $frequency->num_days }} days)
                    </option>
                  @endforeach
                </select>
                <div class="invalid-feedback">{{ __('common.loan_management.please_select_payment_frequency') }}</div>
              </div>

              <div class="col-md-4 mb-3">
                <label for="tenor" class="form-label">{{ __('common.loan_management.tenor_periods') }} <span
                    class="text-danger">*</span></label>
                <input type="number" class="form-control" id="tenor" name="tenor" min="1" required>
                <div class="invalid-feedback">{{ __('common.loan_management.please_enter_loan_tenor') }}</div>
              </div>

              <div class="col-md-4 mb-3">
                <label for="end_pay_date"
                  class="form-label">{{ __('common.loan_management.end_payment_date') }}</label>
                <input type="text" class="form-control" id="end_pay_date" name="end_pay_date" readonly>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4 mb-3">
                <label for="interest_mode" class="form-label">{{ __('common.loan_management.interest_mode') }} <span
                    class="text-danger">*</span></label>
                <select class="form-select" id="interest_mode" name="interest_mode" required>
                  <option value="0">{{ __('common.loan_management.flat_rate') }}</option>
                  <option value="1">{{ __('common.loan_management.declining_balance') }}</option>
                  <option value="2">{{ __('common.loan_management.compound_interest') }}</option>
                </select>
              </div>

              <div class="col-md-4 mb-3">
                <label for="payment_mode" class="form-label">{{ __('common.loan_management.payment_mode') }} <span
                    class="text-danger">*</span></label>
                <select class="form-select" id="payment_mode" name="payment_mode" required>
                  <option value="0">{{ __('common.loan_management.equal_installment') }}</option>
                  <option value="1">{{ __('common.loan_management.equal_principal') }}</option>
                  <option value="2">{{ __('common.loan_management.custom_schedule') }}</option>
                </select>
              </div>

              <div class="col-md-4 mb-3">
                <label for="savings" class="form-label">{{ __('common.loan_management.compulsory_savings') }}</label>
                <div class="input-group">
                  <span class="input-group-text">$</span>
                  <input type="number" class="form-control" id="savings" name="savings" step="0.01"
                    min="0" value="0">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12 mb-3">
                <label for="remark" class="form-label">{{ __('common.loan_management.remarks') }}</label>
                <textarea class="form-control" id="remark" name="remark" rows="3"></textarea>
              </div>
            </div>
          </div>
        </div>

        <!-- Payment Schedule Preview -->
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">{{ __('common.loan_management.payment_schedule_preview') }}</h5>
            <button type="button" class="btn btn-sm btn-primary" id="generateSchedule">
              <i class="fas fa-calculator me-1"></i> {{ __('common.loan_management.generate_schedule') }}
            </button>
          </div>
          <div class="card-body">
            <div id="schedulePreview" class="table-responsive">
              <p class="text-muted text-center">{{ __('common.loan_management.click_generate_schedule') }}</p>
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
                <th>{{ __('common.loan_management.principal_amount') }}</th>
                <td class="text-end"><span id="summaryPrincipal">$0.00</span></td>
              </tr>
              <tr>
                <th>{{ __('common.loan_management.total_interest') }}</th>
                <td class="text-end"><span id="summaryInterest">$0.00</span></td>
              </tr>
              <tr>
                <th>{{ __('common.loan_management.total_payment') }}</th>
                <td class="text-end"><strong><span id="summaryTotal">$0.00</span></strong></td>
              </tr>
              <tr>
                <th>{{ __('common.loan_management.monthly_payment') }}</th>
                <td class="text-end"><span id="summaryMonthly">$0.00</span></td>
              </tr>
            </table>
          </div>
        </div>

        <!-- Actions -->
        <div class="card mb-4">
          <div class="card-header">
            <h5 class="card-title mb-0">{{ __('common.loan_management.actions') }}</h5>
          </div>
          <div class="card-body">
            <button type="submit" class="btn btn-success w-100 mb-2">
              <i class="fas fa-check me-2"></i> {{ __('common.loan_management.submit_application') }}
            </button>
            <button type="button" class="btn btn-primary w-100 mb-2" id="saveAsDraft">
              <i class="fas fa-save me-2"></i> {{ __('common.loan_management.save_as_draft') }}
            </button>
            <button type="button" class="btn btn-secondary w-100"
              onclick="window.location.href='{{ route('loans.index') }}'">
              <i class="fas fa-times me-2"></i> {{ __('common.general.cancel') }}
            </button>
          </div>
        </div>

        <!-- Collateral -->
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">{{ __('common.loan_management.collateral') }}</h5>
            <button type="button" class="btn btn-sm btn-outline-primary" id="addCollateral">
              <i class="fas fa-plus"></i>
            </button>
          </div>
          <div class="card-body">
            <div id="collateralList">
              <p class="text-muted text-center">{{ __('common.loan_management.no_collateral_added') }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>

  <!-- Collateral Modal -->
  <div class="modal fade" id="collateralModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ __('common.loan_management.add_collateral') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="collateralForm">
            <div class="mb-3">
              <label for="collateral_type"
                class="form-label">{{ __('common.loan_management.collateral_type') }}</label>
              <select class="form-select" id="collateral_type">
                <option value="">{{ __('common.loan_management.select_type') }}</option>
                @foreach (\App\Models\CollateralType::all() as $type)
                  <option value="{{ $type->collateral_type_id }}">{{ $type->collateral_type }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label for="collateral_value"
                class="form-label">{{ __('common.loan_management.estimated_value') }}</label>
              <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="number" class="form-control" id="collateral_value" step="0.01" min="0">
              </div>
            </div>
            <div class="mb-3">
              <label for="collateral_no" class="form-label">{{ __('common.loan_management.document_number') }}</label>
              <input type="text" class="form-control" id="collateral_no">
            </div>
            <div class="mb-3">
              <label for="collateral_remarks" class="form-label">{{ __('common.loan_management.remarks') }}</label>
              <textarea class="form-control" id="collateral_remarks" rows="2"></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary"
            data-bs-dismiss="modal">{{ __('common.general.cancel') }}</button>
          <button type="button" class="btn btn-primary"
            id="saveCollateral">{{ __('common.loan_management.add_collateral') }}</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    $(document).ready(function() {
      let collaterals = [];

      // Initialize Tom Select for account selection
      const accountSelect = new TomSelect('#acct_id', {
        valueField: 'acct_id',
        labelField: 'display',
        searchField: ['acct_no', 'acct_name', 'customer_name', 'ic_no'],
        preload: false,
        load: function(query, callback) {
          if (!query.length) return callback();
          $.ajax({
            url: '/api/accounts/search',
            type: 'get',
            data: {
              q: query
            },
            success: function(res) {
              callback(res.map(item => ({
                ...item,
                display: `${item.acct_no} - ${item.customer.name_en} (${item.customer.ic_no})`
              })));
            },
            error: function(xhr) {
              console.error('Account search error:', xhr);
              callback();
            }
          });
        },
        onChange: function(value) {
          if (value) {
            loadAccountDetails(value);
          }
        }
      });

      // Initialize Flatpickr
      flatpickr("#date_issue", {
        dateFormat: "Y-m-d",
        defaultDate: "today"
      });

      // Load account details
      function loadAccountDetails(accountId) {
        $.ajax({
          url: `/api/accounts/${accountId}/details`,
          type: 'get',
          success: function(response) {
            const data = response.data; // Extract data from response
            $('#custName').text(data.customer.name_en);
            $('#custIC').text(data.customer.ic_no);
            $('#custPhone').text(data.customer.phone1);
            $('#acctNo').text(data.acct_no);
            $('#acctType').text(data.account_type.acct_type);
            $('#acctBalance').text(formatCurrency(data.balance));
            $('#customerDetails').removeClass('d-none');
          },
          error: function(xhr) {
            toastr.error('Failed to load account details');
            console.error('Account details error:', xhr);
          }
        });
      }

      // Calculate end payment date
      function calculateEndDate() {
        const issueDate = $('#date_issue').val();
        const frequencyId = $('#frequency_id').val();
        const tenor = $('#tenor').val();

        if (issueDate && frequencyId && tenor) {
          const days = $('#frequency_id option:selected').data('days');
          const totalDays = days * tenor;
          const endDate = new Date(issueDate);
          endDate.setDate(endDate.getDate() + totalDays);
          $('#end_pay_date').val(endDate.toISOString().split('T')[0]);
        }
      }

      $('#date_issue, #frequency_id, #tenor').change(calculateEndDate);

      // Generate payment schedule
      $('#generateSchedule').click(function() {
        const amount = parseFloat($('#amount').val());
        const rate = parseFloat($('#int_rate').val());
        const tenor = parseInt($('#tenor').val());
        const frequency = $('#frequency_id').val();
        const interestMode = $('#interest_mode').val();
        const paymentMode = $('#payment_mode').val();

        if (!amount || !rate || !tenor || !frequency) {
          toastr.warning('Please fill in all loan details first');
          return;
        }

        // Calculate payment schedule
        let schedule = calculatePaymentSchedule(amount, rate, tenor, interestMode, paymentMode);
        displaySchedule(schedule);
        updateSummary(schedule);
      });

      function calculatePaymentSchedule(principal, rate, periods, interestMode, paymentMode) {
        let schedule = [];
        let balance = principal;
        let totalInterest = 0;

        // Simplified calculation - you can enhance this
        const monthlyRate = rate / 12 / 100;
        const monthlyPayment = principal * (monthlyRate * Math.pow(1 + monthlyRate, periods)) /
          (Math.pow(1 + monthlyRate, periods) - 1);

        for (let i = 1; i <= periods; i++) {
          const interest = balance * monthlyRate;
          const principalPayment = monthlyPayment - interest;
          balance -= principalPayment;
          totalInterest += interest;

          schedule.push({
            period: i,
            principal: principalPayment,
            interest: interest,
            total: monthlyPayment,
            balance: Math.max(0, balance)
          });
        }

        return schedule;
      }

      function displaySchedule(schedule) {
        let html = '<table class="table table-sm table-striped">';
        html += '<thead><tr>';
        html += '<th>#</th>';
        html += '<th>Principal</th>';
        html += '<th>Interest</th>';
        html += '<th>Total</th>';
        html += '<th>Balance</th>';
        html += '</tr></thead><tbody>';

        schedule.forEach(row => {
          html += '<tr>';
          html += `<td>${row.period}</td>`;
          html += `<td>${formatCurrency(row.principal)}</td>`;
          html += `<td>${formatCurrency(row.interest)}</td>`;
          html += `<td>${formatCurrency(row.total)}</td>`;
          html += `<td>${formatCurrency(row.balance)}</td>`;
          html += '</tr>';
        });

        html += '</tbody></table>';
        $('#schedulePreview').html(html);
      }

      function updateSummary(schedule) {
        const principal = parseFloat($('#amount').val());
        const totalInterest = schedule.reduce((sum, row) => sum + row.interest, 0);
        const totalPayment = principal + totalInterest;
        const monthlyPayment = schedule[0] ? schedule[0].total : 0;

        $('#summaryPrincipal').text(formatCurrency(principal));
        $('#summaryInterest').text(formatCurrency(totalInterest));
        $('#summaryTotal').text(formatCurrency(totalPayment));
        $('#summaryMonthly').text(formatCurrency(monthlyPayment));
      }

      // Add collateral
      $('#addCollateral').click(function() {
        $('#collateralModal').modal('show');
      });

      $('#saveCollateral').click(function() {
        const type = $('#collateral_type').val();
        const value = $('#collateral_value').val();
        const no = $('#collateral_no').val();
        const remarks = $('#collateral_remarks').val();

        if (!type || !value) {
          toastr.warning('Please fill in required collateral fields');
          return;
        }

        const typeName = $('#collateral_type option:selected').text();

        collaterals.push({
          type: type,
          typeName: typeName,
          value: value,
          no: no,
          remarks: remarks
        });

        displayCollaterals();
        $('#collateralModal').modal('hide');
        $('#collateralForm')[0].reset();
      });

      function displayCollaterals() {
        if (collaterals.length === 0) {
          $('#collateralList').html('<p class="text-muted text-center">No collateral added</p>');
          return;
        }

        let html = '<div class="list-group">';
        collaterals.forEach((col, index) => {
          html += '<div class="list-group-item">';
          html += `<div class="d-flex justify-content-between align-items-start">`;
          html += `<div>`;
          html += `<h6 class="mb-0">${col.typeName}</h6>`;
          html += `<small class="text-muted">Value: ${formatCurrency(col.value)}</small>`;
          if (col.no) {
            html += `<br><small class="text-muted">Doc: ${col.no}</small>`;
          }
          html += `</div>`;
          html +=
            `<button type="button" class="btn btn-sm btn-outline-danger" onclick="removeCollateral(${index})">`;
          html += `<i class="fas fa-times"></i>`;
          html += `</button>`;
          html += `</div>`;
          html += '</div>';
        });
        html += '</div>';

        $('#collateralList').html(html);
      }

      window.removeCollateral = function(index) {
        collaterals.splice(index, 1);
        displayCollaterals();
      };

      // Form submission
      $('#loanApplicationForm').on('submit', function(e) {
        e.preventDefault();

        // Validate form
        if (!this.checkValidity()) {
          e.stopPropagation();
          $(this).addClass('was-validated');
          return;
        }

        // Prepare data
        const formData = $(this).serializeArray();
        formData.push({
          name: 'collaterals',
          value: JSON.stringify(collaterals)
        });

        // Submit via AJAX
        $.ajax({
          url: $(this).attr('action'),
          type: 'post',
          data: $.param(formData),
          success: function(response) {
            if (response.success) {
              Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: response.message,
                confirmButtonText: 'ok'
              }).then(() => {
                window.location.href = '{{ route('loans.index') }}';
              });
            }
          },
          error: function(xhr) {
            const errors = xhr.responseJSON.errors;
            if (errors) {
              $.each(errors, function(key, value) {
                toastr.error(value[0]);
              });
            } else {
              toastr.error('An error occurred. Please try again.');
            }
          }
        });
      });

      // Save as draft
      $('#saveAsDraft').click(function() {
        // Implement save as draft functionality
        toastr.info('Draft saving functionality to be implemented');
      });
    });

    function formatCurrency(amount) {
      return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'usd'
      }).format(amount);
    }
  </script>
@endpush
