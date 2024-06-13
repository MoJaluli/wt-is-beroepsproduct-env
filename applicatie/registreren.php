<?php
require_once 'db_connectie.php';
$melding = '';

function CheckIfUserExists($gebruikersnaam)
{
    $db = maakVerbinding();
    // Voorbereiden insert into statement


    $sql = "select gebruikersnaam from gebruiker WHERE gebruikersnaam = :var_gebruikersnaam";

    $query = $db->prepare($sql);

    // Data voorbereiden

    $data = [
        'var_gebruikersnaam' => $gebruikersnaam,
    ];

    $query->execute($data);

    if ($query->rowCount() == 0) {
        return false;
    } else {
        return true;
    }
}

function validateInput($gebruikersnaam, $wachtwoord)
{
    global $melding;
    $fouten = [];
    if (strlen($gebruikersnaam) < 4) {
        $fouten[] = 'Gebruikersnaam minstens 4 karakters.';
    }

    if (strlen($wachtwoord) < 8) {
        $fouten[] = 'Wachtwoord minstens 8 karakters.';
    }
echo($gebruikersnaam. $wachtwoord);
    // 3. opslaan van de gegevens
    if (count($fouten) > 0) {
        $melding = "<div class='error-msg'>Er waren fouten in de invoer.<ul>";
        foreach ($fouten as $fout) {
            $melding .= "<li>$fout</li>";
        }
        $melding .= "</ul></div>";
        return false;
    } else {
        return true;
    }
}

if (isset($_POST['registeren'])) {
    $gebruikersnaam = htmlspecialchars(trim($_POST['gebruikersnaam']));
    $wachtwoord = htmlspecialchars(trim($_POST['wachtwoord']));

    if (!CheckIfUserExists($gebruikersnaam)){ 
        if(validateInput($gebruikersnaam, $wachtwoord)) {

       
        $passwordhash = password_hash($wachtwoord, PASSWORD_DEFAULT);
        try {
        // database
        $db = maakVerbinding();
        // Insert query (prepared statement)
        $sql = "INSERT INTO Gebruiker(gebruikersnaam, email, wachtwoordhash, gebruikersrol)
         values (:var_gebruikersnaam, 'fictief@email.nl', :var_passwordhash, 'admin')";
        echo ($sql);
        $query = $db->prepare($sql);

        // Send data to database
        $data_array = [
            'var_gebruikersnaam' => $gebruikersnaam,
            'var_passwordhash' => $passwordhash
        ];
        $succes = $query->execute($data_array);

        // Check results
        if ($succes) {
            $melding = "<p class='success-msg'>Gebruiker is geregistreerd</p>";
        }  else {
            $melding = "<p class='error-msg'>Registratie is mislukt</p>";
        }
    }catch(PDOException){
        $melding = "<p class='error-msg'>Er is iets misgegaan met het aanmaken van uw account.</p>";
    }

    }else{

    }
}else{
    $melding = "<p class='error-msg'>Gebruikersnaam bestaat al.</p>";
}
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

    <main>
        <?php echo $melding ?>
        <form action="registreren.php" method="post">

            <label for="gebruikersnaam">Gebruikersnaam: </label>
            <input type="text" name="gebruikersnaam" id="gebruikersnaam" placeholder="Gebruikersnaam">

            <label for="wachtwoord">Wachtwoord: </label>
            <input type="password" name="wachtwoord" id="wachtwoord" placeholder="Wachtwoord">

            <label for="email">Email: </label>
            <input type="email" name="email" id="email" placeholder="Email">

            <label for="gebruikersrol">Gebruikersrol: </label>
<div class="radio-group">
    <input type="radio" name="gebruikersrol" id="medewerker" value="medewerker">
    <label for="medewerker">Medewerker</label>
    <input type="radio" name="gebruikersrol" id="passagier" value="passagier">
    <label for="passagier">Passagier</label>
</div>



            <input type="submit" id="registeren" name="registeren" value="registeren">

        </form>
    </main>

        <?php
        require_once 'footer.php';
        ?>

</>

</html>