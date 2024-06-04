<?php
require_once 'sanitize.php';


?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkin Gelre - Home</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
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

    <section>
        <h2>Welkom bij Checkin Gelre</h2>
        <p>Uw oplossing voor inchecken en vluchtinformatie op het vliegveld.</p>
    </section>
    
    <div class="login-sections">
        <section class="login-section">
            <h2>Passagier Inloggen</h2>
            <?php if (isset($error)) { ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php } ?>
            <form class="text-center" method="post" action="helpers/login.php">
            <form action="passenger.php" method="get">
                <label for="passagier">Passagier</label>
                <input type="text" id="passagier" name="passagier" required>
                <label for="wachtwoord">Wachtwoord:</label>
                <input type="text" id="wachtwoord" name="wachtwoord" required>
                <button type="submit">Inloggen</button>
            </form>
        </section>
        
        <section class="login-section">
            <h2>Medewerker Inloggen</h2>
            <?php if (isset($error)) { ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php } ?>
            <form class="text-center" method="post" action="helpers/login.php">
            <form action="employee.php" method="get">
                <label for="ballienummer">Ballienummer:</label>
                <input type="text" id="ballienummer" name="ballienummer" required>
                <label for="wachtwoord">Wachtwoord:</label>
                <input type="text" id="wachtwoord" name="wachtwoord" required>
                <button type="submit">Inloggen</button>
            </form>
        </section>
    </div>

    <footer>
        <?php
        require_once 'footer.php';
        ?>
    </footer>
</body>
</html>