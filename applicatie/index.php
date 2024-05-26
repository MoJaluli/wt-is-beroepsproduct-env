<?php
    require_once 'db_connectie.php';
    session_start();
    
    ?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkin Gelre - Home</title>
    <link rel="stylesheet" href="style.css">
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

    <section>
        <h2>Welkom bij Checkin Gelre</h2>
        <p>Uw oplossing voor inchecken en vluchtinformatie op het vliegveld.</p>
    </section>

    <section>
        <h2>Kies je rol:</h2>
        <form action="passenger.php" method="get">
            <label for="passenger">Passagier</label>
            <input type="radio" id="passenger" name="userType" value="passenger" required>
            <label for="passengerCode">Code:</label>
            <input type="text" id="passengerCode" name="passengerCode" required>
            <button type="submit">Ga naar je pagina</button>
        </form>

        <form action="employee.php" method="get">
            <label for="employee">Medewerker</label>
            <input type="radio" id="employee" name="userType" value="employee" required>
            <label for="employeeCode">Code:</label>
            <input type="text" id="employeeCode" name="employeeCode" required>
            <button type="submit">Ga naar je pagina</button>
        </form>
    </section>

    <img id="gelre-image" src="Tarjeta-de-embarque1.jpg" alt="Checkin Gelre Afbeelding">

    <footer>
        <p>&copy; 2023 Checkin Gelre. Alle rechten voorbehouden.</p>
    </footer>
</body>
</html>