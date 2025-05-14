<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Department Dashboard - Add Supervisor</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
      <h4 class="text-white mb-4 text-center">Hod Panel</h4>
      <button class="btn btn-dark mb-3" id="toggleSidebarBtn">
        <i class="bi bi-arrow-left-circle"></i>
      </button>
      <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link" href="{{ route('hod.dashboard') }}"><i class="bi bi-house-door me-2"></i><span> Dashboard</span></a></li>
        <li class="nav-item"><a class="nav-link active" href="{{ route('hod.addSupervisor') }}"><i class="bi bi-person-plus me-2"></i><span> Supervisors</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('assign.supervisor.page') }}"><i class="bi bi-link-45deg me-2"></i><span> Assign Supervisor</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('preferences') }}"><i class="bi bi-gear me-2"></i><span>Preferences</span>
        </a>
    </li>
    </ul>
    </nav>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="dashboard-title">{{ isset($supervisor) ? 'Edit Supervisor' : 'Add Supervisor' }}</h2>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="btn btn-outline-dark">Logout</button>
      </form>
      </div>

      <div class="card shadow-sm p-4 mb-5">
        <h5 class="mb-4">{{ isset($supervisor) ? 'Edit Supervisor' : 'Supervisor Information' }}</h5>
        <form method="POST" action="{{ isset($supervisor) ? route('hod.updateSupervisor', $supervisor->SupervisorId) : route('AddSupervisorForm') }}">
          @csrf
          @if(isset($supervisor))
              @method('PUT')
          @endif
      
          @if(session('success'))
              <div class="toast-container position-fixed top-0 end-0 p-3">
                  <div class="toast align-items-center text-bg-success border-0 show" role="alert">
                      <div class="d-flex">
                          <div class="toast-body">{{ session('success') }}</div>
                          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                      </div>
                  </div>
              </div>
          @endif
      
          <div class="row g-3">
              <div class="col-md-6">
                  <label class="form-label">First Name</label>
                  <input type="text" name="FirstName" class="form-control @error('FirstName') is-invalid @enderror" placeholder="Enter first name" value="{{ old('FirstName', $supervisor->FirstName ?? '') }}" required>
                  @error('FirstName')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>
              <div class="col-md-6">
                  <label class="form-label">Last Name</label>
                  <input type="text" name="LastName" class="form-control @error('LastName') is-invalid @enderror" placeholder="Enter last name" value="{{ old('LastName', $supervisor->LastName ?? '') }}" required>
                  @error('LastName')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>
              <div class="col-md-6">
                  <label class="form-label">Email Address</label>
                  <input type="email" name="Email" class="form-control @error('Email') is-invalid @enderror" placeholder="example@email.com" value="{{ old('Email', $supervisor->Email ?? '') }}" required>
                  @error('Email')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>
              <div class="col-md-6">
                  <label class="form-label">Phone Number</label>
                  <input type="tel" name="PhoneNumber" class="form-control @error('PhoneNumber') is-invalid @enderror" placeholder="+2507XXXXXXXX" value="{{ old('PhoneNumber', $supervisor->PhoneNumber ?? '') }}" required>
                  @error('PhoneNumber')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>
          </div>
      
          <div class="mt-4 text-end">
              <button type="submit" class="btn btn-primary px-4">
                  <i class="bi {{ isset($supervisor) ? 'bi-pencil-fill' : 'bi-person-plus-fill' }} me-2"></i>
                  {{ isset($supervisor) ? 'Update Supervisor' : 'Add Supervisor' }}
              </button>
          </div>
        </form>
      </div>

      <!-- Supervisor List Table -->
      <div class="card shadow-sm">
        <div class="card-header">
          <h5 class="mb-0">Supervisor List</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered">
              <thead>
                <tr>
                  {{-- <th>#</th> --}}
                  <th>Full Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($supervisors as $supervisorItem)
                  <tr>
                    {{-- <td>{{ $supervisorItem->SupervisorId }}</td> --}}
                    <td>{{ $supervisorItem->FirstName }} {{ $supervisorItem->LastName }}</td>
                    <td>{{ $supervisorItem->Email }}</td>
                    <td>{{ $supervisorItem->PhoneNumber }}</td>
                    <td>
                      <a href="{{ route('hod.editSupervisor', $supervisorItem->SupervisorId) }}" class="btn btn-sm btn-warning">Edit</a>
                      <form action="{{ route('hod.deleteSupervisor', $supervisorItem->SupervisorId) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this supervisor?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                      </form>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="d-flex justify-content-center mt-4">
            {{ $supervisors->links('pagination::bootstrap-5') }}
          </div>
        </div>
      </div>

    </main>
  </div>
</div>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Sidebar Toggle Script -->
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
