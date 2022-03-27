<?php
include_once "rbac_permissions_support.inc.php";

$neededPermissions = array();
$neededPermissions[] = "ReadKlantgegevensSingle";
$neededPermissions[] = "ReadMeetgegevensSingle";
ApproveOrRedirect($neededPermissions, true);

if (!isset($_SESSION["HasClient"])) {
    $_SESSION["HasClient"] = 0;
}

if (isset($_POST['done'])) {
    unset($_SESSION["adres"]);
    unset($_SESSION["user"]);
    unset($_SESSION["meter"]);

    $_SESSION["HasClient"] = 0;
}

if (isset($_POST['requestPH'])) {
    $postcode = $_POST['Postcode'];
    $huisnummer = $_POST['Huisnummer'];

    $user = 'website';       ///< the username to connect to the database
    $pass = 'wachtwoord';    ///< the password to connect to the database
    $connection = new PDO('mysql:host=localhost;dbname=energy', $user, $pass); ///< make the connection
    $KLANTENSERVICE_READ_METERSTANDEN =
        "SELECT ms_stand,m_product
    FROM tbl_meters_standen
    JOIN tbl_meter_telwerken
        on ms_fk_idMeterTelwerk = mt_idMeterTelwerk
    JOIN tbl_meters
        on mt_fk_idMeter = m_idMeter
    JOIN tbl_adressen 
        on m_fk_idAdres = a_idAdres
        WHERE a_postcode = :postcode 
            AND a_huisnummer = :huisnummer
            AND mt_type='v'";

    $statement = $connection->prepare($KLANTENSERVICE_READ_METERSTANDEN);
    $statement->bindValue(':postcode', $postcode);
    $statement->bindValue(':huisnummer', $huisnummer);
    $statement->execute();
    $meterstanden = $statement->fetchAll();

    if (!empty($meterstanden)){
        $_SESSION['meter'] = array();
        $g_total = 0;
        $e_total = 0;
        $g_count = 0;
        $e_count = 0;
        $ms_mid_e = 0;
        $ms_mid_g = 0;
        foreach ($meterstanden as $value){
            if ($value["m_product"] == 'G'){
                $g_total += $value["ms_stand"];
                $g_count++;
            }
            else if ($value["m_product"] == 'E'){
                $e_total += $value["ms_stand"];
                $e_count++;
            }
        }
        if ($e_count != 0){
            $ms_mid_e = intval($ms_mid = $e_total / $e_count);
        }
        if ($g_count != 0){
            $ms_mid_g = intval($ms_mid = $g_total / $g_count);
        }

        $_SESSION["meter"]["e_gemideld"] = $ms_mid_e;
        $_SESSION["meter"]["e_count"] = $e_count;
        $_SESSION["meter"]["g_gemideld"] = $ms_mid_g;
        $_SESSION["meter"]["g_count"] = $g_count;
        $_SESSION["HasClient"] = 1;
    }

    $KLANTENSERVICE_READ_KLANTGEGEVENS = "
        SELECT k_achternaam, k_voornaam, k_klantnummer, a_postcode, a_huisnummer, a_straatnaam
        FROM tbl_klanten
        JOIN tbl_adressen 
            on k_fk_idAdres = a_idAdres
        WHERE a_postcode = :postcode AND a_huisnummer = :huisnummer";

    $statement = $connection->prepare($KLANTENSERVICE_READ_KLANTGEGEVENS);
    $statement->bindValue(':postcode', $postcode);
    $statement->bindValue(':huisnummer', $huisnummer);
    $statement->execute();
    $user = $statement->fetch();

    if (!empty($meterstanden)) {
        $_SESSION["user"]["voornaam"] = $user["k_voornaam"];
        $_SESSION["user"]["achternaam"] = $user["k_achternaam"];
        $_SESSION["user"]["klantnummer"] = $user["k_klantnummer"];
        $_SESSION["user"]["postcode"] = $user["a_postcode"];
        $_SESSION["user"]["huisnummer"] = $user["a_huisnummer"];
        $_SESSION["user"]["straatnaam"] = $user["a_straatnaam"];
    }
    $connection = null;
    $statement = null;
}

