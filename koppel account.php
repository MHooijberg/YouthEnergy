<?php
include_once "intranet/ldap_constants.inc.php";
include_once "intranet/ldap_support.inc.php";

try{
    $lnk = ConnectAndCheckLDAP();
}
catch(Exception $ex){
    die($ex->getMessage());
}

$user = 'website';       ///< the username to connect to the database
$pass = 'wachtwoord';    ///< the password to connect to the database
$connection = new PDO('mysql:host=localhost;dbname=energy', $user, $pass); ///< make the connection

$klantnummer = $_POST['klantnummer'];
$email = $_POST['email'];
$postcode = $_POST['postcode'];
$wachtwoord = $_POST['wachtwoord'];
$herhaaldwachtwoord = $_POST['herhaaldwachtwoord'];
$privacy = $_POST['privacy'];

// ToDo: Checks to perform are passwords are the same, postalcode is valid, client number exists, client number and postal code match,
// and privacy is turned on
$IsValidPassword = $wachtwoord == $herhaaldwachtwoord;
$IsPrivacyChecked = $privacy == 'on';
if ($IsPrivacyChecked && $IsValidPassword) {
    $IsValidClientData = CheckClientNumberAndPostalCode($connection, $klantnummer, $postcode);
    if ($IsValidClientData){
        try {
            CreateNewClient($lnk, $email, $wachtwoord, $klantnummer);
            header("Location:"."/intranet/index.php");
        } catch (Exception $exception){

        }
    }
}
else {
    echo 'De ingevoerde wachtwoorden komen niet overeen.';
}

function CheckClientNumberAndPostalCode($con, $givenClientNumber, $givenPostalCode){
    $SEARCH_QUERY = "SELECT a_postcode FROM tbl_adressen WHERE a_idAdres = 
                    (SELECT k_fk_idAdres FROM tbl_klanten WHERE k_klantnummer = :klantnummer);";
    $statement = $con->prepare($SEARCH_QUERY);
    $statement->bindParam(':klantnummer', $givenClientNumber);
    $statement->execute();
    $rowCount = $statement->rowCount();
    if ($rowCount == 1)
    {
        $result = $statement->fetchColumn();
        if ($result == $givenPostalCode){
            echo 'success';
            return true;
        }
        else {
            echo 'verkeerde postcode';
            return false;
        }
    }
    else {
        echo 'verkeerde klantnummer';
        return false;
    }
}
?>
