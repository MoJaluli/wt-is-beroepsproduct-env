<?php
;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkin Gelre - Vluchten</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <header>
        <h1>Checkin Gelre</h1>
        <nav>
            <ul>
                <li><a href="Home.php">Startpagina</a></li>
                <li><a href="employee.php">Toevoegen</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
    </header>

    <section id="flights">
        <h2>Vluchten Pagina</h2>
        <p>Welkom op de vluchtenpagina van Checkin Gelre.</p>

        <h2>Gegevenstabel</h2>

        <form id="searchForm">
            <input type="text" id="searchInput" placeholder="Zoeken op naam">
            <button type="submit">Zoeken</button>
        </form>

        <div class="flight-cards">
            <div class="flight-card" data-search="John Doe">
                <h3>John Doe</h3>
                <p>Stad: Amsterdam</p>
                <p>Vluchtnummer: FG123</p>
                <p>Vertrektijd: 08:00</p>
                <p>Aankomsttijd: 10:30</p>
                <button type="button">Aanpassen</button>
            </div>

            <div class="flight-card" data-search="Alice Smith">
                <h3>Alice Smith</h3>
                <p>Stad: Parijs</p>
                <p>Vluchtnummer: PS789</p>
                <p>Vertrektijd: 14:30</p>
                <p>Aankomsttijd: 17:45</p>
                <button type="button">Aanpassen</button>
            </div>
        
            <div class="flight-card" data-search="Bob Johnson">
                <h3>Bob Johnson</h3>
                <p>Stad: Tokyo</p>
                <p>Vluchtnummer: TJ101</p>
                <p>Vertrektijd: 18:15</p>
                <p>Aankomsttijd: 21:00</p>
                <button type="button">Aanpassen</button>
            </div>
        
            <div class="flight-card" data-search="Emily White">
                <h3>Emily White</h3>
                <p>Stad: Sydney</p>
                <p>Vluchtnummer: SW202</p>
                <p>Vertrektijd: 09:20</p>
                <p>Aankomsttijd: 12:10</p>
                <button type="button">Aanpassen</button>
            </div>
        </div>

        <form id="addFlightForm">
            <h2>Nieuwe Vlucht Toevoegen</h2>
            <label for="passengerName">Naam passagier:</label>
            <input type="text" id="passengerName" name="passengerName" required>

            <label for="city">Bestemming stad:</label>
            <input type="text" id="city" name="city" required>

            <label for="flightNumber">Vluchtnummer:</label>
            <input type="text" id="flightNumber" name="flightNumber" required>

            <label for="departureTime">Vertrektijd:</label>
            <input type="text" id="departureTime" name="departureTime" required>

            <label for="arrivalTime">Aankomsttijd:</label>
            <input type="text" id="arrivalTime" name="arrivalTime" required>

            <button type="submit">Toevoegen</button>
        </form>

        <img src="schiphol-legt-zich-voorlopig-Vluchten.webp" alt="Voorbeeld Afbeelding" style="max-width: 100%; height: auto; border-radius: 8px; margin-top: 20px;">
    </section>

    <footer>
        <p>&copy; 2023 Checkin Gelre. Alle rechten voorbehouden.</p>
    </footer>
</body>
</html>
