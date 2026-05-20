<?php
/**
 * Quick script to create language file structure
 * Copies English translations as placeholders for manual translation later
 */

$enFile = __DIR__ . '/resources/lang/en/common.php';
$enData = require $enFile;

// Create Khmer file
$khFile = __DIR__ . '/resources/lang/kh/common.php';
file_put_contents($khFile, "<?php\n\nreturn " . var_export($enData, true) . ";\n");
echo "Created: {$khFile}\n";

// Create Chinese file
$zhFile = __DIR__ . '/resources/lang/zh/common.php';
file_put_contents($zhFile, "<?php\n\nreturn " . var_export($enData, true) . ";\n");
echo "Created: {$zhFile}\n";

echo "\nFiles created with English placeholders.\n";
echo "You can now use the /translation-keys interface to translate them gradually.\n";
