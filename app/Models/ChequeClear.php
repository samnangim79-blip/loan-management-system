<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChequeClear extends Model
{
  protected $table = 'cheque_clears';
  protected $primaryKey = 'chq_clear_id';
  public $timestamps = false;

  protected $fillable = [
    'chq_no',
    'tran_id',
    'clear_by',
    'clear_date'
  ];

  protected $casts = [
    'clear_date' => 'datetime'
  ];

  public function transaction()
  {
    return $this->belongsTo(Trans::class, 'tran_id', 'tran_id');
  }

  public function clearedBy()
  {
    return $this->belongsTo(UserLogin::class, 'clear_by', 'user_id');
  }
}
