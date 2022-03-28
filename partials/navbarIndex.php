<?php
if (session_status() == PHP_SESSION_NONE){
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light" role="navigation">
    <a class="navbar-brand" href="#">
        <img src="/images/logo.png" width="30" height="30" alt="">
    </a>
    <ul class="navbar-nav mr-auto">
        <li class="nav-item"><a href="/index.php" class="nav-link">Home</a></li>
        <li class="nav-item"><a href="/intranet" class="nav-link">Intranet</a></li>
        <?php
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                if (isset($_SESSION['permissions'])) {
                    $permissions = $_SESSION['permissions'];
                    $verbruiksmeter = $permissions['ReadOwnVerbruiksmeter'] == true;
                    $klantgegevens = $permissions['ReadOwnKlantgegevens'] == true;
                    $backoffice = ($permissions['ReadMeetgegevensPostcode'] == true |
                        $permissions['ReadMeetgegevensGemeente'] == true |
                        $permissions['ReadMeetgegevensPlaatsnaam'] == true |
                        $permissions['ReadMeetgegevensStraat'] == true
                    );
                    $verkoop = ($permissions['ReadMeetgegevensPostcode'] |
                        $permissions['ReadMeetgegevensGemeente'] |
                        $permissions['ReadMeetgegevensPlaatsnaam'] |
                        $permissions['ReadMeetgegevensStraat'] |
                        $permissions['ExportMeetgegevensBulk']
                    );
                    $klantenservice = ($permissions['ReadKlantgegevensSingle'] == true |
                        $permissions['ReadMeetgegevensSingle'] == true
                    );
                    $ict = ($permissions['CreateRol'] == true |
                        $permissions['EditRole'] == true |
                        $permissions['DeleteRole'] == true
                    );
                    if ($verbruiksmeter){
                        echo '<li class="nav-item"><a href="/intranet/Verbruiksmeter.php" class="nav-link">Verbruiksmeter</a></li>';
                    }
                    if ($klantgegevens){
                        echo '<li class="nav-item"><a href="/intranet/Klantgegevens.php" class="nav-link">Klantgegevens</a></li>';
                    }
                    if ($backoffice){
                        echo '<li class="nav-item"><a href="/intranet/Backoffice.php" class="nav-link">Backoffice</a> </li>';
                    }
                    if ($verkoop){
                        echo '<li class="nav-item"><a href="/intranet/Verkoop.php" class="nav-link">Verkoop</a> </li>';
                    }
                    if ($klantenservice){
                        echo '<li class="nav-item"><a href="/intranet/KlantenService.php"class="nav-link">Klantenservice</a></li>';
                    }
                    if ($ict){
                        echo '<li class="nav-item"><a href="/intranet/ICT.php"class="nav-link">ICT</a></li>';
                    }
                }
            }
            else {
                echo '<li class="nav-item"><a href="/accountaanmaken.php" class="nav-link">Account Aanvragen</a></li>';
            }
        ?>
    </ul>
</nav>
