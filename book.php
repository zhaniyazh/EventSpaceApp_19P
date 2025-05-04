<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

// connect to DB
$servername = "localhost";
$username = "root"; 
$password = ""; 
$database = "eventspace_db"; 

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['email'], $_POST['date'], $_POST['room'], $_POST['start_time'], $_POST['end_time'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $date = $conn->real_escape_string($_POST['date']);
    $room = $conn->real_escape_string($_POST['room']);
    $start_time = $conn->real_escape_string($_POST['start_time']);
    $end_time = $conn->real_escape_string($_POST['end_time']);
    
    // conflict check
    $conflict_sql = "SELECT * FROM bookings WHERE date='$date' AND room='$room' 
                     AND (
                        (start_time < '$end_time' AND end_time > '$start_time')
                     )";
    $conflict_result = $conn->query($conflict_sql);

    if ($conflict_result->num_rows > 0) {
        $error = "The selected time slot overlaps with an existing booking.";
    } else {
        $sql = "INSERT INTO bookings (name, email, date, room, start_time, end_time, status) 
                VALUES ('$name', '$email', '$date', '$room', '$start_time', '$end_time', 'confirmed')";
        if ($conn->query($sql) === TRUE) {
            $success = "Booking confirmed!";

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'zhaniyazhaksylyk@gmail.com'; // your Gmail
                $mail->Password = 'tjbsuvqsznvsueph';   // app-specific password
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
        
                $mail->setFrom('eventspace@gmail.com', 'EventSpace');
                $mail->addAddress($email, $name); // to user
                $mail->Subject = 'Your EventSpace Booking is Confirmed';
                $mail->Body = "Hello $name,\n\nYour booking is confirmed:\nRoom: $room\nDate: $date\nTime: $start_time to $end_time.\n\nThank you for using EventSpace!";
        
                $mail->send();
            } catch (Exception $e) {
                //email error
                $error .= " But confirmation email failed: {$mail->ErrorInfo}";
            }
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}

// fetch bookings
$bookings = [];
$sql = "SELECT date, room, start_time, end_time FROM bookings WHERE status='confirmed'";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Book | EventSpace</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/book.css">
  <style>
    /* --- styles (same clean design, just adding small for highlighting) --- */
    .logout-btn {
      position: absolute;
      top: 20px;
      right: 30px;
      background: #8E2DE2;
      color: white;
      padding: 8px 16px;
      border-radius: 6px;
      font-weight: 600;
      text-decoration: none;
      transition: 0.3s ease;
    }
    .logout-btn:hover {
      background: #6C1BB1;
    }
  
    .compact-calendar { width:100%; max-width:500px; margin:0 auto 30px; background:white; padding:15px; border-radius:12px; box-shadow:0 2px 10px rgba(0,0,0,0.05);}
    .calendar-nav { display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;}
    .calendar-nav button { background:none; border:none; font-size:1.5em; cursor:pointer; color:#6A1B9A; font-weight:bold;}
    .calendar-header { font-weight:600; font-size:1.1em;}
    .calendar-grid { display:grid; grid-template-columns:repeat(7,1fr); gap:4px;}
    .day-header { text-align:center; font-weight:600; font-size:0.7em; padding:5px 0; color:#555; text-transform:uppercase;}
    .day-cell { aspect-ratio:1; display:flex; align-items:center; justify-content:center; font-size:0.75em; cursor:pointer; border-radius:4px; transition:all 0.2s; height:30px;}
    .day-cell:hover { background-color:#f0f0f0;}
    .day-cell.selected { background-color:#6A1B9A; color:white;}
    .day-cell.other-month { color:#ccc; pointer-events:none;}
    .day-cell.today { background-color:#d0e8ff; font-weight:bold; }
    .disabled-date { color:#ccc; pointer-events:none; background:#f9f9f9;}

    .times-section { margin-top:20px;}
    .time-slots { display:grid; grid-template-columns:repeat(2,1fr); gap:8px; margin-top:10px;}
    .time-slot { padding:8px; border:1px solid #ddd; border-radius:4px; text-align:center; cursor:pointer; font-size:0.85em;}
    .time-slot:hover { background:#f0f0f0;}
    .booking-form { background:#f9f9f9; padding:20px; border-radius:8px; margin-top:20px; max-width:500px; margin:auto;}
    .form-group { margin-bottom:15px;}
    .form-group label { display:block; margin-bottom:5px; font-weight:600; font-size:0.9em;}
    .form-group input, .form-group select { width:100%; padding:8px; border:1px solid #ddd; border-radius:4px; font-size:0.9em;}
    .confirm-btn { background:#6A1B9A; color:white; border:none; padding:10px; border-radius:4px; font-weight:600; width:100%; margin-top:10px;}
    .confirm-btn:hover { background:#5a148a;}
    #selectedDateTime { font-size:0.9em; padding:8px; background:#eee; border-radius:4px;}
  </style>
</head>

<body>

<header class="header">
  <img src="assets/icons/logo.png" alt="EventSpace Logo" class="logo">
  <a href="index.php" class="logout-btn">Logout</a>
  <div class="gallery">
  <div class="gallery-item">
    <img src="assets/images/room1.png" alt="The Studio">
    <div class="caption">
      <h4>The Studio</h4>
      <p>Perfect for meeting events and creative brainstormings.</p>
    </div>
  </div>

  <div class="gallery-item">
    <img src="assets/images/room2.webp" alt="Oak Room">
    <div class="caption">
      <h4>Oak Room</h4>
      <p>Warm, wooden ambiance for focused meetings.</p>
    </div>
  </div>

  <div class="gallery-item">
    <img src="assets/images/room3.webp" alt="Botanica Lounge">
    <div class="caption">
      <h4>Botanica Lounge</h4>
      <p>Sunlit and green — perfect for wellness and calm.</p>
    </div>
  </div>

  <div class="gallery-item">
    <img src="assets/images/room4.jpg" alt="Spiral Studio">
    <div class="caption">
      <h4>Spiral Studio</h4>
      <p>Modern design — great for creative sessions.</p>
    </div>
  </div>

  <div class="gallery-item">
    <img src="assets/images/room5.jpg" alt="The Gallery Room">
    <div class="caption">
      <h4>The Gallery Room</h4>
      <p>Open and inspiring for meetups.</p>
    </div>
  </div>
</div>
</header>

<footer class="event-footer">
  <div class="footer-left">
    <h2>EVENTSPACE</h2>
    <p>
      Step into a space where planning meets precision. Located in Berlin, EventSpace is designed for those who value efficiency, style, and modern convenience. Ideal for hosting workshops, business briefings, private meetups, or creative sessions — all within a single, thoughtfully curated venue.
    </p>
  </div>
  <div class="footer-right">
    <ul>
      <li><img src="assets/icons/message.jpg" width="20"> contact@eventspace.com</li>
      <li><img src="assets/icons/icons.jpg" width="20"> (555) 555-1234</li>
      <li><img src="assets/icons/location.jpg" width="20"> Torstraße 135, 10119 Berlin</li>
      <li><img src="assets/icons/calendar.jpg" width="20"> Booking available 24/7</li>
    </ul>
  </div>
</footer>

<hr style="margin:30px 0;">


<!-- selection of room -->
<div class="compact-calendar">
  <div class="form-group">
    <label for="roomSelect">Select Room:</label>
    <select id="roomSelect">
      <option value="">Choose a room</option>
      <option value="The Studio">The Studio</option>
      <option value="Oak Room">Oak Room</option>
      <option value="Botanica Lounge">Botanica Lounge</option>
      <option value="Spiral Studio">Spiral Studio</option>
      <option value="The Gallery Room">The Gallery Room</option>
    </select>
  </div>

  <div class="calendar-nav">
    <button id="prevMonth">&lt;</button>
    <div class="calendar-header" id="calendarHeader"></div>
    <button id="nextMonth">&gt;</button>
  </div>

  <div class="calendar-grid" id="calendarGrid"></div>
</div>

<!-- time slots -->
<div class="times-section">
  <h4>Choose Time</h4>
  <div class="time-slots">
    <div class="form-group">
      <label for="startTime">Start Time:</label>
      <select id="startTime">
        <option value="">Select start</option>
        <?php for ($h = 8; $h <= 17; $h++): ?>
          <option value="<?php echo sprintf("%02d:00", $h); ?>"><?php echo sprintf("%02d:00", $h); ?></option>
        <?php endfor; ?>
      </select>
    </div>

    <div class="form-group">
      <label for="endTime">End Time:</label>
      <select id="endTime">
        <option value="">Select end</option>
        <?php for ($h = 9; $h <= 18; $h++): ?>
          <option value="<?php echo sprintf("%02d:00", $h); ?>"><?php echo sprintf("%02d:00", $h); ?></option>
        <?php endfor; ?>
      </select>
    </div>
  </div>
</div>

<!-- booking form -->
<div class="booking-form" id="bookingForm" style="display:none;">
  <h3>Complete Your Booking</h3>
  <?php if (isset($success)): ?><div class="alert-success"><?php echo $success; ?></div><?php endif; ?>
  <?php if (isset($error)): ?><div class="alert-error"><?php echo $error; ?></div><?php endif; ?>
  
  <form method="POST">
    <input type="hidden" name="date" id="formDate">
    <input type="hidden" name="room" id="formRoom">
    <input type="hidden" name="start_time" id="formStartTime">
    <input type="hidden" name="end_time" id="formEndTime">
    
    <div class="form-group">
      <label for="name">Full Name</label>
      <input type="text" name="name" required>
    </div>
    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" name="email" required>
    </div>

    <div id="selectedDateTime"></div>

    <button type="submit" class="confirm-btn">Confirm Booking</button>
  </form>
</div>

<script>
let today = new Date();
let selectedDate = null;
let selectedRoom = null;
let selectedStart = null;
let selectedEnd = null;
const bookedSlots = <?php echo json_encode($bookings); ?>;
let currentMonth = today.getMonth();
let currentYear = today.getFullYear();

function renderCalendar() {
  const calendarHeader = document.getElementById('calendarHeader');
  const calendarGrid = document.getElementById('calendarGrid');
  const date = new Date(currentYear, currentMonth, 1);
  const firstDay = date.getDay();
  const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
  calendarHeader.textContent = date.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });

  calendarGrid.innerHTML = '';
  const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
  days.forEach(day => {
    const dayHeader = document.createElement('div');
    dayHeader.className = 'day-header';
    dayHeader.textContent = day;
    calendarGrid.appendChild(dayHeader);
  });

  for (let i = 0; i < firstDay; i++) {
    const empty = document.createElement('div');
    empty.className = 'day-cell other-month';
    calendarGrid.appendChild(empty);
  }

  for (let i = 1; i <= daysInMonth; i++) {
    const dayCell = document.createElement('div');
    dayCell.className = 'day-cell';
    dayCell.textContent = i;
    const thisDate = new Date(currentYear, currentMonth, i);

    if (thisDate < today.setHours(0,0,0,0)) {
      dayCell.classList.add('disabled-date');
    }
    if (thisDate.toDateString() === new Date().toDateString()) {
      dayCell.classList.add('today');
    }
    
    dayCell.addEventListener('click', () => {
      if (dayCell.classList.contains('disabled-date')) return;
      document.querySelectorAll('.day-cell').forEach(c => c.classList.remove('selected'));
      dayCell.classList.add('selected');
      selectedDate = `${currentYear}-${String(currentMonth + 1).padStart(2,'0')}-${String(i).padStart(2,'0')}`;
      checkBookingFormReady();
    });
    
    calendarGrid.appendChild(dayCell);
  }
}

document.getElementById('prevMonth').onclick = () => {
  currentMonth--;
  if (currentMonth < 0) { currentMonth = 11; currentYear--; }
  renderCalendar();
};
document.getElementById('nextMonth').onclick = () => {
  currentMonth++;
  if (currentMonth > 11) { currentMonth = 0; currentYear++; }
  renderCalendar();
};

function checkBookingFormReady() {
  selectedRoom = document.getElementById('roomSelect').value;
  selectedStart = document.getElementById('startTime').value;
  selectedEnd = document.getElementById('endTime').value;

  if (selectedRoom && selectedDate && selectedStart && selectedEnd && selectedEnd > selectedStart) {
    document.getElementById('formRoom').value = selectedRoom;
    document.getElementById('formDate').value = selectedDate;
    document.getElementById('formStartTime').value = selectedStart;
    document.getElementById('formEndTime').value = selectedEnd;
    document.getElementById('bookingForm').style.display = 'block';
    document.getElementById('selectedDateTime').textContent = `Booking: ${selectedRoom}, ${selectedDate} from ${selectedStart} to ${selectedEnd}`;
  }
}

['roomSelect', 'startTime', 'endTime'].forEach(id => {
  document.getElementById(id).addEventListener('change', checkBookingFormReady);
});

renderCalendar();
</script>

</body>
</html>
