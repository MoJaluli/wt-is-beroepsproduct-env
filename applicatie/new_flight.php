<?php

require_once 'db_connectie.php';
require_once 'sanitize.php';

try {
    $db = maakVerbinding();
} catch (PDOException $e) {
    die("Error connecting to database: " . $e->getMessage());
}

// Handle search functionality
$searchQuery = "";
if (isset($_POST['search'])) {
    $searchTerm = sanitize($_POST['search']);
    $searchQuery = "WHERE bestemming LIKE '%$searchTerm%' OR vluchtnummer LIKE '%$searchTerm%'";
}

try {
    $query = "SELECT vluchtnummer, bestemming, gatecode, vertrektijd FROM Vlucht $searchQuery";
    $data = $db->query($query);
} catch (PDOException $e) {
    die("Error executing query: " . $e->getMessage());
}


$vlucht_table = '<table id="vluchten" class="vluchtentabel">';
$vlucht_table .= '<thead><tr><th>Vluchtnummer</th><th>Bestemming</th><th>Gate</th><th>Vertrektijd</th></tr></thead>';
$vlucht_table .= '<tbody>';

while ($rij = $data->fetch(PDO::FETCH_ASSOC)) {
    $vluchtnummer = htmlspecialchars($rij['vluchtnummer']); 
    $bestemming = htmlspecialchars($rij['bestemming']);
    $gatecode = htmlspecialchars($rij['gatecode']);
    $vertrektijd = htmlspecialchars($rij['vertrektijd']);

    $vlucht_table .= '<tr><td>' . $vluchtnummer . '</td><td>' . $bestemming . '</td><td>' . $gatecode . '</td><td>' . $vertrektijd . '</td></tr>';
}

$vlucht_table .= '</tbody>';
$vlucht_table .= '</table>';
