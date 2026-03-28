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
</head>
<style>
body {
    background: linear-gradient(135deg, #5c7069, #25302b); /* soft gray gradient */
    min-height: 100vh;
}
.navbar-gradient {
    background: linear-gradient(135deg, #285A48, #091413); /* blue → purple */
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
.card-title {
    font-weight: 600;
    color: #050608; /* Bootstrap primary blue */
}
.card-text {
    color: #7c8994; /* muted gray for readability */
}
.table-modern {
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 6px 18px rgba(0,0,0,0.1);
    background-color: #fff;
}

.table-modern thead {
    background: linear-gradient(135deg, #0d6efd, #6610f2); /* gradient header */
    color: #fff;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-radius: 8px;
}

.table-modern tbody tr {
    transition: background-color 0.2s ease;
}

.table-modern tbody tr:hover {
    
    background-color: #f1f5ff; /* subtle hover highlight */
}

.table-modern td, 
.table-modern th {
    padding: 14px 18px;
    vertical-align: middle;
}
.btn-modern {
    border-radius: 50px; /* pill shape */
    padding: 6px 14px;
    font-size: 0.85rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-edit {
    background: linear-gradient(135deg, #ffc107, #ffcd39); /* warm yellow gradient */
    color: #212529;
    border: none;
    
    
}
.btn-edit:hover {
    background: linear-gradient(135deg, #ffcd39, #ffc107);
    transform: translateY(-2px);
}

.btn-delete {
    background: linear-gradient(135deg, #dc3545, #e4606d); /* red gradient */
    color: #fff;
    border: none;
}
.btn-delete:hover {
    background: linear-gradient(135deg, #e4606d, #dc3545);
    transform: translateY(-2px);
}
.pop-message {
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
.fade-out {
    animation: fadeOutUp 1s ease;
}

@keyframes fadeOutUp {
    from { opacity: 1; transform: translate(-50%, 0); }
    to   { opacity: 0; transform: translate(-50%, -20px); }
}
/* optional fade-in animation */
@keyframes fadeInDown {
    from { opacity: 0; transform: translate(-50%, -20px); }
    to   { opacity: 1; transform: translate(-50%, 0); }
}
.btn:hover {
    transform: scale(1.02);
}

/* Navbar */
.navbar {
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.navbar-brand {
    font-weight: bold;
    letter-spacing: 1px;
}

/* Alerts */
.alert {
    border-radius: 8px;
    font-size: 0.95rem;
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

/* Dashboard quick links */
.list-group-item a {
    text-decoration: none;
    color: #0d6efd;
    transition: color 0.2s ease;
}

.list-group-item a:hover {
    color: #084298;
    font-weight: 500;
}
</style>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-gradient">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">Dashboard</a>
            <div class="d-flex">
                <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5">
        <div class="row">
            <!-- Welcome Card -->
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="card-title">Welcome, <?php echo htmlspecialchars($email); ?>!</h3>
                        <p class="card-text">This is your dashboard. From here you can manage your account, view updates, or navigate to other sections.</p>
                       
                    </div>
                </div>
            </div>

    <div class="row mt-4">
  <div class="col-md-4">
    <div class="card text-center shadow-sm">
      <div class="card-body">
        <i class="bi bi-person-circle fs-1 text-primary"></i>
        <h5 class="card-title mt-2">Profile</h5>
        <p class="card-text">View and edit your personal information.</p>
        <a href="#" class="btn btn-outline-primary btn-sm">Go to Profile</a>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card text-center shadow-sm">
      <div class="card-body">
        <i class="bi bi-gear fs-1 text-success"></i>
        <h5 class="card-title mt-2">Settings</h5>
        <p class="card-text">Manage your account preferences.</p>
        <a href="#" class="btn btn-outline-success btn-sm">Go to Settings</a>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card text-center shadow-sm">
      <div class="card-body">
        <i class="bi bi-envelope fs-1 text-warning"></i>
        <h5 class="card-title mt-2">Messages</h5>
        <p class="card-text">Check your latest updates.</p>
        <a href="#" class="btn btn-outline-warning btn-sm">View Messages</a>
      </div>
    </div>
  </div>
</div>


<div class="container mt-5">
    <h3 class="mb-4">Registered Users</h3>
    <table class="table table-modern table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['firstname']); ?></td>
                        <td><?php echo htmlspecialchars($row['lastname']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger"
                               onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center">No users found</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if (isset($_GET['updated'])): ?>
    <div id="popMessage" class="alert alert-success text-center pop-message">
        User updated successfully!
    </div>
<?php endif; ?>
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