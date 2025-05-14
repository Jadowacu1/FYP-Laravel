<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hod extends Model {
    /** @use HasFactory<\Database\Factories\DepartmentsFactory> */
    protected $primaryKey = 'HodId';
    protected $table = 'hod';
    protected $fillable = [
        'Email',
        'FirstName',
        'LastName',
        'PhoneNumber',
        'DepartmentCode'
    ];

    public function department() {
        return $this->belongsTo( Department::class, 'DepartmentCode', 'DepartmentCode' );
    }

    use HasFactory;
}