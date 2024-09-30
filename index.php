<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Pay System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <header class="bg-primary text-white text-center py-4 animate-header">
        <div class="container">
            <h1 class="display-4">Welcome to the Patient Pay System</h1>
            <p class="lead">Streamlining healthcare billing and payment processes for patients and staff.</p>
        </div>
    </header>

    <main class="container my-5 flex-grow-1">
        <section class="text-center">
            <h2 class="mb-4 animate-heading">Choose Your Role</h2>
            <div class="user-links">
                <a href="staff/staff_login.php" class="btn btn-primary btn-lg mx-2 shadow-sm animate-button">Staff Login</a>
                <a href="patients/login.php" class="btn btn-info btn-lg mx-2 shadow-sm animate-button">Patient Login</a>
            </div>
        </section>
    </main>

    <footer class="bg-dark text-white text-center py-3 mt-auto animate-footer">
        <div class="container">
            <p>&copy; 2024 Patient Pay System. All rights reserved.</p>
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
