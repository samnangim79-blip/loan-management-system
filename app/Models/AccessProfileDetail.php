<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessProfileDetail extends Model
{
  protected $table = 'access_profile_details';
  protected $primaryKey = 'profile_detail_id';
  public $timestamps = false;

  protected $fillable = [
    'profile_id',
    'module_id'
  ];

  public function profile()
  {
    return $this->belongsTo(AccessProfile::class, 'profile_id', 'profile_id');
  }

  public function module()
  {
    return $this->belongsTo(Module::class, 'module_id', 'module_id');
  }
}
