<?php
require_once 'helpers/db_connectie.php';
require_once 'helpers/sanitize.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['ingelogd']) || $_SESSION['ingelogd'] !== true) {
    header("Location: employee.php");
    exit();
}

// Connect to the database
$verbinding = maakVerbinding();

// Fetch all passengers
$sqlquery = "SELECT * FROM Passagier";
$query = $verbinding->prepare($sqlquery);
$query->execute();
$passagiers = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passagiers Overzicht</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Passagiers Overzicht</h1>
    </header>

    <main>
        <table border="1">
            <thead>
                <tr>
                    <th>Passagiernummer</th>
                    <th>Naam</th>
                    <th>Vluchtnummer</th>
                    <th>Geslacht</th>
                    <th>Balienummer</th>
                    <th>Stoel</th>
                    <th>Inchecktijdstip</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($passagiers as $passagier): ?>
                    <tr>
                        <td><?= htmlspecialchars($passagier['passagiernummer']) ?></td>
                        <td><?= htmlspecialchars($passagier['naam']) ?></td>
                        <td><?= htmlspecialchars($passagier['vluchtnummer']) ?></td>
                        <td><?= htmlspecialchars($passagier['geslacht']) ?></td>
                        <td><?= htmlspecialchars($passagier['balienummer']) ?></td>
                        <td><?= htmlspecialchars($passagier['stoel']) ?></td>
                        <td><?= htmlspecialchars($passagier['inchecktijdstip']) ?></td>
                        <td>
                            <form action="edit_passagier.php" method="get">
                                <input type="hidden" name="passagiernummer" value="<?= $passagier['passagiernummer'] ?>">
                                <button type="submit">Bewerken</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
