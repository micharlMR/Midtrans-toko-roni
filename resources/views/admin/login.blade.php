<!-- resources/views/admin/login.blade.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .login-container {
            margin-top: 5%;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center login-container">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Admin Login</div>

                    <div class="card-body">
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form method="POST" action="{{ route('admin.login') }}">
                            @csrf

                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input id="password" type="password" class="form-control" name="password" required>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>