<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Campus Dashboard - Admin Panel</title>
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
       /* —— Sidebar (working version) —— */
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
    
    /* Nav link base */
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
    
    /* Link text */
    .sidebar .nav-link span {
      margin-left: 10px;
      transition: opacity 0.3s ease;
    }
    
    /* Full‑width hover/active (expanded) */
    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
      background-color: #00243a;
      color: #fff;
    }
    
    /* —— Minimized overrides —— */
    /* Center the icon, hide text */
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
    
    /* Icon‑only hover/active (minimized) */
    .sidebar.minimized .nav-link:hover,
    .sidebar.minimized .nav-link.active {
      background-color: #00243a;
      color: #fff;
    }
    
    
        .main-content {
          margin-left: 250px;
          padding: 20px;
          transition: margin-left 0.3s ease;
        }
    
        .main-content.expanded {
          margin-left: 80px;
        }
    
        .main-content {
          transition: all 0.3s ease;
          margin-left: 250px;
          width: calc(100% - 250px);
        }
    
        .sidebar.minimized ~ .main-content {
          margin-left: 80px;
          width: calc(100% - 80px);
        }
      </style>
    </head>
    <body>
    
    <div class="container-fluid">
      <div class="row">
    
        <!-- Sidebar -->
        <nav class="sidebar py-4 px-3" id="sidebar">
          <h4 class="text-white mb-4 text-center">Admin Panel</h4>
          <button class="btn btn-dark mb-3" id="toggleSidebarBtn">
            <i class="bi bi-arrow-left-circle"></i>
          </button>
          <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link" href="{{ route('department.dashboard') }}"><i class="bi bi-house-door me-2"></i><span> Dashboard</span></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('department.addHod') }}"><i class="bi bi-person-plus me-2"></i><span> HOD</span></a></li>
            <li class="nav-item"><a class="nav-link active" href="{{ route('department.Campus') }}"><i class="bi bi-building me-2"></i><span> Campus</span></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('department.faculty') }}"><i class="bi bi-journal-text me-2"></i><span> Faculty</span></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('department.department') }}"><i class="bi bi-diagram-3 me-2"></i><span> Department</span></a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('preferences') }}"><i class="bi bi-gear me-2"></i><span>Preferences</span>
            </a>
        </li>
          </ul>
        </nav>

            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto col-lg-10 px-md-5 py-4 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="dashboard-title">Campus Dashboard</h2>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-outline-dark">Logout</button>
                    </form>
                </div>

                <!-- Campus Form -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Add Campus</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('addCampusForm') }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-5">
                                    <input type="text" name="CampusName" class="form-control @error('CampusName') is-invalid @enderror" placeholder="Campus name" value="{{ old('CampusName') }}" required>
                                    @error('CampusName') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-5">
                                    <input type="text" name="CampusLocation" class="form-control @error('CampusLocation') is-invalid @enderror" placeholder="Location" value="{{ old('CampusLocation') }}" required>
                                    @error('CampusLocation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">Add Campus</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Campus Table -->
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">Campus List</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Location</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($campuses as $index => $campus)
    <tr>
        <td>{{ $campuses->firstItem() + $index }}</td>
        <td>{{ $campus->CampusName }}</td>
        <td>{{ $campus->CampusLocation }}</td>
        <td>
            <form method="POST" action="{{ route('department.deleteCampus', $campus->CampusId) }}" onsubmit="return confirm('Delete this campus?')" style="display:inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
            </form>
        </td>
    </tr>
@endforeach

@if($campuses->isEmpty())
    <tr>
        <td colspan="4" class="text-center">No campuses found.</td>
    </tr>
@endif

                                </tbody>
                                
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($campuses->hasPages())
                            <div class="pagination-nav">
                                {{ $campuses->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle Sidebar
        document.getElementById('toggleSidebarBtn').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('minimized');
            document.querySelector('.main-content').classList.toggle('expanded');
        });

        // Show toast
        const toastEl = document.querySelector('.toast');
        if (toastEl) {
            new bootstrap.Toast(toastEl).show();
        }
    </script>
</body>
</html>
