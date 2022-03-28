<?php
include_once "ldap_constants.inc.php";
include_once "ldap_support.inc.php";
if (session_status() == PHP_SESSION_NONE){
    session_start();
}
$user = 'website';       ///< the username to connect to the database
$pass = 'wachtwoord';    ///< the password to connect to the database
$connection = new PDO('mysql:host=localhost;dbname=energy', $user, $pass); ///< make the connection

try {
    $lnk = ConnectAndCheckLDAP();
} catch (Exception $ex) {
    die($ex->getMessage());
}

if (!isset($_SESSION["loggedin"]) | (isset($_SESSION['loggedin']) == false)){
    $_SESSION["loggedin"] = true;
}

if (!isset($_SESSION['permissions'])){
    $userGroups = GetGroupNames($lnk);
    $permissions = GetPermissions($connection, $userGroups);
    $_SESSION['permissions'] = $permissions;
}

if(!isset($_SESSION["klantnummer"])) {
    $clientNumber = GetClientNumber($lnk, $_SERVER["AUTHENTICATE_UID"]);
    $_SESSION["klantnummer"] = $clientNumber;
}

function ApproveOrRedirect($neededPermissions, $requireAll = false){
    $currentPermissions = $_SESSION['permissions'];
    $retrievedPermissions = array();

    foreach($neededPermissions as $value){
        $retrievedPermissions[] = $currentPermissions[$value];
    }

    $hasPermissions = $requireAll ? (bool) array_product($retrievedPermissions) : in_array(true, $retrievedPermissions, true);

    if (!$hasPermissions){
        // ToDO: Maybe a 'you dont have permission page'
        header("Location:"."/index.php");
    }
}
function GetClientNumber($link, $uid){
    // https://www.php.net/manual/en/function.ldap-search.php
    $ldapRes = ldap_search($link, BASE_DN, "(&(objectClass=INetOrgPerson)(uid=${uid}))", ['*'], 0, -1,-1,0);
    if ($ldapRes ===  false ) {
        throw new Exception("GetUserDNFromUID::Cannot execute query");
    }
    $results = ldap_get_entries($link, $ldapRes);
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
function GetGroupNames($link){
    $retrievedGroups = [];
    $userDN = GetUserDNFromUID($link, $_SERVER["AUTHENTICATE_UID"]);
    $groups = GetAllLDAPGroupMemberships($link, $userDN);

    foreach ($groups as $value) {
        $groupname = substr(explode(',', $value)[0], 3);
        if ($groupname != 'allwebsiteusers') {
            $retrievedGroups[] = $groupname;
        }
    }
    return $retrievedGroups;
}
function GetPermissions($con, $groups){
    $SEARCH_PERMISSIONS = "SELECT MAX(Read_Own_VerbruiksMeter) AS ReadOwnVerbruiksmeter,
        MAX(Read_Own_Klantgegevens) AS ReadOwnKlantgegevens,
        MAX(Edit_Own_Klantgegevens) AS EditOwnKlantgegevens,
        MAX(Read_KlantGegevens_1_Persoon) AS ReadKlantgegevensSingle,
        MAX(Read_Meetgegevens_1_Persoon) AS ReadMeetgegevensSingle,
        MAX(Read_Meetgegevens_Postcode) AS ReadMeetgegevensPostcode,
        MAX(Read_Meetgegevens_Gemeente) AS ReadMeetgegevensGemeente,
        MAX(Read_Meetgegevens_Plaatsnaam) AS ReadMeetgegevensPlaatsnaam,
        MAX(Read_Meetgegevens_Straat) AS ReadMeetgegevensStraat,
        MAX(Export_Meetgegevens_Bulk) AS ExportMeetgegevensBulk,
        MAX(Create_New_Rol) AS CreateRol,
        MAX(Edit_Rol) AS EditRole,
        MAX(Delete_Rol) AS DeleteRole
        FROM tbl_permission
        WHERE ";

    for ($i = 0; $i < sizeof($groups); $i++){
        $SEARCH_PERMISSIONS .= "Rol_id = :" . $groups[$i];
        $SEARCH_PERMISSIONS .= ($i != (sizeof($groups) - 1)) ? " OR " : ";";
    }
    $statement = $con->prepare($SEARCH_PERMISSIONS);
    for ($i = 0; $i < sizeof($groups); $i++) {
        $group = $groups[$i];
        $statement->bindValue(':'.$group, $group);
    }
    $statement->execute();
    $permissionsRecord = $statement->fetch();
    return $permissionsRecord;
}
?>