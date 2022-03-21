<?php
$user = 'website';       ///< the username to connect to the database
$pass = 'wachtwoord';    ///< the password to connect to the database
$connection = new PDO('mysql:host=localhost;dbname=energy', $user, $pass); ///< make the connection

if (isset($_POST['getPostcode'])){
    $inputPostcode = $_POST['postcode'];
}
$BACKOFFICE_READ_AANTAL_METERS_BY_POSTCODE = "
SELECT a_postcode as postcode,
       ms_datum   as datum,
       m_product  as product,
       count(*)   as aantalMeterstanden
FROM tbl_adressen
         JOIN tbl_meters ON m_fk_idAdres = a_idAdres
         JOIN tbl_meter_telwerken ON mt_fk_idMeter = m_idMeter
         JOIN tbl_meters_standen ON ms_fk_idMeterTelwerk = mt_idMeterTelwerk
WHERE a_postcode = '$inputPostcode' 
GROUP BY a_postcode, ms_datum, m_product;";

$statement = $connection->prepare($BACKOFFICE_READ_AANTAL_METERS_BY_POSTCODE);
$statement->execute();
$result = $statement->fetchAll();

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My Energy Portal</title>
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="/bootstrap/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<? include_once '../partials/navbarIndex.php'; ?>
<main class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1>BACK-OFFICE</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <form method="post">
                <div class="form-group m-3 p-3">
                    <label for="postcode">Postcode:</label>
                    <input type="text"
                           id="postcode"
                           name="postcode">
                    <input type="submit"
                           name="getPostcode"
                           value="zoek">
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="table table-striped">
                <thead class="table-dark">
                <tr>
                    <th scope="col">Postcode</th>
                    <th scope="col">Datum</th>
                    <th scope="col">Product</th>
                    <th scope="col">Aantal</th>
                </tr>
                </thead>
                <tbody>
                <!-- print de rows uit voor de opgevraagde data-->
                <?php foreach ($result as $row) {
                    $postcode = $row["postcode"];
                    $datum = $row["datum"];
                    $product = $row["product"];
                    $meterstand = $row["aantalMeterstanden"];
                    echo '<tr> <th scope="row">' . $postcode . '</th>';
                    echo '<td>' . $datum . '</td>';
                    echo '<td>' . $product . '</td>';
                    echo '<td>' . $meterstand . '</td></tr>';
                } ?>

                </tbody>

            </table>
        </div>
</main>

<? include_once '../partials/footer.php'; ?>
</body>
</html>