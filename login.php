<?php
session_start();
include 'database.php';

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

function showError($message) {
  echo '
  <html>
  <head>
    <meta charset="UTF-8">
    <title>Login Error - Tajweed Tutor</title>
    <style>
      body {
        background-color: #fff6f6;
        font-family: Arial, sans-serif;
      }

      .popup {
        background-color: #fff;
        border: 2px solid #cc0000;
        border-radius: 10px;
        padding: 25px;
        max-width: 420px;
        margin: 100px auto;
        text-align: center;
        color: #cc0000;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
      }

      .popup button {
        background-color: #cc0000;
        color: white;
        border: none;
        padding: 10px 20px;
        font-weight: bold;
        border-radius: 6px;
        cursor: pointer;
        margin-top: 20px;
      }
    </style>
  </head>

  <body>
    <div class="popup">
      <h2>⚠️ Login Failed</h2>
      <p>' . htmlspecialchars($message) . '</p>
      <button onclick="window.location.href=\'login.html\'">Try Again</button>
    </div>
  </body>
  </html>';

  exit;
}

// Validate input
if ($username === '' || $password === '') {
  showError("Please enter both username and password.");
}

// Find user
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 1) {

  $row = $result->fetch_assoc();

  /*
   * First try secure hashed password
   */
  if (password_verify($password, $row['password'])) {

    $_SESSION['user'] = $username;

    header("Location: chatbot.php");
    exit;
  }

  /*
   * If old user has plain-text password,
   * allow login and convert it to a secure hash.
   */
  if ($password === $row['password']) {

    $new_hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $update = $conn->prepare(
      "UPDATE users SET password = ? WHERE id = ?"
    );

    $update->bind_param(
      "si",
      $new_hashed_password,
      $row['id']
    );

    $update->execute();
    $update->close();

    $_SESSION['user'] = $username;

    header("Location: chatbot.php");
    exit;
  }

  /*
   * Also support users whose old password
   * is stored in plain_password column.
   */
  if (
    isset($row['plain_password']) &&
    $password === $row['plain_password']
  ) {

    $new_hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $update = $conn->prepare(
      "UPDATE users SET password = ? WHERE id = ?"
    );

    $update->bind_param(
      "si",
      $new_hashed_password,
      $row['id']
    );

    $update->execute();
    $update->close();

    $_SESSION['user'] = $username;

    header("Location: chatbot.php");
    exit;
  }

  showError("Incorrect password. Please try again.");

} else {

  showError("No user found with that username.");
}

$stmt->close();
$conn->close();
?>