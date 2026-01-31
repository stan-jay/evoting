<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>E-Voting System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-light bg-white shadow-sm">
    <div class="container">
        <span class="navbar-brand fw-bold">E-Voting</span>

        @guest
            <div>
                <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Log in</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
            </div>
        @endguest
    </div>
</nav>

<div class="container vh-100 d-flex align-items-center">
    <div class="row w-100">
        <div class="col-md-6">
            <h1 class="display-5 fw-bold">Secure Online Voting</h1>
            <p class="lead mt-3">
                Transparent. Secure. One voter, one vote.
            </p>

            @guest
                <div class="mt-4">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg me-2">Log in</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-lg">Register</a>
                </div>
            @endguest
        </div>
    </div>
</div>

</body>
</html>
