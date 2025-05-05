# **EventSpace – Smart Room Booking System**

## **Introduction**  
This project is a web-based booking system designed for educational institutions and co-working spaces. It allows users to visually select a room, choose a time slot, and confirm a reservation—all while preventing double bookings. Users also receive an email confirmation for successful bookings.

## **Problem Statement**  
Manual room reservation processes often lead to scheduling conflicts, time wastage, and poor resource management. Without a centralized digital system, users may lack clarity on availability. EventSpace solves this issue with a modern, easy-to-use interface that automates confirms reservations and eliminates errors.

## **Objectives**  
- To create an intuitive, conflict-free room reservation interface  
- To notify users of confirmed bookings via email  
- To offer a reliable backend system for managing reservations  
- To simplify time and space management in shared environments  

## **Technology Stack**  
- **Frontend**: HTML5, CSS3, JavaScript  
- **Backend**: PHP  
- **Database**: MySQL  
- **Email**: PHPMailer (SMTP with Gmail)  

## **Installation Instructions**
```bash
# 1. Clone the repository
git clone https://github.com/zhaniyazh/EventSpaceApp_19P.git

# 2. Navigate into the project directory
cd EventSpaceApp_19P

# 3. Set up your local server (I've used XAMPP)

# 4. Import the database
#    - Open phpMyAdmin
#    - Create a new database named `eventspace_db`
#    - Import the provided SQL file `eventspace_db.sql`

# 5. Configure PHPMailer (recommended)
#    - Open `book.php`
#    - Set your Gmail and App Password in the PHPMailer section

# 6. Run the app in your browser
http://localhost/eventspace/index.php
```

## **Usage Guide**  
- Sign in to the system  
- Log in using your credentials
- From the main interface, select a room and a date  
- Choose your preferred start and end time  
- Enter your name and email  
- Click “Confirm Booking”  
- A confirmation email will be sent, and your booking will be stored in the database

> Admin login: `admin_login.php`  
> Admin can view/manage all bookings in `admin.php`

## **Testing**
This project does not include an automated test suite. However, the following manual tests are recommended:
- **Booking Conflict Test:**  
  Attempt to book a room at the same date and time as an existing confirmed booking. The system should detect the overlap.
- **Form Validation Test:**  
  Submit the sign up form with missing or invalid inputs/passwords (empty fields, less than 8 characters, password mismatch). The system should display appropriate error messages.
- **Admin Panel Access Test:**  
  Request a verification code and attempt to log in to the admin panel. Only valid codes should allow access.


## **Known Issues / Limitations**  
- Users can book, but they cannot manage their bookings after submission — only admin can  
- Admin authentication is basic (no hashed passwords)
- No mobile-optimized UI yet 

## **References**  
- [PHPMailer Documentation](https://github.com/PHPMailer/PHPMailer)  
- [W3Schools PHP Tutorial](https://www.w3schools.com/php/)  
- [Font: Poppins - Google Fonts](https://fonts.google.com/specimen/Poppins)  

## **Team Members**  
- Zhaniya Zhaksylyk, 220103082, 19-P  
