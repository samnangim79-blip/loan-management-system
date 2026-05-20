<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
  protected $table = 'modules';
  protected $primaryKey = 'module_id';
  public $timestamps = false;
  public $incrementing = false;

  protected $fillable = [
    'module_id',
    'module',
    'control_name',
    'url',
    'type',
    'status'
  ];

  const TYPE_ALL = 1;
  const TYPE_BRANCH = 2;
  const TYPE_HEAD_OFFICE = 3;

  public function accessProfileDetails()
  {
    return $this->hasMany(AccessProfileDetail::class, 'module_id', 'module_id');
  }
}
