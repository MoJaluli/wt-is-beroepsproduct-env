<?php
session_start();
require_once 'sanitize.php';
require_once '../db_connectie.php';

// Check if the user is already logged in
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']) {
    $_SESSION['error']['global'] = 'U bent al ingelogd.';
    header('Location: index.php');
    exit();
}

// Initialize the session error array
$_SESSION['error'] = [];

// Process passenger login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['passagier'])) {
    $passagier = sanitize($_POST['passagier']);
    $wachtwoord = sanitize($_POST['wachtwoord']);

    if (empty($passagier) || empty($wachtwoord)) {
        $_SESSION['error']['login'] = "Vul alstublieft uw gebruikersnaam en wachtwoord in.";
    } else {
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
           
            $_SESSION['error']['login'] = "Er is een fout opgetreden. Probeer het later opnieuw.";
        }
    }

    header('Location: 404.php'); // Redirect to error page if login fails
    exit();
}

// Process employee login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['balienummer'])) {
    $balienummer = sanitize($_POST['balienummer']);
    $wachtwoord = sanitize($_POST['wachtwoord']);

    if (empty($balienummer) || empty($wachtwoord)) {
        $_SESSION['error']['login'] = "Vul alstublieft uw gebruikersnaam en wachtwoord in.";
    } else {
        try {
            $conn = maakVerbinding();
            $sql = "SELECT balienummer, wachtwoord FROM Balie WHERE balienummer = :balienummer";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':balienummer', $balienummer, PDO::PARAM_STR);
            $stmt->execute();
            $employee = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($employee && password_verify($wachtwoord, $employee['wachtwoord'])) {
                // Set session variables and redirect to employee portal
                $_SESSION['loggedIn'] = true;
                $_SESSION['username'] = $balienummer;
                header("Location: employee.php");
                exit();
            } else {
                $_SESSION['error']['login'] = "Ongeldige inloggegevens voor medewerker.";
            }
        } catch (PDOException $e) {
            logError("Database error: " . $e->getMessage());
            $_SESSION['error']['login'] = "Er is een fout opgetreden. Probeer het later opnieuw.";
        }
    }

    header('Location: 404.php'); // Redirect to error page if login fails
    exit();
}
?>
