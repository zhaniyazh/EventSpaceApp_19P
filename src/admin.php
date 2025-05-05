<?php
// connect to the database
$conn = new mysqli("localhost", "root", "", "eventspace_db");
if ($conn->connect_error) {
    die("DB connection error: " . $conn->connect_error);
}

// delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM bookings WHERE id=$id");
    header("Location: admin.php");
    exit();
}

// edit
if (isset($_POST['edit'])) {
    $id = (int)$_POST['id'];
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $room = $conn->real_escape_string($_POST['room']);
    $date = $conn->real_escape_string($_POST['date']);
    $start = $conn->real_escape_string($_POST['start_time']);
    $end = $conn->real_escape_string($_POST['end_time']);
    $status = $conn->real_escape_string($_POST['status']);

    $conn->query("UPDATE bookings SET name='$name', email='$email', room='$room', date='$date', start_time='$start', end_time='$end', status='$status' WHERE id=$id");
    header("Location: admin.php");
    exit();
}

// email
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'mailer/PHPMailer.php';
require 'mailer/SMTP.php';
require 'mailer/Exception.php';

if (isset($_GET['mail'])) {
    $id = (int)$_GET['mail'];
    $res = $conn->query("SELECT * FROM bookings WHERE id=$id");
    if ($res->num_rows > 0) {
        $b = $res->fetch_assoc();

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'zhaniyazhaksylyk@gmail.com'; // my gmail (sender)
            $mail->Password   = 'tjbsuvqsznvsueph';           // app password
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            // recipients
            $mail->setFrom('zhaniyazhaksylyk@gmail.com', 'EventSpace');
            $mail->addAddress($b['email'], $b['name']);

            $mail->isHTML(false); // set to true for HTML emails
            $mail->Subject = 'Booking Confirmation';
            $mail->Body    = "Hi " . $b['name'] . ",\n\nYour booking for " . $b['room'] . " on " . $b['date'] .
                             " from " . $b['start_time'] . " to " . $b['end_time'] . " is " . strtoupper($b['status']) . ".\n\nThank you!";

            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
    header("Location: admin.php");
    exit();
}

$result = $conn->query("SELECT * FROM bookings ORDER BY date, start_time");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #fafafa;
      padding: 40px;
    }

    .admin-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
      padding: 10px 20px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }

    .admin-header img {
      height: 60px;
    }

    .logout-btn {
      background: linear-gradient(90deg, #8A226F, #4A00E0);
      color: white;
      padding: 8px 16px;
      border-radius: 6px;
      font-weight: 600;
      text-decoration: none;
      transition: 0.3s ease;
    }

    .logout-btn:hover {
      background:linear-gradient(90deg, #8A226F, #4A00E0);
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
      border-radius: 10px;
      overflow: hidden;
    }
    th, td {
      padding: 12px;
      text-align: center;
      border-bottom: 1px solid #eee;
    }
    th {
      background: #8A226F;
      color: white;
    }
    form input, form select {
      width: 90%;
      padding: 6px;
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    .btn {
      padding: 6px 10px;
      border: none;
      border-radius: 6px;
      font-size: 13px;
      cursor: pointer;
      margin: 2px;
    }
    .edit-btn { background: #ffc107; color: black; }
    .delete-btn { background: #e53935; color: white; }
    .email-btn { background: #4A00E0; color: white; }
  </style>
</head>
<body>

  <div class="admin-header">
    <img src="assets/icons/logo.png" alt="EventSpace Logo">
    <h2>EventSpace Admin Panel</h2>
    <a href="index.php" class="logout-btn">Log out</a>
  </div>

<table>
  <tr>
    <th>Name</th>
    <th>Email</th>
    <th>Room</th>
    <th>Date</th>
    <th>Start</th>
    <th>End</th>
    <th>Status</th>
    <th>Actions</th>
  </tr>

  <?php while ($row = $result->fetch_assoc()): ?>
  <tr>
    <form method="POST">
      <input type="hidden" name="id" value="<?= $row['id'] ?>">
      <td><input name="name" value="<?= htmlspecialchars($row['name']) ?>"></td>
      <td><input name="email" value="<?= htmlspecialchars($row['email']) ?>"></td>
      <td><input name="room" value="<?= htmlspecialchars($row['room']) ?>"></td>
      <td><input type="date" name="date" value="<?= $row['date'] ?>"></td>
      <td><input name="start_time" value="<?= $row['start_time'] ?>"></td>
      <td><input name="end_time" value="<?= $row['end_time'] ?>"></td>
      <td>
        <select name="status">
          <option value="confirmed" <?= $row['status'] == 'confirmed' ? 'selected' : '' ?>>confirmed</option>
          <option value="pending" <?= $row['status'] == 'pending' ? 'selected' : '' ?>>pending</option>
          <option value="cancelled" <?= $row['status'] == 'cancelled' ? 'selected' : '' ?>>cancelled</option>
        </select>
      </td>
      <td>
        <button class="btn edit-btn" type="submit" name="edit">Save</button>
        <a class="btn delete-btn" href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this booking?')">Delete</a>
        <a class="btn email-btn" href="?mail=<?= $row['id'] ?>">Email</a>
      </td>
    </form>
  </tr>
  <?php endwhile; ?>

</table>

</body>
</html>
