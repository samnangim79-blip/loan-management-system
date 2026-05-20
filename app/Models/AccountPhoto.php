<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountPhoto extends Model
{
    protected $table = 'account_photos';
    protected $primaryKey = 'acct_photo_id';
    public $timestamps = false;

    protected $fillable = [
        'acct_id',
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

    const PHOTO_TYPE_ACCOUNT = 0;
    const PHOTO_TYPE_DOCUMENT = 1;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    public function account()
    {
        return $this->belongsTo(AccountInfo::class, 'acct_id', 'acct_id');
    }

    public function user()
    {
        return $this->belongsTo(UserLogin::class, 'user_id', 'user_id');
    }
}
