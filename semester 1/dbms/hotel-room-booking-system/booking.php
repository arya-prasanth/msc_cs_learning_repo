<?php
// booking.php - Page 1: reservation desk (entry page for the booking table)
include 'db.php';

$today = date('Y-m-d');
$error = "";

/* Amenities shown on room cards (display only) */
$amenities = [
    "Standard Single" => ["Single bed", "Wi-Fi", "AC"],
    "Standard Double" => ["Double bed", "Wi-Fi", "AC", "TV"],
    "Deluxe"          => ["King bed", "City view", "Mini bar", "Wi-Fi"],
    "Executive"       => ["King bed", "Work desk", "Lounge access", "Breakfast"],
    "Suite"           => ["Living area", "Bathtub", "Lounge access", "Breakfast"],
];

function nights_between($in, $out) {
    return (int)((strtotime($out) - strtotime($in)) / 86400);
}

/* Is a room free for the given dates? (no overlapping booking) */
function room_is_free($conn, $room_no, $in, $out) {
    $stmt = $conn->prepare(
        "SELECT COUNT(*) FROM booking WHERE room_no = ? AND check_in < ? AND check_out > ?"
    );
    $stmt->bind_param("iss", $room_no, $out, $in);
    $stmt->execute();
    $stmt->bind_result($n);
    $stmt->fetch();
    $stmt->close();
    return $n == 0;
}

/* ---------- Handle form submit (POST): INSERT into booking ---------- */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $guest_id  = (int)($_POST["guest_id"] ?? 0);
    $room_no   = (int)($_POST["room_no"] ?? 0);
    $check_in  = $_POST["check_in"] ?? "";
    $check_out = $_POST["check_out"] ?? "";

    $validDates = preg_match('/^\d{4}-\d{2}-\d{2}$/', $check_in) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $check_out);

    if ($guest_id <= 0 || $room_no <= 0 || !$validDates) {
        $error = "Please select a guest and a room before confirming the booking.";
    } elseif ($check_out <= $check_in) {
        $error = "Check-out date must be after the check-in date.";
    } elseif ($check_in < $today) {
        $error = "Check-in date cannot be in the past.";
    } elseif (!room_is_free($conn, $room_no, $check_in, $check_out)) {
        $error = "Sorry, room $room_no was just booked for those dates. Please choose another room.";
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO booking (guest_id, room_no, check_in, check_out) VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("iiss", $guest_id, $room_no, $check_in, $check_out);
        if ($stmt->execute()) {
            $id = $conn->insert_id;
            $stmt->close();
            header("Location: booking.php?saved=" . $id); // redirect so refresh does not re-submit
            exit;
        }
        $error = "The booking could not be saved. " . $stmt->error;
        $stmt->close();
    }
    // keep the dates so the user can retry
    $_GET["check_in"]  = $check_in;
    $_GET["check_out"] = $check_out;
    $_GET["search"]    = 1;
}

/* ---------- Confirmation of a saved booking (SELECT with join) ---------- */
$saved = null;
if (isset($_GET["saved"])) {
    $sid = (int)$_GET["saved"];
    $stmt = $conn->prepare(
        "SELECT b.booking_id, g.name, g.phone, r.room_no, r.room_type, r.tariff_per_day, b.check_in, b.check_out
         FROM booking b
         JOIN guest g ON b.guest_id = g.guest_id
         JOIN room  r ON b.room_no  = r.room_no
         WHERE b.booking_id = ?"
    );
    $stmt->bind_param("i", $sid);
    $stmt->execute();
    $saved = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

/* ---------- Availability search (GET) ---------- */
$in  = $_GET["check_in"]  ?? $today;
$out = $_GET["check_out"] ?? date('Y-m-d', strtotime('+1 day'));
$searched = isset($_GET["search"]);
$available = [];
$nights = 0;

if ($searched) {
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $in) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $out)) {
        $error = $error ?: "Please enter valid dates.";
        $searched = false;
    } elseif ($out <= $in) {
        $error = $error ?: "Check-out date must be after the check-in date.";
        $searched = false;
    } elseif ($in < $today) {
        $error = $error ?: "Check-in date cannot be in the past.";
        $searched = false;
    } else {
        $nights = nights_between($in, $out);
        $filter = $_GET["type"] ?? "";
        $sql = "SELECT r.room_no, r.room_type, r.tariff_per_day, r.floor
                FROM room r
                WHERE NOT EXISTS (
                    SELECT 1 FROM booking b
                    WHERE b.room_no = r.room_no AND b.check_in < ? AND b.check_out > ?
                )";
        if ($filter !== "") {
            $sql .= " AND r.room_type = ?";
        }
        $sql .= " ORDER BY r.tariff_per_day, r.room_no";
        $stmt = $conn->prepare($sql);
        if ($filter !== "") {
            $stmt->bind_param("sss", $out, $in, $filter);
        } else {
            $stmt->bind_param("ss", $out, $in);
        }
        $stmt->execute();
        $available = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }
}

$guests = $conn->query("SELECT guest_id, name, city FROM guest ORDER BY name")->fetch_all(MYSQLI_ASSOC);
$types  = $conn->query("SELECT DISTINCT room_type FROM room ORDER BY room_type")->fetch_all(MYSQLI_ASSOC);

