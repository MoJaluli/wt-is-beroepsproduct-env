<?php
require_once '../helpers/db_connectie.php';
require_once '../helpers/sanitize.php';

function CheckIfTooMuchWeight($passagiernummer, $gewicht) {
  $db = maakVerbinding();

  $sqlMaxGewicht = "
    SELECT v.max_gewicht_pp
    FROM Passagier p 
    JOIN Vlucht v ON p.vluchtnummer = v.vluchtnummer
    WHERE p.passagiernummer = :var_passagiernummer";

  $sqlCheckAlIngecheckteGewicht = "
    SELECT v.max_gewicht_pp, COALESCE(SUM(b.gewicht), 0) as gewicht_bagage 
    FROM Passagier p 
    JOIN Vlucht v ON p.vluchtnummer = v.vluchtnummer
    LEFT JOIN Bagageobject b ON p.passagiernummer = b.passagiernummer
    WHERE p.passagiernummer = :var_passagiernummer
    GROUP BY v.max_gewicht_pp";

  $data = ['var_passagiernummer' => $passagiernummer];

  $queryMaxGewicht = $db->prepare($sqlMaxGewicht);
  $queryMaxGewicht->execute($data);
  $resultMaxGewicht = $queryMaxGewicht->fetch(PDO::FETCH_ASSOC);

  $queryCheckAlIngecheckteGewicht = $db->prepare($sqlCheckAlIngecheckteGewicht);
  $queryCheckAlIngecheckteGewicht->execute($data);
  $resultCheck = $queryCheckAlIngecheckteGewicht->fetch(PDO::FETCH_ASSOC);

  if ($resultCheck) {
    return $gewicht + $resultCheck['gewicht_bagage'] > $resultMaxGewicht['max_gewicht_pp'];
  } else {
    return $gewicht > $resultMaxGewicht['max_gewicht_pp'];
  }
}

function CheckIfTooManyBagages($passagiernummer, $aantal) {
  $db = maakVerbinding();
  $sql = "SELECT COUNT(*) AS aantal_bagage FROM Bagageobject WHERE passagiernummer = :var_passagiernummer";
  $query = $db->prepare($sql);
  $data = ['var_passagiernummer' => $passagiernummer];
  $query->execute($data);
  $result = $query->fetch(PDO::FETCH_ASSOC);
  return $result['aantal_bagage'] >= $aantal;
}

function CheckIfPassengernumberExists($passagiernummer) {
  $db = maakVerbinding();
  $sql = "SELECT passagiernummer FROM Passagier WHERE passagiernummer = :var_passagiernummer";
  $query = $db->prepare($sql);
  $data = ['var_passagiernummer' => $passagiernummer];
  $query->execute($data);
  $result = $query->fetch(PDO::FETCH_ASSOC);
  return !empty($result);
}

if (isset($_POST['opslaan'])) {
  $passagiernummer = intval(htmlspecialchars(trim($_POST['Passagiernummer'])));
  $gewicht = intval(htmlspecialchars(trim($_POST['gewicht'])));
  $aantal = intval(htmlspecialchars(trim($_POST['aantal'])));
  $db = maakVerbinding();

  if (CheckIfTooManyBagages($passagiernummer, 9)) {
    $melding = "<p class='error-msg'>Je hebt al 9 of meer koffers ingecheckt.</p>";
  } elseif (!CheckIfPassengernumberExists($passagiernummer)) {
    $melding = "<p class='error-msg'>Het ingevoerde passagiernummer is niet bekend bij ons.</p>";
  } elseif (CheckIfTooMuchWeight($passagiernummer, $gewicht)) {
    $melding = "<p class='error-msg'>De passagier heeft teveel bagage.</p>";
  } else {
    $sql = "INSERT INTO Bagageobject (passagiernummer, objectvolgnummer, gewicht)
                VALUES (:var_passagiernummer, (SELECT COALESCE(MAX(objectvolgnummer), 0) + 1 FROM Bagageobject WHERE passagiernummer = :var_passagiernummer2), :var_gewicht)";

    $query = $db->prepare($sql);
    $data = [
      'var_passagiernummer' => $passagiernummer,
      'var_passagiernummer2' => $passagiernummer,
      'var_gewicht' => $gewicht,
    ];

    for ($i = 0; $i < $aantal; $i++) {
      $query->execute($data);
    }
    $melding = "<p class='success-msg'>U heeft een koffer ingecheckt.</p>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkin Gelre - Home</title>
  <link rel="stylesheet" href="../css/style.re.css">
</head>

<body>
  <header>
    <h1>Checkin Gelre</h1>
    <nav>
      <ul>
        <li><a href="../uitlog.php">Uitloggen</a></li>
        <li><a href="../sub/vluchten2.php">Vluchten</a></li>
        <li><a href="../passenger.php">Mijn gegevens</a></li>

      </ul>
    </nav>
  </header>
  <main>
    <h2>Welkom bij Checkin Gelre</h2>
    <p>Uw oplossing voor inchecken en vluchtinformatie op het vliegveld.</p>
    <h2>Checkin Gelre</h2>

    <form action="passenger.php" method="post">
      <label for="pnum">Passagiersnummer: </label>
      <input type="number" id="pnum" name="Passagiernummer" placeholder="Passagiersnummer">

      <label for="aantal">Aantal koffers: </label>
      <input type="number" id="aantal" name="aantal" placeholder="aantal">

      <label for="gewicht">Gewicht van koffer: </label>
      <input type="number" pattern="\d{1,6}(\.\d{1,2})?" id="gewicht" name="gewicht" placeholder="Gewicht" title="Voer een geldig gewicht in (maximaal 6 cijfers voor de komma en maximaal 2 decimalen)">
      <br>
      <input type="submit" id="opslaan" name="opslaan" value="Check koffer in">
    </form>

    <?php if (isset($melding)) echo $melding; ?>
  </main>
  <footer>
  <?php
  require_once '../sub/footer.php';
  ?>
  </footer>
</body>

</html>