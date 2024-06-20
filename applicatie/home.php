<?php

use function PHPSTORM_META\type;

require_once 'helpers/sanitize.php';
require_once 'helpers/db_connectie.php';

session_start();

// Login-functionaliteit voor passagiers
if (isset($_POST['login_passagier'])) {
    $gebruikersnaam = htmlspecialchars(trim($_POST['gebruikersnaam']));
    $wachtwoord = htmlspecialchars(trim($_POST['wachtwoord']));
   
    if (!is_numeric($gebruikersnaam) || $gebruikersnaam < 10000 || $gebruikersnaam > 99999 || (int) $gebruikersnaam != $gebruikersnaam) {

        $melding_passagier = "<p class='error-msg'>Fout: incorrecte inloggegevens!</p>";
    } else {
        $db = maakVerbinding();

        $sql = "SELECT wachtwoord FROM Passagier WHERE passagiernummer = :passagiernummer";

        $query = $db->prepare($sql);

        $data = [
            ':passagiernummer' => (int) $gebruikersnaam
        ];

        $query->execute($data);

        if ($rij = $query->fetch()) {
            $passwordhash = $rij['wachtwoord'];

            if (password_verify($wachtwoord, $passwordhash)) {
                // passagier gevonden
                $_SESSION['passagier'] = $gebruikersnaam;
                header("Location: passenger.php");
                exit();
            } else {
                $melding_passagier = "<p class='error-msg'>Fout: incorrecte inloggegevens!</p>";
            }
        } else {
            $melding_passagier = "<p class='error-msg'>Fout: incorrecte inloggegevens!</p>";
        }
    }
}
?>

<?php


require_once 'helpers/sanitize.php';
require_once 'helpers/db_connectie.php';


// Login-functionaliteit voor medewerkers
if (isset($_POST['login_medewerker'])) {
    $balienummer = $_POST['balienummer'];
    $wachtwoord = htmlspecialchars(trim($_POST['wachtwoord']));

    if (!is_numeric($balienummer) || $balienummer < 1 || $balienummer > 99 || (int) $balienummer != $balienummer) {
        $melding_medewerker = "<p class='error-msg'>Fout: incorrecte inloggegevens!</p>";
    } else {
        $db = maakVerbinding();

        $sql = "SELECT wachtwoord FROM Balie WHERE balienummer = :balienummer";

        $query = $db->prepare($sql);

        $data = [
            ':balienummer' => (int) $balienummer
        ];

        $query->execute($data);

        if ($rij = $query->fetch()) {
            $passwordhash = $rij['wachtwoord'];

            if (password_verify($wachtwoord, $passwordhash)) {
                // medewerker gevonden
                $_SESSION['medewerker'] = $balienummer;
                header("Location: medewerker.php");
                exit();
            } else {
                $melding_medewerker = "<p class='error-msg'>Fout: incorrecte inloggegevens!</p>";
            }
        } else {
            $melding_medewerker = "<p class='error-msg'>Fout: incorrecte inloggegevens!</p>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkin Gelre - Home</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <header>
        <h1>Checkin Gelre</h1>
        <nav>
            <ul>
                <li><a href="home.php">Startpagina</a></li>
            </ul>
        </nav>
    </header>

    <section>
        <h2>Welkom bij Checkin Gelre</h2>
        <p>Uw oplossing voor inchecken en vluchtinformatie op het vliegveld.</p>
    </section>

    <div class="login-sections">
        <section class="login-section">
            <h2>Passagier Inloggen</h2>
            <?php if (!empty($melding_passagier)) {
                echo $melding_passagier;
            } ?>
            <form class="text-center" method="POST" action="">
                <label for="gebruikersnaam">Gebruikersnaam:</label>
                <input type="nummeric" id="gebruikersnaam" name="gebruikersnaam" placeholder="Gebruikersnaam" min = '1' max= '1000' step= '1'required>
                <label for="wachtwoord">Wachtwoord:</label>
                <input type="password" id="wachtwoord" name="wachtwoord" placeholder="Wachtwoord" required>
                <button type="submit" name="login_passagier">Inloggen</button>
            </form>
            <a href="registreren.php">registreren</a>
        </section>

        <section class="login-section">
            <h2>Medewerker Inloggen</h2>
            <?php if (!empty($melding_medewerker)) {
                echo $melding_medewerker;
            } ?>
            <form class="text-center" method="POST" action="">
                <label for="balienummer">Ballienummer:</label>
                <input type="number" id="balienummer" name="balienummer" placeholder="Ballienummer" min="1" max="99" step="1" required>
                <label for="wachtwoord">Wachtwoord:</label>
                <input type="password" id="wachtwoord" name="wachtwoord" placeholder="Wachtwoord" required>
                <button type="submit" name="login_medewerker">Inloggen</button>
            </form>
        </section>
    </div>

    <?php
  require_once 'sub/footer.php';
  ?>
</body>

</html>