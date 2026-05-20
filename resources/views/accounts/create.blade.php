@extends('admin.layouts.admin_layout')

@section('pageTitle', __('common.general.create_account'))

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('accounts.index') }}">Accounts</a></li>
  <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ __('common.general.create_new_account') }}</h3>
    </div>
    <form id="accountForm" action="{{ route('accounts.store') }}" method="POST">
      @csrf
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label for="cust_id" class="form-label">Customer <span class="text-danger">*</span></label>
              <select name="cust_id" id="cust_id" class="form-control" required>
                <option value="">{{ __('common.form.select_customer') }}</option>
                @foreach ($customers as $customer)
                  <option value="{{ $customer->cust_id }}">
                    {{ $customer->name_en }} ({{ $customer->ic_no }})
                  </option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label for="acct_name" class="form-label">Account Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="acct_name" name="acct_name" required>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label for="acct_type_id" class="form-label">Account Type <span class="text-danger">*</span></label>
              <select name="acct_type_id" id="acct_type_id" class="form-control" required>
                <option value="">{{ __('common.general.select_type') }}</option>
                @foreach ($accountTypes as $type)
                  <option value="{{ $type->acct_type_id }}">{{ $type->acct_type }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label for="joint_flag" class="form-label">{{ __('common.form.joint_account') }}</label>
              <select name="joint_flag" id="joint_flag" class="form-control">
                <option value="0">No</option>
                <option value="1">{{ __('common.general.yes') }}</option>
              </select>
            </div>
          </div>
        </div>

        <div class="mb-3">
          <label for="mandatory" class="form-label">{{ __('common.pagination.mandatory_field') }}</label>
          <input type="text" class="form-control" id="mandatory" name="mandatory">
        </div>
      </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-save me-1"></i> Save Account
        </button>
        <a href="{{ route('accounts.index') }}" class="btn btn-secondary">
          <i class="fas fa-times me-1"></i> Cancel
        </a>
      </div>
    </form>
  </div>
@endsection

@push('scripts')
  <script>
    $(document).ready(function() {
      $('#accountForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
          url: $(this).attr('action'),
          method: 'POST',
          data: $(this).serialize(),
          success: function(response) {
            if (response.success) {
              toastr.success(response.message);
              window.location.href = "{{ route('accounts.index') }}";
            }
          },
          error: function(xhr) {
            let errors = xhr.responseJSON.errors;
            $.each(errors, function(key, value) {
              toastr.error(value[0]);
            });
          }
        });
      });
    });
  </script>
@endpush
