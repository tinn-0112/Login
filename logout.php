<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logout</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<style>
    body {
    font-family: 'Segoe UI', Arial, sans-serif;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    min-height: 100vh;
}

body {
    background: linear-gradient(135deg, #62666b, #343332);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.btn {
    background: linear-gradient(135deg, #62666b, #343332);
    border-radius: 8px;
    font-weight: 500;
    transition: background-color 0.2s ease, transform 0.1s ease;
}
.card {
    border: none;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}


.card {
    background: linear-gradient(135deg, #959ea9, #c1a992);
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

/* Card styling */
.card {
    border-radius: 12px;
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
</style>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

    <div class="card shadow p-4 text-center" style="width: 400px;">
        <h3 class="mb-3">You have been logged out</h3>
        <a href="login.php" class="btn btn-outline-secondary w-100">Return to Login</a>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>