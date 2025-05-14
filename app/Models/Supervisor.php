<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model {
    use HasFactory;

    protected $primaryKey = 'SupervisorId';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'Email',
        'FirstName',
        'LastName',
        'PhoneNumber',
        'DepartmentCode',
    ];

    public function department() {
        return $this->belongsTo( Department::class, 'DepartmentCode', 'DepartmentCode' );
    }
}