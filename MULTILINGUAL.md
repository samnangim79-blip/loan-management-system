# Multilingual System Documentation

This Laravel loan management system now supports multiple languages: **English**, **Khmer**, and **Chinese**.

## 🌐 Supported Languages

| Language | Code | Flag | Native Name |
| -------- | ---- | ---- | ----------- |
| English  | `en` | 🇺🇸   | English     |
| Khmer    | `kh` | 🇰🇭   | ខ្មែរ       |
| Chinese  | `zh` | 🇨🇳   | 中文        |

## 🏗️ System Architecture

### Configuration

-   **Config file**: `config/app.php` contains `supported_locales` array
-   **Default locale**: English (`en`)
-   **Session-based**: Language preference stored in user session

### Translation Files

All translations are stored in `/lang/{locale}/common.php`:

```
lang/
├── en/common.php  (English)
├── kh/common.php  (Khmer)
└── zh/common.php  (Chinese)
```

### Middleware

-   **SetLocale middleware**: Automatically applies language from session/request
-   **Global application**: Applied to all web routes

## 🔧 Implementation Details

### Language Switching

-   **Language Controller**: `LanguageController` handles language changes
-   **Routes**:
    -   `POST /language/switch` - Switch language
    -   `GET /language/current` - Get current language info
-   **Language switcher component**: `resources/views/components/language-switcher.blade.php`

### Frontend Integration

#### In Blade Templates

```php
// Basic translation
{{ __('common.nav.dashboard') }}

// With parameters
{{ __('common.messages.welcome', ['name' => $user->name]) }}

// Get current language info
@php $currentLang = current_language(); @endphp
{{ $currentLang['name'] }} ({{ $currentLang['native'] }})
```

#### DataTables Support

DataTables automatically load language files:

```javascript
$("#table").DataTable({
    language: {
        url: "{{ asset('assets/js/datatables-' . app()->getLocale() . '.json') }}",
    },
});
```

#### Helper Functions

```php
// Get JavaScript-ready translations
js_translations(['common.nav.dashboard', 'common.actions.save'])

// Get current language data
current_language()

// Get all supported languages
supported_languages()

// Generate language switch form
language_switch_form('kh', 'btn btn-primary')
```

## 📁 File Structure

### Key Files Created/Modified

```
app/
├── Http/
│   ├── Controllers/LanguageController.php
│   └── Middleware/SetLocale.php
├── Helpers/LanguageHelpers.php
config/app.php (updated)
lang/
├── en/common.php
├── kh/common.php
└── zh/common.php
public/assets/js/
├── datatables-en.json
├── datatables-kh.json
└── datatables-zh.json
resources/views/
├── components/language-switcher.blade.php
└── admin/layouts/admin_layout.blade.php (updated)
routes/web.php (updated)
bootstrap/app.php (updated)
composer.json (updated)
```

## 🎯 Usage Examples

### Adding New Translations

1. Add to all language files:

```php
// lang/en/common.php
'new_section' => [
    'title' => 'New Feature',
    'description' => 'This is a new feature',
]

// lang/kh/common.php
'new_section' => [
    'title' => 'មុខងារថ្មី',
    'description' => 'នេះជាមុខងារថ្មី',
]
```

2. Use in views:

```php
<h1>{{ __('common.new_section.title') }}</h1>
<p>{{ __('common.new_section.description') }}</p>
```

### JavaScript Integration

```javascript
// Get translations for JS
const translations = {!! js_translations([
    'common.messages.confirm_delete',
    'common.actions.save',
    'common.actions.cancel'
]) !!};

// Use in SweetAlert2
Swal.fire({
    title: translations['common.messages.confirm_delete'],
    showCancelButton: true,
    confirmButtonText: translations['common.actions.save'],
    cancelButtonText: translations['common.actions.cancel']
});
```

## 🔄 Language Switching Flow

1. User clicks language option in header dropdown
2. Form submits to `POST /language/switch`
3. `LanguageController::switch()` validates and stores locale in session
4. `SetLocale` middleware applies locale on subsequent requests
5. All `__()` functions and Blade directives use the new locale

## 🎨 Styling

The language switcher includes flag icons using CSS background images:

-   English: UK flag
-   Khmer: Cambodia flag
-   Chinese: China flag

## 🚀 Future Enhancements

-   Add more languages (Thai, Vietnamese, etc.)
-   Implement database-driven translations for admin-configurable content
-   Add RTL (Right-to-Left) support for Arabic languages
-   Create translation management interface for admins
-   Add language-specific number/currency formatting

## 🧪 Testing

Visit `/test/language` to see the multilingual system in action with sample translations across all supported languages.

---

The multilingual system is now fully integrated throughout the loan management application, providing a seamless experience for users in English, Khmer, and Chinese languages.
