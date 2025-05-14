<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Signup</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-container {
            height: 100vh;
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
        }

        .left-panel {
            background-color: #003049;
            color: white;
            flex: 1;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .left-panel img {
            max-height: 160px;
            margin-bottom: 20px;
        }

        .right-panel {
            background-color: #fff;
            flex: 1;
            padding: 3rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-wrapper {
            width: 100%;
            max-width: 450px;
        }

        .btn-primary {
            background-color: #003049;
            border-color: #003049;
        }

        .btn-primary:hover {
            background-color: #00243a;
            border-color: #00243a;
        }

        @media (max-width: 768px) {
            .main-container {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<!-- Toast Notification -->
@if (session('success'))
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1100;">
        <div id="toastMessage" class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
@endif


<div class="main-container">
    <div class="left-panel">
        <img src="https://res.cloudinary.com/dwhfpxrgz/image/upload/v1744541196/RP_Logo_si2qge.jpg" alt="RP Logo">
        <h1 class="display-6 fw-bold">FYP PLATFORM</h1>
        <p class="fs-5">Student Registration Form</p>
    </div>

    <div class="right-panel">
        <div class="form-wrapper">
            {{-- Show errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0" style="list-style:none">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('SignUp') }}" autocomplete="off">
                @csrf

                <!-- STEP 1 -->
                <div id="step1">
                    <h4 class="mb-3 text-center fw-semibold">Step 1 of 2</h4>

                    <div class="mb-3">
                        <label for="reg_no" class="form-label">Student Reg. Number</label>
                        <input type="text" class="form-control" id="reg_no" name="StudentRegNumber" required>
                    </div>

                    <div class="mb-3">
                        <label for="first" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first" name="FirstName" required>
                    </div>

                    <div class="mb-3">
                        <label for="last" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last" name="LastName" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gender</label>
                        <select class="form-select" name="Gender" required>
                            <option value="">-- Select Gender --</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>

                    <button type="button" class="btn btn-primary w-100" onclick="nextStep()">Next</button>
                </div>

                <!-- STEP 2 -->
                <div id="step2" style="display: none;">
                    <h4 class="mb-3 text-center fw-semibold">Step 2 of 2</h4>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="Email" required>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="PhoneNumber" required>
                    </div>

                    <div class="mb-3">
                        <label for="dept" class="form-label">Department</label>
                        <select class="form-select" name="DepartmentCode" id="dept" required>
                            <option value="">-- Select Department --</option>
                          
                            @foreach($departments as $dept)
                                <option value="{{ $dept->DepartmentCode }}">{{ $dept->DepartmentName }}</option>
                            @endforeach 
                           
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Create Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary" onclick="prevStep()">Back</button>
                        <button type="submit" class="btn btn-primary">Create Account</button>
                    </div>

                    <p class="text-center mt-3">
                        Already have an account? <a href="{{ route('LoginForm') }}">Login here</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function nextStep() {
        document.getElementById('step1').style.display = 'none';
        document.getElementById('step2').style.display = 'block';
    }

    function prevStep() {
        document.getElementById('step2').style.display = 'none';
        document.getElementById('step1').style.display = 'block';
    }


    document.addEventListener("DOMContentLoaded", function () {
        const toastEl = document.getElementById('toastMessage');
        if (toastEl) {
            const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
            toast.show();

            // Redirect after 4 seconds
            setTimeout(() => {
                window.location.href = "{{ url('/') }}";
            }, 4000);
        }
    });

</script>
</body>
</html>
