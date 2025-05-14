<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model {
    use HasFactory;

    protected $primaryKey = 'ProjectCode';
    public $incrementing = false;
    // Because ProjectCode is a string and not auto-increment
    protected $keyType = 'string';

    protected $fillable = [
        'ProjectCode',
        'ProjectName',
        'ProjectProblems',
        'ProjectSolutions',
        'ProjectAbstract',
        'ProjectDissertation',
        'ProjectSourceCodes',
        'StudentRegNumber',
        'SupervisorId',
        'DepartmentCode',
    ];

    public function student() {
        return $this->belongsTo( students::class, 'StudentRegNumber', 'StudentRegNumber' );
    }

    public function supervisor() {
        return $this->belongsTo( Supervisor::class, 'SupervisorId', 'SupervisorId' );
    }

    public function department() {
        return $this->belongsTo( Department::class, 'DepartmentCode', 'DepartmentCode' );
    }
}