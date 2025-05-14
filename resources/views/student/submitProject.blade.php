<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Submit Project Proposal</title>
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
    .progress-bar {
      background-color: #003049;
    }
    .form-step {
      display: none;
    }
    .form-step.active {
      display: block;
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
          <a class="nav-link" href="{{ route('searchCompletedProjects') }}"><i class="bi bi-search"></i><span> Completed Projects</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="{{ route('student.submitProject') }}"><i class="bi bi-upload"></i><span> Submit Proposal</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('student.viewProject') }}"><i class="bi bi-collection"></i><span> My Projects</span></a>
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
      <h2 class="dashboard-title">Submit Project Proposal</h2>
      <p>Follow the steps to submit your project proposal.</p>

      <!-- Toasts -->
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      @endif


      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <!-- Progress -->
      <div class="progress mb-3" style="height: 10px;">
        <div class="progress-bar" id="progressBar" role="progressbar" style="width: 33%;" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100"></div>
      </div>
      <div class="d-flex justify-content-between mb-4">
        <small>Step 1 of 3: Project Information</small>
        <small>Step 2 of 3: Project Files</small>
        <small>Step 3 of 3: Finalize</small>
      </div>

      <!-- Multi-Step Form -->
      <form action="{{ route('student.AddProject') }}" method="POST" enctype="multipart/form-data" id="projectForm">
        @csrf

        <!-- Step 1 -->
        <div class="form-step active">
          <div class="mb-3">
            <label for="projectName" class="form-label">Project Title</label>
            <input type="text" class="form-control" name="ProjectName" required>
          </div>
          <div class="mb-3">
            <label for="DepartmentCode" class="form-label">Department</label>
            <select class="form-select" name="DepartmentCode" required>
              <option value="">-- Select Department --</option>
              @foreach($departments as $department)
                <option value="{{ $department->DepartmentCode }}">{{ $department->DepartmentName }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Problems to Solve</label>
            <textarea class="form-control" name="ProjectProblems" rows="3" required></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Proposed Solutions</label>
            <textarea class="form-control" name="ProjectSolutions" rows="3" required></textarea>
          </div>
          <button type="button" class="btn btn-primary" onclick="nextStep()">Next</button>
        </div>

        <!-- Step 2 -->
        <div class="form-step">
          <div class="mb-3">
            <label class="form-label">Dissertation File (PDF/DOC)</label>
            <input type="file" class="form-control" name="ProjectDissertation" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Source Code (ZIP/RAR)</label>
            <input type="file" class="form-control" name="ProjectSourceCodes">
          </div>
          <button type="button" class="btn btn-secondary" onclick="prevStep()">Previous</button>
          <button type="button" class="btn btn-primary" onclick="nextStep()">Next</button>
        </div>

        <!-- Step 3 -->
        <div class="form-step">
          <div class="mb-3">
            <label class="form-label">Project Abstract (optional)</label>
            <textarea class="form-control" name="ProjectAbstract" rows="4"></textarea>
          </div>
          <button type="button" class="btn btn-secondary" onclick="prevStep()">Previous</button>
          <button type="submit" class="btn btn-success">Submit Proposal</button>
        </div>
      </form>
    </main>
  </div>
</div>

<!-- JS -->
<script>
  let currentStep = 0;
  const steps = document.querySelectorAll(".form-step");
  const progressBar = document.getElementById("progressBar");

  function showStep(index) {
    steps.forEach((step, i) => {
      step.classList.toggle("active", i === index);
    });
    const progress = ((index + 1) / steps.length) * 100;
    progressBar.style.width = progress + "%";
    progressBar.setAttribute("aria-valuenow", progress);
  }

  function nextStep() {
    if (currentStep < steps.length - 1) {
      currentStep++;
      showStep(currentStep);
    }
  }

  function prevStep() {
    if (currentStep > 0) {
      currentStep--;
      showStep(currentStep);
    }
  }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html> 
