# Q9 – Hotel Room Booking System

- **Name:** Arya Prasanth
- **Roll Number:** <your roll number>
- **Question Number:** 9
- **Database:** hotel_db (tables: guest, room, booking)

## Files
- `db.php` – database connection, included in both pages
- `booking.php` – Page 1: availability search + booking entry form
- `report.php` – Page 2: booking report (join of the three tables)
- `header.php`, `footer.php`, `style.css` – shared layout and styling
- `hotel_db.sql` – database export (structure + 50 guests, 30 rooms, 500+ bookings)
- `screenshots/` – entry form, confirmation message, report page

## How it works
`db.php` opens a MySQLi connection to the `hotel_db` database on localhost (user `root`, empty password in XAMPP) and is included at the top of both pages. On `booking.php`, the user picks check-in and check-out dates and a SELECT query lists only the rooms with no overlapping booking, using `NOT EXISTS` on the booking table. When the form is submitted, PHP re-checks availability and runs a prepared `INSERT INTO booking (guest_id, room_no, check_in, check_out)` statement with bound parameters, then shows a success message with the booking ID, or an error message if the insert fails. `report.php` runs a SELECT that JOINs `booking`, `guest` and `room` and displays Guest Name, Room No, Room Type, Check-in and Check-out (plus nights, amount and status) in an HTML table, with filters and pagination. Each page links to the other.
