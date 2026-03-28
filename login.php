<?php
session_start();
require_once(__DIR__ . '/vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$client = new Google_Client();
$client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
$client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
$client->setRedirectUri($_ENV['GOOGLE_REDIRECT_URI']);
$client->addScope("email");
$client->addScope("profile");

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "appdev";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- GOOGLE LOGIN ---
if (isset($_POST['credential'])) {
    $ticket = $client->verifyIdToken($_POST['credential']);
    if ($ticket) {
        $data = $ticket->getAttributes();
        $payload = $data['payload'];
        $email = $payload['email'];
        $fullName = $payload['name'];

        $parts = explode(" ", $fullName);
        if (count($parts) > 2) {
            $firstname = $parts[0] . " " . $parts[1];
            $lastname  = implode(" ", array_slice($parts, 2));
        } else {
            $firstname = $parts[0];
            $lastname  = $parts[1] ?? "";
        }

        // Check if user exists
        $stmt = $conn->prepare("SELECT id, firstname FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, NULL)");
            $stmt->bind_param("sss", $firstname, $lastname, $email);
            if (!$stmt->execute()) {
                die("Insert failed: " . $stmt->error);
            }
            $user_id = $stmt->insert_id;
            $username = $firstname;
        } else {
            $row = $result->fetch_assoc();
            $user_id = $row['id'];
            $username = $row['firstname'];
        }

        $_SESSION['id']    = $user_id;
        $_SESSION['email'] = $email;

        header("Location: dashboard.php");
        exit;
    } else {
        header("Location: login.php?status=invalidtoken");
        exit;
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    // Look up user by email
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("SQL error: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify hashed password
        if (password_verify($password, $row['password'])) {
            $_SESSION['email'] = $row['email'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "<div class='alert alert-danger text-center'>Invalid password!</div>";
        }
    } else {
        $error = "<div class='alert alert-danger text-center'>Email not found!</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://accounts.google.com/gsi/client" async defer></script>
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
.g_id_signin {
      display: flex;
      justify-content: center;
      margin-top: 1rem;
    }
    .g_id_signin iframe {
      border-radius: 8px !important;
      box-shadow: 0 2px 6px rgba(223, 202, 202, 0.1);
    }
</style>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

    <div class="card shadow p-4" style="width: 400px;">
        <h3 class="text-center mb-3">Login</h3>
        <form method="POST" action="login.php">
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control" required>
                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                        Show
                    </button>
                </div>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">
                    Remember Me
                </label>
            </div>

            <button type="submit" class="btn btn-outline-secondary w-100">Login</button>
        </form>
        <div class="text-center mt-3">
            <small>Don’t have an account? <a href="signup.php">Sign up here</a></small>
        </div>
         <div id="g_id_onload"
            data-client_id="50888728505-9f9hqe4s9a4nbgj1o94bqth99sd3ll4l.apps.googleusercontent.com"
            data-login_uri="http://localhost/appdev/login.php"
            data-auto_prompt="false">
        </div>  

        <div class="g_id_signin"
             data-type="standard"
             data-shape="rectangular"
             data-theme="outline"
             data-text="signin_with"
             data-size="large"
             data-logo_alignment="left">
</div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        const togglePassword = document.querySelector("#togglePassword");
        const password = document.querySelector("#password");

        togglePassword.addEventListener("click", function () {
            // Toggle password visibility
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);

            // Toggle button text
            this.textContent = type === "password" ? "Show" : "Hide";
        });
    </script>

</body>
</html>