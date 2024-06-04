<?php
require_once 'sanitize.php';
require_once '../db_connectie.php';

session_start();

// Check if the user is already logged in
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']) {
    $_SESSION['error']['global'] = 'U bent al ingelogd.';
    header('Location: home.php');
    exit();
}

// Initialize the session error array
$_SESSION['error'] = [];

// Process passenger login
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['passagier'], $_GET['wachtwoord'])) {
    $passagier = sanitize($_GET['passagier']);
    $wachtwoord = sanitize($_GET['wachtwoord']);

    $conn = maakVerbinding();
    $sql = "SELECT passagiernummer, wachtwoord FROM Passagier";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $passengerData = $stmt->fetchAll(PDO::FETCH_ASSOC);

   
    foreach ($passengerData as $passenger) {
        if (password_verify($wachtwoord, $passenger['wachtwoord'])) {
            // Redirect to passenger portal
            $_SESSION['loggedIn'] = true;
            $_SESSION['username'] = $passagier;
            header("Location: passenger.php?code=" . urlencode($passagier));
            exit();
        }
    }

    $_SESSION['error']['login'] = "Ongeldige inloggegevens voor passagier.";
    header('Location: home.php'); // Redirect to home page if login fails
    exit();

}


// Process employee login
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['ballienummer'], $_GET['wachtwoord'])) {
    $ballienummer = sanitize($_GET['ballienummer']);
    $wachtwoord = sanitize($_GET['wachtwoord']);

    $conn = maakVerbinding();
    $sql = "SELECT balienummer, wachtwoord FROM Balie";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $employeeData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($employeeData as $employee) {
        if (password_verify($wachtwoord, $employee['wachtwoord'])) {
            // Redirect to employee portal
            $_SESSION['loggedIn'] = true;
            $_SESSION['username'] = $ballienummer;
            header("Location: employee.php?code=" . urlencode($ballienummer));
            exit();
        }
    }

    $_SESSION['error']['login'] = "Ongeldige inloggegevens voor medewerker.";
    header('Location: home.php'); // Redirect to home page if login fails
    exit();
}   
?>
