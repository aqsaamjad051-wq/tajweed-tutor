<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
  header("Location: login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add New Lesson</title>
  <style>
    body { font-family: Arial; background: #f0fff4; padding: 30px; }
    h1 { color: #007B5E; text-align: center; }
    form { max-width: 500px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px #ccc; }
    input, textarea { width: 100%; padding: 10px; margin-top: 10px; border-radius: 5px; border: 1px solid #aaa; }
    button { background: #007B5E; color: white; padding: 10px 20px; border: none; border-radius: 6px; margin-top: 15px; cursor: pointer; }
  </style>
</head>
<body>
  <h1>📘 Add New Lesson</h1>
  <form method="POST" action="save_lesson.php">
    <label>Lesson Title:</label>
    <input type="text" name="title" required />

    <label>Lesson Description:</label>
    <textarea name="description" rows="5" required></textarea>

    <button type="submit">➕ Save Lesson</button>
  </form>
</body>
</html>
