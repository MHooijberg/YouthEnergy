<?php
session_start();
if (!isset($_SESSION["HasClient"])) {
    $_SESSION["HasClient"] = 0;
}

if (isset($_POST['done'])) {
    $_SESSION["HasClient"] = 0;
}

if (isset($_POST['requestPH'])) {
    $postcode = $_POST['Postcode'];
    $huisnummer = $_POST['Huisnummer'];
    $_SESSION["HasClient"] = 1;
}

if (isset($_POST['requestKN'])) {
    $klantnummer = $_POST['Klantnummer'];
    $_SESSION["HasClient"] = 1;
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
                            <p>Naam klant: </p>
                            <p>Klantnummer: </p>
                            <p>Postcode: </p>
                            <p>Adres: </p>
                        </div>
                    </div>

                    <div class="col-2"></div>

                    <div class="col-4">
                        <div class="row">
                            <div class="border">
                                <h5>Meetgegevens van de klant</h5>
                                <p>Type meter: </p>
                                <p>Totaal aantal metingen: </p>
                                <p>Gemideld verbruik: </p>
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
