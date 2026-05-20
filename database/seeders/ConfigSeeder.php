<?php

namespace Database\Seeders;

use App\Models\Config;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $configs = [
      // General Settings
      ['CONFIG_ID' => 1, 'CONFIG_NAME' => 'SYSTEM_LANGUAGE', 'CONFIG_VALUE' => 'en', 'REMARK' => 'Default system language'],
      ['CONFIG_ID' => 2, 'CONFIG_NAME' => 'DATE_FORMAT', 'CONFIG_VALUE' => 'Y-m-d', 'REMARK' => 'Date display format'],
      ['CONFIG_ID' => 3, 'CONFIG_NAME' => 'DEFAULT_CURRENCY', 'CONFIG_VALUE' => 'USD', 'REMARK' => 'Primary currency'],
      ['CONFIG_ID' => 4, 'CONFIG_NAME' => 'EXCHANGE_RATE', 'CONFIG_VALUE' => '4100', 'REMARK' => 'KHR/USD exchange rate'],
      ['CONFIG_ID' => 5, 'CONFIG_NAME' => 'FISCAL_YEAR_START', 'CONFIG_VALUE' => '1', 'REMARK' => 'Fiscal year start month'],
      ['CONFIG_ID' => 6, 'CONFIG_NAME' => 'SESSION_TIMEOUT', 'CONFIG_VALUE' => '30', 'REMARK' => 'Session timeout in minutes'],

      // Company Info
      ['CONFIG_ID' => 10, 'CONFIG_NAME' => 'COMPANY_NAME_EN', 'CONFIG_VALUE' => 'Loan Management System', 'REMARK' => 'Company name in English'],
      ['CONFIG_ID' => 11, 'CONFIG_NAME' => 'COMPANY_NAME_KH', 'CONFIG_VALUE' => '', 'REMARK' => 'Company name in Khmer'],
      ['CONFIG_ID' => 12, 'CONFIG_NAME' => 'LICENSE_NO', 'CONFIG_VALUE' => '', 'REMARK' => 'MFI license number'],
      ['CONFIG_ID' => 13, 'CONFIG_NAME' => 'TAX_ID', 'CONFIG_VALUE' => '', 'REMARK' => 'Tax identification number'],
      ['CONFIG_ID' => 14, 'CONFIG_NAME' => 'COMPANY_PHONE', 'CONFIG_VALUE' => '', 'REMARK' => 'Main contact phone'],
      ['CONFIG_ID' => 15, 'CONFIG_NAME' => 'COMPANY_EMAIL', 'CONFIG_VALUE' => '', 'REMARK' => 'Main contact email'],
      ['CONFIG_ID' => 16, 'CONFIG_NAME' => 'COMPANY_ADDRESS', 'CONFIG_VALUE' => '', 'REMARK' => 'Company address'],

      // Loan Parameters
      ['CONFIG_ID' => 20, 'CONFIG_NAME' => 'MIN_LOAN_AMOUNT', 'CONFIG_VALUE' => '100', 'REMARK' => 'Minimum loan amount (USD)'],
      ['CONFIG_ID' => 21, 'CONFIG_NAME' => 'MAX_LOAN_AMOUNT', 'CONFIG_VALUE' => '50000', 'REMARK' => 'Maximum loan amount (USD)'],
      ['CONFIG_ID' => 22, 'CONFIG_NAME' => 'MIN_LOAN_TERM', 'CONFIG_VALUE' => '1', 'REMARK' => 'Minimum loan term (months)'],
      ['CONFIG_ID' => 23, 'CONFIG_NAME' => 'MAX_LOAN_TERM', 'CONFIG_VALUE' => '60', 'REMARK' => 'Maximum loan term (months)'],
      ['CONFIG_ID' => 24, 'CONFIG_NAME' => 'DEFAULT_INTEREST_CALC', 'CONFIG_VALUE' => '2', 'REMARK' => '1=Flat, 2=Declining, 3=Effective'],
      ['CONFIG_ID' => 25, 'CONFIG_NAME' => 'GRACE_PERIOD_DAYS', 'CONFIG_VALUE' => '3', 'REMARK' => 'Grace period before penalty'],
      ['CONFIG_ID' => 26, 'CONFIG_NAME' => 'REQUIRE_COLLATERAL', 'CONFIG_VALUE' => '0', 'REMARK' => 'Require collateral for all loans'],
      ['CONFIG_ID' => 27, 'CONFIG_NAME' => 'COLLATERAL_COVERAGE', 'CONFIG_VALUE' => '120', 'REMARK' => 'Collateral coverage ratio (%)'],

      // Interest & Fees
      ['CONFIG_ID' => 30, 'CONFIG_NAME' => 'DEFAULT_INTEREST_RATE', 'CONFIG_VALUE' => '18', 'REMARK' => 'Default annual interest rate'],
      ['CONFIG_ID' => 31, 'CONFIG_NAME' => 'MIN_INTEREST_RATE', 'CONFIG_VALUE' => '12', 'REMARK' => 'Minimum allowed interest rate'],
      ['CONFIG_ID' => 32, 'CONFIG_NAME' => 'MAX_INTEREST_RATE', 'CONFIG_VALUE' => '36', 'REMARK' => 'Maximum allowed interest rate'],
      ['CONFIG_ID' => 33, 'CONFIG_NAME' => 'PROCESSING_FEE', 'CONFIG_VALUE' => '1', 'REMARK' => 'Loan processing fee (%)'],
      ['CONFIG_ID' => 34, 'CONFIG_NAME' => 'INSURANCE_FEE', 'CONFIG_VALUE' => '0.5', 'REMARK' => 'Loan insurance fee (%)'],
      ['CONFIG_ID' => 35, 'CONFIG_NAME' => 'EARLY_REPAYMENT_FEE', 'CONFIG_VALUE' => '2', 'REMARK' => 'Early repayment fee (%)'],
      ['CONFIG_ID' => 36, 'CONFIG_NAME' => 'STAMP_DUTY', 'CONFIG_VALUE' => '5', 'REMARK' => 'Stamp duty (fixed amount)'],

      // Penalties
      ['CONFIG_ID' => 40, 'CONFIG_NAME' => 'LATE_PAYMENT_PENALTY', 'CONFIG_VALUE' => '2', 'REMARK' => 'Late payment penalty rate (%)'],
      ['CONFIG_ID' => 41, 'CONFIG_NAME' => 'PENALTY_CALC_METHOD', 'CONFIG_VALUE' => '2', 'REMARK' => '1=Fixed, 2=Percentage, 3=Compounding'],
      ['CONFIG_ID' => 42, 'CONFIG_NAME' => 'MAX_PENALTY', 'CONFIG_VALUE' => '25', 'REMARK' => 'Maximum penalty cap (%)'],
      ['CONFIG_ID' => 43, 'CONFIG_NAME' => 'DAYS_BEFORE_LEGAL', 'CONFIG_VALUE' => '90', 'REMARK' => 'Days before legal action'],
      ['CONFIG_ID' => 44, 'CONFIG_NAME' => 'DAYS_BEFORE_WRITEOFF', 'CONFIG_VALUE' => '365', 'REMARK' => 'Days before write-off'],
      ['CONFIG_ID' => 45, 'CONFIG_NAME' => 'AUTO_PENALTY', 'CONFIG_VALUE' => '1', 'REMARK' => 'Enable automatic penalty'],

      // Account Settings
      ['CONFIG_ID' => 50, 'CONFIG_NAME' => 'MIN_OPENING_BALANCE', 'CONFIG_VALUE' => '5', 'REMARK' => 'Minimum opening balance (USD)'],
      ['CONFIG_ID' => 51, 'CONFIG_NAME' => 'MIN_BALANCE', 'CONFIG_VALUE' => '5', 'REMARK' => 'Minimum balance to maintain'],
      ['CONFIG_ID' => 52, 'CONFIG_NAME' => 'SAVINGS_INTEREST_RATE', 'CONFIG_VALUE' => '2', 'REMARK' => 'Savings interest rate (% p.a.)'],
      ['CONFIG_ID' => 53, 'CONFIG_NAME' => 'INTEREST_PAYMENT_FREQ', 'CONFIG_VALUE' => 'M', 'REMARK' => 'D=Daily, M=Monthly, Q=Quarterly, Y=Yearly'],
      ['CONFIG_ID' => 54, 'CONFIG_NAME' => 'DORMANT_ACCOUNT_DAYS', 'CONFIG_VALUE' => '365', 'REMARK' => 'Days until account becomes dormant'],
      ['CONFIG_ID' => 55, 'CONFIG_NAME' => 'ACCOUNT_PREFIX', 'CONFIG_VALUE' => 'SA', 'REMARK' => 'Account number prefix'],

      // Fixed Deposit Settings
      ['CONFIG_ID' => 60, 'CONFIG_NAME' => 'MIN_FD_AMOUNT', 'CONFIG_VALUE' => '100', 'REMARK' => 'Minimum FD amount (USD)'],
      ['CONFIG_ID' => 61, 'CONFIG_NAME' => 'MIN_FD_TERM', 'CONFIG_VALUE' => '1', 'REMARK' => 'Minimum FD term (months)'],
      ['CONFIG_ID' => 62, 'CONFIG_NAME' => 'MAX_FD_TERM', 'CONFIG_VALUE' => '60', 'REMARK' => 'Maximum FD term (months)'],
      ['CONFIG_ID' => 63, 'CONFIG_NAME' => 'FD_EARLY_WITHDRAWAL_PENALTY', 'CONFIG_VALUE' => '50', 'REMARK' => 'Early withdrawal penalty (% of interest)'],
      ['CONFIG_ID' => 64, 'CONFIG_NAME' => 'FD_AUTO_RENEWAL', 'CONFIG_VALUE' => '0', 'REMARK' => 'Auto renew FD on maturity'],

      // Security Settings
      ['CONFIG_ID' => 70, 'CONFIG_NAME' => 'REQUIRE_2FA', 'CONFIG_VALUE' => '0', 'REMARK' => 'Require two-factor authentication'],
      ['CONFIG_ID' => 71, 'CONFIG_NAME' => 'PASSWORD_EXPIRY_DAYS', 'CONFIG_VALUE' => '90', 'REMARK' => 'Password expiry in days'],
      ['CONFIG_ID' => 72, 'CONFIG_NAME' => 'MIN_PASSWORD_LENGTH', 'CONFIG_VALUE' => '8', 'REMARK' => 'Minimum password length'],
      ['CONFIG_ID' => 73, 'CONFIG_NAME' => 'MAX_LOGIN_ATTEMPTS', 'CONFIG_VALUE' => '5', 'REMARK' => 'Max failed login attempts'],
      ['CONFIG_ID' => 74, 'CONFIG_NAME' => 'LOCKOUT_DURATION', 'CONFIG_VALUE' => '30', 'REMARK' => 'Account lockout duration (minutes)'],
      ['CONFIG_ID' => 75, 'CONFIG_NAME' => 'REQUIRE_APPROVAL_LARGE_TX', 'CONFIG_VALUE' => '1', 'REMARK' => 'Require approval for large transactions'],
      ['CONFIG_ID' => 76, 'CONFIG_NAME' => 'LARGE_TX_THRESHOLD', 'CONFIG_VALUE' => '10000', 'REMARK' => 'Large transaction threshold (USD)'],

      // Notification Settings
      ['CONFIG_ID' => 80, 'CONFIG_NAME' => 'EMAIL_NOTIFICATIONS', 'CONFIG_VALUE' => '1', 'REMARK' => 'Enable email notifications'],
      ['CONFIG_ID' => 81, 'CONFIG_NAME' => 'SMS_NOTIFICATIONS', 'CONFIG_VALUE' => '0', 'REMARK' => 'Enable SMS notifications'],
      ['CONFIG_ID' => 82, 'CONFIG_NAME' => 'PAYMENT_REMINDER_DAYS', 'CONFIG_VALUE' => '3', 'REMARK' => 'Days before due date for reminder'],
      ['CONFIG_ID' => 83, 'CONFIG_NAME' => 'OVERDUE_NOTIFICATION_DAYS', 'CONFIG_VALUE' => '1', 'REMARK' => 'Days after due date for overdue notice'],
      ['CONFIG_ID' => 84, 'CONFIG_NAME' => 'FD_MATURITY_REMINDER_DAYS', 'CONFIG_VALUE' => '7', 'REMARK' => 'Days before FD maturity for reminder'],

      // Backup Settings
      ['CONFIG_ID' => 90, 'CONFIG_NAME' => 'AUTO_BACKUP', 'CONFIG_VALUE' => '1', 'REMARK' => 'Enable automatic backups'],
      ['CONFIG_ID' => 91, 'CONFIG_NAME' => 'BACKUP_FREQUENCY', 'CONFIG_VALUE' => 'D', 'REMARK' => 'H=Hourly, D=Daily, W=Weekly'],
      ['CONFIG_ID' => 92, 'CONFIG_NAME' => 'BACKUP_RETENTION_DAYS', 'CONFIG_VALUE' => '30', 'REMARK' => 'Backup retention period (days)'],

      // Authentication Settings
      ['CONFIG_ID' => 100, 'CONFIG_NAME' => 'ALLOW_REGISTRATION', 'CONFIG_VALUE' => '1', 'REMARK' => 'Allow new user registration'],
      ['CONFIG_ID' => 101, 'CONFIG_NAME' => 'REQUIRE_EMAIL_VERIFICATION', 'CONFIG_VALUE' => '1', 'REMARK' => 'Require email verification for new users'],
      ['CONFIG_ID' => 102, 'CONFIG_NAME' => 'DEFAULT_USER_ROLE', 'CONFIG_VALUE' => 'user', 'REMARK' => 'Default role for new users'],
      ['CONFIG_ID' => 103, 'CONFIG_NAME' => 'ENABLE_SOCIAL_LOGIN', 'CONFIG_VALUE' => '1', 'REMARK' => 'Enable social login options'],
      ['CONFIG_ID' => 104, 'CONFIG_NAME' => 'ENABLE_GOOGLE_LOGIN', 'CONFIG_VALUE' => '1', 'REMARK' => 'Enable Google OAuth login'],
      ['CONFIG_ID' => 105, 'CONFIG_NAME' => 'ENABLE_GITHUB_LOGIN', 'CONFIG_VALUE' => '1', 'REMARK' => 'Enable GitHub OAuth login'],
      ['CONFIG_ID' => 106, 'CONFIG_NAME' => 'ENABLE_TWITTER_LOGIN', 'CONFIG_VALUE' => '1', 'REMARK' => 'Enable X.com (Twitter) OAuth login'],
      ['CONFIG_ID' => 107, 'CONFIG_NAME' => 'ENABLE_TELEGRAM_LOGIN', 'CONFIG_VALUE' => '1', 'REMARK' => 'Enable Telegram OAuth login'],
      ['CONFIG_ID' => 108, 'CONFIG_NAME' => 'REQUIRE_STRONG_PASSWORD', 'CONFIG_VALUE' => '1', 'REMARK' => 'Require strong passwords'],
      ['CONFIG_ID' => 109, 'CONFIG_NAME' => 'PASSWORD_HISTORY_COUNT', 'CONFIG_VALUE' => '5', 'REMARK' => 'Number of previous passwords to remember'],
      ['CONFIG_ID' => 110, 'CONFIG_NAME' => 'ALLOW_PASSWORD_RESET', 'CONFIG_VALUE' => '1', 'REMARK' => 'Allow password reset via email'],
      ['CONFIG_ID' => 111, 'CONFIG_NAME' => 'PASSWORD_RESET_EXPIRY', 'CONFIG_VALUE' => '60', 'REMARK' => 'Password reset link expiry (minutes)'],
      ['CONFIG_ID' => 112, 'CONFIG_NAME' => 'SINGLE_SESSION_ONLY', 'CONFIG_VALUE' => '0', 'REMARK' => 'Allow only single active session per user'],
      ['CONFIG_ID' => 113, 'CONFIG_NAME' => 'REMEMBER_ME_DURATION', 'CONFIG_VALUE' => '30', 'REMARK' => 'Remember me duration (days)'],
    ];

    foreach ($configs as $config) {
      Config::updateOrCreate(
        ['CONFIG_ID' => $config['CONFIG_ID']],
        $config
      );
    }

    $this->command->info('Configuration settings seeded successfully!');
  }
}
