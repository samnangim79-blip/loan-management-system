@extends('admin.layouts.admin_layout')

@section('pageTitle', __('common.general.language_test'))

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">{{ __('common.general.language_test') }}</h3>
          </div>
          <div class="card-body">
            <h4>{{ __('common.nav.dashboard') }}</h4>
            <p>{{ __('common.general.current_language') }}: {{ current_language()['name'] }}
              ({{ current_language()['native'] }})</p>

            <h5>{{ __('common.nav.menu_items') }}:</h5>
            <ul>
              <li>{{ __('common.nav.customers') }}</li>
              <li>{{ __('common.nav.loans') }}</li>
              <li>{{ __('common.nav.accounts') }}</li>
              <li>{{ __('common.nav.transactions') }}</li>
            </ul>

            <h5>{{ __('common.form.form_fields') }}:</h5>
            <ul>
              <li>{{ __('common.form.name') }}</li>
              <li>{{ __('common.form.amount') }}</li>
              <li>{{ __('common.form.date') }}</li>
              <li>{{ __('common.form.status') }}</li>
            </ul>

            <h5>{{ __('common.general.actions') }}:</h5>
            <ul>
              <li>{{ __('common.actions.create') }}</li>
              <li>{{ __('common.actions.edit') }}</li>
              <li>{{ __('common.actions.delete') }}</li>
              <li>{{ __('common.actions.view') }}</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
