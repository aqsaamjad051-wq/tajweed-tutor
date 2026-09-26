<?php
session_start();

// Basic access control (optional)
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$host = "localhost";
$user = "root";
$pass = "";
$db   = "tajweed_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Database failed: " . $conn->connect_error);
}

// Fetch all registered users
$sql_users = "SELECT id, username, email, phone FROM users";
$result_users = $conn->query($sql_users);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel - Tajweed Tutor</title>
  <style>
    body { font-family: sans-serif; background: #f9fdf9; padding: 20px; }
    h1 { text-align: center; color: #007B5E; }
    table { width: 90%; margin: auto; border-collapse: collapse; margin-top: 30px; }
    th, td { border: 1px solid #ccc; padding: 12px; text-align: center; }
    th { background-color: #007B5E; color: white; }
    tr:nth-child(even) { background-color: #eafff1; }
    .section { max-width: 1000px; margin: 40px auto; }
    a.btn { display: inline-block; padding: 8px 14px; background: #007B5E; color: white; text-decoration: none; border-radius: 6px; margin-top: 10px; }
  </style>
</head>
<body>

<h1>🛠️ Admin Dashboard – Tajweed Tutor</h1>

<div class="section">
  <h2>👤 Registered Users</h2>
  <table>
    <thead>
      <tr><th>ID</th><th>Username</th><th>Email</th><th>Phone</th></tr>
    </thead>
    <tbody>
      <?php
      if ($result_users->num_rows > 0) {
          while ($row = $result_users->fetch_assoc()) {
              echo "<tr>
                      <td>{$row['id']}</td>
                      <td>{$row['username']}</td>
                      <td>{$row['email']}</td>
                      <td>{$row['phone']}</td>
                    </tr>";
          }
      } else {
          echo "<tr><td colspan='4'>No users found.</td></tr>";
      }
      ?>
    </tbody>
  </table>
</div>

<div class="section">
  <h2>📚 Manage Lessons (Placeholder)</h2>
  <p>Add, edit, or remove lessons from the curriculum — coming soon!</p>
<a href="add_lesson.php" class="btn">➕ Add Lesson</a>
</div>

<div class="section" style="text-align:center;">
  <a href="logout.php" class="btn">🔐 Logout</a>
</div>
</body>
</html>
