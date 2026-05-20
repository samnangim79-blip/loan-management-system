<?php
// app/Models/Collateral.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collateral extends Model
{
    protected $table = 'collaterals';
    protected $primaryKey = 'collateral_id';
    public $timestamps = false;

    protected $fillable = [
        'loan_schedule_id', 'collateral_type_id', 'collateral_value',
        'collateral_no', 'date_issue', 'remarks'
    ];

    protected $casts = [
        'date_issue' => 'date',
        'collateral_value' => 'decimal:5'
    ];

    public function loanSchedule()
    {
        return $this->belongsTo(LoanSchedule::class, 'loan_schedule_id', 'loan_schedule_id');
    }

    public function collateralType()
    {
        return $this->belongsTo(CollateralType::class, 'collateral_type_id', 'collateral_type_id');
    }

    public function details()
    {
        return $this->hasMany(CollateralDetail::class, 'collateral_id', 'collateral_id');
    }

    public function releases()
    {
        return $this->hasMany(CollateralRelease::class, 'collateral_id', 'collateral_id');
    }

    public function isReleased()
    {
        return $this->releases()->whereNotNull('approved_date')->exists();
    }
}
