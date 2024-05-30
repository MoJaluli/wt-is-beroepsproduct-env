<?php

require_once "../functions/input.php";
require_once "../dbConnection.php";

if (!isset($_SESSION)) {
    session_start();
}

if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']) {
    $_SESSION['error']['global'] = 'U bent al ingelogd.';
    header('Location: ../../index.php');
    exit();
}

if (empty($_SESSION['loginData'])) {
    $_SESSION['loginData'] = [];
}

$_SESSION['error'] = [];

$username = isset($_POST['username']) ? sanitize($_POST['username']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$recaptcha = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';


$_SESSION['loginData']['username'] = $username;

if (!empty($_SESSION['error'])) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

$sql = "SELECT p.passagiernummer, p.wachtwoord
        FROM Passagier p
        WHERE p.passagiernummer = :username";

$query = $conn->prepare($sql);
$query->execute([':username' => $username]);
$result = $query->fetch(PDO::FETCH_ASSOC);

if (empty($result) || !password_verify($password, $result['wachtwoord'])) {
    $_SESSION['error']['username'] = "De combinatie van gebruikersnaam en wachtwoord is onjuist.";
}

if (!empty($_SESSION['error'])) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

unset($_SESSION['loginData']);
unset($_SESSION['error']);

$_SESSION['loggedIn'] = true;
$_SESSION['username'] = $result['passagiernummer'];

header('Location: ../../index.php');
exit();
?>
