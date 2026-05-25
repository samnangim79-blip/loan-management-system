@extends('admin.layouts.admin_layout')

@section('pageTitle', __('common.general.system_settings'))

@push('styles')
  <style>
    .settings-card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
      transition: all 0.3s ease;
      height: 100%;
    }

    .settings-card:hover {
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
      transform: translateY(-2px);
    }

    .settings-card .card-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: #fff;
      border-radius: 12px 12px 0 0 !important;
      padding: 1.25rem;
    }

    .settings-card .card-header i {
      font-size: 1.5rem;
      margin-right: 0.75rem;
    }

    .settings-card .card-body {
      padding: 1.5rem;
    }

    .setting-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem;
      border-bottom: 1px solid #e9ecef;
      transition: background 0.2s ease;
    }

    .setting-item:last-child {
      border-bottom: none;
    }

    .setting-item:hover {
      background: #f8f9fa;
      border-radius: 8px;
    }

    .setting-label {
      font-weight: 500;
      color: #374151;
    }

    .setting-description {
      font-size: 0.85rem;
      color: #6b7280;
      margin-top: 0.25rem;
    }

    .setting-value {
      font-weight: 600;
      color: #4f46e5;
    }

    .setting-input {
      width: 200px;
      text-align: right;
    }

    .nav-pills-settings .nav-link {
      color: #6b7280;
      font-weight: 500;
      border-radius: 8px;
      padding: 0.75rem 1.25rem;
      margin-bottom: 0.5rem;
      transition: all 0.3s ease;
    }

    .nav-pills-settings .nav-link:hover {
      background: #f3f4f6;
      color: #374151;
    }

    .nav-pills-settings .nav-link.active {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: #fff;
    }

    .nav-pills-settings .nav-link i {
      margin-right: 0.75rem;
      width: 20px;
      text-align: center;
    }

    .config-group-title {
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: #9ca3af;
      font-weight: 600;
      padding: 0.75rem 1.25rem;
      margin-top: 1rem;
    }

    .form-switch .form-check-input {
      width: 48px;
      height: 24px;
      cursor: pointer;
    }

    .form-switch .form-check-input:checked {
      background-color: #4f46e5;
      border-color: #4f46e5;
    }

    .badge-setting {
      font-size: 0.7rem;
      padding: 0.35em 0.65em;
    }

    .quick-stats {
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
      border-radius: 12px;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
    }

    .quick-stat-item {
      text-align: center;
      padding: 1rem;
    }

    .quick-stat-value {
      font-size: 2rem;
      font-weight: 700;
      color: #4f46e5;
    }

    .quick-stat-label {
      font-size: 0.85rem;
      color: #6b7280;
      margin-top: 0.25rem;
    }
  </style>
@endpush

