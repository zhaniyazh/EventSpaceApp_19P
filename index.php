<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>EventSpace | Berlin, Germany</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap & Fonts -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- Header -->
<header class="container-fluid py-4 px-5 d-flex justify-content-between align-items-center">
  <img src="assets/icons/logo.png" alt="EventSpace Logo" style="height: 70px;">
  <div class="d-flex align-items-center gap-4">
    <a href="admin_login.php">
    <span class="text-muted fw-semibold">ADMIN</span>
    </a>
    <a href="register.php" class="btn btn-gradient text-white px-4 py-2 fw-semibold rounded-pill d-flex align-items-center gap-2">
      <img src="assets/icons/book.png" width="20" alt="calendar"> Book Private Room
    </a>
  </div>
</header>

<!-- Hero Section -->
<section class="text-center mt-5 position-relative">
  <p class="text-uppercase text-muted fw-bold">Event Venue Booking in Berlin, Germany</p>
  <h1 class="fw-bold display-4">
    <span style="color:rgb(172, 43, 138);">Welcome to</span> <span style="color: #333;">EventSpace</span>
  </h1>
  <p class="lead mx-auto mb-5" style="max-width: 700px;">A modern solution for booking private rooms and spaces for meetings, workshops, and group gatherings</p>

  <!-- Decorative sparkles (updated position + size) -->
  <img src="assets/icons/sparkle.png" class="position-absolute" style="top: 300px; left: 500px; width: 70px;" alt="sparkle">
  <img src="assets/images/laptop.png" class="img-fluid mt-4" style="max-width: 600px;" alt="device mockup">
  <img src="assets/icons/sparkle.png" class="position-absolute" style="bottom: 60px; right: 500px; width: 70px;" alt="sparkle">
</section>

<!-- Venue Gallery -->
<header class="header">
  <div class="gallery">
    <img src="assets/images/room1.png" alt="Gallery 1">
    <img src="assets/images/room2.webp" alt="Gallery 2">
    <img src="assets/images/room3.webp" alt="Gallery 3">
    <img src="assets/images/room4.jpg" alt="Gallery 4">
    <img src="assets/images/room5.jpg" alt="Gallery 5">
  </div>
</header>

<!-- Footer Section -->
<footer class="container py-5 border-top d-flex flex-column flex-md-row justify-content-between">
  <div class="mb-4 mb-md-0 col-md-6">
    <h5 class="fw-bold">EVENTSPACE</h5>
    <p>Step into a space where planning meets precision. Located in Berlin, EventSpace is designed for those who value efficiency, style, and modern convenience. Ideal for hosting workshops, business briefings, private meetups, or creative sessions — all within a single, thoughtfully curated venue.</p>
  </div>
  <div class="col-md-5">
    <ul class="list-unstyled">
      <li class="mb-3"><img src="assets/icons/message.jpg" width="30"> contact@eventspace.com</li>
      <li class="mb-3"><img src="assets/icons/icons.jpg" width="30"> (555) 555-1234</li>
      <li class="mb-3"><img src="assets/icons/location.jpg" width="30"> Torstraße 135, 10119 Berlin</li>
      <li class="mb-3"><img src="assets/icons/calendar.jpg" width="30"> Booking available 08:00 - 23:00</li>
    </ul>
  </div>
</footer>

</body>
</html>
