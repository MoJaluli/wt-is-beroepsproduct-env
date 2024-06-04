<?php
require_once 'sanitize.php';
require_once 'db_connectie.php';
require_once 'login.php';


session_start();

// Initialize the session error array
$_SESSION['error'] = [];

// Function to log errors for debugging
function logError($message) {
    error_log($message);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['passagiernummer'], $_POST['wachtwoord'])) {
        $passagier = sanitize($_POST['passagiernummer']);
        $wachtwoord = sanitize($_POST['wachtwoord']);

        logError("Processing passenger login for: " . $passagier);

        try {
            $conn = maakVerbinding();
            $sql = "SELECT passagiernummer, wachtwoord FROM Passagier WHERE passagiernummer = :passagiernummer";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':passagiernummer', $passagier, PDO::PARAM_STR);
            $stmt->execute();
            $passenger = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($passenger && password_verify($wachtwoord, $passenger['wachtwoord'])) {
                // Set session variables and redirect to passenger portal
                $_SESSION['loggedIn'] = true;
                $_SESSION['username'] = $passagier;
                header("Location: passenger.php");
                exit();
            } else {
                $_SESSION['error']['login'] = "Ongeldige inloggegevens voor passagier.";
            }
        } catch (PDOException $e) {
            logError("Database error: " . $e->getMessage());
            $_SESSION['error']['login'] = "Er is een fout opgetreden. Probeer het later opnieuw.";
        }

        header('Location: 404.php'); // Redirect to home page if login fails
        exit();
    }

    // Process employee login
    if (isset($_POST['ballienummer'], $_POST['wachtwoord'])) {
        $ballienummer = sanitize($_POST['ballienummer']);
        $wachtwoord = sanitize($_POST['wachtwoord']);

        logError("Processing employee login for: " . $ballienummer);

        try {
            $conn = maakVerbinding();
            $sql = "SELECT balienummer, wachtwoord FROM Balie WHERE balienummer = :balienummer";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':balienummer', $ballienummer, PDO::PARAM_STR);
            $stmt->execute();
            $employee = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($employee && password_verify($wachtwoord, $employee['wachtwoord'])) {
                // Set session variables and redirect to employee portal
                $_SESSION['loggedIn'] = true;
                $_SESSION['username'] = $ballienummer;
                header("Location: employee.php");
                exit();
            } else {
                $_SESSION['error']['login'] = "Ongeldige inloggegevens voor medewerker.";
            }
        } catch (PDOException $e) {
            logError("Database error: " . $e->getMessage());
            $_SESSION['error']['login'] = "Er is een fout opgetreden. Probeer het later opnieuw.";
        }

        header('Location: 404.php'); // Redirect to home page if login fails
        exit();
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
                <li><a href="contact.php">Contact</a></li>
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
            <?php if (isset($error)) { ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php } ?>
            <form class="text-center" method="POST" action="login.php">
            <form action="passenger.php" method="get">
                <label for="passagier">Passagier</label>
                <input type="text" id="passagier" name="passagier" placeholder="gebruikersnaam"required>
                <label for="wachtwoord">Wachtwoord:</label>
                <input type="text" id="wachtwoord" name="wachtwoord" placeholder="wachtwoord" required>
                <button type="submit">Inloggen</button>
            </form>
        </section>
        
        <section class="login-section">
            <h2>Medewerker Inloggen</h2>
            <?php if (isset($error)) { ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php } ?>
            <form class="text-center" method="POST" action="helpers/login.php">
            <form action="employee.php" method="get">
                <label for="ballienummer">Ballienummer:</label>
                <input type="text" id="ballienummer" name="ballienummer" placeholder="ballienummer" required>
                <label for="wachtwoord">Wachtwoord:</label>
                <input type="text" id="wachtwoord" name="wachtwoord" placeholder="wachtwoord" required>
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