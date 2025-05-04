# **EventSpace – Smart Room Booking System**

## **Introduction**  
This project is a web-based booking system designed for educational institutions and co-working spaces. It allows users to visually select a room, choose a time slot, and confirm a reservation—all while preventing double bookings. Users also receive an email confirmation for successful bookings.

## **Problem Statement**  
Manual room reservation processes often lead to scheduling conflicts, time wastage, and poor resource management. Without a centralized digital system, users may double-book spaces or lack clarity on availability. EventSpace solves this issue with a modern, easy-to-use interface that automates availability checks, confirms reservations, and eliminates errors.

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
git clone https://github.com/yourusername/eventspace.git

# 2. Navigate into the project directory
cd eventspace

# 3. Set up your local server (XAMPP/WAMP/MAMP)

# 4. Import the database
#    - Open phpMyAdmin
#    - Create a new database named `eventspace_db`
#    - Import the provided SQL file `eventspace_db.sql`

# 5. Configure PHPMailer (optional but recommended)
#    - Open `book.php`
#    - Set your Gmail and App Password in the PHPMailer section

# 6. Run the app in your browser
http://localhost/eventspace/index.php
```

## **Usage Guide**  
- From the main interface, select a room and a date  
- Choose your preferred start and end time  
- Enter your name and email  
- Click “Confirm Booking”  
- A confirmation email will be sent, and your booking will be stored in the database

> Admin login: `admin_login.php`  
> Admin can view/manage all bookings in `admin.php`

## **Testing**  
No automated test suite is included, but:
- Try booking a room for overlapping times to test conflict detection  
- Try using invalid or missing fields in the form  
- You may add PHPUnit for backend testing in future iterations

## **Known Issues / Limitations**  
- No mobile-optimized UI yet  
- Time slots are fixed between 08:00 and 18:00  
- No user authentication for public users  
- Admin authentication is basic (no hashed passwords)

## **References**  
- [PHPMailer Documentation](https://github.com/PHPMailer/PHPMailer)  
- [W3Schools PHP Tutorial](https://www.w3schools.com/php/)  
- [Font: Poppins - Google Fonts](https://fonts.google.com/specimen/Poppins)  

## **Team Members**  
- Zhaniya Zhaksylyk, 123456, 19-P  
