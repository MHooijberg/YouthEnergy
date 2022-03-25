<?php
?>
<html lang="nl">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Energy | Account Koppelen</title>
        <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <!-- <link href="css/accountaanmaken.css" rel="stylesheet"> -->
        <script src="/bootstrap/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
    <form id="AccountKoppelen">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="InputKlantNummer">Klantnummer</label>
                    <input type="text" class="form-control" id="InputKlantNummer" placeholder="Klantnummer">
                </div>
                <div class="form-group">
                    <label for="InputPostcode">Postcode</label>
                    <input type="text" class="form-control" id="InputPostcode" placeholder="Postcode">
                </div>
                <div class="form-group">
                    <label for="InputEmail">Email address</label>
                    <input type="email" class="form-control" id="InputEmail" aria-describedby="emailHelp" placeholder="Voer email in">
                    <small id="emailHelp" class="form-text text-muted">
                        Disclaimer e-mailadres: Uw e-mail adres wordt alleen gebruikt bij het inloggen,
                        tenzij u aangeeft de wekelijkse nieuwsbrief te willen ontvangen
                    </small>
                </div>
                <div class="form-group">
                    <label for="InputPassword">Wachtwoord</label>
                    <input type="password" class="form-control" id="InputPassword" placeholder="Wachtwoord">
                </div>
                <div class="form-group">
                    <label for="ConfirmPassword">Herhaal Wachtwoord</label>
                    <input type="password" class="form-control" id="ConfirmPassword" placeholder="Herhaal Wachtwoord">
                </div>
            </div>
            <div class="col-6">
                <p>
                    Als bevestiging krijgt u via de post een brief met daarop een authenticatiecode,
                    deze code kunt u invoeren bij 'mijn account'op de website van Youth Energy.
                    Dit is een eenmalig proces, u hoeft het hierna dus niet meer te doen
                </p>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="NewsLetterCheck">
                    <label class="form-check-label" for="NewsLetterCheck">Ik ontvang graag de nieuwsbrief via de e-mail.</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="PrivacyCheck">
                    <label class="form-check-label" for="PrivacyCheck">Ik ga akkord met de privacy verklaring. (klik <a href="">hier</a> om de privacy verklaring in te zien)</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="CodeCheck">
                    <label class="form-check-label" for="CodeCheck">Ik geef toestemming dat Youth Energy mij een code opstuurd.</label>
                </div>
                <!-- Trigger the modal with a button -->
                <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button>
            </div>
        </div>
    </form>
    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Modal Header</h4>
                </div>
                <div class="modal-body">
                    <p>Some text in the modal.</p>
                </div>
                <div class="modal-footer">
                    <button form="AccountKoppelen" type="submit" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    </body>
</html>
