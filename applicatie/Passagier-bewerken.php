<?php
require_once 'helpers/db_connectie.php';
require_once 'helpers/sanitize.php';

// Controleer of er een passagiernummer is meegegeven via GET
if (!isset($_GET['id'])) {
    die("Passagiernummer niet gevonden.");
}

// Verbinding maken met de database
try {
    $db = maakVerbinding();
} catch (PDOException $e) {
    die("Error connecting to database: " . $e->getMessage());
}

// Haal passagiersgegevens op
$passagiernummer = sanitize($_GET['id']);

// Zoek de passagier op basis van het passagiernummer
try {
    $query = "SELECT passagiernummer, naam, vluchtnummer, geslacht, balienummer, stoel FROM Passagier WHERE passagiernummer = :passagiernummer";
    $stmt = $db->prepare($query);
    $stmt->execute([':passagiernummer' => $passagiernummer]);
    $passagier = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error executing query: " . $e->getMessage());
}

// Controleer of de passagier bestaat
if (!$passagier) {
    die("Passagier niet gevonden.");
}

// Controleer of het formulier is ingediend
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verwerk het formulier hier, bijvoorbeeld:
    $naam = sanitize($_POST["naam"]);
    $vluchtnummer = sanitize($_POST["vluchtnummer"]);
    $geslacht = sanitize($_POST["geslacht"]);
    $balienummer = sanitize($_POST["balienummer"]);
    $stoel = sanitize($_POST["stoel"]);

    try {
        // Update de passagiersgegevens in de database
        $query = "UPDATE Passagier SET naam = :naam, vluchtnummer = :vluchtnummer, geslacht = :geslacht, balienummer = :balienummer, stoel = :stoel WHERE passagiernummer = :passagiernummer";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':naam' => $naam,
            ':vluchtnummer' => $vluchtnummer,
            ':geslacht' => $geslacht,
            ':balienummer' => $balienummer,
            ':stoel' => $stoel,
            ':passagiernummer' => $passagiernummer
        ]);
        // Voeg een mooie melding toe om aan te geven dat het bijwerken is gelukt
        $success_message = "Passagiersgegevens zijn succesvol bijgewerkt.";
    } catch (PDOException $e) {
        die("Error updating passagier: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bewerk Passagier</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .success-message {
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<header>
        <h1>GelreAirport</h1>
        <nav>
            <ul>
                <li><a href="medewerker.php">Medewerkers pagina</a></li>
                <li><a href="uitlog.php">Uitloggen</a></li>
            </ul>
        </nav>
    </header>
    


<section class="container">
    <?php if (isset($success_message)) : ?>
        <div class="success-message"><?php echo $success_message; ?></div>
    <?php endif; ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $passagiernummer); ?>">
        <div class="form-group">
            <label for="naam">Naam:</label>
            <input type="text" id="naam" name="naam" value="<?php echo htmlspecialchars($passagier['naam']); ?>" required>
        </div>
        <div class="form-group">
            <label for="vluchtnummer">Vluchtnummer:</label>
            <input type="text" id="vluchtnummer" name="vluchtnummer" value="<?php echo htmlspecialchars($passagier['vluchtnummer']); ?>" required>
        </div>
        <div class="form-group">
            <label for="geslacht">Geslacht:</label>
            <input type="text" id="geslacht" name="geslacht" value="<?php echo htmlspecialchars($passagier['geslacht']); ?>">
        </div>
        <div class="form-group">
            <label for="balienummer">Balienummer:</label>
            <input type="text" id="balienummer" name="balienummer" value="<?php echo htmlspecialchars($passagier['balienummer']); ?>">
        </div>
        <div class="form-group">
            <label for="stoel">Stoel:</label>
            <input type="text" id="stoel" name="stoel" value="<?php echo htmlspecialchars($passagier['stoel']); ?>">
        </div>
        <div class="form-group">
            <input type="submit" value="Opslaan">
        </div>
    </form>
</section>

<?php require_once 'sub/footer.php'; ?>

</body>
</html>
