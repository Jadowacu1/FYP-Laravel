<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supervisor;
use App\Models\User;
use App\Models\Campus;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\Hod;
use App\Models\Project;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class hodController extends Controller {
    public function showHodDashbord() {
        // Get HOD email from session
        $hodEmail = session( 'email' );

        // Retrieve HOD's department code
        $hod = DB::table('hod')
            ->where('Email', $hodEmail)
            ->first();
    
            $projects = Project::with(['student', 'supervisor'])
            ->where('DepartmentCode', $hod->DepartmentCode)
            ->paginate(10);
        
    
        // Count the required entities
        $facultyCount = Faculty::count();
        $campusCount = Campus::count();
        $supervisorCount = Supervisor::count();
        $departmentCount = Department::count();
    
        return view('hod.dashboard', compact(
            'facultyCount',
            'campusCount',
            'supervisorCount',
            'departmentCount',
            'projects' // Add projects to the view
        ));
    }
    

    public function showAddSupervisor() {
        // Get HOD email from session
        $hodEmail = session( 'email' );

        // Retrieve HOD's department code
        $hod = DB::table( 'hod' )
        ->where( 'Email', $hodEmail )
        ->first();

        // Fetch supervisors with the same department code as HOD
        $supervisors = Supervisor::where( 'DepartmentCode', $hod->DepartmentCode )->paginate( 2 );

        return view( 'hod.addSupervisor', compact( 'supervisors' ) );
    }

    public function AddSupervisor( Request $request ) {
        $request->validate( [
            'Email' => 'required|email|unique:supervisors,Email',
            'FirstName' => 'required|string|max:255',
            'LastName' => 'required|string|max:255',
            'PhoneNumber' => 'required|string|max:15',
        ] );

        // Get HOD email from session
        $hodEmail = session( 'email' );
        // Make sure this session key is set during HOD login

        // Retrieve HOD's department code
        $hod = DB::table( 'hod' )
        ->where( 'Email', $hodEmail )
        ->first();

        if ( !$hod ) {
            return redirect()->back()->with( 'error', 'Unable to find HOD information.' );
        }

        $departmentCode = $hod->DepartmentCode;

        // Save supervisor
        Supervisor::create( [
            'Email' => $request->Email,
            'FirstName' => $request->FirstName,
            'LastName' => $request->LastName,
            'PhoneNumber' => $request->PhoneNumber,
            'DepartmentCode' => $departmentCode,
        ] );

        // Create user account for login
        $fullName = $request->FirstName . ' ' . $request->LastName;
        $user = new User();
        $user->name = $fullName;
        $user->email = $request->Email;
        $user->password = Hash::make( 123 );
        // Set a secure password in production
        $user->role = 'supervisor';
        $user->save();

        return redirect()->back()->with( 'success', 'Supervisor added successfully!' );
    }

    public function showAddSupervisorForm() {
        $hodEmail = session( 'email' );
        // Assuming this is set during login
        $hod = DB::table( 'hod' )->where( 'Email', $hodEmail )->first();

        if ( !$hod ) {
            return redirect()->back()->with( 'error', 'HOD not found.' );
        }

        $departmentCode = $hod->DepartmentCode;

        $supervisors = Supervisor::where( 'DepartmentCode', $departmentCode )->paginate( 2 );

        return view( 'hod.addSupervisor', compact( 'supervisors' ) );
    }

    public function editSupervisor( $supervisorId ) {
        $supervisor = Supervisor::findOrFail( $supervisorId );
        $supervisors = Supervisor::paginate( 3 );

        return view( 'hod.addSupervisor', compact( 'supervisor', 'supervisors' ) );
    }

    public function updateSupervisor( Request $request, $supervisorId ) {
        $supervisor = Supervisor::findOrFail( $supervisorId );

        $validated = $request->validate( [
            'Email' => 'required|email|unique:supervisors,Email,' . $supervisorId . ',SupervisorId',
            'FirstName' => 'required',
            'LastName' => 'required',
            'PhoneNumber' => 'required',
        ] );

        $oldEmail = $supervisor->Email;

        // Update Supervisor fields
        $supervisor->Email = $validated[ 'Email' ];
        $supervisor->FirstName = $validated[ 'FirstName' ];
        $supervisor->LastName = $validated[ 'LastName' ];
        $supervisor->PhoneNumber = $validated[ 'PhoneNumber' ];
        $supervisor->save();

        // Update User table where email matches the old supervisor email
        $user = User::where( 'email', $oldEmail )->first();
        if ( $user ) {
            $user->name = $validated[ 'FirstName' ] . ' ' . $validated[ 'LastName' ];
            $user->email = $validated[ 'Email' ];
            $user->save();
        }

        return redirect()->route( 'hod.addSupervisor' )->with( 'success', 'Supervisor updated successfully!' );
    }

    public function deleteSupervisor( $supervisorId ) {
        $supervisor = Supervisor::findOrFail( $supervisorId );

        // Delete related user
        User::where( 'email', $supervisor->Email )->delete();

        // Delete supervisor
        $supervisor->delete();

        return redirect()->route( 'hod.addSupervisor' )->with( 'success', 'Supervisor deleted successfully!' );
    }

    public function showAssignmentPage() {
        // Get HOD email from session
        $hodEmail = session( 'email' );

        // Retrieve HOD's department code
        $hod = DB::table( 'hod' )
        ->where( 'Email', $hodEmail )
        ->first();

        if ( !$hod ) {
            return redirect()->back()->withErrors( 'HOD not found or not logged in.' );
        }

        // Filter projects by HOD's department
        $projects = Project::with( [ 'student', 'supervisor' ] )
        ->where( 'DepartmentCode', $hod->DepartmentCode )
        ->paginate( 10 );

        // Get supervisors from the same department
        $supervisors = DB::table( 'supervisors' )
        ->where( 'DepartmentCode', $hod->DepartmentCode )
        ->get();

        return view( 'hod.assign_supervisor', compact( 'projects', 'supervisors' ) );
    }

    public function assignSupervisor( Request $request, $ProjectCode ) {
        $request->validate( [
            'SupervisorId' => 'required',
        ] );

        $supervisor = Supervisor::find( $request->SupervisorId );

        if ( !$supervisor ) {
            return back()->withErrors( 'Supervisor does not exist.' );
        }

        $project = Project::where( 'ProjectCode', $ProjectCode )->firstOrFail();
        $project->SupervisorId = $request->SupervisorId;
        $project->save();

        return back()->with( 'success', 'Supervisor assigned successfully.' );
    }

    public function removeSupervisor( $ProjectCode ) {
        // Find the project by its code
        $project = Project::where( 'ProjectCode', $ProjectCode )->firstOrFail();

        // Remove the assigned supervisor
        $project->SupervisorId = null;
        $project->save();

        return back()->with( 'success', 'Supervisor removed successfully.' );
    }
    public function approveProject($projectCode) {
        $project = Project::where('ProjectCode', $projectCode)->firstOrFail();
        $project->Status = 'approved';
        $project->save();
    
        return redirect()->back()->with('success', 'Project approved successfully.' );
    }

}