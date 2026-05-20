<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupTmp extends Model
{
    protected $table = 'group_tmps';

    protected $primaryKey = 'group_id';

    public $timestamps = false;

    protected $fillable = [
        'group_name',
        'date_issue',
        'added_by',
        'added_date',
        'updated_by',
        'updated_date',
        'is_approve',
    ];

    protected $casts = [
        'date_issue' => 'date',
        'added_date' => 'date',
        'updated_date' => 'date',
    ];

    public function details()
    {
        return $this->hasMany(GroupDetailTmp::class, 'group_id', 'group_id');
    }
}
