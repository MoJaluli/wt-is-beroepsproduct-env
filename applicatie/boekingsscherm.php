<?php
require_once '../db_connectie.php';
require_once 'sanitize.php';

//dit filtert de error zodat er geen code injectie kan worden uitgevoerd
if (isset($_GET['error3'])) {
  $error = sanitize($_GET['error3']);
}

//is vluchtnummer beschikbaar?
if (isset($_POST['vluchtnummer'])) {
  $vluchtnummeringevuld = sanitize($_POST['vluchtnummer']);

  // Controleer of het vluchtnummer 5 cijfers heeft, zoniet stuur dan door naar index.php
  if (strlen((string)$vluchtnummeringevuld) !== 5) {
      header("Location: index.php?error2=IncorrectFlightdetails");
      exit();
  }
  $sqlquery = "SELECT * FROM Vlucht WHERE vluchtnummer = :vluchtnummer";
  $verbinding = maakVerbinding();
  $query = $verbinding->prepare($sqlquery);

  // dit voorkomt sql injectie
  $query->bindParam(':vluchtnummer', $vluchtnummeringevuld, PDO::PARAM_INT);
  $query->execute();

  // dit haalt de resultaten op en telt hoeveel rijen het zijn met behulp van fetch
  $row = $query->fetch(PDO::FETCH_ASSOC);

  // dit zet aantalrijen op 1 als er resultaten zijn, als dat niet zo is dan komt het op 0 te staan
  $aantalRijen = ($row) ? 1 : 0;
  if ($aantalRijen === 1) {
    
    session_start();
    $_SESSION['ingelogd'] = true;
  } else {
    header("Location: index.php?error2=IncorrectFlightdetails");
    exit();
  }
}

//kijkt of vluchtnummer beschikbaar is
$vluchtnummer = isset($_POST['vluchtnummer']) ? $_POST['vluchtnummer'] : null;


//als vlucht nummer is gezet voer dan query uit
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



//vanaf hier voorbereidingen om bagage in tabel te zetten
if (!empty($_POST['passagiernummer'])) {
  if (!empty($_POST['passagiernummer'])) {
    $passagiernummer = $_POST['passagiernummer'];
  } else {
    $fouten2[] = "<p>passagiernummer ontbreekt</p>";
  }

  if (!empty($_POST['gewicht'])) {
    $gewicht = $_POST['gewicht'];
  } else {
    $fouten2[] = "<p>gewicht ontbreekt</p>";
  }

  if (!empty($fouten2)) {
    header("Location: boekingsscherm.php?error=VulallesinAUB");
    exit();
  }


  //bepaalobjectvolgnummer

  if (isset($_POST['passagiernummer']) != NULL) {
    if (isset($_POST['passagiernummer'])) {
      $passagiernummer = sanitize($_POST['passagiernummer']);
      $sqlquery = "SELECT passagiernummer, objectvolgnummer, gewicht FROM BagageObject WHERE passagiernummer = :passagiernummer";
      $verbinding = maakVerbinding();
      $query4 = $verbinding->prepare($sqlquery);
      // dit voorkomt sql injectie
      $query4->bindParam(':passagiernummer', $passagiernummer, PDO::PARAM_INT);
      $query4->execute();
      // dit haalt de resultaten op en telt hoeveel rijen het zijn met behulp van fetch
      $row = $query4->fetchAll(PDO::FETCH_ASSOC);
      // controleer of er resultaten zijn
      $objectvolgnummer = count($row);
    }


    //eindebepaalobjectvolgnummer

    //zorg dat query alleen wordt uitgevoerd als objectvolgnummer niet null is

    if ($objectvolgnummer !== null) {

      $sqlquery2 = 'INSERT INTO BagageObject (passagiernummer, objectvolgnummer, gewicht) 
    VALUES (:passagiernummer, :objectvolgnummer, :gewicht);';

      $verbinding = maakVerbinding();

      $verbinding->prepare($sqlquery2);
      $query = $verbinding->prepare($sqlquery2);
      $succes = $query->execute([":passagiernummer" => $passagiernummer, ":objectvolgnummer" => $objectvolgnummer, ":gewicht" => $gewicht]);
      unset($_POST);
      header('Location: boekingsscherm.php');
    } else {
      header("Location: boekingsscherm.php?error3=JeHebtTeveelKoffers");
    }
  }
}

