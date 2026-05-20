<?php
// app/Models/Province.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = 'provinces';
    public $timestamps = true;

    protected $fillable = [
        'type', 'code', 'name_kh', 'name_en', 'country_id'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'country_id');
    }

    public function districts()
    {
        return $this->hasMany(District::class, 'province_id', 'id');
    }
}
