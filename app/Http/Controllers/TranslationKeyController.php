<?php

namespace App\Http\Controllers;

use App\Models\TranslationKey;
use App\Services\GoogleTranslateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class TranslationKeyController extends Controller
{
    protected $translateService;

    public function __construct(GoogleTranslateService $translateService)
    {
        $this->translateService = $translateService;
    }

    /**
     * Get a valid user ID
     */
    private function getValidUserId()
    {
        $authId = Auth::id();
        if ($authId && \App\Models\UserLogin::find($authId)) {
            return $authId;
        }
        return 1;
    }

    /**
     * Display translation keys list
     */
    public function index()
    {
        $groups = TranslationKey::getGroups();
        return view('translation-keys.index', compact('groups'));
    }

    /**
     * Get translation keys data for DataTables
     */
    public function getData(Request $request)
    {
        $query = TranslationKey::select('translation_keys.*');

        // Filter by group if provided
        if ($request->has('group') && $request->group !== '') {
            $query->where('group', $request->group);
        }

        return DataTables::of($query)
            ->addColumn('translations', function ($row) {
                $badges = '';
                $languages = ['en' => 'English', 'kh' => 'Khmer', 'zh' => 'Chinese'];

                foreach ($languages as $code => $name) {
                    $hasTranslation = !empty($row->$code);
                    $badgeClass = $hasTranslation ? 'bg-success' : 'bg-secondary';
                    $badges .= '<span class="badge ' . $badgeClass . ' me-1">' . $name . '</span>';
                }

                if ($row->auto_translated) {
                    $badges .= '<span class="badge bg-info ms-1" title="Auto-translated"><i class="fas fa-robot"></i></span>';
                }

                return $badges;
            })
            ->addColumn('status', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-danger">Inactive</span>';
            })
            ->addColumn('action', function ($row) {
                $btn = '<div class="btn-group btn-group-sm" role="group">';
                $btn .= '<button type="button" class="btn btn-info view-btn" data-id="' . $row->key_id . '" title="View"><i class="fas fa-eye"></i></button>';
                $btn .= '<button type="button" class="btn btn-primary edit-btn" data-id="' . $row->key_id . '" title="Edit"><i class="fas fa-edit"></i></button>';
                $btn .= '<button type="button" class="btn btn-warning translate-btn" data-id="' . $row->key_id . '" title="Auto Translate"><i class="fas fa-language"></i></button>';
                $btn .= '<button type="button" class="btn btn-danger delete-btn" data-id="' . $row->key_id . '" title="Delete"><i class="fas fa-trash"></i></button>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['translations', 'status', 'action'])
            ->make(true);
    }

    /**
     * Store a new translation key
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'key_name' => 'required|string|max:255|unique:translation_keys,key_name',
            'group' => 'required|string|max:100',
            'en' => 'required|string',
            'kh' => 'nullable|string',
            'zh' => 'nullable|string',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
            'auto_translate' => 'nullable|boolean'
        ]);

        DB::beginTransaction();
        try {
            $validated['created_by'] = $this->getValidUserId();
            $validated['created_date'] = now();
            $validated['auto_translated'] = false;

            $translationKey = TranslationKey::create($validated);

            // Auto-translate if requested
            if ($request->auto_translate && !empty($validated['en'])) {
                $result = $this->translateService->autoTranslateKey($translationKey);

                if ($result['success']) {
                    $translationKey->refresh();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Translation key created successfully',
                'data' => $translationKey
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show translation key details
     */
    public function show($id)
    {
        $translationKey = TranslationKey::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $translationKey
        ]);
    }

    /**
     * Update translation key
     */
    public function update(Request $request, $id)
    {
        $translationKey = TranslationKey::findOrFail($id);

        $validated = $request->validate([
            'key_name' => 'required|string|max:255|unique:translation_keys,key_name,' . $id . ',key_id',
            'group' => 'required|string|max:100',
            'en' => 'required|string',
            'kh' => 'nullable|string',
            'zh' => 'nullable|string',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean'
        ]);

        $validated['modify_by'] = $this->getValidUserId();
        $validated['modify_date'] = now();

        $translationKey->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Translation key updated successfully',
            'data' => $translationKey
        ]);
    }

    /**
     * Delete translation key
     */
    public function destroy($id)
    {
        $translationKey = TranslationKey::findOrFail($id);
        $translationKey->delete();

        return response()->json([
            'success' => true,
            'message' => 'Translation key deleted successfully'
        ]);
    }

    /**
     * Auto-translate a single key
     */
    public function autoTranslate($id)
    {
        $translationKey = TranslationKey::findOrFail($id);

        try {
            $result = $this->translateService->autoTranslateKey($translationKey);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'data' => $translationKey->fresh()
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Translation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk auto-translate
     */
    public function bulkAutoTranslate(Request $request)
    {
        $validated = $request->validate([
            'group' => 'nullable|string',
            'key_ids' => 'nullable|array',
            'key_ids.*' => 'integer|exists:translation_keys,key_id'
        ]);

        $query = TranslationKey::where('is_active', 1);

        if (!empty($validated['key_ids'])) {
            $query->whereIn('key_id', $validated['key_ids']);
        } elseif (!empty($validated['group'])) {
            $query->where('group', $validated['group']);
        }
        // If both are empty, translate all active keys with missing translations

        // Only get keys that have at least one missing translation
        $query->where(function ($q) {
            $q->whereNull('en')
                ->orWhereNull('kh')
                ->orWhereNull('zh')
                ->orWhere('en', '')
                ->orWhere('kh', '')
                ->orWhere('zh', '');
        });

        $keys = $query->get();

        if ($keys->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No keys found with missing translations'
            ], 400);
        }

        $translated = 0;
        $failed = 0;

        foreach ($keys as $key) {
            try {
                $result = $this->translateService->autoTranslateKey($key);
                if ($result['success']) {
                    $translated++;
                } else {
                    $failed++;
                }

                // Delay to avoid rate limiting
                usleep(200000); // 200ms
            } catch (\Exception $e) {
                $failed++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Translated {$translated} keys successfully" . ($failed > 0 ? ", {$failed} failed" : ""),
            'translated' => $translated,
            'failed' => $failed
        ]);
    }

    /**
     * Export translations to PHP file format
     */
    public function exportGroup(Request $request)
    {
        $validated = $request->validate([
            'group' => 'required|string',
            'locale' => 'required|in:en,kh,zh'
        ]);

        $array = TranslationKey::exportToArray($validated['group'], $validated['locale']);

        $phpContent = "<?php\n\nreturn " . var_export($array, true) . ";\n";

        return response()->json([
            'success' => true,
            'content' => $phpContent,
            'filename' => "{$validated['group']}.php"
        ]);
    }

    /**
     * Import translations from existing lang files
     */
    public function importFromLangFiles(Request $request)
    {
        $validated = $request->validate([
            'group' => 'required|string',
            'locale' => 'required|in:en,kh,zh'
        ]);

        $langPath = lang_path("{$validated['locale']}/{$validated['group']}.php");

        if (!file_exists($langPath)) {
            return response()->json([
                'success' => false,
                'message' => 'Language file not found'
            ], 404);
        }

        $translations = include $langPath;
        $imported = 0;
        $updated = 0;

        $this->importArrayRecursive($translations, $validated['group'], $validated['locale'], '', $imported, $updated);

        return response()->json([
            'success' => true,
            'message' => "Imported {$imported} new keys, updated {$updated} existing keys",
            'imported' => $imported,
            'updated' => $updated
        ]);
    }

    /**
     * Helper to recursively import nested arrays
     */
    private function importArrayRecursive($array, $group, $locale, $prefix, &$imported, &$updated)
    {
        foreach ($array as $key => $value) {
            $fullKey = $prefix ? "{$prefix}.{$key}" : $key;
            $keyName = "{$group}.{$fullKey}";

            if (is_array($value)) {
                $this->importArrayRecursive($value, $group, $locale, $fullKey, $imported, $updated);
            } else {
                $translationKey = TranslationKey::where('key_name', $keyName)->first();

                if ($translationKey) {
                    $translationKey->update([
                        $locale => $value,
                        'modify_by' => $this->getValidUserId(),
                        'modify_date' => now()
                    ]);
                    $updated++;
                } else {
                    TranslationKey::create([
                        'key_name' => $keyName,
                        'group' => $group,
                        $locale => $value,
                        'is_active' => true,
                        'auto_translated' => false,
                        'created_by' => $this->getValidUserId(),
                        'created_date' => now()
                    ]);
                    $imported++;
                }
            }
        }
    }

    /**
     * Get statistics
     */
    public function getStatistics()
    {
        $total = TranslationKey::count();
        $active = TranslationKey::where('is_active', true)->count();
        $autoTranslated = TranslationKey::where('auto_translated', true)->count();

        $missingEn = TranslationKey::whereNull('en')->orWhere('en', '')->count();
        $missingKh = TranslationKey::whereNull('kh')->orWhere('kh', '')->count();
        $missingZh = TranslationKey::whereNull('zh')->orWhere('zh', '')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $total,
                'active' => $active,
                'inactive' => $total - $active,
                'auto_translated' => $autoTranslated,
                'missing_translations' => [
                    'en' => $missingEn,
                    'kh' => $missingKh,
                    'zh' => $missingZh
                ],
                'groups' => TranslationKey::getGroups()
            ]
        ]);
    }

    /**
     * Scan all lang directories and get available groups
     */
    public function scanLangFiles()
    {
        $langPath = lang_path();
        $locales = ['en', 'kh', 'zh'];
        $groups = [];
        $totalFiles = 0;

        foreach ($locales as $locale) {
            $localePath = $langPath . DIRECTORY_SEPARATOR . $locale;

            if (is_dir($localePath)) {
                $files = glob($localePath . DIRECTORY_SEPARATOR . '*.php');
                $totalFiles += count($files);

                foreach ($files as $file) {
                    $group = basename($file, '.php');
                    if (!in_array($group, $groups)) {
                        $groups[] = $group;
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'locales' => $locales,
                'groups' => $groups,
                'total_files' => $totalFiles
            ]
        ]);
    }

    /**
     * Load all translation keys from resources/lang files
     */
    public function loadFromLangFiles(Request $request)
    {
        $validated = $request->validate([
            'locales' => 'nullable|array',
            'locales.*' => 'in:en,kh,zh',
            'groups' => 'nullable|array',
            'groups.*' => 'string',
            'merge_existing' => 'nullable|boolean'
        ]);

        $locales = $validated['locales'] ?? ['en', 'kh', 'zh'];
        $groupsFilter = $validated['groups'] ?? null;
        $mergeExisting = $validated['merge_existing'] ?? true;

        DB::beginTransaction();
        try {
            $stats = [
                'imported' => 0,
                'updated' => 0,
                'skipped' => 0,
                'errors' => []
            ];

            $langPath = lang_path();
            $allGroups = [];

            // First pass: collect all groups and keys from all locales
            foreach ($locales as $locale) {
                $localePath = $langPath . DIRECTORY_SEPARATOR . $locale;

                if (!is_dir($localePath)) {
                    continue;
                }

                $files = glob($localePath . DIRECTORY_SEPARATOR . '*.php');

                foreach ($files as $file) {
                    $group = basename($file, '.php');

                    // Filter by groups if specified
                    if ($groupsFilter && !in_array($group, $groupsFilter)) {
                        continue;
                    }

                    if (!isset($allGroups[$group])) {
                        $allGroups[$group] = [];
                    }

                    try {
                        $translations = include $file;

                        if (!is_array($translations)) {
                            $stats['errors'][] = "File {$file} does not return an array";
                            continue;
                        }

                        $this->collectKeysFromArray($translations, $group, $locale, '', $allGroups[$group]);
                    } catch (\Exception $e) {
                        $stats['errors'][] = "Error loading {$file}: " . $e->getMessage();
                    }
                }
            }

            // Second pass: create or update records in database
            foreach ($allGroups as $group => $keys) {
                foreach ($keys as $keyName => $translations) {
                    $existingKey = TranslationKey::where('key_name', $keyName)->first();

                    if ($existingKey) {
                        if ($mergeExisting) {
                            // Update only non-empty values
                            $updateData = ['modify_by' => $this->getValidUserId(), 'modify_date' => now()];

                            foreach ($locales as $locale) {
                                if (!empty($translations[$locale])) {
                                    $updateData[$locale] = $translations[$locale];
                                }
                            }

                            $existingKey->update($updateData);
                            $stats['updated']++;
                        } else {
                            $stats['skipped']++;
                        }
                    } else {
                        // Create new translation key
                        $createData = [
                            'key_name' => $keyName,
                            'group' => $group,
                            'is_active' => true,
                            'auto_translated' => false,
                            'created_by' => $this->getValidUserId(),
                            'created_date' => now()
                        ];

                        foreach ($locales as $locale) {
                            $createData[$locale] = $translations[$locale] ?? null;
                        }

                        TranslationKey::create($createData);
                        $stats['imported']++;
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Loaded {$stats['imported']} new keys, updated {$stats['updated']} keys, skipped {$stats['skipped']} keys",
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to load from lang files: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper to collect keys from nested arrays
     */
    private function collectKeysFromArray($array, $group, $locale, $prefix, &$collection)
    {
        foreach ($array as $key => $value) {
            $fullKey = $prefix ? "{$prefix}.{$key}" : $key;
            $keyName = "{$group}.{$fullKey}";

            if (is_array($value)) {
                $this->collectKeysFromArray($value, $group, $locale, $fullKey, $collection);
            } else {
                if (!isset($collection[$keyName])) {
                    $collection[$keyName] = ['en' => null, 'kh' => null, 'zh' => null];
                }

                $collection[$keyName][$locale] = $value;
            }
        }
    }

    /**
     * Sync database with lang files (two-way sync)
     */
    public function syncWithLangFiles(Request $request)
    {
        $validated = $request->validate([
            'direction' => 'required|in:import,export,both',
            'groups' => 'nullable|array',
            'groups.*' => 'string'
        ]);

        $direction = $validated['direction'];
        $groupsFilter = $validated['groups'] ?? null;
        $results = [];

        try {
            // Import from lang files to database
            if (in_array($direction, ['import', 'both'])) {
                $importRequest = new Request([
                    'locales' => ['en', 'kh', 'zh'],
                    'groups' => $groupsFilter,
                    'merge_existing' => true
                ]);

                $importResponse = $this->loadFromLangFiles($importRequest);
                $results['import'] = json_decode($importResponse->getContent(), true);
            }

            // Export from database to lang files
            if (in_array($direction, ['export', 'both'])) {
                $exportStats = $this->exportAllToFiles($groupsFilter);
                $results['export'] = $exportStats;
            }

            return response()->json([
                'success' => true,
                'message' => 'Synchronization completed',
                'data' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sync failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export all translation keys to lang files
     */
    private function exportAllToFiles($groupsFilter = null)
    {
        $stats = ['files_created' => 0, 'files_updated' => 0, 'errors' => []];
        $locales = ['en', 'kh', 'zh'];
        $groups = $groupsFilter ?? TranslationKey::getGroups();

        foreach ($groups as $group) {
            foreach ($locales as $locale) {
                try {
                    $array = TranslationKey::exportToArray($group, $locale);

                    if (empty($array)) {
                        continue;
                    }

                    $langPath = lang_path($locale);

                    // Create directory if it doesn't exist
                    if (!is_dir($langPath)) {
                        mkdir($langPath, 0755, true);
                    }

                    $filePath = $langPath . DIRECTORY_SEPARATOR . $group . '.php';
                    $phpContent = "<?php\n\nreturn " . var_export($array, true) . ";\n";

                    $existed = file_exists($filePath);
                    file_put_contents($filePath, $phpContent);

                    if ($existed) {
                        $stats['files_updated']++;
                    } else {
                        $stats['files_created']++;
                    }
                } catch (\Exception $e) {
                    $stats['errors'][] = "Error exporting {$group}.{$locale}: " . $e->getMessage();
                }
            }
        }

        return $stats;
    }
}
