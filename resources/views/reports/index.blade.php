@extends('admin.layouts.admin_layout')

@section('pageTitle', __('common.reports.title'))

@section('breadcrumb')
  <li class="breadcrumb-item active">{{ __('common.reports.title') }}</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-4">
      <div class="card">
        <div class="card-body text-center">
          <i class="fas fa-file-invoice-dollar fa-3x text-primary mb-3"></i>
          <h5 class="card-title">{{ __('common.reports.loan_reports') }}</h5>
          <p class="card-text">{{ __('common.reports.loan_reports_desc') }}</p>
          <a href="{{ route('reports.loans') }}" class="btn btn-primary">
            <i class="fas fa-arrow-right"></i> {{ __('common.reports.view_report') }}
          </a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-body text-center">
          <i class="fas fa-money-bill-wave fa-3x text-success mb-3"></i>
          <h5 class="card-title">{{ __('common.reports.payment_reports') }}</h5>
          <p class="card-text">{{ __('common.reports.payment_reports_desc') }}</p>
          <a href="{{ route('reports.payments') }}" class="btn btn-success">
            <i class="fas fa-arrow-right"></i> {{ __('common.reports.view_report') }}</a>
          </a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-body text-center">
          <i class="fas fa-users fa-3x text-info mb-3"></i>
          <h5 class="card-title">{{ __('common.reports.customer_reports') }}</h5>
          <p class="card-text">{{ __('common.reports.customer_reports_desc') }}</p>
          <a href="{{ route('reports.customers') }}" class="btn btn-info">
            <i class="fas fa-arrow-right"></i> {{ __('common.reports.view_report') }}
          </a>
        </div>
      </div>
    </div>
  </div>
@endsection
