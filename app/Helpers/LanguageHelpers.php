<?php

/**
 * Get translations for JavaScript
 * This helper function returns JSON-encoded translations for use in frontend JavaScript
 *
 * @param string|array $keys Translation keys or array of keys
 * @param string $locale Specific locale (optional, defaults to current app locale)
 * @return string JSON-encoded translations
 */
if (!function_exists('js_translations')) {
    function js_translations($keys, $locale = null)
    {
        $locale = $locale ?? app()->getLocale();

        if (is_string($keys)) {
            return json_encode(__($keys, [], $locale));
        }

        $translations = [];
        foreach ($keys as $key) {
            $translations[$key] = __($key, [], $locale);
        }

        return json_encode($translations);
    }
}

/**
 * Get current language data for frontend
 * Returns array with current locale, name, flag, etc.
 */
if (!function_exists('current_language')) {
    function current_language()
    {
        $currentLocale = app()->getLocale();
        $locales = config('app.supported_locales');
        return $locales[$currentLocale] ?? $locales['en'];
    }
}

/**
 * Get all supported languages for frontend
 */
if (!function_exists('supported_languages')) {
    function supported_languages()
    {
        return config('app.supported_locales');
    }
}

/**
 * Generate CSRF-safe language switch form HTML
 */
if (!function_exists('language_switch_form')) {
    function language_switch_form($targetLocale, $buttonClass = 'dropdown-item', $showFlag = true)
    {
        $locales = config('app.supported_locales');
        $language = $locales[$targetLocale] ?? null;

        if (!$language) {
            return '';
        }

        $flag = $showFlag ? '<span class="flag-icon flag-icon-' . $language['flag'] . ' me-2"></span>' : '';
        $name = $language['name'];
        $native = $language['native'] ? ' <small class="text-muted">(' . $language['native'] . ')</small>' : '';
        $active = app()->getLocale() === $targetLocale ? ' active' : '';

        return sprintf(
            '<form action="%s" method="POST" class="d-inline">' .
            '%s' .
            '<input type="hidden" name="locale" value="%s">' .
            '<button type="submit" class="%s%s" style="border: none; background: none; width: 100%%; text-align: left;">' .
            '%s%s%s' .
            '</button>' .
            '</form>',
            route('language.switch'),
            csrf_field(),
            $targetLocale,
            $buttonClass,
            $active,
            $flag,
            $name,
            $native
        );
    }
}
