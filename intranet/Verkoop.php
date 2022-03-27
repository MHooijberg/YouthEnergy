<?php
$user = 'website';       ///< the username to connect to the database
$pass = 'wachtwoord';    ///< the password to connect to the database
$connection = new PDO('mysql:host=localhost;dbname=energy', $user, $pass); ///< make the connection

session_start();
if (!isset($_SESSION["geografische_eenheid"])) {
    $_SESSION["geografische_eenheid"] = 0;
}
// 1 = postcode
// 2 = plaatsnaam
// 3 = gemeente

//Zoek aantal meters per postcode
//SQL-query met parameters
if (isset($_POST['get_postcode'])) {
    $input_postcode = $_POST['postcode'];
    $_SESSION['geografische_eenheid'] = 1;
}

$BACKOFFICE_READ_AANTAL_METERS_BY_POSTCODE = $connection->prepare("
SELECT a_postcode as postcode,
       ms_datum   as datum,
       m_product  as product,
       count(*)   as aantalMeterstanden
FROM tbl_adressen
         JOIN tbl_meters ON m_fk_idAdres = a_idAdres
         JOIN tbl_meter_telwerken ON mt_fk_idMeter = m_idMeter
         JOIN tbl_meters_standen ON ms_fk_idMeterTelwerk = mt_idMeterTelwerk
WHERE a_postcode = :postcode 
GROUP BY a_postcode, ms_datum, m_product;");
$BACKOFFICE_READ_AANTAL_METERS_BY_POSTCODE->bindParam(':postcode', $input_postcode);
$BACKOFFICE_READ_AANTAL_METERS_BY_POSTCODE->execute();
$result_BACKOFFICE_READ_AANTAL_METERS_BY_POSTCODE = $BACKOFFICE_READ_AANTAL_METERS_BY_POSTCODE->fetchAll();

//zoek aantal meters per plaatsnaam
//SQL-query met parameters
if (isset($_POST['get_plaatsnaam'])) {
    $input_plaatsnaam = $_POST['plaatsnaam'];
    $_SESSION['geografische_eenheid'] = 2;
}
$BACKOFFICE_READ_AANTAL_METERS_BY_PLAATSNAAM = $connection->prepare("
SELECT a_plaatsnaam as plaatsnaam,
       ms_datum   as datum,
       m_product  as product,
       count(*)   as aantalMeterstanden
FROM tbl_adressen
         JOIN tbl_meters ON m_fk_idAdres = a_idAdres
         JOIN tbl_meter_telwerken ON mt_fk_idMeter = m_idMeter
         JOIN tbl_meters_standen ON ms_fk_idMeterTelwerk = mt_idMeterTelwerk
WHERE a_plaatsnaam = :plaatsnaam 
GROUP BY a_plaatsnaam, ms_datum, m_product;");
$BACKOFFICE_READ_AANTAL_METERS_BY_PLAATSNAAM->bindParam(':plaatsnaam', $input_plaatsnaam);
$BACKOFFICE_READ_AANTAL_METERS_BY_PLAATSNAAM->execute();
$result_BACKOFFICE_READ_AANTAL_METERS_BY_PLAATSNAAM = $BACKOFFICE_READ_AANTAL_METERS_BY_PLAATSNAAM->fetchAll();

//zoek aantal meters per gemeente
//SQL-query met parameters
if (isset($_POST['get_gemeente'])) {
    $input_gemeente = $_POST['gemeente'];
    $_SESSION['geografische_eenheid'] = 3;
}
$BACKOFFICE_READ_AANTAL_METERS_BY_GEMEENTE = $connection->prepare("
SELECT a_gemeente as gemeente,
       ms_datum   as datum,
       m_product  as product,
       count(*)   as aantalMeterstanden
FROM tbl_adressen
         JOIN tbl_meters ON m_fk_idAdres = a_idAdres
         JOIN tbl_meter_telwerken ON mt_fk_idMeter = m_idMeter
         JOIN tbl_meters_standen ON ms_fk_idMeterTelwerk = mt_idMeterTelwerk
WHERE a_gemeente = :gemeente 
GROUP BY a_gemeente, ms_datum, m_product;");
$BACKOFFICE_READ_AANTAL_METERS_BY_GEMEENTE->bindParam(':gemeente', $input_gemeente);
$BACKOFFICE_READ_AANTAL_METERS_BY_GEMEENTE->execute();
$result_BACKOFFICE_READ_AANTAL_METERS_BY_GEMEENTE = $BACKOFFICE_READ_AANTAL_METERS_BY_GEMEENTE->fetchAll();

if (isset($_POST['export']))
{
    $file_name = "export_aantal_meters_" . date('Y-m-d') . "xls";
    $fields = array('#', 'geografische eenheid', 'Datum', 'Product', 'Aantal Meters');
    $excel_data = implode("\t", array_values($fields)) . "\n";

    foreach ($result_BACKOFFICE_READ_AANTAL_METERS_BY_POSTCODE as $result) {
        $linedata = array($result['postcode'], $result['datum'], $result['product'], $result['aantalMeterstanden']);
    }
    $excel_data .= implode("\t", array_values($linedata)) . "\n";

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$file_name\"");
}

?>

    <!doctype html>
    <html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verkoop - Youth Energy</title>
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="/bootstrap/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<?php include_once '../partials/navbarIndex.php'; ?>

<main>
    <div class="container-fluid"
    <div class="row">
        <div class="col-12 d-flex justify-content-center">
            <h1>Verkoop</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-3">
            <form method="post">
                <div class="form-group m-3 p-3">
                    <label for="postcode">Postcode:</label> <br>
                    <input type="text"
                           id="postcode"
                           name="postcode">
                    <input type="submit"
                           name="get_postcode"
                           value="zoek"
                           class="btn btn-primary">
                </div>
            </form>
        </div>
        <div class="col-3">
            <form method="post">
                <div class="form-group m-3 p-3">
                    <label for="plaatsnaam">Plaatsnaam:</label> <br>
                    <input type="text"
                           id="plaatsnaam"
                           name="plaatsnaam">
                    <input type="submit"
                           name="get_plaatsnaam"
                           value="zoek"
                           class="btn btn-primary">
                </div>
            </form>
        </div>
        <div class="col-3">
            <form method="post">
                <div class="form-group m-3 p-3">
                    <label for="gemeente">Gemeente:</label> <br>
                    <input type="text"
                           id="gemeente"
                           name="gemeente">
                    <input type="submit"
                           name="get_gemeente"
                           value="zoek"
                           class="btn btn-primary">
                </div>
            </form>
        </div>
        <div class="col-3">
            <form method="post">
                <div class="form-group m-3 p-3">
                    <label>Exporteer: </label> <br>
                    <input type="submit"
                           name="export"
                           value="Exporteer"
                           class="btn btn-warning">
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12 m-100">
            <table class="table table-striped">
                <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <?php
                    switch ($_SESSION['geografische_eenheid']) {
                        case 1:
                            echo '<th scope="col">' . "Postcode" . '</th>';
                            break;
                        case 2:
                            echo '<th scope="col">' . "Plaatnaam" . '</th>';
                            break;
                        case 3:
                            echo '<th scope="col">' . "Gemeente" . '</th>';
                            break;
                        default:
                            echo '<th scope="col">' . "Zoek" . '</th>';
                    } ?>
                    <th scope="col">Datum</th>
                    <th scope="col">Product</th>
                    <th scope="col">Aantal meters</th>
                </tr>
                </thead>
                <tbody>
                <!-- print de rows uit voor de opgevraagde data-->
                <?php
                $index = 1;
                switch ($_SESSION['geografische_eenheid']) {
                    case 1:
                        foreach ($result_BACKOFFICE_READ_AANTAL_METERS_BY_POSTCODE as $row_postcode) {
                            $postcode = $row_postcode["postcode"];
                            $datum = $row_postcode["datum"];
                            $product = $row_postcode["product"];
                            $meterstand = $row_postcode["aantalMeterstanden"];
                            echo '<tr> <th scope="row">' . $index++ . '</th>';
                            echo '<td>' . $postcode . '</td>';
                            echo '<td>' . $datum . '</td>';
                            echo '<td>' . $product . '</td>';
                            echo '<td>' . $meterstand . '</td></tr>';
                        }
                        break;
                    case 2:
                        foreach ($result_BACKOFFICE_READ_AANTAL_METERS_BY_PLAATSNAAM as $result_plaatsnaam) {
                            $plaatsnaam = $result_plaatsnaam["plaatsnaam"];
                            $datum = $result_plaatsnaam["datum"];
                            $product = $result_plaatsnaam["product"];
                            $meterstand = $result_plaatsnaam["aantalMeterstanden"];
                            echo '<tr> <th scope="row">' . $index++ . '</th>';
                            echo '<td>' . $plaatsnaam . '</td>';
                            echo '<td>' . $datum . '</td>';
                            echo '<td>' . $product . '</td>';
                            echo '<td>' . $meterstand . '</td></tr>';
                        }
                        break;
                    case 3:
                        foreach ($result_BACKOFFICE_READ_AANTAL_METERS_BY_GEMEENTE as $result_gemeente) {
                            $gemeente = $result_gemeente["gemeente"];
                            $datum = $result_gemeente["datum"];
                            $product = $result_gemeente["product"];
                            $meterstand = $result_gemeente["aantalMeterstanden"];
                            echo '<tr> <th scope="row">' . $index++ . '</th>';
                            echo '<td>' . $gemeente . '</td>';
                            echo '<td>' . $datum . '</td>';
                            echo '<td>' . $product . '</td>';
                            echo '<td>' . $meterstand . '</td></tr>';
                        }
                        break;
                    default:
                } ?>

                </tbody>
            </table>
        </div>

    </div><!--Container closing tag-->
</main>

<?php include_once '../partials/footer.php'; ?>
</body>
    </html><?php
