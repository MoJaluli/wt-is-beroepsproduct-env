<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medewerker Homepagina</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <header>
        <h1>Welkom, Medewerker!</h1>
        <nav>
            <ul>
                <li><a href="passenger-info.php">Passagiers</a></li>
                <li><a href="flights.php">Vluchten</a></li>
                <li><a href="add-flight.php">Vlucht Toevoegen</a></li>
                <li><a href="uitlog.php">Uitloggen</a></li>
            </ul>
        </nav>
    </header>

    <section class="container">
        <h2>Dashboard</h2>
        <p>Dit is de homepagina voor medewerkers van Checkin Gelre. Gebruik de navigatie hierboven om toegang te krijgen tot verschillende functies.</p>
    </section>

    <?php require_once 'sub/footer.php'; ?>
</body>

</html>
