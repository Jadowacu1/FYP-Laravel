<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model {
    protected $primaryKey = 'FacultyCode';
    protected $fillable = [ 'FacultyName', 'CampusId' ];

    public function campus() {
        return $this->belongsTo( Campus::class, 'CampusId' );
    }
}