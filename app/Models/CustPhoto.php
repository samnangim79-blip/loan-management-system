<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustPhoto extends Model
{
  protected $table = 'cust_photos';
  protected $primaryKey = 'cust_photo_id';
  public $timestamps = false;

  protected $fillable = [
    'cust_id',
    'file_name',
    'photo_type',
    'date_added',
    'status',
    'remark',
    'user_id'
  ];

  protected $casts = [
    'date_added' => 'date'
  ];

  const PHOTO_TYPE_CUSTOMER = 0;
  const PHOTO_TYPE_ACCOUNT = 1;
  const PHOTO_TYPE_DOCUMENT = 2; // ID cards, passports, etc.
  const PHOTO_TYPE_OTHER = 3;

  const STATUS_ACTIVE = 0;
  const STATUS_INACTIVE = 1;

  public function customer()
  {
    return $this->belongsTo(Customer::class, 'cust_id', 'cust_id');
  }

  public function user()
  {
    return $this->belongsTo(UserLogin::class, 'user_id', 'user_id');
  }
}
