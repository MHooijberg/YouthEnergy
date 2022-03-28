<?php
include_once "rbac_permissions_support.inc.php";

$neededPermissions = array();
$neededPermissions[] = "ReadOwnVerbruiksmeter";
ApproveOrRedirect($neededPermissions, true);

$user = 'website';       ///< the username to connect to the database
$pass = 'wachtwoord';    ///< the password to connect to the database
$connection = new PDO('mysql:host=localhost;dbname=energy', $user, $pass); ///< make the connection
$klantnummer = $_SESSION['klantnummer'];


//SQL query voor laatst gemeten meterstand
$KLANT_READ_METERSTANDELEC = "SELECT ms_stand FROM tbl_meters_standen 
JOIN tbl_meter_telwerken
    on ms_fk_idMeterTelwerk = mt_idMeterTelwerk 
LEFT JOIN tbl_meters
    on mt_fk_idMeter = m_idMeter
JOIN tbl_adressen
    on m_fk_idAdres = a_idAdres
JOIN tbl_klanten
    on k_fk_idAdres = a_idAdres
WHERE k_klantnummer = :klantnummer
AND mt_type='v'
AND m_product='E'
AND mt_telwerk=1
ORDER BY ms_datum DESC, ms_tijd DESC";
$statement = $connection->prepare($KLANT_READ_METERSTANDELEC);
$statement->bindParam(':klantnummer', $klantnummer, PDO::PARAM_INT);
$statement->execute();
$meterstandElec = $statement->fetchAll(PDO::FETCH_ASSOC);
$ms_total = 0;
$ms_count = 0;
foreach ($meterstandElec as $value){
    $ms_total += $value["ms_stand"];
    $ms_count++;
}
$ms_midElec = intval($ms_midElec = $ms_total / $ms_count);

//SQL query voor laatst gemeten meterstand
$KLANT_READ_METERSTANDGAS = "SELECT ms_stand FROM tbl_meters_standen 
JOIN tbl_meter_telwerken
    on ms_fk_idMeterTelwerk = mt_idMeterTelwerk 
LEFT JOIN tbl_meters
    on mt_fk_idMeter = m_idMeter
JOIN tbl_adressen
    on m_fk_idAdres = a_idAdres
JOIN tbl_klanten
    on k_fk_idAdres = a_idAdres
WHERE k_klantnummer = :klantnummer
AND mt_type='v'
AND m_product='G'
AND mt_telwerk=1
ORDER BY ms_datum DESC, ms_tijd DESC";
$statement = $connection->prepare($KLANT_READ_METERSTANDGAS);
$statement->bindParam(':klantnummer', $klantnummer, PDO::PARAM_INT);
$statement->execute();
$meterstandGas = $statement->fetchAll(PDO::FETCH_ASSOC);
$ms_total = 0;
$ms_count = 0;
foreach ($meterstandGas as $value){
    $ms_total += $value["ms_stand"];
    $ms_count++;
}
$ms_midGas = intval($ms_midGas = $ms_total / $ms_count);
?>
<!doctype html>
<html lang="en">
<link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="css/verbruiksmeter.css" rel="stylesheet">
<script src="/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="js/verbruiksmeter.js"></script>
</head>
<body>

<? include_once '../partials/navbarIndex.php'; ?>

