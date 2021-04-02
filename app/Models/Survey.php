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
}
