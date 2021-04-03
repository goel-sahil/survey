<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $table = 'survey';
    public $timestamps = false;

    public $dates = ['Date_Submission'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ulb',
        'survey_user',
        'Name',
        'S/0_D/0_W/0',
        'Occupation',
        'Address',
        'Mobile_Number',
        'Anual_Income',
        'Intrested',
        'Extend_Required',
        'Prefered_Location',
        'Distance',
        'Date_Submission',
        'Status'
    ];

    /**
     * Get the ULB info
     *
     * @return void
     */
    public function ulb_name()
    {
        return $this->belongsTo(Ulb::class, 'ulb', 'id');
    }

    /**
     * District relationship
     *
     * @return void
     */
    public function district_relation()
    {
        return $this->belongsTo(District::class, 'district', 'id');
    }

    /**
     * User relationship
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'survey_user', 'Id');
    }

    /**
     * Extent relationship
     *
     * @return void
     */
    public function extent_relation()
    {
        return $this->belongsTo(Extent::class, 'Extend_Required', 'id');
    }

    /**
     * Distance relationship
     *
     * @return void
     */
    public function distance_relation()
    {
        return $this->belongsTo(Distance::class, 'Distance', 'id');
    }
}