@section('content')
  <div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="mb-1 fw-bold text-dark">
          <i class="fas fa-cog me-2 text-primary"></i>System Settings
        </h4>
        <p class="text-muted mb-0">Configure system parameters, loan settings, and preferences</p>
      </div>
      <div>
        <button type="button" class="btn btn-primary" id="saveAllSettings">
          <i class="fas fa-save me-2"></i>Save All Changes
        </button>
      </div>
    </div>

    <div class="row">
      <!-- Settings Navigation -->
      <div class="col-md-3">
        <div class="card settings-card mb-4">
          <div class="card-body p-3">
            <nav class="nav nav-pills flex-column nav-pills-settings">
              <div class="config-group-title">{{ __('common.nav.general') }}</div>
              <a class="nav-link active" data-bs-toggle="pill" href="#general">
                <i class="fas fa-cog"></i>General Settings
              </a>
              <a class="nav-link" data-bs-toggle="pill" href="#company">
                <i class="fas fa-building"></i>Company Info
              </a>

              <div class="config-group-title">{{ __('common.nav.loan_settings') }}</div>
              <a class="nav-link" data-bs-toggle="pill" href="#loan">
                <i class="fas fa-hand-holding-usd"></i>Loan Parameters
              </a>
              <a class="nav-link" data-bs-toggle="pill" href="#interest">
                <i class="fas fa-percent"></i>Interest & Fees
              </a>
              <a class="nav-link" data-bs-toggle="pill" href="#penalty">
                <i class="fas fa-exclamation-triangle"></i>Penalties
              </a>

              <div class="config-group-title">{{ __('common.nav.account_settings') }}</div>
              <a class="nav-link" data-bs-toggle="pill" href="#account">
                <i class="fas fa-wallet"></i>Account Settings
              </a>
              <a class="nav-link" data-bs-toggle="pill" href="#deposit">
                <i class="fas fa-piggy-bank"></i>Fixed Deposit
              </a>

              <div class="config-group-title">{{ __('common.nav.system') }}</div>
              <a class="nav-link" data-bs-toggle="pill" href="#security">
                <i class="fas fa-shield-alt"></i>Security
              </a>
              <a class="nav-link" data-bs-toggle="pill" href="#authentication">
                <i class="fas fa-key"></i>Authentication
              </a>
              <a class="nav-link" data-bs-toggle="pill" href="#notifications">
                <i class="fas fa-bell"></i>Notifications
              </a>
              <a class="nav-link" data-bs-toggle="pill" href="#backup">
                <i class="fas fa-database"></i>Backup & Restore
              </a>
            </nav>
          </div>
        </div>
      </div>

      <!-- Settings Content -->
      <div class="col-md-9">
        <div class="tab-content">
          <!-- General Settings -->
          <div class="tab-pane fade show active" id="general">
            <div class="card settings-card">
              <div class="card-header">
                <i class="fas fa-cog"></i>
                <h5 class="mb-0">{{ __('common.nav.general_settings') }}</h5>
              </div>
              <div class="card-body">
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.general.system_language') }}</div>
                    <div class="setting-description">{{ __('common.general.default_language_for_the_system_interface') }}
                    </div>
                  </div>
                  <select class="form-control setting-input config-input" data-config="SYSTEM_LANGUAGE">
                    <option value="en">{{ __('common.general.english') }}</option>
                    <option value="kh">ខ្មែរ (Khmer)</option>
                  </select>
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.general.date_format') }}</div>
                    <div class="setting-description">
                      {{ __('common.general.format_for_displaying_dates_throughout_the_system') }}</div>
                  </div>
                  <select class="form-control setting-input config-input" data-config="DATE_FORMAT">
                    <option value="ymd">YYYY-MM-DD</option>
                    <option value="d/m/Y">DD/MM/YYYY</option>
                    <option value="m/d/Y">MM/DD/YYYY</option>
                    <option value="dmy">DD-Mon-YYYY</option>
                  </select>
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.general.default_currency') }}</div>
                    <div class="setting-description">{{ __('common.general.primary_currency_for_transactions') }}</div>
                  </div>
                  <select class="form-control setting-input config-input" data-config="DEFAULT_CURRENCY">
                    <option value="usd">USD - US Dollar</option>
                    <option value="khr">KHR - Cambodian Riel</option>
                  </select>
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">Exchange Rate (KHR/USD)</div>
                    <div class="setting-description">
                      {{ __('common.general.current_exchange_rate_for_currency_conversion') }}</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="EXCHANGE_RATE"
                    value="4100" step="1">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.general.fiscal_year_start') }}</div>
                    <div class="setting-description">{{ __('common.pagination.start_month_of_the_fiscal_year') }}</div>
                  </div>
                  <select class="form-control setting-input config-input" data-config="FISCAL_YEAR_START">
                    <option value="1">{{ __('common.general.january') }}</option>
                    <option value="4">{{ __('common.general.april') }}</option>
                    <option value="7">{{ __('common.general.july') }}</option>
                    <option value="10">{{ __('common.pagination.october') }}</option>
                  </select>
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">Session Timeout (minutes)</div>
                    <div class="setting-description">{{ __('common.nav.auto_logout_after_inactivity_period') }}</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="SESSION_TIMEOUT"
                    value="30" min="5" max="480">
                </div>
              </div>
            </div>
          </div>

          <!-- Company Info -->
          <div class="tab-pane fade" id="company">
            <div class="card settings-card">
              <div class="card-header">
                <i class="fas fa-cog"></i>
                <h5 class="mb-0">{{ __('common.messages.company_information') }}</h5>
              </div>
              <div class="card-body">
                <div class="setting-item">
                  <div>
                    <div class="setting-label">Company Name (English)</div>
                    <div class="setting-description">{{ __('common.general.official_company_name_in_english') }}</div>
                  </div>
                  <input type="text" class="form-control setting-input config-input" data-config="COMPANY_NAME_EN" value=""
                    style="width: 300px;">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">Company Name (Khmer)</div>
                    <div class="setting-description">{{ __('common.general.official_company_name_in_khmer') }}</div>
                  </div>
                  <input type="text" class="form-control setting-input config-input" data-config="COMPANY_NAME_KH" value=""
                    style="width: 300px;">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.general.license_number') }}</div>
                    <div class="setting-description">{{ __('common.pagination.microfinance_institution_license_number') }}</div>
                  </div>
                  <input type="text" class="form-control setting-input config-input" data-config="LICENSE_NO" value="">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.general.tax_id') }}</div>
                    <div class="setting-description">{{ __('common.general.tax_identification_number') }}</div>
                  </div>
                  <input type="text" class="form-control setting-input config-input" data-config="TAX_ID" value="">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.general.phone_number') }}</div>
                    <div class="setting-description">{{ __('common.general.main_contact_phone_number') }}</div>
                  </div>
                  <input type="text" class="form-control setting-input config-input" data-config="COMPANY_PHONE" value="">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.general.email_address') }}</div>
                    <div class="setting-description">{{ __('common.general.main_contact_email') }}</div>
                  </div>
                  <input type="email" class="form-control setting-input config-input" data-config="COMPANY_EMAIL" value=""
                    style="width: 300px;">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.general.address') }}</div>
                    <div class="setting-description">{{ __('common.general.company_headquarters_address') }}</div>
                  </div>
                  <input type="text" class="form-control setting-input config-input" data-config="COMPANY_ADDRESS" value=""
                    style="width: 400px;">
                </div>
              </div>
            </div>
          </div>

          <!-- Loan Parameters -->
          <div class="tab-pane fade" id="loan">
            <div class="card settings-card">
              <div class="card-header">
                <i class="fas fa-cog"></i>
                <h5 class="mb-0">{{ __('common.general.loan_parameters') }}</h5>
              </div>
              <div class="card-body">
                <div class="setting-item">
                  <div>
                    <div class="setting-label">Minimum Loan Amount (USD)</div>
                    <div class="setting-description">{{ __('common.general.minimum_amount_that_can_be_loaned') }}</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="MIN_LOAN_AMOUNT" value="100" step="10">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">Maximum Loan Amount (USD)</div>
                    <div class="setting-description">{{ __('common.general.maximum_amount_that_can_be_loaned') }}</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="MAX_LOAN_AMOUNT" value="50000" step="100">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">Minimum Loan Term (months)</div>
                    <div class="setting-description">{{ __('common.general.shortest_allowed_loan_duration') }}</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="MIN_LOAN_TERM" value="1" min="1">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">Maximum Loan Term (months)</div>
                    <div class="setting-description">{{ __('common.general.longest_allowed_loan_duration') }}</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="MAX_LOAN_TERM" value="60" max="360">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.general.default_interest_calculation') }}</div>
                    <div class="setting-description">{{ __('common.general.default_method_for_calculating_interest') }}</div>
                  </div>
                  <select class="form-control setting-input config-input" data-config="DEFAULT_INTEREST_CALC">
                    <option value="1">{{ __('common.general.flat_rate') }}</option>
                    <option value="2">{{ __('common.general.declining_balance') }}</option>
                    <option value="3">{{ __('common.general.effective_rate') }}</option>
                  </select>
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">Grace Period (days)</div>
                    <div class="setting-description">{{ __('common.general.days_after_due_date_before_penalty_applies') }}</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="GRACE_PERIOD_DAYS" value="3" min="0"
                    max="30">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.general.require_collateral') }}</div>
                    <div class="setting-description">{{ __('common.nav.require_collateral_for_all_loans') }}</div>
                  </div>
                  <div class="form-check form-switch">
                    <input class="form-check-input config-input" type="checkbox" data-config="REQUIRE_COLLATERAL">
                  </div>
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">Collateral Coverage Ratio (%)</div>
                    <div class="setting-description">Minimum collateral value as % of loan amount</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="COLLATERAL_COVERAGE" value="120"
                    min="100" max="300">
                </div>
              </div>
            </div>
          </div>

          <!-- Interest & Fees -->
          <div class="tab-pane fade" id="interest">
            <div class="card settings-card">
              <div class="card-header">
                <i class="fas fa-cog"></i>
                <h5 class="mb-0">Interest Rates & Fees</h5>
              </div>
              <div class="card-body">
                <div class="setting-item">
                  <div>
                    <div class="setting-label">Default Interest Rate (% p.a.)</div>
                    <div class="setting-description">{{ __('common.nav.standard_annual_interest_rate_for_loans') }}</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="DEFAULT_INTEREST_RATE" value="18"
                    step="0.1" min="0" max="100">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">Minimum Interest Rate (%)</div>
                    <div class="setting-description">{{ __('common.general.lowest_allowed_interest_rate') }}</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="MIN_INTEREST_RATE" value="12" step="0.1"
                    min="0">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">Maximum Interest Rate (%)</div>
                    <div class="setting-description">Highest allowed interest rate (regulatory limit)</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="MAX_INTEREST_RATE" value="36" step="0.1"
                    max="100">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">Processing Fee (%)</div>
                    <div class="setting-description">One-time fee charged at loan disbursement</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="PROCESSING_FEE" value="1" step="0.1"
                    min="0" max="10">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">Insurance Fee (%)</div>
                    <div class="setting-description">{{ __('common.general.loan_protection_insurance_fee') }}</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="INSURANCE_FEE" value="0.5" step="0.1"
                    min="0" max="5">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">Early Repayment Fee (%)</div>
                    <div class="setting-description">{{ __('common.pagination.fee_for_paying_off_loan_before_term_ends') }}</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="EARLY_REPAYMENT_FEE" value="2"
                    step="0.1" min="0" max="10">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">Stamp Duty (fixed amount)</div>
                    <div class="setting-description">{{ __('common.general.fixed_stamp_duty_fee_per_loan_contract') }}</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="STAMP_DUTY" value="5" step="1"
                    min="0">
                </div>
              </div>
            </div>
          </div>

          <!-- Penalties -->
          <div class="tab-pane fade" id="penalty">
            <div class="card settings-card">
              <div class="card-header">
                <i class="fas fa-cog"></i>
                <h5 class="mb-0">{{ __('common.nav.penalty_settings') }}</h5>
              </div>
              <div class="card-body">
                <div class="setting-item">
                  <div>
                    <div class="setting-label">Late Payment Penalty (%)</div>
                    <div class="setting-description">Penalty rate on overdue payments (% of overdue amount)</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="LATE_PAYMENT_PENALTY" value="2"
                    step="0.1" min="0" max="20">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.general.penalty_calculation_method') }}</div>
                    <div class="setting-description">{{ __('common.general.how_penalty_is_calculated') }}</div>
                  </div>
                  <select class="form-control setting-input config-input" data-config="PENALTY_CALC_METHOD">
                    <option value="1">{{ __('common.general.fixed_amount') }}</option>
                    <option value="2">{{ __('common.pagination.percentage_of_overdue') }}</option>
                    <option value="3">{{ __('common.general.daily_compounding') }}</option>
                  </select>
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">Maximum Penalty (%)</div>
                    <div class="setting-description">{{ __('common.pagination.cap_on_total_penalty_charges') }}</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="MAX_PENALTY" value="25" step="1"
                    min="0" max="100">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.general.days_before_legal_action') }}</div>
                    <div class="setting-description">{{ __('common.general.days_overdue_before_initiating_legal_proceedings') }}
                    </div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="DAYS_BEFORE_LEGAL" value="90" min="30"
                    max="365">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">Days Before Write-Off</div>
                    <div class="setting-description">{{ __('common.pagination.days_overdue_before_loan_is_written_off') }}</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="DAYS_BEFORE_WRITEOFF" value="365"
                    min="180" max="730">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.pagination.enable_automatic_penalty') }}</div>
                    <div class="setting-description">{{ __('common.nav.automatically_apply_penalty_on_overdue_loans') }}</div>
                  </div>
                  <div class="form-check form-switch">
                    <input class="form-check-input config-input" type="checkbox" data-config="AUTO_PENALTY" checked>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Account Settings -->
          <div class="tab-pane fade" id="account">
            <div class="card settings-card">
              <div class="card-header">
                <i class="fas fa-cog"></i>
                <h5 class="mb-0">{{ __('common.nav.account_settings') }}</h5>
              </div>
              <div class="card-body">
                <div class="setting-item">
                  <div>
                    <div class="setting-label">Minimum Opening Balance (USD)</div>
                    <div class="setting-description">{{ __('common.pagination.minimum_deposit_to_open_a_savings_account') }}</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="MIN_OPENING_BALANCE" value="5"
                    step="1" min="0">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">Minimum Balance (USD)</div>
                    <div class="setting-description">{{ __('common.pagination.minimum_balance_to_maintain_in_account') }}</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="MIN_BALANCE" value="5" step="1"
                    min="0">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">Savings Interest Rate (% p.a.)</div>
                    <div class="setting-description">{{ __('common.nav.annual_interest_rate_for_savings_accounts') }}</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="SAVINGS_INTEREST_RATE" value="2"
                    step="0.1" min="0" max="20">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.general.interest_payment_frequency') }}</div>
                    <div class="setting-description">{{ __('common.general.how_often_interest_is_credited') }}</div>
                  </div>
                  <select class="form-control setting-input config-input" data-config="INTEREST_PAYMENT_FREQ">
                    <option value="D">{{ __('common.general.daily') }}</option>
                    <option value="M">{{ __('common.general.monthly') }}</option>
                    <option value="Q">{{ __('common.general.quarterly') }}</option>
                    <option value="Y">{{ __('common.general.yearly') }}</option>
                  </select>
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.general.dormant_account_days') }}</div>
                    <div class="setting-description">{{ __('common.pagination.days_of_inactivity_before_account_becomes_dormant') }}
                    </div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="DORMANT_ACCOUNT_DAYS" value="365"
                    min="90" max="730">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.general.account_number_prefix') }}</div>
                    <div class="setting-description">{{ __('common.general.prefix_for_generated_account_numbers') }}</div>
                  </div>
                  <input type="text" class="form-control setting-input config-input" data-config="ACCOUNT_PREFIX" value="sa" maxlength="5">
                </div>
              </div>
            </div>
          </div>

          <!-- Fixed Deposit -->
          <div class="tab-pane fade" id="deposit">
            <div class="card settings-card">
              <div class="card-header">
                <i class="fas fa-cog"></i>
                <h5 class="mb-0">{{ __('common.nav.fixed_deposit_settings') }}</h5>
              </div>
              <div class="card-body">
                <div class="setting-item">
                  <div>
                    <div class="setting-label">Minimum FD Amount (USD)</div>
                    <div class="setting-description">{{ __('common.general.minimum_fixed_deposit_amount') }}</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="MIN_FD_AMOUNT" value="100" step="10"
                    min="0">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">Minimum FD Term (months)</div>
                    <div class="setting-description">{{ __('common.general.shortest_fixed_deposit_term') }}</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="MIN_FD_TERM" value="1" min="1"
                    max="12">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">Maximum FD Term (months)</div>
                    <div class="setting-description">{{ __('common.general.longest_fixed_deposit_term') }}</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="MAX_FD_TERM" value="60" min="12"
                    max="120">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">Early Withdrawal Penalty (%)</div>
                    <div class="setting-description">{{ __('common.general.penalty_for_withdrawing_fd_before_maturity') }}</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="FD_EARLY_WITHDRAWAL_PENALTY" value="50"
                    step="5" min="0" max="100">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.pagination.auto_renewal') }}</div>
                    <div class="setting-description">{{ __('common.pagination.automatically_renew_fd_on_maturity') }}</div>
                  </div>
                  <div class="form-check form-switch">
                    <input class="form-check-input config-input" type="checkbox" data-config="FD_AUTO_RENEWAL">
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Security Settings -->
          <div class="tab-pane fade" id="security">
            <div class="card settings-card">
              <div class="card-header">
                <i class="fas fa-cog"></i>
                <h5 class="mb-0">{{ __('common.nav.security_settings') }}</h5>
              </div>
              <div class="card-body">
                <div class="setting-item">
                  <div>
                    <div class="setting-label">Two-Factor Authentication</div>
                    <div class="setting-description">Require 2FA for user login</div>
                  </div>
                  <div class="form-check form-switch">
                    <input class="form-check-input config-input" type="checkbox" data-config="REQUIRE_2FA">
                  </div>
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">Password Expiry (days)</div>
                    <div class="setting-description">{{ __('common.auth.force_password_change_after_this_many_days') }}</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="PASSWORD_EXPIRY_DAYS" value="90"
                    min="30" max="365">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.auth.minimum_password_length') }}</div>
                    <div class="setting-description">{{ __('common.form.minimum_characters_required_for_password') }}</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="MIN_PASSWORD_LENGTH" value="8"
                    min="6" max="20">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.auth.max_login_attempts') }}</div>
                    <div class="setting-description">{{ __('common.general.lock_account_after_this_many_failed_attempts') }}</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="MAX_LOGIN_ATTEMPTS" value="5" min="3"
                    max="10">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">Account Lockout Duration (minutes)</div>
                    <div class="setting-description">{{ __('common.general.how_long_account_remains_locked') }}</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="LOCKOUT_DURATION" value="30" min="5"
                    max="1440">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.general.require_approval_for_large_transactions') }}</div>
                    <div class="setting-description">{{ __('common.general.transactions_above_limit_need_supervisor_approval') }}
                    </div>
                  </div>
                  <div class="form-check form-switch">
                    <input class="form-check-input config-input" type="checkbox" data-config="REQUIRE_APPROVAL_LARGE_TX" checked>
                  </div>
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">Large Transaction Threshold (USD)</div>
                    <div class="setting-description">{{ __('common.general.amount_above_which_approval_is_required') }}</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="LARGE_TX_THRESHOLD" value="10000" step="1000"
                    min="1000">
                </div>
              </div>
            </div>
          </div>

          <!-- Notifications -->
          <div class="tab-pane fade" id="notifications">
            <div class="card settings-card">
              <div class="card-header">
                <i class="fas fa-cog"></i>
                <h5 class="mb-0">{{ __('common.general.notification_settings') }}</h5>
              </div>
              <div class="card-body">
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.general.email_notifications') }}</div>
                    <div class="setting-description">{{ __('common.general.enable_email_notifications_for_transactions') }}</div>
                  </div>
                  <div class="form-check form-switch">
                    <input class="form-check-input config-input" type="checkbox" data-config="EMAIL_NOTIFICATIONS" checked>
                  </div>
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.general.sms_notifications') }}</div>
                    <div class="setting-description">{{ __('common.general.enable_sms_notifications_for_transactions') }}</div>
                  </div>
                  <div class="form-check form-switch">
                    <input class="form-check-input config-input" type="checkbox" data-config="SMS_NOTIFICATIONS">
                  </div>
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.general.payment_reminder_days') }}</div>
                    <div class="setting-description">{{ __('common.general.days_before_due_date_to_send_payment_reminder') }}</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="PAYMENT_REMINDER_DAYS" value="3"
                    min="1" max="14">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.general.overdue_notification_days') }}</div>
                    <div class="setting-description">{{ __('common.general.days_after_due_date_to_send_overdue_notice') }}</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="OVERDUE_NOTIFICATION_DAYS" value="1"
                    min="1" max="7">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.general.fd_maturity_reminder_days') }}</div>
                    <div class="setting-description">{{ __('common.general.days_before_fd_maturity_to_notify_customer') }}</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="FD_MATURITY_REMINDER_DAYS" value="7"
                    min="1" max="30">
                </div>
              </div>
            </div>
          </div>

          <!-- Authentication Settings -->
          <div class="tab-pane fade" id="authentication">
            <div class="card settings-card">
              <div class="card-header">
                <i class="fas fa-cog"></i>
                <h5 class="mb-0">{{ __('common.nav.authentication_settings') }}</h5>
              </div>
              <div class="card-body">
                <!-- Registration Settings -->
                <h6 class="text-muted mb-3"><i class="fas fa-user-plus me-2"></i>Registration</h6>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.general.allow_user_registration') }}</div>
                    <div class="setting-description">{{ __('common.general.enable_public_user_registration') }}</div>
                  </div>
                  <div class="form-check form-switch">
                    <input class="form-check-input config-input" type="checkbox" data-config="ALLOW_REGISTRATION" checked>
                  </div>
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.general.require_email_verification') }}</div>
                    <div class="setting-description">New users must verify email before accessing the system</div>
                  </div>
                  <div class="form-check form-switch">
                    <input class="form-check-input config-input" type="checkbox" data-config="REQUIRE_EMAIL_VERIFICATION">
                  </div>
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.general.default_user_role') }}</div>
                    <div class="setting-description">{{ __('common.auth.role_assigned_to_new_registered_users') }}</div>
                  </div>
                  <select class="form-control setting-input config-input" data-config="DEFAULT_USER_ROLE">
                    <option value="user">{{ __('common.general.user') }}</option>
                    <option value="staff">{{ __('common.general.staff') }}</option>
                    <option value="teller">{{ __('common.general.teller') }}</option>
                  </select>
                </div>

                <hr class="my-4">

                <!-- Social Login Settings -->
                <h6 class="text-muted mb-3"><i class="fas fa-share-alt me-2"></i>Social Login</h6>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.auth.enable_social_login') }}</div>
                    <div class="setting-description">{{ __('common.nav.allow_users_to_login_with_social_media_accounts') }}</div>
                  </div>
                  <div class="form-check form-switch">
                    <input class="form-check-input config-input" type="checkbox" data-config="ENABLE_SOCIAL_LOGIN" checked>
                  </div>
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label"><i class="fab fa-google text-danger me-2"></i>Google Login</div>
                    <div class="setting-description">{{ __('common.auth.allow_login_with_google_account') }}</div>
                  </div>
                  <div class="form-check form-switch">
                    <input class="form-check-input config-input" type="checkbox" data-config="ENABLE_GOOGLE_LOGIN" checked>
                  </div>
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label"><i class="fab fa-github text-dark me-2"></i>GitHub Login</div>
                    <div class="setting-description">{{ __('common.auth.allow_login_with_github_account') }}</div>
                  </div>
                  <div class="form-check form-switch">
                    <input class="form-check-input config-input" type="checkbox" data-config="ENABLE_GITHUB_LOGIN" checked>
                  </div>
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label"><i class="fab fa-x-twitter text-dark me-2"></i>X.com (Twitter) Login
                    </div>
                    <div class="setting-description">Allow login with X.com account</div>
                  </div>
                  <div class="form-check form-switch">
                    <input class="form-check-input config-input" type="checkbox" data-config="ENABLE_TWITTER_LOGIN" checked>
                  </div>
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label"><i class="fab fa-telegram text-info me-2"></i>Telegram Login</div>
                    <div class="setting-description">{{ __('common.auth.allow_login_with_telegram_account') }}</div>
                  </div>
                  <div class="form-check form-switch">
                    <input class="form-check-input config-input" type="checkbox" data-config="ENABLE_TELEGRAM_LOGIN" checked>
                  </div>
                </div>

                <hr class="my-4">

                <!-- OAuth Credentials -->
                <h6 class="text-muted mb-3"><i class="fas fa-cogs me-2"></i>OAuth Credentials</h6>
                <div class="alert alert-warning">
                  <i class="fas fa-exclamation-triangle me-2"></i>
                  <strong>Security Note:</strong> OAuth credentials are sensitive. Changes will be saved to the
                  <code>.env</code> file.
                  Keep your credentials secure and never share them publicly.
                </div>

                <!-- Google OAuth -->
                <div class="card mb-3 border">
                  <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                    <span><i class="fab fa-google text-danger me-2"></i>Google OAuth</span>
                    @if (config('services.google.client_id'))
                      <span class="badge bg-success badge-setting"><i class="fas fa-check me-1"></i>Configured</span>
                    @else
                      <span class="badge bg-secondary badge-setting"><i class="fas fa-times me-1"></i>Not Configured</span>
                    @endif
                  </div>
                  <div class="card-body">
                    <div class="row g-3">
                      <div class="col-md-6">
                        <label class="form-label">{{ __('common.general.client_id') }}</label>
                        <input type="text" class="form-control" data-env="GOOGLE_CLIENT_ID"
                          value="{{ config('services.google.client_id') }}"
                          placeholder="{{ __('common.general.enter_google_client_id') }}">
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">{{ __('common.form.client_secret') }}</label>
                        <div class="input-group">
                          <input type="text" class="form-control env-input" data-env="GOOGLE_CLIENT_SECRET"
                            value="{{ config('services.google.client_secret') }}"
                            placeholder="{{ __('common.form.enter_google_client_secret') }}">
                          <button class="btn btn-outline-secondary toggle-password" type="button">
                            <i class="fas fa-eye"></i>
                          </button>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <label class="form-label">{{ __('common.form.redirect_uri') }}</label>
                        <input type="text" class="form-control" value="{{ url('/auth/google/callback') }}"
                          readonly>
                        <small class="text-muted">Add this URL to your Google Console authorized redirect URIs</small>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- GitHub OAuth -->
                <div class="card mb-3 border">
                  <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                    <span><i class="fab fa-github text-dark me-2"></i>GitHub OAuth</span>
                    @if (config('services.github.client_id'))
                      <span class="badge bg-success badge-setting"><i class="fas fa-check me-1"></i>Configured</span>
                    @else
                      <span class="badge bg-secondary badge-setting"><i class="fas fa-times me-1"></i>Not Configured</span>
                    @endif
                  </div>
                  <div class="card-body">
                    <div class="row g-3">
                      <div class="col-md-6">
                        <label class="form-label">{{ __('common.general.client_id') }}</label>
                        <input type="text" class="form-control" data-env="GITHUB_CLIENT_ID"
                          value="{{ config('services.github.client_id') }}"
                          placeholder="{{ __('common.general.enter_github_client_id') }}">
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">{{ __('common.form.client_secret') }}</label>
                        <div class="input-group">
                          <input type="text" class="form-control env-input" data-env="GITHUB_CLIENT_SECRET"
                            value="{{ config('services.github.client_secret') }}"
                            placeholder="{{ __('common.form.enter_github_client_secret') }}">
                          <button class="btn btn-outline-secondary toggle-password" type="button">
                            <i class="fas fa-eye"></i>
                          </button>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <label class="form-label">{{ __('common.form.redirect_uri') }}</label>
                        <input type="text" class="form-control" value="{{ url('/auth/github/callback') }}"
                          readonly>
                        <small class="text-muted">Add this URL to your GitHub OAuth App callback URL</small>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Twitter/X OAuth -->
                <div class="card mb-3 border">
                  <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                    <span><i class="fab fa-x-twitter text-dark me-2"></i>X.com (Twitter) OAuth</span>
                    @if (config('services.twitter.client_id'))
                      <span class="badge bg-success badge-setting"><i class="fas fa-check me-1"></i>Configured</span>
                    @else
                      <span class="badge bg-secondary badge-setting"><i class="fas fa-times me-1"></i>Not Configured</span>
                    @endif
                  </div>
                  <div class="card-body">
                    <div class="row g-3">
                      <div class="col-md-6">
                        <label class="form-label">{{ __('common.general.client_id') }}</label>
                        <input type="text" class="form-control" data-env="TWITTER_CLIENT_ID"
                          value="{{ config('services.twitter.client_id') }}"
                          placeholder="{{ __('common.general.enter_twitter_client_id') }}">
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">{{ __('common.form.client_secret') }}</label>
                        <div class="input-group">
                          <input type="text" class="form-control env-input" data-env="TWITTER_CLIENT_SECRET"
                            value="{{ config('services.twitter.client_secret') }}"
                            placeholder="{{ __('common.form.enter_twitter_client_secret') }}">
                          <button class="btn btn-outline-secondary toggle-password" type="button">
                            <i class="fas fa-eye"></i>
                          </button>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <label class="form-label">{{ __('common.form.redirect_uri') }}</label>
                        <input type="text" class="form-control" value="{{ url('/auth/twitter/callback') }}"
                          readonly>
                        <small class="text-muted">Add this URL to your Twitter Developer Portal callback URL</small>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Telegram OAuth -->
                <div class="card mb-3 border">
                  <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                    <span><i class="fab fa-telegram text-info me-2"></i>Telegram Login</span>
                    @if (config('services.telegram.bot'))
                      <span class="badge bg-success badge-setting"><i class="fas fa-check me-1"></i>Configured</span>
                    @else
                      <span class="badge bg-secondary badge-setting"><i class="fas fa-times me-1"></i>Not Configured</span>
                    @endif
                  </div>
                  <div class="card-body">
                    <div class="row g-3">
                      <div class="col-md-6">
                        <label class="form-label">{{ __('common.general.bot_username') }}</label>
                        <div class="input-group">
                          <span class="input-group-text">@</span>
                          <input type="text" class="form-control" data-env="TELEGRAM_BOT_NAME"
                            value="{{ config('services.telegram.bot') }}"
                            placeholder="{{ __('common.general.yourbotname') }}">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">{{ __('common.pagination.bot_token') }}</label>
                        <div class="input-group">
                          <input type="text" class="form-control env-input" data-env="TELEGRAM_BOT_TOKEN"
                            value="{{ config('services.telegram.client_secret') }}"
                            placeholder="{{ __('common.form.enter_telegram_bot_token') }}">
                          <button class="btn btn-outline-secondary toggle-password" type="button">
                            <i class="fas fa-eye"></i>
                          </button>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <label class="form-label">{{ __('common.form.website_domain') }}</label>
                        <input type="text" class="form-control"
                          value="{{ parse_url(config('app.url'), PHP_URL_HOST) ?: request()->getHost() }}" readonly>
                        <small class="text-muted">Set this domain in @BotFather using /setdomain command</small>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Save OAuth Button -->
                <div class="text-end mt-3">
                  <button type="button" class="btn btn-primary" id="saveAllSettings">
                    <i class="fas fa-save me-2"></i>Save OAuth Credentials
                  </button>
                </div>

                <hr class="my-4">

                <!-- Password Policy -->
                <h6 class="text-muted mb-3"><i class="fas fa-lock me-2"></i>Password Policy</h6>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.auth.require_strong_password') }}</div>
                    <div class="setting-description">Password must contain uppercase, lowercase, numbers, and symbols
                    </div>
                  </div>
                  <div class="form-check form-switch">
                    <input class="form-check-input config-input" type="checkbox" data-config="REQUIRE_STRONG_PASSWORD" checked>
                  </div>
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.auth.password_history_count') }}</div>
                    <div class="setting-description">{{ __('common.auth.prevent_reuse_of_last_n_passwords') }}</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="PASSWORD_HISTORY_COUNT" value="5"
                    min="0" max="24">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.general.allow_password_reset') }}</div>
                    <div class="setting-description">{{ __('common.general.users_can_reset_their_password_via_email') }}</div>
                  </div>
                  <div class="form-check form-switch">
                    <input class="form-check-input config-input" type="checkbox" data-config="ALLOW_PASSWORD_RESET" checked>
                  </div>
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">Password Reset Link Expiry (hours)</div>
                    <div class="setting-description">{{ __('common.general.how_long_password_reset_links_remain_valid') }}</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="PASSWORD_RESET_EXPIRY" value="24"
                    min="1" max="72">
                </div>

                <hr class="my-4">

                <!-- Session Settings -->
                <h6 class="text-muted mb-3"><i class="fas fa-clock me-2"></i>Session Management</h6>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.general.single_session_only') }}</div>
                    <div class="setting-description">{{ __('common.general.allow_only_one_active_session_per_user') }}</div>
                  </div>
                  <div class="form-check form-switch">
                    <input class="form-check-input config-input" type="checkbox" data-config="SINGLE_SESSION_ONLY">
                  </div>
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">Remember Me Duration (days)</div>
                    <div class="setting-description">How long '{{ __('common.auth.remember_me') }}' sessions last</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="REMEMBER_ME_DURATION" value="30"
                    min="1" max="365">
                </div>
              </div>
            </div>
          </div>

          <!-- Backup & Restore -->
          <div class="tab-pane fade" id="backup">
            <div class="card settings-card">
              <div class="card-header">
                <i class="fas fa-cog"></i>
                <h5 class="mb-0">Backup & Restore</h5>
              </div>
              <div class="card-body">
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.general.auto_backup') }}</div>
                    <div class="setting-description">{{ __('common.general.enable_automatic_database_backups') }}</div>
                  </div>
                  <div class="form-check form-switch">
                    <input class="form-check-input config-input" type="checkbox" data-config="AUTO_BACKUP" checked>
                  </div>
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.general.backup_frequency') }}</div>
                    <div class="setting-description">{{ __('common.general.how_often_to_create_backups') }}</div>
                  </div>
                  <select class="form-control setting-input config-input" data-config="BACKUP_FREQUENCY">
                    <option value="H">{{ __('common.general.hourly') }}</option>
                    <option value="D" selected>{{ __('common.general.daily') }}</option>
                    <option value="W">{{ __('common.general.weekly') }}</option>
                  </select>
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">Backup Retention (days)</div>
                    <div class="setting-description">{{ __('common.general.how_long_to_keep_backup_files') }}</div>
                  </div>
                  <input type="number" class="form-control setting-input config-input" data-config="BACKUP_RETENTION_DAYS" value="30"
                    min="7" max="365">
                </div>
                <div class="setting-item">
                  <div>
                    <div class="setting-label">{{ __('common.general.last_backup') }}</div>
                    <div class="setting-description">{{ __('common.general.most_recent_backup_timestamp') }}</div>
                  </div>
                  <span class="setting-value" id="lastBackupTime">{{ __('common.general.never') }}</span>
                </div>
                <div class="mt-4 d-flex gap-3">
                  <button type="button" class="btn btn-success" id="backupNow">
                    <i class="fas fa-download me-2"></i>Backup Now
                  </button>
                  <button type="button" class="btn btn-warning" id="restoreBackup">
                    <i class="fas fa-upload me-2"></i>Restore Backup
                  </button>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    $(document).ready(function() {
      // Load existing config values
      loadConfigs();

      // Save all settings
      $('#saveAllSettings').click(function() {
        const settings = [];

        $('.config-input').each(function() {
          const configName = $(this).data('config');
          let configValue;

          if ($(this).attr('type') === 'checkbox') {
            configValue = $(this).is(':checked') ? '1' : '0';
          } else {
            configValue = $(this).val();
          }

          settings.push({
            name: configName,
            value: configValue
          });
        });

        $.ajax({
          url: '{{ route('dashboard') }}',
          type: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
            settings: settings
          },
          success: function(response) {
            if (response.success) {
              toastr.success(response.message);
            } else {
              toastr.error(response.message);
            }
          },
          error: function(xhr) {
            toastr.error('{{ __('common.general.failed_to_save_settings') }}');
          }
        });
      });

      // Backup now
      $('#backupNow').click(function() {
        Swal.fire({
          title: '{{ __('common.general.create_backup') }}',
          text: '{{ __('common.general.this_will_create_a_new_database_backup') }}',
          icon: '{{ __('common.general.question') }}',
          showCancelButton: true,
          confirmButtonText: '{{ __('common.general.yes_backup_now') }}'
        }).then((result) => {
          if (result.isConfirmed) {
            toastr.info('{{ __('common.general.backup_started') }}');
            // Implement backup API call
          }
        });
      });

      // Restore backup
      $('#restoreBackup').click(function() {
        Swal.fire({
          title: '{{ __('common.general.restore_backup') }}',
          text: 'This will replace current data with backup. This action cannot be undone!',
          icon: '{{ __('common.messages.warning') }}',
          showCancelButton: true,
          confirmButtonText: '{{ __('common.general.yes_restore') }}'
        }).then((result) => {
          if (result.isConfirmed) {
            // Implement restore functionality
          }
        });
      });

      function loadConfigs() {
        $.ajax({
          url: '{{ route('dashboard') }}',
          type: 'GET',
          success: function(response) {
            if (response.success && response.data) {
              response.data.forEach(function(config) {
                const input = $(`.config-input[data-config="${config.config_name}"]`);
                if (input.length) {
                  if (input.attr('type') === 'checkbox') {
                    input.prop('checked', config.config_value === '1');
                  } else {
                    input.val(config.config_value);
                  }
                }
              });
            }
          }
        });
      }

      // Toggle password visibility
      $(document).on('click', '.toggle-password', function() {
        const input = $(this).closest('.input-group').find('input');
        const icon = $(this).find('i');

        if (input.attr('type') === 'password') {
          input.attr('type', 'text');
          icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
          input.attr('type', 'password');
          icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
      });

      // Save OAuth Credentials
      $('#saveOAuthCredentials').click(function() {
        const btn = $(this);
        const credentials = {};

        $('.env-input').each(function() {
          const envKey = $(this).data('env');
          const envValue = $(this).val();
          if (envKey) {
            credentials[envKey] = envValue;
          }
        });

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Saving...');

        $.ajax({
          url: '{{ route('dashboard') }}',
          type: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
            credentials: credentials
          },
          success: function(response) {
            if (response.success) {
              toastr.success(response.message);
              // Reload page to reflect new status
              setTimeout(() => location.reload(), 1500);
            } else {
              toastr.error(response.message);
            }
          },
          error: function(xhr) {
            const msg = xhr.responseJSON?.message ||
              '{{ __('common.general.failed_to_save_oauth_credentials') }}';
            toastr.error(msg);
          },
          complete: function() {
            btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i>Save OAuth Credentials');
          }
        });
      });
    });
  </script>
@endpush
