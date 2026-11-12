# 🏨 Hotel Room Booking System

A PHP and MySQL-based web application developed as part of the **DBMS Project**. The system allows users to search for available hotel rooms, make bookings, and view booking records through a structured report.

---

## 📌 Project Information

| Detail | Description |
|---|---|
| **Project Name** | Hotel Room Booking System |
| **Name** | Arya PL |
| **Roll Number** | 09 |
| **Question Number** | Q9 |
| **Database Name** | `hotel_db` |

---

## 🛠️ Technologies Used

- **Frontend:** HTML, CSS
- **Backend:** PHP
- **Database:** MySQL
- **Local Server:** XAMPP (Apache and MySQL)
- **Database Management:** phpMyAdmin

---

## 📂 Project Structure

```text
Hotel-Room-Booking-System/
│
├── db.php
├── booking.php
├── report.php
├── header.php
├── footer.php
├── style.css
├── hotel_db.sql
├── README.md
│
└── screenshots/
    ├── booking-form.png
    ├── booking-confirmation.png
    └── booking-report.png
```

---

## 🗄️ Database Design

The project uses a MySQL database named `hotel_db`, consisting of three relational tables.

| Table | Description |
|---|---|
| `guest` | Stores guest details such as ID, name, city and phone number. |
| `room` | Stores room details, including room number, room type, tariff and floor. |
| `booking` | Stores booking transactions, including guest ID, room number, check-in and check-out dates. |

### Table Relationships

- `guest_id` is the Primary Key (PK) in the `guest` table.
- `room_no` is the Primary Key (PK) in the `room` table.
- `booking_id` is the Primary Key (PK) in the `booking` table.
- `booking.guest_id` references `guest.guest_id`.
- `booking.room_no` references `room.room_no`.

These relationships maintain referential integrity between the tables.

### Sample Data

The database export includes:

- 50 guest records
- 30 room records
- 500+ booking records

---

## ✨ Features

### 1. Room Booking – `booking.php`

- Search for rooms based on check-in and check-out dates.
- Filter available rooms by room type.
- Display rooms available for the selected dates.
- Select a guest and an available room.
- Validate booking details before submission.
- Prevent overlapping bookings for the same room.
- Save booking information into the database.
- Display a booking confirmation message with the booking ID.

### 2. Booking Report – `report.php`

- Display booking records in an HTML table.
- Retrieve guest and room information using SQL JOIN operations.
- Show guest name, room number, room type, check-in date and check-out date.
- Display additional booking information such as nights, amount and status.
- Provide filtering and pagination options.
- Navigate back to the booking page.

---

## ⚙️ How the System Works

### Database Connection – `db.php`

The `db.php` file establishes a connection between PHP and MySQL using the MySQLi extension. It connects to the local XAMPP MySQL server using `localhost`, the `root` username, an empty password and the database `hotel_db`. This file is included in both `booking.php` and `report.php` to maintain a common database connection.

### Booking Process – INSERT Query

When a user submits the booking form, PHP validates the selected guest, room and dates. The system checks room availability again to prevent overlapping reservations. A prepared SQL INSERT statement is then executed to store the booking details in the `booking` table.

```sql
INSERT INTO booking (guest_id, room_no, check_in, check_out)
VALUES (?, ?, ?, ?);
```

### Report Generation – SELECT and JOIN Query

The report page retrieves booking information by joining the three database tables using their related primary and foreign keys.

```sql
SELECT
    g.name,
    r.room_no,
    r.room_type,
    b.check_in,
    b.check_out
FROM booking b
INNER JOIN guest g ON b.guest_id = g.guest_id
INNER JOIN room r ON b.room_no = r.room_no;
```

The retrieved records are displayed in an HTML table.

---

## 🚀 Installation and Execution

Follow these steps to run the project locally:

**1. Install XAMPP**

Download and install XAMPP on your system.

**2. Start the Servers**

Open the XAMPP Control Panel and start:
- Apache
- MySQL

**3. Copy the Project**

Place the project folder inside the XAMPP `htdocs` directory.

```text
C:\xampp\htdocs\Hotel-Room-Booking-System\
```

**4. Create the Database**

- Open `http://localhost/phpmyadmin`
- Create a database named `hotel_db`, or import the supplied SQL file directly.
- Select the `hotel_db` database.
- Import `hotel_db.sql` through the Import option.

**5. Configure Database Connection**

Check the database connection settings in `db.php`:

```php
$host = "localhost";
$user = "root";
$password = "";
$database = "hotel_db";
```

**6. Run the Application**

Open the following URL in your browser:

```text
http://localhost/Hotel-Room-Booking-System/booking.php
```

Use the navigation link to access the booking report.

---

## 🎯 Learning Outcomes

- Understanding relational database design.
- Implementing Primary Key and Foreign Key constraints.
- Establishing PHP–MySQL connectivity.
- Executing INSERT and SELECT queries.
- Implementing SQL JOIN operations.
- Using prepared statements for database operations.
- Developing dynamic web pages using PHP and MySQL.

---

## 📄 Deliverables

- PHP source files (`db.php`, `booking.php`, `report.php`).
- Shared layout and styling files.
- MySQL database export (`hotel_db.sql`).
- Application screenshots.
- Project documentation (`README.md`).

---

**Developed by Arya PL**  
