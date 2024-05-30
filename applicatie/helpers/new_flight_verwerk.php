<?php
require_once 'db_connectie.php';
require_once 'sanitize.php';

// Controleer of alle vereiste velden zijn ingevuld
if (isset($_POST['vluchtnummer'], $_POST['bestemming'], $_POST['gatecode'], $_POST['vertrektijd'])) {
    // Haal de formuliergegevens op en zorg ervoor dat ze veilig zijn om in de database in te voegen
    $vluchtnummer = sanitize($_POST['vluchtnummer']);
    $bestemming = sanitize($_POST['bestemming']);
    $gatecode = sanitize($_POST['gatecode']);
    

    // Formatteer de vertrektijd in het juiste SQL Server-tijdsformaat
    $vertrektijd = date('Y-m-d H:i:s', strtotime($_POST['vertrektijd']));

    try {
        // Maak verbinding met de database
        $db = maakVerbinding();

        // Bereid de SQL-query voor om een nieuwe vlucht toe te voegen
        $query = "INSERT INTO Vlucht (vluchtnummer, bestemming, gatecode, vertrektijd) VALUES (:vluchtnummer, :bestemming, :gatecode, :vertrektijd)";

        // Maak de query klaar voor uitvoering
        $stmt = $db->prepare($query);

        // Voer de query uit met de opgegeven gegevens
        $stmt->execute(array(':vluchtnummer' => $vluchtnummer, ':bestemming' => $bestemming, ':gatecode' => $gatecode, ':vertrektijd' => $vertrektijd));

        // Geef een succesbericht weer
        echo "Nieuwe vlucht is succesvol toegevoegd.";

    } catch (PDOException $e) {
        // Vang eventuele fouten op bij het uitvoeren van de query en geef een foutmelding weer
        die("Error executing query: " . $e->getMessage());
    }
} else {
    // Als niet alle vereiste velden zijn ingevuld, geef dan een foutmelding weer
    echo "Alle velden zijn verplicht.";
}
?>
