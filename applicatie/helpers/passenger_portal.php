<?php
require_once 'sanitize.php';

if (isset($_GET['code'])) {
    $passengerCode = sanitize($_GET['code']);
} else {
    // Redirect to home if code is not set
    header("Location: home.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passagier Portaal - Checkin Gelre</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Checkin Gelre</h1>
        <nav>
            <ul>
                <li><a href="home.php">Startpagina</a></li>
                <li><a href="new_flight.php">Nieuwe Vlucht</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
    </header>

    <section>
        <h2>Welkom, Passagier!</h2>
        <p>Uw passagierscode: <?= htmlspecialchars($passengerCode) ?></p>
        <p>Hier kunt u uw vluchtinformatie bekijken en inchecken.</p>
    </section>

    <footer>
        <p>&copy; 2023 Checkin Gelre. Alle rechten voorbehouden.</p>
    </footer>
</body>
</html>
