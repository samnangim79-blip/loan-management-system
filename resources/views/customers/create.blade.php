@extends('admin.layouts.admin_layout')

@section('pageTitle', __('customers.add_new_customer'))

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">{{ __('nav.customers') }}</a></li>
  <li class="breadcrumb-item active">{{ __('common.add_new') }}</li>
@endsection

@section('content')
  <form id="customerForm" action="{{ route('customers.store') }}" method="POST">
    @csrf

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
                <label for="ic_no" class="form-label">{{ __('customers.ic_passport_number') }} <span
                    class="text-danger">*</span></label>
                <input type="text" class="form-control @error('ic_no') is-invalid @enderror" id="ic_no"
                  name="ic_no" value="{{ old('ic_no') }}" required>
                @error('ic_no')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6 mb-3">
                <label for="id_no" class="form-label">{{ __('common.general.id_number') }}</label>
                <input type="text" class="form-control @error('id_no') is-invalid @enderror" id="id_no"
                  name="id_no" value="{{ old('id_no') }}">
                @error('id_no')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="name_en" class="form-label">{{ __('customers.name_english') }} <span
                    class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name_en') is-invalid @enderror" id="name_en"
                  name="name_en" value="{{ old('name_en') }}" required>
                @error('name_en')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6 mb-3">
                <label for="name_kh" class="form-label">Name (Khmer)</label>
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
                <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                  <option value="">{{ __('common.form.select_gender') }}</option>
                  <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}>Male</option>
                  <option value="F" {{ old('gender') == 'F' ? 'selected' : '' }}>Female</option>
                </select>
                @error('gender')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-3 mb-3">
                <label for="marital_status" class="form-label">Marital Status <span class="text-danger">*</span></label>
                <select class="form-select @error('marital_status') is-invalid @enderror" id="marital_status"
                  name="marital_status" required>
                  <option value="">{{ __('common.general.select_status') }}</option>
                  <option value="0" {{ old('marital_status') == '0' ? 'selected' : '' }}>Single</option>
                  <option value="1" {{ old('marital_status') == '1' ? 'selected' : '' }}>Married</option>
                </select>
                @error('marital_status')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-3 mb-3">
                <label for="dob" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('dob') is-invalid @enderror" id="dob"
                  name="dob" value="{{ old('dob') }}" required>
                @error('dob')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-3 mb-3">
                <label for="nationality_id" class="form-label">{{ __('common.form.nationality') }}</label>
                <select id="nationality_id" name="nationality_id"
                  class="form-control @error('nationality_id') is-invalid @enderror">
                  <option value="">{{ __('common.form.select_nationality') }}</option>
                </select>
                @error('nationality_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="pob" class="form-label">{{ __('common.pagination.place_of_birth') }}</label>
                <input type="text" class="form-control @error('pob') is-invalid @enderror" id="pob"
                  name="pob" value="{{ old('pob') }}">
                @error('pob')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6 mb-3">
                <label for="occupation" class="form-label">{{ __('common.form.occupation') }}</label>
                <input type="text" class="form-control @error('occupation') is-invalid @enderror" id="occupation"
                  name="occupation" value="{{ old('occupation') }}">
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
              <div class="col-md-4 mb-3">
                <label for="phone1" class="form-label">Primary Phone <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('phone1') is-invalid @enderror" id="phone1"
                  name="phone1" value="{{ old('phone1') }}" required>
                @error('phone1')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-4 mb-3">
                <label for="phone2" class="form-label">{{ __('common.general.secondary_phone') }}</label>
                <input type="text" class="form-control @error('phone2') is-invalid @enderror" id="phone2"
                  name="phone2" value="{{ old('phone2') }}">
                @error('phone2')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-4 mb-3">
                <label for="email" class="form-label">{{ __('common.general.email_address') }}</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                  name="email" value="{{ old('email') }}">
                @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="row">
              <div class="col-12 mb-3">
                <label for="address" class="form-label">Street Address <span class="text-danger">*</span></label>
                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2"
                  required>{{ old('address') }}</textarea>
                @error('address')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="row">
              <div class="col-md-3 mb-3">
                <label for="province" class="form-label">Province <span class="text-danger">*</span></label>
                <select id="" class="form-control">
                  <option value="">{{ __('common.form.select_province') }}</option>
                </select>
              </div>

              <div class="col-md-3 mb-3">
                <label for="district" class="form-label">District <span class="text-danger">*</span></label>
                <select id="" class="form-control" disabled>
                  <option value="">{{ __('common.form.select_district') }}</option>
                </select>
              </div>

              <div class="col-md-3 mb-3">
                <label for="commune" class="form-label">Commune <span class="text-danger">*</span></label>
                <select id="" class="form-control" disabled>
                  <option value="">{{ __('common.form.select_commune') }}</option>
                </select>
              </div>

              <div class="col-md-3 mb-3">
                <label for="village_id" class="form-label">Village <span class="text-danger">*</span></label>
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

        <!-- Spouse Information (for married customers) -->
        <div class="card mb-4" id="spouseSection" style="display: none;">
          <div class="card-header">
            <h5 class="card-title mb-0">{{ __('common.messages.spouse_information') }}</h5>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="spouse_en" class="form-label">Spouse Name (English)</label>
                <input type="text" class="form-control @error('spouse_en') is-invalid @enderror" id="spouse_en"
                  name="spouse_en" value="{{ old('spouse_en') }}">
                @error('spouse_en')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6 mb-3">
                <label for="spouse_kh" class="form-label">Spouse Name (Khmer)</label>
                <input type="text" class="form-control @error('spouse_kh') is-invalid @enderror" id="spouse_kh"
                  name="spouse_kh" value="{{ old('spouse_kh') }}">
                @error('spouse_kh')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="spouse_dob" class="form-label">{{ __('common.general.spouse_date_of_birth') }}</label>
                <input type="text" class="form-control @error('spouse_dob') is-invalid @enderror" id="spouse_dob"
                  name="spouse_dob" value="{{ old('spouse_dob') }}">
                @error('spouse_dob')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <!-- Actions -->
        <div class="card mb-4">
          <div class="card-header">
            <h5 class="card-title mb-0">{{ __('common.general.actions') }}</h5>
          </div>
          <div class="">
            <button type="submit" class="btn btn-primary w-100 mb-2">
              <i class="fas fa-save me-2"></i> Save Customer
            </button>
            <button type="button" class="btn btn-secondary w-100"
              onclick="window.location.href='{{ route('dashboard') }}'">
              <i class="fas fa-times me-2"></i> Cancel
            </button>
          </div>
        </div>

        <!-- Additional Notes -->
        <div class="card">
          <div class="card-header">
            <h5 class="card-title mb-0">{{ __('common.general.additional_notes') }}</h5>
          </div>
          <div class="">
            <div class="mb-3">
              <label for="remark" class="form-label">{{ __('common.general.remarks') }}</label>
              <textarea class="form-control @error('remark') is-invalid @enderror" id="remark" name="remark" rows="4">{{ old('remark') }}</textarea>
              @error('remark')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="staff_id" class="form-label">{{ __('common.form.assigned_staff') }}</label>
              <select id="staff_id" name="staff_id" class="form-control @error('staff_id') is-invalid @enderror">
                <option value="">{{ __('common.form.select_staff') }}</option>
                @foreach (\App\Models\Staff::all() as $staff)
                  <option value="{{ $staff->staff_id }}" {{ old('staff_id') == $staff->staff_id ? 'selected' : '' }}>
                    {{ $staff->full_name }}
                  </option>
                @endforeach
              </select>
              @error('staff_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
@endsection

@push('scripts')
  <script>
    $(document).ready(function() {
      // Initialize Flatpickr for date fields
      flatpickr("#dob", {
        dateFormat: "Y-m-d",
        maxDate: "today",
        yearRange: [1920, new Date().getFullYear()]
      });

      flatpickr("#spouse_dob", {
        dateFormat: "Y-m-d",
        maxDate: "today",
        yearRange: [1920, new Date().getFullYear()]
      });

      // Initialize Tom Select for nationality
      new TomSelect('#nationality_id', {
        valueField: 'nationality_id',
        labelField: 'nationality',
        searchField: ['nationality', 'nationality_kh'],
        preload: true,
        load: function(query, callback) {
          $.ajax({
            url: '/api/nationalities',
            type: 'GET',
            success: function(res) {
              callback(res);
            }
          });
        }
      });

      // Initialize Tom Select for staff
      new TomSelect('#staff_id', {
        placeholder: 'Select Staff Member'
      });

      // Show/hide spouse section based on marital status
      $('#marital_status').change(function() {
        if ($(this).val() == '1') {
          $('#spouseSection').slideDown();
        } else {
          $('#spouseSection').slideUp();
        }
      });

      // Load provinces
      $.ajax({
        url: '/api/provinces',
        type: 'GET',
        success: function(data) {
          $('#province').empty().append('<option value="">{{ __('common.form.select_province') }}</option>');
          $.each(data, function(index, province) {
            $('#province').append($('<option>', {
              value: province.id,
              text: province.name_en + ' - ' + province.name_kh
            }));
          });
        }
      });

      // Province change event
      $('#province').change(function() {
        var provinceId = $(this).val();
        $('#district').prop('disabled', !provinceId).empty().append(
          '<option value="">{{ __('common.form.select_district') }}</option>');
        $('#commune').prop('disabled', true).empty().append(
          '<option value="">{{ __('common.form.select_commune') }}</option>');
        $('#village_id').prop('disabled', true).empty().append(
          '<option value="">{{ __('common.form.select_village') }}</option>');

        if (provinceId) {
          $.ajax({
            url: '/api/districts/' + provinceId,
            type: 'GET',
            success: function(data) {
              $.each(data, function(index, district) {
                $('#district').append($('<option>', {
                  value: district.id,
                  text: district.name_en + ' - ' + district.name_kh
                }));
              });
            }
          });
        }
      });

      // District change event
      $('#district').change(function() {
        var districtId = $(this).val();
        $('#commune').prop('disabled', !districtId).empty().append(
          '<option value="">{{ __('common.form.select_commune') }}</option>');
        $('#village_id').prop('disabled', true).empty().append(
          '<option value="">{{ __('common.form.select_village') }}</option>');

        if (districtId) {
          $.ajax({
            url: '/api/communes/' + districtId,
            type: 'GET',
            success: function(data) {
              $.each(data, function(index, commune) {
                $('#commune').append($('<option>', {
                  value: commune.id,
                  text: commune.name_en + ' - ' + commune.name_kh
                }));
              });
            }
          });
        }
      });

      // Commune change event
      $('#commune').change(function() {
        var communeId = $(this).val();
        $('#village_id').prop('disabled', !communeId).empty().append(
          '<option value="">{{ __('common.form.select_village') }}</option>');

        if (communeId) {
          $.ajax({
            url: '/api/villages/' + communeId,
            type: 'GET',
            success: function(data) {
              $.each(data, function(index, village) {
                $('#village_id').append($('<option>', {
                  value: village.id,
                  text: village.name_en + ' - ' + village.name_kh
                }));
              });
            }
          });
        }
      });

      // Form validation
      $('#customerForm').on('submit', function(e) {
        e.preventDefault();

        // Validate required fields
        var isValid = true;
        $(this).find('[required]').each(function() {
          if (!$(this).val()) {
            $(this).addClass('is-invalid');
            isValid = false;
          } else {
            $(this).removeClass('is-invalid');
          }
        });

        if (!isValid) {
          toastr.error('Please fill in all required fields');
          return false;
        }

        // Submit form via AJAX
        var formData = $(this).serialize();

        $.ajax({
          url: $(this).attr('action'),
          type: 'POST',
          data: formData,
          success: function(response) {
            if (response.success) {
              toastr.success(response.message);
              setTimeout(function() {
                window.location.href = '{{ route('customers.index') }}';
              }, 1000);
            }
          },
          error: function(xhr) {
            var errors = xhr.responseJSON.errors;
            $.each(errors, function(key, value) {
              toastr.error(value[0]);
              $('#' + key).addClass('is-invalid');
            });
          }
        });
      });
    });
  </script>
@endpush
