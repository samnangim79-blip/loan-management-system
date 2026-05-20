# Translation Keys - Lang Files Integration

## Overview

The Translation Keys system now supports bidirectional synchronization with Laravel's traditional `resources/lang/` directory structure. This allows you to:

-   **Import** existing translation files into the database
-   **Export** database translations to PHP files
-   **Sync** bidirectionally between files and database
-   **Scan** to discover available translation groups

## Features

### 1. Scan Lang Files

**Purpose:** Discover all available translation files in the `resources/lang/` directory.

**Usage:**

-   Click the **"Scan Lang"** button in the Translation Keys page
-   Shows available locales (en, kh, zh), groups, and total files
-   Automatically updates the "Load from Lang" modal with available groups

**API Endpoint:**

```http
GET /translation-keys/scan-lang-files
```

**Response:**

```json
{
    "success": true,
    "data": {
        "locales": ["en", "kh", "zh"],
        "groups": ["common", "auth", "validation"],
        "total_files": 9
    }
}
```

### 2. Load from Lang Files

**Purpose:** Import translation keys from existing PHP lang files into the database.

**Features:**

-   Select specific locales (en, kh, zh) or all
-   Filter by groups or load all groups
-   Choose merge strategy:
    -   **Skip:** Keep existing database values (default)
    -   **Update:** Overwrite with file values

**Usage:**

1. Click **"Load Lang"** button
2. Select locales and groups (optional)
3. Choose merge mode
4. Click "Load Keys"

**API Endpoint:**

```http
POST /translation-keys/load-from-lang
Content-Type: application/json

{
  "locales": ["en", "kh", "zh"],
  "groups": ["common"],  // Optional
  "merge_mode": "skip"   // "skip" or "update"
}
```

**Response:**

```json
{
    "success": true,
    "message": "Loaded 150 translation keys",
    "data": {
        "created": 100,
        "updated": 50,
        "skipped": 20,
        "total": 150
    }
}
```

### 3. Sync with Lang Files

**Purpose:** Bidirectional synchronization between database and files.

**Sync Directions:**

#### Import (Files → Database)

-   Loads all keys from lang files into database
-   Updates existing keys with file values
-   Creates new keys from files

#### Export (Database → Files)

-   Writes all database keys to PHP lang files
-   Creates missing files
-   Updates existing files with database values
-   Preserves file structure and nested arrays

#### Both (Two-way Sync)

-   Imports from files first
-   Then exports to files
-   Ensures complete consistency

**Usage:**

1. Click **"Sync"** button
2. Choose direction (Import/Export/Both)
3. Confirm the operation
4. Wait for completion

**API Endpoint:**

```http
POST /translation-keys/sync-with-lang
Content-Type: application/json

{
  "direction": "both"  // "import", "export", or "both"
}
```

**Response:**

```json
{
    "success": true,
    "message": "Sync completed successfully",
    "data": {
        "imported": 150,
        "exported": 145,
        "files_created": 3,
        "files_updated": 6
    }
}
```

## File Structure

### Expected Lang Directory Structure

```
resources/
  lang/
    en/
      common.php
      auth.php
      validation.php
    kh/
      common.php
      auth.php
      validation.php
    zh/
      common.php
      auth.php
      validation.php
```

### PHP File Format

Lang files must return associative arrays:

```php
<?php
// resources/lang/en/common.php

return [
    'general' => [
        'save' => 'Save',
        'cancel' => 'Cancel',
        'delete' => 'Delete'
    ],
    'messages' => [
        'success' => 'Operation successful',
        'error' => 'An error occurred'
    ]
];
```

### Database Key Naming

Nested arrays are flattened with dot notation:

-   File: `['general' => ['save' => 'Save']]`
-   Key: `common.general.save`
-   Group: `common`

## Backend Implementation

### Controller Methods

#### `scanLangFiles()`

Scans `resources/lang/` to discover available files.

```php
public function scanLangFiles()
{
    $langPath = resource_path('lang');
    $locales = ['en', 'kh', 'zh'];
    $groups = [];

    // Scan directories...

    return response()->json([
        'success' => true,
        'data' => [
            'locales' => $locales,
            'groups' => $groups,
            'total_files' => $totalFiles
        ]
    ]);
}
```

#### `loadFromLangFiles(Request $request)`

Loads keys from lang files with merge strategies.

**Parameters:**

-   `locales[]`: Array of locale codes
-   `groups[]`: (Optional) Array of group names
-   `merge_mode`: 'skip' or 'update'

**Process:**

