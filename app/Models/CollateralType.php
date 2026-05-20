<?php
// app/Models/CollateralType.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollateralType extends Model
{
    protected $table = 'collateral_types';
    protected $primaryKey = 'collateral_type_id';
    public $timestamps = false;

    protected $fillable = [
        'collateral_type'
    ];

    public function collaterals()
    {
        return $this->hasMany(Collateral::class, 'collateral_type_id', 'collateral_type_id');
    }

    public function typeDetails()
    {
        return $this->hasMany(CollateralTypeDetail::class, 'collateral_type_id', 'collateral_type_id');
    }
}
