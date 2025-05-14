<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - Add HODr</title>
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
      <h4 class="text-white mb-4 text-center">Admin Panel</h4>
      <button class="btn btn-dark mb-3" id="toggleSidebarBtn">
        <i class="bi bi-arrow-left-circle"></i>
      </button>
      <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link" href="{{ route('department.dashboard') }}"><i class="bi bi-house-door me-2"></i><span> Dashboard</span></a></li>
        <li class="nav-item"><a class="nav-link active" href="{{ route('department.addHod') }}"><i class="bi bi-person-plus me-2"></i><span> HOD</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('department.Campus') }}"><i class="bi bi-building me-2"></i><span> Campus</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('department.faculty') }}"><i class="bi bi-journal-text me-2"></i><span> Faculty</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('department.department') }}"><i class="bi bi-diagram-3 me-2"></i><span> Department</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('preferences') }}"><i class="bi bi-gear me-2"></i><span>Preferences</span>
        </a>
    </li>
      </ul>
    </nav>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="dashboard-title">{{ isset($hod) ? 'Edit HOD' : 'Add HOD' }}</h2>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="btn btn-outline-dark">Logout</button>
      </form>
      </div>

      <div class="card shadow-sm p-4 mb-5">
        <h5 class="mb-4">{{ isset($hod) ? 'Edit HOD' : 'HOD Information' }}</h5>
    <form method="POST" action="{{ isset($hod) ? route('updateHod', $hod->HodId) : route('AddHodForm') }}">
          @csrf
          @if(isset($hod))
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
                  <input type="text" name="FirstName" class="form-control @error('FirstName') is-invalid @enderror" placeholder="Enter first name" value="{{ old('FirstName', $hod->FirstName ?? '') }}" required>
                  @error('FirstName')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>
              <div class="col-md-6">
                  <label class="form-label">Last Name</label>
                  <input type="text" name="LastName" class="form-control @error('LastName') is-invalid @enderror" placeholder="Enter last name" value="{{ old('LastName', $hod->LastName ?? '') }}" required>
                  @error('LastName')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>
              <div class="col-md-6">
                  <label class="form-label">Email Address</label>
                  <input type="email" name="Email" class="form-control @error('Email') is-invalid @enderror" placeholder="example@email.com" value="{{ old('Email', $hod->Email ?? '') }}" required>
                  @error('Email')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>
              <div class="col-md-6">
                  <label class="form-label">Phone Number</label>
                  <input type="tel" name="PhoneNumber" class="form-control @error('PhoneNumber') is-invalid @enderror" placeholder="+2507XXXXXXXX" value="{{ old('PhoneNumber', $hod->PhoneNumber ?? '') }}" required>
                  @error('PhoneNumber')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>
              <div class="col-md-6">
                <label for="department" class="form-label">Select Department</label>
                <select name="DepartmentCode" id="department" class="form-select">
                    <option value="" disabled selected>Select Department</option>
                    @foreach($departments as $department)
                    <option value="{{ $department->DepartmentCode }}"
                        {{ old('DepartmentCode', $hod->DepartmentCode ?? '') == $department->DepartmentCode ? 'selected' : '' }}>
                        {{ $department->DepartmentName }}
                    </option>
                    
                    @endforeach
                </select>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi {{ isset($supervisor) ? 'bi-pencil-fill' : 'bi-person-plus-fill' }} me-2"></i>
                        {{ isset($hod) ? 'Update HOD' : 'Add HOD' }}
                    </button>
                </div>
        </div>
          </div>
      
          
        </form>
      </div>

      <!-- HOD List Table -->
      <div class="card shadow-sm">
        <div class="card-header">
          <h5 class="mb-0">HOD List</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Full Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Department</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($hods as $hodItem)
                  <tr>
                    <td>{{ $hodItem->HodId  }}</td>
                    <td>{{ $hodItem->FirstName }} {{ $hodItem->LastName }}</td>
                    <td>{{ $hodItem->Email }}</td>
                    <td>{{ $hodItem->PhoneNumber }}</td>
                    <td>
                        {{ $hodItem->department->DepartmentName ?? 'N/A' }}
                    </td>
                    <td>
                      <a href="{{ route('editHod', $hodItem->HodId ) }}" class="btn btn-sm btn-warning">Edit</a>
                      <form action="{{ route('deleteHod', $hodItem->HodId ) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this HOD?');">
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
            {{ $hods->links('pagination::bootstrap-5') }}
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
