<?php
/**
 * Script to auto-translate validation.php from English to Khmer and Chinese
 * Uses Laravel's GoogleTranslateService
 *
 * Usage: php translate_validation.php
 */

// Bootstrap Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\GoogleTranslateService;

/**
 * Translate array recursively while preserving placeholders
 */
function translateArray(array $data, GoogleTranslateService $service, $targetLang, $depth = 0, &$progress = 0, $total = 0) {
    $result = [];

    foreach ($data as $key => $value) {
        if (is_array($value)) {
            // Recursively translate nested arrays
            $result[$key] = translateArray($value, $service, $targetLang, $depth + 1, $progress, $total);
        } else {
            // Skip empty values
            if (empty($value) || !is_string($value)) {
                $result[$key] = $value;
                continue;
            }

            try {
                // Extract placeholders before translation
                $placeholders = [];
                $text = preg_replace_callback('/:([a-z_]+)/', function($matches) use (&$placeholders) {
                    $placeholder = $matches[0];
                    $index = count($placeholders);
                    $placeholders[$index] = $placeholder;
                    return "{{PLACEHOLDER{$index}}}";
                }, $value);

                // Translate the text
                $translated = $service->translate($text, $targetLang, 'en');

                // Restore placeholders
                foreach ($placeholders as $index => $placeholder) {
                    $translated = str_replace("{{PLACEHOLDER{$index}}}", $placeholder, $translated);
                }

                $result[$key] = $translated ?: $value;
                $progress++;

                if ($depth === 0 && $progress % 10 === 0) {
                    echo sprintf("  Translated %d/%d items...\n", $progress, $total);
                }

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
 * Count translatable items
 */
function countTranslatableItems(array $data) {
    $count = 0;
    foreach ($data as $value) {
        if (is_array($value)) {
            $count += countTranslatableItems($value);
        } else {
            if (!empty($value) && is_string($value)) {
                $count++;
            }
        }
    }
    return $count;
}

/**
 * Export array to PHP file with proper formatting
 */
function exportToPhpFile($data, $filepath) {
    $content = "<?php\n\nreturn [\n\n";
    $content .= "    /*\n";
    $content .= "    |--------------------------------------------------------------------------\n";
    $content .= "    | Validation Language Lines\n";
    $content .= "    |--------------------------------------------------------------------------\n";
    $content .= "    |\n";
    $content .= "    | The following language lines contain the default error messages used by\n";
    $content .= "    | the validator class. Some of these rules have multiple versions such\n";
    $content .= "    | as the size rules. Feel free to tweak each of these messages here.\n";
    $content .= "    |\n";
    $content .= "    */\n\n";

    $content .= exportArrayContent($data, 1);

    $content .= "\n    /*\n";
    $content .= "    |--------------------------------------------------------------------------\n";
    $content .= "    | Custom Validation Language Lines\n";
    $content .= "    |--------------------------------------------------------------------------\n";
    $content .= "    |\n";
    $content .= "    | Here you may specify custom validation messages for attributes using the\n";
    $content .= "    | convention \"attribute.rule\" to name the lines. This makes it quick to\n";
    $content .= "    | specify a specific custom language line for a given attribute rule.\n";
    $content .= "    |\n";
    $content .= "    */\n\n";
    $content .= "    'custom' => [\n";
    $content .= "        'attribute-name' => [\n";
    $content .= "            'rule-name' => 'custom-message',\n";
    $content .= "        ],\n";
    $content .= "    ],\n\n";

    $content .= "    /*\n";
    $content .= "    |--------------------------------------------------------------------------\n";
    $content .= "    | Custom Validation Attributes\n";
    $content .= "    |--------------------------------------------------------------------------\n";
    $content .= "    |\n";
    $content .= "    | The following language lines are used to swap our attribute placeholder\n";
    $content .= "    | with something more reader friendly such as \"E-Mail Address\" instead\n";
    $content .= "    | of \"email\". This simply helps us make our message more expressive.\n";
    $content .= "    |\n";
    $content .= "    */\n\n";
    $content .= "    'attributes' => [],\n\n";
    $content .= "];\n";

    file_put_contents($filepath, $content);
}

/**
 * Export array content recursively
 */
function exportArrayContent($data, $indent = 1) {
    $output = '';
    $spaces = str_repeat('    ', $indent);

    // Skip custom and attributes keys as they're handled separately
    foreach ($data as $key => $value) {
        if ($key === 'custom' || $key === 'attributes') {
            continue;
        }

        if (is_array($value)) {
            $output .= "{$spaces}'{$key}' => [\n";
            foreach ($value as $subKey => $subValue) {
                $escapedValue = addslashes($subValue);
                $output .= "{$spaces}    '{$subKey}' => '{$escapedValue}',\n";
            }
            $output .= "{$spaces}],\n";
        } else {
            $escapedValue = addslashes($value);
            $output .= "{$spaces}'{$key}' => '{$escapedValue}',\n";
        }
    }

    return $output;
}

try {
    echo "========================================\n";
    echo "  Translation Script - validation.php\n";
    echo "========================================\n\n";

    // Initialize Google Translate Service
    $translateService = new GoogleTranslateService();

    // Load English translations
    $enFile = __DIR__ . '/resources/lang/en/validation.php';
    if (!file_exists($enFile)) {
        throw new Exception("English translation file not found: {$enFile}");
    }

    $enData = require $enFile;

    // Remove custom and attributes for translation
    unset($enData['custom']);
    unset($enData['attributes']);

    $total = countTranslatableItems($enData);

    echo "English translations loaded: {$total} items\n\n";

    // Translate to Khmer
    echo "=== Translating to Khmer (kh) ===\n";
    $progress = 0;
    $khData = translateArray($enData, $translateService, 'kh', 0, $progress, $total);
    $khFile = __DIR__ . '/resources/lang/kh/validation.php';
    exportToPhpFile($khData, $khFile);
    echo "✓ Khmer translation completed and saved\n";
    echo "  File: {$khFile}\n";
    echo "  Translated: {$progress} items\n\n";

    // Translate to Chinese
    echo "=== Translating to Chinese (zh) ===\n";
    $progress = 0;
    $zhData = translateArray($enData, $translateService, 'zh', 0, $progress, $total);
    $zhFile = __DIR__ . '/resources/lang/zh/validation.php';
    exportToPhpFile($zhData, $zhFile);
    echo "✓ Chinese translation completed and saved\n";
    echo "  File: {$zhFile}\n";
    echo "  Translated: {$progress} items\n\n";

    echo "========================================\n";
    echo "  Translation completed successfully!\n";
    echo "========================================\n";

} catch (Exception $e) {
    echo "\n✗ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
