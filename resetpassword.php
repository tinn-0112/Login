<?php
session_start();
require_once(__DIR__ . '/vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$conn = new mysqli("localhost", "root", "", "appdev");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST['token'];
    $newPassword = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, email, reset_expires FROM users WHERE reset_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (strtotime($row['reset_expires']) > time()) {
    $hashed = password_hash($newPassword, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
    $stmt->bind_param("si", $hashed, $row['id']);
    if ($stmt->execute()) {
        header("Location: login.php?reset=success");
        exit();
    } else {
        die("Update failed: " . $stmt->error);
    }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
    <div class="p-4 bg-white shadow rounded" style="width: 350px;">
        <h4 class="mb-3">Reset Password</h4>
        <?php if (!empty($message)): ?>
            <div class="alert alert-info fixed message"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST" action="resetpassword.php">
    <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>">
    <div class="mb-3">
        <label class="form-label">New Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">Reset Password</button>
</form>
    </div>
</body>
</html>