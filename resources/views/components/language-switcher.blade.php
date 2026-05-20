<!-- Language Switcher Dropdown -->
<div class="position-relative">
  <button id="lang-btn" class="btn btn-link p-0 border-0 d-flex align-items-center text-decoration-none" type="button">
    @php
      $currentLocale = app()->getLocale();
      $locales = config('app.supported_locales');
      $current = $locales[$currentLocale] ?? $locales['en'];
    @endphp
    <div class="d-flex align-items-center text-secondary">
      <i class="fas fa-globe me-2"></i>
      <span class="flag-icon flag-icon-{{ $current['flag'] }} me-2"></span>
      <span class="d-none d-md-inline">{{ $current['name'] }}</span>
      <i class="fas fa-chevron-down ms-2 small"></i>
    </div>
  </button>

  <div id="lang-menu" class="d-none position-absolute bg-white border rounded shadow-lg py-0"
    style="min-width: 12rem; right: 0; z-index: 1050; top: 100%; margin-top: 0.5rem; overflow: hidden;">
    <div class="py-1">
      @foreach (config('app.supported_locales') as $code => $language)
        <form action="{{ route('language.switch') }}" method="POST" class="d-inline">
          @csrf
          <input type="hidden" name="locale" value="{{ $code }}">
          <button type="submit"
            class="dropdown-item d-flex align-items-center px-3 py-2 small text-dark text-decoration-none pt-hover-bg-light {{ app()->getLocale() === $code ? 'active bg-primary text-white' : '' }}"
            style="border: none; background: none; width: 100%; text-align: left;">
            <span class="flag-icon flag-icon-{{ $language['flag'] }} me-2"></span>
            <span class="grow">{{ $language['name'] }}</span>
            @if ($language['native'])
              <small class="text-muted ms-1">({{ $language['native'] }})</small>
            @endif
            @if (app()->getLocale() === $code)
              <i class="fas fa-check ms-2 text-white"></i>
            @endif
          </button>
        </form>
      @endforeach
    </div>
  </div>
</div>

<style>
  /* Flag Icons CSS */
  .flag-icon {
    background-size: contain !important;
    background-repeat: no-repeat !important;
    background-position: center !important;
    width: 1.2em !important;
    height: 0.9em !important;
    display: inline-block !important;
    vertical-align: middle !important;
    border-radius: 2px;
  }

  .flag-icon-gb {
    background-image: url('/assets/backend/flags/gb.png') !important;
  }

  .flag-icon-kh {
    background-image: url('/assets/backend/flags/kh.png') !important;
  }

  .flag-icon-cn {
    background-image: url('/assets/backend/flags/cn.png') !important;
  }

  /* Language button styling */
  #lang-btn {
    transition: all 0.2s ease-in-out;
    padding: 0.5rem !important;
    border-radius: 0.375rem;
  }

  #lang-btn:hover {
    background-color: rgba(0, 0, 0, 0.05) !important;
  }

  #lang-btn .text-secondary {
    color: #6c757d !important;
  }

  #lang-btn:hover .text-secondary {
    color: #495057 !important;
  }

  #lang-btn:focus {
    outline: none;
    box-shadow: none;
  }

  /* Language menu styling */
  #lang-menu {
    background-color: #fff;
    border: 1px solid rgba(0, 0, 0, .125);
    border-radius: 0.375rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .175);
    z-index: 1050;
  }

  #lang-menu .dropdown-item {
    border: none !important;
    background: none !important;
    cursor: pointer;
    transition: background-color 0.15s ease-in-out;
  }

  #lang-menu .dropdown-item:hover {
    background-color: #f8f9fa !important;
  }

  #lang-menu .dropdown-item.active {
    background-color: #0d6efd !important;
    color: #fff !important;
  }

  #lang-menu .dropdown-item.active small.text-muted {
    color: rgba(255, 255, 255, 0.75) !important;
  }

  #lang-menu .dropdown-item.active:hover {
    background-color: #0b5ed7 !important;
  }

  /* Hover effect for non-active items */
  .pt-hover-bg-light:hover:not(.active) {
    background-color: #f8f9fa !important;
  }

  /* Mobile responsive */
  @media (max-width: 767.98px) {
    #lang-btn .d-none.d-md-inline {
      display: none !important;
    }

    #lang-menu {
      min-width: 10rem !important;
    }
  }
</style>
