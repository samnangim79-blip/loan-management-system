<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
  protected $table = 'configs';
  protected $primaryKey = 'config_id';
  public $timestamps = false;
  public $incrementing = false;

  protected $fillable = [
    'config_id',
    'config_name',
    'config_value',
    'remark'
  ];

  /**
   * Get config value by name
   */
  public static function getValue(string $name, $default = null)
  {
    $config = static::where('config_name', $name)->first();
    return $config ? $config->config_value : $default;
  }
}
