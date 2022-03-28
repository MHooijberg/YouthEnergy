<?php
include_once "rbac_permissions_support.inc.php";

$neededPermissions = array();
$neededPermissions[] = "ReadOwnKlantgegevens";
$neededPermissions[] = "EditOwnKlantgegevens";
ApproveOrRedirect($neededPermissions, true);

$user = 'website';       ///< the username to connect to the database
$pass = 'wachtwoord';    ///< the password to connect to the database
$connection = new PDO('mysql:host=localhost;dbname=energy', $user, $pass); ///< make the connection
$klantnummer = $_SESSION['klantnummer'];

$KLANT_READ_KLANTGEGEVENS = $connection->prepare("
SELECT k_voornaam as voornaam,
       k_achternaam as achternaam,
       k_klantnummer as klantnummer,
       a_straatnaam as straatnaam,
       a_huisnummer as huisnummer,
       a_postcode as postcode,
       a_plaatsnaam as plaatsnaam,
       a_gemeente as gemeente,
       a_provincie as provincie,
       a_regio as regio
FROM tbl_klanten
    JOIN tbl_adressen ta on tbl_klanten.k_fk_idAdres = ta.a_idAdres
WHERE k_klantnummer = :klantnummer");
$KLANT_READ_KLANTGEGEVENS->bindParam(':klantnummer', $klantnummer);
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
WHERE k_klantnummer = :klantnummer");
$KLANT_UPDATE_VOORNAAM->bindParam(':voornaam', $input_voornaam);
$KLANT_UPDATE_VOORNAAM->bindParam(':klantnummer', $klantnummer);
$KLANT_UPDATE_VOORNAAM->execute();

//klant aanpassen achternaam
//Gebruik van parameters
if (isset($_POST['get_achternaam'])) {
    $input_achternaam = $_POST['achternaam'];
}
$KLANT_UPDATE_ACHTERNAAM = $connection->prepare("
UPDATE tbl_klanten
SET k_achternaam = :achternaam
WHERE k_klantnummer = :klantnummer");
$KLANT_UPDATE_ACHTERNAAM->bindParam(':achternaam', $input_achternaam);
$KLANT_UPDATE_ACHTERNAAM->bindParam(':klantnummer', $klantnummer);
$KLANT_UPDATE_ACHTERNAAM->execute();

if (isset($_POST['get_adresgegevens'])) {
    $input_straatnaam = $_POST['straatnaam'];
    $input_postcode = $_POST['postcode'];
    $input_plaatsnaam = $_POST['plaatsnaam'];
    $input_gemeente = $_POST['gemeente'];
    $input_provincie = $_POST['provincie'];
    $input_regio = $_POST['regio'];
    $input_huisnummer = $_POST['huisnummer'];
}
$KLANT_UPDATE_ADRESGEGEVENS = $connection->prepare("
UPDATE tbl_adressen
JOIN tbl_klanten
    on tbl_adressen.a_idAdres = tbl_klanten.k_fk_idAdres
SET a_straatnaam = :straatnaam,
    a_huisnummer = :huisnummer,
    a_postcode = :postcode,
    a_plaatsnaam = :plaatsnaam,
    a_regio = :regio,
    a_provincie = :provincie,
    a_gemeente = :gemeente
WHERE k_klantnummer= :klantnummer");
$KLANT_UPDATE_ADRESGEGEVENS->bindParam(':straatnaam', $input_straatnaam);
$KLANT_UPDATE_ADRESGEGEVENS->bindParam(':huisnummer', $input_huisnummer);
$KLANT_UPDATE_ADRESGEGEVENS->bindParam(':postcode', $input_postcode);
$KLANT_UPDATE_ADRESGEGEVENS->bindParam(':plaatsnaam', $input_plaatsnaam);
$KLANT_UPDATE_ADRESGEGEVENS->bindParam(':regio', $input_regio);
$KLANT_UPDATE_ADRESGEGEVENS->bindParam(':provincie', $input_provincie);
$KLANT_UPDATE_ADRESGEGEVENS->bindParam(':gemeente', $input_gemeente);
$KLANT_UPDATE_ADRESGEGEVENS->bindParam(':klantnummer', $klantnummer);
$KLANT_UPDATE_ADRESGEGEVENS->execute();

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
                <a href="verbruiksmeter.php">
                    <input type="button"
                           class="btn btn-primary btn-lg m-3"
                           value="Aanvragen">
                </a>
            </div>
        </div>

        <div class="row d-flex justify-content-center p-3">
            <div class="col-6 d-flex flex-column border border-dark rounded w-25 m-3">
                <h2>Dit ben jij</h2>
                <?php
                echo '<p> Voornaam: ' . $result_KLANT_READ_KLANTGEGEVENS[0]['voornaam'] . '</p>';
                echo '<p> Achternaam: ' . $result_KLANT_READ_KLANTGEGEVENS[0]['achternaam'] . '</p>';
                echo '<p> Email: ' . $_SERVER["AUTHENTICATE_UID"] . "</p>";
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
                               value="aanpassen"
                               class="btn btn-secondary">
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
                               value="aanpassen"
                               class="btn btn-secondary">
                    </div>
                </form>
            </div>
        </div>

        <div class="row d-flex justify-content-center p-3">
            <div class="col-6 d-flex flex-column border border-dark rounded w-25 m-3">
                <h2>Jouw adresgegevens</h2>
                <?php
                $straatnaam = $result_KLANT_READ_KLANTGEGEVENS[0]['straatnaam'];
                $huisnummer = $result_KLANT_READ_KLANTGEGEVENS[0]['huisnummer'];

                echo '<p> Adres: ' . $straatnaam . " " . $huisnummer . '</p>';
                echo '<p> Postcode: ' . $result_KLANT_READ_KLANTGEGEVENS[0]['postcode'] . '</p>';
                echo '<p> Plaatsnaam: ' . $result_KLANT_READ_KLANTGEGEVENS[0]['plaatsnaam'] . '</p>';
                echo '<p> Gemeente: ' . $result_KLANT_READ_KLANTGEGEVENS[0]['gemeente'] . '</p>';
                echo '<p> Provincie: ' . $result_KLANT_READ_KLANTGEGEVENS[0]['provincie'] . '</p>';
                echo '<p> Regio: ' . $result_KLANT_READ_KLANTGEGEVENS[0]['regio'] . '</p>';
                ?>
            </div>
            <div class="col-6 d-flex flex-column border border-dark rounded w-25 m-3">
                <h2>Aanpassen</h2>
                <form method="post">
                    <div class="form-group w-50">
                        <label for="straatnaam">Straatnaam: </label>
                        <input type="text"
                               id="straatnaam"
                               name="straatnaam">

                        <label for="huisnummer">huismueer: </label>
                        <input type="text"
                               id="huisnummer"
                               name="huisnummer">

                        <label for="postcode">Postcode: </label>
                        <input type="text"
                               id="postcode"
                               name="postcode">

                        <label for="plaatsnaam">Plaatsnaam: </label>
                        <input type="text"
                               id="plaatsnaam"
                               name="plaatsnaam">

                        <label for="gemeente">Gemeente: </label>
                        <input type="text"
                               id="gemeente"
                               name="gemeente">

                        <label for="provincie">Provincie: </label>
                        <input type="text"
                               id="provincie"
                               name="provincie">

                        <label for="regio">Regio: </label>
                        <input type="text"
                               id="regio"
                               name="regio">

                        <input type="submit"
                               name="get_adresgegevens"
                               value="aanpassen"
                               class="btn btn-secondary m-1">
                    </div>
                </form>
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