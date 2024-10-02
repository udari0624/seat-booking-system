<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/changepassword.css') }}">
</head>
<body>
    <div class="container">
        <form action="{{ route('changepw.reset') }}" method="POST">
            @csrf
            <h1>Change Password</h1>

            @if (session('status'))
              <div class="alert alert-success">
                  {{ session('status') }}
              </div>
            @endif


            @if($errors->any())
              <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    {{ $error }}
                @endforeach
              </div>
            @endif

            <div class="mb-3">
                <label for="emp_id" class="form-label">Username</label>
                <input type="text" name="emp_id" id="emp_id" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="nic" class="form-label">NIC</label>
                <input type="text" name="nic" id="nic" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="newPassword" class="form-label">New Password</label>
                <input type="password" name="newPassword" id="newPassword" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="newPassword_confirmation" class="form-label">Confirm Password</label>
                <input type="password" name="newPassword_confirmation" id="newPassword_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Change Password</button>

            <div class="login-link">
                <p>
                    <a href="{{ route('login.view') }}" class="no-underline">Login</a>
                </p>
            </div>
        </form>
    </div>
</body>
</html>
