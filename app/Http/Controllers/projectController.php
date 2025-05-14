<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supervisor;
use App\Models\User;
use App\Models\Campus;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\Project;
use App\Models\students;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
class projectController extends Controller {
    public function showStudentDashboard()
    {
        $approvedProjects = Project::where('status', 'Approved')->paginate(5); // You can change 5 to whatever per-page value you want
        return view('student.dashboard', compact('approvedProjects'));
    }
    

    

    public function showSubmitProject() {
        $departments = Department::all();
        return view( 'student.submitProject', compact( 'departments' ) );
    }

    public function addProject( Request $request ) {
        $validated = $request->validate( [
            'ProjectName' => 'required|string|max:255',
            'ProjectProblems' => 'required|string',
            'ProjectSolutions' => 'required|string',
            'ProjectAbstract' => 'required|string',
            'ProjectDissertation' => 'required|file|mimes:pdf,doc,docx|max:20480',
            'ProjectSourceCodes' => 'required|file|mimes:zip,rar|max:51200',
            'DepartmentCode' => 'required|exists:departments,DepartmentCode',
        ] );

        $studentRegNumber = session( 'reg_number' );
        if ( !$studentRegNumber ) {
            return redirect()->back()->with( 'error', 'Student not logged in.' );
        }

        // Check for similar projects
        $existingProjects = Project::all();
        foreach ( $existingProjects as $project ) {
            $problemPercent = 0;
            $solutionPercent = 0;
            $abstractPercent = 0;

            similar_text(
                strtolower( trim( $project->ProjectProblems ) ),
                strtolower( trim( $validated[ 'ProjectProblems' ] ) ),
                $problemPercent
            );
            similar_text(
                strtolower( trim( $project->ProjectSolutions ) ),
                strtolower( trim( $validated[ 'ProjectSolutions' ] ) ),
                $solutionPercent
            );
            similar_text(
                strtolower( trim( $project->ProjectAbstract ) ),
                strtolower( trim( $validated[ 'ProjectAbstract' ] ) ),
                $abstractPercent
            );

            Log::info( "Similarity: Problem=$problemPercent, Solution=$solutionPercent, Abstract=$abstractPercent" );

            if ( $problemPercent >= 75 || $solutionPercent >= 75 || $abstractPercent >= 75 ) {
                return redirect()->back()->with( 'error', 'A similar project already exists. Please revise your content.' );
            }
        }

     

        $dissertationPath = $request->file( 'ProjectDissertation' )->store( 'dissertations', 'public' );
        $sourceCodePath = $request->file( 'ProjectSourceCodes' )->store( 'source_codes', 'public' );

        // Create project
        $projectCode = 'PRJ-' . strtoupper( Str::random( 8 ) );

        Project::create( [
            'ProjectCode' => $projectCode,
            'ProjectName' => $validated[ 'ProjectName' ],
            'ProjectProblems' => $validated[ 'ProjectProblems' ],
            'ProjectSolutions' => $validated[ 'ProjectSolutions' ],
            'ProjectAbstract' => $validated[ 'ProjectAbstract' ],
            'ProjectDissertation' => $dissertationPath,
            'ProjectSourceCodes' => $sourceCodePath,
            'StudentRegNumber' => $studentRegNumber,
            'SupervisorId' => null,
            'DepartmentCode' => $validated[ 'DepartmentCode' ],
        ] );

        return redirect()->back()->with( 'success', 'Project submitted successfully!' );
    }

    public function viewCompletedProject() {
        $projects = Project::paginate( 5 );
        return view('student.completedProject', compact('projects'));

    }

    public function searchCompletedProjects(Request $request)
{
    $query = Project::query();

    // Filter only completed projects
    $query->where('status', 'Approved');

    // Keyword filter: ProjectName, ProjectProblems, or ProjectSolutions
    if ($request->filled('keyword')) {
        $query->where(function ($q) use ($request) {
            $q->where('ProjectName', 'like', '%' . $request->keyword . '%')
              ->orWhere('ProjectProblems', 'like', '%' . $request->keyword . '%')
              ->orWhere('ProjectSolutions', 'like', '%' . $request->keyword . '%');
        });
    }

    // Filter by year of completion
    if ($request->filled('year')) {
        $query->whereYear('created_at', $request->year);
    }

    // Filter by department
    if ($request->filled('department_code')) {
        $query->where('DepartmentCode', $request->department_code);
    }

    // Eager load student and department
    $projects = $query->with(['student', 'department'])->paginate(10);

    return view('student.completedProject', compact('projects'));
}

public function viewProjects()
{
    // Retrieve the student's registration number from the session
    $studentRegNumber = session( 'reg_number' ); 

    // Fetch all projects assigned to the student using their registration number
    $projects = Project::where('StudentRegNumber', $studentRegNumber)->get();

    // Pass the projects to the view
    return view('student.viewProject', compact('projects'));
}


public function update(Request $request, $ProjectCode)
{
    // Find the project by its ID
    $project = Project::findOrFail($ProjectCode);

    // Validate the incoming request
    $request->validate([
        'ProjectName' => 'required|string|max:255',
        'ProjectProblems' => 'required|string',
        'ProjectSolutions' => 'required|string',
        'ProjectAbstract' => 'required|string',
        'ProjectDissertation' => 'nullable|file|mimes:pdf|max:10000', // File validation for dissertation (PDF)
        'ProjectSourceCodes' => 'nullable|file|mimes:zip,rar,tar,tar.gz|max:50000', // File validation for source code
    ]);

    try {
        // Handle the dissertation file upload
        if ($request->hasFile('ProjectDissertation')) {
            // Delete the old dissertation file if it exists
            if ($project->ProjectDissertation) {
                Storage::disk('public')->delete($project->ProjectDissertation);
            }

            // Upload the new dissertation file
            $dissertationPath = $request->file('ProjectDissertation')->store('dissertations', 'public');
            $project->ProjectDissertation = $dissertationPath;
        }

        // Handle the source code file upload
        if ($request->hasFile('ProjectSourceCodes')) {
            // Delete the old source code file if it exists
            if ($project->ProjectSourceCodes) {
                Storage::disk('public')->delete($project->ProjectSourceCodes);
            }

            // Upload the new source code file
            $sourceCodePath = $request->file('ProjectSourceCodes')->store('source_codes', 'public');
            $project->ProjectSourceCodes = $sourceCodePath;
        }

        // Update the rest of the project details
        $project->update([
            'ProjectName' => $request->input('ProjectName'),
            'ProjectProblems' => $request->input('ProjectProblems'),
            'ProjectSolutions' => $request->input('ProjectSolutions'),
            'ProjectAbstract' => $request->input('ProjectAbstract'),
        ]);

        // Flash success message
        session()->flash('success', 'Project updated successfully!');

    } catch (\Exception $e) {
        // Flash error message
        session()->flash('error', 'There was an error updating the project: ' . $e->getMessage());
    }

    // Redirect back with a success message
    return redirect()->route('student.viewProject');
}

public function chatWithSupervisor()
{
    $studentRegNumber = Session::get('reg_number');
    // $studentRegNumber = session( 'reg_number' );

    $project = Project::where('StudentRegNumber', $studentRegNumber)->first();

    if ($project && $project->SupervisorId) {
        $supervisor = Supervisor::where('SupervisorId', $project->SupervisorId)->first();
    } else {
        $supervisor = null;
    }

    return view('student.chat', compact('supervisor'));
}


}