<?php
$user = 'website';       ///< the username to connect to the database
$pass = 'wachtwoord';    ///< the password to connect to the database
$connection = new PDO('mysql:host=localhost;dbname=energy', $user, $pass); ///< make the connection

$KLANT_READ_KLANTGEGEVENS = $connection->prepare("
SELECT k_voornaam as voornaam,
       k_achternaam as achternaam,
       k_klantnummer as klantnummer,
       a_straatnaam as straatnaam,
       a_huisnummer as huisnummer,
       a_postcode as postcode,
       a_plaatsnaam as plaatsnaam
FROM tbl_klanten
    JOIN tbl_adressen ta on tbl_klanten.k_fk_idAdres = ta.a_idAdres
WHERE k_klantnummer = 2644654722");
//$KLANT_READ_KLANTGEGEVENS->bindParam();
$KLANT_READ_KLANTGEGEVENS->execute();
$result_KLANT_READ_KLANTGEGEVENS = $KLANT_READ_KLANTGEGEVENS->fetchAll();

//Klant aanpassen voornaam
//Gebruik van parameters
if (isset($_POST['get_voornaam'])) {
    $input_voornaam = $_POST['voornaam'];
}
$KLANT_UPDATE_VOORNAAM = $connection->prepare("
UPDATE tbl_klanten
SET k_voornaam = :voornaam
WHERE k_klantnummer = '2644654722'");
$KLANT_UPDATE_VOORNAAM->bindParam(':voornaam', $input_voornaam);
$KLANT_UPDATE_VOORNAAM->execute();

//klant aanpassen achternaam
//Gebruik van parameters
if (isset($_POST['get_achternaam'])) {
    $input_achternaam = $_POST['achternaam'];
}
$KLANT_UPDATE_ACHTERNAAM = $connection->prepare("
UPDATE tbl_klanten
SET k_achternaam = :achternaam
WHERE k_klantnummer = '2644654722'");
$KLANT_UPDATE_ACHTERNAAM->bindParam(':achternaam', $input_achternaam);
$KLANT_UPDATE_ACHTERNAAM->execute();

//$KLANT_UPDATE_WOONGEGEVENS =
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My Energy Portal</title>
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="/bootstrap/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<? include_once '../partials/navbarIndex.php'; ?>
<main>
    <div class="container-fluid">

        <div class="row d-flex justify-content-center p-3">
            <div class="col-12 d-flex flex-column border border-dark rounded w-50">
                <h2>Mijn meterstanden aanvragen</h2>
                <p>vraag hier je meterstanden aan</p>
                <input type="button"
                       class="btn btn-primary btn-lg m-3"
                       value="Aanvragen">
            </div>
        </div>

        <div class="row d-flex justify-content-center p-3">
            <div class="col-6 d-flex flex-column border border-dark rounded w-25 m-3">
                <h2>Dit ben jij</h2>
                <?php
                echo '<p> Voornaam: ' . $result_KLANT_READ_KLANTGEGEVENS[0]['voornaam'] . '</p>';
                echo '<p> Achternaam: ' . $result_KLANT_READ_KLANTGEGEVENS[0]['achternaam'] . '</p>';
                echo '<p> Email: ';
                echo '<p> Klantnummer: ' . $result_KLANT_READ_KLANTGEGEVENS[0]['klantnummer'] . '</p>';
                ?>
            </div>
            <div class="col-6 d-flex flex-column border border-dark rounded w-25 m-3">
                <h2>Aanpassen</h2>
                <form method="post">
                    <div class="form-group">
                        <label for="voornaam">Voornaam</label><br>
                        <input type="text"
                               id="voornaam"
                               name="voornaam">
                        <input type="submit"
                               name="get_voornaam"
                               value="aanpassen">
                    </div>
                </form>
                <form method="post">
                    <div class="form-group">
                        <label for="achternaam">Achternaam</label><br>
                        <input type="text"
                               id="achternaam"
                               name="achternaam">
                        <input type="submit"
                               name="get_achternaam"
                               value="aanpassen">
                    </div>
                </form>
            </div>
        </div>

        <div class="row d-flex justify-content-center p-3">
            <div class="col-12 d-flex flex-column border border-dark rounded w-50">
                <h2>Jouw adresgegevens</h2>
                <?php
                $straatnaam = $result_KLANT_READ_KLANTGEGEVENS[0]['straatnaam'];
                $huisnummer = $result_KLANT_READ_KLANTGEGEVENS[0]['huisnummer'];

                echo '<p> Adres: ' . $straatnaam . " " . $huisnummer . '</p>';
                echo '<p> Postcode: ' . $result_KLANT_READ_KLANTGEGEVENS[0]['postcode'] . '</p>';
                echo '<p> Plaatsnaam: ' . $result_KLANT_READ_KLANTGEGEVENS[0]['plaatsnaam'] . '</p>';
                ?>
            </div>
        </div>

        <div class="row d-flex justify-content-center p-3">
            <div class="col-12 d-flex flex-column border border-dark rounded w-50">
                <h3>Wilt u uw persoonsgegevens laten verwijderen?</h3>
                <p>bel met klantenservice</p>
                <p>Wytse Hofstra: 06 112345678</p>
                <p>Beschikbaar: ma-vr van 10.00 tot 11.00</p>
            </div>
        </div>

    </div> <!--Container closing tag-->
</main>

<? include_once '../partials/footer.php'; ?>
</body>
</html>