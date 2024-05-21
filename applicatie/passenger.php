<?php
;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkin Gelre - Home</title>
    
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Checkin Gelre</h1>
        <nav>
            <ul>
                <li><a href="Home.php">Startpagina</a></li>
                <li><a href="new_flight.php">Nieuwe Vlucht</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
    </header>

    <section id="flightDetails">
        <h2>Mijn Vluchtgegevens</h2>
        <p>Vluchtnummer: <span id="passengerFlightNumber">123</span></p>
        <p>Bestemming: <span id="passengerDestination">Amsterdam</span></p>
        <p>Vertrektijd: <span id="passengerDepartureTime">2023-11-30 12:00:00</span></p>

        <form action="inchecken.php" method="post">
            <label for="baggageWeight">Gewicht van koffer (kg):</label>
            <input type="number" id="baggageWeight" name="baggageWeight" required>

            <label for="numberOfBags">Aantal koffers:</label>
            <input type="number" id="numberOfBags" name="numberOfBags" required>

            <button onclick="window.location.href='inchecken_apart.php';">Inchecken</button>
        </form>
    </section>

    <footer>
        <p>&copy; 2023 Checkin Gelre. Alle rechten voorbehouden.</p>
    </footer>
</body>
</html>