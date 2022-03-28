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
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    </head>
    <body>
        <? include_once 'partials/navbarIndex.php'; ?>
        <main class="mx-5">
            <form id="AccountKoppelen" method="post" action="koppel account.php">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="klantnummer">Klantnummer</label>
                            <input type="text" class="form-control" id="klantnummer" name="klantnummer" placeholder="Klantnummer" required>
                        </div>
                        <div class="form-group">
                            <label for="postcode">Postcode</label>
                            <input type="text" class="form-control" id="postcode" name="postcode" placeholder="Postcode" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Voer email in" required>
                            <small id="emailHelp" class="form-text text-muted">
                                Disclaimer e-mailadres: Uw e-mail adres wordt alleen gebruikt bij het inloggen,
                                tenzij u aangeeft de wekelijkse nieuwsbrief te willen ontvangen
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="InputPassword">Wachtwoord</label>
                            <input type="password" class="form-control" id="wachtwoord" name="wachtwoord" placeholder="Wachtwoord" required>
                        </div>
                        <div class="form-group">
                            <label for="ConfirmPassword">Herhaal Wachtwoord</label>
                            <input type="password" class="form-control" id="herhaaldwachtwoord" name="herhaaldwachtwoord" placeholder="Herhaal Wachtwoord" required>
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
                            <input type="checkbox" class="form-check-input" name="privacy" id="privacy" required>
                            <label class="form-check-label" for="privacy">Ik ga akkoord met de privacy verklaring.</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="CodeCheck" required>
                            <label class="form-check-label" for="code">Ik geef toestemming dat Youth Energy mij een code opstuurd.</label>
                        </div>
                        <!-- Trigger the modal with a button -->
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Privacy Verklaring</button>
                        <button form="AccountKoppelen" type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
            <!-- Modal -->
            <div id="myModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Privacy verklaring</h4>
                        </div>
                        <div class="modal-body" style="overflow-y:scroll;max-height: 80vh;">
                            <p>
                                Privacy verklaring implementatie in de website
                                Youth Energy, gevestigd aan Van Alphenstraat 142, is verantwoordelijk voor de verwerking van persoonsgegevens zoals weergegeven in deze privacyverklaring.

                                Contactgegevens:

                                https://www.youthenergy.eu

                                Van Alphenstraat 142

                                +31 050 1238284

                                Wytse Hofstra is de Functionaris Gegevensbescherming van Youth Energy Hij/zij is te bereiken via wytse.hofstra@student.nhlstenden.com

                                Persoonsgegevens die wij verwerken
                                Youth Energy verwerkt uw persoonsgegevens doordat u gebruik maakt van onze diensten en/of omdat u deze zelf aan ons verstrekt. Hieronder vindt u een overzicht van de persoonsgegevens die wij verwerken:

                                - Voor- en achternaam
                                - Geboortedatum
                                - Adresgegevens
                                - Telefoonnummer
                                - E-mailadres
                                - Overige persoonsgegevens die u actief verstrekt bijvoorbeeld door een profiel op deze website aan te maken, in correspondentie en telefonisch
                                - Locatiegegevens
                                - Gegevens over uw activiteiten op onze website
                                Bijzondere en/of gevoelige persoonsgegevens die wij verwerken
                                Onze website en/of dienst heeft niet de intentie gegevens te verzamelen over websitebezoekers die jonger zijn dan 16 jaar. Tenzij ze toestemming hebben van ouders of voogd. We kunnen echter niet controleren of een bezoeker ouder dan 16 is. Wij raden ouders dan ook aan betrokken te zijn bij de online activiteiten van hun kinderen, om zo te voorkomen dat er gegevens over kinderen verzameld worden zonder ouderlijke toestemming. Als u er van overtuigd bent dat wij zonder die toestemming persoonlijke gegevens hebben verzameld over een minderjarige, neem dan contact met ons op via privacy@youthenergy.eu, dan verwijderen wij deze informatie.

                                Met welk doel en op basis van welke grondslag wij persoonsgegevens verwerken
                                Youth Energy verwerkt uw persoonsgegevens voor de volgende doelen:

                                - Het afhandelen van uw betaling
                                - Verzenden van onze nieuwsbrief en/of reclamefolder
                                - U te kunnen bellen of e-mailen indien dit nodig is om onze dienstverlening uit te kunnen voeren
                                - U te informeren over wijzigingen van onze diensten en producten
                                - Om goederen en diensten bij u af te leveren
                                - Youth Energy verwerkt ook persoonsgegevens als wij hier wettelijk toe verplicht zijn, zoals gegevens die wij nodig hebben voor onze belastingaangifte.
                                Geautomatiseerde besluitvorming
                                Youth Energy neemt #responsibility op basis van geautomatiseerde verwerkingen besluiten over zaken die (aanzienlijke) gevolgen kunnen hebben voor personen. Het gaat hier om besluiten die worden genomen door computerprogramma's of -systemen, zonder dat daar een mens (bijvoorbeeld een medewerker van Youth Energy) tussen zit. Youth Energy gebruikt de volgende computerprogramma's of -systemen: #use_explanation

                                Hoe lang we persoonsgegevens bewaren
                                Youth Energy bewaart uw persoonsgegevens niet langer dan strikt nodig is om de doelen te realiseren waarvoor uw gegevens worden verzameld. Wij hanteren de volgende bewaartermijnen voor de volgende (categorieÃ«n) van persoonsgegevens: #retention_period
                                Delen van persoonsgegevens met derden
                                Youth Energy verstrekt uitsluitend aan derden en alleen als dit nodig is voor de uitvoering van onze overeenkomst met u of om te voldoen aan een wettelijke verplichting.

                                Cookies, of vergelijkbare technieken, die wij gebruiken
                                Youth Energy gebruikt alleen technische en functionele cookies. En analytische cookies die geen inbreuk maken op uw privacy. Een cookie is een klein tekstbestand dat bij het eerste bezoek aan deze website wordt opgeslagen op uw computer, tablet of smartphone. De cookies die wij gebruiken zijn noodzakelijk voor de technische werking van de website en uw gebruiksgemak. Ze zorgen ervoor dat de website naar behoren werkt en onthouden bijvoorbeeld uw voorkeursinstellingen. Ook kunnen wij hiermee onze website optimaliseren. U kunt zich afmelden voor cookies door uw internetbrowser zo in te stellen dat deze geen cookies meer opslaat. Daarnaast kunt u ook alle informatie die eerder is opgeslagen via de instellingen van uw browser verwijderen.

                                Gegevens inzien, aanpassen of verwijderen
                                U heeft het recht om uw persoonsgegevens in te zien, te corrigeren of te verwijderen. Dit kunt u zelf doen via de persoonlijke instellingen van uw account. Daarnaast heeft u het recht om uw eventuele toestemming voor de gegevensverwerking in te trekken of bezwaar te maken tegen de verwerking van uw persoonsgegevens door ons bedrijf en heeft u het recht op gegevensoverdraagbaarheid. Dat betekent dat u bij ons een verzoek kunt indienen om de persoonsgegevens die wij van u beschikken in een computerbestand naar u of een ander, door u genoemde organisatie, te sturen. Wilt u gebruik maken van uw recht op bezwaar en/of recht op gegevensoverdraagbaarheid of heeft u andere vragen/opmerkingen over de gegevensverwerking, stuur dan een gespecificeerd verzoek naar privacy@youthenergy.eu. Om er zeker van te zijn dat het verzoek tot inzage door u is gedaan, vragen wij u een kopie van uw identiteitsbewijs bij het verzoek mee te sturen. Maak in deze kopie uw pasfoto, MRZ (machine readable zone, de strook met nummers onderaan het paspoort), paspoortnummer en Burgerservicenummer (BSN) zwart. Dit ter bescherming van uw privacy. Youth Energy zal zo snel mogelijk, maar in ieder geval binnen vier weken, op uw verzoek reageren. Youth Energy wil u er tevens op wijzen dat u de mogelijkheid hebt om een klacht in te dienen bij de nationale toezichthouder, de Autoriteit Persoonsgegevens. Dat kan via de volgende link: https://autoriteitpersoonsgegevens.nl/nl/contact-met-de-autoriteit-persoonsgegevens/tip-ons

                                Hoe wij persoonsgegevens beveiligen
                                Youth Energy neemt de bescherming van uw gegevens serieus en neemt passende maatregelen om misbruik, verlies, onbevoegde toegang, ongewenste openbaarmaking en ongeoorloofde wijziging tegen te gaan. Als u de indruk heeft dat uw gegevens niet goed beveiligd zijn of er zijn aanwijzingen van misbruik, neem dan contact op met onze klantenservice of via privacy@youthenergy.eu. Youth Energy heeft de volgende maatregelen genomen om uw persoonsgegevens te beveiligen: [voeg hier eventueel andere maatregelen die je neemt aan toe]

                                - Beveiligingssoftware, zoals een virusscanner en firewall.
                                - TLS (voorheen SSL) Wij versturen uw gegevens via een beveiligde internetverbinding. Dit kunt u zien aan de adresbalk 'https' en het hangslotje in de adresbalk.

                                Recht op vergetelheid
                                U hebt heeft recht op gegevenswissing, zonder onredelijke vertraging. Als er bezwaar is tegen de verwerking, persoonsgegevens zijn niet langer nodig voor de doeleinden, zich onrechtmatige gegevensverwerking voordoet (Artikel 17, lid 1).
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <div class="fixed-bottom">
        <? include_once 'partials/footer.php'; ?>
        </div>
    </body>
</html>
