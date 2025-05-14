<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Department Dashboard - Assign Supervisor</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f8f9fa;
    }

    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      width: 250px;
      background-color: #003049;
      color: #fff;
      overflow-x: hidden;
      transition: width 0.3s ease;
      z-index: 1000;
    }

    .sidebar.minimized {
      width: 80px;
    }

    .sidebar .nav-link {
      display: flex;
      align-items: center;
      padding: 10px 20px;
      color: #adb5bd;
      border-radius: 8px;
      margin: 5px 10px;
      transition: background-color 0.3s ease;
    }

    .sidebar .nav-link i {
      font-size: 1rem;
    }

    .sidebar .nav-link span {
      margin-left: 10px;
      transition: opacity 0.3s ease;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
      background-color: #00243a;
      color: #fff;
    }

    .sidebar.minimized .nav-link {
      justify-content: center;
      width: 80px;
      margin: 8px auto;
      padding: 10px;
      border-radius: 8%;
    }

    .sidebar.minimized .nav-link span {
      display: none;
    }

    .main-content {
      margin-left: 250px;
      padding: 20px;
      transition: margin-left 0.3s ease;
      width: calc(100% - 250px);
    }

    .sidebar.minimized ~ .main-content {
      margin-left: 80px;
      width: calc(100% - 80px);
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<nav class="sidebar py-4 px-3" id="sidebar">
  <h4 class="text-white mb-4 text-center">Hod Panel</h4>
  <button class="btn btn-dark mb-3 w-100" id="toggleSidebarBtn">
    <i class="bi bi-arrow-left-circle"></i>
  </button>
  <ul class="nav flex-column">
    <li class="nav-item"><a class="nav-link" href="{{ route('hod.dashboard') }}"><i class="bi bi-house-door me-2"></i><span> Dashboard</span></a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('hod.addSupervisor') }}"><i class="bi bi-person-plus me-2"></i><span> Supervisors</span></a></li>
    <li class="nav-item"><a class="nav-link active" href="{{ route('assign.supervisor.page') }}"><i class="bi bi-link-45deg me-2"></i><span> Assign Supervisor</span></a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('preferences') }}"><i class="bi bi-gear me-2"></i><span> Preferences</span></a></li>
  </ul>
</nav>

<!-- Main Content -->
<div class="main-content" id="mainContent">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="dashboard-title">ASSIGN SUPERVISORS</h2>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-outline-dark">Logout</button>
        </form>
    </div>
  <div class="card shadow-sm mt-4">
    <div class="card-header">
      <h5 class="mb-0">Assign Supervisor to Project</h5>
    </div>
    <div class="card-body">
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>Project Title</th>
              <th>Student Name</th>
              <th>Supervisor</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($projects as $project)
                <tr>
                    <td>{{ $project->ProjectName }}</td>
                    <td>{{ $project->student->FirstName }} {{ $project->student->LastName }}</td>
                    <td>
                        @if(!$project->SupervisorId)
                            <form method="POST" action="{{ route('assign.supervisor', ['ProjectCode' => $project->ProjectCode]) }}">
                                @csrf
                                @method('PUT')
                                <select name="SupervisorId" class="form-select" required>
                                    <option value="" disabled selected>Select Supervisor</option>
                                    @foreach($supervisors as $supervisor)
                                        <option value="{{ $supervisor->SupervisorId }}">{{ $supervisor->FirstName }} {{ $supervisor->LastName }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm">Assign</button>
                            </form>
                        @else
                            {{ $project->supervisor->FirstName }} {{ $project->supervisor->LastName }}
                        @endif
                    </td>
                    <td>
                        @if($project->SupervisorId)
                            <form action="{{ route('remove.supervisor', $project->ProjectCode) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Remove Supervisor</button>
                            </form>
                        @else
                            <button type="button" class="btn btn-secondary btn-sm" disabled>No Supervisor Assigned</button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
        
        </table>
      </div>

      <!-- Pagination -->
      <div class="mt-3">
        {{ $projects->links('pagination::bootstrap-5') }}
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const toggleBtn = document.getElementById('toggleSidebarBtn');
  const sidebar = document.getElementById('sidebar');
  const mainContent = document.getElementById('mainContent');

  toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('minimized');
    mainContent.classList.toggle('expanded');
  });
</script>

</body>
</html>
