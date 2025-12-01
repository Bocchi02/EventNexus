<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Events</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Header styling */
        header {
            background-color: #0d6efd;
            color: white;
            padding: 20px 0;
            text-align: center;
            position: relative;
        }

        header h1 {
            margin: 0;
            font-weight: 600;
        }

        header p {
            margin: 0;
            font-size: 0.95rem;
        }

        .logout-btn {
            position: absolute;
            top: 15px;
            right: 30px;
        }

        .logout-btn button {
            border: none;
            background-color: white;
            color: #0d6efd;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .logout-btn button:hover {
            background-color: #e2e6ea;
        }

        h2.title {
            color: #0d6efd;
            text-align: center;
            margin-top: 40px;
            font-weight: 600;
        }

        .event-card {
            border: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            background: white;
        }

        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }

        .card-body h5 {
            font-weight: 600;
            color: #333;
        }

        .text-muted {
            font-size: 0.9rem;
        }

        .btn-sm {
            min-width: 90px;
            font-weight: 500;
            border-radius: 6px;
        }

        .btn-success {
            background-color: #198754;
            border: none;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
        }

        .btn-success:hover {
            background-color: #157347;
        }

        .btn-danger:hover {
            background-color: #bb2d3b;
        }

        .container {
            margin-top: 60px;
            margin-bottom: 60px;
        }
    </style>
</head>
<body>

    <!-- ✅ Header Section with Logout -->
    <header>
        <h1>Welcome, Guest!</h1>
        <p>Explore and respond to upcoming events</p>

        <form action="/logout" method="POST" class="logout-btn">
            <!-- Laravel CSRF token -->
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button type="submit">Logout</button>
        </form>
    </header>

    <div class="container">
        <h2 class="title mb-5">Upcoming Events</h2>

        <div class="row g-4">
            <!-- Event 1 -->
            <div class="col-md-4">
                <div class="card event-card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Tech Summit 2025</h5>
                        <p class="text-muted">📅 Dec 10, 2025 | Manila</p>
                        
                        <div class="d-flex justify-content-center gap-2">
                            <a href="#" class="btn btn-success btn-sm">Accept</a>
                            <a href="#" class="btn btn-danger btn-sm">Decline</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Event 2 -->
            <div class="col-md-4">
                <div class="card event-card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Art & Design Expo</h5>
                        <p class="text-muted">📅 Jan 5, 2026 | Cebu</p>

                        <div class="d-flex justify-content-center gap-2">
                            <a href="#" class="btn btn-success btn-sm">Accept</a>
                            <a href="#" class="btn btn-danger btn-sm">Decline</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Event 3 -->
            <div class="col-md-4">
                <div class="card event-card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Music Festival</h5>
                        <p class="text-muted">📅 Feb 2, 2026 | Davao</p>

                        <div class="d-flex justify-content-center gap-2">
                            <a href="#" class="btn btn-success btn-sm">Accept</a>
                            <a href="#" class="btn btn-danger btn-sm">Decline</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
