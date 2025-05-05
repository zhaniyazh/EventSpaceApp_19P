<?php
session_start();
require_once 'includes/db.php'; // DB connection file

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') { //only runs when the login form is submitted
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error_message = 'Please fill in both fields.';
    } else {
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

         // if a user was found with that email
        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                // success login
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                header("Location: book.php"); // redirect to next page
                exit();
            } else {
                // password wrong
                $error_message = 'Incorrect password. Please try again.';
            }
        } else {
            // email not found
            $error_message = 'No account found with this email.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | EventSpace</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/register.css"> <!-- Using same style -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="register-page">

  <div class="container">
    <!-- left side of the page -->
    <div class="left-side">
      <a href="index.php">
        <img src="assets/icons/logo.png" alt="EventSpace Logo" class="logo-register">
      </a>
      <h1>Let's get started</h1>
      <p class="subheadline">Manage your events, spaces, and<br>bookings — all in one place.</p>
      <p class="login-text">
        Don't have an account?<br>
        <a href="register.php">Register here!</a>
      </p>

      <div class="rooms">
        <img src="assets/icons/home1.webp" alt="Room 1" class="room-image">
        <img src="assets/icons/home2.webp" alt="Room 2" class="room-image">
        <img src="assets/icons/room5.webp" alt="Room 3" class="room-image">
      </div>
    </div>

    <!-- right side -->
    <div class="right-side">
      <?php if (!empty($error_message)): ?>
        <div class="error-message"><?php echo $error_message; ?></div>
      <?php endif; ?>

      <form action="login.php" method="POST" class="form-box">
        <h2>Sign In</h2>
        <input type="email" name="email" placeholder="Enter your email" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit" class="btn-gradient">Login</button>
      </form>
    </div>
  </div>

</body>
</html>

