<?php
include_once "rbac_permissions_support.inc.php";

/** @file intranet/Backoffice.php
 * Index for the intranet. Users need to logon using BasicAuth
 *
 * @author Martin Molema <martin.molema@nhlstenden.com>
 * @copyright 2022
 *
 * Show the user's DN and all group memberships
 */

/**
 * Function to show info on the logged in user
 * @return void
 * @throws Exception
 */
function reportUserInfo()
{
    try {
        $lnk = ConnectAndCheckLDAP();
    } catch (Exception $ex) {
        die($ex->getMessage());
    }

    $userDN = GetUserDNFromUID($lnk, $_SERVER["AUTHENTICATE_UID"]);
    echo "<P>User DN = ${userDN} </P>";
    if ($userDN != null) {
        $groups = GetAllLDAPGroupMemberships($lnk, $userDN);
        echo "<P>Group memberships:</P><ul>";
        foreach ($groups as $group) {
            echo "<li>$group</li>";
        }
        echo "</ul>";
    }
}//reportUserInfo
?>
<html lang="en">
<head>
    <title>Hello Intranet!</title>
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <script src="/bootstrap/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<? include_once '../partials/navbarIndex.php'; ?>
<main class="container-fluid">
    <article>
        <section>
            <header>
                <P> Intranet :: It works! </p>
            </header>
            <P>Login gegevens:</P>
            <h1></h1>
            <?

            /**
             * First the HTML
             */
            echo "<P>Gebruiker '" . $_SERVER["AUTHENTICATE_UID"] . "' ingelogd met wachtwoord '" . $_SERVER['PHP_AUTH_PW'] . "'</P>";
            ?>
        </section>
        <section>
            <P>Gebruik onderstaande formulier om een nieuwe gebruiker aan te maken. De afhandeling van het aanmaken van
                deze gebruiker vindt plaats via het script 'createNewUser.php'.
            </P>
            <form action="createNewUser.php" method="post">
                <label for="idUserName">Gebruikersnaam</label>
                <input type="text" name="username" id="idUserName">
                <br/>
                <label for="idVoornaam">Voornaam</label>
                <input type="text" name="voornaam" id="idVoornaam">
                <br/>
                <label for="idAchternaam">Achternaam</label>
                <input type="text" name="achternaam" id="idAchternaam">
                <br/>
                <button type="submit">Opslaan</button>
            </form>
        </section>
    </article>
</main>
<? include_once '../partials/footer.php'; ?>
</body>
</html>
