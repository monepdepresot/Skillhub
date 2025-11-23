<!DOCTYPE html>
<html>
<head>
    <title>SkillHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">SkillHub</a>
            <div>
                <a href="{{ route('participants.index') }}" class="text-white me-3">Participants</a>
                <a href="{{ route('courses.index') }}" class="text-white">Courses</a>
            </div>
        </div>
    </nav>
    <div class="container">
        @yield('content')
    </div>
</body>
</html>
