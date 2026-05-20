<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TranslationKey extends Model
{
    protected $table = 'translation_keys';
    protected $primaryKey = 'key_id';
    public $timestamps = false;

    protected $fillable = [
        'key_name',
        'group',
        'en',
        'kh',
        'zh',
        'description',
        'is_active',
        'auto_translated',
        'created_by',
        'created_date',
        'modify_by',
        'modify_date'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'auto_translated' => 'boolean',
        'created_date' => 'date',
        'modify_date' => 'date'
    ];

    /**
     * Scope to get only active keys
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by group
     */
    public function scopeByGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Get translation for a specific locale
     */
    public function getTranslation($locale)
    {
        return $this->$locale ?? $this->en;
    }

    /**
     * Set translation for a specific locale
     */
    public function setTranslation($locale, $value)
    {
        if (in_array($locale, ['en', 'kh', 'zh'])) {
            $this->$locale = $value;
            return true;
        }
        return false;
    }

    /**
     * Check if key has translation for locale
     */
    public function hasTranslation($locale)
    {
        return !empty($this->$locale);
    }

    /**
     * Get all translations as array
     */
    public function getAllTranslations()
    {
        return [
            'en' => $this->en,
            'kh' => $this->kh,
            'zh' => $this->zh,
        ];
    }

    /**
     * Get missing translations
     */
    public function getMissingTranslations()
    {
        $missing = [];
        foreach (['en', 'kh', 'zh'] as $locale) {
            if (empty($this->$locale)) {
                $missing[] = $locale;
            }
        }
        return $missing;
    }

    /**
     * Get unique groups
     */
    public static function getGroups()
    {
        return static::select('group')
            ->distinct()
            ->orderBy('group')
            ->pluck('group')
            ->toArray();
    }

    /**
     * Export keys to PHP array format for lang files
     */
    public static function exportToArray($group, $locale = 'en')
    {
        $keys = static::where('group', $group)
            ->where('is_active', true)
            ->get();

        $array = [];
        foreach ($keys as $key) {
            $keyParts = explode('.', str_replace($group . '.', '', $key->key_name));
            $value = $key->getTranslation($locale) ?? '';

            $current = &$array;
            foreach ($keyParts as $i => $part) {
                if ($i === count($keyParts) - 1) {
                    $current[$part] = $value;
                } else {
                    if (!isset($current[$part])) {
                        $current[$part] = [];
                    }
                    $current = &$current[$part];
                }
            }
        }

        return $array;
    }
}
