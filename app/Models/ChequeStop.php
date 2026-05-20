<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChequeStop extends Model
{
  protected $table = 'cheque_stops';
  protected $primaryKey = 'chq_stop_id';
  public $timestamps = false;

  protected $fillable = [
    'chq_no',
    'reason',
    'note',
    'stopped_by',
    'stopped_date',
    'released_by',
    'released_date'
  ];

  protected $casts = [
    'stopped_date' => 'datetime',
    'released_date' => 'datetime'
  ];

  public function stoppedBy()
  {
    return $this->belongsTo(UserLogin::class, 'stopped_by', 'user_id');
  }

  public function releasedBy()
  {
    return $this->belongsTo(UserLogin::class, 'released_by', 'user_id');
  }
}
