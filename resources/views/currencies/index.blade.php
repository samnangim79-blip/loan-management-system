@extends('admin.layouts.admin_layout')

@section('title', __('currencies.management'))

@section('header-buttons')
  @permission('currency_add')
    <button type="button" class="btn btn-primary btn-sm" id="addCurrencyBtn">
      <i class="fas fa-plus me-1"></i> Add Currency
    </button>
  @endpermission
@endsection

@section('content')
  <div class="card">
    <div class="card-header bg-white py-3">
      <div class="row">
        <div class="col">
          <h5 class="mb-0"><i class="fas fa-dollar-sign me-2 text-success"></i>Currencies</h5>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="currenciesTable" class="table table-striped table-hover w-100">
          <thead>
            <tr>
              <th>ID</th>
              <th>{{ __('common.general.currency') }}</th>
              <th>{{ __('common.general.exchange_rate') }}</th>
              <th>{{ __('common.general.round_value') }}</th>
              <th>{{ __('common.general.decimal_places') }}</th>
              <th>{{ __('common.general.actions') }}</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Add/Edit Currency Modal -->
  <div class="modal fade" id="currencyModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="currencyModalLabel">{{ __('common.general.add_currency') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="currencyForm">
          <div class="modal-body">
            <input type="hidden" id="currencyId" name="currency_id">
            <div class="mb-3">
              <label class="form-label">Currency Code <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="currency" name="currency" maxlength="4" required
                placeholder="e.g., USD, KHR, EUR">
            </div>
            <div class="mb-3">
              <label class="form-label">Exchange Rate <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="ccyRate" name="ccy_rate" step="0.00001" min="0"
                required>
              <small class="form-text text-muted">Rate compared to base currency</small>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label">{{ __('common.form.round_value') }}</label>
                  <input type="number" class="form-control" id="roundValue" name="round_value" min="0"
                    value="0">
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label">{{ __('common.form.decimal_places') }}</label>
                  <input type="number" class="form-control" id="decimalPlace" name="decimal_place" min="0"
                    max="5" value="2">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label">{{ __('common.form.compare_value') }}</label>
                  <input type="text" class="form-control" id="compareValue" name="compare_value">
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label">{{ __('common.form.value_format') }}</label>
                  <input type="text" class="form-control" id="valueFormat" name="value_format">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('common.general.cancel') }}</button>
            <button type="submit" class="btn btn-primary" id="saveCurrencyBtn">{{ __('common.general.save') }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- View Currency Modal -->
  <div class="modal fade" id="viewCurrencyModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ __('common.general.currency_details') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="viewCurrencyBody">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('common.general.close') }}</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Exchange Rate History Modal -->
  <div class="modal fade" id="historyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-history me-2"></i>Exchange Rate History</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-striped" id="historyTable">
              <thead>
                <tr>
                  <th>{{ __('common.general.date') }}</th>
                  <th>{{ __('common.general.exchange_rate') }}</th>
                </tr>
              </thead>
              <tbody id="historyTableBody">
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('common.general.close') }}</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    $(document).ready(function() {
      // Initialize DataTable
      var table = $('#currenciesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('currencies.data') }}',
        columns: [{
            data: 'ccy_id',
            name: 'ccy_id'
          },
          {
            data: 'currency',
            name: 'currency',
            render: function(data) {
              return '<span class="badge bg-primary fs-6">' + data + '</span>';
            }
          },
          {
            data: 'formatted_rate',
            name: 'ccy_rate'
          },
          {
            data: 'round_value',
            name: 'round_value',
            render: function(data) {
              return data || '0';
            }
          },
          {
            data: 'decimal_place',
            name: 'decimal_place',
            render: function(data) {
              return data || '2';
            }
          },
          {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
          }
        ]
      });

      // Add Currency
      $('#addCurrencyBtn').click(function() {
        $('#currencyForm')[0].reset();
        $('#currencyId').val('');
        $('#currencyModalLabel').text('{{ __('common.general.add_currency') }}');
        $('#currencyModal').modal('show');
      });

      // View Currency
      $('#currenciesTable').on('click', '.view-btn', function() {
        var id = $(this).data('id');
        $.get('/currencies/' + id, function(data) {
          var html = `
                        <table class="table table-striped">
                            <tr><th>ID</th><td>${data.ccy_id}</td></tr>
                            <tr><th>{{ __('common.general.currency_code') }}</th><td><span class="badge bg-primary">${data.currency}</span></td></tr>
                            <tr><th>{{ __('common.general.exchange_rate') }}</th><td>${parseFloat(data.ccy_rate).toFixed(5)}</td></tr>
                            <tr><th>{{ __('common.general.round_value') }}</th><td>${data.round_value || 0}</td></tr>
                            <tr><th>{{ __('common.general.decimal_places') }}</th><td>${data.decimal_place || 2}</td></tr>
                            <tr><th>{{ __('common.general.compare_value') }}</th><td>${data.compare_value || '-'}</td></tr>
                            <tr><th>{{ __('common.general.value_format') }}</th><td>${data.value_format || '-'}</td></tr>
                        </table>
                    `;
          $('#viewCurrencyBody').html(html);
          $('#viewCurrencyModal').modal('show');
        });
      });

      // Edit Currency
      $('#currenciesTable').on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        $.get('/currencies/' + id, function(data) {
          $('#currencyId').val(data.ccy_id);
          $('#currency').val(data.currency);
          $('#ccyRate').val(data.ccy_rate);
          $('#roundValue').val(data.round_value);
          $('#decimalPlace').val(data.decimal_place);
          $('#compareValue').val(data.compare_value);
          $('#valueFormat').val(data.value_format);
          $('#currencyModalLabel').text('{{ __('common.general.edit_currency') }}');
          $('#currencyModal').modal('show');
        });
      });

      // View Rate History
      $('#currenciesTable').on('click', '.history-btn', function() {
        var id = $(this).data('id');
        $.get('/currencies/' + id + '/rates', function(data) {
          var html = '';
          if (data.length > 0) {
            $.each(data, function(i, item) {
              html += '<tr>';
              html += '<td>' + item.rate_date + '</td>';
              html += '<td>' + parseFloat(item.ex_rate).toFixed(5) + '</td>';
              html += '</tr>';
            });
          } else {
            html = '<tr><td colspan="2" class="text-center">{{ __('common.general.no_history_available') }}</td></tr>';
          }
          $('#historyTableBody').html(html);
          $('#historyModal').modal('show');
        });
      });

      // Save Currency
      $('#currencyForm').submit(function(e) {
        e.preventDefault();
        var id = $('#currencyId').val();
        var url = id ? '/currencies/' + id : '{{ route('currencies.store') }}';
        var method = id ? 'PUT' : 'POST';

        $.ajax({
          url: url,
          method: method,
          data: $(this).serialize(),
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          success: function(response) {
            if (response.success) {
              $('#currencyModal').modal('hide');
              table.ajax.reload();
              toastr.success(response.message);
            }
          },
          error: function(xhr) {
            toastr.error(xhr.responseJSON?.message || '{{ __('common.messages.an_error_occurred') }}');
          }
        });
      });
    });
  </script>
@endpush
