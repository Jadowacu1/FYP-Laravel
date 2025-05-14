<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Dashboard - My Projects</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f8f9fa;
    }

    .sidebar {
      height: 100vh;
      background-color: #003049;
      color: #fff;
      width: 250px;
      padding-top: 20px;
      position: fixed;
    }

    .sidebar .nav-link {
      color: #adb5bd;
      display: flex;
      align-items: center;
      padding-left: 20px;
    }

    .sidebar .nav-link i {
      font-size: 1.5rem;
    }

    .sidebar .nav-link span {
      margin-left: 10px;
    }

    .sidebar .nav-link.active, .sidebar .nav-link:hover {
      background-color: #00243a;
      color: #fff;
    }

    .main-content {
      margin-left: 250px;
      padding: 20px;
    }

    .dashboard-title {
      font-weight: 600;
      font-size: 1.8rem;
    }

    .card:hover {
      transform: translateY(-5px);
      transition: 0.3s ease;
    }

    .btn-outline-dark:hover {
      background-color: #003049;
      color: #fff;
    }

    .modal-header {
      background-color: #003049;
      color: white;
    }

    .project-card {
      cursor: pointer;
    }
  </style>
</head>
<body>
  <!-- Toast Container -->
<div id="toastContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
    <!-- Success Toast -->
    <div id="successToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header" style="background-color: #28a745; color: white;">
        <i class="bi bi-check-circle text-white me-2"></i>
        <strong class="me-auto">Success</strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body" style="background-color: #28a745; color: white;">
        {{ session('success') }}
      </div>
    </div>
  
    <!-- Error Toast -->
    <div id="errorToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <i class="bi bi-x-circle text-danger me-2"></i>
        <strong class="me-auto">Error</strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">
        {{ session('error') }}
      </div>
    </div>
