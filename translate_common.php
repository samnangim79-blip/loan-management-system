<?php
/**
 * Script to auto-translate common.php from English to Khmer and Chinese
 * Uses Laravel's GoogleTranslateService
 *
 * Usage: php translate_common.php
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
function translateArray(array $data, GoogleTranslateService $service, $targetLang, $depth = 0, &$progress = 0, $total = 0) {
    $result = [];

    foreach ($data as $key => $value) {
        if (is_array($value)) {
            // Recursively translate nested arrays
            $result[$key] = translateArray($value, $service, $targetLang, $depth + 1, $progress, $total);
        } else {
            try {
                // Skip translation for technical identifiers
                if (shouldSkipTranslation($value)) {
                    $result[$key] = $value;
                } else {
                    // Translate the value
                    $translated = $service->translate($value, $targetLang, 'en');
                    $result[$key] = $translated ?: $value;
                    $progress++;

                    if ($depth === 0 && $progress % 50 === 0) {
                        echo sprintf("  Translated %d/%d items...\n", $progress, $total);
                    }

                    // Small delay to avoid rate limiting
                    usleep(150000); // 150ms delay
                }
            } catch (Exception $e) {
                echo "  Warning - Error translating '{$key}': " . $e->getMessage() . "\n";
                $result[$key] = $value; // Keep original on error
            }
        }
    }

    return $result;
}

/**
 * Check if a value should skip translation
 */
function shouldSkipTranslation($value) {
    if (!is_string($value) || empty($value)) {
        return true;
    }

    // Skip technical terms and codes
    $skipPatterns = [
        '/^(en|kh|zh|km)$/i', // Language codes
        '/^(usd|khr|sa)$/i', // Currency codes
        '/^(asc|desc|get|post|put|delete|patch)$/i', // HTTP/SQL terms
        '/^(true|false|null|none|undefined)$/i', // Boolean/null values
        '/^(mon|tue|wed|thu|fri|sat|sun)$/i', // Day abbreviations
        '/^(excel|pdf|json|csv|xml|html)$/i', // File formats
        '/^[a-z0-9-_\.]+\.[a-z0-9-_\.]+$/i', // Routes (contains dots)
        '/^(btn|card|modal|table|form|input|badge|alert)/', // CSS classes
        '/^fa[a-z]/', // Font Awesome classes
        '/^pt[a-z]/', // Custom PT classes
        '/currentColor|bevel|round|square/', // CSS/SVG values
    ];

    foreach ($skipPatterns as $pattern) {
        if (preg_match($pattern, $value)) {
            return true;
        }
    }

    // Skip if value is mostly non-alphabetic (like CSS classes)
    if (preg_match('/^[a-z0-9-_]+$/i', $value) &&
        (strpos($value, '-') !== false || strpos($value, '_') !== false)) {
        return true;
    }

    return false;
}

/**
 * Count translatable items
 */
function countTranslatableItems(array $data) {
    $count = 0;
    foreach ($data as $value) {
        if (is_array($value)) {
            $count += countTranslatableItems($value);
        } else {
            $count++;
        }
    }
    return $count;
}

/**
 * Export array to PHP file with proper formatting
 */
function exportToPhpFile($data, $filepath) {
    $export = "<?php\n\nreturn " . var_export($data, true) . ";\n";

    // Improve formatting
    $export = str_replace('array (', '[', $export);
    $export = str_replace(')', ']', $export);
    $export = preg_replace('/\s+\]/', ']', $export);

    file_put_contents($filepath, $export);
}

try {
    echo "========================================\n";
    echo "  Translation Script - common.php\n";
    echo "========================================\n\n";

    // Initialize Google Translate Service
    $translateService = new GoogleTranslateService();

    // Load English translations
    $enFile = __DIR__ . '/resources/lang/en/common.php';
    if (!file_exists($enFile)) {
        throw new Exception("English translation file not found: {$enFile}");
    }

    $enData = require $enFile;
    $total = countTranslatableItems($enData);

    echo "English translations loaded: {$total} items\n\n";

    // Translate to Khmer
    echo "=== Translating to Khmer (kh) ===\n";
    $progress = 0;
    $khData = translateArray($enData, $translateService, 'kh', 0, $progress, $total);
    $khFile = __DIR__ . '/resources/lang/kh/common.php';
    exportToPhpFile($khData, $khFile);
    echo "✓ Khmer translation completed and saved\n";
    echo "  File: {$khFile}\n";
    echo "  Translated: {$progress} items\n\n";

    // Translate to Chinese
    echo "=== Translating to Chinese (zh) ===\n";
    $progress = 0;
    $zhData = translateArray($enData, $translateService, 'zh', 0, $progress, $total);
    $zhFile = __DIR__ . '/resources/lang/zh/common.php';
    exportToPhpFile($zhData, $zhFile);
    echo "✓ Chinese translation completed and saved\n";
    echo "  File: {$zhFile}\n";
    echo "  Translated: {$progress} items\n\n";

    echo "========================================\n";
    echo "  Translation completed successfully!\n";
    echo "========================================\n";

} catch (Exception $e) {
    echo "\n✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
