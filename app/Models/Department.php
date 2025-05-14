<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model {
    /** @use HasFactory<\Database\Factories\DepartmentsFactory> */
    protected $primaryKey = 'DepartmentCode';
    protected $fillable = [ 'DepartmentName', 'FacultyCode' ];

    public function faculty() {
        return $this->belongsTo( Faculty::class, 'FacultyCode' );
    }
    use HasFactory;
}