1. Validates request data
2. Scans specified locales/groups
3. Reads PHP files using `include`
4. Flattens nested arrays with dot notation
5. Creates/updates database records
6. Uses database transactions for safety

#### `syncWithLangFiles(Request $request)`

Orchestrates bidirectional sync.

**Parameters:**

-   `direction`: 'import', 'export', or 'both'

**Import Process:**

1. Calls `loadFromLangFiles()` with update mode
2. Returns imported count

**Export Process:**

1. Gets all active translation keys
2. Groups by locale and group
3. Writes to PHP files
4. Returns files created/updated

**Both Process:**

1. Runs import
2. Runs export
3. Combines statistics

#### `collectKeysFromArray(array $array, string $prefix)`

Helper to recursively flatten nested arrays.

```php
protected function collectKeysFromArray(array $array, string $prefix = ''): array
{
    $keys = [];
    foreach ($array as $key => $value) {
        $fullKey = $prefix ? "$prefix.$key" : $key;
        if (is_array($value)) {
            $keys = array_merge($keys, $this->collectKeysFromArray($value, $fullKey));
        } else {
            $keys[$fullKey] = $value;
        }
    }
    return $keys;
}
```

## Frontend Integration

### UI Components

#### Buttons (Added to card header)

```blade
<div class="btn-group me-2">
  <button type="button" class="btn btn-secondary btn-sm" id="scanLangBtn">
    <i class="fas fa-search"></i> Scan Lang
  </button>
  <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#loadLangModal">
    <i class="fas fa-download"></i> Load Lang
  </button>
  <button type="button" class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#syncModal">
    <i class="fas fa-sync"></i> Sync
  </button>
</div>
```

#### Load Lang Modal

-   Locale checkboxes (en, kh, zh)
-   Groups multi-select dropdown
-   Merge mode radio buttons (skip/update)
-   Progress indicator

#### Sync Modal

-   Direction radio buttons (import/export/both)
-   Warning alert
-   Progress indicator

### JavaScript Functions

#### Scan Button Handler

```javascript
$("#scanLangBtn").click(function () {
    $.ajax({
        url: "{{ route('translation-keys.scan') }}",
        method: "GET",
    }).done(function (response) {
        // Show scan results in SweetAlert
        // Update load modal groups dropdown
    });
});
```

#### Load Form Handler

```javascript
$("#loadLangForm").submit(function (e) {
    e.preventDefault();

    const data = {
        locales: [], // From checkboxes
        groups: $("#load_groups").val(),
        merge_mode: $('input[name="merge_mode"]:checked').val(),
    };

    $.ajax({
        url: "{{ route('translation-keys.load-from-lang') }}",
        method: "POST",
        data: data,
    }).done(function (response) {
        // Show success with statistics
        // Reload table and statistics
    });
});
```

#### Sync Form Handler

```javascript
$("#syncForm").submit(function (e) {
    e.preventDefault();

    const direction = $('input[name="direction"]:checked').val();

    $.ajax({
        url: "{{ route('translation-keys.sync') }}",
        method: "POST",
        data: { direction: direction },
    }).done(function (response) {
        // Show success with statistics
        // Reload table and statistics
    });
});
```

## Use Cases

### Scenario 1: Migrating from File-based to Database

**Problem:** You have existing translation files and want to manage them via the UI.

**Solution:**

1. Click "Scan Lang" to verify files are detected
2. Click "Load Lang"
3. Select all locales
4. Leave groups empty (load all)
5. Choose "Skip" mode (preserve any existing DB keys)
6. Click "Load Keys"

**Result:** All translation keys from files are now in the database and editable via the UI.

---

### Scenario 2: Exporting Database Translations to Files

**Problem:** You've been managing translations in the database and need to deploy to a server that uses file-based translations.

**Solution:**

1. Click "Sync"
2. Choose "Export" direction
3. Confirm the operation

**Result:** All database translations are written to PHP files in `resources/lang/`.

---

### Scenario 3: Keeping Files and Database in Sync

**Problem:** Developers work with files, but the UI is used for quick edits. Need to keep both synchronized.

**Solution:**

1. Before UI editing: Click "Sync" → "Import" (load latest file changes)
2. After UI editing: Click "Sync" → "Export" (write DB changes to files)
3. For complete sync: Click "Sync" → "Both"

**Result:** Files and database stay synchronized.

---

### Scenario 4: Loading Only Specific Groups

**Problem:** You have many translation files but only want to manage certain groups in the database.

**Solution:**

