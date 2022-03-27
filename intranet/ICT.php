<?php
include_once "rbac_permissions_support.inc.php";

$neededPermissions = array();
$neededPermissions[] = "CreateRol";
$neededPermissions[] = "EditRole";
$neededPermissions[] = "DeleteRole";
ApproveOrRedirect($neededPermissions, true);

$user = 'website';       ///< the username to connect to the database
$pass = 'wachtwoord';    ///< the password to connect to the database
$connection = new PDO('mysql:host=localhost;dbname=energy', $user, $pass);

if (!isset($_SESSION["HasRequest"])) {
    $_SESSION["HasRequest"] = 0;
}


if (isset($_POST['done'])) {
    $_SESSION["HasRequest"] = 0;
    unset($_SESSION["MW"]);
    unset($_SESSION['perms']);
    unset($_SESSION['roles']);
    unset($_SESSION['groups']);


} elseif (isset($_POST['requestMM'])) {
    $_SESSION["HasRequest"] = 'MM';
} elseif (isset($_POST['zoekpn'])) {
    unset($_SESSION["MW"]);
    unset($_SESSION["groups"]);
    $pn = $_POST['pn'];
    $GET_MEDEWERKER_FROM_DB = "SELECT * 
        FROM tbl_medewerkers
        WHERE emp_personeelsnummer=:pn";
    $statement = $connection->prepare($GET_MEDEWERKER_FROM_DB);
    $statement->bindValue(':pn', $pn);
    $statement->execute();
    $medewerker = $statement->fetch();
    if ($medewerker !== false) {
        $_SESSION['MW']['voornaam'] = $medewerker['emp_voornaam'];
        $_SESSION['MW']['achternaam'] = $medewerker['emp_achternaam'];
        $_SESSION['MW']['email'] = $medewerker['emp_email'];
        $_SESSION['MW']['status'] = $medewerker['emp_status'];
        $_SESSION['MW']['pn'] = $medewerker['emp_personeelsnummer'];
        $_SESSION['MW']['functie'] = $medewerker['emp_functie'];
        $_SESSION['MW']['DID'] = $medewerker['emp_datum_in_dienst'];
        if ($medewerker['emp_datum_uit_dienst'] == null) {
            $_SESSION['MW']['DUD'] = 'nvt';
        } else {
            $_SESSION['MW']['DUD'] = $medewerker['emp_datum_uit_dienst'];
        }

        include_once "ldap_constants.inc.php";
        include_once "ldap_support.inc.php";

        try {
            $lnk = ConnectAndCheckLDAP();
        } catch (Exception $ex) {
            die($ex->getMessage());
        }
        $roles = GetAllRoles($lnk);
        $_SESSION['roles'] = $roles;

        $cn = $_SESSION['MW']['achternaam'];
        try {
            $userDN = GetUserDNFromUID($lnk, $cn);
        } catch (Exception $e) {
        }
        if (isset($userDN)) {
            try {
                $groups = GetAllLDAPGroupMemberships($lnk, $userDN);
            } catch (Exception $e) {
            }
            if (isset($groups)) {
                for ($x = 0; $x < count($groups); $x++) {

                    $new_str = strstr($groups[$x], ',', true);

                    $new_str = str_replace('cn=', '', $new_str);
                    $groups[$x] = $new_str;

                    if ($groups[$x] == 'allwebsiteusers') {
                        unset($groups[$x]);
                    }

                }
                $_SESSION['groups'] = $groups;
            }
        }
    }

} elseif (isset($_POST['submitMW'])) {
    $status = $_POST['status'];
    if ($status != $_SESSION['MW']['status']) {
        $UPDATE_STATUS_MW = "UPDATE tbl_medewerkers SET emp_status=:st WHERE emp_personeelsnummer=:pn";
        $statement = $connection->prepare($UPDATE_STATUS_MW);
        $statement->bindValue(':pn', $_SESSION['MW']['pn']);
        $statement->bindValue(':st', $status);

        $statement->execute();
    }

    try {
        $lnk = ConnectAndCheckLDAP();
    } catch (Exception $ex) {
        die($ex->getMessage());
    }

    if (!isset($_POST['selected']) && isset($_SESSION['groups'])){
        //delete all roles in ldap for the user
        foreach ($_SESSION['groups'] as $group){
            $RoleDN = "cn=" . $group . "," . USERS_INTERN_DN;
            $cn = $_SESSION['MW']['achternaam'];
            try {
                $userDN = GetUserDNFromUID($lnk, $cn);
            } catch (Exception $e) {
            }
            $group_info['uniqueMember'] = $userDN;

            try {
                RemoveRolFromUser($lnk, $RoleDN, $group_info);
            } catch (Exception $e) {
            }

        }
    }
    elseif ((isset($_POST['selected']))){
        $selected = $_POST['selected'];
        $alreadyIn = $_SESSION['groups'];
        foreach ($selected as $group){
            if (!in_array($group, $alreadyIn)){
                $cn = $_SESSION['MW']['achternaam'];
                $RoleDN = "cn=" . $group . "," . USERS_INTERN_DN;
                try {
                    $userDN = GetUserDNFromUID($lnk, $cn);
                } catch (Exception $e) {
                }
                try {
                    AddUserToGroup($lnk, $RoleDN, $userDN);
                } catch (Exception $e) {
                }
            }
        }
        foreach ($alreadyIn as $group){
            if (!in_array($group, $selected)){
                $RoleDN = "cn=" . $group . "," . USERS_INTERN_DN;
                $cn = $_SESSION['MW']['achternaam'];
                try {
                    $userDN = GetUserDNFromUID($lnk, $cn);
                } catch (Exception $e) {
                }
                $group_info['uniqueMember'] = $userDN;

                try {
                    RemoveRolFromUser($lnk, $RoleDN, $group_info);
                } catch (Exception $e) {
                }
            }
        }
    }
    unset($_SESSION['MW']);

} elseif (isset($_POST['requestMR'])) {
    $_SESSION["HasRequest"] = 'MR';
    $GET_PERMISSION = "SELECT COLUMN_NAME
        FROM information_schema.COLUMNS
        WHERE TABLE_NAME='tbl_permission' AND DATA_TYPE='tinyint'";

    $statement = $connection->prepare($GET_PERMISSION);
    $statement->execute();
    $permissions = $statement->fetchAll();
    $_SESSION['perms'] = $permissions;
} elseif (isset($_POST['requestDR'])) {
    $_SESSION["HasRequest"] = 'DR';
    $GET_PERMISSION = "SELECT Rol_id
        FROM tbl_permission";

    $statement = $connection->prepare($GET_PERMISSION);
    $statement->execute();
    $roles = $statement->fetchAll();
    $_SESSION['roles'] = $roles;
} elseif (isset($_POST['Newrol'])) {
    if (isset($_POST['selected_perm'])) {
        include_once "ldap_constants.inc.php";
        include_once "ldap_support.inc.php";

        try {
            $lnk = ConnectAndCheckLDAP();
        } catch (Exception $ex) {
            die($ex->getMessage());
        }

        $cn = $_POST['Name'];
        $newRoleDN = "cn=" . $cn . "," . USERS_INTERN_DN;

        try {
            CreateNewRole($lnk, $newRoleDN, $cn);

            $CREATE_ROLE_IN_DB = "INSERT INTO tbl_permission (Rol_id) VALUES (:cn)";
            $statement = $connection->prepare($CREATE_ROLE_IN_DB);
            $statement->bindValue(':cn', $cn);
            $statement->execute();
            foreach ($_SESSION['perms'] as $perm) {
                if (in_array($perm[0], $_POST['selected_perm'])) {
                    $INSERT_ROLE_IN_DB = "UPDATE tbl_permission SET " . $perm[0] . "=1 WHERE Rol_id=:cn";
                    $statement = $connection->prepare($INSERT_ROLE_IN_DB);
                    $statement->bindValue(':cn', $cn);
                    $statement->execute();
                }
            }
            $_SESSION["HasRequest"] = 0;
        } catch (Exception $e) {
        }
    }
} elseif (isset($_POST['Deleterol'])) {
    $role = $_POST['Role'];

    include_once "ldap_constants.inc.php";
    include_once "ldap_support.inc.php";

    try {
        $lnk = ConnectAndCheckLDAP();
    } catch (Exception $ex) {
        die($ex->getMessage());
    }

    $RoleToDelete = "cn=" . $role . "," . USERS_INTERN_DN;

    try {
        DeleteRol($lnk, $role);
    } catch (Exception $ex) {
        die($ex->getMessage());
    }

    $DELETE_ROL = "DELETE FROM tbl_permission WHERE Rol_id=:cn";
    $statement = $connection->prepare($DELETE_ROL);
    $statement->bindValue(':cn', $role);
    $statement->execute();

    $_SESSION["HasRequest"] = 0;
}


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My Energy Portal</title>
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/klantenservice.css" rel="stylesheet">
    <script src="/bootstrap/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<? include_once '../partials/navbarIndex.php'; ?>
