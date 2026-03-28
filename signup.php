<?php
session_start();

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "appdev";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname  = $_POST['firstname'];
    $lastname   = $_POST['lastname'];
    $email      = $_POST['email'];
    $password   = $_POST['password'];

    $hashed_pass = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("SQL error: " . $conn->error);
    }

    $stmt->bind_param("ssss", $firstname, $lastname, $email, $hashed_pass);

  // Check if email already exists
$check = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo '
    <div id="popMessage" class="alert alert-success position-fixed bottom-0 end-0 m-3">
        Registration failed: Email already exists.
    </div>
    ';
} else {
    if ($stmt->execute()) {
        echo '
        <div id="popMessage" class="alert alert-success position-fixed bottom-0 end-0 m-3">
            Registration successful! You can now 
            <a href="login.php" class="alert-link">login here</a>.
        </div>
        ';
    }
}
}

?>


<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
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
.btn {
    background: linear-gradient(135deg, #62666b, #343332);
    border-radius: 8px;
    font-weight: 500;
    transition: background-color 0.2s ease, transform 0.1s ease;
}
.popMessage {
    position: fixed;
    top: 20px;              /* distance from top */
    left: 50%;
    transform: translateX(-50%);
    z-index: 9999;
    width: auto;
    max-width: 400px;
    text-align: center;
    font-weight: 500;
    box-shadow: 0 6px 18px rgba(0,0,0,0.15);
    border-radius: 8px;
    animation: fadeInDown 1s ease;
}
</style>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

    <div class="container d-flex justify-content-center align-items-center vh-100">
  <div class="card shadow p-4" style="width: 600px;">
    <h3 class="text-center mb-4">Create Your Account</h3>

    <form method="POST" action="signup.php">
      <!-- Row 1: First + Last Name -->
      <div class="row mb-3">
        <div class="col">
          <label class="form-label">First Name</label>
          <input type="text" name="firstname" class="form-control" required>
        </div>
        <div class="col">
          <label class="form-label">Last Name</label>
          <input type="text" name="lastname" class="form-control" required>
        </div>
      </div>

      <!-- Row 2: Email -->
      <div class="row mb-3">
        <div class="col">
          <label class="form-label">Email Address</label>
          <input type="email" name="email" class="form-control" required>
        </div>
      </div>

      <!-- Row 3: Password + Confirm Password -->
      <div class="row mb-3">
        <div class="col">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <div class="col">
          <label class="form-label">Confirm Password</label>
          <input type="password" name="confirm_password" class="form-control" required>
        </div>
      </div>

      <!-- Actions -->
      <button type="submit" class="btn btn-primary w-100">Sign Up</button>
      <p class="text-center mt-3">
        Already have an account? <a href="login.php">Log in</a>
      </p>
    </form>
  </div>
</div>

     <script>
document.addEventListener("DOMContentLoaded", function() {
    const msg = document.getElementById("popMessage");
    if (msg) {
        setTimeout(() => {
            msg.classList.add("fade-out");
        }, 2600); // 3 seconds before fade-out
        setTimeout(() => {
            msg.style.display = "none";
        }, 3000); // remove after fade animation
    }
});
</script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>