<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supervisor;
use App\Models\User;
use App\Models\Campus;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\Hod;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller {

    public function showDeparmentDashbord() {
        $facultyCount = Faculty::count();
        $campusCount = Campus::count();
        $hodCount = Hod::count();
        $departmentCount = Department::count();

        return view( 'department.dashboard', compact(
            'facultyCount',
            'campusCount',
            'hodCount',
            'departmentCount'
        ) );
    }

    public function showCampusForm () {
        return view( 'department.campus' );
    }

    public function addCampus( Request $request ) {

        $validated = $request->validate( [
            'CampusName' => 'required|string|max:255',
            'CampusLocation' => 'required|string|max:255',
        ] );

        Campus::create( $validated );

        return redirect()->back()->with( 'success', 'Campus inserted successfully!' );

    }

    public function showAllCampuses() {
        $campuses = Campus::paginate( 5 );
        return view( 'department.campus', compact( 'campuses' ) );
    }

    public function deleteCampus( $CampusId ) {
        $campus = Campus::findOrFail( $CampusId );

        $campus->delete();
        return redirect()->route( 'department.Campus' )->with( 'success', 'Campus deleted successfully!' );
    }

    public function showFacultyPage() {
        $faculties = Faculty::with( 'campus' )->get();
        $campuses = Campus::all();

        return view( 'department.faculty', compact( 'faculties', 'campuses' ) );
    }

    public function showFacultyList() {
        $faculties = Faculty::all();
        return view( 'department.faculty', compact( 'faculties' ) );
    }

    public function AddFaculty( Request $request ) {
        $request->validate( [
            'FacultyName' => 'required',
            'CampusId' => 'required|exists:campuses,CampusId',
        ] );

        Faculty::create( [
            'FacultyName' => $request->FacultyName,
            'CampusId' => $request->CampusId,
        ] );

        return redirect()->route( 'department.faculty' )->with( 'success', 'Faculty created.' );
    }

    public function editFaculty( $FacultyCode ) {
        $faculty = Faculty::where( 'FacultyCode', $FacultyCode )->first();

        $campuses = Campus::all();
        $faculties = Faculty::all();
        return view( 'department.faculty', compact( 'faculty', 'campuses', 'faculties' ) );
    }

    public function updateFaculty( Request $request, $FacultyCode ) {
        $faculty = Faculty::findOrFail( $FacultyCode );

        $validatedData = $request->validate( [
            'FacultyName' => 'required|string|max:255',
            'CampusId' => 'required|exists:campuses,CampusId',
        ] );

        $faculty->FacultyName = $validatedData[ 'FacultyName' ];
        $faculty->CampusId = $validatedData[ 'CampusId' ];
        $faculty->save();

        return redirect()->route( 'department.faculty' )->with( 'success', 'Faculty updated.' );
    }

    public function deleteFaculty( $FacultyCode ) {
        Faculty::where( 'FacultyCode', $FacultyCode )->delete();

        return redirect()->route( 'department.faculty' )->with( 'success', 'Faculty deleted.' );
    }

    public function showDepartmentPage() {
        $departments = Department::with( 'faculty' )->paginate( 2 );
        $faculties = Faculty::all();

        return view( 'department.department', compact( 'faculties', 'departments' ) );
    }

    public function AddDepartment( Request $request ) {
        $request->validate( [
            'DepartmentName' => 'required',
            'FacultyCode' => 'required|exists:faculties,FacultyCode',
        ] );

        Department::create( [
            'DepartmentName' => $request->DepartmentName,
            'FacultyCode' => $request->FacultyCode,
        ] );

        return redirect()->route( 'department.department' )->with( 'success', 'Department created.' );
    }

    public function editDepartment( $DepartmentCode ) {
        // Fetch faculty by FacultyCode
        $department = Department::where( 'DepartmentCode', $DepartmentCode )->first();

        $faculties = Faculty::all();
        $departments = Department::with( 'faculty' )->paginate( 2 );

        // Return the view with faculty and campuses
        return view( 'department.department', compact( 'departments',  'department', 'faculties' ) );
    }

    public function updateDepartment( Request $request, $DepartmentCode ) {
        $department = Department::findOrFail( $DepartmentCode );

        $validatedData = $request->validate( [
            'DepartmentName' => 'required|string|max:255',
            'FacultyCode' => 'required|exists:faculties,FacultyCode',
        ] );

        $department->DepartmentName = $validatedData[ 'DepartmentName' ];
        $department->FacultyCode = $validatedData[ 'FacultyCode' ];
        $department->save();

        return redirect()->route( 'department.department' )->with( 'success', 'Department updated.' );
    }

    public function deleteDepartment( $DepartmentCode ) {
        Department::where( 'DepartmentCode', $DepartmentCode )->delete();

        return redirect()->route( 'department.department' )->with( 'success', 'Department deleted.' );
    }

    public function showAddHod( Request $request ) {
        $hods = Hod::with( 'department' )->paginate( 2 );

        // eager load the department
        $departments = Department::all();
        return view( 'department.addHod', compact( 'hods', 'departments' ) );
    }

    public function AddHod( Request $request ) {
        $request->validate( [
            'Email' => 'required|email|unique:supervisors,Email',
            'FirstName' => 'required|string|max:255',
            'LastName' => 'required|string|max:255',
            'PhoneNumber' => 'required|string|max:15',
            'DepartmentCode' => 'required|exists:departments,DepartmentCode'
        ] );

        Hod::create( [
            'Email' => $request->Email,
            'FirstName' => $request->FirstName,
            'LastName' => $request->LastName,
            'PhoneNumber' => $request->PhoneNumber,
            'DepartmentCode'=>$request->DepartmentCode
        ] );

        $fullName = $request->FirstName . ' ' . $request->LastName;
        $user = new User();
        $user->name = $fullName;
        $user->email = $request->Email;
        $user->password = Hash::make( 123 );
        $user->role = 'hod';
        $user->save();

        // Redirect or return success response
        return redirect()->back()->with( 'success', 'HOD added successfully!' );
    }

    public function editHod( $HodId ) {
        $hod = Hod::findOrFail( $HodId );
        $hods = Hod::paginate( 3 );
        $departments = Department::all();
        return view( 'department.addHod', compact( 'hod', 'hods', 'departments' ) );
    }

    public function updateHod( Request $request, $HodId ) {
        $hod = Hod::findOrFail( $HodId );

        $validated = $request->validate( [
            'Email' => 'required|email|unique:supervisors,Email,' . $HodId . ',SupervisorId',
            'FirstName' => 'required',
            'LastName' => 'required',
            'PhoneNumber' => 'required',
            'DepartmentCode' => 'required|exists:departments,DepartmentCode', // ✅ Validate department
        ] );

        $oldEmail = $hod->Email;

        // Update HOD fields
        $hod->Email = $validated[ 'Email' ];
        $hod->FirstName = $validated[ 'FirstName' ];
        $hod->LastName = $validated[ 'LastName' ];
        $hod->PhoneNumber = $validated[ 'PhoneNumber' ];
        $hod->DepartmentCode = $validated[ 'DepartmentCode' ];
        $hod->save();

        // Update User table if user exists
        $user = User::where( 'email', $oldEmail )->first();
        if ( $user ) {
            $user->name = $validated[ 'FirstName' ] . ' ' . $validated[ 'LastName' ];
            $user->email = $validated[ 'Email' ];
            $user->save();
        }

        return redirect()->route( 'department.addHod' )->with( 'success', 'HOD updated successfully!' );
    }

    public function deleteHod( $HodId ) {
        $hod = Hod::findOrFail( $HodId );

        // Delete related user
        User::where( 'email', $hod->Email )->delete();

        // Delete supervisor
        $hod->delete();

        return redirect()->route( 'department.addHod' )->with( 'success', 'Supervisor deleted successfully!' );
    }

}