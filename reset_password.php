<?php
$conn = new mysqli("localhost", "root", "", "tajweed_db");
if ($conn->connect_error) {
    echo json_encode(["message" => "❌ DB connection failed: " . $conn->connect_error]);
    exit;
}

// 📩 Get data from frontend
$data = json_decode(file_get_contents("php://input"), true);
$email = strtolower(trim($conn->real_escape_string($data['email'])));
$otp = $conn->real_escape_string($data['otp']);
$newPass = $conn->real_escape_string($data['newPass']);
$hashed = password_hash($newPass, PASSWORD_DEFAULT);

// 🧪 Basic input validation
if (!$email || !$otp || !$newPass) {
    echo json_encode(["message" => "⚠️ Missing required fields"]);
    exit;
}

// 🔍 Find user + check OTP
$res = $conn->query("SELECT otp_code, otp_expiry FROM users WHERE email='$email'");
if ($res->num_rows === 0) {
    echo json_encode(["message" => "❌ Email not found"]);
    exit;
}

$row = $res->fetch_assoc();

// ✅ OTP matching check
if ($row['otp_code'] != $otp) {
    echo json_encode(["message" => "❌ Incorrect OTP"]);
    exit;
}

// ⏰ Expiry check
if (strtotime($row['otp_expiry']) < time()) {
    echo json_encode(["message" => "⏳ OTP has expired"]);
    exit;
}

// 💾 Reset password in DB
$update = $conn->query("UPDATE users SET password='$hashed', plain_password='$newPass', otp_code=NULL, otp_expiry=NULL WHERE email='$email'");
if ($update) {
    echo json_encode(["message" => "✅ Password reset successful"]);
} else {
    echo json_encode(["message" => "❌ Password reset failed: " . $conn->error]);
}
?>
