<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
  protected $table = 'groups';
  protected $primaryKey = 'group_id';
  public $timestamps = false;

  protected $fillable = [
    'group_name',
    'date_issue',
    'added_by',
    'added_date',
    'updated_by',
    'updated_date'
  ];

  protected $casts = [
    'date_issue' => 'date',
    'added_date' => 'date',
    'updated_date' => 'date'
  ];

  public function details()
  {
    return $this->hasMany(GroupDetail::class, 'group_id', 'group_id');
  }

  public function addedBy()
  {
    return $this->belongsTo(UserLogin::class, 'added_by', 'user_id');
  }
}
