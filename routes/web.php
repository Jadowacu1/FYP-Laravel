<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\authentication;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\hodController;
use App\Http\Controllers\projectController;
use App\Http\Controllers\SupervisorController;


Route::get( '/', [ authentication::class, 'showForm' ] )->name( 'LoginForm' );
Route::get( '/studentRegistration', [ authentication::class, 'showRegistrationFrom' ] ) -> name( 'SignUpForm' );
Route::post( '/studentRegistration', [ authentication::class, 'studentRegistration' ] ) -> name( 'SignUp' );
Route::post( '/', [ authentication::class, 'Login' ] ) -> name( 'login' );
Route::post('/logout', [authentication::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/preferences', [authentication::class, 'preferences'])->name('preferences');
    Route::put('/preferences/update-password', [authentication::class, 'updatePassword'])->name('preferences.updatePassword');
    Route::get('/dashboard', [authentication::class, 'redirect'])->name('dashboard.redirect');

});


Route::middleware(['auth', 'role:student'])->group(function () {
    
    Route::get('/student/dashboard', [projectController::class, 'showStudentDashboard'])->name('student.dashboard');

    Route::get('/student/submitProject', [projectController::class, 'showSubmitProject'])->name('student.submitProject');
    Route::post('/student/submitProject', [projectController::class, 'addProject'])->name('student.AddProject');

    Route::get('student/completedProject',[projectController::class,'viewCompletedProject'])->name('student.completedProject');
    Route::get('student/completedProject', [ProjectController::class, 'searchCompletedProjects'])->name('searchCompletedProjects');
    
    Route::get('/student/project', [ProjectController::class, 'viewProjects'])->name('student.viewProject');
    Route::put('/student/project/{ProjectCode}', [ProjectController::class, 'update'])->name('student.updateProject');
    Route::get('/student/chat', [ProjectController::class, 'chatWithSupervisor'])->name('student.chat');

    
});


Route::middleware(['auth', 'role:supervisor'])->group(function () {
    Route::get('/supervisor/dashboard', [SupervisorController::class, 'dashboard'])->name('supervisor.dashboard');
    Route::put('/supervisor/projects/{projectCode}/status', [SupervisorController::class, 'updateStatus'])->name('supervisor.updateStatus');
    Route::get('/supervisor/chat', [SupervisorController::class, 'showAssignedStudents'])->name('supervisor.chat');

});

Route::middleware(['auth', 'role:department'])->group(function () {
    Route::get('/department/dashboard', [AdminController::class, 'showDeparmentDashbord'] )->name( 'department.dashboard' );
    Route::get('/department/AddHod', [AdminController::class, 'showAddHod'] )->name( 'department.addHod' );
    Route::post( '/department/AddHod', [ AdminController::class, 'AddHod' ] ) -> name( 'AddHodForm' );
    Route::get('/department/AddHod/edit/{HodId}', [AdminController::class, 'editHod'])->name('editHod');
    Route::put('/department/AddHod/update/{HodId}', [AdminController::class, 'updateHod'])->name('updateHod');
    Route::delete('/department/AddHod/delete/{HodId}', [AdminController::class, 'deleteHod'])->name('deleteHod');


    Route::get('department/campus',[AdminController::class, 'showCampusForm'])-> name('department.Campus');
    Route::post('department/campus',[AdminController::class, 'addCampus']) -> name ('addCampusForm');
    Route::get('department/campus',[AdminController::class, 'showAllCampuses'])->name ('department.Campus');
    Route::delete('/department/Campus/delete/{CampusId}', [AdminController::class, 'deleteCampus'])->name('department.deleteCampus');



    Route::get('/department/faculty',[AdminController::class,'showFacultyPage'])->name('department.faculty');
    Route::post('department/faculty',[AdminController::class,'AddFaculty'])->name('addFacultyForm');
    Route::get('/department/faculty/edit/{FacultyCode}', [AdminController::class, 'editFaculty'])->name('editFaculty');
    Route::put('/department/faculty/update/{FacultyCode}', [AdminController::class, 'updateFaculty'])->name('updateFaculty');
    Route::delete('/department/faculty/delete/{FacultyCode}', [AdminController::class, 'deleteFaculty'])->name('deleteFaculty');


    Route::get('/department/department',[AdminController::class,'showDepartmentPage'])->name('department.department');
    Route::post('/department/department',[AdminController::class,'AddDepartment'])->name('addDepartmentForm');
    Route::get('/department/department/edit/{DepartmentCode}', [AdminController::class, 'editDepartment'])->name('editDepartment');
    Route::put('/department/department/update/{DepartmentCode}', [AdminController::class, 'updateDepartment'])->name('updateDepartment');
    Route::delete('/department/department/delete/{DepartmentCode}', [AdminController::class, 'deleteDepartment'])->name('deleteDepartment');

});


Route::middleware(['auth', 'role:hod'])->group(function () {
    Route::get('/hod/dashboard', [hodController::class, 'showHodDashbord'] )->name( 'hod.dashboard' );
    Route::get('/hod/AddSupervisor', [hodController::class, 'showAddSupervisor'] )->name( 'hod.addSupervisor' );

    Route::post( '/hod/AddSupervisor', [ hodController::class, 'AddSupervisor' ] ) -> name( 'AddSupervisorForm' );
    Route::get('/hod/AddSupervisor/edit/{SupervisorId}', [hodController::class, 'editSupervisor'])->name('hod.editSupervisor');
    Route::put('/hod/AddSupervisor/update/{supervisorId}', [hodController::class, 'updateSupervisor'])->name('hod.updateSupervisor');
    Route::delete('/hod/AddSupervisor/delete/{SupervisorId}', [hodController::class, 'deleteSupervisor'])->name('hod.deleteSupervisor');
    
    Route::get('/hod/assign-supervisors', [hodController::class, 'showAssignmentPage'])->name('assign.supervisor.page');
    Route::put('/hod/assign-supervisors/{ProjectCode}', [hodController::class, 'assignSupervisor'])->name('assign.supervisor');
    Route::delete('/hod/remove-supervisor/{ProjectCode}', [hodController::class, 'removeSupervisor'])->name('remove.supervisor');
    Route::put('/hod/approve-project/{ProjectCode}', [hodController::class, 'approveProject'])->name('approve.project');



});