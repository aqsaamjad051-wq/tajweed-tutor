<?php
include 'database.php';

$username       = trim($_POST['username'] ?? '');
$email          = trim($_POST['email'] ?? '');
$phone          = trim($_POST['phone'] ?? '');
$plain_password = trim($_POST['password'] ?? '');

// Server-side validation
if ($username === '' || $email === '' || $phone === '' || $plain_password === '') {
  header("Location: register.html"); // optional: or show styled error
  exit;
}
if (strlen($username) < 2 || strlen($username) > 12 ||
    !filter_var($email, FILTER_VALIDATE_EMAIL) ||
    !preg_match('/^[0-9]{10,14}$/', $phone) ||
    strlen($plain_password) < 6) {
  header("Location: register.html");
  exit;
}

// Hash password
$hashed_password = password_hash($plain_password, PASSWORD_BCRYPT);

try {
  $stmt = $conn->prepare("INSERT INTO users (username, email, phone, password, plain_password) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssss", $username, $email, $phone, $hashed_password, $plain_password);
  $stmt->execute();

  // 🎉 Styled Welcome Screen
  echo '
  <html>
  <head>
    <style>
      body {
        background-color: #f0fff4;
        font-family: Arial, sans-serif;
      }
      .popup {
        background-color: #ffffff;
        border: 2px solid #007B5E;
        border-radius: 10px;
        padding: 25px;
        max-width: 420px;
        margin: 100px auto;
        text-align: center;
        box-shadow: 0 0 20px rgba(0,0,0,0.2);
        animation: fadeIn 0.7s ease;
      }
      .popup h2 {
        color: #007B5E;
      }
      .popup button {
        background-color: #007B5E;
        color: white;
        border: none;
        padding: 10px 20px;
        font-weight: bold;
        border-radius: 6px;
        cursor: pointer;
      }
      @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-30px); }
        to { opacity: 1; transform: translateY(0); }
      }
    </style>
  </head>
  <body>
    <div class="popup">
      <h2>🎉 Welcome to Tajweed Tutor Chatbot!</h2>
      <p>We’re so happy you joined. May your journey be full of barakah 🌙</p>
      <button onclick="location.href=\'login.html\'">Proceed to Login</button>
    </div>
  </body>
  </html>';
} catch (mysqli_sql_exception $e) {
  // 💔 Styled Error Screen
  $errorMessage = $e->getCode() == 1062 ? "This username or email already exists. Try something else!" : "Something went wrong. Please try again later.";
  echo '
  <html>
  <head>
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
        animation: fadeIn 0.7s ease;
      }
      .popup button {
        background-color: #cc0000;
        color: white;
        border: none;
        padding: 10px 20px;
        font-weight: bold;
        border-radius: 6px;
        cursor: pointer;
      }
      @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-30px); }
        to { opacity: 1; transform: translateY(0); }
      }
    </style>
  </head>
  <body>
    <div class="popup">
      <h2>❌ Registration Failed</h2>
      <p>' . $errorMessage . '</p>
      <button onclick="location.href=\'register.html\'">Try Again</button>
    </div>
  </body>
  </html>';
}

$conn->close();
?>
