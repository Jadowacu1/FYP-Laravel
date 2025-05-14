<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Supervisor;

class SupervisorController extends Controller
{

public function dashboard()
{
    $email = session('email');

    $supervisor = Supervisor::where('Email', $email)->first();

    if (!$supervisor) {
        return back()->with('error', 'Supervisor not found.');
    }

    // Use SupervisorId (not id or user_id)
    $supervisorId = $supervisor->SupervisorId;

    $assignedProjects = Project::where('supervisorId', $supervisorId)->paginate(10);

    return view('supervisor.dashboard', compact('assignedProjects'));
}
public function updateStatus(Request $request, $projectCode)
{
    $project = Project::where('ProjectCode', $projectCode)->firstOrFail();

    $project->status = $request->input('status');
    $project->save();

    return redirect()->back()->with('success', 'Project status updated successfully.');
}
public function showAssignedStudents()
{
    $email = session('email');
    $supervisor = Supervisor::where('Email', $email)->first();

    if (!$supervisor) {
        return redirect()->route('supervisor.dashboard')->with('error', 'Supervisor not found');
    }

    // Get students assigned to this supervisor
    $assignedStudents = Project::where('SupervisorId', $supervisor->SupervisorId)
                               ->join('students', 'students.StudentRegNumber', '=', 'projects.StudentRegNumber')
                               ->select('students.FirstName', 'students.LastName', 'students.StudentRegNumber', 'students.Email', 'students.PhoneNumber')
                               ->get();
                               

    return view('supervisor.chat', compact('assignedStudents'));
}

}
