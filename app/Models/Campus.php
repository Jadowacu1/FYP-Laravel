<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campus extends Model {
    protected $primaryKey = 'CampusId';
    protected $fillable = [
        'CampusName',
        'CampusLocation'
    ];

}