<main>
    <div class="container-fluid"
    <div class="row">
        <div class="col-12 d-flex justify-content-center">
            <h1>ICT</h1>
        </div>
    </div>
    <? if ($_SESSION["HasRequest"] === 0): ?>
        <div class="row ruimteboven">
            <div class="col-4 text-center">
                <form method="post">
                    <button name="requestMM" type="submit" class="btn btn-primary">Manage Medewerkers</button>
                </form>
            </div>
            <div class="col-4 text-center">
                <form method="post">
                    <button name="requestMR" type="submit" class="btn btn-primary">Maak nieuwe rol</button>
                </form>
            </div>
            <div class="col-4 text-center">
                <form method="post">
                    <button name="requestDR" type="submit" class="btn btn-primary">Delete rol</button>
                </form>
            </div>
        </div>
    <? elseif ($_SESSION["HasRequest"] == 'MM'): ?>
        <form method="post">
            <div class="row ruimteboven">

                <div class="col-1"></div>

                <div class="col-3">
                    <div>Zoek een medewerker</div>
                    <div>
                        <input type="text" class="form-control" name="pn" placeholder="Personeelsnummer">
                        <button name="zoekpn" type="submit" class="btn-primary btn-lg btn">Zoek</button>
                    </div>
                    <? if (isset($_SESSION['MW'])): ?>
                        <div>
                            <h5>Personeels gegevens</h5>
                            <p>Naam
                                medewerker: <?= $_SESSION["MW"]["voornaam"]; ?> <?= $_SESSION["MW"]["achternaam"]; ?></p>
                            <p>Personeelsnummer: <?= $_SESSION["MW"]["pn"]; ?></p>
                            <p>Email: <?= $_SESSION["MW"]["email"]; ?></p>
                            <p>Functie: <?= $_SESSION["MW"]["functie"]; ?></p>
                            <p>Status: <?= $_SESSION["MW"]["status"]; ?></p>
                            <p>Datum in Dienst: <?= $_SESSION["MW"]["DID"]; ?></p>
                            <p>Datum uit Dienst: <?= $_SESSION["MW"]["DUD"]; ?></p>
                        </div>
                    <? endif; ?>
                </div>
                <div class="col-1"></div>

                <div class="col-3">
                    <? if (isset($_SESSION['MW'])): ?>
                        <div>
                            <h5>Selecteer rollen om aan de medewerker te geven</h5>
                            <?
                            for ($x = 0; $x < $_SESSION['roles']['count']; $x++) {
                                $role = $_SESSION['roles'][$x]['cn'][0]; ?>
                                <div><input type='checkbox' name='selected[]' value='<?= $role ?>'
                                        <?php
                                        if (isset($_SESSION['groups'])) {
                                            for ($y = 0; $y < count($_SESSION['groups']); $y++) {
                                                if ($_SESSION['groups'][$y] == $role) {
                                                    echo (true) ? "checked" : "";
                                                }
                                            }
                                        }
                                        ?>
                                    ><?= $role ?></div>
                                <?
                            }
                            ?>
                        </div>
                    <? endif; ?>
                </div>

                <div class="col-1"></div>

                <div class="col-2">
                    <? if (isset($_SESSION['MW'])): ?>
                        <div>
                            <h5>Medewerker in of uit dienst</h5>
                            <div><input type="radio" name='status'
                                        value='A' <?php echo ($_SESSION['MW']['status'] == 'A') ? "checked" : ""; ?>>In
                                dienst
                            </div>
                            <div><input type="radio" name='status'
                                        value='U' <?php echo ($_SESSION['MW']['status'] == 'U') ? "checked" : ""; ?>>Uit
                                dienst
                            </div>
                        </div>
                        <div class="ruimteboven">
                            <button name="submitMW" type="submit" class="btn-primary btn-lg btn">Update Medewerker
                            </button>
                        </div>
                    <? endif; ?>
                </div>

                <div class="col-1"></div>

            </div>
        </form>
        <div class="row">
            <div class="col-11 d-flex justify-content-end">
                <form method="post">
                    <button name="done" type="submit" class="btn-secondary btn-lg btn">Cancel</button>
                </form>
            </div>
        </div>
    <? elseif ($_SESSION["HasRequest"] == 'MR'): ?>
        <form method="post">
            <div class="row ruimteboven">

                <div class="col-1"></div>

                <div class="col-4">
                    <div>Select permissies voor de nieuwe rol</div>
                    <div>
                        <?
                        foreach ($_SESSION['perms'] as $permissie) {
                            $name = $permissie["COLUMN_NAME"];
                            echo "<div><input type='checkbox' name='selected_perm[]' value='$name'>$name</div>";
                        }
                        ?>

                    </div>
                </div>

                <div class="col-2"></div>

                <div class="col-4">
                    <label class="form-label">Naam nieuwe rol</label>
                    <input type="text" class="form-control" name="Name" required>
                    <button name="Newrol" type="submit" class="btn-primary btn-lg btn">Maak nieuwe rol</button>
                </div>

                <div class="col-1"></div>

            </div>
        </form>
        <div class="row">
            <div class="col-11 d-flex justify-content-end">
                <form method="post">
                    <button name="done" type="submit" class="btn-secondary btn-lg btn">Cancel</button>
                </form>
            </div>
        </div>
    <? elseif ($_SESSION["HasRequest"] == 'DR'): ?>
        <form method="post">
            <div class="row ruimteboven">

                <div class="col-1"></div>

                <div class="col-4">
                    <div>Selecteer een rol om te verwijderen</div>
                    <div>
                        <?
                        foreach ($_SESSION['roles'] as $role) {
                            $name = $role;
                            echo "<div><input type='radio' name='Role' value='$name[0]'>$name[0]</div>";
                        }
                        ?>

                    </div>
                </div>

                <div class="col-2"></div>

                <div class="col-4">
                    <label class="form-label">Delete een rol, deze actie kan niet worden teruggedraaid</label>
                    <button name="Deleterol" type="submit" class="btn-primary btn-lg btn">Delete selected rol
                    </button>
                </div>

                <div class="col-1"></div>

            </div>
        </form>
        <div class="row">
            <div class="col-11 d-flex justify-content-end">
                <form method="post">
                    <button name="done" type="submit" class="btn-secondary btn-lg btn">Cancel</button>
                </form>
            </div>
        </div>

    <? endif; ?>

</main>
<? include_once '../partials/footer.php'; ?>
</body>
</html>
