@extends('admin.layouts.admin_layout')

@section('pageTitle', __('reports.customer_reports'))

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
  <li class="breadcrumb-item active">Customers</li>
@endsection

@section('content')
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ __('common.pagination.customer_report') }}</h3>
    </div>
    <div class="card-body">
      <div class="row mb-4">
        <div class="col-md-3">
          <div class="">
            <span class=""><i class=""></i></span>
            <div class="">
              <span class="">{{ __('common.nav.total_customers') }}</span>
              <span class="">{{ $totalCustomers ?? 0 }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="">
            <span class=""><i class=""></i></span>
            <div class="">
              <span class="">{{ __('common.nav.active_accounts') }}</span>
              <span class="">{{ $activeAccounts ?? 0 }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="">
            <span class=""><i class=""></i></span>
            <div class="">
              <span class="">{{ __('common.nav.with_active_loans') }}</span>
              <span class="">{{ $withActiveLoans ?? 0 }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="">
            <span class=""><i class=""></i></span>
            <div class="">
              <span class="">{{ __('common.general.new_this_month') }}</span>
              <span class="">{{ $newThisMonth ?? 0 }}</span>
            </div>
          </div>
        </div>
      </div>

      @if (isset($customers))
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>{{ __('common.general.customer_id') }}</th>
              <th>{{ __('common.general.name') }}</th>
              <th>{{ __('common.general.phone') }}</th>
              <th>{{ __('common.general.location') }}</th>
              <th>{{ __('common.nav.accounts') }}</th>
              <th>{{ __('common.nav.active_loans') }}</th>
              <th>{{ __('common.general.created_date') }}</th>
            </tr>
          </thead>
          <tbody>
            @forelse($customers as $customer)
              <tr>
                <td>{{ $customer->cust_id }}</td>
                <td>{{ $customer->name_en }}</td>
                <td>{{ $customer->phone1 }}</td>
                <td>{{ $customer->village->commune->district->province->province ?? 'N/A' }}</td>
                <td>{{ $customer->accounts_count ?? 0 }}</td>
                <td>{{ $customer->active_loans_count ?? 0 }}</td>
                <td>{{ $customer->created_date?->format('{{ __('common.general.ymd') }}') }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="">{{ __('common.general.no_customers_found') }}</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      @endif
    </div>
  </div>
@endsection
