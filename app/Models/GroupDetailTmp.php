<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupDetailTmp extends Model
{
    protected $table = 'group_detail_tmps';

    protected $primaryKey = 'group_detail_id';

    public $timestamps = false;

    protected $fillable = [
        'group_id',
        'contract_no',
    ];

    public function group()
    {
        return $this->belongsTo(GroupTmp::class, 'group_id', 'group_id');
    }
}
