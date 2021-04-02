<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ulb extends Model
{
    use HasFactory;

    protected $table = 'ulb';

    /**
     * District relationship
     *
     * @return void
     */
    public function district_relation()
    {
        return $this->belongsTo(District::class, 'district', 'id');
    }
}
