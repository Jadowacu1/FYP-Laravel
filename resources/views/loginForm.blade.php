<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - FYP PLATFORM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
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
            max-width: 400px;
        }

        @media (max-width: 768px) {
            .main-container {
                flex-direction: column;
            }

            .left-panel {
                padding: 2rem;
            }

            .right-panel {
                padding: 2rem;
            }
        }

        .btn-primary {
            background-color: #003049;
            border-color: #003049;
        }

        .btn-primary:hover {
            background-color: #00243a;
            border-color: #00243a;
        }

        a {
            color: #003049;
        }

        a:hover {
            color: #001d2d;
        }

        .alert-danger {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="main-container">

    <!-- Left: Description and Logo -->
    <div class="left-panel">
        <img src="https://res.cloudinary.com/dwhfpxrgz/image/upload/v1744541196/RP_Logo_si2qge.jpg" alt="RP Logo">
        <h1 class="display-6 fw-bold">FYP PLATFORM</h1>
        <p class="mt-3 fs-5">A Modern Final Year Project Management System Tailored For Students And Staff.</p>
    </div>

    <!-- Right: Login Form -->
    <div class="right-panel">
        <div class="form-wrapper">
            <h3 class="mb-4 text-center fw-semibold">Login to Your Account</h3>

            <!-- Display errors if any -->
            @if($errors->any())
                <div class="alert alert-danger">
                     <ul class="mb-0" style="list-style:none">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Role Switch -->
            <div class="mb-3">
                <label for="role" class="form-label">Login As</label>
                <select id="role" class="form-select">
                    <option value="student">Student</option>
                    <option value="staff">Staff</option>
                </select>
            </div>

            <form method="POST" action="{{ Route('login') }}">
                @csrf

                <!-- Student Field -->
                <div id="student-fields">
                    <div class="mb-3">
                        <label for="reg_no" class="form-label">Registration Number</label>
                        <input type="text" class="form-control" id="reg_no" name="reg_no" placeholder="e.g. 22RP0001">
                    </div>
                </div>

                <!-- Staff Field -->
                <div id="staff-fields" style="display: none;">
                    <div class="mb-3">
                        <label for="email" class="form-label">Staff Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="example@example.com">
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <!-- Remember -->
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember Me</label>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn btn-primary w-100">Login</button>

                <p class="text-center mt-3">
                    Don't have an account?
                    <a href="{{ route('SignUpForm') }}">Sign up here</a>
                </p>
            </form>
        </div>
    </div>
</div>

<!-- Role switcher logic -->
<script>
    const roleSelect = document.getElementById('role');
    const studentFields = document.getElementById('student-fields');
    const staffFields = document.getElementById('staff-fields');

    roleSelect.addEventListener('change', function () {
        if (this.value === 'student') {
            studentFields.style.display = 'block';
            staffFields.style.display = 'none';
        } else {
            studentFields.style.display = 'none';
            staffFields.style.display = 'block';
        }
    });
</script>
</body>
</html>
