<?php
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Logging Out...</title>
  <style>
    body {
      background-color: #f0fff4;
      font-family: Arial, sans-serif;
      text-align: center;
      padding: 100px 20px;
    }
    .goodbye {
      background: white;
      display: inline-block;
      padding: 30px;
      border: 2px solid #007B5E;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
      animation: fadeIn 0.6s ease;
    }
    h2 {
      color: #007B5E;
    }
    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(-20px);}
      to {opacity: 1; transform: translateY(0);}
    }
  </style>
  <meta http-equiv="refresh" content="2;url=login.html" />
</head>
<body>
  <div class="goodbye">
    <h2>👋 You're safely logged out.</h2>
    <p>Redirecting to login screen...</p>
  </div>
</body>
</html>
