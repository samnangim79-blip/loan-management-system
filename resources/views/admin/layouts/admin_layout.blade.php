<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PanhaTech - Bootstrap 5 Admin Dashboard</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
  <link rel="shortcut icon" href="assets/img/favicon.ico">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:ital,wght@0,100..700;1,100..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Tom Select CSS -->
  <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">

  <!-- Flatpickr CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

  <!-- Toastr CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('assets/backend/css/main.css') }}">
  @stack('styles')

</head>

<body>

  <div class="pt-main-wrapper">

    <!-- Sidebar -->
    @include('admin.layouts.partials.left_sidebar')

    <!-- Main Content Area -->
    <div class="pt-content-wrapper">

      <!-- Top Header -->
      <header class="top-header position-relative">
        <div class="container-fluid h-100">
          <div class="row h-100 align-items-center">

            <!-- Left Section -->
            <div class="col-6 col-md-4">
              <div class="d-flex align-items-center" style="margin-left: 20px;">
                <button id="sidebar-toggle" class="btn btn-link text-secondary d-md-none p-0 me-3">
                  <i class="fa-solid fa-bars fa-lg"></i>
                </button>
                <a href="#" class="btn btn-outline-secondary btn-sm d-none d-sm-flex align-items-center">
                  <i class="fa-solid fa-store me-2"></i> Storefront
                </a>
              </div>
            </div>

            <!-- Right Section -->
            <div class="col-6 col-md-8">
              <div class="d-flex align-items-center justify-content-end">

                <!-- Fullscreen Toggle -->
                <button class="btn btn-link text-secondary p-2 d-none d-sm-inline-block" id="fullscreen-btn">
                  <i class="fa-solid fa-expand"></i>
                </button>

                <!-- Language Dropdown -->
                <div class="ms-2">
                  @include('components.language-switcher')
                </div>

                <!-- Profile Dropdown -->
                <div class="position-relative ms-3" id="profile-dropdown">
                  <button id="profile-btn" class="btn btn-link p-0 border-0 d-flex align-items-center">
                    <img class="rounded-circle" width="36" height="36"
                      src="https://ui-avatars.com/api/?name=Panha+Tech&color=fff&background=007bff"
                      alt="{{ __('common.nav.user_avatar') }}">
                  </button>
                  <div id="profile-menu" class="d-none position-absolute bg-white border rounded shadow-lg py-0"
                    style="min-width: 15rem; right: 0; z-index: 1050; top: 100%; margin-top: 0.5rem; overflow: hidden;">
                    <div class="px-3 py-2 border-bottom bg-light d-flex align-items-center">
                      <img class="rounded-circle me-2" width="40" height="40"
                        src="https://ui-avatars.com/api/?name=Panha+Tech&color=fff&background=007bff"
                        alt="{{ __('common.nav.user_avatar') }}">
                      <div>
                        <p class="mb-0 fw-bold small">Panha Tech</p>
                        <p class="mb-0 text-muted small">admin@panhatech.com</p>
                      </div>
                    </div>
                    <div class="py-1">
                      <a href="#"
                        class="d-flex align-items-center px-3 py-2 small text-dark text-decoration-none pt-hover-bg-light">
                        <i class="fa-regular fa-user me-2 text-muted" style="width: 1.5rem;"></i>
                        My Profile
                      </a>
                      <a href="#"
                        class="d-flex align-items-center px-3 py-2 small text-dark text-decoration-none pt-hover-bg-light">
                        <i class="fa-solid fa-gear me-2 text-muted" style="width: 1.5rem;"></i>
                        Settings
                      </a>
                    </div>
                    <div class="border-top py-1">
                      <a href="#"
                        class="d-flex align-items-center px-3 py-2 small text-danger text-decoration-none pt-hover-bg-danger-light">
                        <i class="fa-solid fa-arrow-right-from-bracket me-2" style="width: 1.5rem;"></i>
                        Logout
                      </a>
                    </div>
                  </div>
                </div>

              </div>
            </div>

          </div>
        </div>
      </header>

      <!-- Main Content -->
      <main class="pt-main-content">
        <div class="container-fluid py-4">

          <!-- Page Title and Breadcrumb -->
          <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
            <h1 class="h3 mb-2 mb-md-0 fw-bold text-dark">{{ __('common.nav.dashboard') }}</h1>
            <nav aria-label="Close">
              <ol class="breadcrumb mb-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-primary"><i
                      class="fas fa-home me-1"></i>Home</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page">Dashboard</li>
              </ol>
            </nav>
          </div>
          <!-- Main Dashboard Content Goes Here -->
          @yield('content')
          <!--End Main Dashboard content-->
        </div>
      </main>

    </div>

  </div>

  <!-- Mobile Overlay -->
  <div class="pt-mobile-overlay" id="mobileOverlay"></div>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <!-- Bootstrap 5 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Toastr JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

  <!-- Tom Select -->
  <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
  <!-- Flatpickr -->
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

  <!-- Custom Accordion Menu -->
  <script src="{{ asset('assets/backend/js/PanhaAccordionMenu.js') }}"></script>
  <!-- Global AJAX Setup -->
  <script>
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
  </script>
  <!-- Custom JS -->
  @stack('scripts')
</body>

</html>
