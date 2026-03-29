<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "appdev";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Run query safely
$sql = "SELECT id, firstname, lastname, email FROM users";
$result = $conn->query($sql);

if ($result === false) {
    die("Query error: " . $conn->error);
}


$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Dashboard</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    body {
      background: linear-gradient(135deg, #5c7069, #25302b);
      min-height: 100vh;
    }
    .sidebar {
      height: 100vh;
      background: linear-gradient(135deg, #285A48, #091413);
      color: #fff;
      padding-top: 20px;
      position: fixed;
      width: 220px;
    }
    .sidebar a {
      color: #fff;
      text-decoration: none;
      display: block;
      padding: 12px 20px;
      transition: background 0.2s ease;
    }
    .sidebar a:hover {
      background-color: rgba(255,255,255,0.1);
      border-radius: 6px;
    }
    .content {
      margin-left: 240px; /* space for sidebar */
      padding: 20px;
    }
    .card {
      border-radius: 12px;
      box-shadow: 0 6px 15px rgba(0,0,0,0.1);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    .table-modern {
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 6px 18px rgba(0,0,0,0.1);
      background-color: #fff;
    }
    .table-modern thead {
      background: linear-gradient(135deg, #0d6efd, #6610f2);
      color: #fff;
      font-weight: 600;
      text-transform: uppercase;
    }
    .btn-edit {
      background: linear-gradient(135deg, #ffc107, #ffcd39);
      color: #212529;
      border: none;
    }
    .btn-delete {
      background: linear-gradient(135deg, #dc3545, #e4606d);
      color: #fff;
      border: none;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h4 class="text-center mb-4">Dashboard</h4>
    <a href="#"><i class="bi bi-house-door"></i> Home</a>
    <a href="#"><i class="bi bi-people"></i> Users</a>
    <a href="#"><i class="bi bi-person-circle"></i> Profile</a>
    <a href="#"><i class="bi bi-gear"></i> Settings</a>
    <a href="#"><i class="bi bi-envelope"></i> Messages</a>
    <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
  </div>

  <!-- Main Content -->
  <div class="content">
    <div class="container-fluid">
      <!-- Welcome Card -->
      <div class="card mb-4">
        <div class="card-body">
          <h3 class="card-title">Welcome, <?php echo htmlspecialchars($email); ?>!</h3>
          <p class="card-text">This is your dashboard. Use the sidebar to navigate.</p>
        </div>
      </div>

      <!-- Users Table -->
      <h3 class="mb-4">Registered Users</h3>
      <table class="table table-modern table-hover">
        <thead>
          <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
              <tr>

                <td><?php echo htmlspecialchars($row['firstname']); ?></td>
                <td><?php echo htmlspecialchars($row['lastname']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td>
                  <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-edit">Edit</a>
                  <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-delete"
                     onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="5" class="text-center">No users found</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>