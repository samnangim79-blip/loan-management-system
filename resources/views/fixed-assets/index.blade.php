@extends('admin.layouts.admin_layout')

@section('pageTitle', 'Fixed Assets Management')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('fixed-assets.index') }}">{{ __('common.nav.fixed_assets') }}</a></li>
  <li class="breadcrumb-item active">{{ __('common.general.list') }}</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-building me-2"></i>Fixed Assets List</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-primary btn-sm" id="addAssetBtn">
              <i class="fas fa-plus"></i> Add Fixed Asset
            </button>
          </div>
        </div>
        <div class="card-body">
          <table id="assetsTable" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>{{ __('common.general.id') }}</th>
                <th>Asset Code</th>
                <th>Description</th>
                <th>Asset Type</th>
                <th>Purchase Date</th>
                <th>Purchase Price</th>
                <th>Net Value</th>
                <th>Currency</th>
                <th>Useful Life</th>
                <th>Status</th>
                <th>{{ __('common.general.action') }}</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Add/Edit Asset Modal -->
  <div class="modal fade" id="assetModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Add Fixed Asset</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="assetForm">
          <div class="modal-body">
            <input type="hidden" id="asset_id" name="asset_id">

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="fa_code" class="form-label">Asset Code <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="fa_code" name="fa_code" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="fa_type_id" class="form-label">Asset Type <span class="text-danger">*</span></label>
                  <select class="form-select" id="fa_type_id" name="fa_type_id" required>
                    <option value="">Select Asset Type</option>
                    @foreach ($assetTypes as $type)
                      <option value="{{ $type->fa_type_id }}">{{ $type->fa_type }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="mb-3">
              <label for="fa_desc" class="form-label">Description <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="fa_desc" name="fa_desc" required>
            </div>

            <div class="mb-3">
              <label for="fa_comment" class="form-label">Comment</label>
              <textarea class="form-control" id="fa_comment" name="fa_comment" rows="2"></textarea>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="mb-3">
                  <label for="purchase_date" class="form-label">Purchase Date <span class="text-danger">*</span></label>
                  <input type="text" class="form-control datepicker" id="purchase_date" name="purchase_date" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="mb-3">
                  <label for="purchase_price" class="form-label">Purchase Price <span class="text-danger">*</span></label>
                  <input type="number" class="form-control" id="purchase_price" name="purchase_price" step="0.01"
                    min="0" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="mb-3">
                  <label for="ccy_id" class="form-label">Currency <span class="text-danger">*</span></label>
                  <select class="form-select" id="ccy_id" name="ccy_id" required>
                    <option value="">Select Currency</option>
                    @foreach ($currencies as $currency)
                      <option value="{{ $currency->ccy_id }}">{{ $currency->currency }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="usefull_life" class="form-label">Useful Life (Years) <span
                      class="text-danger">*</span></label>
                  <input type="number" class="form-control" id="usefull_life" name="usefull_life" min="1"
                    required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="credit_gl" class="form-label">Credit GL Account</label>
                  <input type="number" class="form-control" id="credit_gl" name="credit_gl">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary"
              data-bs-dismiss="modal">{{ __('common.general.cancel') }}</button>
            <button type="submit" class="btn btn-primary">{{ __('common.general.save') }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- View Asset Modal -->
  <div class="modal fade" id="viewAssetModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Asset Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="assetDetails">
          <!-- Asset details will be loaded here -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Depreciation Modal -->
  <div class="modal fade" id="depreciationModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Record Depreciation</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="depreciationForm">
          <div class="modal-body">
            <input type="hidden" id="depre_asset_id" name="asset_id">
            <div class="alert alert-info" id="assetInfo"></div>

            <div class="mb-3">
              <label for="depre_date" class="form-label">Depreciation Date <span class="text-danger">*</span></label>
              <input type="text" class="form-control datepicker" id="depre_date" name="depre_date" required>
            </div>

            <div class="mb-3">
              <label for="amount" class="form-label">Depreciation Amount <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="0"
                required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Record Depreciation</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Disposal Modal -->
  <div class="modal fade" id="disposalModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Dispose Asset</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="disposalForm">
          <div class="modal-body">
            <input type="hidden" id="dispose_asset_id" name="asset_id">

            <div class="mb-3">
              <label for="dispose_date" class="form-label">Disposal Date <span class="text-danger">*</span></label>
              <input type="text" class="form-control datepicker" id="dispose_date" name="dispose_date" required>
            </div>

            <div class="mb-3">
              <label for="dispose_value" class="form-label">Disposal Value <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="dispose_value" name="dispose_value" step="0.01"
                min="0" required>
            </div>

            <div class="mb-3">
              <label for="dispose_comment" class="form-label">Comment</label>
              <textarea class="form-control" id="dispose_comment" name="dispose_comment" rows="2"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger">Dispose Asset</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    $(document).ready(function() {
      // Initialize Flatpickr for date fields
      $('.datepicker').flatpickr({
        dateFormat: 'Y-m-d',
        allowInput: true
      });

      // Initialize DataTable
      const table = $('#assetsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('fixed-assets.data') }}',
        columns: [{
            data: 'FA_ID',
            name: 'FA_ID'
          },
          {
            data: 'FA_CODE',
            name: 'FA_CODE'
          },
          {
            data: 'FA_DESC',
            name: 'FA_DESC'
          },
          {
            data: 'type_name',
            name: 'type_name'
          },
          {
            data: 'PURCHASE_DATE',
            name: 'PURCHASE_DATE',
            render: function(data) {
              return data ? new Date(data).toLocaleDateString() : 'N/A';
            }
          },
          {
            data: 'formatted_price',
            name: 'formatted_price',
            className: 'text-end'
          },
          {
            data: 'formatted_net_value',
            name: 'formatted_net_value',
            className: 'text-end'
          },
          {
            data: 'currency_code',
            name: 'currency_code'
          },
          {
            data: 'USEFULL_LIFE',
            name: 'USEFULL_LIFE',
            render: function(data) {
              return data ? data + ' years' : 'N/A';
            }
          },
          {
            data: 'DISPOSE_DATE',
            name: 'DISPOSE_DATE',
            render: function(data) {
              if (data) {
                return '<span class="badge bg-danger">Disposed</span>';
              }
              return '<span class="badge bg-success">Active</span>';
            }
          },
          {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
          }
        ],
        order: [
          [0, 'desc']
        ]
      });

      // Add Asset Button
      $('#addAssetBtn').click(function() {
        $('#assetForm')[0].reset();
        $('#asset_id').val('');
        $('#modalTitle').text('Add Fixed Asset');
        $('#assetModal').modal('show');
      });

      // Submit Asset Form
      $('#assetForm').submit(function(e) {
        e.preventDefault();
        const assetId = $('#asset_id').val();
        const url = assetId ? `/fixed-assets/${assetId}` : '{{ route('fixed-assets.store') }}';
        const method = assetId ? 'PUT' : 'POST';

        $.ajax({
          url: url,
          type: method,
          data: $(this).serialize(),
          success: function(response) {
            if (response.success) {
              $('#assetModal').modal('hide');
              table.ajax.reload();
              toastr.success(response.message);
            }
          },
          error: function(xhr) {
            if (xhr.status === 422) {
              const errors = xhr.responseJSON.errors;
              let errorMsg = '';
              $.each(errors, function(key, value) {
                errorMsg += value[0] + '<br>';
              });
              toastr.error(errorMsg);
            } else {
              toastr.error(xhr.responseJSON.message || 'An error occurred');
            }
          }
        });
      });

      // View Asset Button
      $(document).on('click', '.view-btn', function() {
        const assetId = $(this).data('id');

        $.ajax({
          url: `/fixed-assets/${assetId}`,
          type: 'GET',
          success: function(asset) {
            let html = `
              <div class="row">
                <div class="col-md-6">
                  <p><strong>Asset Code:</strong> ${asset.FA_CODE || 'N/A'}</p>
                  <p><strong>Description:</strong> ${asset.FA_DESC || 'N/A'}</p>
                  <p><strong>Asset Type:</strong> ${asset.asset_type?.FA_TYPE || 'N/A'}</p>
                  <p><strong>Purchase Date:</strong> ${asset.PURCHASE_DATE || 'N/A'}</p>
                  <p><strong>Purchase Price:</strong> ${asset.PURCHASE_PRICE || 'N/A'}</p>
                </div>
                <div class="col-md-6">
                  <p><strong>Currency:</strong> ${asset.currency?.CURRENCY || 'N/A'}</p>
                  <p><strong>Useful Life:</strong> ${asset.USEFULL_LIFE || 'N/A'} years</p>
                  <p><strong>Net Value:</strong> ${asset.NET_VALUE || 'N/A'}</p>
                  <p><strong>Status:</strong> ${asset.DISPOSE_DATE ? '<span class="badge bg-danger">Disposed</span>' : '<span class="badge bg-success">Active</span>'}</p>
                </div>
              </div>
              ${asset.FA_COMMENT ? `<hr><p><strong>Comment:</strong> ${asset.FA_COMMENT}</p>` : ''}
              ${asset.DISPOSE_DATE ? `<hr><p><strong>Disposal Date:</strong> ${asset.DISPOSE_DATE}</p><p><strong>Disposal Value:</strong> ${asset.DISPOSE_VALUE}</p>${asset.DISPOSE_COMMENT ? `<p><strong>Disposal Comment:</strong> ${asset.DISPOSE_COMMENT}</p>` : ''}` : ''}
            `;

            if (asset.depreciations && asset.depreciations.length > 0) {
              html +=
                '<hr><h6>Depreciation History:</h6><table class="table table-sm"><thead><tr><th>Date</th><th>Amount</th></tr></thead><tbody>';
              asset.depreciations.forEach(function(depre) {
                html += `<tr><td>${depre.DEPRE_DATE}</td><td>${depre.AMOUNT}</td></tr>`;
              });
              html += '</tbody></table>';
            }

            $('#assetDetails').html(html);
            $('#viewAssetModal').modal('show');
          },
          error: function() {
            toastr.error('Failed to load asset details');
          }
        });
      });

      // Edit Asset Button
      $(document).on('click', '.edit-btn', function() {
        const assetId = $(this).data('id');

        $.ajax({
          url: `/fixed-assets/${assetId}`,
          type: 'GET',
          success: function(asset) {
            $('#asset_id').val(asset.FA_ID);
            $('#fa_code').val(asset.FA_CODE);
            $('#fa_desc').val(asset.FA_DESC);
            $('#fa_comment').val(asset.FA_COMMENT);
            $('#fa_type_id').val(asset.FA_TYPE_ID);
            $('#usefull_life').val(asset.USEFULL_LIFE);
            $('#credit_gl').val(asset.CREDIT_GL);

            $('#modalTitle').text('Edit Fixed Asset');
            $('#assetModal').modal('show');
          },
          error: function() {
            toastr.error('Failed to load asset data');
          }
        });
      });

      // Depreciation Button
      $(document).on('click', '.depreciate-btn', function() {
        const assetId = $(this).data('id');

        $.ajax({
          url: `/fixed-assets/${assetId}`,
          type: 'GET',
          success: function(asset) {
            $('#depre_asset_id').val(asset.FA_ID);
            $('#assetInfo').html(
              `<strong>${asset.FA_CODE}</strong> - ${asset.FA_DESC}<br>Current Net Value: ${asset.NET_VALUE} ${asset.currency?.CURRENCY || ''}`
            );
            $('#amount').attr('max', asset.NET_VALUE);
            $('#depreciationModal').modal('show');
          },
          error: function() {
            toastr.error('Failed to load asset data');
          }
        });
      });

      // Submit Depreciation Form
      $('#depreciationForm').submit(function(e) {
        e.preventDefault();
        const assetId = $('#depre_asset_id').val();

        $.ajax({
          url: `/fixed-assets/${assetId}/depreciate`,
          type: 'POST',
          data: $(this).serialize(),
          success: function(response) {
            if (response.success) {
              $('#depreciationModal').modal('hide');
              table.ajax.reload();
              toastr.success(response.message);
            }
          },
          error: function(xhr) {
            if (xhr.status === 422) {
              const errors = xhr.responseJSON.errors;
              let errorMsg = '';
              $.each(errors, function(key, value) {
                errorMsg += value[0] + '<br>';
              });
              toastr.error(errorMsg);
            } else {
              toastr.error(xhr.responseJSON.message || 'An error occurred');
            }
          }
        });
      });

      // Disposal Button
      $(document).on('click', '.dispose-btn', function() {
        const assetId = $(this).data('id');
        $('#dispose_asset_id').val(assetId);
        $('#disposalModal').modal('show');
      });

      // Submit Disposal Form
      $('#disposalForm').submit(function(e) {
        e.preventDefault();
        const assetId = $('#dispose_asset_id').val();

        Swal.fire({
          title: 'Are you sure?',
          text: "This will mark the asset as disposed and cannot be undone!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Yes, dispose it!'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: `/fixed-assets/${assetId}/dispose`,
              type: 'POST',
              data: $('#disposalForm').serialize(),
              success: function(response) {
                if (response.success) {
                  $('#disposalModal').modal('hide');
                  table.ajax.reload();
                  Swal.fire('Disposed!', response.message, 'success');
                }
              },
              error: function(xhr) {
                if (xhr.status === 422) {
                  const errors = xhr.responseJSON.errors;
                  let errorMsg = '';
                  $.each(errors, function(key, value) {
                    errorMsg += value[0] + '<br>';
                  });
                  toastr.error(errorMsg);
                } else {
                  toastr.error(xhr.responseJSON.message || 'An error occurred');
                }
              }
            });
          }
        });
      });
    });
  </script>
@endpush
