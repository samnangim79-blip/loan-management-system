@extends('admin.layouts.admin_layout')

@section('pageTitle', __('config.non_working_days'))

@push('styles')
  <style>
    .nwd-card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    }

    .nwd-card .card-header {
      background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
      color: #fff;
      border-radius: 12px 12px 0 0 !important;
    }

    .day-checkbox {
      padding: 1.25rem;
      border: 2px solid #e5e7eb;
      border-radius: 12px;
      text-align: center;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-bottom: 1rem;
    }

    .day-checkbox:hover {
      border-color: #f59e0b;
      background: #fffbeb;
    }

    .day-checkbox.selected {
      border-color: #f59e0b;
      background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    }

    .day-checkbox .day-name {
      font-size: 1.25rem;
      font-weight: 600;
      color: #374151;
    }

    .day-checkbox .day-abbr {
      font-size: 0.85rem;
      color: #6b7280;
      margin-top: 0.25rem;
    }

    .day-checkbox.selected .day-name {
      color: #92400e;
    }

    .day-checkbox i {
      font-size: 2rem;
      color: #9ca3af;
      margin-bottom: 0.5rem;
    }

    .day-checkbox.selected i {
      color: #f59e0b;
    }
  </style>
@endpush

@section('content')
  <div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="mb-1 fw-bold text-dark">
          <i class="fas fa-calendar-times me-2 text-warning"></i>Non-Working Days
        </h4>
        <p class="text-muted mb-0">Select the days when the institution is closed (weekend days)</p>
      </div>
      <div>
        <a href="{{ route('dashboard') }}" class="btn btn-primary">
          <i class="fas fa-arrow-left me-2"></i>Back to Settings
        </a>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <i class="fas fa-calendar-week me-2"></i>
        <h5 class="mb-0">{{ __('common.general.weekly_schedule') }}</h5>
      </div>
      <div class="card-body">
        <p class="text-muted mb-4">
          <i class="fas fa-info-circle me-2"></i>
          Click on the days that are non-working days. Selected days will be excluded from loan schedules and interest
          calculations.
        </p>

        <form id="nonWorkingDaysForm">
          <div class="row">
            @php
              $daysOfWeek = [
                  'Sunday' => 'Sun',
                  'Monday' => 'Mon',
                  'Tuesday' => 'Tue',
                  'Wednesday' => 'Wed',
                  'Thursday' => 'Thu',
                  'Friday' => 'Fri',
                  'Saturday' => 'Sat',
              ];
              $icons = [
                  'Sunday' => 'fa-sun',
                  'Monday' => 'fa-briefcase',
                  'Tuesday' => 'fa-briefcase',
                  'Wednesday' => 'fa-briefcase',
                  'Thursday' => 'fa-briefcase',
                  'Friday' => 'fa-briefcase',
                  'Saturday' => 'fa-umbrella-beach',
              ];
              $selectedDays = $days->pluck('non_work_day')->toArray();
            @endphp

            @foreach ($daysOfWeek as $day => $abbr)
              <div class="col-md-3 col-6">
                <div class="day-checkbox {{ in_array($day, $selectedDays) ? 'selected' : '' }}"
                  data-day="{{ $day }}">
                  <input type="checkbox" name="days[]" value="{{ $day }}" class="d-none"
                    {{ in_array($day, $selectedDays) ? 'checked' : '' }}>
                  <i class="fas {{ $icons[$day] }}"></i>
                  <div class="day-name">{{ $day }}</div>
                  <div class="day-abbr">{{ $abbr }}</div>
                </div>
              </div>
            @endforeach
          </div>

          <hr class="my-4">

          <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted">
              <i class="fas fa-check-circle text-success me-2"></i>
              <span id="selectedCount">{{ count($selectedDays) }}</span> day(s) selected as non-working
            </div>
            <button type="submit" class="btn btn-success">
              <i class="fas fa-save me-2"></i>Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Common Presets -->
    <div class="card nwd-card mt-4">
      <div class="card-header">
        <i class="fas fa-magic me-2"></i>
        <h5 class="mb-0">{{ __('common.general.quick_presets') }}</h5>
      </div>
      <div class="card-body">
        <p class="text-muted mb-3">Apply common working schedules:</p>
        <div class="d-flex flex-wrap gap-2">
          <button type="button" class="btn btn-outline-primary preset-btn" data-days='["Saturday", "Sunday"]'>
            <i class="fas fa-calendar-week me-2"></i>Sat-Sun Weekend
          </button>
          <button type="button" class="btn btn-outline-primary preset-btn" data-days='["Sunday"]'>
            <i class="fas fa-sun me-2"></i>Sunday Only
          </button>
          <button type="button" class="btn btn-outline-primary preset-btn" data-days='["Friday", "Saturday"]'>
            <i class="fas fa-moon me-2"></i>Fri-Sat Weekend
          </button>
          <button type="button" class="btn btn-outline-secondary preset-btn" data-days='[]'>
            <i class="fas fa-times me-2"></i>Clear All
          </button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    $(document).ready(function() {
      // Toggle day selection
      $('.day-checkbox').click(function() {
        $(this).toggleClass('selected');
        const checkbox = $(this).find('input[type="checkbox"]');
        checkbox.prop('checked', !checkbox.prop('checked'));
        updateSelectedCount();
      });

      // Apply preset
      $('.preset-btn').click(function() {
        const days = $(this).data('days');

        // Reset all
        $('.day-checkbox').removeClass('selected');
        $('.day-checkbox input').prop('checked', false);

        // Apply preset
        days.forEach(function(day) {
          const checkbox = $(`.day-checkbox[data-day="${day}"]`);
          checkbox.addClass('selected');
          checkbox.find('input').prop('checked', true);
        });

        updateSelectedCount();
      });

      // Save form
      $('#nonWorkingDaysForm').submit(function(e) {
        e.preventDefault();

        const formData = $(this).serializeArray();
        const days = formData.filter(item => item.name === 'days[]').map(item => item.value);

        $.ajax({
          url: '{{ route('config.update-non-working-days') }}',
          type: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
            days: days
          },
          success: function(response) {
            if (response.success) {
              toastr.success(response.message);
            } else {
              toastr.error(response.message);
            }
          },
          error: function() {
            toastr.error('{{ __('common.general.failed_to_save_nonworking_days') }}');
          }
        });
      });

      function updateSelectedCount() {
        const count = $('.day-checkbox.selected').length;
        $('#selectedCount').text(count);
      }
    });
  </script>
@endpush
