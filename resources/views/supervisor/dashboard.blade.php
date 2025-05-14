<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Supervisor Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">

    <!-- Sidebar -->
    <nav class="col-md-2 sidebar d-flex flex-column">
      <h4 class="text-white text-center mb-4">Supervisor Panel</h4>
      <ul class="nav flex-column mb-auto">
        <li class="nav-item">
          <a class="nav-link active" href="#"><i class="bi bi-speedometer2"></i><span> Dashboard</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('supervisor.chat') }}">
                <i class="bi bi-chat-dots"></i><span> Chat</span>
            </a>
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
        <h2 class="dashboard-title">Supervisor Dashboard</h2>
      </div>

      <!-- Stat Cards -->
      <div class="row g-4 mb-4">
        <div class="col-md-4">
          <div class="card shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-clipboard-data text-primary" style="font-size: 2rem;"></i>
              <h5 class="mt-2">Assigned Projects</h5>
              <p class="fs-4">{{ $assignedProjects->count() }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Assigned Projects Table -->
      <div class="card shadow-sm">
        <div class="card-header">
          <h5 class="mb-0">Projects Assigned to You</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Project Title</th>
                    <th>Student Reg No</th>
                    <th>Status</th>
                    <th>Change Status</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($assignedProjects as $index => $project)
                    <tr>
                      <td>{{ $index + 1 }}</td>
                      <td>{{ $project->ProjectName }}</td>
                      <td>{{ $project->StudentRegNumber }}</td>
                      <td>
                        <span class="badge 
                          {{ 
                            $project->status == 'approved' ? 'bg-success' : 
                            ($project->status == 'rejected' ? 'bg-danger' : 
                            ($project->status == 'pending' ? 'bg-warning text-dark' : 'bg-secondary')) 
                          }}">
                          {{ ucfirst($project->status) }}
                        </span>
                      </td>
                      <td>
                        <form method="POST" action="{{ route('supervisor.updateStatus', $project->ProjectCode) }}">
                          @csrf
                          @method('PUT')
                          <div class="d-flex">
                            <select name="status" class="form-select form-select-sm me-2">
                              <option value="pending" {{ $project->status == 'pending' ? 'selected' : '' }}>Pending</option>
                              <option value="approved" {{ $project->status == 'approved' ? 'selected' : '' }}>Approve</option>
                              <option value="rejected" {{ $project->status == 'rejected' ? 'selected' : '' }}>Reject</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary">Update</button>
                          </div>
                        </form>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="5" class="text-center">No projects assigned.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
              

          <!-- Pagination -->
          <div class="d-flex justify-content-center mt-3">
            {{ $assignedProjects->links('pagination::bootstrap-5') }}
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
