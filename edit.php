<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "appdev";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die("No user ID provided.");
}

// Fetch current user data
$sql = "SELECT * FROM users WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname  = $_POST['firstname'];
    $lastname   = $_POST['lastname'];
    $email      = $_POST['email'];

    // Correct: 4 placeholders, 4 variables
    // Update user
$stmt = $conn->prepare("UPDATE users SET firstname=?, lastname=?, email=? WHERE id=?");
$stmt->bind_param("sssi", $firstname, $lastname, $email, $id);

if ($stmt->execute()) {
    $message = "<div class='alert alert-success text-center'>✅ User updated successfully!</div>";

    // Refresh user data
    $stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    header("Location: dashboard.php?updated=1");
    exit();
} else {
    $message = "<div class='alert alert-danger text-center'>❌ Error updating record: " . $stmt->error . "</div>";
}
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
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
/* Form inputs */
.form-control {
    border-radius: 8px;
    padding: 10px;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, .25);
}
</style>
<body>

<div class="container mt-5">
    <?php echo $message; ?> <!-- Echo success/error message -->

    <div class="card shadow p-4 mx-auto" style="width: 500px;">
        <h3 class="text-center mb-3">Edit User</h3>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">First Name</label>
                <input type="text" name="firstname" class="form-control"
                       value="<?php echo htmlspecialchars($user['firstname']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Last Name</label>
                <input type="text" name="lastname" class="form-control"
                       value="<?php echo htmlspecialchars($user['lastname']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control"
                       value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <button type="submit" class="btn btn-outline-secondary w-100">Save Changes</button>
            <a href="dashboard.php" class="btn btn-outline-secondary w-100 mt-2">Cancel</a>
        </form>
    </div>
</div>

</body>
</html>
