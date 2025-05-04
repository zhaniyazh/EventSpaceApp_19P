<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error_message = 'Please fill in both fields.';
    } elseif ($email === 'zhaniyazhaksylyk@gmail.com' && $password === 'EventSpace12345') {
        // Generate 6-digit verification code
        $code = rand(100000, 999999);
        $_SESSION['code'] = $code;
        $_SESSION['temp_user'] = [ 'email' => $email ];
        $_SESSION['show_verification'] = true;  

        // Send email with code
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'zhaniyazhaksylyk@gmail.com';
            $mail->Password = 'tjbsuvqsznvsueph';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('zhaniyazhaksylyk@gmail.com', 'EventSpace Admin');
            $mail->addAddress($email);
            $mail->Subject = 'EventSpace Login Code';
            $mail->Body = "Your login verification code is: $code";

            $mail->send();
        } catch (Exception $e) {
            $error_message = 'Email failed: ' . $mail->ErrorInfo;
        }
    } else {
        $error_message = 'Invalid email or password.';
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Panel | EventSpace</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <style>
body {
  margin: 0;
  padding: 0;
  height: 100vh;
  background: #fafafa;
  font-family: 'Poppins', sans-serif;
  display: flex;
  justify-content: center;
  align-items: center;
}

.register-box {
  background: white;
  padding: 40px 30px;
  border-radius: 16px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
  max-width: 400px;
  width: 100%;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.register-box input {
  width: 80%;
  max-width: 320px;
  padding: 12px 14px;
  margin-bottom: 16px;
  border-radius: 8px;
  border: 1px solid #ccc;
  font-size: 14px;
  background-color: #ffeefe;
}

.register-box button {
  width: 80%;
  max-width: 320px;
  padding: 12px;
  border: none;
  border-radius: 30px;
  background: linear-gradient(to right, #8A226F, #4A00E0);
  color: white;
  font-size: 16px;
  font-weight: bold;
  cursor: pointer;
  box-shadow: 0 6px 20px rgba(138, 34, 111, 0.2);
  transition: 0.3s ease;
}

.register-box button:hover {
  opacity: 0.95;
}

.register-box img {
  width: 100px;
  margin-bottom: 20px;
}

.error-message {
      color: #E53935;
      background-color: #ffeaea;
      padding: 10px 15px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-weight: 500;
      text-align: center;
    }
  </style>
</head>
<body>

  <div class="register-box">
    <a href="index.php">
      <img src="assets/icons/logo.png" alt="EventSpace Logo">
    </a>

    <!-- Optional: dynamic error message -->
    <?php if (!empty($error_message)): ?>
      <div class="error-message"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <h2>Admin Panel</h2>
      <input type="email" name="email" placeholder="Enter admin email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
  </div>

  <?php if (isset($_SESSION['show_verification']) && $_SESSION['show_verification']): ?>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    Swal.fire({
      title: 'Verification Sent!',
      text: 'A code was sent to your email.',
      input: 'text',
      inputPlaceholder: 'Enter code',
      confirmButtonText: 'Verify',
      showCancelButton: false,
      preConfirm: (input) => {
        return new Promise((resolve, reject) => {
          if (input === "<?php echo $_SESSION['code']; ?>") {
            resolve();
          } else {
            reject('Incorrect code');
          }
        });
      }
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = 'admin.php';
      }
    });
  </script>
  <?php unset($_SESSION['show_verification'], $_SESSION['code']); ?>
<?php endif; ?>

</body>
</html>