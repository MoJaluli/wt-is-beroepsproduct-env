<?php
require_once 'sanitize.php';
require_once 'db_connectie.php';

session_start();

// Initialize the session error array
$_SESSION['error'] = [];

// Meldingen voor login
$melding_passagier = '';
$melding_medewerker = '';

// Login-functionaliteit voor passagiers
if (isset($_POST['login_passagier'])) {
    $gebruikersnaam = htmlspecialchars(trim($_POST['gebruikersnaam']));
    $wachtwoord = htmlspecialchars(trim($_POST['wachtwoord']));
    try {
        $db = maakVerbinding();

        $sql = "SELECT wachtwoordhash
                FROM Gebruiker
                WHERE gebruikersnaam = :var_gebruikersnaam";
                echo($sql);

        $query = $db->prepare($sql);

        $data = [
            'var_gebruikersnaam' => $gebruikersnaam,
        ];

        $query->execute($data);

        if ($rij = $query->fetch()) {
            // gebruiker gevonden
            $passwordhash = $rij['wachtwoordhash'];
            if (password_verify($wachtwoord, $passwordhash)) {
                $_SESSION['gebruiker'] = $gebruikersnaam;
                header("Location: passenger.php");
                exit();
            } else {
                $melding_passagier = "<p class='error-msg'>Fout: incorrecte inloggegevens!</p>";
            }
        } else {
            $melding_passagier = "<p class='error-msg'>Fout: incorrecte inloggegevens!</p>";
        }
    } catch (PDOException $e) {
        $melding_passagier = "<p class='error-msg'>Er is iets misgegaan. Neem contact op met de systeembeheerder.</p>" . $e->getMessage();
    }
}
?>

<?php

require_once 'sanitize.php';
require_once 'db_connectie.php';

// Login-functionaliteit voor medewerkers
if (isset($_POST['login_medewerker'])) {
    $balienummer = $_POST['balienummer'];
    $wachtwoord = htmlspecialchars(trim($_POST['wachtwoord']));

    
    try {
        $db = maakVerbinding();

        $sql = "SELECT balienummer, wachtwoord FROM Balie WHERE balienummer = :balienummer AND wachtwoord = :password";

        echo($sql);

        $query = $db->prepare($sql);

        $data = [
            'balienummer' => (int) $balienummer,
            'password' => $wachtwoord,
        ];

        $query->execute($data);

        if ($rij = $query->fetch()) {
            // medewerker gevonden
            $_SESSION['medewerker'] = $balienummer;
            header("Location: employee.php");
            exit();
        } else {
            $melding_medewerker = "<p class='error-msg'>Fout: incorrecte inloggegevens!</p>";
        }
        
    } catch (PDOException $e) {
        $melding_medewerker = "<p class='error-msg'>Er is iets misgegaan. Neem contact op met de systeembeheerder.</p>" . $e->getMessage();
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
                <li><a href="flights.php">Vluchten</a></li>
                <li><a href="employee.php">inchecken</a></li>
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
            <?php if (!empty($melding_passagier)) { echo $melding_passagier; } ?> 
            <form class="text-center" method="POST" action="">
                <label for="gebruikersnaam">Gebruikersnaam:</label>
                <input type="text" id="gebruikersnaam" name="gebruikersnaam" placeholder="Gebruikersnaam" required>
                <label for="wachtwoord">Wachtwoord:</label>
                <input type="password" id="wachtwoord" name="wachtwoord" placeholder="Wachtwoord" required>
                <button type="submit" name="login_passagier">Inloggen</button>
            </form>
            <a href="registreren.php">registreren</a>
        </section>

        <section class="login-section">
    <h2>Medewerker Inloggen</h2>
    <?php if (!empty($melding_medewerker)) { echo $melding_medewerker; } ?>
    <form class="text-center" method="POST" action="">
        <label for="balienummer">Ballienummer:</label>
        <input type="text" id="balienummer" name="balienummer" placeholder="Ballienummer" required>
        <label for="wachtwoord">Wachtwoord:</label>
        <input type="password" id="wachtwoord" name="wachtwoord" placeholder="Wachtwoord" required>
        <button type="submit" name="login_medewerker">Inloggen</button>
    </form>
</section>
    </div>
    <footer>
        <?php require_once 'footer.php'; ?>
    </footer>
</body>
</html>
