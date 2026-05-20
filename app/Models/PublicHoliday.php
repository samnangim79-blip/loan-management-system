<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicHoliday extends Model
{
  protected $table = 'public_holidays';
  protected $primaryKey = 'holiday_id';
  public $timestamps = false;

  protected $fillable = [
    'holiday_date',
    'repeat',
    'description'
  ];

  protected $casts = [
    'holiday_date' => 'date'
  ];

  const REPEAT_MONTHLY = 'm';
  const REPEAT_YEARLY = 'y';

  /**
   * Check if a date is a holiday
   */
  public static function isHoliday($date): bool
  {
    $date = is_string($date) ? \Carbon\Carbon::parse($date) : $date;

    return static::where('holiday_date', $date->format('Y-m-d'))
      ->orWhere(function ($query) use ($date) {
        $query->where('repeat', self::REPEAT_YEARLY)
          ->whereMonth('holiday_date', $date->month)
          ->whereDay('holiday_date', $date->day);
      })
      ->exists();
  }
}
