<?php
require_once 'sanitize.php';
require_once 'db_connectie.php';

session_start();

// Initialize the session error array
$_SESSION['error'] = [];

// Function to log errors for debugging
function logError($message) {
    error_log($message);

    if (isset($_SESSION['error'])) {
        $_SESSION['error']['global'] = $message;
    }

    header('Location: 404.php');
    exit();
}

// Meldingen voor login
$melding = '';

// Login-functionaliteit
if (isset($_POST['login'])) {
    $gebruikersnaam = htmlspecialchars(trim($_POST['gebruikersnaam']));
    $wachtwoord = htmlspecialchars(trim($_POST['wachtwoord']));
    try {
        $db = maakVerbinding();

        $sql = "SELECT wachtwoordhash
        FROM Gebruiker
        WHERE gebruikersnaam = :var_gebruikersnaam";

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
                $melding = "<p class='error-msg'>Fout: incorrecte inloggegevens!</p>";
            }
        } else {
            $melding = "<p class='error-msg'>Fout: incorrecte inloggegevens!</p>";
        }
    } catch (PDOException $e) {
        $melding = "<p class='error-msg'>Er is iets misgegaan. Neem contact op met de systeembeheerder.</p>" . $e->getMessage();
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
                <li><a href="new_flight.php">Nieuwe Vlucht</a></li>
                <li><a href="newpassenger.php">Nieuwe passagier</a></li>
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
            <?php if (isset($melding)) { echo $melding; } ?>
            <form class="text-center" method="POST" action="">
                <label for="gebruikersnaam">Gebruikersnaam:</label>
                <input type="text" id="gebruikersnaam" name="gebruikersnaam" placeholder="Gebruikersnaam" required>
                <label for="wachtwoord">Wachtwoord:</label>
                <input type="password" id="wachtwoord" name="wachtwoord" placeholder="Wachtwoord" required>
                <button type="submit" name="login">Inloggen</button>
            </form>
            <a href="registreren.php">registreren</a>
        </section>

        <section class="login-section">
            <h2>Medewerker Inloggen</h2>
            <?php if (isset($error)) { ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php } ?>
            <form class="text-center" method="POST" action="employee.php">
                <label for="ballienummer">Ballienummer:</label>
                <input type="text" id="ballienummer" name="ballienummer" placeholder="Ballienummer" required>
                <label for="wachtwoord">Wachtwoord:</label>
                <input type="password" id="wachtwoord" name="wachtwoord" placeholder="Wachtwoord" required>
                <button type="submit">Inloggen</button>
            </form>
        </section>
    </div>
    <footer>
        <?php
     require_once 'footer.php';
        ?>
    </footer>
</body>
</html>