$pageTitle = "Book a Room";
$active = "book";
include 'header.php';
?>
<section class="hero">
    <div class="wrap">
        <h1>Reserve your stay</h1>
        <p>Check live room availability for your dates, choose a room and confirm the reservation for a guest.</p>
    </div>
</section>

<main class="wrap pull-up">

    <?php if ($saved): ?>
        <?php $n = nights_between($saved["check_in"], $saved["check_out"]); ?>
        <div class="alert success">
            <strong>Booking saved successfully.</strong> Reservation <b>BK-<?= str_pad($saved["booking_id"], 5, "0", STR_PAD_LEFT) ?></b> has been recorded in the booking table.
            <div class="confirm">
                <div><span>Guest</span><b><?= htmlspecialchars($saved["name"]) ?></b></div>
                <div><span>Room</span><b><?= $saved["room_no"] ?> &middot; <?= htmlspecialchars($saved["room_type"]) ?></b></div>
                <div><span>Check-in</span><b><?= date('d M Y', strtotime($saved["check_in"])) ?></b></div>
                <div><span>Check-out</span><b><?= date('d M Y', strtotime($saved["check_out"])) ?></b></div>
                <div><span>Nights</span><b><?= $n ?></b></div>
                <div><span>Total tariff</span><b>&#8377;<?= number_format($n * $saved["tariff_per_day"]) ?></b></div>
            </div>
            <div class="actions"><a class="btn dark" href="report.php">View booking report</a></div>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert error"><strong>Error:</strong> <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Step 1: dates -->
    <section class="card">
        <h2><span class="step">1</span>Select your dates</h2>
        <p class="sub">Only rooms that are free for the whole stay will be shown.</p>
        <form method="get" action="booking.php">
            <input type="hidden" name="search" value="1">
            <div class="grid g3">
                <div>
                    <label class="lbl" for="check_in">Check-in</label>
                    <input type="date" id="check_in" name="check_in" min="<?= $today ?>" value="<?= htmlspecialchars($in) ?>" required>
                </div>
                <div>
                    <label class="lbl" for="check_out">Check-out</label>
                    <input type="date" id="check_out" name="check_out" min="<?= $today ?>" value="<?= htmlspecialchars($out) ?>" required>
                </div>
                <div>
                    <label class="lbl" for="type">Room type</label>
                    <select id="type" name="type">
                        <option value="">All types</option>
                        <?php foreach ($types as $t): ?>
                            <option value="<?= htmlspecialchars($t["room_type"]) ?>" <?= (($_GET["type"] ?? "") === $t["room_type"]) ? "selected" : "" ?>>
                                <?= htmlspecialchars($t["room_type"]) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="actions">
                <button class="btn" type="submit">Check availability</button>
                <a class="btn ghost" href="report.php">View booking report &rarr;</a>
            </div>
        </form>
    </section>

    <!-- Step 2: choose room + guest, then submit -->
    <?php if ($searched): ?>
    <section class="card">
        <h2><span class="step">2</span>Choose a room &amp; guest</h2>
        <p class="sub">
            <?= date('D, d M Y', strtotime($in)) ?> &rarr; <?= date('D, d M Y', strtotime($out)) ?>
            &middot; <?= $nights ?> night<?= $nights > 1 ? "s" : "" ?>
            &middot; <?= count($available) ?> room<?= count($available) == 1 ? "" : "s" ?> available
        </p>

        <?php if (!$available): ?>
            <div class="alert info">No rooms are available for these dates. Try different dates or another room type.</div>
        <?php else: ?>
        <form method="post" action="booking.php">
            <input type="hidden" name="check_in"  value="<?= htmlspecialchars($in) ?>">
            <input type="hidden" name="check_out" value="<?= htmlspecialchars($out) ?>">

            <label class="lbl">Room</label>
            <div class="rooms">
                <?php foreach ($available as $i => $r): ?>
                    <label class="room-opt">
                        <input type="radio" name="room_no" value="<?= $r["room_no"] ?>" required <?= $i === 0 ? "" : "" ?>>
                        <div class="room-card">
                            <div class="room-no">Room <?= $r["room_no"] ?></div>
                            <div class="room-type"><?= htmlspecialchars($r["room_type"]) ?></div>
                            <div class="room-meta">Floor <?= $r["floor"] ?></div>
                            <div class="room-price">&#8377;<?= number_format($r["tariff_per_day"]) ?> <small>/ night</small></div>
                            <div class="room-total">Total &#8377;<?= number_format($r["tariff_per_day"] * $nights) ?> for <?= $nights ?> night<?= $nights > 1 ? "s" : "" ?></div>
                            <div class="chips">
                                <?php foreach (($amenities[$r["room_type"]] ?? []) as $a): ?>
                                    <span class="chip"><?= htmlspecialchars($a) ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </label>
                <?php endforeach; ?>
            </div>

            <div class="grid g2" style="margin-top:18px">
                <div>
                    <label class="lbl" for="guest_id">Guest</label>
                    <select id="guest_id" name="guest_id" required>
                        <option value="">-- Select guest --</option>
                        <?php foreach ($guests as $g): ?>
                            <option value="<?= $g["guest_id"] ?>"><?= htmlspecialchars($g["name"]) ?> &middot; <?= htmlspecialchars($g["city"]) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="actions">
                <button class="btn" type="submit">Submit &amp; confirm booking</button>
            </div>
        </form>
        <?php endif; ?>
    </section>
    <?php endif; ?>

</main>
<?php include 'footer.php'; ?>
