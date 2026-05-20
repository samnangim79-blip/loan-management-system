<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SocialAccount;
use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class SocialAuthController extends Controller
{
  /**
   * Supported social providers
   */
  protected array $providers = ['google', 'github', 'twitter', 'telegram'];

  /**
   * Redirect to the social provider for authentication.
   */
  public function redirect(string $provider)
  {
    if (!$this->isValidProvider($provider)) {
      return redirect()->route('login')
        ->with('error', 'Invalid authentication provider.');
    }

    // Check if social login is enabled globally
    $socialLoginEnabled = Config::where('config_name', 'enable_social_login')->value('config_value') ?? '1';
    if ($socialLoginEnabled != '1') {
      return redirect()->route('login')
        ->with('error', 'Social login is currently disabled.');
    }

    // Check if specific provider is enabled
    $providerConfigMap = [
      'google' => 'enable_google_login',
      'github' => 'enable_github_login',
      'twitter' => 'enable_twitter_login',
      'telegram' => 'enable_telegram_login',
    ];

    $providerEnabled = Config::where('config_name', $providerConfigMap[$provider] ?? '')->value('config_value') ?? '1';
    if ($providerEnabled != '1') {
      return redirect()->route('login')
        ->with('error', ucfirst($provider) . ' login is currently disabled.');
    }

    try {
      // Telegram uses widget, not OAuth redirect
      if ($provider === 'telegram') {
        return redirect()->route('login')
          ->with('error', 'Please use the Telegram widget to login.');
      }

      return Socialite::driver($provider)->redirect();
    } catch (\Exception $e) {
      Log::error("Social auth redirect error for {$provider}: " . $e->getMessage());
      return redirect()->route('login')
        ->with('error', 'Unable to connect to ' . ucfirst($provider) . '. Please try again.');
    }
  }

  /**
   * Handle callback from social provider.
   */
  public function callback(string $provider)
  {
    if (!$this->isValidProvider($provider)) {
      return redirect()->route('login')
        ->with('error', 'Invalid authentication provider.');
    }

    // Check if social login is enabled globally
    $socialLoginEnabled = Config::where('config_name', 'enable_social_login')->value('config_value') ?? '1';
    if ($socialLoginEnabled != '1') {
      return redirect()->route('login')
        ->with('error', 'Social login is currently disabled.');
    }

    // Check if specific provider is enabled
    $providerConfigMap = [
      'google' => 'enable_google_login',
      'github' => 'enable_github_login',
      'twitter' => 'enable_twitter_login',
      'telegram' => 'enable_telegram_login',
    ];

    $providerEnabled = Config::where('config_name', $providerConfigMap[$provider] ?? '')->value('config_value') ?? '1';
    if ($providerEnabled != '1') {
      return redirect()->route('login')
        ->with('error', ucfirst($provider) . ' login is currently disabled.');
    }

    try {
      $socialUser = Socialite::driver($provider)->user();
      return $this->handleSocialUser($provider, $socialUser);
    } catch (\Exception $e) {
      Log::error("Social auth callback error for {$provider}: " . $e->getMessage());
      return redirect()->route('login')
        ->with('error', 'Authentication failed. Please try again.');
    }
  }

  /**
   * Handle Telegram authentication via widget.
   */
  public function telegramCallback(Request $request)
  {
    // Check if social login is enabled globally
    $socialLoginEnabled = Config::where('config_name', 'enable_social_login')->value('config_value') ?? '1';
    if ($socialLoginEnabled != '1') {
      return redirect()->route('login')
        ->with('error', 'Social login is currently disabled.');
    }

    // Check if Telegram is enabled
    $telegramEnabled = Config::where('config_name', 'enable_telegram_login')->value('config_value') ?? '1';
    if ($telegramEnabled != '1') {
      return redirect()->route('login')
        ->with('error', 'Telegram login is currently disabled.');
    }

    try {
      // Verify Telegram auth data
      if (!$this->verifyTelegramAuth($request->all())) {
        return redirect()->route('login')
          ->with('error', 'Invalid Telegram authentication data.');
      }

      // Create a socialite-like user object from Telegram data
      $telegramUser = new \stdClass();
      $telegramUser->id = $request->id;
      $telegramUser->name = trim(($request->first_name ?? '') . ' ' . ($request->last_name ?? ''));
      $telegramUser->nickname = $request->username ?? null;
      $telegramUser->avatar = $request->photo_url ?? null;
      $telegramUser->email = null; // Telegram doesn't provide email

      return $this->handleSocialUser('telegram', $telegramUser, true);
    } catch (\Exception $e) {
      Log::error("Telegram auth error: " . $e->getMessage());
      return redirect()->route('login')
        ->with('error', 'Telegram authentication failed. Please try again.');
    }
  }

  /**
   * Verify Telegram authentication data.
   */
  protected function verifyTelegramAuth(array $authData): bool
  {
    $checkHash = $authData['hash'] ?? '';
    unset($authData['hash']);

    $dataCheckArr = [];
    foreach ($authData as $key => $value) {
      $dataCheckArr[] = $key . '=' . $value;
    }
    sort($dataCheckArr);
    $dataCheckString = implode("\n", $dataCheckArr);

    $secretKey = hash('sha256', config('services.telegram.bot_token'), true);
    $hash = hash_hmac('sha256', $dataCheckString, $secretKey);

    if (strcmp($hash, $checkHash) !== 0) {
      return false;
    }

    // Check auth date (must be within last day)
    if ((time() - ($authData['auth_date'] ?? 0)) > 86400) {
      return false;
    }

    return true;
  }

  /**
   * Handle the social user authentication.
   */
  protected function handleSocialUser(string $provider, $socialUser, bool $isTelegram = false)
  {
    DB::beginTransaction();

    try {
      // Check if social account exists
      $socialAccount = SocialAccount::where('provider', $provider)
        ->where('provider_id', $socialUser->id ?? $socialUser->getId())
        ->first();

      if ($socialAccount) {
        // Update tokens
        $this->updateSocialAccount($socialAccount, $socialUser, $isTelegram);

        // Login user
        Auth::login($socialAccount->user, true);

        DB::commit();

        return redirect()->intended(route('dashboard'))
          ->with('success', 'Welcome back!');
      }

      // Get email from social user
      $email = $isTelegram ? null : ($socialUser->email ?? $socialUser->getEmail());

      // Check if user with this email already exists
      $user = null;
      if ($email) {
        $user = User::where('email', $email)->first();
      }

      // Create new user if doesn't exist
      if (!$user) {
        // For Telegram without email, generate a unique placeholder
        if (!$email) {
          $email = 'telegram_' . ($socialUser->id ?? $socialUser->getId()) . '@placeholder.local';
        }

        $user = User::create([
          'name' => $isTelegram ? $socialUser->name : ($socialUser->name ?? $socialUser->getName()),
          'email' => $email,
          'avatar' => $isTelegram ? $socialUser->avatar : ($socialUser->avatar ?? $socialUser->getAvatar()),
          'email_verified_at' => now(),
          'password' => null, // Social users don't need password
        ]);
      }

      // Create social account link
      $this->createSocialAccount($user, $provider, $socialUser, $isTelegram);

      // Login user
      Auth::login($user, true);

      DB::commit();

      return redirect()->intended(route('dashboard'))
        ->with('success', 'Account created successfully!');
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error("Social user handling error: " . $e->getMessage());
      throw $e;
    }
  }

  /**
   * Create a new social account record.
   */
  protected function createSocialAccount(User $user, string $provider, $socialUser, bool $isTelegram = false): SocialAccount
  {
    return SocialAccount::create([
      'user_id' => $user->id,
      'provider' => $provider,
      'provider_id' => $isTelegram ? $socialUser->id : ($socialUser->id ?? $socialUser->getId()),
      'provider_token' => $isTelegram ? null : ($socialUser->token ?? null),
      'provider_refresh_token' => $isTelegram ? null : ($socialUser->refreshToken ?? null),
      'token_expires_at' => $isTelegram ? null : ($socialUser->expiresIn ? now()->addSeconds($socialUser->expiresIn) : null),
      'provider_data' => [
        'name' => $isTelegram ? $socialUser->name : ($socialUser->name ?? $socialUser->getName()),
        'nickname' => $isTelegram ? $socialUser->nickname : ($socialUser->nickname ?? $socialUser->getNickname()),
        'avatar' => $isTelegram ? $socialUser->avatar : ($socialUser->avatar ?? $socialUser->getAvatar()),
      ],
    ]);
  }

  /**
   * Update existing social account tokens.
   */
  protected function updateSocialAccount(SocialAccount $socialAccount, $socialUser, bool $isTelegram = false): void
  {
    $updateData = [
      'provider_data' => [
        'name' => $isTelegram ? $socialUser->name : ($socialUser->name ?? $socialUser->getName()),
        'nickname' => $isTelegram ? $socialUser->nickname : ($socialUser->nickname ?? $socialUser->getNickname()),
        'avatar' => $isTelegram ? $socialUser->avatar : ($socialUser->avatar ?? $socialUser->getAvatar()),
      ],
    ];

    if (!$isTelegram) {
      $updateData['provider_token'] = $socialUser->token ?? null;
      $updateData['provider_refresh_token'] = $socialUser->refreshToken ?? null;
      $updateData['token_expires_at'] = $socialUser->expiresIn ? now()->addSeconds($socialUser->expiresIn) : null;
    }

    $socialAccount->update($updateData);

    // Update user avatar if changed
    $avatar = $isTelegram ? $socialUser->avatar : ($socialUser->avatar ?? $socialUser->getAvatar());
    if ($avatar && $socialAccount->user->avatar !== $avatar) {
      $socialAccount->user->update(['avatar' => $avatar]);
    }
  }

  /**
   * Check if provider is valid.
   */
  protected function isValidProvider(string $provider): bool
  {
    return in_array($provider, $this->providers);
  }

  /**
   * Unlink a social account from user.
   */
  public function unlink(Request $request, string $provider)
  {
    $user = Auth::user();

    // Check if user has more than one way to login
    $socialAccountsCount = $user->socialAccounts()->count();
    $hasPassword = !$user->isSocialUser();

    if ($socialAccountsCount <= 1 && !$hasPassword) {
      return back()->with('error', 'You cannot unlink your only login method. Please set a password first.');
    }

    $socialAccount = $user->socialAccounts()->where('provider', $provider)->first();

    if ($socialAccount) {
      $socialAccount->delete();
      return back()->with('success', ucfirst($provider) . ' account unlinked successfully.');
    }

    return back()->with('error', 'Social account not found.');
  }

  /**
   * Link a social account to existing user.
   */
  public function link(string $provider)
  {
    if (!Auth::check()) {
      return redirect()->route('login');
    }

    if (!$this->isValidProvider($provider)) {
      return back()->with('error', 'Invalid provider.');
    }

    // Store intent in session
    session(['social_link_provider' => $provider]);

    if ($provider === 'telegram') {
      return back()->with('info', 'Please use the Telegram widget to link your account.');
    }

    return Socialite::driver($provider)->redirect();
  }

  /**
   * Handle callback for linking social account.
   */
  public function linkCallback(string $provider)
  {
    if (!Auth::check()) {
      return redirect()->route('login');
    }

    $user = Auth::user();

    try {
      $socialUser = Socialite::driver($provider)->user();

      // Check if this social account is already linked to another user
      $existingAccount = SocialAccount::where('provider', $provider)
        ->where('provider_id', $socialUser->getId())
        ->first();

      if ($existingAccount && $existingAccount->user_id !== $user->id) {
        return redirect()->route('profile.edit')
          ->with('error', 'This ' . ucfirst($provider) . ' account is already linked to another user.');
      }

      if ($existingAccount) {
        return redirect()->route('profile.edit')
          ->with('info', ucfirst($provider) . ' account is already linked.');
      }

      // Create the link
      $this->createSocialAccount($user, $provider, $socialUser);

      return redirect()->route('profile.edit')
        ->with('success', ucfirst($provider) . ' account linked successfully!');
    } catch (\Exception $e) {
      Log::error("Social link error for {$provider}: " . $e->getMessage());
      return redirect()->route('profile.edit')
        ->with('error', 'Failed to link ' . ucfirst($provider) . ' account.');
    }
  }
}
