# Translation Keys Management with Google Auto-Translation

Complete CRUD system for managing multilingual translation keys with automatic Google Translate integration.

## Features

✅ **Full CRUD Operations** - Create, read, update, delete translation keys
✅ **Google Auto-Translation** - Automatically translate missing languages
✅ **Multi-Language Support** - English, Khmer (km), Chinese (zh-CN)
✅ **Bulk Operations** - Translate multiple keys at once
✅ **Import/Export** - Import from existing lang files, export to PHP format
✅ **Group Management** - Organize translations by groups (common, validation, etc.)
✅ **Statistics Dashboard** - Track translation progress
✅ **DataTables Integration** - Server-side processing for performance

## Installation

Already installed! The system includes:

-   **Package**: `stichoza/google-translate-php` (v5.3.1)
-   **Migration**: `translation_keys` table created
-   **Routes**: All endpoints configured under `/translation-keys`

## Usage

### Access the Interface

Navigate to: **`/translation-keys`**

### Key Features

#### 1. **Add Translation Key**

```
Key Name: common.general.save
Group: common
English: Save
Auto-translate: ✓ (automatically fills Khmer & Chinese)
```

#### 2. **Auto-Translate Single Key**

Click the 🤖 button on any key to automatically translate missing languages.

#### 3. **Bulk Auto-Translate**

Translate all keys in a group or all keys at once.

#### 4. **Import from Lang Files**

Import existing translations from `lang/{locale}/{group}.php` files.

#### 5. **Export to PHP**

Export translations back to PHP array format for lang files.

## API Endpoints

### CRUD Operations

```php
GET    /translation-keys              // List page
GET    /translation-keys/data         // DataTables JSON
POST   /translation-keys              // Create key
GET    /translation-keys/{id}         // Show key
PUT    /translation-keys/{id}         // Update key
DELETE /translation-keys/{id}         // Delete key
```

### Auto-Translation

```php
POST   /translation-keys/{id}/auto-translate     // Translate single key
POST   /translation-keys/bulk-auto-translate     // Bulk translate
```

### Import/Export

```php
POST   /translation-keys/import        // Import from lang files
POST   /translation-keys/export        // Export to PHP format
```

### Statistics

```php
GET    /translation-keys/statistics    // Get stats
```

## Database Schema

```sql
CREATE TABLE translation_keys (
    key_id BIGINT PRIMARY KEY AUTO_INCREMENT,
    key_name VARCHAR(255) UNIQUE,
    group VARCHAR(100),
    en TEXT,           -- English
    kh TEXT,           -- Khmer
    zh TEXT,           -- Chinese
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    auto_translated BOOLEAN DEFAULT FALSE,
    created_by BIGINT,
    created_date DATE,
    modify_by BIGINT,
    modify_date DATE
);
```

## Models & Services

### TranslationKey Model

```php
use App\Models\TranslationKey;

// Get all active keys
$keys = TranslationKey::active()->get();

// Get by group
$keys = TranslationKey::byGroup('common')->get();

// Check missing translations
$missing = $key->getMissingTranslations(); // ['kh', 'zh']

// Get translation for locale
$text = $key->getTranslation('kh');

// Export group to array
$array = TranslationKey::exportToArray('common', 'en');
```

### GoogleTranslateService

```php
use App\Services\GoogleTranslateService;

$service = app(GoogleTranslateService::class);

// Translate single text
$result = $service->translate('Hello', 'kh', 'en');
// Result: "សួស្តី"

// Translate to multiple languages
$translations = $service->translateToMultiple('Hello', ['kh', 'zh'], 'en');
// Result: ['kh' => 'សួស្តី', 'zh' => '你好']

// Auto-translate a TranslationKey model
$result = $service->autoTranslateKey($translationKey);
```

## Controller Usage

```php
use App\Http\Controllers\TranslationKeyController;

// Inject the service in your controller
public function __construct(GoogleTranslateService $translateService)
{
    $this->translateService = $translateService;
}

// Auto-translate a key
$result = $this->translateService->autoTranslateKey($translationKey);
```

## Language Code Mapping

