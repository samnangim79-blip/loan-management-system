<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JointAccountHolder extends Model
{
    protected $table = 'joint_account_holders';

    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = ['acct_id', 'cust_id'];

    protected $fillable = [
        'acct_id',
        'cust_id',
        'joint_date',
        'joint_added_by',
        'status',
        'updated_date',
        'updated_by',
    ];

    protected $casts = [
        'joint_date' => 'date',
        'updated_date' => 'datetime',
    ];

    const STATUS_ACTIVE = 0;

    const STATUS_DELETED = 1;

    public function account()
    {
        return $this->belongsTo(AccountInfo::class, 'acct_id', 'acct_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'cust_id', 'cust_id');
    }

    /**
     * Override getKey for composite primary key
     */
    public function getKey()
    {
        return [$this->acct_id, $this->cust_id];
    }
}
