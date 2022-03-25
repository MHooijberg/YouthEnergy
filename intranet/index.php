<?php
include_once "ldap_constants.inc.php";
include_once "ldap_support.inc.php";
session_start();

echo "Before check:"; print_r($_SESSION["klantnummer"]);

if(!isset($_SESSION["klantnummer"])) {
    try {
        $lnk = ConnectAndCheckLDAP();
    } catch (Exception $ex) {
        die($ex->getMessage());
    }
    $clientNumber = GetClientNumber($lnk, $_SERVER["AUTHENTICATE_UID"]);
    $_SESSION["klantnummer"] = $clientNumber;
}

function GetClientNumber($lnk, $uid){
    // https://www.php.net/manual/en/function.ldap-search.php
    $ldapRes = ldap_search($lnk, BASE_DN, "(&(objectClass=INetOrgPerson)(uid=${uid}))", ['*'], 0, -1,-1,0);
    if ($ldapRes ===  false ) {
        throw new Exception("GetUserDNFromUID::Cannot execute query");
    }
    $results = ldap_get_entries($lnk, $ldapRes);
    print_r($results);
    if ($results !== false && $results['count'] == 1) {
        $record = $results[0];
        if (isset($record['employeenumber'])) {
            return $record['employeenumber'][0];
        }
        else {
            return null;
        }
    }
    else {
        return null;
    }
}
echo "after check: "; print_r($_SESSION["klantnummer"]);
?>

<?php
/** @file intranet/Backoffice.php
 * Index for the intranet. Users need to logon using BasicAuth
 *
 * @author Martin Molema <martin.molema@nhlstenden.com>
 * @copyright 2022
 *
 * Show the user's DN and all group memberships
 */

include_once "ldap_constants.inc.php";
include_once "ldap_support.inc.php";

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