if (isset($_POST['requestKN'])) {
    $klantnummer = $_POST['Klantnummer'];
$user = 'website';       ///< the username to connect to the database
$pass = 'wachtwoord';    ///< the password to connect to the database
$connection = new PDO('mysql:host=localhost;dbname=energy', $user, $pass); ///< make the connection
$KLANTENSERVICE_READ_METERSTANDEN =
    "SELECT ms_stand,m_product
    FROM tbl_meters_standen
    JOIN tbl_meter_telwerken
        on ms_fk_idMeterTelwerk = mt_idMeterTelwerk
    JOIN tbl_meters
        on mt_fk_idMeter = m_idMeter
    JOIN tbl_adressen 
        on m_fk_idAdres = a_idAdres
    JOIN tbl_klanten
        on  k_fk_idAdres = a_idAdres
    WHERE k_klantnummer = :klantnummer
    AND mt_type='v'";


$statement = $connection->prepare($KLANTENSERVICE_READ_METERSTANDEN);
$statement->bindValue(':klantnummer', $klantnummer);
$statement->execute();
$meterstanden = $statement->fetchAll();

if (!empty($meterstanden)){
    $_SESSION['meter'] = array();
    $g_total = 0;
    $e_total = 0;
    $g_count = 0;
    $e_count = 0;
    $ms_mid_e = 0;
    $ms_mid_g = 0;
    foreach ($meterstanden as $value){
        if ($value["m_product"] == 'G'){
            $g_total += $value["ms_stand"];
            $g_count++;
        }
        else if ($value["m_product"] == 'E'){
            $e_total += $value["ms_stand"];
            $e_count++;
        }
    }
    if ($e_count != 0){
        $ms_mid_e = intval($ms_mid = $e_total / $e_count);
    }
    if ($g_count != 0){
        $ms_mid_g = intval($ms_mid = $g_total / $g_count);
    }

    $_SESSION["meter"]["e_gemideld"] = $ms_mid_e;
    $_SESSION["meter"]["e_count"] = $e_count;
    $_SESSION["meter"]["g_gemideld"] = $ms_mid_g;
    $_SESSION["meter"]["g_count"] = $g_count;
    $_SESSION["HasClient"] = 1;
}
    $KLANTENSERVICE_READ_KLANTGEGEVENS = "
        SELECT k_achternaam, k_voornaam, k_klantnummer, a_postcode, a_huisnummer, a_straatnaam
        FROM tbl_klanten
        JOIN tbl_adressen 
            on k_fk_idAdres = a_idAdres
        WHERE k_klantnummer = :klantnummer";

    $statement = $connection->prepare($KLANTENSERVICE_READ_KLANTGEGEVENS);
    $statement->bindValue(':klantnummer', $klantnummer);
    $statement->execute();
    $user = $statement->fetch();

    if (!empty($meterstanden)) {
        $_SESSION["user"]["voornaam"] = $user["k_voornaam"];
        $_SESSION["user"]["achternaam"] = $user["k_achternaam"];
        $_SESSION["user"]["klantnummer"] = $user["k_klantnummer"];
        $_SESSION["user"]["postcode"] = $user["a_postcode"];
        $_SESSION["user"]["huisnummer"] = $user["a_huisnummer"];
        $_SESSION["user"]["straatnaam"] = $user["a_straatnaam"];
    }
    $connection = null;
    $statement = null;
}
/*    $user = 'website';       ///< the username to connect to the database
    $pass = 'wachtwoord';    ///< the password to connect to the database
    $connection = new PDO('mysql:host=localhost;dbname=energy', $user, $pass); ///< make the connection

    $KLANTENSERVICE_READ_METERSTANDEN =
        "SELECT ms_stand, mt_type 
        FROM tbl_meters_standen 
        JOIN tbl_meter_telwerken 
            on ms_fk_idMeterTelwerk = mt_idMeterTelwerk
        JOIN tbl_meters 
            on mt_fk_idMeter = m_idMeter
        JOIN tbl_adressen 
            on m_fk_idAdres = a_idAdres
        JOIN tbl_klanten
            on  k_fk_idAdres = a_idAdres
        WHERE k_klantnummer = :klantnummer";

    $statement = $connection->prepare($KLANTENSERVICE_READ_METERSTANDEN);
    $statement->bindValue(':klantnummer', $klantnummer);
    $statement->execute();
    $meterstanden = $statement->fetchAll();

    if (!empty($meterstanden)){
        $_SESSION['meter'] = array();
        $_SESSION["meter"]["type"] = $meterstanden[0]["mt_type"];
        $metercount = count($meterstanden);
        $_SESSION["meter"]["count"] = $metercount;
        $ms_total = 0;
        foreach ($meterstanden as $value){
            $ms_total += $value["ms_stand"];
        }
        $ms_mid = intval($ms_mid = $ms_total / $metercount);
        $_SESSION["meter"]["gemideld"] = $ms_mid;

        $_SESSION["HasClient"] = 1;
    }

    $KLANTENSERVICE_READ_KLANTGEGEVENS = "
        SELECT k_achternaam, k_voornaam, k_klantnummer, a_postcode, a_huisnummer, a_straatnaam
        FROM tbl_klanten
        JOIN tbl_adressen 
            on k_fk_idAdres = a_idAdres
        WHERE k_klantnummer = :klantnummer";

    $statement = $connection->prepare($KLANTENSERVICE_READ_KLANTGEGEVENS);
    $statement->bindValue(':klantnummer', $klantnummer);
    $statement->execute();
    $user = $statement->fetch();

    if (!empty($meterstanden)) {
        $_SESSION["user"]["voornaam"] = $user["k_voornaam"];
        $_SESSION["user"]["achternaam"] = $user["k_achternaam"];
        $_SESSION["user"]["klantnummer"] = $user["k_klantnummer"];
        $_SESSION["user"]["postcode"] = $user["a_postcode"];
        $_SESSION["user"]["huisnummer"] = $user["a_huisnummer"];
        $_SESSION["user"]["straatnaam"] = $user["a_straatnaam"];
    }
    $connection = null;
    $statement = null;
}*/
?>
<!doctype html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My Energy Portal</title>
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <link href="css/klantenservice.css" rel="stylesheet">
    <script src="/bootstrap/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<main class="container-fluid">
    <div>
        <? include_once '../partials/navbarIndex.php'; ?>
        <div class="row ruimteboven">
            <div class="col-12">
                <h4 style="text-align: center">klantenservice</h4>
                <?php if ($_SESSION["HasClient"] == 0): ?>
                <div class="row">
                    <div class="col-2"></div>
                    <div class="col-3">
                        <div class="form">
                            <form method="post">
                                <div class="form-group">
                                    <label class="form-label">Postcode</label>
                                    <input type="text" class="form-control" name="Postcode" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Huisnummer</label>
                                    <input type="number" class="form-control" name="Huisnummer" required>
                                </div>
                                <button name="requestPH" type="submit" class="btn btn-lg btn-primary">aanvragen</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-2"></div>
                    <div class="col-3">
                        <div class="form">
                            <form method="post">
                                <div class="form-group">
                                    <label class="form-label">Klantnummer</label>
                                    <input type="number" class="form-control" name="Klantnummer" required>
                                </div>
                                <button name="requestKN" type="submit" class="btn btn-lg btn-primary">aanvragen</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-2"></div>
                </div>
            </div>
            <?php elseif ($_SESSION["HasClient"] == 1): ?>
                <div class="row ruimteboven">
                    <div class="col-1"></div>

                    <div class="col-4 border">
                        <div>
                            <h5>Klant gegevens</h5>
                            <p>Naam klant: <?=$_SESSION["user"]["voornaam"];?> <?=$_SESSION["user"]["achternaam"];?></p>
                            <p>Klantnummer: <?=$_SESSION["user"]["klantnummer"];?></p>
                            <p>Postcode: <?=$_SESSION["user"]["postcode"];?></p>
                            <p>Adres: <?=$_SESSION["user"]["straatnaam"];?> <?=$_SESSION["user"]["huisnummer"];?></p>
                        </div>
                    </div>

                    <div class="col-2"></div>

                    <div class="col-4">
                        <div class="row">
                            <div class="border">
                                <h5>Meetgegevens van de klant</h5>
                                <p>Totaal aantal energy metingen: <?=$_SESSION["meter"]["e_count"] ?></p>
                                <p>Gemideld energy verbruik: <?=$_SESSION["meter"]["e_gemideld"] ?></p>
                                <p>Totaal aantal gas metingen: <?=$_SESSION["meter"]["g_count"] ?></p>
                                <p>Gemideld gas verbruik: <?=$_SESSION["meter"]["g_gemideld"] ?></p>
                            </div>
                        </div>

                        <div class="row float-end">
                            <div class="ruimteboven">
                                <form method="post">
                                    <button name="done" type="submit" class="btn-primary btn-lg btn">Done</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-1"></div>
                </div>
            <?php endif ?>
        </div>
    </div>
</main>
<? include_once '../partials/footer.php'; ?>
</body>
</html>
