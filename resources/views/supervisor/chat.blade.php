<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chat with Student - Supervisor Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
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
            padding-top: 20px;
            width: 250px;
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
                    <a class="nav-link" href="{{ route('supervisor.dashboard') }}">
                        <i class="bi bi-house-door"></i><span> Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('supervisor.chat') }}">
                        <i class="bi bi-chat-dots"></i><span> Chat</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('preferences') }}">
                        <i class="bi bi-gear me-2"></i><span> Preferences</span>
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
        <main class="col-md-10 ms-sm-auto col-lg-10 px-md-5 py-4 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Chat with Your Students</h2>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-outline-dark">Logout</button>
                </form>
            </div>

            @if($assignedStudents->isNotEmpty())
                <div class="row">
                    @foreach($assignedStudents as $student)
                        <div class="col-md-6 col-lg-5 mb-4">
                            <div class="card shadow border-0">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="me-3">
                                            <i class="bi bi-person-circle" style="font-size: 2.5rem; color: #0d6efd;"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0">{{ $student->FirstName }} {{ $student->LastName }}</h5>
                                            <small class="text-muted">Student</small>
                                        </div>
                                    </div>
                                    <p class="mb-2">
                                        <i class="bi bi-envelope me-2"></i>
                                        <a href="mailto:{{ $student->Email }}">{{ $student->Email }}</a>
                                    </p>
                                    <p class="mb-3">
                                        <i class="bi bi-telephone me-2"></i>{{ $student->PhoneNumber }}
                                    </p>
                                    @php
                                        $whatsAppNumber = preg_replace('/[^0-9]/', '', $student->PhoneNumber);
                                        $whatsAppNumber = '25' . $whatsAppNumber;
                                    @endphp
                                    <div class="d-grid gap-2">
                                        <a href="https://wa.me/{{ $whatsAppNumber }}" target="_blank" class="btn btn-success">
                                            <i class="bi bi-whatsapp"></i> Chat on WhatsApp
                                        </a>
                                        <a href="tel:{{ $student->PhoneNumber }}" class="btn btn-primary">
                                            <i class="bi bi-telephone-forward"></i> Call Student
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-warning">
                    No students assigned to you yet.
                </div>
            @endif
        </main>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
