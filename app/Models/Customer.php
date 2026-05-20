<?php

namespace App\Models;

use App\Models\CustPhoto;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer_infos';
    protected $primaryKey = 'cust_id';
    public $timestamps = false;

    protected $fillable = [
        'id_no',
        'name_en',
        'name_kh',
        'gender',
        'marital_status',
        'dob',
        'pob',
        'phone1',
        'phone2',
        'phone3',
        'address',
        'country_id',
        'province_id',
        'district_id',
        'commune_id',
        'village_id',
        'occupation',
        'email',
        'spouse_id_no',
        'spouse_name_en',
        'spouse_name_kh',
        'spouse_dob',
        'guarantor_id_no',
        'guarantor_name_en',
        'guarantor_name_kh',
        'family_book',
        'guarantor_dob',
        'staff_id',
        'remark',
        'created_by',
        'created_date',
        'modify_by',
        'modify_date',
        'nationality_id'
    ];

    protected $casts = [
        'dob' => 'date',
        'spouse_dob' => 'date',
        'guarantor_dob' => 'date',
        'created_date' => 'date',
        'modify_date' => 'date'
    ];

    public function customerPhoto()
    {
        return $this->hasMany(CustPhoto::class, 'cust_id', 'cust_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'country_id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }

    public function commune()
    {
        return $this->belongsTo(Commune::class, 'commune_id', 'id');
    }

    public function village()
    {
        return $this->belongsTo(Village::class, 'village_id', 'id');
    }

    public function nationality()
    {
        return $this->belongsTo(Nationality::class, 'nationality_id', 'nationality_id');
    }

    public function accounts()
    {
        return $this->hasMany(AccountInfo::class, 'cust_id', 'cust_id');
    }
}
