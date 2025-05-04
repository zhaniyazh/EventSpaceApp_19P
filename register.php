<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<?php
require_once 'includes/auth.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    registerUser($_POST['email'], $_POST['username'], $_POST['password'], $_POST['confirm_password']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register | EventSpace</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/register.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="register-page">

<div class="container">
  <div class="left-side">
    <a href="index.php">
      <img src="assets/icons/logo.png" alt="EventSpace Logo" class="logo-register">
    </a>
    <h1>Sign Up to</h1>
    <p class="subheadline">Create your profile and<br>launch your next gathering</p>
    <p class="login-text">
      Already have an account?<br>
      <a href="login.php">Login here!</a><br>
      or<br>
      <a href="forgot_password.php">Forgot Password?</a>
    </p>
    <div class="rooms">
      <img src="assets/icons/home1.webp" alt="Room 1" class="room-image">
      <img src="assets/icons/home2.webp" alt="Room 2" class="room-image">
      <img src="assets/icons/room5.webp" alt="Room 3" class="room-image">
    </div>
  </div>

  <div class="right-side">
    <form method="POST" class="form-box" id="registerForm" action="register.php">
      <h2>Sign Up</h2>
      <input type="email" name="email" placeholder="Enter Email" required>
      <input type="text" name="username" placeholder="Create Username" required>
      <input type="password" name="password" placeholder="Password (min 8 characters)" required>
      <input type="password" name="confirm_password" placeholder="Confirm Password" required>
      <button type="submit" class="btn-gradient">Register</button>
    </form>
  </div>
</div>

<script>
// Frontend password basic validation
document.getElementById('registerForm').addEventListener('submit', function(event) {
  var password = document.querySelector('input[name="password"]').value;
  var confirm_password = document.querySelector('input[name="confirm_password"]').value;

  if (password.length < 8) {
    event.preventDefault();
    Swal.fire({
      icon: 'error',
      title: 'Weak Password',
      text: 'Password must be at least 8 characters long.',
      confirmButtonColor: '#8E2DE2'
    });
    return false;
  }

  if (password !== confirm_password) {
    event.preventDefault();
    Swal.fire({
      icon: 'error',
      title: 'Password Mismatch',
      text: 'Passwords do not match.',
      confirmButtonColor: '#8E2DE2'
    });
    return false;
  }
});
</script>

</body>
</html>
