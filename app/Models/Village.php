<?php
// app/Models/Village.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    protected $table = 'villages';
    // primaryKey is 'id' by default, no need to specify
    public $timestamps = true; // The migration has timestamps()

    protected $fillable = [
        'type',
        'code',
        'name_kh',
        'name_en',
        'province_id',
        'district_id',
        'commune_id'
    ];

    public function commune()
    {
        return $this->belongsTo(Commune::class, 'commune_id', 'id');
    }

    public function customers()
    {
        return $this->hasMany(Customer::class, 'village_id', 'id');
    }

    // Helper method to get full address
    public function getFullAddress()
    {
        $this->load(['commune.district.province.country']);

        return sprintf(
            '%s, %s, %s, %s, %s',
            $this->name_en ?? $this->name_kh ?? '',
            $this->commune->name_en ?? $this->commune->name_kh ?? '',
            $this->commune->district->name_en ?? $this->commune->district->name_kh ?? '',
            $this->commune->district->province->name_en ?? $this->commune->district->province->name_kh ?? '',
            $this->commune->district->province->country->country ?? ''
        );
    }
}
