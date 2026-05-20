@extends('admin.layouts.admin_layout')

@section('title', __('common.loan_management.statistics'))

@section('content')
  <div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 mb-0 text-gray-800">{{ __('common.loan_management.statistics') }}</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('common.nav.dashboard') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('loans.index') }}">{{ __('common.nav.loans') }}</a></li>
            <li class="breadcrumb-item active">{{ __('common.loan_management.statistics') }}</li>
          </ol>
        </nav>
      </div>
      <div>
        <a href="{{ route('loans.index') }}" class="btn btn-secondary">
          <i class="fas fa-arrow-left me-1"></i> {{ __('common.loan_management.back_to_loans') }}
        </a>
      </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
      <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-primary shadow h-100">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                  {{ __('common.loan_management.total_loans') }}</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalLoans) }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-success shadow h-100">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                  {{ __('common.loan_management.active_loans') }}</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($activeLoans) }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-clock fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-info shadow h-100">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                  {{ __('common.loan_management.approved_loans') }}</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($completedLoans) }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-warning shadow h-100">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                  {{ __('common.loan_management.collection_rate') }}</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($collectionRate, 1) }}%</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-percentage fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Financial Summary Cards -->
    <div class="row mb-4">
      <div class="col-xl-4 col-md-6 mb-3">
        <div class="card shadow h-100">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-uppercase mb-1">
                  {{ __('common.loan_management.total_disbursed') }}</div>
                <div class="h4 mb-0 font-weight-bold text-primary">${{ number_format($totalDisbursed, 2) }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-4 col-md-6 mb-3">
        <div class="card shadow h-100">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-uppercase mb-1">
                  {{ __('common.loan_management.outstanding_balance') }}</div>
                <div class="h4 mb-0 font-weight-bold text-danger">${{ number_format($totalOutstanding, 2) }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-balance-scale fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-4 col-md-6 mb-3">
        <div class="card shadow h-100">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-uppercase mb-1">
                  {{ __('common.loan_management.total_collected') }}</div>
                <div class="h4 mb-0 font-weight-bold text-success">${{ number_format($totalCollected, 2) }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-coins fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Average Metrics -->
    <div class="row mb-4">
      <div class="col-xl-4 col-md-6 mb-3">
        <div class="card bg-gradient-primary text-white shadow h-100">
          <div class="card-body">
            <div class="text-white-50 small text-uppercase mb-1">{{ __('common.loan_management.avg_loan_amount') }}</div>
            <div class="h4 mb-0">${{ number_format($avgLoanAmount, 2) }}</div>
          </div>
        </div>
      </div>

      <div class="col-xl-4 col-md-6 mb-3">
        <div class="card bg-gradient-success text-white shadow h-100">
          <div class="card-body">
            <div class="text-white-50 small text-uppercase mb-1">{{ __('common.loan_management.avg_interest_rate') }}
            </div>
            <div class="h4 mb-0">{{ number_format($avgInterestRate, 2) }}%</div>
          </div>
        </div>
      </div>

      <div class="col-xl-4 col-md-6 mb-3">
        <div class="card bg-gradient-info text-white shadow h-100">
          <div class="card-body">
            <div class="text-white-50 small text-uppercase mb-1">{{ __('common.loan_management.avg_tenor') }}</div>
            <div class="h4 mb-0">{{ number_format($avgTenor, 1) }} {{ __('common.general.months') }}</div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- Loans by Purpose -->
      <div class="col-xl-6 mb-4">
        <div class="card shadow h-100">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
              <i class="fas fa-chart-pie me-2"></i>{{ __('common.loan_management.loans_by_purpose') }}
            </h6>
          </div>
          <div class="card-body">
            @if ($loansByPurpose->count() > 0)
              <div class="table-responsive">
                <table class="table table-bordered table-sm">
                  <thead class="table-light">
                    <tr>
                      <th>{{ __('common.loan_management.purpose') }}</th>
                      <th class="text-center">{{ __('common.general.count') }}</th>
                      <th class="text-end">{{ __('common.general.total_amount') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($loansByPurpose as $item)
                      <tr>
                        <td>{{ $item['purpose'] }}</td>
                        <td class="text-center">{{ number_format($item['count']) }}</td>
                        <td class="text-end">${{ number_format($item['total_amount'], 2) }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <p class="text-muted text-center mb-0">{{ __('common.general.no_data') }}</p>
            @endif
          </div>
        </div>
      </div>

      <!-- Loans by Frequency -->
      <div class="col-xl-6 mb-4">
        <div class="card shadow h-100">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
              <i class="fas fa-calendar-alt me-2"></i>{{ __('common.loan_management.loans_by_frequency') }}
            </h6>
          </div>
          <div class="card-body">
            @if ($loansByFrequency->count() > 0)
              <div class="table-responsive">
                <table class="table table-bordered table-sm">
                  <thead class="table-light">
                    <tr>
                      <th>{{ __('common.general.frequency') }}</th>
                      <th class="text-center">{{ __('common.general.count') }}</th>
                      <th class="text-end">{{ __('common.general.total_amount') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($loansByFrequency as $item)
                      <tr>
                        <td>{{ $item['frequency'] }}</td>
                        <td class="text-center">{{ number_format($item['count']) }}</td>
                        <td class="text-end">${{ number_format($item['total_amount'], 2) }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <p class="text-muted text-center mb-0">{{ __('common.general.no_data') }}</p>
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- Monthly Disbursements -->
    <div class="row">
      <div class="col-12 mb-4">
        <div class="card shadow">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
              <i class="fas fa-chart-line me-2"></i>{{ __('common.loan_management.monthly_disbursements') }}
            </h6>
          </div>
          <div class="card-body">
            @if ($monthlyDisbursements->count() > 0)
              <div class="table-responsive">
                <table class="table table-bordered table-sm">
                  <thead class="table-light">
                    <tr>
                      <th>{{ __('common.general.month') }}</th>
                      <th class="text-center">{{ __('common.loan_management.number_of_loans') }}</th>
                      <th class="text-end">{{ __('common.loan_management.total_disbursed') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($monthlyDisbursements as $item)
                      <tr>
                        <td>{{ \Carbon\Carbon::parse($item->month . '-01')->format('F Y') }}</td>
                        <td class="text-center">{{ number_format($item->count) }}</td>
                        <td class="text-end">${{ number_format($item->total_amount, 2) }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <p class="text-muted text-center mb-0">{{ __('common.loan_management.no_disbursement_data') }}</p>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('styles')
  <style>
    .border-left-primary {
      border-left: 4px solid #4e73df !important;
    }

    .border-left-success {
      border-left: 4px solid #1cc88a !important;
    }

    .border-left-info {
      border-left: 4px solid #36b9cc !important;
    }

    .border-left-warning {
      border-left: 4px solid #f6c23e !important;
    }

    .bg-gradient-primary {
      background: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
    }

    .bg-gradient-success {
      background: linear-gradient(180deg, #1cc88a 10%, #13855c 100%);
    }

    .bg-gradient-info {
      background: linear-gradient(180deg, #36b9cc 10%, #258391 100%);
    }
  </style>
@endpush
