@extends('admin.layouts.admin_layout')

@section('title', __('locations.provinces'))

@section('header-buttons')
  @permission('province_add')
    <button type="button" class="btn btn-primary btn-sm" id="addProvinceBtn">
      <i class="fas fa-plus me-1"></i> Add Province
    </button>
  @endpermission
@endsection

@section('content')
  <div class="card">
    <div class="card-header bg-white py-3">
      <div class="row">
        <div class="col">
          <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2 text-primary"></i>Provinces / Districts / Communes / Villages</h5>
        </div>
        <div class="col-auto">
          <div class="btn-group" role="group">
            <a href="{{ route('locations.provinces') }}" class="btn btn-primary active">Provinces</a>
            <a href="{{ route('locations.provinces') }}?tab=districts" class="btn btn-outline-primary" id="districtsTab">Districts</a>
            <a href="{{ route('locations.provinces') }}?tab=communes" class="btn btn-outline-primary" id="communesTab">Communes</a>
            <a href="{{ route('locations.provinces') }}?tab=villages" class="btn btn-outline-primary" id="villagesTab">Villages</a>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="provincesTable" class="table table-striped table-hover w-100">
          <thead>
            <tr>
              <th>ID</th>
              <th>Province (EN)</th>
              <th>Province (KH)</th>
              <th>{{ __('common.general.districts') }}</th>
              <th>{{ __('common.general.actions') }}</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Districts Section -->
  <div class="card shadow-sm mt-4" id="districtsSection" style="display: none;">
    <div class="card-header bg-white py-3">
      <div class="row">
        <div class="col">
          <h5 class="mb-0"><i class="fas fa-building me-2 text-info"></i>Districts</h5>
        </div>
        <div class="col-auto">
          @permission('district_add')
            <button type="button" class="btn btn-info btn-sm" id="addDistrictBtn">
              <i class="fas fa-plus me-1"></i> Add District
            </button>
          @endpermission
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-md-4">
          <label class="form-label">{{ __('common.general.filter_by_province') }}</label>
          <select id="filterProvinceDistrict" class="form-control">
            <option value="">{{ __('common.form.all_provinces') }}</option>
          </select>
        </div>
      </div>
      <div class="table-responsive">
        <table id="districtsTable" class="table table-striped table-hover w-100">
          <thead>
            <tr>
              <th>ID</th>
              <th>{{ __('common.general.province') }}</th>
              <th>District (EN)</th>
              <th>District (KH)</th>
              <th>{{ __('common.general.actions') }}</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Communes Section -->
  <div class="card shadow-sm mt-4" id="communesSection" style="display: none;">
    <div class="card-header bg-white py-3">
      <div class="row">
        <div class="col">
          <h5 class="mb-0"><i class="fas fa-home me-2 text-success"></i>Communes</h5>
        </div>
        <div class="col-auto">
          @permission('commune_add')
            <button type="button" class="btn btn-success btn-sm" id="addCommuneBtn">
              <i class="fas fa-plus me-1"></i> Add Commune
            </button>
          @endpermission
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-md-4">
          <label class="form-label">{{ __('common.general.filter_by_district') }}</label>
          <select id="filterDistrictCommune" class="form-control">
            <option value="">{{ __('common.form.all_districts') }}</option>
          </select>
        </div>
      </div>
      <div class="table-responsive">
        <table id="communesTable" class="table table-striped table-hover w-100">
          <thead>
            <tr>
              <th>ID</th>
              <th>{{ __('common.general.province') }}</th>
              <th>{{ __('common.general.district') }}</th>
              <th>Commune (EN)</th>
              <th>Commune (KH)</th>
              <th>{{ __('common.general.actions') }}</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Villages Section -->
  <div class="card shadow-sm mt-4" id="villagesSection" style="display: none;">
    <div class="card-header bg-white py-3">
      <div class="row">
        <div class="col">
          <h5 class="mb-0"><i class="fas fa-map-pin me-2 text-warning"></i>Villages</h5>
        </div>
        <div class="col-auto">
          @permission('village_add')
            <button type="button" class="btn btn-warning btn-sm" id="addVillageBtn">
              <i class="fas fa-plus me-1"></i> Add Village
            </button>
          @endpermission
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-md-4">
          <label class="form-label">{{ __('common.general.filter_by_commune') }}</label>
          <select id="filterCommuneVillage" class="form-control">
            <option value="">{{ __('common.form.all_communes') }}</option>
          </select>
        </div>
      </div>
      <div class="table-responsive">
        <table id="villagesTable" class="table table-striped table-hover w-100">
          <thead>
            <tr>
              <th>ID</th>
              <th>{{ __('common.general.province') }}</th>
              <th>{{ __('common.general.district') }}</th>
              <th>{{ __('common.general.commune') }}</th>
              <th>Village (EN)</th>
              <th>Village (KH)</th>
              <th>{{ __('common.general.actions') }}</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Province Modal -->
  <div class="modal fade" id="provinceModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="provinceModalLabel">{{ __('common.general.add_province') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="provinceForm">
          <div class="modal-body">
            <input type="hidden" id="provinceId" name="id">
            <div class="mb-3">
              <label class="form-label">Province Name (English) <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="provinceName" name="name_en" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Province Name (Khmer)</label>
              <input type="text" class="form-control" id="provinceNameKh" name="name_kh">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('common.general.cancel') }}</button>
            <button type="submit" class="btn btn-primary" id="saveProvinceBtn">{{ __('common.general.save') }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- District Modal -->
  <div class="modal fade" id="districtModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="districtModalLabel">{{ __('common.general.add_district') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="districtForm">
          <div class="modal-body">
            <input type="hidden" id="districtId" name="id">
            <div class="mb-3">
              <label class="form-label">Province <span class="text-danger">*</span></label>
              <select class="form-control" id="districtProvinceId" name="province_id" required>
                <option value="">{{ __('common.form.select_province') }}</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">District Name (English) <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="districtName" name="name_en" required>
            </div>
            <div class="mb-3">
              <label class="form-label">District Name (Khmer)</label>
              <input type="text" class="form-control" id="districtNameKh" name="name_kh">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('common.general.cancel') }}</button>
            <button type="submit" class="btn btn-primary" id="saveDistrictBtn">{{ __('common.general.save') }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Commune Modal -->
  <div class="modal fade" id="communeModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="communeModalLabel">{{ __('common.general.add_commune') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="communeForm">
          <div class="modal-body">
            <input type="hidden" id="communeId" name="id">
            <div class="mb-3">
              <label class="form-label">Province <span class="text-danger">*</span></label>
              <select class="form-control" id="communeProvinceId" required>
                <option value="">{{ __('common.form.select_province') }}</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">District <span class="text-danger">*</span></label>
              <select class="form-control" id="communeDistrictId" name="district_id" required>
                <option value="">{{ __('common.form.select_district') }}</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Commune Name (English) <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="communeName" name="name_en" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Commune Name (Khmer)</label>
              <input type="text" class="form-control" id="communeNameKh" name="name_kh">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('common.general.cancel') }}</button>
            <button type="submit" class="btn btn-primary" id="saveCommuneBtn">{{ __('common.general.save') }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Village Modal -->
  <div class="modal fade" id="villageModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="villageModalLabel">{{ __('common.general.add_village') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="villageForm">
          <div class="modal-body">
            <input type="hidden" id="villageId" name="id">
            <div class="mb-3">
              <label class="form-label">Province <span class="text-danger">*</span></label>
              <select class="form-control" id="villageProvinceId" required>
                <option value="">{{ __('common.form.select_province') }}</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">District <span class="text-danger">*</span></label>
              <select class="form-control" id="villageDistrictId" required>
                <option value="">{{ __('common.form.select_district') }}</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Commune <span class="text-danger">*</span></label>
              <select class="form-control" id="villageCommuneId" name="commune_id" required>
                <option value="">{{ __('common.form.select_commune') }}</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Village Name (English) <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="villageName" name="name_en" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Village Name (Khmer)</label>
              <input type="text" class="form-control" id="villageNameKh" name="name_kh">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('common.general.cancel') }}</button>
            <button type="submit" class="btn btn-primary" id="saveVillageBtn">{{ __('common.general.save') }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- View Modal -->
  <div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewModalLabel">{{ __('common.general.details') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="viewModalBody">
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
      // Initialize DataTables
      var provincesTable = $('#provincesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('locations.provinces-data') }}',
        columns: [{
            data: 'id',
            name: 'id'
          },
          {
            data: 'name_en',
            name: 'name_en'
          },
          {
            data: 'name_kh',
            name: 'name_kh',
            render: function(data) {
              return data || '-';
            }
          },
          {
            data: 'districts_count',
            name: 'districts_count',
            searchable: false
          },
          {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
          }
        ]
      });

      var districtsTable = $('#districtsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '{{ route('locations.districts-data') }}',
          data: function(d) {
            d.province_id = $('#filterProvinceDistrict').val();
          }
        },
        columns: [{
            data: 'id',
            name: 'id'
          },
          {
            data: 'province_name',
            name: 'province.name_en'
          },
          {
            data: 'name_en',
            name: 'name_en'
          },
          {
            data: 'name_kh',
            name: 'name_kh',
            render: function(data) {
              return data || '-';
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

      var communesTable = $('#communesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '{{ route('locations.communes-data') }}',
          data: function(d) {
            d.district_id = $('#filterDistrictCommune').val();
          }
        },
        columns: [{
            data: 'id',
            name: 'id'
          },
          {
            data: 'province_name',
            name: 'district.province.name_en'
          },
          {
            data: 'district_name',
            name: 'district.name_en'
          },
          {
            data: 'name_en',
            name: 'name_en'
          },
          {
            data: 'name_kh',
            name: 'name_kh',
            render: function(data) {
              return data || '-';
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

      var villagesTable = $('#villagesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '{{ route('locations.villages-data') }}',
          data: function(d) {
            d.commune_id = $('#filterCommuneVillage').val();
          }
        },
        columns: [{
            data: 'id',
            name: 'id'
          },
          {
            data: 'province_name',
            name: 'commune.district.province.name_en'
          },
          {
            data: 'district_name',
            name: 'commune.district.name_en'
          },
          {
            data: 'commune_name',
            name: 'commune.name_en'
          },
          {
            data: 'name_en',
            name: 'name_en'
          },
          {
            data: 'name_kh',
            name: 'name_kh',
            render: function(data) {
              return data || '-';
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

      // Load provinces for dropdowns
      function loadProvinces(selectId) {
        $.get('{{ route('locations.provinces-all') }}', function(data) {
          var select = $(selectId);
          select.find('option:not(:first)').remove();
          $.each(data, function(i, province) {
            select.append('<option value="' + province.id + '">' + province.name_en + '</option>');
          });
        });
      }

      // Tab navigation
      $('#districtsTab').click(function(e) {
        e.preventDefault();
        $('#districtsSection').show();
        $('#communesSection, #villagesSection').hide();
        $('.btn-group .btn').removeClass('active').addClass('btn-outline-primary');
        $(this).removeClass('btn-outline-primary').addClass('active');
        loadProvinces('#filterProvinceDistrict');
        districtsTable.ajax.reload();
      });

      $('#communesTab').click(function(e) {
        e.preventDefault();
        $('#communesSection').show();
        $('#districtsSection, #villagesSection').hide();
        $('.btn-group .btn').removeClass('active').addClass('btn-outline-primary');
        $(this).removeClass('btn-outline-primary').addClass('active');
        communesTable.ajax.reload();
      });

      $('#villagesTab').click(function(e) {
        e.preventDefault();
        $('#villagesSection').show();
        $('#districtsSection, #communesSection').hide();
        $('.btn-group .btn').removeClass('active').addClass('btn-outline-primary');
        $(this).removeClass('btn-outline-primary').addClass('active');
        villagesTable.ajax.reload();
      });

      // Filter change handlers
      $('#filterProvinceDistrict').change(function() {
        districtsTable.ajax.reload();
      });

      $('#filterDistrictCommune').change(function() {
        communesTable.ajax.reload();
      });

      $('#filterCommuneVillage').change(function() {
        villagesTable.ajax.reload();
      });

      // Province CRUD
      $('#addProvinceBtn').click(function() {
        $('#provinceForm')[0].reset();
        $('#provinceId').val('');
        $('#provinceModalLabel').text('{{ __('common.general.add_province') }}');
        $('#provinceModal').modal('show');
      });

      $('#provincesTable').on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        $.get('/locations/provinces/' + id, function(response) {
          if (response.success) {
            var data = response.data;
            $('#provinceId').val(data.id);
            $('#provinceName').val(data.name_en);
            $('#provinceNameKh').val(data.name_kh);
            $('#provinceModalLabel').text('{{ __('common.general.edit_province') }}');
            $('#provinceModal').modal('show');
          }
        });
      });

      $('#provinceForm').submit(function(e) {
        e.preventDefault();
        var id = $('#provinceId').val();
        var url = id ? '/locations/provinces/' + id : '{{ route('locations.store-province') }}';
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
              $('#provinceModal').modal('hide');
              provincesTable.ajax.reload();
              toastr.success(response.message);
            }
          },
          error: function(xhr) {
            toastr.error(xhr.responseJSON?.message || '{{ __('common.messages.an_error_occurred') }}');
          }
        });
      });

      $('#provincesTable').on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        Swal.fire({
          title: '{{ __('common.general.are_you_sure') }}',
          text: '{{ __('common.general.this_action_cannot_be_undone') }}',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          confirmButtonText: '{{ __('common.general.yes_delete_it') }}'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: '/locations/provinces/' + id,
              method: 'DELETE',
              headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
              },
              success: function(response) {
                if (response.success) {
                  provincesTable.ajax.reload();
                  toastr.success(response.message);
                }
              },
              error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || '{{ __('common.messages.an_error_occurred') }}');
              }
            });
          }
        });
      });

      $('#provincesTable').on('click', '.view-btn', function() {
        var id = $(this).data('id');
        $.get('/locations/provinces/' + id, function(response) {
          if (response.success) {
            var data = response.data;
            var html = `
              <table class="table table-striped">
                <tr><th>ID</th><td>${data.id}</td></tr>
                <tr><th>Province (EN)</th><td>${data.name_en || '-'}</td></tr>
                <tr><th>Province (KH)</th><td>${data.name_kh || '-'}</td></tr>
                <tr><th>{{ __('common.general.districts_count') }}</th><td>${data.districts_count || 0}</td></tr>
              </table>
            `;
            $('#viewModalLabel').text('{{ __('common.general.province_details') }}');
            $('#viewModalBody').html(html);
            $('#viewModal').modal('show');
          }
        });
      });

      // District CRUD
      $('#addDistrictBtn').click(function() {
        $('#districtForm')[0].reset();
        $('#districtId').val('');
        $('#districtModalLabel').text('{{ __('common.general.add_district') }}');
        loadProvinces('#districtProvinceId');
        $('#districtModal').modal('show');
      });

      $('#districtsTable').on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        loadProvinces('#districtProvinceId');
        $.get('/locations/districts/' + id, function(response) {
          if (response.success) {
            var data = response.data;
            $('#districtId').val(data.id);
            $('#districtProvinceId').val(data.province_id);
            $('#districtName').val(data.name_en);
            $('#districtNameKh').val(data.name_kh);
            $('#districtModalLabel').text('{{ __('common.general.edit_district') }}');
            $('#districtModal').modal('show');
          }
        });
      });

      $('#districtForm').submit(function(e) {
        e.preventDefault();
        var id = $('#districtId').val();
        var url = id ? '/locations/districts/' + id : '{{ route('locations.store-district') }}';
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
              $('#districtModal').modal('hide');
              districtsTable.ajax.reload();
              toastr.success(response.message);
            }
          },
          error: function(xhr) {
            toastr.error(xhr.responseJSON?.message || '{{ __('common.messages.an_error_occurred') }}');
          }
        });
      });

      $('#districtsTable').on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        Swal.fire({
          title: '{{ __('common.general.are_you_sure') }}',
          text: '{{ __('common.general.this_action_cannot_be_undone') }}',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          confirmButtonText: '{{ __('common.general.yes_delete_it') }}'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: '/locations/districts/' + id,
              method: 'DELETE',
              headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
              },
              success: function(response) {
                if (response.success) {
                  districtsTable.ajax.reload();
                  toastr.success(response.message);
                }
              },
              error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || '{{ __('common.messages.an_error_occurred') }}');
              }
            });
          }
        });
      });

      $('#districtsTable').on('click', '.view-btn', function() {
        var id = $(this).data('id');
        $.get('/locations/districts/' + id, function(response) {
          if (response.success) {
            var data = response.data;
            var html = `
              <table class="table table-striped">
                <tr><th>ID</th><td>${data.id}</td></tr>
                <tr><th>{{ __('common.general.province') }}</th><td>${data.province?.name_en || '-'}</td></tr>
                <tr><th>District (EN)</th><td>${data.name_en || '-'}</td></tr>
                <tr><th>District (KH)</th><td>${data.name_kh || '-'}</td></tr>
              </table>
            `;
            $('#viewModalLabel').text('{{ __('common.general.district_details') }}');
            $('#viewModalBody').html(html);
            $('#viewModal').modal('show');
          }
        });
      });

      // Commune CRUD
      $('#addCommuneBtn').click(function() {
        $('#communeForm')[0].reset();
        $('#communeId').val('');
        $('#communeModalLabel').text('{{ __('common.general.add_commune') }}');
        loadProvinces('#communeProvinceId');
        $('#communeDistrictId').html('<option value="">{{ __('common.form.select_district') }}</option>');
        $('#communeModal').modal('show');
      });

      $('#communeProvinceId').change(function() {
        var provinceId = $(this).val();
        var districtSelect = $('#communeDistrictId');
        districtSelect.html('<option value="">Loading...</option>');
        if (provinceId) {
          $.get('/locations/districts/by-province/' + provinceId, function(data) {
            districtSelect.html('<option value="">{{ __('common.form.select_district') }}</option>');
            $.each(data, function(i, district) {
              districtSelect.append('<option value="' + district.id + '">' + district.name_en +
                '</option>');
            });
          });
        } else {
          districtSelect.html('<option value="">{{ __('common.form.select_district') }}</option>');
        }
      });

      $('#communesTable').on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        loadProvinces('#communeProvinceId');
        $.get('/locations/communes/' + id, function(response) {
          if (response.success) {
            var data = response.data;
            $('#communeId').val(data.id);
            if (data.district) {
              $('#communeProvinceId').val(data.district.province_id);
              $.get('/locations/districts/by-province/' + data.district.province_id, function(districts) {
                $('#communeDistrictId').html('<option value="">{{ __('common.form.select_district') }}</option>');
                $.each(districts, function(i, district) {
                  $('#communeDistrictId').append('<option value="' + district.id + '">' + district
                    .name_en + '</option>');
                });
                $('#communeDistrictId').val(data.district_id);
              });
            }
            $('#communeName').val(data.name_en);
            $('#communeNameKh').val(data.name_kh);
            $('#communeModalLabel').text('{{ __('common.general.edit_commune') }}');
            $('#communeModal').modal('show');
          }
        });
      });

      $('#communeForm').submit(function(e) {
        e.preventDefault();
        var id = $('#communeId').val();
        var url = id ? '/locations/communes/' + id : '{{ route('locations.store-commune') }}';
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
              $('#communeModal').modal('hide');
              communesTable.ajax.reload();
              toastr.success(response.message);
            }
          },
          error: function(xhr) {
            toastr.error(xhr.responseJSON?.message || '{{ __('common.messages.an_error_occurred') }}');
          }
        });
      });

      $('#communesTable').on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        Swal.fire({
          title: '{{ __('common.general.are_you_sure') }}',
          text: '{{ __('common.general.this_action_cannot_be_undone') }}',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          confirmButtonText: '{{ __('common.general.yes_delete_it') }}'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: '/locations/communes/' + id,
              method: 'DELETE',
              headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
              },
              success: function(response) {
                if (response.success) {
                  communesTable.ajax.reload();
                  toastr.success(response.message);
                }
              },
              error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || '{{ __('common.messages.an_error_occurred') }}');
              }
            });
          }
        });
      });

      $('#communesTable').on('click', '.view-btn', function() {
        var id = $(this).data('id');
        $.get('/locations/communes/' + id, function(response) {
          if (response.success) {
            var data = response.data;
            var html = `
              <table class="table table-striped">
                <tr><th>ID</th><td>${data.id}</td></tr>
                <tr><th>{{ __('common.general.province') }}</th><td>${data.district?.province?.name_en || '-'}</td></tr>
                <tr><th>{{ __('common.general.district') }}</th><td>${data.district?.name_en || '-'}</td></tr>
                <tr><th>Commune (EN)</th><td>${data.name_en || '-'}</td></tr>
                <tr><th>Commune (KH)</th><td>${data.name_kh || '-'}</td></tr>
              </table>
            `;
            $('#viewModalLabel').text('{{ __('common.general.commune_details') }}');
            $('#viewModalBody').html(html);
            $('#viewModal').modal('show');
          }
        });
      });

      // Village CRUD
      $('#addVillageBtn').click(function() {
        $('#villageForm')[0].reset();
        $('#villageId').val('');
        $('#villageModalLabel').text('{{ __('common.general.add_village') }}');
        loadProvinces('#villageProvinceId');
        $('#villageDistrictId').html('<option value="">{{ __('common.form.select_district') }}</option>');
        $('#villageCommuneId').html('<option value="">{{ __('common.form.select_commune') }}</option>');
        $('#villageModal').modal('show');
      });

      $('#villageProvinceId').change(function() {
        var provinceId = $(this).val();
        var districtSelect = $('#villageDistrictId');
        districtSelect.html('<option value="">Loading...</option>');
        $('#villageCommuneId').html('<option value="">{{ __('common.form.select_commune') }}</option>');
        if (provinceId) {
          $.get('/locations/districts/by-province/' + provinceId, function(data) {
            districtSelect.html('<option value="">{{ __('common.form.select_district') }}</option>');
            $.each(data, function(i, district) {
              districtSelect.append('<option value="' + district.id + '">' + district.name_en +
                '</option>');
            });
          });
        } else {
          districtSelect.html('<option value="">{{ __('common.form.select_district') }}</option>');
        }
      });

      $('#villageDistrictId').change(function() {
        var districtId = $(this).val();
        var communeSelect = $('#villageCommuneId');
        communeSelect.html('<option value="">Loading...</option>');
        if (districtId) {
          $.get('/locations/communes/by-district/' + districtId, function(data) {
            communeSelect.html('<option value="">{{ __('common.form.select_commune') }}</option>');
            $.each(data, function(i, commune) {
              communeSelect.append('<option value="' + commune.id + '">' + commune.name_en +
                '</option>');
            });
          });
        } else {
          communeSelect.html('<option value="">{{ __('common.form.select_commune') }}</option>');
        }
      });

      $('#villagesTable').on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        loadProvinces('#villageProvinceId');
        $.get('/locations/villages/' + id, function(response) {
          if (response.success) {
            var data = response.data;
            $('#villageId').val(data.id);
            if (data.commune && data.commune.district) {
              var provinceId = data.commune.district.province_id;
              var districtId = data.commune.district_id;
              $('#villageProvinceId').val(provinceId);

              $.get('/locations/districts/by-province/' + provinceId, function(districts) {
                $('#villageDistrictId').html('<option value="">{{ __('common.form.select_district') }}</option>');
                $.each(districts, function(i, district) {
                  $('#villageDistrictId').append('<option value="' + district.id + '">' + district
                    .name_en + '</option>');
                });
                $('#villageDistrictId').val(districtId);

                $.get('/locations/communes/by-district/' + districtId, function(communes) {
                  $('#villageCommuneId').html(
                    '<option value="">{{ __('common.form.select_commune') }}</option>');
                  $.each(communes, function(i, commune) {
                    $('#villageCommuneId').append('<option value="' + commune.id + '">' +
                      commune.name_en + '</option>');
                  });
                  $('#villageCommuneId').val(data.commune_id);
                });
              });
            }
            $('#villageName').val(data.name_en);
            $('#villageNameKh').val(data.name_kh);
            $('#villageModalLabel').text('{{ __('common.general.edit_village') }}');
            $('#villageModal').modal('show');
          }
        });
      });

      $('#villageForm').submit(function(e) {
        e.preventDefault();
        var id = $('#villageId').val();
        var url = id ? '/locations/villages/' + id : '{{ route('locations.store-village') }}';
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
              $('#villageModal').modal('hide');
              villagesTable.ajax.reload();
              toastr.success(response.message);
            }
          },
          error: function(xhr) {
            toastr.error(xhr.responseJSON?.message || '{{ __('common.messages.an_error_occurred') }}');
          }
        });
      });

      $('#villagesTable').on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        Swal.fire({
          title: '{{ __('common.general.are_you_sure') }}',
          text: '{{ __('common.general.this_action_cannot_be_undone') }}',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          confirmButtonText: '{{ __('common.general.yes_delete_it') }}'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: '/locations/villages/' + id,
              method: 'DELETE',
              headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
              },
              success: function(response) {
                if (response.success) {
                  villagesTable.ajax.reload();
                  toastr.success(response.message);
                }
              },
              error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || '{{ __('common.messages.an_error_occurred') }}');
              }
            });
          }
        });
      });

      $('#villagesTable').on('click', '.view-btn', function() {
        var id = $(this).data('id');
        $.get('/locations/villages/' + id, function(response) {
          if (response.success) {
            var data = response.data;
            var html = `
              <table class="table table-striped">
                <tr><th>ID</th><td>${data.id}</td></tr>
                <tr><th>{{ __('common.general.province') }}</th><td>${data.commune?.district?.province?.name_en || '-'}</td></tr>
                <tr><th>{{ __('common.general.district') }}</th><td>${data.commune?.district?.name_en || '-'}</td></tr>
                <tr><th>{{ __('common.general.commune') }}</th><td>${data.commune?.name_en || '-'}</td></tr>
                <tr><th>Village (EN)</th><td>${data.name_en || '-'}</td></tr>
                <tr><th>Village (KH)</th><td>${data.name_kh || '-'}</td></tr>
              </table>
            `;
            $('#viewModalLabel').text('{{ __('common.general.village_details') }}');
            $('#viewModalBody').html(html);
            $('#viewModal').modal('show');
          }
        });
      });
    });
  </script>
@endpush
