<?php
require_once 'db_connectie.php';
require_once 'helpers/sanitize.php';
// Ensure this file contains the correct database connection details
$melding = '';

// Maak verbinding met de database
$db = maakVerbinding();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $passagiernummer = $_POST["passagiernummer"];
    $naam = $_POST["naam"];
    $vluchtnummer = $_POST["vluchtnummer"];
    $geslacht = $_POST["geslacht"];
    $balienummer = $_POST["balienummer"];
    $stoel = $_POST["stoel"];

    // Update de passagiersgegevens in de database
    $sql = "UPDATE Passagier SET 
                naam = ?, 
                vluchtnummer = ?, 
                geslacht = ?, 
                balienummer = ?, 
                stoel = ?, 
            WHERE passagiernummer = ?";
$stmt = $db->prepare($sql);
if ($stmt === false) {
    die("Error preparing statement: " . $db->errorInfo()[2]);
}

if ($stmt->execute()) {
    // Check if any rows were affected
    $affectedRows = $stmt->rowCount();
    if ($affectedRows > 0) {
        echo "Record updated successfully";
    } else {
        echo "No records were updated";
    }
} else {
    echo "Error updating record: " . $stmt->errorInfo()[2];
}

$stmt->closeCursor();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" type="text/css" href="css/style.re.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8" />
    <title>Gelre airport</title>
</head>

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


<h2>Update Passagier Gegevens</h2>

<?php echo $melding; ?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
  <div class="form-group">
    <label for="passagiernummer">Passagiernummer:</label>
    <input type="number" name="passagiernummer" id="passagiernummer" required>
  </div>
  <div class="form-group">
    <label for="naam">Naam:</label>
    <input type="text" name="naam" id="naam" required>
  </div>
  <div class="form-group">
    <label for="vluchtnummer">Vluchtnummer:</label>
    <input type="number" name="vluchtnummer" id="vluchtnummer" required>
  </div>
  <div class="form-group">
    <label for="geslacht">Geslacht:</label>
    <input type="text" name="geslacht" id="geslacht">
  </div>
  <div class="form-group">
    <label for="balienummer">Balienummer:</label>
    <input type="number" name="balienummer" id="balienummer">
  <div class="form-group">
    <label for="stoel">Stoel:</label>
    <input type="text" name="stoel" id="stoel">
  </div>
  <div class="form-group">
  </div>
  <div class="form-group">
    <input type="submit" value="Update">
  </div>
</form>

</body>
</html>