@extends('admin.layouts.admin_layout')

@section('content')
  <div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
      <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h1 class="h3 mb-0 text-gray-800">{{ __('common.general.collateral_summary') }}</h1>
            <p class="text-muted">{{ __('common.general.collateral_summary_description') }}</p>
          </div>
          <div>
            <a href="{{ route('collaterals.index') }}" class="btn btn-primary">
              <i class="fas fa-list me-1"></i> {{ __('common.general.view_all_collaterals') }}
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                  {{ __('common.general.total_collaterals') }}
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($data['total_collaterals']) }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-shield-alt fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                  {{ __('common.general.total_value') }}
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                  ${{ number_format($data['total_value'], 2) }}
                </div>
              </div>
              <div class="col-auto">
                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                  {{ __('common.general.active_collaterals') }}
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($data['status_stats']['active']) }}
                </div>
              </div>
              <div class="col-auto">
                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                  {{ __('common.general.released_collaterals') }}
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                  {{ number_format($data['status_stats']['released']) }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-unlock fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts and Tables Row -->
    <div class="row">
      <!-- Collaterals by Type -->
      <div class="col-xl-6 col-lg-6">
        <div class="card shadow mb-4">
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('common.general.collaterals_by_type') }}</h6>
          </div>
          <div class="card-body">
            @if ($data['collaterals_by_type']->count() > 0)
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>{{ __('common.general.type') }}</th>
                      <th>{{ __('common.general.count') }}</th>
                      <th>{{ __('common.general.total_value') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($data['collaterals_by_type'] as $typeData)
                      <tr>
                        <td>{{ $typeData['type'] }}</td>
                        <td>{{ number_format($typeData['count']) }}</td>
                        <td>${{ number_format($typeData['total_value'], 2) }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <div class="text-center py-4">
                <i class="fas fa-shield-alt fa-3x text-gray-300 mb-3"></i>
                <p class="text-muted">{{ __('common.general.no_collateral_data') }}</p>
              </div>
            @endif
          </div>
        </div>
      </div>

      <!-- Recent Collaterals -->
      <div class="col-xl-6 col-lg-6">
        <div class="card shadow mb-4">
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('common.general.recent_collaterals') }}</h6>
            <a href="{{ route('collaterals.index') }}" class="btn btn-sm btn-primary">
              {{ __('common.general.view_all') }}
            </a>
          </div>
          <div class="card-body">
            @if ($data['recent_collaterals']->count() > 0)
              <div class="table-responsive">
                <table class="table table-sm">
                  <thead>
                    <tr>
                      <th>{{ __('common.general.customer') }}</th>
                      <th>{{ __('common.general.type') }}</th>
                      <th>{{ __('common.general.value') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($data['recent_collaterals'] as $collateral)
                      <tr>
                        <td>
                          <div class="font-weight-bold">
                            {{ $collateral->loanSchedule->account->customer->name_en ?? 'N/A' }}
                          </div>
                          <small class="text-muted">{{ $collateral->loanSchedule->contract_no ?? 'N/A' }}</small>
                        </td>
                        <td>{{ $collateral->collateralType->collateral_type ?? 'N/A' }}</td>
                        <td class="font-weight-bold">${{ number_format($collateral->collateral_value, 2) }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <div class="text-center py-4">
                <i class="fas fa-shield-alt fa-3x text-gray-300 mb-3"></i>
                <p class="text-muted">{{ __('common.general.no_recent_collaterals') }}</p>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- Status Distribution Chart -->
    <div class="row">
      <div class="col-xl-12">
        <div class="card shadow mb-4">
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('common.general.collateral_status_distribution') }}</h6>
          </div>
          <div class="card-body">
            <div class="chart-container" style="height: 300px;">
              <canvas id="statusChart"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    $(document).ready(function() {
      // Status Distribution Chart
      const statusCtx = document.getElementById('statusChart').getContext('2d');
      const statusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
          labels: ['{{ __('common.general.active') }}', '{{ __('common.general.released') }}'],
          datasets: [{
            data: [{{ $data['status_stats']['active'] }}, {{ $data['status_stats']['released'] }}],
            backgroundColor: [
              '#36b9cc',
              '#f6c23e'
            ],
            borderWidth: 2
          }]
        },
        options: {
          maintainAspectRatio: false,
          tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            caretPadding: 10,
          },
          legend: {
            display: true,
            position: 'bottom'
          },
          cutoutPercentage: 80,
        },
      });
    });
  </script>
@endpush
