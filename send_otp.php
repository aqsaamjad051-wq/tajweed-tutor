<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 📦 PHPMailer autoload (Composer-based)
require __DIR__ . '/vendor/autoload.php';

// ✅ Create DB connection
$conn = new mysqli("localhost", "root", "", "tajweed_db");
if ($conn->connect_error) {
    echo json_encode(["message" => "❌ DB connection failed: " . $conn->connect_error]);
    exit;
}

// 📨 Get email from frontend (JSON)
$data = json_decode(file_get_contents("php://input"), true);
$email = strtolower(trim($conn->real_escape_string($data['email'])));

// 🧪 Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["message" => "📛 Invalid email format"]);
    exit;
}

// 🔍 Check if user exists
$res = $conn->query("SELECT * FROM users WHERE email='$email'");
if ($res->num_rows === 0) {
    echo json_encode(["message" => "❌ Email not registered"]);
    exit;
}

// 🔐 Generate OTP
$otp = rand(100000, 999999);
$expiry = date("Y-m-d H:i:s", strtotime("+5 minutes"));

// 💾 Save OTP in database
$update = $conn->query("UPDATE users SET otp_code='$otp', otp_expiry='$expiry' WHERE email='$email'");
if (!$update) {
    echo json_encode(["message" => "❌ OTP not saved: " . $conn->error]);
    exit;
}

// 📧 Send OTP via Gmail SMTP
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'aqsaamjad051@gmail.com';         // 👈 Your Gmail
    $mail->Password = 'omdy mebk ncem lgli';         // 👈 App password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('aqsaamjad051@gmail.com', 'Tajweed Tutor');
    $mail->addAddress($email);
    $mail->Subject = '🔐 Your OTP for Tajweed Tutor';
    $mail->Body = "Your OTP is: $otp\n\nIt is valid for 5 minutes only.";

    $mail->send();
    echo json_encode(["message" => "📩 OTP sent successfully!"]);
} catch (Exception $e) {
    echo json_encode(["message" => "❌ Email sending failed: " . $mail->ErrorInfo]);
}
?>
