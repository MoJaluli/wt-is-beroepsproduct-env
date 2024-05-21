<?php
require_once '../db_connectie.php';
require_once './sanitize.php';
session_start();

$fouten2 = [];
$fouten3 = [];

//dit filtert de error zodat er geen code injectie kan worden uitgevoerd
if (isset($_GET['error'])) {
  $error = sanitize($_GET['error']);
}

if (isset($_GET['error2'])) {
  $error2 = sanitize($_GET['error2']);
}

if (isset($_GET['error3'])) {
  $error3 = sanitize($_GET['error3']);
}

//is er een username en password ingevuld?
if (isset($_POST['username']) && isset($_POST['password'])) {


  $username = sanitize($_POST['username']);
  $password = sanitize($_POST['password']);

  if (empty($username)) {
    header("Location: index.php?error=UsernameIsRequired");
    exit();
  } else if (empty($password)) {
    header("Location: index.php?error=PasswordIsRequired");
    exit();
  } else {
    $balienummer = (int)$username;
    $sqlquery = "SELECT * FROM Balie WHERE balienummer = :balienummer AND wachtwoord = :password";
    $verbinding = maakVerbinding();
    $query = $verbinding->prepare($sqlquery);

    // dit voorkomt sql injectie
    $query->bindParam(':balienummer', $balienummer, PDO::PARAM_INT);
    $query->bindParam(':password', $password);
    $query->execute();

    // dit haalt de resultaten op en telt hoeveel rijen het zijn met behulp van fetch
    $row = $query->fetch(PDO::FETCH_ASSOC);

    // dit zet aantalrijen op 1 als er resultaten zijn, als dat niet zo is dan komt het op 0 te staan
    $aantalRijen = ($row) ? 1 : 0;

    if ($aantalRijen === 1) {

      $_SESSION['ingelogd'] = true;
    } else {
      header("Location: index.php?error=IncorrectUsernameOrPassword");
      exit();
    }
  }
}
// controleerd of de gebruiker niet is ingelogd, stuur terug naar index.php
if (!isset($_SESSION['ingelogd']) || $_SESSION['ingelogd'] !== true) {
  header("Location: index.php");

  exit();
}


//zet vluchtnummer op null als er niks is ingevuld
$vluchtnummer = isset($_POST['vluchtnummer']) ? $_POST['vluchtnummer'] : null;

//laat vluchttabel zien
if (isset($vluchtnummer)) {

  $sqlquery = "SELECT vluchtnummer, bestemming, gatecode, vertrektijd FROM Vlucht WHERE vluchtnummer = :vluchtnummer";


  $verbinding = maakVerbinding();
  $query = $verbinding->prepare($sqlquery);
  $query->bindParam(':vluchtnummer', $vluchtnummer, PDO::PARAM_INT);
  $query->execute();

  $vlucht_table = '<table id="vluchten" class="vluchtentabel">';
  $vlucht_table .= '<thead>
                      <tr>
                        <th>Vluchtnummer</th>
                        <th>Aankomst luchthaven</th>
                        <th>Gate</th>
                        <th>Vertrektijd</th>
                      </tr>
                    </thead>';
  $vlucht_table .= '<tbody>';

  foreach ($query as $rij) {
    $vlucht_table .= '<tr>';
    $vlucht_table .= '<td>' . $rij['vluchtnummer'] . '</td>';
    $vlucht_table .= '<td>' . $rij['bestemming'] . '</td>';
    $vlucht_table .= '<td>' . $rij['gatecode'] . '</td>';
    $vlucht_table .= '<td>' . $rij['vertrektijd'] . '</td>';
    $vlucht_table .= '</tr>';
  }

  $vlucht_table .= '</tbody>';
  $vlucht_table .= '</table>';
}


