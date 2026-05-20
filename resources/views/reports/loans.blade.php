@extends('admin.layouts.admin_layout')

@section('pageTitle', __('reports.loan_reports'))

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
  <li class="breadcrumb-item active">Loans</li>
@endsection

@section('content')
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ __('common.general.loan_report_filters') }}</h3>
    </div>
    <div class="">
      <form id="" method="GET">
        <div class="">
          <div class="col-md-3">
            <div class="mb-3">
              <label for="date_from" class="form-label">{{ __('common.general.date_from') }}</label>
              <input type="date" class="form-control" id="date_from" name="date_from">
            </div>
          </div>
          <div class="col-md-3">
            <div class="mb-3">
              <label for="date_to" class="form-label">{{ __('common.general.date_to') }}</label>
              <input type="date" class="form-control" id="date_to" name="date_to">
            </div>
          </div>
          <div class="col-md-3">
            <div class="mb-3">
              <label for="status" class="form-label">{{ __('common.general.status') }}</label>
              <select name="status" id="" class="form-control">
                <option value="">{{ __('common.form.all') }}</option>
                <option value="active">{{ __('common.form.active') }}</option>
                <option value="closed">{{ __('common.general.closed') }}</option>
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="mb-3">
              <label for="format" class="form-label">{{ __('common.general.export_format') }}</label>
              <select name="format" id="" class="form-control">
                <option value="">{{ __('common.general.view_on_screen') }}</option>
                <option value="pdf">{{ __('common.general.pdf') }}</option>
                <option value="excel">{{ __('common.general.excel') }}</option>
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

  @if (isset($loans))
    <div class="card mt-3">
      <div class="card-header">
        <h3 class="card-title">{{ __('common.general.report_results') }}</h3>
      </div>
      <div class="">
        <div class="row mb-3">
          <div class="col-md-3">
            <div class="">
              <span class=""><i class=""></i></span>
              <div class="">
                <span class="">{{ __('common.nav.total_loans') }}</span>
                <span class="">{{ $summary['total_loans'] ?? 0 }}</span>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="">
              <span class=""><i class=""></i></span>
              <div class="">
                <span class="">{{ __('common.pagination.total_disbursed') }}</span>
                <span class="">${{ number_format($summary['total_disbursed'] ?? 0, 2) }}</span>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="">
              <span class=""><i class=""></i></span>
              <div class="">
                <span class="">{{ __('common.general.outstanding') }}</span>
                <span class="">${{ number_format($summary['outstanding_balance'] ?? 0, 2) }}</span>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="">
              <span class=""><i class=""></i></span>
              <div class="">
                <span class="">{{ __('common.general.paid_amount') }}</span>
                <span class="">${{ number_format($summary['paid_amount'] ?? 0, 2) }}</span>
              </div>
            </div>
          </div>
        </div>

        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>{{ __('common.general.contract_no') }}</th>
              <th>{{ __('common.pagination.customer') }}</th>
              <th>{{ __('common.general.amount') }}</th>
              <th>{{ __('common.general.outstanding') }}</th>
              <th>{{ __('common.general.interest_rate') }}</th>
              <th>{{ __('common.general.issue_date') }}</th>
              <th>{{ __('common.general.status') }}</th>
            </tr>
          </thead>
          <tbody>
            @forelse($loans as $loan)
              <tr>
                <td>{{ $loan->contract_no }}</td>
                <td>{{ $loan->account->customer->name_en ?? 'N/A' }}</td>
                <td>${{ number_format($loan->amount, 2) }}</td>
                <td>${{ number_format($loan->os_balance, 2) }}</td>
                <td>{{ $loan->int_rate }}%</td>
                <td>{{ $loan->date_issue?->format('{{ __('common.general.ymd') }}') }}</td>
                <td>
                  @if ($loan->os_balance > 0)
                    <span class="">{{ __('common.general.active') }}</span>
                  @else
                    <span class="">{{ __('common.general.closed') }}</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="">{{ __('common.general.no_loans_found') }}</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  @endif
@endsection
