<?php
// report.php - Page 2: booking report (JOIN of booking + guest + room)
include 'db.php';

/* ---------- Filters (GET) ---------- */
$q      = trim($_GET["q"] ?? "");
$type   = $_GET["type"] ?? "";
$status = $_GET["status"] ?? "";
$page   = max(1, (int)($_GET["page"] ?? 1));
$perPage = 15;

$statusSql = "CASE
                WHEN b.check_out <= CURDATE() THEN 'Completed'
                WHEN b.check_in  >  CURDATE() THEN 'Upcoming'
                ELSE 'In-house'
              END";

$where  = [];
$params = [];
$ptypes = "";
if ($q !== "") {
    $where[] = "g.name LIKE ?";
    $params[] = "%" . $q . "%";
    $ptypes .= "s";
}
if ($type !== "") {
    $where[] = "r.room_type = ?";
    $params[] = $type;
    $ptypes .= "s";
}
if (in_array($status, ["Completed", "Upcoming", "In-house"], true)) {
    $where[] = "($statusSql) = ?";
    $params[] = $status;
    $ptypes .= "s";
}
$whereSql = $where ? "WHERE " . implode(" AND ", $where) : "";

$fromSql = "FROM booking b
            JOIN guest g ON b.guest_id = g.guest_id
            JOIN room  r ON b.room_no  = r.room_no";

/* ---------- Summary cards ---------- */
$stats = $conn->query(
    "SELECT COUNT(*) AS total,
            COUNT(DISTINCT b.guest_id) AS guests,
            COALESCE(SUM(DATEDIFF(b.check_out, b.check_in) * r.tariff_per_day), 0) AS revenue,
            COALESCE(SUM(b.check_in <= CURDATE() AND b.check_out > CURDATE()), 0) AS inhouse,
            COALESCE(SUM(b.check_in > CURDATE()), 0) AS upcoming
     $fromSql"
)->fetch_assoc();
$totalRooms = (int)$conn->query("SELECT COUNT(*) FROM room")->fetch_row()[0];
$occupancy  = $totalRooms ? round($stats["inhouse"] * 100 / $totalRooms) : 0;

/* ---------- Count for pagination ---------- */
$stmt = $conn->prepare("SELECT COUNT(*) $fromSql $whereSql");
if ($params) { $stmt->bind_param($ptypes, ...$params); }
$stmt->execute();
$stmt->bind_result($totalRows);
$stmt->fetch();
$stmt->close();

$pages = max(1, (int)ceil($totalRows / $perPage));
$page  = min($page, $pages);
$offset = ($page - 1) * $perPage;

/* ---------- The report: join of the three tables ---------- */
$sql = "SELECT b.booking_id, g.name, g.city, r.room_no, r.room_type, r.tariff_per_day,
               b.check_in, b.check_out,
               DATEDIFF(b.check_out, b.check_in) AS nights,
               DATEDIFF(b.check_out, b.check_in) * r.tariff_per_day AS amount,
               $statusSql AS status
        $fromSql
        $whereSql
        ORDER BY b.check_in DESC, b.booking_id DESC
        LIMIT $perPage OFFSET $offset";
$stmt = $conn->prepare($sql);
if ($params) { $stmt->bind_param($ptypes, ...$params); }
$stmt->execute();
$rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$types = $conn->query("SELECT DISTINCT room_type FROM room ORDER BY room_type")->fetch_all(MYSQLI_ASSOC);

function page_link($p) {
    $qs = $_GET;
    $qs["page"] = $p;
    return "report.php?" . htmlspecialchars(http_build_query($qs));
}
$badge = ["In-house" => "in", "Upcoming" => "up", "Completed" => "done"];

$pageTitle = "Booking Report";
$active = "report";
include 'header.php';
?>
<section class="hero">
    <div class="wrap">
        <h1>Booking report</h1>
        <p>Every reservation with guest, room and stay details, joined from the guest, room and booking tables.</p>
    </div>
</section>

