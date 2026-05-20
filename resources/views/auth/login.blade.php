<!DOCTYPE html>
<html lang="{{ __('common.general.en') }}">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrftoken" content="{{ csrf_token() }}">
  <title>Login - Loan Management System</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    :root {
      --primary-color: #4f46e5;
      --primary-hover: #4338ca;
      --google-color: #ea4335;
      --github-color: #333;
      --twitter-color: #000;
      --telegram-color: #0088cc;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .auth-container {
      width: 100%;
      max-width: 500px;
    }

    .auth-card {
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
      padding: 40px;
    }

    .auth-logo {
      text-align: center;
      margin-bottom: 30px;
    }

    .auth-logo .logo-icon {
      width: 60px;
      height: 60px;
      background: var(--primary-color);
      border-radius: 16px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 15px;
    }

    .auth-logo .logo-icon i {
      font-size: 28px;
      color: #fff;
    }

    .auth-logo h1 {
      font-size: 24px;
      font-weight: 600;
      color: #1f2937;
      margin-bottom: 5px;
    }

    .auth-logo p {
      color: #6b7280;
      font-size: 14px;
    }

    .form-floating {
      margin-bottom: 16px;
    }

    .form-floating .form-control {
      border: 2px solid #e5e7eb;
      border-radius: 12px;
      height: 56px;
      padding: 16px;
      font-size: 15px;
      transition: all 0.3s ease;
    }

    .form-floating .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }

    .form-floating label {
      padding: 16px;
      color: #9ca3af;
    }

    .form-check {
      margin-bottom: 20px;
    }

    .form-check-input:checked {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }

    .btn-primary {
      background: var(--primary-color);
      border: none;
      border-radius: 12px;
      height: 52px;
      font-size: 16px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      background: var(--primary-hover);
      transform: translateY(-2px);
      box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
    }

    .divider {
      display: flex;
      align-items: center;
      margin: 25px 0;
    }

    .divider::before,
    .divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: #e5e7eb;
    }

    .divider span {
      padding: 0 15px;
      color: #9ca3af;
      font-size: 13px;
      font-weight: 500;
    }

    .social-buttons {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 12px;
    }

    .btn-social {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      padding: 12px 16px;
      border: 2px solid #e5e7eb;
      border-radius: 12px;
      background: #fff;
      font-size: 14px;
      font-weight: 500;
      color: #374151;
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .btn-social:hover {
      border-color: transparent;
      color: #fff;
      transform: translateY(-2px);
    }

    .btn-social.google:hover {
      background: var(--google-color);
      box-shadow: 0 8px 16px rgba(234, 67, 53, 0.3);
    }

    .btn-social.github:hover {
      background: var(--github-color);
      box-shadow: 0 8px 16px rgba(51, 51, 51, 0.3);
    }

    .btn-social.twitter:hover {
      background: var(--twitter-color);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
    }

    .btn-social.telegram:hover {
      background: var(--telegram-color);
      box-shadow: 0 8px 16px rgba(0, 136, 204, 0.3);
    }

    .auth-footer {
      color: #6b7280;
      font-size: 14px;
    }

    .auth-footer a {
      font-weight: 500;
    }

    @media (max-width: 768px) {
      .auth-card {
        padding: 30px 25px;
        margin: 20px;
      }

      .social-buttons {
        grid-template-columns: 1fr;
      }
    }

    .btn-social i {
      font-size: 18px;
    }

    .btn-social.google i {
      color: var(--google-color);
    }

    .btn-social.github i {
      color: var(--github-color);
    }

    .btn-social.twitter i {
      color: var(--twitter-color);
    }

    .btn-social.telegram i {
      color: var(--telegram-color);
    }

    .btn-social:hover i {
      color: #fff;
    }

    .auth-footer {
      text-align: center;
      margin-top: 25px;
      color: #6b7280;
      font-size: 14px;
    }

    .auth-footer a {
      color: var(--primary-color);
      font-weight: 600;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .auth-footer a:hover {
      color: var(--primary-hover);
    }

    .alert {
      border-radius: 12px;
      border: none;
      font-size: 14px;
    }

    .alert-danger {
      background: #fef2f2;
      color: #dc2626;
    }

    .alert-success {
      background: #f0fdf4;
      color: #16a34a;
    }

    .forgot-password {
      text-align: right;
      margin-bottom: 20px;
    }

    .forgot-password a {
      color: var(--primary-color);
      font-size: 13px;
      text-decoration: none;
    }

    .forgot-password a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>
  <div class="auth-container">
    <div class="auth-card">
      <!-- Logo -->
      <div class="auth-logo">
        <div class="logo-icon">
          <i class="fas fa-university"></i>
        </div>
        <h1>{{ __('common.auth.welcome_back') }}</h1>
        <p>{{ __('common.auth.sign_in_to_your_account_to_continue') }}</p>
      </div>

      <!-- Error Messages -->
      @if ($errors->any())
        <div class="alert alert-danger mb-4">
          <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @if (session('success'))
        <div class="alert alert-success mb-4">
          {{ session('success') }}
        </div>
      @endif

      @if (session('error'))
        <div class="alert alert-danger mb-4">
          {{ session('error') }}
        </div>
      @endif

      <!-- Login Form -->
      <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-floating">
          <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
            value="{{ old('email') }}" placeholder="{{ __('common.general.email_address') }}" required autofocus>
          <label for="email"><i class="fas fa-envelope me-2"></i>Email address</label>
        </div>

        <div class="form-floating">
          <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
            name="password" placeholder="{{ __('common.auth.password') }}" required>
          <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember"
              {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">
              Remember me
            </label>
          </div>
          <div>
            <a href="#" class="text-primary text-decoration-none">Forgot password?</a>
          </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">
          <i class="fas fa-sign-in-alt me-2"></i>Sign In
        </button>
      </form>

      @php
        $socialLoginEnabled =
            \App\Models\Config::where('config_name', 'enable_social_login')->value('config_value') ?? '1';
        $googleEnabled = \App\Models\Config::where('config_name', 'enable_google_login')->value('config_value') ?? '1';
        $githubEnabled = \App\Models\Config::where('config_name', 'enable_github_login')->value('config_value') ?? '1';
        $twitterEnabled =
            \App\Models\Config::where('config_name', 'enable_twitter_login')->value('config_value') ?? '1';
        $telegramEnabled =
            \App\Models\Config::where('config_name', 'enable_telegram_login')->value('config_value') ?? '1';

        $hasAnySocialLogin =
            $socialLoginEnabled == '1' &&
            ($googleEnabled == '1' || $githubEnabled == '1' || $twitterEnabled == '1' || $telegramEnabled == '1');
      @endphp

      @if ($hasAnySocialLogin)
        <!-- Divider -->
        <div class="divider">
          <span>{{ __('common.general.or_continue_with') }}</span>
        </div>

        <!-- Social Login Buttons -->
        <div class="social-buttons">
          @if ($socialLoginEnabled == '1' && $googleEnabled == '1')
            <a href="{{ route('social.redirect', 'google') }}" class="btn-social google">
              <i class="fab fa-google"></i>
              <span>{{ __('common.auth.google') }}</span>
            </a>
          @endif
          @if ($socialLoginEnabled == '1' && $githubEnabled == '1')
            <a href="{{ route('social.redirect', 'github') }}" class="btn-social github">
              <i class="fab fa-github"></i>
              <span>{{ __('common.auth.github') }}</span>
            </a>
          @endif
          @if ($socialLoginEnabled == '1' && $twitterEnabled == '1')
            <a href="{{ route('social.redirect', 'twitter') }}" class="btn-social twitter">
              <i class="fab fa-x-twitter"></i>
              <span>X.com</span>
            </a>
          @endif
          @if ($socialLoginEnabled == '1' && $telegramEnabled == '1')
            <a href="{{ route('social.redirect', 'telegram') }}" class="btn-social telegram">
              <i class="fab fa-telegram"></i>
              <span>{{ __('common.auth.telegram') }}</span>
            </a>
          @endif
        </div>
      @endif

      <!-- Footer -->
      @php
        $allowRegistration =
            \App\Models\Config::where('config_name', 'allow_registration')->value('config_value') ?? '1';
      @endphp
      @if ($allowRegistration == '1')
        <div class="auth-footer text-center mt-4">
          Don't have an account? <a href="{{ route('register') }}" class="text-primary text-decoration-none">Create
            one</a>
        </div>
      @endif
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
