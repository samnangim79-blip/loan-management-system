@extends('admin.layouts.admin_layout')

@section('pageTitle', __('common.customer_management.title'))

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">{{ __('common.nav.customers') }}</a></li>
  <li class="breadcrumb-item active">{{ __('common.general.list') }}</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">{{ __('common.customer_management.customer_list') }}</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-primary btn-sm" id="addCustomerBtn">
              <i class="fas fa-plus"></i> {{ __('common.customer_management.add_customer') }}
            </button>
          </div>
        </div>
        <div class="card-body">
          <table id="customersTable" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>{{ __('common.general.id') }}</th>
                <th>{{ __('common.customer_management.id_no') }}</th>
                <th>{{ __('common.form.name') }}</th>
                <th>{{ __('common.customer_management.gender') }}</th>
                <th>{{ __('common.customer_management.dob') }}</th>
                <th>{{ __('common.form.contact') }}</th>
                <th>{{ __('common.customer_management.address') }}</th>
                <th>{{ __('common.general.action') }}</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Customer Modal -->
  <div class="modal fade" id="customerModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
      <div class="modal-content d-flex flex-column" style="height: 100vh;">
        <div class="modal-header flex-shrink-0">
          <h5 class="modal-title" id="modalTitle">{{ __('common.customer_management.add_customer') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form id="customerForm" class="d-flex flex-column flex-grow-1 overflow-hidden">
          <div class="modal-body flex-grow-1 overflow-auto">
            <input type="hidden" id="cust_id" name="cust_id">
            <div class="row">
              <div class="col-lg-8">
                <!-- Personal Information -->
                <div class="card mb-4">
                  <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('common.messages.personal_information') }}</h5>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="cid" class="form-label">{{ __('common.customer_management.cid') }}</label>
                        <input type="text" class="form-control" id="cid" name="cid" maxlength="30" disabled>
                        @error('cid')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="id_no" class="form-label">{{ __('common.customer_management.id_no') }} <span
                            class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="id_no" name="id_no" maxlength="30" required>
                        @error('id_no')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="name_en" class="form-label">{{ __('common.customer_management.name_english') }}
                          <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name_en') is-invalid @enderror" id="name_en"
                          name="name_en" value="{{ old('name_en') }}" required>
                        @error('name_en')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>

                      <div class="col-md-6 mb-3">
                        <label for="name_kh"
                          class="form-label">{{ __('common.customer_management.name_khmer') }}</label>
                        <input type="text" class="form-control @error('name_kh') is-invalid @enderror" id="name_kh"
                          name="name_kh" value="{{ old('name_kh') }}">
                        @error('name_kh')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-3 mb-3">
                        <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                        <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender"
                          required>
                          <option value="">{{ __('common.customer_management.select_gender') }}</option>
                          <option value="M">{{ __('common.customer_management.male') }}</option>
                          <option value="F">{{ __('common.customer_management.female') }}</option>
                        </select>
                      </div>

                      <div class="col-md-3 mb-3">
                        <label for="marital_status" class="form-label">Marital Status <span
                            class="text-danger">*</span></label>
                        <select class="form-select @error('marital_status') is-invalid @enderror" id="marital_status"
                          name="marital_status" required>
                          <option value="">{{ __('common.customer_management.select_status') }}</option>
                          <option value="0">{{ __('common.customer_management.single') }}</option>
                          <option value="1">{{ __('common.customer_management.married') }}</option>
                        </select>
                        @error('marital_status')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>

                      <div class="col-md-3 mb-3">
                        <label for="dob"
                          class="form-label">{{ __('common.customer_management.date_of_birth') }}<span
                            class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('dob') is-invalid @enderror" id="dob"
                          name="dob" value="{{ old('dob') }}" required>
                        @error('dob')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>

                      <div class="col-md-3 mb-3">
                        <label for="nationality_id"
                          class="form-label">{{ __('common.customer_management.nationality') }}</label>
                        <select id="nationality_id" name="nationality_id"
                          class="form-control @error('nationality_id') is-invalid @enderror">
                          <option value="">{{ __('common.customer_management.select_nationality') }}</option>
                        </select>
                        @error('nationality_id')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="pob"
                          class="form-label">{{ __('common.customer_management.place_of_birth') }}</label>
                        <input type="text" class="form-control @error('pob') is-invalid @enderror" id="pob"
                          name="pob" value="{{ old('pob') }}">
                        @error('pob')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>

                      <div class="col-md-6 mb-3">
                        <label for="occupation"
                          class="form-label">{{ __('common.customer_management.occupation') }}</label>
                        <input type="text" class="form-control @error('occupation') is-invalid @enderror"
                          id="occupation" name="occupation" value="{{ old('occupation') }}">
                        @error('occupation')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Contact Information -->
                <div class="card mb-4">
                  <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('common.messages.contact_information') }}</h5>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-3 mb-3">
                        <label for="phone1" class="form-label">{{ __('common.customer_management.phone1') }}<span
                            class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('phone1') is-invalid @enderror" id="phone1"
                          name="phone1" value="{{ old('phone1') }}" required>
                        @error('phone1')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>

                      <div class="col-md-3 mb-3">
                        <label for="phone2" class="form-label">{{ __('common.customer_management.phone2') }}</label>
                        <input type="text" class="form-control @error('phone2') is-invalid @enderror" id="phone2"
                          name="phone2" value="{{ old('phone2') }}">
                        @error('phone2')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                      <div class="col-md-3 mb-3">
                        <label for="phone3" class="form-label">{{ __('common.customer_management.phone3') }}</label>
                        <input type="text" class="form-control @error('phone3') is-invalid @enderror"
                          id="phone3" name="phone3" value="{{ old('phone3') }}">
                        @error('phone3')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>

                      <div class="col-md-3 mb-3">
                        <label for="email" class="form-label">{{ __('common.customer_management.email') }}</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                          name="email" value="{{ old('email') }}">
                        @error('email')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-9 mb-3">
                        <label for="address"
                          class="form-label">{{ __('common.customer_management.address_information') }} <span
                            class="text-danger">*</span></label>
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2"
                          required>{{ old('address') }}</textarea>
                        @error('address')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                      <div class="col-md-3 mb-3">
                        <label for="country_id" class="form-label">{{ __('common.customer_management.country') }} <span
                            class="text-danger">*</span></label>
                        <select class="form-select @error('country_id') is-invalid @enderror" id="country_id"
                          name="country_id" required>
                          <option value="">{{ __('common.customer_management.select_country') }}</option>
                        </select>
                        @error('country_id')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-3 mb-3">
                        <label for="province_id" class="form-label">{{ __('common.customer_management.province') }}
                          <span class="text-danger">*</span></label>
                        <select id="province_id" name="province_id"
                          class="form-control @error('province_id') is-invalid @enderror" disabled required>
                          <option value="">{{ __('common.form.select_province') }}</option>
                        </select>
                        @error('province_id')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>

                      <div class="col-md-3 mb-3">
                        <label for="district_id" class="form-label">{{ __('common.customer_management.district') }}
                          <span class="text-danger">*</span></label>
                        <select id="district_id" name="district_id"
                          class="form-control @error('district_id') is-invalid @enderror" disabled required>
                          <option value="">{{ __('common.form.select_district') }}</option>
                        </select>
                        @error('district_id')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>

                      <div class="col-md-3 mb-3">
                        <label for="commune_id" class="form-label">{{ __('common.customer_management.commune') }} <span
                            class="text-danger">*</span></label>
                        <select id="commune_id" name="commune_id"
                          class="form-control @error('commune_id') is-invalid @enderror" disabled required>
                          <option value="">{{ __('common.form.select_commune') }}</option>
                        </select>
                        @error('commune_id')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>

                      <div class="col-md-3 mb-3">
                        <label for="village_id" class="form-label">{{ __('common.customer_management.village') }} <span
                            class="text-danger">*</span></label>
                        <select id="village_id" name="village_id"
                          class="form-select @error('village_id') is-invalid @enderror" disabled required>
                          <option value="">{{ __('common.form.select_village') }}</option>
                        </select>
                        @error('village_id')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <!-- Spouse Information (for married customers) -->
                <div class="card mb-4">
                  <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('common.messages.spouse_information') }}</h5>
                  </div>
                  <div class="card-body">
                    <div id="spouseSection" style="display: none;">
                      <div class="row">
                        <div class="col-md-6 mb-3">
                          <label for="spouse_name_en"
                            class="form-label">{{ __('common.customer_management.spouse_name') }}
                            (English)</label>
                          <input type="text" class="form-control @error('spouse_name_en') is-invalid @enderror"
                            id="spouse_name_en" name="spouse_name_en" value="{{ old('spouse_name_en') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                          <label for="spouse_name_kh"
                            class="form-label">{{ __('common.customer_management.spouse_name') }}
                            (Khmer)</label>
                          <input type="text" class="form-control @error('spouse_name_kh') is-invalid @enderror"
                            id="spouse_name_kh" name="spouse_name_kh" value="{{ old('spouse_name_kh') }}">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6 mb-3">
                          <label for="spouse_dob"
                            class="form-label">{{ __('common.customer_management.spouse_date_of_birth') }}</label>
                          <input type="text" class="form-control @error('spouse_dob') is-invalid @enderror"
                            id="spouse_dob" name="spouse_dob" value="{{ old('spouse_dob') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                          <label for="spouse_id_no"
                            class="form-label">{{ __('common.customer_management.spouse_id_no') }}</label>
                          <input type="text" class="form-control @error('spouse_id_no') is-invalid @enderror"
                            id="spouse_id_no" name="spouse_id_no" value="{{ old('spouse_id_no') }}">
                          @error('spouse_id_no')
                            <div class="invalid-feedback">{{ $message }}</div>
                          @enderror
                        </div>

                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="guarantor_id_no"
                          class="form-label">{{ __('common.customer_management.guarantor_id_no') }}</label>
                        <input type="text" class="form-control @error('guarantor_id_no') is-invalid @enderror"
                          id="guarantor_id_no" name="guarantor_id_no" value="{{ old('guarantor_id_no') }}">
                        @error('guarantor_id_no')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="guarantor_dob"
                          class="form-label">{{ __('common.customer_management.guarantor_dob') }}</label>
                        <input type="text" class="form-control @error('guarantor_dob') is-invalid @enderror"
                          id="guarantor_dob" name="guarantor_dob" value="{{ old('guarantor_dob') }}">
                        @error('guarantor_dob')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="guarantor_name_en"
                          class="form-label">{{ __('common.customer_management.guarantor_name') }} (English)</label>
                        <input type="text" class="form-control @error('guarantor_name_en') is-invalid @enderror"
                          id="guarantor_name_en" name="guarantor_name_en" value="{{ old('guarantor_name_en') }}">
                        @error('guarantor_name_en')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="guarantor_name_kh"
                          class="form-label">{{ __('common.customer_management.guarantor_name') }} (Khmer)</label>
                        <input type="text" class="form-control @error('guarantor_name_kh') is-invalid @enderror"
                          id="guarantor_name_kh" name="guarantor_name_kh" value="{{ old('guarantor_name_kh') }}">
                        @error('guarantor_name_kh')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12 mb-3">
                        <label for="family_book"
                          class="form-label">{{ __('common.customer_management.family_book') }}</label>
                        <select class="form-select @error('family_book') is-invalid @enderror" id="family_book"
                          name="family_book">
                          <option value="">{{ __('common.customer_management.select_family_book') }}</option>
                          <option value="family_book">{{ __('common.customer_management.family_book') }}</option>
                          <option value="residence_book">{{ __('common.customer_management.residence_book') }}</option>
                          <option value="passport">{{ __('common.customer_management.passport') }}</option>
                        </select>
                        @error('family_book')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Customer Photos & Documents -->
                <div class="card mb-4">
                  <div class="card-header">
                    <h5 class="card-title mb-0">Photos & Documents</h5>
                  </div>
                  <div class="card-body">
                    <div id="customerPhotoUploader"></div>
                  </div>
                </div>

              </div>
            </div>
          </div>
          <div class="modal-footer flex-shrink-0">
            <button type="button" class="btn btn-secondary"
              data-bs-dismiss="modal">{{ __('common.general.cancel') }}</button>
            <button type="submit"
              class="btn btn-primary">{{ __('common.customer_management.save_customer') }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>

@endsection

@push('scripts')
  <!-- Include Panha Media Upload Preview Library -->
  <script src="{{ asset('assets/backend/js/panha-media-upload-preview.js') }}"></script>

  <script>
    $(document).ready(function() {
      // Show/hide spouse section based on marital status
      document.getElementById('marital_status').addEventListener('change', function() {
        const spouseSection = document.getElementById('spouseSection');
        if (this.value === '1') { // Married
          spouseSection.style.display = 'block';
        } else {
          spouseSection.style.display = 'none';
          // Clear spouse fields when hidden
          document.getElementById('spouse_name_en').value = '';
          document.getElementById('spouse_name_kh').value = '';
          document.getElementById('spouse_dob').value = '';
        }
      });

      // Track deleted photo IDs
      let deletedPhotoIds = [];

      // Initialize Customer Photo Uploader (supports multiple files)
      const customerPhotoUploader = new PanhaMediaUploadPreview('#customerPhotoUploader', {
        maxFiles: 8,
        maxFileSize: 5 * 1024 * 1024, // 5MB
        allowedTypes: ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'application/pdf', '.pdf', '.doc', '.docx'],
        fieldName: 'customer_photos[]',
        showFileInfo: true,
        enableDragDrop: true,
        texts: {
          title: 'Photos & Documents',
          uploadText: 'Drop photos/documents here',
          uploadSubtext: 'Max 8 files, 5MB each (JPG, PNG, WEBP, PDF, DOC, DOCX)',
          addMoreText: '{{ __('common.actions.add_more') }}',
          clearAllText: '{{ __('common.actions.clear_all') }}',
          confirmClearText: 'Are you sure you want to remove all files?',
          fileNotImageError: 'File "{filename}" is not allowed.',
          fileTooLargeError: 'File size exceeds 5MB limit'
        },
        callbacks: {
          onFileRemove: function(removedFile) {
            // If the removed file has an id (existing file), track it for deletion
            if (removedFile && removedFile.id && removedFile.isExisting) {
              deletedPhotoIds.push(removedFile.id);
              console.log('Marked photo for deletion:', removedFile.id);
            }
          }
        }
      });

      // Initialize Flatpickr for date fields
      flatpickr('#dob', {
        dateFormat: 'Y-m-d',
        maxDate: 'today',
        allowInput: true
      });

      flatpickr('#spouse_dob', {
        dateFormat: 'Y-m-d',
        maxDate: 'today',
        allowInput: true
      });

      flatpickr('#guarantor_dob', {
        dateFormat: 'Y-m-d',
        maxDate: 'today',
        allowInput: true
      });

      // Load Countries
      function loadCountries() {
        $.ajax({
          url: '{{ route('locations.countries-all') }}',
          method: 'GET',
          success: function(response) {
            const countrySelect = $('#country_id');
            countrySelect.empty().append(
              '<option value="">{{ __('common.customer_management.select_country') }}</option>');
            response.data.forEach(function(country) {
              countrySelect.append(`<option value="${country.country_id}">${country.country}</option>`);
            });
          },
          error: function(xhr) {
            console.error('Failed to load countries:', xhr);
          }
        });
      }

      // Load Nationalities
      function loadNationalities() {
        $.ajax({
          url: '{{ route('nationalities.all') }}',
          method: 'GET',
          success: function(response) {
            const nationalitySelect = $('#nationality_id');
            nationalitySelect.empty().append(
              '<option value="">{{ __('common.customer_management.select_nationality') }}</option>');
            response.data.forEach(function(nationality) {
              nationalitySelect.append(
                `<option value="${nationality.nationality_id}">${nationality.nationality}</option>`);
            });
          },
          error: function(xhr) {
            console.error('Failed to load nationalities:', xhr);
          }
        });
      }

      // Load Provinces by Country
      function loadProvinces(countryId) {
        if (!countryId) {
          $('#province_id').empty().append('<option value="">{{ __('common.form.select_province') }}</option>')
            .prop('disabled', true);
          return;
        }

        $.ajax({
          url: `/locations/provinces/by-country/${countryId}`,
          method: 'GET',
          success: function(response) {
            const provinceSelect = $('#province_id');
            provinceSelect.empty().append(
              '<option value="">{{ __('common.form.select_province') }}</option>');

            response.data.forEach(function(province) {
              provinceSelect.append(`<option value="${province.id}">${province.name_en}</option>`);
            });
            provinceSelect.prop('disabled', false);
          },
          error: function(xhr) {
            console.error('Failed to load provinces:', xhr);
          }
        });
      }

      // Load Districts by Province
      function loadDistricts(provinceId) {
        if (!provinceId) {
          $('#district_id').empty().append('<option value="">{{ __('common.form.select_district') }}</option>')
            .prop('disabled', true);
          $('#commune_id').empty().append('<option value="">{{ __('common.form.select_commune') }}</option>').prop(
            'disabled', true);
          $('#village_id').empty().append('<option value="">{{ __('common.form.select_village') }}</option>').prop(
            'disabled', true);
          return;
        }

        $.ajax({
          url: `/locations/districts/by-province/${provinceId}`,
          method: 'GET',
          success: function(response) {
            const districtSelect = $('#district_id');
            districtSelect.empty().append(
              '<option value="">{{ __('common.form.select_district') }}</option>');
            response.data.forEach(function(district) {
              districtSelect.append(`<option value="${district.id}">${district.name_en}</option>`);
            });
            districtSelect.prop('disabled', false);
          },
          error: function(xhr) {
            console.error('Failed to load districts:', xhr);
          }
        });
      }

      // Load Communes by District
      function loadCommunes(districtId) {
        if (!districtId) {
          $('#commune_id').empty().append('<option value="">{{ __('common.form.select_commune') }}</option>').prop(
            'disabled', true);
          $('#village_id').empty().append('<option value="">{{ __('common.form.select_village') }}</option>').prop(
            'disabled', true);
          return;
        }

        $.ajax({
          url: `/locations/communes/by-district/${districtId}`,
          method: 'GET',
          success: function(response) {
            const communeSelect = $('#commune_id');
            communeSelect.empty().append('<option value="">{{ __('common.form.select_commune') }}</option>');
            response.data.forEach(function(commune) {
              communeSelect.append(`<option value="${commune.id}">${commune.name_en}</option>`);
            });
            communeSelect.prop('disabled', false);
          },
          error: function(xhr) {
            console.error('Failed to load communes:', xhr);
          }
        });
      }

      // Load Villages by Commune
      function loadVillages(communeId) {
        if (!communeId) {
          $('#village_id').empty().append('<option value="">{{ __('common.form.select_village') }}</option>').prop(
            'disabled', true);
          return;
        }

        $.ajax({
          url: `/locations/villages/by-commune/${communeId}`,
          method: 'GET',
          success: function(response) {
            const villageSelect = $('#village_id');
            villageSelect.empty().append('<option value="">{{ __('common.form.select_village') }}</option>');
            response.data.forEach(function(village) {
              villageSelect.append(`<option value="${village.id}">${village.name_en}</option>`);
            });
            villageSelect.prop('disabled', false);
          },
          error: function(xhr) {
            console.error('Failed to load villages:', xhr);
          }
        });
      }

      // Cascading Dropdown Event Handlers
      $('#country_id').on('change', function() {
        const countryId = $(this).val();
        loadProvinces(countryId);
        // Reset dependent dropdowns
        $('#district_id').empty().append('<option value="">{{ __('common.form.select_district') }}</option>')
          .prop('disabled', true);
        $('#commune_id').empty().append('<option value="">{{ __('common.form.select_commune') }}</option>')
          .prop('disabled', true);
        $('#village_id').empty().append('<option value="">{{ __('common.form.select_village') }}</option>')
          .prop('disabled', true);
      });

      $('#province_id').on('change', function() {
        const provinceId = $(this).val();
        loadDistricts(provinceId);
      });

      $('#district_id').on('change', function() {
        const districtId = $(this).val();
        loadCommunes(districtId);
      });

      $('#commune_id').on('change', function() {
        const communeId = $(this).val();
        loadVillages(communeId);
      });

      // Load initial data
      loadCountries();
      loadNationalities();

      // Translation variables
      const translations = {
        addCustomer: '{{ __('common.customer_management.add_customer') }}',
        editCustomer: '{{ __('common.customer_management.edit_customer') }}',
        operationCompleted: '{{ __('common.customer_management.operation_completed') }}',
        errorOccurred: '{{ __('common.customer_management.error_occurred') }}',
        failedLoadCustomer: '{{ __('common.customer_management.failed_load_customer') }}',
        areYouSure: '{{ __('common.customer_management.are_you_sure') }}',
        cannotRevert: '{{ __('common.customer_management.cannot_revert') }}',
        yesDelete: '{{ __('common.customer_management.yes_delete') }}',
        deleted: '{{ __('common.customer_management.deleted') }}',
        error: '{{ __('common.customer_management.error') }}',
        unknownError: '{{ __('common.general.unknown_error') }}'
      };
      // Initialize DataTable
      var table = $('#customersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('customers.data') }}",
        columns: [{
            data: 'cust_id',
            name: 'cust_id'
          },
          {
            data: 'id_no',
            name: 'id_no'
          },
          {
            data: 'full_name',
            name: 'name_en'
          },
          {
            data: 'gender',
            name: 'gender'
          },
          {
            data: 'dob',
            name: 'dob'
          },
          {
            data: 'contact',
            name: 'phone1'
          },
          {
            data: 'address',
            name: 'address'
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
        ],
        pageLength: 10,
        responsive: true
      });

      // Add Customer Button
      $('#addCustomerBtn').click(function() {
        $('#customerForm')[0].reset();
        customerPhotoUploader.clear(); // Clear photo uploader
        deletedPhotoIds = []; // Reset deleted photo IDs

        // Reset location dropdowns
        $('#province_id').empty().append('<option value="">{{ __('common.form.select_province') }}</option>')
          .prop('disabled', true);
        $('#district_id').empty().append('<option value="">{{ __('common.form.select_district') }}</option>')
          .prop('disabled', true);
        $('#commune_id').empty().append('<option value="">{{ __('common.form.select_commune') }}</option>')
          .prop('disabled', true);
        $('#village_id').empty().append('<option value="">{{ __('common.form.select_village') }}</option>')
          .prop('disabled', true);

        $('#cust_id').val('');
        $('#modalTitle').text(translations.addCustomer);
        $('#customerModal').modal('show');
      });

      // Handle form submission
      $('#customerForm').on('submit', function(e) {
        e.preventDefault();

        // Create FormData from form
        const formData = new FormData(this);

        // Get all uploaded photo files and append them to FormData
        const allFiles = customerPhotoUploader.getFiles();
        console.log('All files in uploader:', allFiles);

        // Filter to get only new files (not existing ones loaded from server)
        const newFiles = allFiles.filter(item => item.file !== null && !item.isExisting);
        console.log('New files to upload:', newFiles);

        if (newFiles && newFiles.length > 0) {
          // Remove any existing customer_photos entries
          formData.delete('customer_photos[]');
          formData.delete('customer_photos');

          // Add each file with array notation
          newFiles.forEach((item, index) => {
            console.log(`Adding file ${index}:`, item.file.name, item.file.size);
            formData.append('customer_photos[]', item.file);
          });
        }

        // Get customer ID for update vs create
        const custId = $('#cust_id').val();
        const url = custId ? `/customers/${custId}` : '{{ route('customers.store') }}';
        const method = custId ? 'PUT' : 'POST';

        if (custId) {
          formData.append('_method', 'PUT');

          // Add deleted photo IDs for update
          if (deletedPhotoIds.length > 0) {
            console.log('Deleted photo IDs:', deletedPhotoIds);
            deletedPhotoIds.forEach((id, index) => {
              formData.append(`deleted_photo_ids[${index}]`, id);
            });
          }
        }

        // Debug: Log FormData contents
        console.log('FormData contents:');
        for (let pair of formData.entries()) {
          console.log(pair[0] + ':', pair[1]);
        }

        // Submit via AJAX
        $.ajax({
          url: url,
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
            if (response.success) {
              $('#customerModal').modal('hide');
              table.ajax.reload();
              Swal.fire({
                icon: 'success',
                title: translations.operationCompleted,
                text: response.message
              });
            }
          },
          error: function(xhr) {
            console.error('AJAX Error:', xhr);
            let errorMessage = translations.unknownError;

            if (xhr.responseJSON) {
              if (xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
              }

              // Show validation errors if present
              if (xhr.responseJSON.errors) {
                let validationErrors = '';
                Object.keys(xhr.responseJSON.errors).forEach(key => {
                  validationErrors += xhr.responseJSON.errors[key].join('<br>') + '<br>';
                });
                errorMessage += '<br><br>' + validationErrors;
              }
            } else if (xhr.responseText) {
              console.error('Response text:', xhr.responseText);
            }

            Swal.fire({
              icon: 'error',
              title: translations.error,
              html: errorMessage
            });
          }
        });
      });

      // View Customer Button Handler (using event delegation)
      $('#customersTable').on('click', '.view-btn', function() {
        const custId = $(this).data('id');

        $.ajax({
          url: `/customers/${custId}/show`,
          method: 'GET',
          success: function(response) {
            const customer = response;

            // Build customer details HTML
            let detailsHtml = `
              <div class="text-start">
                <p><strong>ID:</strong> ${customer.cust_id}</p>
                <p><strong>ID No:</strong> ${customer.id_no || 'N/A'}</p>
                <p><strong>Name (EN):</strong> ${customer.name_en}</p>
                <p><strong>Name (KH):</strong> ${customer.name_kh || 'N/A'}</p>
                <p><strong>Gender:</strong> ${customer.gender === 'M' ? 'Male' : 'Female'}</p>
                <p><strong>Date of Birth:</strong> ${customer.dob}</p>
                <p><strong>Phone:</strong> ${customer.phone1}</p>
                <p><strong>Email:</strong> ${customer.email || 'N/A'}</p>
                <p><strong>Address:</strong> ${customer.address}</p>
              `;

            if (customer.village) {
              detailsHtml += `<p><strong>Village:</strong> ${customer.village.name_en}</p>`;
            }

            detailsHtml += `</div>`;

            Swal.fire({
              title: 'Customer Details',
              html: detailsHtml,
              width: 600,
              confirmButtonText: 'Close'
            });
          },
          error: function(xhr) {
            Swal.fire({
              icon: 'error',
              title: translations.error,
              text: translations.failedLoadCustomer
            });
          }
        });
      });

      // Edit Customer Button Handler (using event delegation)
      $('#customersTable').on('click', '.edit-btn', function() {
        const custId = $(this).data('id');

        $.ajax({
          url: `/customers/${custId}/edit`,
          method: 'GET',
          success: function(customer) {
            // Reset form first
            $('#customerForm')[0].reset();
            customerPhotoUploader.clear();
            deletedPhotoIds = []; // Reset deleted photo IDs

            // Set customer ID and modal title
            $('#cust_id').val(customer.cust_id);
            $('#modalTitle').text(translations.editCustomer);

            // Populate basic fields
            $('#cid').val(customer.cust_id);
            $('#id_no').val(customer.id_no);
            $('#name_en').val(customer.name_en);
            $('#name_kh').val(customer.name_kh);
            $('#gender').val(customer.gender);
            $('#marital_status').val(customer.marital_status);
            $('#dob').val(customer.dob);
            $('#pob').val(customer.pob);
            $('#phone1').val(customer.phone1);
            $('#phone2').val(customer.phone2);
            $('#phone3').val(customer.phone3);
            $('#email').val(customer.email);
            $('#address').val(customer.address);
            $('#occupation').val(customer.occupation);
            $('#nationality_id').val(customer.nationality_id);

            // Spouse fields
            if (customer.marital_status === 1) {
              $('#spouseSection').show();
            }
            $('#spouse_id_no').val(customer.spouse_id_no);
            $('#spouse_name_en').val(customer.spouse_name_en);
            $('#spouse_name_kh').val(customer.spouse_name_kh);
            $('#spouse_dob').val(customer.spouse_dob);

            // Guarantor fields
            $('#guarantor_id_no').val(customer.guarantor_id_no);
            $('#guarantor_name_en').val(customer.guarantor_name_en);
            $('#guarantor_name_kh').val(customer.guarantor_name_kh);
            $('#guarantor_dob').val(customer.guarantor_dob);
            $('#family_book').val(customer.family_book);

            // Load cascading location dropdowns with saved values
            // First load provinces for the selected country
            if (customer.country_id) {
              $.ajax({
                url: `/locations/provinces/by-country/${customer.country_id}`,
                method: 'GET',
                success: function(response) {
                  const provinceSelect = $('#province_id');
                  provinceSelect.empty().append(
                    '<option value="">{{ __('common.form.select_province') }}</option>');
                  response.data.forEach(function(province) {
                    provinceSelect.append(
                      `<option value="${province.id}">${province.name_en}</option>`);
                  });
                  provinceSelect.prop('disabled', false);
                  provinceSelect.val(customer.province_id);

                  // Then load districts for the selected province
                  if (customer.province_id) {
                    $.ajax({
                      url: `/locations/districts/by-province/${customer.province_id}`,
                      method: 'GET',
                      success: function(response) {
                        const districtSelect = $('#district_id');
                        districtSelect.empty().append(
                          '<option value="">{{ __('common.form.select_district') }}</option>'
                          );
                        response.data.forEach(function(district) {
                          districtSelect.append(
                            `<option value="${district.id}">${district.name_en}</option>`);
                        });
                        districtSelect.prop('disabled', false);
                        districtSelect.val(customer.district_id);

                        // Then load communes for the selected district
                        if (customer.district_id) {
                          $.ajax({
                            url: `/locations/communes/by-district/${customer.district_id}`,
                            method: 'GET',
                            success: function(response) {
                              const communeSelect = $('#commune_id');
                              communeSelect.empty().append(
                                '<option value="">{{ __('common.form.select_commune') }}</option>'
                                );
                              response.data.forEach(function(commune) {
                                communeSelect.append(
                                  `<option value="${commune.id}">${commune.name_en}</option>`
                                  );
                              });
                              communeSelect.prop('disabled', false);
                              communeSelect.val(customer.commune_id);

                              // Finally load villages for the selected commune
                              if (customer.commune_id) {
                                $.ajax({
                                  url: `/locations/villages/by-commune/${customer.commune_id}`,
                                  method: 'GET',
                                  success: function(response) {
                                    const villageSelect = $('#village_id');
                                    villageSelect.empty().append(
                                      '<option value="">{{ __('common.form.select_village') }}</option>'
                                      );
                                    response.data.forEach(function(village) {
                                      villageSelect.append(
                                        `<option value="${village.id}">${village.name_en}</option>`
                                        );
                                    });
                                    villageSelect.prop('disabled', false);
                                    villageSelect.val(customer.village_id);
                                  }
                                });
                              }
                            }
                          });
                        }
                      }
                    });
                  }
                }
              });

              // Set country value
              $('#country_id').val(customer.country_id);
            }

            // Load existing photos if available
            if (customer.photos && customer.photos.length > 0) {
              const existingPhotos = customer.photos.map(photo => ({
                url: photo.url,
                name: photo.name,
                size: photo.size || 0,
                id: photo.id,
                type: photo.type
              }));
              customerPhotoUploader.loadExistingFiles(existingPhotos);
            }

            // Show modal
            $('#customerModal').modal('show');
          },
          error: function(xhr) {
            Swal.fire({
              icon: 'error',
              title: translations.error,
              text: translations.failedLoadCustomer
            });
          }
        });
      });

      // Delete Customer Button Handler (using event delegation)
      $('#customersTable').on('click', '.delete-btn', function() {
        const custId = $(this).data('id');

        Swal.fire({
          title: translations.areYouSure,
          text: translations.cannotRevert,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: translations.yesDelete,
          cancelButtonText: '{{ __('common.general.cancel') }}'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: `/customers/${custId}`,
              method: 'DELETE',
              data: {
                _token: '{{ csrf_token() }}'
              },
              success: function(response) {
                if (response.success) {
                  table.ajax.reload();
                  Swal.fire({
                    icon: 'success',
                    title: translations.deleted,
                    text: response.message
                  });
                }
              },
              error: function(xhr) {
                let errorMessage = translations.unknownError;
                if (xhr.responseJSON && xhr.responseJSON.message) {
                  errorMessage = xhr.responseJSON.message;
                }
                Swal.fire({
                  icon: 'error',
                  title: translations.error,
                  text: errorMessage
                });
              }
            });
          }
        });
      });
    });
  </script>
@endpush