1. Click "Load Lang"
2. Select locales
3. In groups dropdown, select only desired groups (e.g., "common", "auth")
4. Click "Load Keys"

**Result:** Only selected groups are loaded into the database.

## Error Handling

### Common Errors

**File Not Found**

```json
{
    "success": false,
    "message": "Lang file not found: resources/lang/en/common.php"
}
```

**Solution:** Ensure the file exists and path is correct.

---

**Invalid PHP File**

```json
{
    "success": false,
    "message": "Failed to parse lang file: syntax error"
}
```

**Solution:** Check PHP syntax in the lang file. File must return an array.

---

**Permission Denied**

```json
{
    "success": false,
    "message": "Cannot write to file: resources/lang/en/common.php"
}
```

**Solution:** Ensure web server has write permissions to `resources/lang/` directory.

---

**Database Transaction Failed**

```json
{
    "success": false,
    "message": "Database error: Duplicate entry for key 'key_name'"
}
```

**Solution:** Transaction is rolled back automatically. Check for duplicate keys or constraint violations.

## Performance Considerations

### Large Translation Files

-   Files with 1000+ keys may take 10-30 seconds to load
-   Uses database transactions for safety
-   Progress indicators keep users informed

### Rate Limiting (Export)

-   When exporting, multiple file writes occur
-   Add 50-100ms delay between writes if needed
-   Current implementation handles this efficiently

### Memory Usage

-   Large nested arrays are processed recursively
-   PHP memory limit should be adequate (128MB+)
-   No significant memory issues expected

## Best Practices

### 1. Regular Backups

Before major sync operations, backup your database and lang files.

### 2. Use Version Control

Commit lang files to Git before/after sync operations to track changes.

### 3. Test in Development

Test sync operations in development environment before production.

### 4. Choose Merge Strategy Carefully

-   **Skip:** Safe for first import, preserves DB customizations
-   **Update:** Use when files are the source of truth

### 5. Sync Direction

-   **Import:** When developers update files
-   **Export:** When UI changes need to be deployed
-   **Both:** For complete consistency, but may be slow

## Troubleshooting

### Issue: Keys not showing after load

**Check:**

-   Verify `is_active = 1` in database
-   Reload DataTable
-   Check browser console for JS errors

**Solution:**

```javascript
table.ajax.reload();
loadStatistics();
```

---

### Issue: Export creates malformed PHP files

**Check:**

-   Special characters in translations (quotes, backslashes)
-   Array nesting structure

**Solution:** The export method uses `var_export()` which handles escaping automatically.

---

### Issue: Sync takes too long

**Check:**

-   Number of keys being processed
-   Network/disk speed
-   Database performance

**Solution:**

-   Increase timeout: `timeout: 300000` (5 minutes)
-   Process in smaller batches (by group)
-   Optimize database indexes

## Technical Notes

### Database Transaction Safety

All load/sync operations use database transactions:

```php
DB::beginTransaction();
try {
    // Process keys...
    DB::commit();
} catch (\Exception $e) {
    DB::rollback();
    throw $e;
}
```

### File Writing Safety

Export operations check for:

-   Directory existence (creates if missing)
-   Write permissions
-   Valid PHP syntax in output

### Key Naming Convention

-   Group is extracted from file name: `common.php` → `common`
-   Nested keys use dot notation: `general.save` → `common.general.save`
-   Top-level keys: `welcome` → `common.welcome`

### Locale Mapping

-   Database columns: `en`, `kh`, `zh`
-   Lang directories: `en/`, `kh/`, `zh/`
-   Locales are hardcoded but can be made dynamic

## Future Enhancements

### Potential Improvements

1. **Dynamic Locale Detection:** Scan for any locale directory
2. **Partial Group Sync:** Sync only specific groups
3. **Conflict Resolution UI:** Show differences before sync
4. **Audit Log:** Track sync operations
5. **Scheduled Sync:** Cron job for automatic sync
6. **Validation:** Check for missing translations before export
7. **Rollback:** Undo last sync operation

## Related Documentation

-   [Translation Keys Documentation](TRANSLATION_KEYS_DOCUMENTATION.md)
-   [Multilingual System](MULTILINGUAL.md)
-   [Laravel Localization Docs](https://laravel.com/docs/12.x/localization)

## Support

For issues or questions:

1. Check error messages in browser console
2. Review Laravel logs: `storage/logs/laravel.log`
3. Verify file permissions and paths
4. Test with small dataset first

---

**Last Updated:** 2024-12-24
**Version:** 1.0.0
