@extends('admin.layouts.admin_layout')

@section('pageTitle', '{{ __('common.general.account_details') }}')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accounts</a></li>
  <li class="breadcrumb-item active">View</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">{{ __('common.messages.account_information') }}</h3>
        </div>
        <div class="card-body">
          <table class="table table-striped">
            <tr>
              <th>Account No:</th>
              <td>{{ $account->acct_no }}</td>
            </tr>
            <tr>
              <th>Account Name:</th>
              <td>{{ $account->acct_name }}</td>
            </tr>
            <tr>
              <th>Customer:</th>
              <td>{{ $account->customer->name_en ?? 'N/A' }}</td>
            </tr>
            <tr>
              <th>Account Type:</th>
              <td>{{ $account->accountType->acct_type ?? 'N/A' }}</td>
            </tr>
            <tr>
              <th>Status:</th>
              <td>
                <span class="badge bg-{{ $account->account_status == 1 ? '{{ __('common.messages.success') }}' : '{{ __('common.messages.warning') }}' }}">
                  {{ $account->status_text }}
                </span>
              </td>
            </tr>
            <tr>
              <th>Joint Account:</th>
              <td>{{ $account->joint_flag ? '{{ __('common.general.yes') }}' : 'No' }}</td>
            </tr>
            <tr>
              <th>Opened Date:</th>
              <td>{{ $account->opened_date?->format('{{ __('common.general.ymd') }}') }}</td>
            </tr>
          </table>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">{{ __('common.messages.balance_information') }}</h3>
        </div>
        <div class="card-body">
          <div class="">
            <h2 class="">${{ number_format($account->getBalance(), 2) }}</h2>
            <p class="text-muted">{{ __('common.general.current_balance') }}</p>
          </div>
          <hr>
          <table class="table table-striped">
            <tr>
              <th>Branch:</th>
              <td>{{ $account->branch->branch_name ?? 'N/A' }}</td>
            </tr>
            <tr>
              <th>Last Withdrawal:</th>
              <td>{{ $account->last_withdraw_date?->format('{{ __('common.general.ymd') }}') ?? 'N/A' }}</td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="row mt-3">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">{{ __('common.nav.loans') }}</h3>
        </div>
        <div class="card-body">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>{{ __('common.general.contract_no') }}</th>
                <th>{{ __('common.general.amount') }}</th>
                <th>{{ __('common.general.outstanding') }}</th>
                <th>{{ __('common.general.interest_rate') }}</th>
                <th>{{ __('common.general.status') }}</th>
                <th>{{ __('common.general.action') }}</th>
              </tr>
            </thead>
            <tbody>
              @forelse($account->loans as $loan)
                <tr>
                  <td>{{ $loan->contract_no }}</td>
                  <td>${{ number_format($loan->amount, 2) }}</td>
                  <td>${{ number_format($loan->os_balance, 2) }}</td>
                  <td>{{ $loan->int_rate }}%</td>
                  <td>
                    @if ($loan->os_balance > 0)
                      <span class="">{{ __('common.general.active') }}</span>
                    @else
                      <span class="">{{ __('common.general.closed') }}</span>
                    @endif
                  </td>
                  <td>
                    <a href="{{ route('loans.show', $loan->loan_schedule_id) }}" class="">
                      <i class=""></i>
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="">{{ __('common.general.no_loans_found') }}</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="row mt-3">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">{{ __('common.general.recent_transactions') }}</h3>
        </div>
        <div class="card-body">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>{{ __('common.general.transaction_id') }}</th>
                <th>{{ __('common.general.date') }}</th>
                <th>{{ __('common.general.type') }}</th>
                <th>{{ __('common.general.amount') }}</th>
                <th>{{ __('common.general.balance') }}</th>
              </tr>
            </thead>
            <tbody>
              @forelse($account->transactions->take(10) as $tran)
                <tr>
                  <td>{{ $tran->cust_tran_id }}</td>
                  <td>{{ $tran->transaction->tran_date?->format('{{ __('common.general.ymd') }}') ?? 'N/A' }}</td>
                  <td>
                    @if ($tran->dr_cr == 'D')
                      <span class="">{{ __('common.general.debit') }}</span>
                    @else
                      <span class="">{{ __('common.general.credit') }}</span>
                    @endif
                  </td>
                  <td>${{ number_format($tran->amt, 2) }}</td>
                  <td>${{ number_format($tran->os_bal, 2) }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="">{{ __('common.general.no_transactions_found') }}</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="mt-3">
    <a href="{{ route('accounts.edit', $account->acct_id) }}" class="btn btn-primary">
      <i class=""></i> Edit Account
    </a>
    <a href="{{ route('dashboard') }}" class="btn btn-primary">
      <i class=""></i> Back to List
    </a>
  </div>
@endsection
