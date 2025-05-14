<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Department Dashboard - Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8f9fa;
            transition: background-color 0.3s ease;
        }

        /* Sidebar styles */
        .sidebar {
            height: 100vh;
            background-color: #003049;
            color: #fff;
            transition: width 0.3s ease;
            width: 250px;
            padding-top: 20px;
            overflow: hidden;
        }

        .sidebar.minimized {
            width: 80px;
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
            transition: opacity 0.3s ease;
        }

        .sidebar .nav-link.active, .sidebar .nav-link:hover {
            background-color: #00243a;
            color: #fff;
        }

        .sidebar.minimized .nav-link span {
            opacity: 0;
        }

        .sidebar.minimized .nav-link {
            padding-left: 10px;
        }

        /* Main content area */
        .main-content {
            transition: margin-left 0.3s ease, width 0.3s ease;
            margin-left: 250px; /* Space for sidebar */
            padding: 20px;
        }

        .main-content.expanded {
            margin-left: 80px; /* Space for minimized sidebar */
            width: calc(100% - 80px); /* Fill the space left by the sidebar */
        }

        /* Dashboard title */
        .dashboard-title {
            font-weight: 600;
            font-size: 1.5rem;
        }

        /* Card design (unchanged from previous) */
        .card-icon {
            font-size: 2rem;
            padding: 20px;
            background: rgba(0, 48, 73, 0.1);
            border-radius: 0.75rem;
        }

        .card {
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
        }

        .btn-outline-dark:hover {
            background-color: #003049;
            color: #fff;
        }

        /* Table and pagination styles */
        .table th, .table td {
            font-size: 1rem;
            padding: 1rem;
        }

        .pagination .page-item a {
            color: #003049;
        }

        .pagination .page-item a:hover {
            background-color: #003049;
            color: white;
        }

        /* Media queries */
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .sidebar.minimized {
                width: 60px;
            }

            .main-content {
                margin-left: 200px;
            }

            .main-content.expanded {
                margin-left: 60px;
                width: calc(100% - 60px);
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                width: 150px;
            }

            .sidebar.minimized {
                width: 50px;
            }

            .main-content {
                margin-left: 150px;
            }

            .main-content.expanded {
                margin-left: 50px;
                width: calc(100% - 50px);
            }
        }

    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">

        <!-- Sidebar -->
        <nav class="col-md-2 d-none d-md-block sidebar py-4 px-3" id="sidebar">
            <h4 class="text-white mb-4 text-center">Admin Panel</h4>
            <button class="btn btn-dark mb-3" id="toggleSidebarBtn">
                <i class="bi bi-arrow-left-circle"></i>
            </button>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link active" href="{{ route('department.dashboard') }}"><i class="bi bi-house-door me-2"></i><span> Dashboard</span></a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('department.addHod') }}"><i class="bi bi-person-plus me-2"></i><span> HOD</span></a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('department.Campus') }}"><i class="bi bi-building me-2"></i><span> Campus</span></a></li>
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
                <h2 class="dashboard-title">Department Dashboard</h2>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-outline-dark">Logout</button>
                </form>
                
            </div>

            <!-- Stat Cards (Original design preserved) -->
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">HOD</h6>
                                <h3>{{ $hodCount }}</h3>
                            </div>
                            <div class="card-icon text-primary">
                                <i class="bi bi-person-badge-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">Faculties</h6>
                                <h3>{{ $facultyCount }}</h3>
                            </div>
                            <div class="card-icon text-success">
                                <i class="bi bi-diagram-3-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">Campuses</h6>
                                <h3>{{$campusCount}}</h3>
                            </div>
                            <div class="card-icon text-warning">
                                <i class="bi bi-building-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">Departments</h6>
                                <h3>{{ $departmentCount }}</h3>
                            </div>
                            <div class="card-icon text-danger">
                                <i class="bi bi-diagram-2-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mt-5">
                <div class="card-header">
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Bootstrap JS & jQuery -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Toggle Sidebar
    document.getElementById('toggleSidebarBtn').addEventListener('click', function() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.querySelector('.main-content');
        
        sidebar.classList.toggle('minimized');
        mainContent.classList.toggle('expanded');
    });
</script>

</body>
</html>
