<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NonWorkingDay extends Model
{
  protected $table = 'non_working_days';
  public $timestamps = false;
  public $incrementing = false;

  protected $fillable = [
    'non_work_day'
  ];
}
