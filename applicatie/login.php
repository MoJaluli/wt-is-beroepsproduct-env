<?php
require_once 'sanitize.php';
require_once 'db_connectie.php';


// Check if the user is already logged in
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']) {
    $_SESSION['error']['global'] = 'U bent al ingelogd.';
    header('Location: index.php');
    exit();
}

// Initialize the session error array
$_SESSION['error'] = [];



// Process passenger login
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['passagiernummer'], $_GET['wachtwoord'])) {
    $passagier = sanitize($_GET['passagiernummer']);
    $wachtwoord = sanitize($_GET['wachtwoord']);

    logError("Processing passenger login for: " . $passagier);

    try {
        $conn = maakVerbinding();
        $sql = "SELECT passagiernummer, wachtwoord FROM Passagier WHERE passagiernummer = :passagiernummer";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':passagiernummer', $passagier, PDO::PARAM_STR);
        $stmt->execute();
        $passenger = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($passenger && password_verify($wachtwoord, $passenger['wachtwoord'])) {
            // Redirect to passenger portal
            $_SESSION['loggedIn'] = true;
            $_SESSION['username'] = $passagier;
            header("Location: passenger.php?code=" . urlencode($passagier));
            exit();
        } else {
            $_SESSION['error']['login'] = "Ongeldige inloggegevens voor passagier.";
        }
    } catch (PDOException $e) {
        logError("Database error: " . $e->getMessage());
        $_SESSION['error']['login'] = "Er is een fout opgetreden. Probeer het later opnieuw.";
    }

    header('Location: home.php'); // Redirect to home page if login fails
    exit();
}

// Process employee login
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['ballienummer'], $_GET['wachtwoord'])) {
    $ballienummer = sanitize($_GET['ballienummer']);
    $wachtwoord = sanitize($_GET['wachtwoord']);

    logError("Processing employee login for: " . $ballienummer);

    try {
        $conn = maakVerbinding();
        $sql = "SELECT balienummer, wachtwoord FROM Balie WHERE balienummer = :balienummer";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':balienummer', $ballienummer, PDO::PARAM_STR);
        $stmt->execute();
        $employee = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($employee && password_verify($wachtwoord, $employee['wachtwoord'])) {
            // Redirect to employee portal
            $_SESSION['loggedIn'] = true;
            $_SESSION['username'] = $ballienummer;
            header("Location: employee.php?code=" . urlencode($ballienummer));
            exit();
        } else {
            $_SESSION['error']['login'] = "Ongeldige inloggegevens voor medewerker.";
        }
    } catch (PDOException $e) {
        logError("Database error: " . $e->getMessage());
        $_SESSION['error']['login'] = "Er is een fout opgetreden. Probeer het later opnieuw.";
    }

    header('Location: home.php'); // Redirect to home page if login fails
    exit();
}
?>
