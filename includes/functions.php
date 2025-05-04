<?php

function showError($message) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            html: '$message',
            confirmButtonColor: '#8E2DE2'
        }).then(() => {
             window.history.back(); // Redirect to login page
        });
    </script>";
    exit();
}

function showSuccess($message) {
    echo "
    <html>
    <head>
      <meta charset='UTF-8'>
      <title>Success | EventSpace</title>
      <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap' rel='stylesheet'>
      <style>
        body {
          font-family: 'Poppins', sans-serif;
          background: #f7f7f7;
          margin: 0;
          padding: 0;
          display: flex;
          align-items: center;
          justify-content: center;
          height: 100vh;
        }
        .success-box {
          background: white;
          padding: 40px;
          border-radius: 16px;
          box-shadow: 0 6px 24px rgba(0,0,0,0.1);
          text-align: center;
        }
        .success-box img {
          height: 50px;
          margin-bottom: 20px;
        }
        .success-box h1 {
          font-size: 24px;
          font-weight: 700;
          margin-bottom: 16px;
          color: #333;
        }
        .success-box p {
          font-size: 18px;
          color: #555;
          margin-bottom: 30px;
        }
        .success-box button {
          background: linear-gradient(to right, #8E2DE2, #4A00E0);
          color: white;
          padding: 12px 30px;
          border: none;
          border-radius: 8px;
          font-size: 16px;
          font-weight: 600;
          cursor: pointer;
          box-shadow: 0 4px 14px rgba(142,45,226,0.3);
          transition: 0.3s;
        }
        .success-box button:hover {
          background: #731fd7;
        }
      </style>
      <meta http-equiv='refresh' content='4;url=login.php'>
    </head>
    <body>
      <div class='success-box'>
        <img src='assets/icons/logo.png' alt='EventSpace Logo'>
        <h1>You're all set!</h1>
        <p>Thanks for joining EventSpace.<br>Redirecting to Login...</p>
        <button onclick=\"window.location.href='login.php'\">Login Now</button>
      </div>
    </body>
    </html>";
    exit();
}



function sendWelcomeEmail($email, $username) {
    require_once __DIR__ . '/../src/PHPMailer.php';
    require_once __DIR__ . '/../src/SMTP.php';
    require_once __DIR__ . '/../src/Exception.php';

    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'zhaniyazhaksylyk@gmail.com'; 
        $mail->Password = 'tjbsuvqsznvsueph';    
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('zhaniyazhaksylyk@gmail.com', 'EventSpace');
        $mail->addAddress($email, $username);

        $mail->isHTML(true);
        $mail->Subject = 'Welcome to EventSpace!';
        $mail->Body    = "<h1>Welcome, $username!</h1><p>Thank you for registering at EventSpace. We are excited to have you!</p>";

        $mail->send();
    } catch (Exception $e) {
        showError('Mailer Error: ' . $mail->ErrorInfo);
    }
}
?>