| Our Code | Google Code | Language             |
| -------- | ----------- | -------------------- |
| `en`     | `en`        | English              |
| `kh`     | `km`        | Khmer                |
| `zh`     | `zh-CN`     | Chinese (Simplified) |

## Workflow Example

### 1. Create a new translation key

```
POST /translation-keys
{
    "key_name": "common.buttons.submit",
    "group": "common",
    "en": "Submit",
    "auto_translate": true
}
```

Result:

-   English: "Submit"
-   Khmer: "ដាក់ស្នើ" (auto-translated)
-   Chinese: "提交" (auto-translated)

### 2. Import existing translations

```
POST /translation-keys/import
{
    "group": "common",
    "locale": "en"
}
```

### 3. Bulk auto-translate missing keys

```
POST /translation-keys/bulk-auto-translate
{
    "group": "common"
}
```

### 4. Export to PHP file

```
POST /translation-keys/export
{
    "group": "common",
    "locale": "kh"
}
```

Returns PHP file content ready to save to `lang/kh/common.php`.

## Statistics Dashboard

The index page shows:

-   **Total Keys**: All translation keys in database
-   **Active Keys**: Currently active keys
-   **Auto-Translated**: Keys that were auto-translated
-   **Missing Translations**: Total count of missing translations across all languages

## Best Practices

1. **Use Dot Notation**: `group.category.subcategory.key`

    ```
    common.general.save
    validation.required.email
    ```

2. **Group Organization**: Keep related translations together

    - `common` - General UI elements
    - `validation` - Validation messages
    - `auth` - Authentication messages
    - `customers` - Customer-specific translations

3. **Primary Language**: Always start with English (en) as it's used as the source for auto-translation

4. **Review Auto-Translations**: While Google Translate is good, always review auto-translated content for context and accuracy

5. **Bulk Operations**: Use bulk auto-translate for large groups, but be aware of rate limiting (includes automatic delays)

## Rate Limiting

The service includes automatic delays to avoid Google Translate rate limiting:

-   Single translation: No delay
-   Batch translation: 100ms delay between translations
-   Bulk auto-translate: 200ms delay between keys

## Error Handling

All operations return JSON responses:

**Success:**

```json
{
    "success": true,
    "message": "Operation completed",
    "data": { ... }
}
```

**Error:**

```json
{
    "success": false,
    "message": "Error description"
}
```

## Technical Details

### Project Conventions Followed

✅ No Eloquent timestamps (`public $timestamps = false`)
✅ Manual date tracking (`created_date`, `modify_date`)
✅ Auth fallback: `auth()->id() ?? 1`
✅ DataTables server-side pattern
✅ JSON response convention
✅ Database transaction wrapping
✅ Bootstrap 5 + jQuery UI

### Dependencies

-   **stichoza/google-translate-php**: Free Google Translate API (no API key needed)
-   **yajra/laravel-datatables**: Server-side DataTables
-   **Bootstrap 5**: UI framework
-   **SweetAlert2**: Confirmations
-   **Toastr**: Notifications

## Troubleshooting

### Translation fails

-   Check internet connection (Google Translate requires external access)
-   Verify language codes are correct
-   Check error logs: `storage/logs/laravel.log`

### Rate limiting

-   Increase delays in `GoogleTranslateService`
-   Process smaller batches
-   Use import for bulk operations instead of API calls

### Import not finding files

-   Ensure lang files exist: `lang/{locale}/{group}.php`
-   Check file paths are correct
-   Verify file returns array (not syntax errors)

## Future Enhancements

-   [ ] Add more languages (French, Spanish, etc.)
-   [ ] Translation memory (reuse previous translations)
-   [ ] Translation review workflow
-   [ ] Version history
-   [ ] API key configuration for enterprise Google Translate
-   [ ] Translation suggestions from AI
-   [ ] Sync with actual lang files automatically

## Support

For issues or questions, check:

-   Laravel logs: `storage/logs/laravel.log`
-   Browser console for JavaScript errors
-   Network tab for failed AJAX requests

---

**Access URL**: `/translation-keys`
**Created**: December 24, 2025
**Version**: 1.0.0
