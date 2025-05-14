<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Completed Projects</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


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
    .sidebar .nav-link.active,
    .sidebar .nav-link:hover {
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
  </style>
</head>

<body>

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
          <a class="nav-link active" href="#"><i class="bi bi-search"></i><span> Completed Projects</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('student.submitProject') }}"><i class="bi bi-upload"></i><span> Submit Proposal</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="{{ route('student.viewProject') }}"><i class="bi bi-collection"></i><span> My Projects</span></a>
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
      <h2 class="dashboard-title mb-3">Completed Projects</h2>
      <p>Browse through the list of completed student projects.</p>

      
      <form method="GET" action="{{ route('searchCompletedProjects') }}" class="d-flex mb-3">
        <input type="text" name="keyword" class="form-control me-2" placeholder="Search..." value="{{ request('keyword') }}">
        <button type="submit" class="btn btn-outline-primary">
            <i class="fas fa-search"></i>
        </button>
    </form>
    
      <!-- Projects Table -->
      <div class="table-responsive">
        <table class="table table-striped table-bordered">
          <thead class="table-dark">
            <tr>
              <th>Project Title</th>
              <th>Student</th>
              <th>Department</th>
              <th>Submission Year</th>
              <th>Download</th>
            </tr>
          </thead>
          <tbody>
              @forelse($projects as $project)
                  <tr>
                    <td>{{ $project->ProjectName }}</td>
                    <td>{{ $project->student->FirstName ?? 'Unknown' }} {{ $project->student->LastName ?? '' }}</td>
                    <td>{{ $project->department->DepartmentName ?? 'Unknown' }}</td>
                    <td>{{ \Carbon\Carbon::parse($project->created_at)->format('Y') }}</td>
                    <td>
                        @if ($project->ProjectDissertation)
                            <a href="{{ asset('storage/' . $project->ProjectDissertation) }}" target="_blank" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1">
                              <i class="fas fa-download"></i> File
                        @else
                            Not Available
                        @endif
                    </td>
                  </tr>
              @empty
                  <tr>
                      <td colspan="4">No completed projects found.</td>
                  </tr>
              @endforelse
              </tbody>
        </table>
      </div>
    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
