<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Preferences</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #003049;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .wrapper {
            width: 100%;
            max-width: 520px;
        }

        .navbar-custom {
            background-color: #003049;
            border-radius: 1rem 1rem 0 0;
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }

        .card-custom {
            border: none;
            border-radius: 0 0 1rem 1rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .card-header-custom {
            background-color: #003049;
            color: white;
            padding: 1rem;
            text-align: center;
            border-top: 1px solid #dee2e6;
        }

        .btn-custom {
            background-color: #003049;
            border: none;
        }

        .btn-custom:hover {
            background-color: #002438;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="navbar-custom">
        <span class="h5 mb-0"><i class="bi bi-person-circle me-2"></i>Preferences</span>
        <a href="{{ route('dashboard.redirect') }}" class="btn btn-outline-light btn-sm">
            <i class="bi bi-arrow-left-circle me-1"></i>Back
        </a>
    </div>

    <div class="card card-custom">
        <div class="card-header-custom">
            <h5 class="mb-0"><i class="bi bi-shield-lock-fill me-2"></i>Change Password</h5>
        </div>
        <div class="card-body p-4">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('preferences.updatePassword') }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" name="new_password" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="new_password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-custom text-white w-100">
                    <i class="bi bi-arrow-repeat me-1"></i>Update Password
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS (for alert dismissing) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
