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