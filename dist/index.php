<?php
session_start();

include("connection.php");

function getBottlesRemaining($input_beerId)
{
    global $conn;

    $bottlesBrewed = getBottlesBrewed($input_beerId);
    $bottlesSold = getBottlesSold($input_beerId);

    return $bottlesBrewed - $bottlesSold;
}

function getBottlesBrewed($input_beerId)
{
    global $conn;

    $totalNumBottles = 0;

    // loop over all brews of this beer type
    $sql = "SELECT * FROM brews WHERE beerId = $input_beerId";
    $query = mysqli_query($conn, $sql) or die("Could not run SQL query.");

    while ($result = mysqli_fetch_assoc($query))
    {
        $res_numBottles = $result['numBottles'];

        $totalNumBottles += $res_numBottles;
    }

    return $totalNumBottles;
}

function getBottlesSold($input_beerId)
{
    global $conn;

    $totalNumBottles = 0;

    // loop over all orders and sum the order items of this beer type
    $sql = "SELECT * FROM orders";
    $query = mysqli_query($conn, $sql) or die("Could not run SQL query.");

    while ($result = mysqli_fetch_assoc($query))
    {
        $res_id = $result['id'];

        // get all the order items that belong to that order
        $sqlItem = "SELECT * FROM orderItems WHERE orderId = $res_id";
        $queryItem = mysqli_query($conn, $sqlItem) or die("Could not run SQL query.");

        while ($resultItem = mysqli_fetch_assoc($queryItem))
        {
            $orderItems_beerId = $resultItem['beerId'];
            $orderItems_quantity = $resultItem['quantity'];

            if ($orderItems_beerId == $input_beerId)
            {
                $totalNumBottles += $orderItems_quantity;
            }
        }
    }

    return $totalNumBottles;
}

function getBottlesSoldCurrentYear($input_beerId)
{
    global $conn;

    $numBottlesSold = [];

    // loop over all weeks of the year
    for ($week = 0; $week < 54; $week++)
    {
        $numBottlesThisWeek = 0;

        // loop over all orders and sum the order items of this beer type
        $sql = "SELECT * FROM orders WHERE YEAR(createDate) = YEAR(Now()) AND WEEK(createDate) = $week;";
        $query = mysqli_query($conn, $sql) or die("Could not run SQL query.");

        while ($result = mysqli_fetch_assoc($query))
        {
            $res_id = $result['id'];

            // get all the order items that belong to that order
            $sqlItem = "SELECT * FROM orderItems WHERE orderId = $res_id";
            $queryItem = mysqli_query($conn, $sqlItem) or die("Could not run SQL query.");

            while ($resultItem = mysqli_fetch_assoc($queryItem))
            {
                $orderItems_beerId = $resultItem['beerId'];
                $orderItems_quantity = $resultItem['quantity'];

                if ($orderItems_beerId == $input_beerId)
                {
                    $numBottlesThisWeek += $orderItems_quantity;
                }
            }
        }

        $numBottlesSold[$week] = $numBottlesThisWeek;
    }

    return $numBottlesSold;
}

function getBottlesBottledCurrentYear($input_beerId)
{
    global $conn;

    $numBottlesBottled = [];

    // loop over all weeks of the year
    for ($week = 0; $week < 54; $week++)
    {
        $numBottlesThisWeek = 0;

        // loop over all brews and sum the number of bottles of this beer type
        $sql = "SELECT * FROM brews WHERE YEAR(bottleDate) = YEAR(Now()) AND WEEK(bottleDate) = $week;";
        $query = mysqli_query($conn, $sql) or die("Could not run SQL query.");

        while ($result = mysqli_fetch_assoc($query))
        {
            $brews_beerId = $result['beerId'];
            $brews_numBottles = $result['numBottles'];

            if ($brews_beerId == $input_beerId)
            {
                $numBottlesThisWeek += $brews_numBottles;
            }
        }

        $numBottlesBottled[$week] = $numBottlesThisWeek;
    }

    return $numBottlesBottled;
}

