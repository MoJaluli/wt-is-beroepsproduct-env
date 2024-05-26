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
                <li><a href="employee.php">Toevoegen</a></li>
                <li><a href="flights.php">Vluchten</a></li>
            </ul>
        </nav>
    </header>

    <section id="new-flight">
        <h2>Nieuwe Vlucht</h2>
        <div id="flightSearch">
            <div>Vluchtgegevens Ophalen</div>
            <form>
                <label for="flightNumber">Vluchtnummer:</label>
                <input type="number" id="flightNumber" name="flightNumber" required>
                <button type="button">Zoeken</button>
            </form>
        
            <section id="flightDetails" style="display: none;">
                <h2>Details van Vlucht</h2>
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

        <?php if (isset($error)) {
                    ?>
                        <p class="error"><?=$error ?></p>
                    <?php } ?>
          <label for="vluchtnummerbestemming">vluchtnummer</label>
          <input type="number" id="vluchtnummerbestemming" name="vluchtnummerbestemming" required />

          <label for="passagiernummer">passagiernummer:</label>
          <input type="number" id="passagiernummer" name="passagiernummer" required />

          <label for="naam">naam</label>
          <input type="text" id="naam" name="naam" required />


          <label class="sorteercontainer">
            Man
            <input type="radio" value="m" name="sorteer" required />
            <span class="checkmark1"></span>
          </label>
          <label class="sorteercontainer">
            Vrouw
            <input type="radio" value="v" name="sorteer" />
            <span class="checkmark1"></span>
          </label>
          <label class="sorteercontainer">
            Anders
            <input type="radio" value="x" name="sorteer" />
            <span class="checkmark1"></span>
          </label>

          <label for="balienummer">balienummer</label>
          <input type="number" id="balienummer" name="balienummer" required />

          <label for="stoelnummer">stoelnummer</label>
          <input maxlength="3" type="text" id="stoelnummer" name="stoelnummer" required />

          <label for="inchecktijdstip">inchecktijdstip:</label>
          <input type="datetime-local" id="inchecktijdstip" name="inchecktijdstip" required />

          <label for="wachtwoordpassagier">wachtwoordpassagier</label>
          <input type="text" id="wachtwoordpassagier" name="wachtwoordpassagier" required />





          <button type="submit">Toevoegen</button>
        </form>
      </div>
      <div class="container2">
        <div class="boekingform">
          <h2>Vluchtnummer</h2>
          <form action="medewerkerportaal.php" method="post">
            <label for="vluchtnummer">Vul vluchtnummer in om vlucht info te zien</label>
            <input type="number" id="vluchtnummer" name="vluchtnummer" required />
            <button type="submit">Submit</button>
          </form>

        </div>
        <h2>Koffers inchecken</h2>
        <div class="kofferformulier">
          <form action="medewerkerportaal.php" method="post">

            <?php if (isset($error3)) { ?>
                        <p class="error3"><?=$error3 ?></p>
                    <?php } ?>

            <label for="passagiernummerkoffercheck">Passagiernummer:</label>
            <input type="passagiernummerkoffercheck" id="passagiernummerkoffercheck" name="passagiernummerkoffercheck" required />

            <label for="gewichtkoffercheck">Gewicht van de koffer:</label>
            <input max="30" type="number" id="gewichtkoffercheck" name="gewichtkoffercheck" required />

            <button type="submit">Inchecken</button>
          </form>
        </div>

      </div>
      <div class="container3">
        <img class="vliegtuigfoto" src="images/vliegtuig2.jpg" alt="vliegtuig" />
        <?php
        if (isset($vluchtnummer)) {
          echo $vlucht_table;
        }
        ?>
        <h3>Vlucht: <?php echo $vluchtnummer; ?></h3>

      </div>
    </div>
    <div class="groottecontainer3">
      <!-- end sessie knop -->
      <form action="endsession.php" method="POST">
        <input type="submit" value="Uitloggen" />
      </form>
    </div>
  </main>
  <footer>Gelre Airport Copyright 2024</footer>
        
    </section>

    <section id="flights-list">
        <h2>Beschikbare Vluchten</h2>
        <ul>
            <li><strong>Vluchtnummer:</strong> 123, <strong>Bestemming:</strong> New York, <strong>Luchtvaartmaatschappij:</strong> Air Gelre, <strong>Stoelnummer:</strong> 15A</li>
            <li><strong>Vluchtnummer:</strong> 456, <strong>Bestemming:</strong> London, <strong>Luchtvaartmaatschappij:</strong> Sky Express, <strong>Stoelnummer:</strong> 22C</li>
            <li><strong>Vluchtnummer:</strong> 789, <strong>Bestemming:</strong> Paris, <strong>Luchtvaartmaatschappij:</strong> Windy Airways, <strong>Stoelnummer:</strong> 10B</li>
            <li><strong>Vluchtnummer:</strong> 101, <strong>Bestemming:</strong> Tokyo, <strong>Luchtvaartmaatschappij:</strong> Sun Airlines, <strong>Stoelnummer:</strong> 7F</li>
            <li><strong>Vluchtnummer:</strong> 202, <strong>Bestemming:</strong> Sydney, <strong>Luchtvaartmaatschappij:</strong> Skies Air, <strong>Stoelnummer:</strong> 12D</li>
        </ul>
    </section>
    
    <footer>
        <p>&copy; 2023 Checkin Gelre. Alle rechten voorbehouden.</p>
    </footer>
</body>
</html>