//KOFFERTABEL
//Hier worden ingechkte koffers laten zien
if (isset($_POST['passagiernummerkoffercheck']) != NULL) {
$passagiernummerkoffercheck = sanitize($_POST['passagiernummerkoffercheck']);

$sqlquery3 = "SELECT objectvolgnummer, gewicht FROM BagageObject WHERE passagiernummer = :passagiernummer";
$verbinding = maakVerbinding();
$query_koffer = $verbinding->prepare($sqlquery3);
$query_koffer->bindParam(':passagiernummer', $passagiernummerkoffercheck, PDO::PARAM_INT);
$query_koffer->execute();
if (isset($_POST['passagiernummerkoffercheck']) && $_POST['passagiernummerkoffercheck'] !== "") {
$koffer_tabel = '<table id="koffertabel" class="koffertabel">';
$koffer_tabel .= '<thead>
                  <tr>
                    <th>KofferID</th>
                    <th>Gewicht in KG</th>
                    
                  </tr>
                </thead>';
$koffer_tabel .= '<tbody>';

foreach ($query_koffer as $rij) {
    $koffer_tabel .= '<tr>';
    $koffer_tabel .= '<td>' . $rij['objectvolgnummer'] . '</td>';
    $koffer_tabel .= '<td>' . $rij['gewicht'] . '</td>';
    $koffer_tabel .= '</tr>';
} 

$koffer_tabel .= '</tbody>';
$koffer_tabel .= '</table>';
} else {
  // Geen resultaten gevonden, wijzig de header
  $koffer_tabel = '<p>Geen koffers gevonden voor het opgegeven passagiernummer.</p>';
}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <link rel="stylesheet" href="styles/normalize.css" />
  <link rel="stylesheet" href="styles/styles.css" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gelre Airport</title>
</head>

<body>
  <header>
    <h1>GelreAirport</h1>
    <img class="vliegtuiglogo" src="images/vliegtuig.png" alt="vliegtuiglogo" />
    <nav>
      <ul>
        <li><a href="index.php">Loginscherm</a></li>
        <li><a href="boekingsscherm.php">Boekingscherm</a></li>
        <li><a href="Privacy.php">Privacy</a></li>
      </ul>
    </nav>
  </header>
  <main>
    <div class="groottecontainer2">
      <div class="container2">
        <div class="boekingform">
          <h2>Vluchtnummer</h2>
          <form action="boekingsscherm.php" method="post">
            <label for="vluchtnummer">Vul uw vluchtnummer in om vluchtinfo te zien en koffers in te checken</label>
            <input type="text" id="vluchtnummer" name="vluchtnummer" required />
            <button type="submit">Submit</button>
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

        <h3>Vlucht: <?php if (isset($vluchtnummer)) {
                      echo $vluchtnummer;
                    } ?></h3>

      </div>
      <div class="container2">
        <h2>Koffers inchecken</h2>
        <div class="kofferformulier">
          <form action="boekingsscherm.php" method="post">

            <?php if (isset($error3)) { ?>
                        <p class="error3"><?=$error3 ?></p>
                    <?php } ?>

            <label for="passagiernummer">Passagiernummer:</label>
            <input type="passagiernummer" id="passagiernummer" name="passagiernummer" required />

            <label for="gewicht">Gewicht van de koffer:</label>
            <input max="30" type="number" id="gewicht" name="gewicht" required />

            <button type="submit">Inchecken</button>
          </form>
        </div>
      </div>
    </div>
    <div class="groottecontainer2">
    <div class="container2">
    <div class="vluchtentabel">
        <h2>Ingecheckte koffers</h2>
        <form action="boekingsscherm.php" method="post">
        <label for="passagiernummerkoffercheck">Passagiernummer:</label>
            <input type="number" id="passagiernummerkoffercheck" name="passagiernummerkoffercheck" required />
            <button type="submit">Inchecken</button>
          <?php 
          if(isset($koffer_tabel)){
          echo $koffer_tabel;} ?>
            </form>
      </div>
          </div>
          </div>
    <div class="groottecontainer3">


    </div>
    </div>
  </main>
  <footer>Gelre Airport Copyright 2024</footer>
</body>

</html>