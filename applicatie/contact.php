<?php
require_once 'db_connectie.php';
require_once 'sanitize.php'
;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Mijn Klikbare Prototype</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Checkin Gelre</h1>
        <nav>
            <ul>
                <li><a href="Home.php">Startpagina</a></li>
                <li><a href="new_flight.php">Nieuwe Vlucht</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
    </header>

    <section id="content">
        <h2>Contact</h2>
        <p>Neem contact met ons op via onderstaand formulier.</p>
        
        <form>
            <label for="name">Naam:</label>
            <input type="text" id="name" name="name" required>

            <label for="Lastname">Achternaam:</label>
            <input type="text" id="Lastname" name="Lastname" required>
            

            <label for="Reserveringsnummer">Reserveringsnummer:</label>
            <input type="text" id="Reserveringsnummer" name="Reserveringsnummer" required>

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>

            <label for="message">Bericht:</label>
            <textarea id="message" name="message" rows="4" required></textarea>

            <button type="submit">Verstuur</button>
        </form>
    </section>

    <footer>
        <p>&copy; 2023 Mijn Checkin Gelre. Alle rechten voorbehouden.</p>
    </footer>
</body>
</html>