<main class="container-fluid"><br>
    <h1>Verbruiksmeter</h1>
    <h5>De ondestaande meetgegevens zijn de actuele gegevens die wij uit de database halen.<br>
        Hieronder staan verschillende meterstanden met de bijbehorende gegevens:</h5><br>
    <div class="d-flex justify-content-between">
    <div id='myChart'></div>
    <div id='myChart2'></div>
    </div>
    <div class="d-flex justify-content-between">
    <div id='myChart3'></div>
    <div id='myChart4'></div>
    </div>
    <!--De onderstaande code is van een website genaamd zingchart.com.
    Doordat er niet op de gui werd beoordeeld hebben wij ervoor
    gekozen om een nette gui te kopieren om inplaats van deze zelf te schrijven.-->
    <script>
        var myConfig = {
            type: "gauge",
            globals: {
                fontSize: 20
            },
            plotarea:{
                marginTop:80
            },
            plot:{
                size:'100%',
                valueBox: {
                    placement: 'center',
                    text: 'De meetgegevens van het laatst <br> Gemeten Gas verbruik:<br><?php echo $meterstandGas[0]["ms_stand"] ?>',
                    fontSize: 18,
                }
            },
            tooltip:{
                borderRadius:5
            },
            scaleR:{
                minValue:0,
                maxValue:32000,
                step: 1000,
                center:{
                    visible:false
                },
                item:{
                    offsetR:0,
                    rules:[
                        {
                            rule:'%i == 9',
                            offsetX:15
                        }
                    ]
                },
                ring:{
                    size:50,
                    rules:[
                        {
                            rule:'%v <= 11000',
                            backgroundColor:'#29B6F6'
                        },
                        {
                            rule:'%v > 11000 && %v < 20000',
                            backgroundColor:'#FFA726'
                        },
                        {
                            rule:'%v >= 20000 && %v < 28000',
                            backgroundColor:'#EF5350'
                        },
                        {
                            rule:'%v >= 28000',
                            backgroundColor:'#E53935'
                        }
                    ]
                }
            },
            series : [
                {
                    values : [<?php echo $meterstandGas[0]["ms_stand"] ?>], // starting value <- hier moeten values komen uit database
                    backgroundColor:'black',
                    indicator:[10,10,10,10,0.75],
                    animation:{
                        effect:2,
                        method:1,
                        sequence:4,
                        speed: 900
                    },
                }
            ]
        };
        zingchart.render({
            id : 'myChart',
            data : myConfig,
            height: 500,
            width: '100%'
        });
    </script>
    <script>
        var myConfig2 = {
            type: "gauge",
            globals: {
                fontSize: 20
            },
            plotarea:{
                marginTop:80
            },
            plot:{
                size:'100%',
                valueBox: {
                    placement: 'center',
                    text: 'De meetgegevens van het laatst <br> Gemeten Energie verbruik:<br><?php echo $meterstandElec[0]["ms_stand"] ?>',
                    fontSize:18,
                }
            },
            tooltip:{
                borderRadius:5
            },
            scaleR:{
                minValue:0,
                maxValue:32000,
                step: 1000,
                center:{
                    visible:false
                },
                item:{
                    offsetR:0,
                    rules:[
                        {
                            rule:'%i == 9',
                            offsetX:15
                        }
                    ]
                },
                ring:{
                    size:50,
                    rules:[
                        {
                            rule:'%v <= 11000',
                            backgroundColor:'#29B6F6'
                        },
                        {
                            rule:'%v > 11000 && %v < 20000',
                            backgroundColor:'#FFA726'
                        },
                        {
                            rule:'%v >= 20000 && %v < 28000',
                            backgroundColor:'#EF5350'
                        },
                        {
                            rule:'%v >= 28000',
                            backgroundColor:'#E53935'
                        }
                    ]
                }
            },
            series : [
                {
                    values : [<?php echo $meterstandElec[0]["ms_stand"] ?>], // starting value <- hier moeten values komen uit database
                    backgroundColor:'black',
                    indicator:[10,10,10,10,0.75],
                    animation:{
                        effect:2,
                        method:1,
                        sequence:4,
                        speed: 900
                    },
                }
            ]
        };
        zingchart.render({
            id : 'myChart2',
            data : myConfig2,
            height: 500,
            width: '100%'
        });
    </script>
    <script>
        var myConfig3 = {
            type: "gauge",
            globals: {
                fontSize: 20
            },
            plotarea:{
                marginTop:80
            },
            plot:{
                size:'100%',
                valueBox: {
                    placement: 'center',
                    text: 'De meetgegevens van het gemiddelde <br> Gas verbruik:<br><?php echo $ms_midGas ?>',
                    fontSize:16,
                }
            },
            tooltip:{
                borderRadius:5
            },
            scaleR:{
                minValue:0,
                maxValue:32000,
                step: 1000,
                center:{
                    visible:false
                },
                item:{
                    offsetR:0,
                    rules:[
                        {
                            rule:'%i == 9',
                            offsetX:15
                        }
                    ]
                },
                ring:{
                    size:50,
                    rules:[
                        {
                            rule:'%v <= 11000',
                            backgroundColor:'#29B6F6'
                        },
                        {
                            rule:'%v > 11000 && %v < 20000',
                            backgroundColor:'#FFA726'
                        },
                        {
                            rule:'%v >= 20000 && %v < 28000',
                            backgroundColor:'#EF5350'
                        },
                        {
                            rule:'%v >= 28000',
                            backgroundColor:'#E53935'
                        }
                    ]
                }
            },
            series : [
                {
                    values : [<?php echo $ms_midGas ?>], // starting value <- hier moeten values komen uit database
                    backgroundColor:'black',
                    indicator:[10,10,10,10,0.75],
                    animation:{
                        effect:2,
                        method:1,
                        sequence:4,
                        speed: 900
                    },
                }
            ]
        };
        zingchart.render({
            id : 'myChart3',
            data : myConfig3,
            height: 500,
            width: '100%'
        });
    </script>
    <script>
        var myConfig4 = {
            type: "gauge",
            globals: {
                fontSize: 20
            },
            plotarea:{
                marginTop:80
            },
            plot:{
                size:'100%',
                valueBox: {
                    placement: 'center',
                    text: 'De meetgegevens van het gemiddelde <br> Energie verbruik:<br><?php echo $ms_midElec ?>',
                    fontSize:16,
                }
            },
            tooltip:{
                borderRadius:5
            },
            scaleR:{
                minValue:0,
                maxValue:32000,
                step: 1000,
                center:{
                    visible:false
                },
                item:{
                    offsetR:0,
                    rules:[
                        {
                            rule:'%i == 9',
                            offsetX:15
                        }
                    ]
                },
                ring:{
                    size:50,
                    rules:[
                        {
                            rule:'%v <= 11000',
                            backgroundColor:'#29B6F6'
                        },
                        {
                            rule:'%v > 11000 && %v < 20000',
                            backgroundColor:'#FFA726'
                        },
                        {
                            rule:'%v >= 20000 && %v < 28000',
                            backgroundColor:'#EF5350'
                        },
                        {
                            rule:'%v >= 28000',
                            backgroundColor:'#E53935'
                        }
                    ]
                }
            },
            series : [
                {
                    values : [<?php echo $ms_midElec ?>], // starting value <- hier moeten values komen uit database
                    backgroundColor:'black',
                    indicator:[10,10,10,10,0.75],
                    animation:{
                        effect:2,
                        method:1,
                        sequence:4,
                        speed: 900
                    },
                }
            ]
        };
        zingchart.render({
            id : 'myChart4',
            data : myConfig4,
            height: 500,
            width: '100%'
        });
    </script>
</main>
<div class="fixed-bottom">
<? include_once '../partials/footer.php'; ?>
</div>
</body>
</html>
