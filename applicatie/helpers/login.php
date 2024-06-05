<?php
require_once '../db_connectie.php';
$melding = '';

if (isset($_POST['login'])) {
    $gebruikersnaam = htmlspecialchars(trim($_POST['gebruikersnaam']));
    $wachtwoord = htmlspecialchars(trim($_POST['wachtwoord']));
    try {
        $db = maakVerbinding();

        $sql = "SELECT wachtwoordhash
        FROM Gebruiker
        WHERE gebruikersnaam = :var_gebruikersnaam";
        
        $query = $db->prepare($sql);

        $data = [
            'var_gebruikersnaam' => $gebruikersnaam,
          ];
    
        $query->execute($data);

        if ($rij = $query->fetch()) {
            //gebruiker gevonden
            $passwordhash = $rij['wachtwoordhash'];
            if (password_verify($wachtwoord, $passwordhash)) {
                session_start();
                // header('location: index.php');
                $_SESSION['gebruiker'] = $gebruikersnaam;
                header("Location: ./index.php");
            } else {
                $melding = "<p class='error-msg'>fout: incorrecte inloggegevens!</p>";
            }
        } else {
            $melding = "<p class='error-msg'>fout: incorrecte inloggegevens!</p>";
        }
        


    }catch(PDOException $e) {
        $melding = "<p class='error-msg'>Er is iets misgegaan. Neem contact op met de systeembeheerder.</p>" . $e->getMessage();
      }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" type="text/css" href="../css/style.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8" />
    <title>Gelre airport</title>
</head>

<body>
    <header>
        <input type="checkbox" id="menu-toggle" />
        <label for="menu-toggle"></label>
        <nav>
            <ul>
              <li><a href="./index.php">Home</a></li>
              <li><a href="./Kofferinchecken.php">Koffer inchecken</a></li>
              <li><a href="./Login.php">Log in voor medewerkers</a></li>
            </ul>
          </nav>
        <a>Log in</a>
    </header>

    <main>
    <?php if (isset($error)) { ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php } ?>
            <main>
    <?php echo $melding ?>
        <form action="./login.php" method="post">

            <label for="gebruikersnaam">Gebruikersnaam: </label>
            <input type="text" name="gebruikersnaam" id="gebruikersnaam" placeholder="Gebruikersnaam">

            <label for="wachtwoord">Wachtwoord: </label>
            <input type="password" name="wachtwoord" id="wachtwoord" placeholder="Wachtwoord">

            <input type="submit" name="login" value="Log in">

        </form>
        <a href="./registreren.php">registreren</a>
    </main>

    <footer>
        <p>&copy; 2023 Gelre Airport. All rights reserved.</p>
    </footer>
</body>

</html>