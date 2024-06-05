<?php
require_once 'db_connectie.php';
require_once 'sanitize.php';

function CheckIfToMuchWeight($passagiernummer, $gewicht) {
    $db = maakVerbinding();
  
    $sqlMaxGewicht = "select v.max_gewicht_pp
    from Passagier p 
    JOIN Vlucht v ON p.vluchtnummer = v.vluchtnummer
    LEFT JOIN Bagageobject b ON p.passagiernummer = b.passagiernummer
    WHERE p.passagiernummer = :var_passagiernummer1
     GROUP BY p.passagiernummer, p.naam, v.vluchtnummer, v.max_gewicht_pp";
  
    $sqlCheckAlIngecheckteGewicht = "select v.max_gewicht_pp, sum(b.gewicht) as gewicht_bagage 
    from Passagier p 
    JOIN Vlucht v ON p.vluchtnummer = v.vluchtnummer
    LEFT JOIN Bagageobject b ON p.passagiernummer = b.passagiernummer
    WHERE p.passagiernummer = :var_passagiernummer
     GROUP BY p.passagiernummer, p.naam, v.vluchtnummer, v.max_gewicht_pp
                  HAVING COALESCE(SUM(b.gewicht), 0) <= v.max_gewicht_pp";
  
                  
  $data_sqlCheckAlIngecheckteGewicht = [
    'var_passagiernummer' => $passagiernummer,
    
  ];
  $data_sqlMaxGewicht = [
    'var_passagiernummer1' => $passagiernummer,
  ];
  $queryMaxGewicht = $db->prepare($sqlMaxGewicht);
  $queryMaxGewicht->execute($data_sqlMaxGewicht);
  
  $queryCheckAlIngecheckteGewicht = $db->prepare($sqlCheckAlIngecheckteGewicht);
  $queryCheckAlIngecheckteGewicht->execute($data_sqlCheckAlIngecheckteGewicht);
  
  $result = $queryMaxGewicht->fetchAll(PDO::FETCH_ASSOC);
  if ($queryCheckAlIngecheckteGewicht->rowCount() == 0) {
    if($gewicht > $result[0]['max_gewicht_pp'])
    return true;
  }
  
  $resultCheck = $queryCheckAlIngecheckteGewicht->fetchAll(PDO::FETCH_ASSOC);
  if($gewicht+$resultCheck[0]['gewicht_bagage']> $result[0]['max_gewicht_pp']){
    return true;
  }
  return false;
  }
  function CheckIfToMuchBagages($passagiernummer, $aantal)
  {
    $db = maakVerbinding();
    // Voorbereiden insert into statement
  
  
    $sql = "select MAX(objectvolgnummer) AS nummer from Bagageobject WHERE passagiernummer = :var_passagiernummer";
  
    $query = $db->prepare($sql);
  
    // Data voorbereiden
  
    $data = [
      'var_passagiernummer' => $passagiernummer,
    ];
  
    $query->execute($data);
  
    if ($query->rowCount() == 0) {
      return false;
    }
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result[0]['nummer'] >= $aantal ? true : false;
  }
  
  
  function CheckIfPassengernumberExists($passagiernummer)
  {
    $db = maakVerbinding();
    // Voorbereiden insert into statement
  
  
    $sql = "select passagiernummer from Bagageobject WHERE passagiernummer = :var_passagiernummer";
  
    $query = $db->prepare($sql);
  
    // Data voorbereiden
  
    $data = [
      'var_passagiernummer' => $passagiernummer,
    ];
  
    $query->execute($data);
  
    if ($query->rowCount() == 0) {
      return false;
    }else{
      return true;
    }
    
  }
  
  if (isset($_POST['opslaan'])) {
    $passagiernummer = intval(htmlspecialchars(trim($_POST['Passagiernummer'])));
    $gewicht = intval(htmlspecialchars(trim($_POST['gewicht'])));
  
    // Er zijn geen fouten, door naar sql insert into...
    //try {
      $db = maakVerbinding();
      // Voorbereiden insert into statement
      if (!CheckIfToMuchBagages($passagiernummer, 9) && CheckIfPassengernumberExists($passagiernummer) && !CheckIfToMuchWeight($passagiernummer, $gewicht)) {
        
  
        $sql = "INSERT INTO Bagageobject (passagiernummer, objectvolgnummer, gewicht)
  VALUES (:var_passagiernummer,(Select MAX(objectvolgnummer) from Bagageobject WHERE passagiernummer = :var_passagiernummer2)  +1 ,:var_gewicht)";
  
        $query = $db->prepare($sql);
  
        // Data voorbereiden
  
        $data = [
          'var_passagiernummer' => $passagiernummer,
          'var_gewicht' => $gewicht,
          'var_passagiernummer2' => $passagiernummer,
        ];
  
        $query->execute($data);
        if ($query) {
          $melding = "<p class='success-msg'>U heeft een koffer ingecheckt.</p>";
        }
      } elseif(CheckIfToMuchBagages($passagiernummer, 9)) {
        $melding = "<p class='error-msg'>Je hebt al 9 of meer koffers ingecheckt.</p>";
      }elseif(!CheckIfPassengernumberExists($passagiernummer)) {
        $melding = "<p class='error-msg'>Het ingevoerde passagiernummer is niet bekend bij ons.</p>";
      }elseif(CheckIfToMuchWeight($passagiernummer, $gewicht)) {
        $melding = "<p class='error-msg'>De passagier heeft teveel bagage</p>";
      }
  
    // } 
    // catch (PDOException) {
    //   $melding = "<p class='error-msg'>Er is iets misgegaan met het inchecken van uw koffer.</p>";
    // }
  }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkin Gelre - Home</title>
    
    <link rel="stylesheet" href="css/style.re.css">
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
    <body>
    <main>
        <h2>Welkom bij Checkin Gelre</h2>
        <p>Uw oplossing voor inchecken en vluchtinformatie op het vliegveld.</p>
    <h2>Checkin Gelre</h2>

    <?php echo $melding ?>
    <form action="passenger.php" method="post">

      <label for="pnum">Passagiersnummer: </label>
      <input type="number" id="pnum" name="Passagiernummer" placeholder="Passagiersnummer">

      <label for="gewicht">Gewicht van koffer: </label>
      <input type="text" pattern="\d{1,6}(\.\d{1,2})?" id="gewicht" name="gewicht" placeholder="Gewicht" title="Voer een geldig gewicht in (maximaal 6 cijfers voor de komma en maximaal 2 decimalen)">

      <input type="submit" id="opslaan" name="opslaan" value="Check koffer in">

    </form>
  </main>

   
        <?php
        require_once 'footer.php';
        ?>
    </footer>
</body>
</html>
