<?php
/** @file Backoffice.php
 * Index for the public website
 *
 * @author Martin Molema <martin.molema@nhlstenden.com>
 * @copyright 2022
 *
 * Show a very basis HTML Bootstrap template
 */
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

<main class="container-fluid">
    <div class="row d-flex justify-content-center">
        <div class="col-4 d-flex flex-column justify-content-center border border-dark">
            <h2>Mijn meterstanden aanvragen</h2>
            <p>vraag hier je meterstanden aan</p>
            <input type="button"
                   class="btn btn-primary btn-lg m-3"
                   value="Aanvragen">
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="border border-dark m-3 p-3">
                <h2>Dit ben jij</h2>
                <p>Voornaam: Aylin</p>
                <p>Achternaam: Adusei-Poku</p>
                <p>email: </p>
                <p>klantnummer: 569759384</p>
            </div>

            <div class="border border-dark m-3 p-3">
                <h2>Jouw adress gegevens</h2>
                <p>Adres: Exloërmond</p>
                <p>Postcode: 1432RO</p>
                <p>Woonplaats: Drenthe</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 border border-dark m-3 p-3">
            <h3>Wilt u uw persoonsgegevens laten verwijderen?</h3>
            <p>bel met klantenservice</p>
            <p>Wytse Hofstra: 06 112345678</p>
            <p>Beschikbaar: ma-vr van 10.00 tot 11.00</p>
        </div>
    </div>
</main>

<? include_once '../partials/footer.php'; ?>
</body>
</html>