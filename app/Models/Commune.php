<?php
// app/Models/Commune.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    protected $table = 'communes';
    public $timestamps = true;

    protected $fillable = [
        'type', 'code', 'name_kh', 'name_en', 'province_id', 'district_id'
    ];

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }

    public function villages()
    {
        return $this->hasMany(Village::class, 'commune_id', 'id');
    }
}
