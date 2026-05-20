<?php
// app/Models/District.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = 'districts';
    public $timestamps = true;

    protected $fillable = [
        'type', 'code', 'name_kh', 'name_en', 'province_id'
    ];

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }

    public function communes()
    {
        return $this->hasMany(Commune::class, 'district_id', 'id');
    }
}
