<?php
$db_host = 'database_server'; // de database server 
$db_name = 'GelreAirport';                    // naam van database

// defined in sql-script 'movies.sql'
$db_user    = 'sa';                 // db user
$db_password = 'abc123!@#';  // wachtwoord db user
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $verbinding = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die('Databaseverbinding mislukt: ' . $e->getMessage());
}
?>
