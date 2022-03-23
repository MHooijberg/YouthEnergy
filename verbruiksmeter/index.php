<?php
$user = 'website';       ///< the username to connect to the database
$pass = 'wachtwoord';    ///< the password to connect to the database
$connection = new PDO('mysql:host=localhost;dbname=energy', $user, $pass); ///< make the connection
$klantnummer = 1575783258;

// foreach($meterstand as $meter){
//       echo '<h1>'.$meter["ms_stand"].' </h1>';
//    }

//SQL query voor laatst gemeten meterstand
$KLANT_READ_METERSTAND = "SELECT ms_stand FROM tbl_meters_standen 
JOIN tbl_meter_telwerken
    on mt_fk_idMeter = mt_idMeterTelwerk
JOIN tbl_meters
    on m_fk_idAdres = m_idMeter
JOIN tbl_klanten
    on k_fk_idAdres = m_fk_idAdres
WHERE k_klantnummer = :klantnummer 
ORDER BY ms_datum DESC, ms_tijd DESC";
$statement = $connection->prepare($KLANT_READ_METERSTAND);
$statement->bindParam(':klantnummer', $klantnummer, PDO::PARAM_INT);
$statement->execute();
$meterstand = $statement->fetchAll(PDO::FETCH_ASSOC);

//SQL query voor gemiddeld energie
$KLANT_READ_GEMIDDELDE_METERSTAND_ENERGY = "
";


?>
<!doctype html>
<html lang="en">
<link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="css/index.css" rel="stylesheet">
<script src="/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="verbruiksmeter.js"></script>
</head>
<body>

<? include_once '../partials/navbarIndex.php'; ?>

<main class="container-fluid">
    <div id='myChart'></div>
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
                    values : [<?php echo $meterstand[0]["ms_stand"] ?>],
                    fontSize:35,
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
                    values : [<?php echo $meterstand[0]["ms_stand"] ?>], // starting value <- hier moeten values komen uit database
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
</main>

<? include_once '../partials/footer.php'; ?>
</body>
</html>