if (!isset($_SESSION['username']))
{
    header("location:login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
    <script>
        function drawgraph()
        {
            // bottles bottled
            var ctx = document.getElementById("bottlesBottled");
            var myLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [
                    <?php
                    for ($week = 0; $week < 54; $week++)
                    {
                        echo "\"Woche $week\",";
                    }
                    ?>
                    ],
                datasets: [{
                label: "Flaschen Frisches Mais",
                lineTension: 0.3,
                backgroundColor: "rgba(255,255,255,0)",
                borderColor: "rgba(232, 224, 4, 1)",
                pointRadius: 5,
                pointBackgroundColor: "rgba(232, 224, 4, 1)",
                pointBorderColor: "rgba(255,255,255,0.8)",
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "rgba(232, 224, 4, 1)",
                pointHitRadius: 50,
                pointBorderWidth: 2,
                data: [
                    <?php
                    $bottlesBottled = getBottlesBottledCurrentYear(0);

                    for ($week = 0; $week < 54; $week++)
                    {
                        echo $bottlesBottled[$week];
                        echo ",";
                    }
                    ?>
                    ],
                },
                {
                label: "Flaschen Freaky Craft",
                lineTension: 0.3,
                backgroundColor: "rgba(255,255,255,0)",
                borderColor: "rgba(237, 28, 36, 1)",
                pointRadius: 5,
                pointBackgroundColor: "rgba(237, 28, 36, 1)",
                pointBorderColor: "rgba(255,255,255,0.8)",
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "rgba(237, 28, 36, 1)",
                pointHitRadius: 50,
                pointBorderWidth: 2,
                data: [
                    <?php
                    $bottlesBottled = getBottlesBottledCurrentYear(1);

                    for ($week = 0; $week < 54; $week++)
                    {
                        echo $bottlesBottled[$week];
                        echo ",";
                    }
                    ?>
                    ],
                },
                {
                label: "Flaschen Funky IPA",
                lineTension: 0.3,
                backgroundColor: "rgba(255,255,255,0)",
                borderColor: "rgba(76, 192, 112, 1)",
                pointRadius: 5,
                pointBackgroundColor: "rgba(76, 192, 112, 1)",
                pointBorderColor: "rgba(255,255,255,0.8)",
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "rgba(76, 192, 112, 1)",
                pointHitRadius: 50,
                pointBorderWidth: 2,
                data: [
                    <?php
                    $bottlesBottled = getBottlesBottledCurrentYear(2);

                    for ($week = 0; $week < 54; $week++)
                    {
                        echo $bottlesBottled[$week];
                        echo ",";
                    }
                    ?>
                    ],
                },
                {
                label: "Flaschen Volles Dinkel",
                lineTension: 0.3,
                backgroundColor: "rgba(255,255,255,0)",
                borderColor: "rgba(183, 70, 170, 1)",
                pointRadius: 5,
                pointBackgroundColor: "rgba(183, 70, 170, 1)",
                pointBorderColor: "rgba(255,255,255,0.8)",
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "rgba(183, 70, 170, 1)",
                pointHitRadius: 50,
                pointBorderWidth: 2,
                data: [
                    <?php
                    $bottlesBottled = getBottlesBottledCurrentYear(3);

                    for ($week = 0; $week < 54; $week++)
                    {
                        echo $bottlesBottled[$week];
                        echo ",";
                    }
                    ?>
                    ],
                },
                {
                label: "Flaschen Lovely Amber",
                lineTension: 0.3,
                backgroundColor: "rgba(255,255,255,0)",
                borderColor: "rgba(244, 106, 30, 1)",
                pointRadius: 5,
                pointBackgroundColor: "rgba(244, 106, 30, 1)",
                pointBorderColor: "rgba(255,255,255,0.8)",
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "rgba(244, 106, 30, 1)",
                pointHitRadius: 50,
                pointBorderWidth: 2,
                data: [
                    <?php
                    $bottlesBottled = getBottlesBottledCurrentYear(4);

                    for ($week = 0; $week < 54; $week++)
                    {
                        echo $bottlesBottled[$week];
                        echo ",";
                    }
                    ?>
                    ],
                }],
            },
            options: {
                scales: {
                xAxes: [{
                    time: {
                    unit: 'date'
                    },
                    gridLines: {
                    display: false
                    },
                    ticks: {
                    maxTicksLimit: 7
                    }
                }],
                yAxes: [{
                    ticks: {
                    min: 0,
                    //max: 40000,
                    //maxTicksLimit: 5
                    },
                    gridLines: {
                    color: "rgba(0, 0, 0, .125)",
                    }
                }],
                },
                legend: {
                display: false
                }
            }
            });

            // bottles sold
            var ctx2 = document.getElementById("bottlesSold");
            var myLineChart2 = new Chart(ctx2, {
            type: 'line',
            data: {
                labels: [
                    <?php
                    for ($week = 0; $week < 54; $week++)
                    {
                        echo "\"Woche $week\",";
                    }
                    ?>
                    ],
                datasets: [{
                label: "Flaschen Frisches Mais",
                lineTension: 0.3,
                backgroundColor: "rgba(255,255,255,0)",
                borderColor: "rgba(232, 224, 4, 1)",
                pointRadius: 5,
                pointBackgroundColor: "rgba(232, 224, 4, 1)",
                pointBorderColor: "rgba(255,255,255,0.8)",
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "rgba(232, 224, 4, 1)",
                pointHitRadius: 50,
                pointBorderWidth: 2,
                data: [
                    <?php
                    $bottlesSold = getBottlesSoldCurrentYear(0);

                    for ($week = 0; $week < 54; $week++)
                    {
                        echo $bottlesSold[$week];
                        echo ",";
                    }
                    ?>
                    ],
                },
                {
                label: "Flaschen Freaky Craft",
                lineTension: 0.3,
                backgroundColor: "rgba(255,255,255,0)",
                borderColor: "rgba(237, 28, 36, 1)",
                pointRadius: 5,
                pointBackgroundColor: "rgba(237, 28, 36, 1)",
                pointBorderColor: "rgba(255,255,255,0.8)",
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "rgba(237, 28, 36, 1)",
                pointHitRadius: 50,
                pointBorderWidth: 2,
                data: [
                    <?php
                    $bottlesSold = getBottlesSoldCurrentYear(1);

                    for ($week = 0; $week < 54; $week++)
                    {
                        echo $bottlesSold[$week];
                        echo ",";
                    }
                    ?>
                    ],
                },
                {
                label: "Flaschen Funky IPA",
                lineTension: 0.3,
                backgroundColor: "rgba(255,255,255,0)",
                borderColor: "rgba(76, 192, 112, 1)",
                pointRadius: 5,
                pointBackgroundColor: "rgba(76, 192, 112, 1)",
                pointBorderColor: "rgba(255,255,255,0.8)",
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "rgba(76, 192, 112, 1)",
                pointHitRadius: 50,
                pointBorderWidth: 2,
                data: [
                    <?php
                    $bottlesSold = getBottlesSoldCurrentYear(2);

                    for ($week = 0; $week < 54; $week++)
                    {
                        echo $bottlesSold[$week];
                        echo ",";
                    }
                    ?>
                    ],
                },
                {
                label: "Flaschen Volles Dinkel",
                lineTension: 0.3,
                backgroundColor: "rgba(255,255,255,0)",
                borderColor: "rgba(183, 70, 170, 1)",
                pointRadius: 5,
                pointBackgroundColor: "rgba(183, 70, 170, 1)",
                pointBorderColor: "rgba(255,255,255,0.8)",
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "rgba(183, 70, 170, 1)",
                pointHitRadius: 50,
                pointBorderWidth: 2,
                data: [
                    <?php
                    $bottlesSold = getBottlesSoldCurrentYear(3);

                    for ($week = 0; $week < 54; $week++)
                    {
                        echo $bottlesSold[$week];
                        echo ",";
                    }
                    ?>
                    ],
                },
                {
                label: "Flaschen Lovely Amber",
                lineTension: 0.3,
                backgroundColor: "rgba(255,255,255,0)",
                borderColor: "rgba(244, 106, 30, 1)",
                pointRadius: 5,
                pointBackgroundColor: "rgba(244, 106, 30, 1)",
                pointBorderColor: "rgba(255,255,255,0.8)",
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "rgba(244, 106, 30, 1)",
                pointHitRadius: 50,
                pointBorderWidth: 2,
                data: [
                    <?php
                    $bottlesSold = getBottlesSoldCurrentYear(4);

                    for ($week = 0; $week < 54; $week++)
                    {
                        echo $bottlesSold[$week];
                        echo ",";
                    }
                    ?>
                    ],
                }],
            },
            options: {
                scales: {
                xAxes: [{
                    time: {
                    unit: 'date'
                    },
                    gridLines: {
                    display: false
                    },
                    ticks: {
                    maxTicksLimit: 7
                    }
                }],
                yAxes: [{
                    ticks: {
                    min: 0,
                    //max: 40000,
                    //maxTicksLimit: 5
                    },
                    gridLines: {
                    color: "rgba(0, 0, 0, .125)",
                    }
                }],
                },
                legend: {
                display: false
                }
            }
            });
        }
    </script>
    <?php include "./inc/head.html" ?>
    <body class="sb-nav-fixed" onload="drawgraph()">
        <?php include "./inc/bars.html" ?>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <?php include "./inc/sidenav.php" ?>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Dashboard</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                        <div class="row">
                            <div class="col-lg-2 col-md-6">
                                <div class="card bg-frischesmais text-dark mb-4">
                                    <div class="card-body"><b>Frisches Mais</b></div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <span style="font-size: 0.75rem">
                                            <li>Stock: <?php echo getBottlesRemaining(0); ?> Flaschen</li>
                                            <li>Total gebraut: <?php echo getBottlesBrewed(0); ?> Flaschen</li>
                                            <li>Total verkauft+verschenkt: <?php echo getBottlesSold(0); ?> Flaschen</li>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <div class="card bg-vollesdinkel text-white mb-4">
                                    <div class="card-body"><b>Volles Dinkel</b></div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <span style="font-size: 0.75rem">
                                            <li>Stock: <?php echo getBottlesRemaining(3); ?> Flaschen</li>
                                            <li>Total gebraut: <?php echo getBottlesBrewed(3); ?> Flaschen</li>
                                            <li>Total verkauft+verschenkt: <?php echo getBottlesSold(3); ?> Flaschen</li>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <div class="card bg-funkyipa text-white mb-4">
                                    <div class="card-body"><b>Funky IPA</b></div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <span style="font-size: 0.75rem">
                                            <li>Stock: <?php echo getBottlesRemaining(2); ?> Flaschen</li>
                                            <li>Total gebraut: <?php echo getBottlesBrewed(2); ?> Flaschen</li>
                                            <li>Total verkauft+verschenkt: <?php echo getBottlesSold(2); ?> Flaschen</li>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <div class="card bg-lovelyamber text-white mb-4">
                                    <div class="card-body"><b>Lovely Amber</b></div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <span style="font-size: 0.75rem">
                                            <li>Stock: <?php echo getBottlesRemaining(4); ?> Flaschen</li>
                                            <li>Total gebraut: <?php echo getBottlesBrewed(4); ?> Flaschen</li>
                                            <li>Total verkauft+verschenkt: <?php echo getBottlesSold(4); ?> Flaschen</li>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <div class="card bg-freakycraft text-white mb-4">
                                    <div class="card-body"><b>Freaky Craft</b></div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <span style="font-size: 0.75rem">
                                            <li>Stock: <?php echo getBottlesRemaining(1); ?> Flaschen</li>
                                            <li>Total gebraut: <?php echo getBottlesBrewed(1); ?> Flaschen</li>
                                            <li>Total verkauft+verschenkt: <?php echo getBottlesSold(1); ?> Flaschen</li>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div>
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-area me-1"></i>
                                        Flaschen abgefüllt über das aktuelle Jahr
                                    </div>
                                    <div class="card-body"><canvas id="bottlesBottled" width="100%" height="20"></canvas></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div>
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-area me-1"></i>
                                        Flaschen verkauft+verschenkt über das aktuelle Jahr
                                    </div>
                                    <div class="card-body"><canvas id="bottlesSold" width="100%" height="20"></canvas></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid px-4">
                        <div class="row"><div class="col-xl-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Anzahl verkaufter Flaschen und ausgeliehene Harasse pro Kunde
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Anzahl Flaschen</th>
                                            <th>Ausgeliehene (noch nicht zurückgebrachte) Harasse</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT * FROM clients";
                                        $query = mysqli_query($conn, $sql) or die("Could not run SQL query.");

                                        while ($result = mysqli_fetch_assoc($query))
                                        {
                                            // count how many bottles this client has bought so far
                                            $clientQuantity = 0;
                                            $clientCrates = 0;
                                            $res_clientId = $result['id'];
                                            $res_prename = $result['prename'];
                                            $res_lastname = $result['lastname'];

                                            // get all orders for this client
                                            $sqlOrders = "SELECT * FROM orders WHERE clientId = $res_clientId";
                                            $queryOrders = mysqli_query($conn, $sqlOrders) or die("Could not run SQL query.");

                                            while ($resultOrders = mysqli_fetch_assoc($queryOrders))
                                            {
                                                $res_orderId = $resultOrders['id'];
                                                $res_numCrates = $resultOrders['numCrates'];

                                                // get all order items for this order
                                                $sqlOrderItems = "SELECT * FROM orderItems WHERE orderId = $res_orderId";
                                                $queryOrderItems = mysqli_query($conn, $sqlOrderItems) or die("Could not run SQL query.");

                                                while ($resultOrderItems = mysqli_fetch_assoc($queryOrderItems))
                                                {
                                                    $clientQuantity += $resultOrderItems['quantity'];
                                                }

                                                $clientCrates += $res_numCrates;
                                            }

                                            echo "<tr>\n";
                                            echo "    <td>$res_prename $res_lastname</td>\n";
                                            echo "    <td>$clientQuantity</td>\n";
                                            echo "    <td>$clientCrates</td>\n";
                                            echo "</tr>\n";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        </div></div>
                    </div>
                </main>
                <?php include "./inc/footer.html" ?>
            </div>
        </div>
        <?php include "./inc/scripts.html" ?>
    </body>
</html>
