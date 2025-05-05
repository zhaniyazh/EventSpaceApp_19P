<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db.php';
require_once 'functions.php';

function registerUser($email, $username, $password, $confirm_password) {
    global $conn;

    $email = $conn->real_escape_string(trim($email));
    $username = $conn->real_escape_string(trim($username));
    $password = trim($password);
    $confirm_password = trim($confirm_password);

    // 1. password basic validation
    if (strlen($password) < 8) {
        showError('Password must be at least 8 characters long.');
    }

    if ($password !== $confirm_password) {
        showError('Passwords do not match.');
    }

    // 2. check if email already exists
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    if (!$stmt) {
        showError('Database prepare error: ' . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();  // required for num_rows to work correctly

    if ($stmt->num_rows > 0) {
        $stmt->close();
        echo "DEBUG: Email exists!<br>";  // test if the request is executed
        showError('This email is already registered. Please login through your email.');
    }
    $stmt->close();

    // 3. insert new user
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $insert_stmt = $conn->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
    if (!$insert_stmt) {
        showError('Database prepare error: ' . $conn->error);
    }
    $insert_stmt->bind_param("sss", $email, $username, $password_hash);

    if ($insert_stmt->execute()) {
        // send welcome email
        sendWelcomeEmail($email, $username);

        showSuccess('Registered successfully! Redirecting to login page...');
    } else {
        showError('Registration failed: ' . $insert_stmt->error);
    } 
    $insert_stmt->close();
}
