<?php
require_once '../db_connectie.php';
require_once 'sanitize.php';

// als de medewerker is ingelogd dan komt de pagina tevoorschijn
session_start();
if (!isset($_SESSION['ingelogd']) || $_SESSION['ingelogd'] !== true) {
  header("Location: employee.php");
  exit();
}


;
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkin Gelre - Nieuwe Vlucht</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Checkin Gelre</h1>
        <nav>
            <ul>
                <li><a href="Home.php">Startpagina</a></li>
                <li><a href="employee.php">Toevoegen</a></li>
                <li><a href="flights.php">Vluchten</a></li>
            </ul>
        </nav>
    </header>

    <section id="logout">
            <form action="endsession.php" method="POST">
                <input type="submit" value="Uitloggen">
            </form>
        </section>

    <main>
        <section id="new-flight">
            <h2>Nieuwe Vlucht</h2>
            <div id="flightSearch">
                <h3>Vluchtgegevens Ophalen</h3>
                <form>
                    <label for="flightNumber">Vluchtnummer:</label>
                    <input type="number" id="flightNumber" name="flightNumber" required>
                    <button type="button">Zoeken</button>
                </form>
                <section id="flightDetails" style="display: none;">
                    <h3>Details van Vlucht</h3>
                    <p>Vluchtnummer: <span id="resultFlightNumber"></span></p>
                    <p>Bestemming: <span id="resultDestination"></span></p>
                    <p>Aankomsttijd: <span id="resultArrivalTime"></span></p>
                    <p>Vertrektijd: <span id="resultDepartureTime"></span></p>
                    <p>Luchtvaartmaatschappij: <span id="resultAirline"></span></p>
                    <p>Stoelnummer: <span id="resultSeatNumber"></span></p>
                    <p>Gate: <span id="resultGate"></span></p>
                    <p>Check-in Balie: <span id="resultCheckinCounter"></span></p>
                </section>
            </div>
        </section>
        
        <section id="passengerDetails">
            <h2>Nieuwe Passagier</h2>
            <form id="passengerForm">
                <label for="destination">Bestemming:</label>
                <input type="text" id="destination" name="destination" required>
                <label for="passengerFlightNumber">Vluchtnummer:</label>
                <input type="text" id="passengerFlightNumber" name="passengerFlightNumber" required>
                <label for="airline">Luchtvaartmaatschappij:</label>
                <input type="text" id="airline" name="airline" required>
                <label for="departureAirport">Vertrek luchthaven:</label>
                <input type="text" id="departureAirport" name="departureAirport" required>
                <label for="arrivalAirport">Aankomst luchthaven:</label>
                <input type="text" id="arrivalAirport" name="arrivalAirport" required>
                <label for="departureDate">Vertrekdatum:</label>
                <input type="date" id="departureDate" name="departureDate" required>
                <label for="departureTime">Vertrektijd:</label>
                <input type="text" id="departureTime" name="departureTime" required>
                <label for="arrivalDate">Aankomstdatum:</label>
                <input type="date" id="arrivalDate" name="arrivalDate" required>
                <label for="arrivalTime">Aankomsttijd:</label>
                <input type="text" id="arrivalTime" name="arrivalTime" required>
                <label for="passengerName">Naam passagier:</label>
                <input type="text" id="passengerName" name="passengerName" required>
                <label for="passengerLastName">Achternaam passagier:</label>
                <input type="text" id="passengerLastName" name="passengerLastName" required>
                <label for="passengerEmail">E-mail passagier:</label>
                <input type="email" id="passengerEmail" name="passengerEmail" required>
                <button type="submit">Toevoegen</button>
            </form>
            <?php if (isset($error)): ?>
                <p class="error"><?=$error ?></p>
            <?php endif; ?>
        </section>

        <section id="luggageCheckIn">
          <h2>Koffers inchecken</h2>
          <form action="medewerkerportaal.php" method="post" class="luggage-check-in-form">
            <?php if (isset($error3)): ?>
              <p class="error3"><?=$error3 ?></p>
            <?php endif; ?>
            <label for="passagiernummerkoffercheck">Passagiernummer:</label>
            <input type="number" id="passagiernummerkoffercheck" name="passagiernummerkoffercheck" required>
            <label for="gewichtkoffercheck">Gewicht van de koffer:</label>
            <input type="number" id="gewichtkoffercheck" name="gewichtkoffercheck" max="30" required>
            <button type="submit">Inchecken</button>
          </form>
        </section>


    
        <?php
        // Make a query to fetch flight data from the database or API
        $flights = [
          ['flightNumber' => '123', 'destination' => 'New York', 'airline' => 'Air Gelre', 'seatNumber' => '15A'],
          ['flightNumber' => '456', 'destination' => 'London', 'airline' => 'Sky Express', 'seatNumber' => '22C'],
          ['flightNumber' => '789', 'destination' => 'Paris', 'airline' => 'Windy Airways', 'seatNumber' => '10B'],
          ['flightNumber' => '101', 'destination' => 'Tokyo', 'airline' => 'Sun Airlines', 'seatNumber' => '7F'],
          ['flightNumber' => '202', 'destination' => 'Sydney', 'airline' => 'Skies Air', 'seatNumber' => '12D'],
        ];
        ?>
        <section id="flights-list">
          <h2>Beschikbare Vluchten</h2>
          <ul>
            <?php foreach ($flights as $flight): ?>
              <li><strong>Vluchtnummer:</strong> <?=$flight['flightNumber'] ?>, <strong>Bestemming:</strong> <?=$flight['destination'] ?>, <strong>Luchtvaartmaatschappij:</strong> <?=$flight['airline'] ?>, <strong>Stoelnummer:</strong> <?=$flight['seatNumber'] ?></li>
            <?php endforeach; ?>
          </ul>
        </section>
        
        <section id="logout">
            <form action="endsession.php" method="POST">
                <input type="submit" value="Uitloggen">
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2023 Checkin Gelre. Alle rechten voorbehouden.</p>
    </footer>
</body>
</html>
