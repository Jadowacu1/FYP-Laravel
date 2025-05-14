<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class students extends Model {
    /** @use HasFactory<\Database\Factories\StudentsFactory> */
    use HasFactory;
    protected $primaryKey = 'StudentRegNumber';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'StudentRegNumber',
        'FirstName',
        'LastName',
        'Gender',
        'Email',
        'PhoneNumber',
        'DepartmentCode',
    ];

}