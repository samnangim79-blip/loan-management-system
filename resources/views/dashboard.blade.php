@extends('admin.layouts.admin_layout')

@section('pageTitle', __('common.nav.dashboard'))

@section('breadcrumb')
  <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
  <div class="row">
    <!-- Statistics Cards -->
    <div class="col-lg-3 col-6">
      <div class="card bg-primary">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <h6 class="text-white-50">{{ __('common.general.total_customers') }}</h6>
              <h3 class="mb-0 text-white">{{ number_format($statistics['total_customers']) }}</h3>
            </div>
            <div class="fs-1 text-white-50">
              <i class="fas fa-users"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-6">
      <div class="card bg-success">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <h6 class="text-white-50">{{ __('common.general.active_loans') }}</h6>
              <h3 class="mb-0 text-white">{{ number_format($statistics['active_loans']) }}</h3>
            </div>
            <div class="fs-1 text-white-50">
              <i class="fas fa-chart-line"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-6">
      <div class="card bg-info">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <h6 class="text-white-50">{{ __('common.pagination.total_disbursed') }}</h6>
              <h3 class="mb-0 text-white">${{ number_format($statistics['total_disbursed'], 2) }}</h3>
            </div>
            <div class="fs-1 text-white-50">
              <i class="fas fa-dollar-sign"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-6">
      <div class="card bg-warning">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <h6 class="text-white-50">{{ __('common.general.outstanding') }}</h6>
              <h3 class="mb-0 text-white">${{ number_format($statistics['outstanding_balance'], 2) }}</h3>
            </div>
            <div class="fs-1 text-white-50">
              <i class="fas fa-exclamation-circle"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mt-4">
    <!-- Loans Due Today -->
    <div class="col-lg-6">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">{{ __('common.general.loans_due_today') }}</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>{{ __('common.general.contract_no') }}</th>
                  <th>{{ __('common.pagination.customer') }}</th>
                  <th>{{ __('common.general.amount_due') }}</th>
                  <th>{{ __('common.general.action') }}</th>
                </tr>
              </thead>
              <tbody>
                @forelse($loansDueToday as $loan)
                  <tr>
                    <td>{{ $loan->contract_no }}</td>
                    <td>{{ $loan->account->customer->name_en ?? 'N/A' }}</td>
                    <td>${{ number_format($loan->os_balance, 2) }}</td>
                    <td>
                      <button class="btn btn-sm btn-primary" data-id="{{ $loan->loan_schedule_id }}">
                        <i class="fas fa-eye"></i>
                      </button>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="text-center">{{ __('common.general.no_loans_due_today') }}</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Transactions -->
    <div class="col-lg-6">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">{{ __('common.general.recent_transactions') }}</h5>
        </div>
        <div class="card-body">
          <div class="table table-striped">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>{{ __('common.general.date') }}</th>
                  <th>{{ __('common.general.description') }}</th>
                  <th>{{ __('common.general.amount') }}</th>
                  <th>{{ __('common.general.status') }}</th>
                </tr>
              </thead>
              <tbody>
                @forelse($recentTransactions as $transaction)
                  <tr>
                    <td>{{ $transaction->tran_date->format('Y-m-d') }}</td>
                    <td>{{ Str::limit($transaction->description, 30) }}</td>
                    <td>${{ number_format($transaction->amount, 2) }}</td>
                    <td>
                      @if ($transaction->approved_by)
                        <span class="badge bg-success">{{ __('common.general.approved') }}</span>
                      @else
                        <span class="badge bg-warning">{{ __('common.general.pending') }}</span>
                      @endif
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="text-center">{{ __('common.general.no_recent_transactions') }}</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="row mt-4">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">{{ __('common.general.quick_actions') }}</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-3 mb-2">
              <a href="{{ route('dashboard') }}" class="btn btn-primary w-100">
                <i class=""></i> New Customer
              </a>
            </div>
            <div class="col-md-3 mb-2">
              <a href="{{ route('dashboard') }}" class="btn btn-success w-100">
                <i class=""></i> New Loan
              </a>
            </div>
            <div class="col-md-3 mb-2">
              <a href="{{ route('dashboard') }}" class="btn btn-info w-100">
                <i class=""></i> New Account
              </a>
            </div>
            <div class="col-md-3 mb-2">
              <a href="{{ route('reports.index') }}" class="btn btn-warning w-100">
                <i class=""></i> Reports
              </a>
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
      // Quick payment button
      $('.payment-quick').on('submit', function() {
        var loanId = $(this).data('id');
        window.location.href = `/loans/${loanId}`;
      });

      // Auto-refresh dashboard every 60 seconds
      setInterval(function() {
        location.reload();
      }, 60000);

      // Initialize tooltips
      $('[data-bs-toggle="{{ __('common.pagination.tooltip') }}"]').tooltip();
    });
  </script>
@endpush
