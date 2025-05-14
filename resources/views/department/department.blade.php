<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Department Dashboard - Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f8f9fa; transition: background-color 0.3s ease; }
        .sidebar { height: 100vh; background-color: #003049; color: #fff; transition: width 0.3s ease; width: 250px; padding-top: 20px; overflow: hidden; }
        .sidebar.minimized { width: 80px; }
        .sidebar .nav-link { color: #adb5bd; display: flex; align-items: center; padding-left: 20px; }
        .sidebar .nav-link i { font-size: 1.5rem; }
        .sidebar .nav-link span { margin-left: 10px; transition: opacity 0.3s ease; }
        .sidebar .nav-link.active, .sidebar .nav-link:hover { background-color: #00243a; color: #fff; }
        .sidebar.minimized .nav-link span { opacity: 0; }
        .sidebar.minimized .nav-link { padding-left: 10px; }
        .main-content { transition: margin-left 0.3s ease, width 0.3s ease; margin-left: 250px; padding: 20px; }
        .main-content.expanded { margin-left: 80px; width: calc(100% - 80px); }
        .dashboard-title { font-weight: 600; font-size: 1.5rem; }
        .card:hover { transform: translateY(-10px); }
        .btn-outline-dark:hover { background-color: #003049; color: #fff; }
        .table th, .table td { font-size: 1rem; padding: 1rem; }
        .pagination .page-item a { color: #003049; }
        .pagination .page-item a:hover { background-color: #003049; color: white; }

        @media (max-width: 768px) {
            .sidebar { width: 200px; }
            .sidebar.minimized { width: 60px; }
            .main-content { margin-left: 200px; }
            .main-content.expanded { margin-left: 60px; width: calc(100% - 60px); }
        }

        @media (max-width: 576px) {
            .sidebar { width: 150px; }
            .sidebar.minimized { width: 50px; }
            .main-content { margin-left: 150px; }
            .main-content.expanded { margin-left: 50px; width: calc(100% - 50px); }
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">

        <!-- Sidebar -->
        <nav class="col-md-2 d-none d-md-block sidebar py-4 px-3" id="sidebar">
            <h4 class="text-white mb-4 text-center">Hod Panel</h4>
            <button class="btn btn-dark mb-3" id="toggleSidebarBtn"><i class="bi bi-arrow-left-circle"></i></button>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="{{ route('department.dashboard') }}"><i class="bi bi-house-door me-2"></i><span> Dashboard</span></a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('department.addHod') }}"><i class="bi bi-person-plus me-2"></i><span> HOD</span></a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('department.Campus') }}"><i class="bi bi-building me-2"></i><span> Campus</span></a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('department.faculty') }}"><i class="bi bi-journal-text me-2"></i><span> Faculty</span></a></li>
                <li class="nav-item"><a class="nav-link active" href="{{ route('department.department') }}"><i class="bi bi-diagram-3 me-2"></i><span> Department</span></a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('preferences') }}"><i class="bi bi-gear me-2"></i><span>Preferences</span>
                </a>
            </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="col-md-10 ms-sm-auto col-lg-10 px-md-5 py-4 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="dashboard-title">Department Management</h2>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-outline-dark">Logout</button>
                </form>
            </div>

            <!-- Toasts -->
            @if(session('success') || session('error') || $errors->any())
                <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100">
                    @if(session('success'))
                        <div class="toast align-items-center text-bg-success border-0 show" role="alert">
                            <div class="d-flex">
                                <div class="toast-body">{{ session('success') }}</div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="toast align-items-center text-bg-danger border-0 show" role="alert">
                            <div class="d-flex">
                                <div class="toast-body">{{ session('error') }}</div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="toast align-items-center text-bg-warning border-0 show" role="alert">
                            <div class="d-flex">
                                <div class="toast-body">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Department Form -->
            <form action="{{ isset($department) ? route('updateDepartment', $department->DepartmentCode) : route('addDepartmentForm') }}" method="POST">
                @csrf
                @if(isset($department)) @method('PUT') @endif
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="DepartmentName" class="form-label">Department Name</label>
                        <input type="text" name="DepartmentName" class="form-control" value="{{ old('DepartmentName', $department->DepartmentName ?? '') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="FacultyId" class="form-label">Faculty</label>
                        <select name="FacultyCode" class="form-select" required>
                            <option value="">-- Select Faculty --</option>
                            @foreach($faculties as $faculty)
                                <option value="{{ $faculty->FacultyCode }}" {{ (isset($department) && $department->FacultyId == $faculty->FacultyCode) ? 'selected' : '' }}>
                                    {{ $faculty->FacultyName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-{{ isset($department) ? 'warning' : 'primary' }}">
                    {{ isset($department) ? 'Update Department' : 'Add Department' }}
                </button>
                @if(isset($department))
                    <a href="{{ route('department.department') }}" class="btn btn-secondary ms-2">Cancel</a>
                @endif
            </form>

            <!-- Department Table -->
            <h4 class="mt-5 mb-3">List of Departments</h4>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Department Name</th>
                        <th>Faculty</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $department)
                        <tr>
                            <td>{{ $department->DepartmentName }}</td>
                            <td>{{ $department->faculty->FacultyName ?? 'N/A' }}</td>
                            <td>{{ $department->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('editDepartment', $department->DepartmentCode) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Edit</a>
                                <form action="{{ route('deleteDepartment', $department->DepartmentCode) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this department?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center">No department found.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $departments->links('pagination::bootstrap-5') }}
            </div>
        </main>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('toggleSidebarBtn').addEventListener('click', function () {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.querySelector('.main-content');
        sidebar.classList.toggle('minimized');
        mainContent.classList.toggle('expanded');
    });

    const toastElList = [].slice.call(document.querySelectorAll('.toast'));
    toastElList.forEach(function (toastEl) {
        new bootstrap.Toast(toastEl).show();
    });
</script>

</body>
</html>