//dit zorgt ervoor dat het maximaal aantal personen in een vlucht niet wordt overschreven (hier ben ik me toch een partij trots op xD)
if (!empty($_POST['passagiernummer'])) {
  if (!empty($_POST['vluchtnummerbestemming'])) {
    $vluchtnummerbestemming = sanitize($_POST['vluchtnummerbestemming']);
  }

  //query waar max aantal wordt geteld
  $sqlquerymaxpers = "SELECT max_aantal FROM Vlucht WHERE vluchtnummer = :vluchtnummerbestemming";
  $verbinding = maakVerbinding();
  $querymaxpers = $verbinding->prepare($sqlquerymaxpers);
  $querymaxpers->bindParam(':vluchtnummerbestemming', $vluchtnummerbestemming, PDO::PARAM_INT);
  $querymaxpers->execute();

  $row = $querymaxpers->fetch();
  // $aantalrijenpersonen = count($query->fetchAll());
  $max_personen = $row['max_aantal'];

  //volgende query waar ze aantalpersonen tellen van vlucht
  $sqlqueryaantalpers = "SELECT passagiernummer FROM Passagier WHERE vluchtnummer = :vluchtnummerbestemming";
  $verbinding = maakVerbinding();
  $queryaantalpers = $verbinding->prepare($sqlqueryaantalpers);
  $queryaantalpers->bindParam(':vluchtnummerbestemming', $vluchtnummerbestemming, PDO::PARAM_INT);
  $queryaantalpers->execute();

  $aantalrijenpersonen = count($queryaantalpers->fetchAll());

  if ($aantalrijenpersonen + 1 <= $max_personen) {
    //passagierformulier
    if (!empty($_POST['passagiernummer'])) {
      if (!empty($_POST['vluchtnummerbestemming'])) {
        $vluchtnummerbestemming = sanitize($_POST['vluchtnummerbestemming']);
      } else {
        $fouten2[] = "<p>vluchtnummerbestemming ontbreekt</p>";
      }


      if (!empty($_POST['inchecktijdstip'])) {
        $inchecktijdstip = $_POST['inchecktijdstip'];
        $formattedinchecktijdstip = date("Y-m-d H:i:s", strtotime($inchecktijdstip));
      } else {
        $fouten2[] = "<p>inchecktijdstip ontbreekt</p>";
      }



      if (!empty($_POST['passagiernummer'])) {
        $passagiernummer = sanitize($_POST['passagiernummer']);
      } else {
        $fouten2[] = "<p>passagiernummer ontbreekt</p>";
      }


      if (!empty($_POST['naam'])) {
        $naam = sanitize($_POST['naam']);
      } else {
        $fouten2[] = "<p>naam ontbreekt</p>";
      }

      if (!empty($_POST['sorteer'])) {
        $manofvrouwofanders = sanitize($_POST['sorteer']);
      } else {

        $fouten2[] = "<p>Geslacht is niet geselecteerd</p>";
      }


      if (!empty($_POST['balienummer'])) {
        $balienummer = sanitize($_POST['balienummer']);
      } else {
        $fouten2[] = "<p>balienummer ontbreekt</p>";
      }


      if (!empty($_POST['stoelnummer'])) {
        $stoelnummer = sanitize($_POST['stoelnummer']);
      } else {
        $fouten2[] = "<p>stoelnummer ontbreekt</p>";
      }

      if (!empty($_POST['wachtwoordpassagier'])) {
        $wachtwoordpassagier = sanitize($_POST['wachtwoordpassagier']);
      } else {
        $fouten2[] = "<p>wachtwoordpassagier ontbreekt</p>";
      }

      

      if (!empty($fouten2)) {
        header("Location: medewerkerportaal.php?error=VulallesinAUB");
        exit();
      }

      $sqlquery2 = 'INSERT INTO Passagier (passagiernummer, naam, vluchtnummer, geslacht, balienummer, stoel, inchecktijdstip, wachtwoord) 
  VALUES (:passagiernummer, :naam, :vluchtnummer, :geslacht, :balienummer, :stoel, :inchecktijdstip, :wachtwoord);';

      $verbinding = maakVerbinding();

      $verbinding->prepare($sqlquery2);
      $query = $verbinding->prepare($sqlquery2);
      $succes = $query->execute([":passagiernummer" => $passagiernummer, ":naam" => $naam, ":vluchtnummer" => $vluchtnummerbestemming, ":geslacht" => $manofvrouwofanders, ":balienummer" => $balienummer, ":stoel" => $stoelnummer, ":inchecktijdstip" => $formattedinchecktijdstip, ":wachtwoord" => $wachtwoordpassagier]);

      unset($_POST);
      header('Location: medewerkerportaal.php');
      
    }
  } else {
    header("Location: medewerkerportaal.php?error=Vlucht-zit-vol");
    exit();
  }
}

//vanaf hier voorbereidingen om bagage in tabel te zetten
if (!empty($_POST['passagiernummerkoffercheck'])) {
  if (!empty($_POST['passagiernummerkoffercheck'])) {
    $passagiernummerkoffercheck = $_POST['passagiernummerkoffercheck'];
  } else {
    $fouten2[] = "<p>passagiernummer ontbreekt</p>";
  }

  if (!empty($_POST['gewichtkoffercheck'])) {
    $gewichtkoffercheck = $_POST['gewichtkoffercheck'];
  } else {
    $fouten2[] = "<p>gewicht ontbreekt</p>";
  }

  if (!empty($fouten2)) {
    header("Location: boekingsscherm.php?error=VulallesinAUB");
    exit();
  }


  //bepaalobjectvolgnummer

  if (isset($_POST['passagiernummerkoffercheck']) != NULL) {
    if (isset($_POST['passagiernummerkoffercheck'])) {
      $passagiernummerkoffercheck = sanitize($_POST['passagiernummerkoffercheck']);
      $sqlquerykoffercheck = "SELECT passagiernummer, objectvolgnummer, gewicht FROM BagageObject WHERE passagiernummer = :passagiernummer";
      $verbinding = maakVerbinding();
      $querykoffercheck = $verbinding->prepare($sqlquerykoffercheck);
      // dit voorkomt sql injectie
      $querykoffercheck->bindParam(':passagiernummer', $passagiernummerkoffercheck, PDO::PARAM_INT);
      $querykoffercheck->execute();
      // dit haalt de resultaten op en telt hoeveel rijen het zijn met behulp van fetch
      $rowkoffercheck = $querykoffercheck->fetchAll(PDO::FETCH_ASSOC);
      // controleer of er resultaten zijn
      $objectvolgnummerkoffercheck = count($rowkoffercheck);
    }


    //eindebepaalobjectvolgnummer

    //zorg dat query alleen wordt uitgevoerd als objectvolgnummer niet null is

    if ($objectvolgnummerkoffercheck !== null) {

      $sqlquerykoffercheck2 = 'INSERT INTO BagageObject (passagiernummer, objectvolgnummer, gewicht) 
    VALUES (:passagiernummer, :objectvolgnummer, :gewicht);';

      $verbinding = maakVerbinding();

      $verbinding->prepare($sqlquerykoffercheck2);
      $querykoffercheck2 = $verbinding->prepare($sqlquerykoffercheck2);
      $succes = $querykoffercheck2->execute([":passagiernummer" => $passagiernummerkoffercheck, ":objectvolgnummer" => $objectvolgnummerkoffercheck, ":gewicht" => $gewichtkoffercheck]);
      unset($_POST);
      header('Location: medewerkerportaal.php');
    } else {
      header("Location: medewerkerportaal.php?error3=JeHebtTeveelKoffers");
    }
  }
}


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
