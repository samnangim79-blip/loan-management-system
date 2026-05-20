<?php
/**
 * Script to auto-translate pagination.php from English to Khmer and Chinese
 * Uses Laravel's GoogleTranslateService
 *
 * Usage: php translate_pagination.php
 */

// Bootstrap Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\GoogleTranslateService;

/**
 * Translate array recursively while preserving HTML entities
 */
function translateArray(array $data, GoogleTranslateService $service, $targetLang) {
    $result = [];

    foreach ($data as $key => $value) {
        if (is_array($value)) {
            // Recursively translate nested arrays
            $result[$key] = translateArray($value, $service, $targetLang);
        } else {
            // Extract HTML entities before translation
            preg_match('/^(&[a-z]+;)?\s*(.+?)\s*(&[a-z]+;)?$/i', $value, $matches);

            $prefix = $matches[1] ?? '';
            $text = $matches[2] ?? $value;
            $suffix = $matches[3] ?? '';

            try {
                // Translate only the text part
                $translated = $service->translate($text, $targetLang, 'en');

                // Reconstruct with HTML entities
                if ($prefix && $suffix) {
                    $result[$key] = $prefix . ' ' . $translated . ' ' . $suffix;
                } elseif ($prefix) {
                    $result[$key] = $prefix . ' ' . $translated;
                } elseif ($suffix) {
                    $result[$key] = $translated . ' ' . $suffix;
                } else {
                    $result[$key] = $translated ?: $value;
                }

                echo "  '{$key}' => '{$result[$key]}'\n";

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
    $content = "<?php\n\nreturn [\n\n    /*\n    |--------------------------------------------------------------------------\n    | {$comment}\n    |--------------------------------------------------------------------------\n    |\n    | The following language lines are used by the paginator library to build\n    | the simple pagination links. You are free to change them to anything\n    | you want to customize your views to better match your application.\n    |\n    */\n\n";

    foreach ($data as $key => $value) {
        $escapedValue = addslashes($value);
        $content .= "    '{$key}' => '{$escapedValue}',\n";
    }

    $content .= "\n];\n";

    file_put_contents($filepath, $content);
}

try {
    echo "========================================\n";
    echo "  Translation Script - pagination.php\n";
    echo "========================================\n\n";

    // Initialize Google Translate Service
    $translateService = new GoogleTranslateService();

    // Load English translations
    $enFile = __DIR__ . '/resources/lang/en/pagination.php';
    if (!file_exists($enFile)) {
        throw new Exception("English translation file not found: {$enFile}");
    }

    $enData = require $enFile;
    $total = count($enData);

    echo "English translations loaded: {$total} items\n\n";

    // Translate to Khmer
    echo "=== Translating to Khmer (kh) ===\n";
    $khData = translateArray($enData, $translateService, 'kh');
    $khFile = __DIR__ . '/resources/lang/kh/pagination.php';
    exportToPhpFile($khData, $khFile, 'Pagination Language Lines (Khmer)');
    echo "✓ Khmer translation completed and saved\n";
    echo "  File: {$khFile}\n\n";

    // Translate to Chinese
    echo "=== Translating to Chinese (zh) ===\n";
    $zhData = translateArray($enData, $translateService, 'zh');
    $zhFile = __DIR__ . '/resources/lang/zh/pagination.php';
    exportToPhpFile($zhData, $zhFile, 'Pagination Language Lines (Chinese)');
    echo "✓ Chinese translation completed and saved\n";
    echo "  File: {$zhFile}\n\n";

    echo "========================================\n";
    echo "  Translation completed successfully!\n";
    echo "========================================\n";

} catch (Exception $e) {
    echo "\n✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
