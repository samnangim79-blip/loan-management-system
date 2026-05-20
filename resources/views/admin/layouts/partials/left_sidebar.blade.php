<aside id="pt-sidebar" class="pt-sidebar">

  <!-- Toggle Button -->
  <div class="pt-toggle-btn-wrapper">
    <button id="collapse-btn" class="pt-sidebar-toggle-btn">
      <i class="fa-solid fa-chevron-left"></i>
    </button>
  </div>

  <!-- Logo -->
  <div class="p-3 border-bottom border-secondary pt-logo-container d-flex align-items-center">
    <div class="d-flex align-items-center">
      <div class="bg-primary rounded d-flex align-items-center justify-content-center shrink-0"
        style="width: 32px; height: 32px;">
        <i class="fas fa-landmark text-white"></i>
      </div>
      <span class="ms-3 text-white h5 mb-0 pt-logo-text fw-semibold">LoanMS</span>
    </div>
  </div>

  <!-- Navigation Menu -->
  <div class="flex-fill overflow-auto">
    <nav class="py-3">
      <ul class="pt-accordionmenu list-unstyled" id="menu">

        <!-- Dashboard - Available to all authenticated users -->
        <li class="{{ request()->is('dashboard') ? 'pt-active' : '' }}">
          <a href="{{ route('dashboard') }}" class="pt-single-link">
            <i class="fas fa-th-large pt-menu-icon"></i>
            <span class="pt-sidebar-text">{{ __('common.nav.dashboard') }}</span>
          </a>
        </li>

        <!-- Customers -->
        @if (user_has_any_permission(['customer_view', 'customer_add', 'customer_edit', 'customer_delete']) ||
                user_is_super_admin())
          <li class="{{ request()->is('customers*') ? 'pt-active' : '' }}">
            <a href="#" class="pt-has-arrow" aria-expanded="{{ request()->is('customers*') ? 'true' : 'false' }}">
              <i class="fas fa-users pt-menu-icon"></i>
              <span class="pt-sidebar-text">{{ __('common.nav.customers') }}</span>
              <i class="fas fa-chevron-down pt-chevron-icon"></i>
            </a>
            <ul class="{{ request()->is('customers*') ? 'pt-show' : '' }}">
              @if (user_has_permission('customer_view') || user_is_super_admin())
                <li><a href="{{ route('customers.index') }}"><span
                      class="pt-sidebar-text">{{ __('common.nav.all_customers') }}</span></a></li>
              @endif
              @if (user_has_permission('customer_add') || user_is_super_admin())
                <li><a href="{{ route('customers.create') }}"><span
                      class="pt-sidebar-text">{{ __('common.nav.add_customer') }}</span></a></li>
              @endif
            </ul>
          </li>
        @endif

        <!-- Accounts -->
        @if (user_has_any_permission(['account_view', 'account_add', 'account_edit', 'account_delete']) || user_is_super_admin())
          <li class="{{ request()->is('accounts*') ? 'pt-active' : '' }}">
            <a href="#" class="pt-has-arrow" aria-expanded="{{ request()->is('accounts*') ? 'true' : 'false' }}">
              <i class="fas fa-wallet pt-menu-icon"></i>
              <span class="pt-sidebar-text">{{ __('common.nav.accounts') }}</span>
              <i class="fas fa-chevron-down pt-chevron-icon"></i>
            </a>
            <ul class="{{ request()->is('accounts*') ? 'pt-show' : '' }}">
              @if (user_has_permission('account_view') || user_is_super_admin())
                <li><a href="{{ route('accounts.index') }}"><span
                      class="pt-sidebar-text">{{ __('common.nav.all_accounts') }}</span></a></li>
              @endif
              @if (user_has_permission('account_add') || user_is_super_admin())
                <li><a href="{{ route('accounts.create') }}"><span
                      class="pt-sidebar-text">{{ __('common.nav.open_account') }}</span></a></li>
              @endif
            </ul>
          </li>
        @endif

        <!-- Loans -->
        @if (user_has_any_permission(['loan_view', 'loan_add', 'loan_edit', 'loan_approve', 'loan_disburse']) ||
                user_is_super_admin())
          <li class="{{ request()->is('loans*') ? 'pt-active' : '' }}">
            <a href="#" class="pt-has-arrow" aria-expanded="{{ request()->is('loans*') ? 'true' : 'false' }}">
              <i class="fas fa-hand-holding-usd pt-menu-icon"></i>
              <span class="pt-sidebar-text">{{ __('common.nav.loans') }}</span>
              <i class="fas fa-chevron-down pt-chevron-icon"></i>
            </a>
            <ul class="{{ request()->is('loans*') ? 'pt-show' : '' }}">
              @if (user_has_permission('loan_view') || user_is_super_admin())
                <li><a href="{{ route('loans.index') }}"><span
                      class="pt-sidebar-text">{{ __('common.nav.all_loans') }}</span></a></li>
                <li><a href="{{ route('loans.statistics') }}"><span
                      class="pt-sidebar-text">{{ __('common.nav.loan_statistics') }}</span></a>
                </li>
              @endif
              @if (user_has_permission('loan_add') || user_is_super_admin())
                <li><a href="{{ route('loans.create') }}"><span
                      class="pt-sidebar-text">{{ __('common.nav.loan_disbursement') }}</span></a></li>
                <li><a href="{{ route('loans.create') }}"><span
                      class="pt-sidebar-text">{{ __('common.nav.group_loan_disbursement') }}</span></a></li>
                <li><a href="{{ route('loans.create') }}"><span
                      class="pt-sidebar-text">{{ __('common.nav.loan_repayment') }}</span></a></li>
                <li><a href="{{ route('loans.create') }}"><span
                      class="pt-sidebar-text">{{ __('common.nav.loan_area') }}</span></a></li>
                <li><a href="{{ route('loans.create') }}"><span
                      class="pt-sidebar-text">{{ __('common.nav.release_collateral') }}</span></a></li>
                <li><a href="{{ route('loans.create') }}"><span
                      class="pt-sidebar-text">{{ __('common.nav.new_loan') }}</span></a></li>
              @endif
            </ul>
          </li>
        @endif

        <!-- Collaterals -->
        @if (user_has_any_permission(['collateral_view', 'collateral_add', 'collateral_edit', 'collateral_delete']) ||
                user_is_super_admin())
          <li class="{{ request()->is('collaterals*') ? 'pt-active' : '' }}">
            <a href="#" class="pt-has-arrow"
              aria-expanded="{{ request()->is('collaterals*') ? 'true' : 'false' }}">
              <i class="fas fa-shield-alt pt-menu-icon"></i>
              <span class="pt-sidebar-text">{{ __('common.nav.collaterals') }}</span>
              <i class="fas fa-chevron-down pt-chevron-icon"></i>
            </a>
            <ul class="{{ request()->is('collaterals*') ? 'pt-show' : '' }}">
              @if (user_has_permission('collateral_view') || user_is_super_admin())
                <li><a href="{{ route('collaterals.index') }}"><span
                      class="pt-sidebar-text">{{ __('common.nav.all_collaterals') }}</span></a>
                </li>
                <li><a href="{{ route('collaterals.summary') }}"><span
                      class="pt-sidebar-text">{{ __('common.nav.summary') }}</span></a></li>
              @endif
            </ul>
          </li>
        @endif

        <!-- Transactions -->
        @if (user_has_any_permission(['transaction_view', 'deposit', 'withdrawal', 'loan_payment', 'transfer']) ||
                user_is_super_admin())
          <li class="{{ request()->is('transactions*') || request()->is('cash*') ? 'pt-active' : '' }}">
            <a href="#" class="pt-has-arrow"
              aria-expanded="{{ request()->is('transactions*') || request()->is('cash*') ? 'true' : 'false' }}">
              <i class="fas fa-exchange-alt pt-menu-icon"></i>
              <span class="pt-sidebar-text">{{ __('common.nav.transactions') }}</span>
              <i class="fas fa-chevron-down pt-chevron-icon"></i>
            </a>
            <ul class="{{ request()->is('transactions*') || request()->is('cash*') ? 'pt-show' : '' }}">
              @if (user_has_permission('transaction_view') || user_is_super_admin())
                <li><a href="{{ route('transactions.index') }}"><span
                      class="pt-sidebar-text">{{ __('common.nav.all_transactions') }}</span></a></li>
              @endif
              @if (user_has_any_permission(['deposit', 'withdrawal']) || user_is_super_admin())
                <li><a href="{{ route('cash.index') }}"><span
                      class="pt-sidebar-text">{{ __('common.nav.cash_management') }}</span></a></li>
                <li><a href="{{ route('cash.transfers') }}"><span
                      class="pt-sidebar-text">{{ __('common.nav.cash_transfers') }}</span></a>
                </li>
              @endif
            </ul>
          </li>
        @endif

        <!-- Fixed Deposits -->
        @if (user_has_any_permission(['fixed_deposit_view', 'fixed_deposit_add', 'fixed_deposit_edit']) || user_is_super_admin())
          <li class="{{ request()->is('fixed-deposits*') ? 'pt-active' : '' }}">
            <a href="#" class="pt-has-arrow"
              aria-expanded="{{ request()->is('fixed-deposits*') ? 'true' : 'false' }}">
              <i class="fas fa-piggy-bank pt-menu-icon"></i>
              <span class="pt-sidebar-text">{{ __('common.nav.fixed_deposits') }}</span>
              <i class="fas fa-chevron-down pt-chevron-icon"></i>
            </a>
            <ul class="{{ request()->is('fixed-deposits*') ? 'pt-show' : '' }}">
              @if (user_has_permission('fixed_deposit_view') || user_is_super_admin())
                <li><a href="{{ route('fixed-deposits.index') }}"><span
                      class="pt-sidebar-text">{{ __('common.nav.all_fd_accounts') }}</span></a></li>
                <li><a href="{{ route('fixed-deposits.terms') }}"><span
                      class="pt-sidebar-text">{{ __('common.nav.fd_terms') }}</span></a>
                </li>
                <li><a href="{{ route('fixed-deposits.options') }}"><span
                      class="pt-sidebar-text">{{ __('common.nav.fd_options') }}</span></a>
                </li>
              @endif
            </ul>
          </li>
        @endif

        <!-- Cheques & Passbooks -->
        @if (user_has_any_permission(['transaction_view', 'deposit', 'withdrawal']) || user_is_super_admin())
          <li class="{{ request()->is('cheques*') || request()->is('passbooks*') ? 'pt-active' : '' }}">
            <a href="#" class="pt-has-arrow"
              aria-expanded="{{ request()->is('cheques*') || request()->is('passbooks*') ? 'true' : 'false' }}">
              <i class="fas fa-money-check pt-menu-icon"></i>
              <span class="pt-sidebar-text">{{ __('common.nav.cheques_passbooks') }}</span>
              <i class="fas fa-chevron-down pt-chevron-icon"></i>
            </a>
            <ul class="{{ request()->is('cheques*') || request()->is('passbooks*') ? 'pt-show' : '' }}">
              <li><a href="{{ route('cheques.index') }}"><span
                    class="pt-sidebar-text">{{ __('common.nav.cheque_issues') }}</span></a></li>
              <li><a href="{{ route('cheques.clearing') }}"><span
                    class="pt-sidebar-text">{{ __('common.nav.cheque_clearing') }}</span></a>
              </li>
              <li><a href="{{ route('cheques.stops') }}"><span
                    class="pt-sidebar-text">{{ __('common.nav.stop_payments') }}</span></a></li>
              <li><a href="{{ route('cheques.maintenance') }}"><span
                    class="pt-sidebar-text">{{ __('common.nav.cheque_maintenance') }}</span></a></li>
              <li><a href="{{ route('passbooks.index') }}"><span
                    class="pt-sidebar-text">{{ __('common.nav.passbook_issues') }}</span></a>
              </li>
              <li><a href="{{ route('passbooks.list') }}"><span
                    class="pt-sidebar-text">{{ __('common.nav.passbook_list') }}</span></a></li>
              <li><a href="{{ route('passbooks.maintenance') }}"><span
                    class="pt-sidebar-text">{{ __('common.nav.passbook_maintenance') }}</span></a></li>
            </ul>
          </li>
        @endif

        <!-- Loan Groups -->
        @if (user_has_any_permission(['loan_view', 'loan_add']) || user_is_super_admin())
          <li class="{{ request()->is('groups*') ? 'pt-active' : '' }}">
            <a href="{{ route('groups.index') }}" class="pt-single-link">
              <i class="fas fa-people-arrows pt-menu-icon"></i>
              <span class="pt-sidebar-text">{{ __('common.nav.loan_groups') }}</span>
            </a>
          </li>
        @endif

        <!-- Interest -->
        @if (user_has_any_permission(['loan_view', 'account_view', 'reports']) || user_is_super_admin())
          <li class="{{ request()->is('interest*') ? 'pt-active' : '' }}">
            <a href="#" class="pt-has-arrow"
              aria-expanded="{{ request()->is('interest*') ? 'true' : 'false' }}">
              <i class="fas fa-percentage pt-menu-icon"></i>
              <span class="pt-sidebar-text">{{ __('common.nav.interest') }}</span>
              <i class="fas fa-chevron-down pt-chevron-icon"></i>
            </a>
            <ul class="{{ request()->is('interest*') ? 'pt-show' : '' }}">
              <li><a href="{{ route('interest.rates') }}"><span
                    class="pt-sidebar-text">{{ __('common.nav.interest_rates') }}</span></a>
              </li>
              <li><a href="{{ route('interest.accrued') }}"><span
                    class="pt-sidebar-text">{{ __('common.nav.accrued_interest') }}</span></a>
              </li>
            </ul>
          </li>
        @endif

        <!-- Reports -->
        @if (user_has_permission('reports') || user_is_super_admin())
          <li class="{{ request()->is('reports*') ? 'pt-active' : '' }}">
            <a href="#" class="pt-has-arrow"
              aria-expanded="{{ request()->is('reports*') ? 'true' : 'false' }}">
              <i class="fas fa-chart-bar pt-menu-icon"></i>
              <span class="pt-sidebar-text">{{ __('common.nav.reports') }}</span>
              <i class="fas fa-chevron-down pt-chevron-icon"></i>
            </a>
            <ul class="{{ request()->is('reports*') ? 'pt-show' : '' }}">
              <li><a href="{{ route('reports.index') }}"><span
                    class="pt-sidebar-text">{{ __('common.nav.reports_dashboard') }}</span></a>
              </li>
              <li><a href="{{ route('reports.daily') }}"><span
                    class="pt-sidebar-text">{{ __('common.nav.daily_report') }}</span></a></li>
              <li><a href="{{ route('reports.loans') }}"><span
                    class="pt-sidebar-text">{{ __('common.nav.loan_report') }}</span></a></li>
              <li><a href="{{ route('reports.loan-summary') }}"><span
                    class="pt-sidebar-text">{{ __('common.nav.loan_summary') }}</span></a>
              </li>
              <li><a href="{{ route('reports.loan-arrears') }}"><span
                    class="pt-sidebar-text">{{ __('common.nav.loan_arrears') }}</span></a>
              </li>
              <li><a href="{{ route('reports.disbursements') }}"><span
                    class="pt-sidebar-text">{{ __('common.nav.disbursements') }}</span></a></li>
              <li><a href="{{ route('reports.customers') }}"><span
                    class="pt-sidebar-text">{{ __('common.nav.customer_report') }}</span></a>
              </li>
              <li><a href="{{ route('reports.accounts') }}"><span
                    class="pt-sidebar-text">{{ __('common.nav.account_summary') }}</span></a>
              </li>
              <li><a href="{{ route('reports.transactions') }}"><span
                    class="pt-sidebar-text">{{ __('common.nav.transaction_report') }}</span></a></li>
              <li><a href="{{ route('reports.trial-balance') }}"><span
                    class="pt-sidebar-text">{{ __('common.nav.trial_balance') }}</span></a></li>
            </ul>
          </li>
        @endif

        <!-- General Ledger -->
        @if (user_has_any_permission(['gl_view', 'journal_view']) || user_is_super_admin())
          <li class="{{ request()->is('gl*') ? 'pt-active' : '' }}">
            <a href="#" class="pt-has-arrow" aria-expanded="{{ request()->is('gl*') ? 'true' : 'false' }}">
              <i class="fas fa-book pt-menu-icon"></i>
              <span class="pt-sidebar-text">{{ __('common.nav.general_ledger') }}</span>
              <i class="fas fa-chevron-down pt-chevron-icon"></i>
            </a>
            <ul class="{{ request()->is('gl*') ? 'pt-show' : '' }}">
              <li><a href="{{ route('gl.index') }}"><span
                    class="pt-sidebar-text">{{ __('common.nav.chart_of_accounts') }}</span></a></li>
              <li><a href="{{ route('gl.tree') }}"><span
                    class="pt-sidebar-text">{{ __('common.nav.gl_tree_view') }}</span></a></li>
            </ul>
          </li>
        @endif

        <!-- Fixed Assets -->
        @if (user_has_any_permission(['gl_view', 'config']) || user_is_super_admin())
          <li class="{{ request()->is('fixed-assets*') ? 'pt-active' : '' }}">
            <a href="#" class="pt-has-arrow"
              aria-expanded="{{ request()->is('fixed-assets*') ? 'true' : 'false' }}">
              <i class="fas fa-building pt-menu-icon"></i>
              <span class="pt-sidebar-text">{{ __('common.nav.fixed_assets') }}</span>
              <i class="fas fa-chevron-down pt-chevron-icon"></i>
            </a>
            <ul class="{{ request()->is('fixed-assets*') ? 'pt-show' : '' }}">
              <li><a href="{{ route('fixed-assets.index') }}"><span
                    class="pt-sidebar-text">{{ __('common.nav.asset_register') }}</span></a>
              </li>
              <li><a href="{{ route('fixed-assets.types') }}"><span
                    class="pt-sidebar-text">{{ __('common.nav.asset_types') }}</span></a>
              </li>
            </ul>
          </li>
        @endif

        <!-- Administration - Only for users who can manage users -->
        @if (user_can_manage_users() || user_is_super_admin())
          <li
            class="{{ request()->is('users*') || request()->is('staff*') || request()->is('branches*') || request()->is('access-profiles*') ? 'pt-active' : '' }}">
            <a href="#" class="pt-has-arrow"
              aria-expanded="{{ request()->is('users*') || request()->is('staff*') || request()->is('branches*') || request()->is('access-profiles*') ? 'true' : 'false' }}">
              <i class="fas fa-user-shield pt-menu-icon"></i>
              <span class="pt-sidebar-text">{{ __('common.nav.administration') }}</span>
              <i class="fas fa-chevron-down pt-chevron-icon"></i>
            </a>
            <ul
              class="{{ request()->is('users*') || request()->is('staff*') || request()->is('branches*') || request()->is('access-profiles*') ? 'pt-show' : '' }}">
              @if (user_has_permission('user_view') || user_is_super_admin())
                <li><a href="{{ route('users.index') }}"><span
                      class="pt-sidebar-text">{{ __('common.nav.users') }}</span></a></li>
              @endif
              @if (user_has_permission('staff_view') || user_is_super_admin())
                <li><a href="{{ route('staff.index') }}"><span
                      class="pt-sidebar-text">{{ __('common.nav.staff') }}</span></a></li>
              @endif
              @if (user_has_permission('branch_view') || user_is_super_admin())
                <li><a href="{{ route('branches.index') }}"><span
                      class="pt-sidebar-text">{{ __('common.nav.branches') }}</span></a></li>
              @endif
              @if (user_has_permission('access_profiles') || user_is_super_admin())
                <li><a href="{{ route('access-profiles.index') }}"><span
                      class="pt-sidebar-text">{{ __('common.nav.access_profiles') }}</span></a></li>
              @endif
            </ul>
          </li>
        @endif

        <!-- Settings - Only for super admin or users with config permission -->
        @if (user_has_any_permission([
                'config_view',
                'config_edit',
                'currency_view',
                'nationality_view',
                'province_view',
                'holiday_view',
            ]) || user_is_super_admin())
          <li
            class="{{ request()->is('config*') || request()->is('currencies*') || request()->is('nationalities*') || request()->is('locations*') ? 'pt-active' : '' }}">
            <a href="#" class="pt-has-arrow"
              aria-expanded="{{ request()->is('config*') || request()->is('currencies*') || request()->is('nationalities*') || request()->is('locations*') ? 'true' : 'false' }}">
              <i class="fas fa-cog pt-menu-icon"></i>
              <span class="pt-sidebar-text">{{ __('common.nav.settings') }}</span>
              <i class="fas fa-chevron-down pt-chevron-icon"></i>
            </a>
            <ul
              class="{{ request()->is('config*') || request()->is('currencies*') || request()->is('nationalities*') || request()->is('locations*') ? 'pt-show' : '' }}">
              @if (user_has_any_permission(['config_view', 'config_edit']) || user_is_super_admin())
                <li><a href="{{ route('config.index') }}"><span
                      class="pt-sidebar-text">{{ __('common.nav.system_settings') }}</span></a>
                </li>
              @endif
              @if (user_has_permission('holiday_view') || user_is_super_admin())
                <li><a href="{{ route('config.holidays') }}"><span
                      class="pt-sidebar-text">{{ __('common.nav.public_holidays') }}</span></a>
                </li>
                <li><a href="{{ route('config.non-working-days') }}"><span
                      class="pt-sidebar-text">{{ __('common.nav.non_working_days') }}</span></a></li>
              @endif
              @if (user_has_any_permission(['access_profile_view', 'access_profile_edit']) || user_is_super_admin())
                <li><a href="{{ route('config.modules') }}"><span
                      class="pt-sidebar-text">{{ __('common.nav.modules') }}</span></a></li>
              @endif
              @if (user_has_any_permission(['currency_view', 'currency_add']) || user_is_super_admin())
                <li><a href="{{ route('currencies.index') }}"><span
                      class="pt-sidebar-text">{{ __('common.nav.currencies') }}</span></a>
                </li>
              @endif
              @if (user_has_permission('nationality_view') || user_is_super_admin())
                <li><a href="{{ route('nationalities.index') }}"><span
                      class="pt-sidebar-text">{{ __('common.nav.nationalities') }}</span></a></li>
              @endif
              @if (user_has_any_permission(['province_view', 'district_view', 'commune_view', 'village_view']) ||
                      user_is_super_admin())
                <li><a href="{{ route('locations.provinces') }}"><span
                      class="pt-sidebar-text">{{ __('common.nav.locations') }}</span></a>
                </li>
              @endif
            </ul>
          </li>
        @endif

        <!-- Languages Management -->
        @if (user_can_manage_users() || user_is_super_admin())
          <li
            class="{{ request()->is('users*') || request()->is('staff*') || request()->is('branches*') || request()->is('access-profiles*') ? 'pt-active' : '' }}">
            <a href="#" class="pt-has-arrow"
              aria-expanded="{{ request()->is('users*') || request()->is('staff*') || request()->is('branches*') || request()->is('access-profiles*') ? 'true' : 'false' }}">
              <i class="fas fa-user-shield pt-menu-icon"></i>
              <span class="pt-sidebar-text">{{ __('common.nav.languages') }}</span>
              <i class="fas fa-chevron-down pt-chevron-icon"></i>
            </a>
            <ul class="{{ request()->is('languages*') || request()->is('translation-keys*') ? 'pt-show' : '' }}">
              @if (user_has_permission('user_view') || user_is_super_admin())
                <li><a href="{{ route('languages.index') }}"><span
                      class="pt-sidebar-text">{{ __('common.nav.languages') }}</span></a></li>
              @endif
              @if (user_has_permission('staff_view') || user_is_super_admin())
                <li><a href="{{ route('translation-keys.index') }}"><span
                      class="pt-sidebar-text">{{ __('common.nav.languagekeys') }}</span></a></li>
              @endif
            </ul>
          </li>
        @endif

      </ul>
    </nav>
  </div>

  <!-- User Profile Section -->
  <div class="border-top border-secondary p-3">
    <div class="d-flex align-items-center">
      <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center shrink-0"
        style="width: 36px; height: 36px;">
        @if (auth()->user()->avatar ?? false)
          <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="rounded-circle"
            style="width: 36px; height: 36px; object-fit: cover;">
        @else
          <i class="fas fa-user text-white"></i>
        @endif
      </div>
      <div class="ms-3 d-flex flex-column pt-sidebar-user-info">
        <span class="text-white small fw-semibold">{{ auth()->user()->name ?? 'Admin User' }}</span>
        <span class="text-muted small">
          @if (user_is_super_admin())
            <span class="badge bg-danger badge-sm">Super Admin</span>
          @else
            {{ auth()->user()->email ?? 'admin@example.com' }}
          @endif
        </span>
      </div>
      <div class="ms-auto">
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
          @csrf
          <button type="submit" class="btn btn-sm btn-outline-light rounded-circle" title="Logout">
            <i class="fas fa-sign-out-alt"></i>
          </button>
        </form>
      </div>
    </div>
  </div>

</aside>
