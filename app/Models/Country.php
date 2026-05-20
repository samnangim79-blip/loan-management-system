<?php
// app/Models/Country.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries';
    protected $primaryKey = 'country_id';
    public $timestamps = false;

    protected $fillable = [
        'country', 'country_kh'
    ];

    public function provinces()
    {
        return $this->hasMany(Province::class, 'country_id', 'country_id');
    }
}
