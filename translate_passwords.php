<?php
/**
 * Script to auto-translate passwords.php from English to Khmer and Chinese
 * Uses Laravel's GoogleTranslateService
 *
 * Usage: php translate_passwords.php
 */

// Bootstrap Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\GoogleTranslateService;

/**
 * Translate array recursively
 */
function translateArray(array $data, GoogleTranslateService $service, $targetLang) {
    $result = [];

    foreach ($data as $key => $value) {
        if (is_array($value)) {
            // Recursively translate nested arrays
            $result[$key] = translateArray($value, $service, $targetLang);
        } else {
            // Translate the value
            try {
                $translated = $service->translate($value, $targetLang, 'en');
                $result[$key] = $translated ?: $value;
                echo "  '{$key}' => '{$translated}'\n";

                // Small delay to avoid rate limiting
                usleep(150000); // 150ms delay
            } catch (Exception $e) {
                echo "  Warning - Error translating '{$key}': " . $e->getMessage() . "\n";
                $result[$key] = $value; // Keep original on error
            }
        }
    }

    return $result;
}

/**
 * Export array to PHP file with proper formatting
 */
function exportToPhpFile($data, $filepath, $comment) {
    $content = "<?php\n\nreturn [\n\n    /*\n    |--------------------------------------------------------------------------\n    | {$comment}\n    |--------------------------------------------------------------------------\n    |\n    | The following language lines are the default lines which match reasons\n    | that are given by the password broker for a password update attempt\n    | outcome such as failure due to an invalid password / reset token.\n    |\n    */\n\n";

    foreach ($data as $key => $value) {
        $escapedValue = addslashes($value);
        $content .= "    '{$key}' => '{$escapedValue}',\n";
    }

    $content .= "\n];\n";

    file_put_contents($filepath, $content);
}

try {
    echo "========================================\n";
    echo "  Translation Script - passwords.php\n";
    echo "========================================\n\n";

    // Initialize Google Translate Service
    $translateService = new GoogleTranslateService();

    // Load English translations
    $enFile = __DIR__ . '/resources/lang/en/passwords.php';
    if (!file_exists($enFile)) {
        throw new Exception("English translation file not found: {$enFile}");
    }

    $enData = require $enFile;
    $total = count($enData);

    echo "English translations loaded: {$total} items\n\n";

    // Translate to Khmer
    echo "=== Translating to Khmer (kh) ===\n";
    $khData = translateArray($enData, $translateService, 'kh');
    $khFile = __DIR__ . '/resources/lang/kh/passwords.php';
    exportToPhpFile($khData, $khFile, 'Password Reset Language Lines (Khmer)');
    echo "✓ Khmer translation completed and saved\n";
    echo "  File: {$khFile}\n\n";

    // Translate to Chinese
    echo "=== Translating to Chinese (zh) ===\n";
    $zhData = translateArray($enData, $translateService, 'zh');
    $zhFile = __DIR__ . '/resources/lang/zh/passwords.php';
    exportToPhpFile($zhData, $zhFile, 'Password Reset Language Lines (Chinese)');
    echo "✓ Chinese translation completed and saved\n";
    echo "  File: {$zhFile}\n\n";

    echo "========================================\n";
    echo "  Translation completed successfully!\n";
    echo "========================================\n";

} catch (Exception $e) {
    echo "\n✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
