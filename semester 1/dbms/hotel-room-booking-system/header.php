<?php
// header.php - shared page header + navigation. Set $pageTitle and $active before including.
$pageTitle = $pageTitle ?? "The Meridian Residency";
$active    = $active ?? "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle) ?> | The Meridian Residency</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="topbar">
    <div class="wrap topbar-in">
        <a class="brand" href="booking.php">
            <span class="brand-mark">M</span>
            <span><strong>The Meridian Residency</strong><small>Kochi &middot; Kerala &middot; 5-star hotel</small></span>
        </a>
        <nav>
            <a href="booking.php" class="<?= $active === 'book' ? 'on' : '' ?>">Book a Room</a>
            <a href="report.php"  class="<?= $active === 'report' ? 'on' : '' ?>">Booking Report</a>
        </nav>
    </div>
</header>
