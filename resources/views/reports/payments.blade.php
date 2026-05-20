@extends('admin.layouts.admin_layout')

@section('pageTitle', __('reports.payment_reports'))

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
  <li class="breadcrumb-item active">Payments</li>
@endsection

@section('content')
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ __('common.general.payment_report_filters') }}</h3>
    </div>
    <div class="">
      <form id="" method="GET">
        <div class="">
          <div class="col-md-4">
            <div class="mb-3">
              <label for="date_from" class="form-label">{{ __('common.general.date_from') }}</label>
              <input type="date" class="form-control" id="date_from" name="date_from">
            </div>
          </div>
          <div class="col-md-4">
            <div class="mb-3">
              <label for="date_to" class="form-label">{{ __('common.general.date_to') }}</label>
              <input type="date" class="form-control" id="date_to" name="date_to">
            </div>
          </div>
          <div class="col-md-4">
            <div class="mb-3">
              <label for="branch_id" class="form-label">{{ __('common.form.branch') }}</label>
              <select name="branch_id" id="branch_id" class="form-control">
                <option value="">{{ __('common.form.all_branches') }}</option>
              </select>
            </div>
          </div>
        </div>
        <button type="submit" class="btn btn-primary">
          <i class=""></i> Generate Report
        </button>
      </form>
    </div>
  </div>

  @if (isset($payments))
    <div class="card mt-3">
      <div class="card-header">
        <h3 class="card-title">{{ __('common.general.payment_results') }}</h3>
      </div>
      <div class="">
        <div class="row mb-3">
          <div class="col-md-6">
            <div class="">
              <span class=""><i class=""></i></span>
              <div class="">
                <span class="">{{ __('common.pagination.total_payments') }}</span>
                <span class="">{{ $summary['total_payments'] ?? 0 }}</span>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="">
              <span class=""><i class=""></i></span>
              <div class="">
                <span class="">{{ __('common.general.total_amount') }}</span>
                <span class="">${{ number_format($summary['total_amount'] ?? 0, 2) }}</span>
              </div>
            </div>
          </div>
        </div>

        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>{{ __('common.general.transaction_id') }}</th>
              <th>{{ __('common.general.date') }}</th>
              <th>{{ __('common.general.amount') }}</th>
              <th>{{ __('common.general.currency') }}</th>
              <th>{{ __('common.general.branch') }}</th>
              <th>{{ __('common.general.user') }}</th>
            </tr>
          </thead>
          <tbody>
            @forelse($payments as $payment)
              <tr>
                <td>{{ $payment->tran_id }}</td>
                <td>{{ $payment->tran_date?->format('{{ __('common.general.ymd') }}') }}</td>
                <td>${{ number_format($payment->amount, 2) }}</td>
                <td>{{ $payment->currency->currency ?? '{{ __('common.general.usd') }}' }}</td>
                <td>{{ $payment->branch->branch_name ?? 'N/A' }}</td>
                <td>{{ $payment->user->login_name ?? 'N/A' }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="">{{ __('common.general.no_payments_found') }}</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  @endif
@endsection
