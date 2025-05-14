<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Dashboard</title>
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
          <a class="nav-link active" href="{{ route('student.dashboard') }}"><i class="bi bi-house-door"></i><span> Dashboard</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('searchCompletedProjects') }}"><i class="bi bi-search"></i><span> Completed Projects</span></a>
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
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="dashboard-title">Student Dashboard</h2>
        <button class="btn btn-outline-dark">
          <a class="nav-link" href="{{ route('student.submitProject') }}"><i class="bi bi-upload"></i><span> Submit Proposal</span></a>
        </button>
      </div>

      <!-- Stat Cards -->
      <div class="row g-4 mb-4">
        <div class="col-md-4">
          <div class="card shadow-sm">
            <div class="card-body text-center">
              <a href="{{ route('student.viewProject') }}" class="text-decoration-none">
              <i class="bi bi-file-earmark-text-fill text-primary" style="font-size: 2rem;"></i>
              <h5 class="mt-3">My Project</h5>
              <p>View your submitted project proposals.</p>
              </a>
            </div>
          </div>
        </div>
      
        <div class="col-md-4">
          <div class="card shadow-sm">
            <div class="card-body text-center">
              <a href="{{ route('searchCompletedProjects') }}" class="text-decoration-none">
              <i class="bi bi-journal-check text-success" style="font-size: 2rem;"></i>
              <h5 class="mt-3">Completed Projects</h5>
              <p>Search through completed projects database.</p>
            </a>
            </div>
          </div>
        </div>
     

        <div class="col-md-4">
          <div class="card shadow-sm">
            <div class="card-body text-center">
              <a href="{{ route('student.chat') }}" class="text-decoration-none">
              <i class="bi bi-chat-dots-fill text-warning" style="font-size: 2rem;"></i>
              <h5 class="mt-3">Chat with Supervisor</h5>
              <p>Communicate with your assigned supervisor.</p>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Projects Table (Example Table) -->
      <div class="card shadow-sm">
        <div class="card-header">
          <h5 class="card-title mb-0">Completed Projects</h5>
        </div>
        <div class="card-body">
          <table class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>#</th>
                <th>Project Title</th>
                <th>Year</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($approvedProjects as $index => $project)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $project->ProjectName }}</td>
                  <td>{{ \Carbon\Carbon::parse($project->created_at)->format('Y') }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center">No approved projects found.</td>
                </tr>
              @endforelse
            </tbody>
            
          </table>
<!-- Pagination -->
<div class="d-flex justify-content-center mt-4">
  {{ $approvedProjects->links('pagination::bootstrap-5') }}
</div>

        </div>
      </div>
    </main>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
