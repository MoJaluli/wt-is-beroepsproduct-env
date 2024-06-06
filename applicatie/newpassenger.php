<?php
require_once 'db_connectie.php';
session_start();

$vluchtnummers = '';

$db = maakVerbinding();
$sql = "SELECT vluchtnummer FROM Vlucht GROUP BY vluchtnummer";
$query = $db->prepare($sql);
$query->execute();
$result = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $rij) {
    $vluchtnummers .= '<option value="' . $rij['vluchtnummer'] . '">' . $rij['vluchtnummer'] . '</option>';
}

function CheckIfTooMuchPassengers($vluchtnummer) {
    $db = maakVerbinding();
    $sql = "SELECT COUNT(p.passagiernummer) as aantal_passagiers, v.max_aantal
            FROM Passagier p 
            INNER JOIN Vlucht v ON v.vluchtnummer = p.vluchtnummer
            WHERE p.vluchtnummer = :var_vluchtnummer
            GROUP BY v.max_aantal";
    $query = $db->prepare($sql);
    $query->execute(['var_vluchtnummer' => $vluchtnummer]);
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result && $result['aantal_passagiers'] >= $result['max_aantal'];
}

function CheckIfCombinationofFlightAndChairExist($vluchtnummer, $stoel) {
    $db = maakVerbinding();
    $sql = "SELECT 1 FROM passagier WHERE vluchtnummer = :var_vluchtnummer AND stoel = :var_stoel";
    $query = $db->prepare($sql);
    $query->execute(['var_vluchtnummer' => $vluchtnummer, 'var_stoel' => $stoel]);
    return $query->fetchColumn() !== false;
}

$melding = '';
if (isset($_POST['Nieuwe_Passagier'])) {
    $naam = htmlspecialchars(trim($_POST['Naam']));
    $geslacht = $_POST['Geslacht'];
    $stoel = htmlspecialchars(trim($_POST['Stoel']));
    $vluchtnummer = $_POST['Vluchtnummer'];

    if (!CheckIfTooMuchPassengers($vluchtnummer)) {
        if (!CheckIfCombinationofFlightAndChairExist($vluchtnummer, $stoel)) {
            try {
                $sql = "INSERT INTO Passagier (passagiernummer, naam, vluchtnummer, geslacht, balienummer, stoel, inchecktijdstip, wachtwoord)
                VALUES ((Select MAX(passagiernummer) from passagier)  +1 , :var_naam, :var_vluchtnummer, :var_geslacht, (Select balienummer from IncheckenVlucht WHERE vluchtnummer = :var_vluchtnummer2), :var_stoel, GETDATE(), 'unsafe-pass')";
                $query = $db->prepare($sql);
                $query->execute([
                    'var_vluchtnummer' => $vluchtnummer,
                    'var_vluchtnummer2' => $vluchtnummer,
                    'var_naam' => $naam,
                    'var_geslacht' => $geslacht,
                    'var_stoel' => $stoel
                ]);
                $melding = "<p class='success-msg'>U heeft een passagier aangemaakt.</p>";
            } catch (PDOException $e) {
                $melding = "<p class='error-msg'>Er is iets misgegaan: " . $e->getMessage() . "</p>";
            }
        } else {
            $melding = "<p class='error-msg'>Er is al iemand met dezelfde combinatie van vlucht en stoel.</p>";
        }
    } else {
        $melding = "<p class='error-msg'>Er zitten teveel passagiers op deze vlucht.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nieuwe Passagier Inchecken</title>
    <link rel="stylesheet" href="css/style.re.css">
</head>
<body>
        <header>
            <h1>Checkin Gelre</h1>
            <nav>
                <ul>
                    <li><a href="home.php">Startpagina</a></li>
                    <li><a href="new_flight.php">Nieuwe vluchten</a></li>
                </ul>
            </nav>
        </header>
    </body>
    </html>

    <main>
        <section id="new-passenger">
            <h2>Nieuwe Passagier Inchecken</h2>
            <?php if (!empty($melding)) echo $melding; ?>
            <form action="newpassenger.php" method="post">
                <label for="Naam">Naam:</label>
                <input type="text" name="Naam" id="Naam" required>

                <label for="Geslacht">Geslacht:</label>
                <select name="Geslacht" id="Geslacht" required>
                    <option value="M">Man</option>
                    <option value="V">Vrouw</option>
                </select>

                <label for="Stoel">Stoel:</label>
                <input type="text" name="Stoel" id="Stoel" required>

                <label for="Vluchtnummer">Vluchtnummer:</label>
                <select name="Vluchtnummer" id="Vluchtnummer" required>
                    <?php echo $vluchtnummers; ?>
                </select>

                <input type="submit" name="Nieuwe_Passagier" value="Inchecken">
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2023 Checkin Gelre. Alle rechten voorbehouden.</p>
    </footer>
</body>
</html>
