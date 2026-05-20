<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $table = 'languages';
    protected $primaryKey = 'language_id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'code',
        'native_name',
        'flag',
        'is_active',
        'is_default',
        'sort_order',
        'created_by',
        'created_date',
        'modify_by',
        'modify_date'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'sort_order' => 'integer',
        'created_date' => 'date',
        'modify_date' => 'date'
    ];

    /**
     * Scope to get only active languages
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get languages ordered by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get the default language
     */
    public static function getDefault()
    {
        return static::where('is_default', true)->first();
    }

    /**
     * Get active languages for dropdown
     */
    public static function getForDropdown()
    {
        return static::active()
            ->ordered()
            ->get()
            ->pluck('name', 'code')
            ->toArray();
    }
}