</div>


  <div class="container-fluid">
    <div class="row">

      <!-- Sidebar -->
      <nav class="col-md-2 sidebar d-flex flex-column">
        <h4 class="text-white text-center mb-4">Student Panel</h4>

        <ul class="nav flex-column mb-auto">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('student.dashboard') }}"><i class="bi bi-house-door"></i><span> Dashboard</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('searchCompletedProjects') }}"><i class="bi bi-search"></i><span> Completed Projects</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('student.submitProject') }}"><i class="bi bi-upload"></i><span> Submit Proposal</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="{{ route('student.viewProject') }}"><i class="bi bi-collection"></i><span> My Projects</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('student.chat') }}"><i class="bi bi-chat-dots"></i><span> Chat</span></a>
          </li>
          <li class="nav-item"><a class="nav-link" href="{{ route('preferences') }}"><i class="bi bi-gear me-2"></i><span>Preferences</span>
          </a>
      </li>
        </ul>

        <div class="mt-auto">
          <hr class="border-white">
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn text-start text-danger w-100 d-flex align-items-center gap-2">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </button>
          </form>
        </div>
      </nav>

      <!-- Main Content -->
      <main class="col-md-10 ms-sm-auto col-lg-10 main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h2 class="dashboard-title">My Projects</h2>
        </div>

        @if($projects->isEmpty())
          <div class="alert alert-info">You have not submitted any projects yet.</div>
        @else
          <div class="row g-4">
            @foreach($projects as $project)
              <div class="col-md-6 col-lg-4">
                <div class="card shadow border-0 h-100">
                  <div class="card-body">
                    <h5 class="card-title text-primary">{{ $project->ProjectName }}</h5>
                    <p><strong>Status:</strong>
                      <span class="badge 
                        @if($project->status == 'Approved') bg-success 
                        @elseif($project->status == 'Pending') bg-warning 
                        @elseif($project->status == 'Rejected') bg-danger 
                        @else bg-secondary 
                        @endif">
                        {{ $project->status }}
                      </span>
                    </p>
                    <p><strong>Submitted:</strong> {{ \Carbon\Carbon::parse($project->created_at)->format('d M, Y') }}</p>
                  </div>
                  <div class="card-footer bg-white text-end">
                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#projectModal{{ $project->ProjectCode }}">
                      <i class="bi bi-pencil-square"></i> View / Update
                    </button>
                  </div>
                </div>
              </div>

              <!-- Modal -->
              <div class="modal fade" id="projectModal{{ $project->ProjectCode }}" tabindex="-1" aria-labelledby="modalLabel{{ $project->ProjectCode }}" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="modalLabel{{ $project->ProjectCode }}">Project Details - {{ $project->ProjectCode }}</h5>
                      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                      <p><strong>Project Code:</strong> {{ $project->ProjectCode }}</p>
                      <p><strong>Student Name:</strong> {{ $project->student->FirstName ?? 'Unknown' }} {{ $project->student->LastName ?? '' }}</p>
                      <p><strong>Department:</strong> {{ $project->department->DepartmentName ?? 'Unknown' }}</p>
                      <p><strong>Supervisor:</strong> 
                          @if($project->supervisor)
                            {{ $project->supervisor->FirstName }} {{ $project->supervisor->LastName }} ({{ $project->supervisor->Email }})
                          @else
                            <span class="text-muted">Not assigned</span>
                          @endif
                        </p>
                        
                      <p><strong>Status:</strong> 
                        <span class="badge 
                          @if($project->status == 'Approved') bg-success 
                          @elseif($project->status == 'Pending') bg-warning 
                          @elseif($project->status == 'Rejected') bg-danger 
                          @else bg-secondary 
                          @endif">
                          {{ $project->status }}
                        </span>
                      </p>
                      <p><strong>Created At:</strong> {{ $project->created_at }}</p>
                      <p><strong>Updated At:</strong> {{ $project->updated_at }}</p>

                      <hr>

                      <form method="POST" action="{{ route('student.updateProject', $project->ProjectCode) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                          <label class="form-label">Project Title</label>
                          <input type="text" class="form-control" name="ProjectName" value="{{ $project->ProjectName }}" required>
                        </div>

                        <div class="mb-3">
                          <label class="form-label">Project Problem</label>
                          <textarea class="form-control" name="ProjectProblems" rows="2" required>{{ $project->ProjectProblems }}</textarea>
                        </div>

                        <div class="mb-3">
                          <label class="form-label">Project Solution</label>
                          <textarea class="form-control" name="ProjectSolutions" rows="2" required>{{ $project->ProjectSolutions }}</textarea>
                        </div>

                        <div class="mb-3">
                          <label class="form-label">Abstract</label>
                          <textarea class="form-control" name="ProjectAbstract" rows="3" required>{{ $project->ProjectAbstract }}</textarea>
                        </div>

                        <div class="mb-3">
                          <label for="ProjectDissertation" class="form-label">Upload Dissertation (PDF)</label>
                          <input type="file" class="form-control" name="ProjectDissertation" accept=".pdf">
                          @if($project->ProjectDissertation)
                          <a href="{{ asset('storage/' . $project->ProjectDissertation) }}" target="_blank" class="btn btn-outline-success btn-sm">
                           <i class="bi bi-file-earmark-text"></i> View Current Dissertation
                          </a>
                           @endif

                        </div>
                        
                        <div class="mb-3">
                          <label for="ProjectSourceCodes" class="form-label">Upload Source Code (ZIP)</label>
                          <input type="file" class="form-control" name="ProjectSourceCodes" accept=".zip,.rar,.7z">
                          @if($project->ProjectSourceCodes)
                          <a href="{{ asset('storage/' . $project->ProjectSourceCodes) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                          <i class="bi bi-download"></i> Download Current Source Code
                          </a>
                         @endif

                        </div>
                        

                        <div class="text-end">
                          <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Changes</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </main>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Display toast messages if they exist
    document.addEventListener('DOMContentLoaded', function () {
      @if (session('success'))
        var successToast = new bootstrap.Toast(document.getElementById('successToast'));
        successToast.show();
      @endif

      @if (session('error'))
        var errorToast = new bootstrap.Toast(document.getElementById('errorToast'));
        errorToast.show();
      @endif
    });
  </script>
</body>
</html>
