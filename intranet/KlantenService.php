<?php
session_start();
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
    $_SESSION["HasClient"] = 1;

    $user = 'website';       ///< the username to connect to the database
    $pass = 'wachtwoord';    ///< the password to connect to the database
    $connection = new PDO('mysql:host=localhost;dbname=energy', $user, $pass); ///< make the connection

    $KLANTENSERVICE_READ_ADRES = "SELECT * FROM tbl_adressen WHERE a_postcode='$postcode' AND a_huisnummer='$huisnummer'";
    $statement = $connection->prepare($KLANTENSERVICE_READ_ADRES);
    $statement->execute();
    $adres = $statement->fetch();
    $_SESSION["adres"] = $adres;
    $idadres = $adres["a_idAdres"];

    $KLANTENSERVICE_READ_KLANTGEGEVENS = "SELECT * FROM tbl_klanten WHERE k_fk_idAdres='$idadres'";
    $statement = $connection->prepare($KLANTENSERVICE_READ_KLANTGEGEVENS);
    $statement->execute();
    $user = $statement->fetch();
    $_SESSION["user"] = $user;

    $KLANTENSERVICE_READ_METERID = "SELECT m_idMeter FROM tbl_meters WHERE m_fk_idAdres='$idadres'";
    $statement = $connection->prepare($KLANTENSERVICE_READ_METERID);
    $statement->execute();
    $meterid = $statement->fetch();

    $KLANTENSERVICE_READ_METER = "SELECT mt_type, mt_idMeterTelwerk FROM tbl_meter_telwerken WHERE mt_fk_idMeter='$meterid[m_idMeter]'";
    $statement = $connection->prepare($KLANTENSERVICE_READ_METER);
    $statement->execute();
    $meter = $statement->fetch();
    $metertelwerkid = $meter["mt_idMeterTelwerk"];

    $KLANTENSERVICE_READ_METERSTANDEN = "SELECT ms_stand FROM tbl_meters_standen WHERE ms_fk_idMeterTelwerk='$metertelwerkid'";
    $statement = $connection->prepare($KLANTENSERVICE_READ_METERSTANDEN);
    $statement->execute();
    $meterstanden = $statement->fetchAll();

    $_SESSION['meter'] = array();
    $_SESSION["meter"]["type"] = $meter["mt_type"];
    $metercount = count($meterstanden);
    $_SESSION["meter"]["count"] = $metercount;
    $ms_total = 0;
    foreach ($meterstanden as $value){
        $ms_total += $value["ms_stand"];
    }
    $ms_mid = intval($ms_mid = $ms_total / $metercount);
    $_SESSION["meter"]["gemideld"] = $ms_mid;

    // close connection
    $connection = null;
    $statement = null;
}

if (isset($_POST['requestKN'])) {
    $klantnummer = $_POST['Klantnummer'];
    $_SESSION["HasClient"] = 1;

    $user = 'website';       ///< the username to connect to the database
    $pass = 'wachtwoord';    ///< the password to connect to the database
    $connection = new PDO('mysql:host=localhost;dbname=energy', $user, $pass); ///< make the connection


    $KLANTENSERVICE_READ_KLANTGEGEVENS = "SELECT * FROM tbl_klanten WHERE k_klantnummer='$klantnummer'";
    $statement = $connection->prepare($KLANTENSERVICE_READ_KLANTGEGEVENS);
    $statement->execute();
    $user = $statement->fetch();
    $_SESSION["user"] = $user;

    $KLANTENSERVICE_READ_ADRES = "SELECT * FROM tbl_adressen WHERE a_idAdres='$user[k_fk_idAdres]'";
    $statement = $connection->prepare($KLANTENSERVICE_READ_ADRES);
    $statement->execute();
    $adres = $statement->fetch();
    $_SESSION["adres"] = $adres;
    $idadres = $adres["a_idAdres"];

    $KLANTENSERVICE_READ_METERID = "SELECT m_idMeter FROM tbl_meters WHERE m_fk_idAdres='$idadres'";
    $statement = $connection->prepare($KLANTENSERVICE_READ_METERID);
    $statement->execute();
    $meterid = $statement->fetch();

    $KLANTENSERVICE_READ_METER = "SELECT mt_type, mt_idMeterTelwerk FROM tbl_meter_telwerken WHERE mt_fk_idMeter='$meterid[m_idMeter]'";
    $statement = $connection->prepare($KLANTENSERVICE_READ_METER);
    $statement->execute();
    $meter = $statement->fetch();
    $metertelwerkid = $meter["mt_idMeterTelwerk"];

    $KLANTENSERVICE_READ_METERSTANDEN = "SELECT ms_stand FROM tbl_meters_standen WHERE ms_fk_idMeterTelwerk='$metertelwerkid'";
    $statement = $connection->prepare($KLANTENSERVICE_READ_METERSTANDEN);
    $statement->execute();
    $meterstanden = $statement->fetchAll();

    $_SESSION['meter'] = array();
    $_SESSION["meter"]["type"] = $meter["mt_type"];
    $metercount = count($meterstanden);
    $_SESSION["meter"]["count"] = $metercount;
    $ms_total = 0;
    foreach ($meterstanden as $value){
        $ms_total += $value["ms_stand"];
    }
    $ms_mid = intval($ms_mid = $ms_total / $metercount);
    $_SESSION["meter"]["gemideld"] = $ms_mid;

    // close connection
    $connection = null;
    $statement = null;
}
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
                            <p>Naam klant: <?=$_SESSION["user"]["k_voornaam"];?> <?=$_SESSION["user"]["k_achternaam"];?></p>
                            <p>Klantnummer: <?=$_SESSION["user"]["k_klantnummer"];?></p>
                            <p>Postcode: <?=$_SESSION["adres"]["a_postcode"];?></p>
                            <p>Adres: <?=$_SESSION["adres"]["a_straatnaam"];?> <?=$_SESSION["adres"]["a_huisnummer"];?></p>
                        </div>
                    </div>

                    <div class="col-2"></div>

                    <div class="col-4">
                        <div class="row">
                            <div class="border">
                                <h5>Meetgegevens van de klant</h5>
                                <p>Type meter: <?=$_SESSION["meter"]["type"] ?></p>
                                <p>Totaal aantal metingen: <?=$_SESSION["meter"]["count"] ?></p>
                                <p>Gemideld verbruik: <?=$_SESSION["meter"]["gemideld"] ?></p>
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