<main class="wrap pull-up">

    <div class="stats">
        <div class="stat"><span>Total bookings</span><b><?= number_format($stats["total"]) ?></b></div>
        <div class="stat"><span>Guests served</span><b><?= $stats["guests"] ?></b></div>
        <div class="stat"><span>Total revenue</span><b>&#8377;<?= number_format($stats["revenue"]) ?></b></div>
        <div class="stat"><span>In-house today</span><b><?= $stats["inhouse"] ?></b></div>
        <div class="stat"><span>Occupancy today</span><b><?= $occupancy ?>%</b></div>
        <div class="stat"><span>Upcoming arrivals</span><b><?= $stats["upcoming"] ?></b></div>
    </div>

    <section class="card">
        <form method="get" action="report.php">
            <div class="grid g4">
                <div>
                    <label class="lbl" for="q">Guest name</label>
                    <input type="text" id="q" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Search guest...">
                </div>
                <div>
                    <label class="lbl" for="type">Room type</label>
                    <select id="type" name="type">
                        <option value="">All types</option>
                        <?php foreach ($types as $t): ?>
                            <option value="<?= htmlspecialchars($t["room_type"]) ?>" <?= $type === $t["room_type"] ? "selected" : "" ?>><?= htmlspecialchars($t["room_type"]) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="lbl" for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">All</option>
                        <?php foreach (["In-house", "Upcoming", "Completed"] as $s): ?>
                            <option <?= $status === $s ? "selected" : "" ?>><?= $s ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="align-self:end">
                    <button class="btn" type="submit">Apply filters</button>
                    <a class="btn ghost" href="report.php">Reset</a>
                </div>
            </div>
        </form>
    </section>

    <section class="card">
        <div class="tbl-wrap">
        <?php if (!$rows): ?>
            <div class="empty">No bookings match your filters.</div>
        <?php else: ?>
            <table class="data">
                <tr>
                    <th>Booking</th>
                    <th>Guest Name</th>
                    <th>Room No</th>
                    <th>Room Type</th>
                    <th>Check-in Date</th>
                    <th>Check-out Date</th>
                    <th class="num">Nights</th>
                    <th class="num">Amount</th>
                    <th>Status</th>
                </tr>
                <?php foreach ($rows as $r): ?>
                <tr>
                    <td>BK-<?= str_pad($r["booking_id"], 5, "0", STR_PAD_LEFT) ?></td>
                    <td><?= htmlspecialchars($r["name"]) ?><span class="guest-city"><?= htmlspecialchars($r["city"]) ?></span></td>
                    <td><?= $r["room_no"] ?></td>
                    <td><?= htmlspecialchars($r["room_type"]) ?></td>
                    <td><?= date('d M Y', strtotime($r["check_in"])) ?></td>
                    <td><?= date('d M Y', strtotime($r["check_out"])) ?></td>
                    <td class="num"><?= $r["nights"] ?></td>
                    <td class="num">&#8377;<?= number_format($r["amount"]) ?></td>
                    <td><span class="badge <?= $badge[$r["status"]] ?>"><?= $r["status"] ?></span></td>
                </tr>
                <?php endforeach; ?>
            </table>

            <div class="pager">
                <?php if ($page > 1): ?><a href="<?= page_link($page - 1) ?>">&laquo; Prev</a><?php else: ?><span class="dis">&laquo; Prev</span><?php endif; ?>
                <?php for ($p = max(1, $page - 2); $p <= min($pages, $page + 2); $p++): ?>
                    <?php if ($p == $page): ?><span class="cur"><?= $p ?></span><?php else: ?><a href="<?= page_link($p) ?>"><?= $p ?></a><?php endif; ?>
                <?php endfor; ?>
                <?php if ($page < $pages): ?><a href="<?= page_link($page + 1) ?>">Next &raquo;</a><?php else: ?><span class="dis">Next &raquo;</span><?php endif; ?>
                <span class="count" style="border:0;background:none">Showing <?= $offset + 1 ?>&ndash;<?= $offset + count($rows) ?> of <?= $totalRows ?> bookings</span>
            </div>
        <?php endif; ?>
        </div>
    </section>

    <div class="actions" style="margin-top:0">
        <a class="btn dark" href="booking.php">&larr; Back to booking form</a>
    </div>
</main>
<?php include 'footer.php'; ?>
