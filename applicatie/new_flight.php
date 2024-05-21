<?php
;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkin Gelre - Nieuwe Vlucht</title>
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

    <section id="new-flight">
        <h2>Nieuwe Vlucht</h2> 
        <section id="flightSearch">
            <h2>Vluchtgegevens Ophalen</h2>
            <form id="flightForm">
                <label for="destination">Bestemming:</label>
                <input type="text" id="destination" name="destination" required>

                <label for="flightNumber">Vluchtnummer:</label>
                <input type="text" id="flightNumber" name="flightNumber" required>

                <label for="airline">Luchtvaartmaatschappij:</label>
                <input type="text" id="airline" name="airline" required>

                <button type="submit">Zoeken</button>
            </form>
        
            <section id="flightDetails" style="display: none;">
                <h2>Details van Vlucht</h2>
                <p>Vluchtnummer: <span id="resultFlightNumber"></span></p>
                <p>Bestemming: <span id="resultDestination"></span></p>
                <section id="moreDetails">
                    <h2>Meer details</h2>
                    <p>Aankomsttijd: <span id="resultArrivalTime"></span></p>
                    <p>Vertrektijd: <span id="resultDepartureTime"></span></p>
                    <p>Luchtvaartmaatschappij: <span id="resultAirline"></span></p>
                </section>
            </section>
        </section>
    </section>

    <section id="flights-list">
        <h2>Beschikbare Vluchten</h2>
        <ul>
            <li><strong>Vluchtnummer:</strong> 123, <strong>Bestemming:</strong> New York, <strong>Luchtvaartmaatschappij:</strong> Air Gelre</li>
            <li><strong>Vluchtnummer:</strong> 456, <strong>Bestemming:</strong> London, <strong>Luchtvaartmaatschappij:</strong> Sky Express</li>
            <li><strong>Vluchtnummer:</strong> 789, <strong>Bestemming:</strong> Paris, <strong>Luchtvaartmaatschappij:</strong> Windy Airways</li>
            <li><strong>Vluchtnummer:</strong> 101, <strong>Bestemming:</strong> Tokyo, <strong>Luchtvaartmaatschappij:</strong> Rising Sun Airlines</li>
            <li><strong>Vluchtnummer:</strong> 202, <strong>Bestemming:</strong> Sydney, <strong>Luchtvaartmaatschappij:</strong> Southern Skies Airways</li>
            <li><strong>Vluchtnummer:</strong> 303, <strong>Bestemming:</strong> Rome, <strong>Luchtvaartmaatschappij:</strong> Eternal City Airlines</li>
            <li><strong>Vluchtnummer:</strong> 404, <strong>Bestemming:</strong> Amsterdam, <strong>Luchtvaartmaatschappij:</strong> Dutch Wings</li>
            <li><strong>Vluchtnummer:</strong> 505, <strong>Bestemming:</strong> Beijing, <strong>Luchtvaartmaatschappij:</strong> Great Wall Airlines</li>
            <li><strong>Vluchtnummer:</strong> 606, <strong>Bestemming:</strong> Dubai, <strong>Luchtvaartmaatschappij:</strong> Arabian Skies</li>
        </ul>
    </section>
    

    <footer>
        <p>&copy; 2023 Checkin Gelre. Alle rechten voorbehouden.</p>
    </footer>
</body>
</html>