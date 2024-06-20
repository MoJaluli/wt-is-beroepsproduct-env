<?php
require_once 'helpers/sanitize.php';
require_once 'helpers/db_connectie.php';

$melding = '';
$Gatecodes = '';
$Maatschappijcodes = '';
$Bestemming = '';

$db = maakVerbinding();

// Gatecodes ophalen
$sql = "SELECT gatecode FROM gate GROUP BY gatecode";
$query = $db->prepare($sql);
$query->execute();
$result = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $rij) {
    $Gatecodes .= '<option value="' . $rij['gatecode'] . '">' . $rij['gatecode'] . '</option>';
}

// Maatschappijcodes ophalen
$sql = "SELECT maatschappijcode, naam FROM Maatschappij GROUP BY maatschappijcode, naam";
$query = $db->prepare($sql);
$query->execute();
$result = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $rij) {
    $Maatschappijcodes .= '<option value="' . $rij['maatschappijcode'] . '">' . $rij['naam'] . '</option>';
}

// Bestemmingen ophalen
$sql = "SELECT luchthavencode FROM IncheckenBestemming GROUP BY luchthavencode";
$query = $db->prepare($sql);
$query->execute();
$result = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $rij) {
    $Bestemming .= '<option value="' . $rij['luchthavencode'] . '">' . $rij['luchthavencode'] . '</option>';
}

function checkIfFlightExist($vluchtnummer)
{
    $db = maakVerbinding();
    $sql = "SELECT vluchtnummer FROM vlucht WHERE vluchtnummer = :var_vluchtnummer";
    $query = $db->prepare($sql);
    $data = ['var_vluchtnummer' => $vluchtnummer];
    $query->execute($data);
    return $query->rowCount() > 0;
}

if (isset($_POST['submit'])) {
    $vluchtnummer = htmlspecialchars(trim($_POST['vluchtnummer']));
    $MPersonen = htmlspecialchars(trim($_POST['MPersonen']));
    $MGewPP = htmlspecialchars(trim($_POST['MGewPP']));
    $Maxtotaalgewicht = htmlspecialchars(trim($_POST['Maxtotaalgewicht']));
    $Gatecode = htmlspecialchars(trim($_POST['Gatecode']));
    $Maatschappijcode = htmlspecialchars(trim($_POST['Maatschappijcode']));
    $Bestemming = htmlspecialchars(trim($_POST['Bestemming']));
    $vertrektijd = date('Y-m-d H:i:s', strtotime($_POST['vertrektijd']));

    if (!checkIfFlightExist($vluchtnummer)) {
        try {
            $sql = 'INSERT INTO Vlucht
                    (vluchtnummer, bestemming, gatecode, max_aantal, max_gewicht_pp, max_totaalgewicht, vertrektijd, maatschappijcode)
                    VALUES (:var_vluchtnummer, :var_Bestemming, :var_Gatecode, :var_MPersonen, :var_MGewPP, :var_Maxtotaalgewicht, :var_vertrektijd, :var_Maatschappijcode)';
            $query = $db->prepare($sql);
            $data = [
                'var_vluchtnummer' => $vluchtnummer,
                'var_MPersonen' => $MPersonen,
                'var_MGewPP' => $MGewPP,
                'var_Maxtotaalgewicht' => $Maxtotaalgewicht,
                'var_Gatecode' => $Gatecode,
                'var_Maatschappijcode' => $Maatschappijcode,
                'var_Bestemming' => $Bestemming,
                'var_vertrektijd' => $vertrektijd,
            ];
            $query->execute($data);
            if ($query) {
                $melding = "<p class='success-msg'>U heeft een nieuwe vlucht aangemaakt.</p>";
            }
        } catch (PDOException $e) {
            $melding = "<p class='error-msg'>Er is iets misgegaan met het inchecken van uw vlucht. Fout: " . $e->getMessage() . "</p>";
        }
    } else {
        $melding = "<p class='error-msg'>Er bestaat al een vlucht met dit vluchtnummer.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkin Gelre - Vluchten</title>
    <link rel="stylesheet" href="css/style.re.css">
</head>
<body>
    <header>
        <h1>Checkin Gelre</h1>
        <nav>
            <ul>
                <li><a href="Home.php">Startpagina</a></li>
                <li><a href="new_flight.php">Nieuwe Vlucht</a></li>
                <li><a href="newpassenger.php">Nieuwe passagier</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section id="flights">
            <h2>Vluchten Pagina</h2>
            <p>Welkom op de vluchtenpagina van Checkin Gelre.</p>
            <h3>Nieuwe Vlucht Aanmaken</h3>
            <?php if (!empty($melding)) { echo $melding; } ?>
            <form action="new_flight.php" method="post">
                <div class="form-group">
                    <label for="vluchtnummer">Vluchtnummer:</label>
                    <input type="number" name="vluchtnummer" id="vluchtnummer" placeholder="Vluchtnummer" maxlength="5" required>
                </div>
                <div class="form-group">
                    <label for="MPersonen">Max. aantal personen:</label>
                    <input type="number" name="MPersonen" id="MPersonen" placeholder="Max. aantal personen" maxlength="3" required>
                </div>
                <div class="form-group">
                    <label for="MGewPP">Max. gewicht p.p. (kg):</label>
                    <input type="text" pattern="\d{1,6}(\.\d{1,2})?" name="MGewPP" id="MGewPP" placeholder="Max. gewicht p.p." title="Voer een geldig gewicht in (maximaal 6 cijfers voor de komma en maximaal 2 decimalen)" required>
                </div>
                <div class="form-group">
                    <label for="Maxtotaalgewicht">Max. totaal gewicht (kg):</label>
                    <input type="text" pattern="\d{1,6}(\.\d{1,2})?" name="Maxtotaalgewicht" id="Maxtotaalgewicht" placeholder="Max. totaal gewicht" title="Voer een geldig gewicht in (maximaal 6 cijfers voor de komma en maximaal 2 decimalen)" required>
                </div>
                <div class="form-group">
                    <label for="Gatecode">Gatecode:</label>
                    <select id="Gatecode" name="Gatecode" required>
                        <?php echo $Gatecodes; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Maatschappijcode">Maatschappijcode:</label>
                    <select id="Maatschappijcode" name="Maatschappijcode" required>
                        <?php echo $Maatschappijcodes; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Bestemming">Bestemming:</label>
                    <select id="Bestemming" name="Bestemming" required>
                        <?php echo $Bestemming; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="vertrektijd">Vertrektijd:</label>
                    <input type="datetime-local" name="vertrektijd" id="vertrektijd" required>
                </div>
                <div class="form-group">
                    <input type="submit" name="submit" value="Maak nieuwe vlucht">
                </div>
            </form>
        </section>
    </main>
    <?php
  require_once 'sub/footer.php';
  ?>
</body>
</html>
