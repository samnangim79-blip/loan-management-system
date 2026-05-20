<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurposeLoan extends Model
{
    protected $table = 'purpose_loans';
    protected $primaryKey = 'purpose_id';
    public $timestamps = false;

    protected $fillable = [
        'purpose_type'
    ];

    public function loanSchedules()
    {
        return $this->hasMany(LoanSchedule::class, 'purpose_id', 'purpose_id');
    }
}
