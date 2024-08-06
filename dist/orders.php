<?php
session_start();

include("connection.php");

//if (!isset($_SESSION['username'])) {
//    header("location:login.php");
//}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Dashboard - SB Admin</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <!-- Bootstrap Font Icon CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="index.html">Dorfbräu Admin</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#!">Settings</a></li>
                        <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="#!">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                        <div class="sb-sidenav-menu-heading">Info</div>
                            <a class="nav-link" href="index.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <a class="nav-link" href="prices.html">
                                <div class="sb-nav-link-icon"><i class="bi bi-currency-dollar"></i></div>
                                Preisgestaltung
                            </a>
                            <div class="sb-sidenav-menu-heading">Edit</div>
                            <a class="nav-link" href="orders.php">
                                <div class="sb-nav-link-icon"><i class="bi bi-cart-fill"></i></div>
                                Bestellungen
                            </a>
                            <a class="nav-link" href="neworder.php">
                                <div class="sb-nav-link-icon"><i class="bi bi-plus-circle"></i></div>
                                Neue Bestellung
                            </a>
                            <a class="nav-link" href="clients.php">
                                <div class="sb-nav-link-icon"><i class="bi bi-file-earmark-person"></i></div>
                                Kunden
                            </a>
                            <a class="nav-link" href="newclient.php">
                                <div class="sb-nav-link-icon"><i class="bi bi-plus-circle"></i></div>
                                Neuer Kundeneintrag
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        Start Bootstrap
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Bestellungen</h1>
                        <p><a class="btn btn-primary" href="neworder.php"><i class="bi bi-cart-plus"></i> Neue Bestellung</a></p>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Offene Bestellungen
                            </div>

                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>Erstelldatum</th>
                                            <th>Erstellt von</th>
                                            <th>Kunde</th>
                                            <th>Bestellung</th>
                                            <th>Preis</th>
                                            <th>Status</th>
                                            <th>Notizen</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT * FROM orders WHERE deliveryStatusId=0 OR paymentStatusId=0 OR bankaccountStatusId=0 OR crateStatusId=0";
                                        $query = mysqli_query($conn, $sql) or die("Could not run SQL query.");

                                        while ($result = mysqli_fetch_assoc($query))
                                        {
                                            $res_id = $result['id'];
                                            $res_date = $result['createDate'];
                                            $res_userId = $result['userId'];
                                            $res_clientId = $result['clientId'];
                                            $res_price = $result['price'];
                                            $res_numCrates = $result['numCrates'];
                                            $res_crateStatusId = $result['crateStatusId'];
                                            $res_deliveryStatusId = $result['deliveryStatusId'];
                                            $res_paymentStatusId = $result['paymentStatusId'];
                                            $res_bankaccountStatusId = $result['bankaccountStatusId'];
                                            $res_notes = $result['notes'];

                                            echo "<tr>\n";
                                            echo "    <td>$res_date</td>\n";

                                            // get name for userId
                                            $sqlUser = "SELECT * FROM users WHERE id = $res_userId";
                                            $queryUser = mysqli_query($conn, $sqlUser) or die("Could not run SQL query.");
                                            $resultUser = mysqli_fetch_assoc($queryUser);
                                            $userName = $resultUser['prename'];
                                            echo "    <td>$userName</td>\n";

                                            // get name for clientId
                                            $sqlClient = "SELECT * FROM clients WHERE id = $res_clientId";
                                            $queryClient = mysqli_query($conn, $sqlClient) or die("Could not run SQL query.");
                                            $resultClient = mysqli_fetch_assoc($queryClient);
                                            $clientName = $resultClient['prename']." ".$resultClient['lastname'];
                                            echo "    <td>$clientName</td>\n";

                                            // get all the order items that belong to that order
                                            $sqlItem = "SELECT * FROM orderItems WHERE orderId = $res_id";
                                            $queryItem = mysqli_query($conn, $sqlItem) or die("Could not run SQL query.");

                                            echo "    <td>\n";
                                            echo "        <ul style=\"list-style-type:none;\">\n";

                                            // check if we have any non-gift item in the orderlist
                                            $anyNonGift = 0;

                                            while ($resultItem = mysqli_fetch_assoc($queryItem))
                                            {
                                                $orderItems_beerId = $resultItem['beerId'];
                                                $orderItems_amount = $resultItem['amount'];
                                                $orderItems_containerId = $resultItem['containerId'];
                                                $orderItems_gift = $resultItem['gift'];

                                                // get name for beerId
                                                $sqlBeer = "SELECT * FROM beers WHERE id = $orderItems_beerId";
                                                $queryBeer = mysqli_query($conn, $sqlBeer) or die("Could not run SQL query.");
                                                $resultBeer = mysqli_fetch_assoc($queryBeer);
                                                $beerType = $resultBeer['type'];

                                                $container = ($orderItems_containerId == 0) ? "Fl." : "Keg";
                                                $gift = ($orderItems_gift == 1) ? "<i class=\"bi bi-gift-fill\"></i>" : "";

                                                if ($orderItems_gift == 0)
                                                {
                                                    $anyNonGift = 1;
                                                }

                                                switch($beerType)
                                                {
                                                    case "Lovely Amber":
                                                        echo "    <li><span style=\"height: 15px; width: 15px; background-color: #f46a1e; border-radius: 50%; display: inline-block;\"></span> $orderItems_amount $container $beerType $gift</li>\n";
                                                        break;
                                                    case "Funky IPA":
                                                        echo "    <li><span style=\"height: 15px; width: 15px; background-color: #4cc070; border-radius: 50%; display: inline-block;\"></span> $orderItems_amount $container $beerType $gift</li>\n";
                                                        break;
                                                    case "Frisches Mais":
                                                        echo "    <li><span style=\"height: 15px; width: 15px; background-color: #e8e004; border-radius: 50%; display: inline-block;\"></span> $orderItems_amount $container $beerType $gift</li>\n";
                                                        break;
                                                    case "Freaky Craft":
                                                        echo "    <li><span style=\"height: 15px; width: 15px; background-color: #ed1c24; border-radius: 50%; display: inline-block;\"></span> $orderItems_amount $container $beerType $gift</li>\n";
                                                        break;
                                                    case "Volles Dinkel":
                                                        echo "    <li><span style=\"height: 15px; width: 15px; background-color: #b746aa; border-radius: 50%; display: inline-block;\"></span> $orderItems_amount $container $beerType $gift</li>\n";
                                                        break;
                                                    default:
                                                        echo "    <li><span style=\"height: 15px; width: 15px; background-color: #ffffff; border-radius: 50%; display: inline-block;\"></span> $orderItems_amount $container $beerType $gift</li>\n";
                                                        break;
                                                }
                                            }

                                            if ($res_numCrates > 1)
                                            {
                                                echo "    <li>&mdash;</li><li><i class=\"bi bi-box-seam\"></i> $res_numCrates Harasse</li>\n";
                                            }
                                            else if ($res_numCrates > 0)
                                            {
                                                echo "    <li>&mdash;</li><li><i class=\"bi bi-box-seam\"></i> $res_numCrates Harass</li>\n";
                                            }

                                            echo "        </ul>\n";
                                            echo "    </td>\n";

                                            echo "    <td>$res_price CHF</td>\n";

                                            // status
                                            echo "    <td>\n";
                                            echo "        <ul style=\"list-style-type:none;\">\n";
                                            if ($res_deliveryStatusId == 1)
                                            {
                                                echo "        <li><i style=\"color:green;\" class=\"bi bi-check\"></i>geliefert</li>\n";
                                            }
                                            else
                                            {
                                                echo "        <li><i style=\"color:red;\" class=\"bi bi-x\"></i>noch nicht geliefert</li>\n";
                                            }

                                            // any orderitem which is not a gift?
                                            if ($anyNonGift == 1)
                                            {
                                                if ($res_paymentStatusId == 1)
                                                {
                                                    echo "        <li><i style=\"color:green;\" class=\"bi bi-check\"></i>bezahlt</li>\n";
                                                }
                                                else
                                                {
                                                    echo "        <li><i style=\"color:red;\" class=\"bi bi-x\"></i>noch nicht bezahlt</li>\n";
                                                }
                                                if ($res_bankaccountStatusId == 1)
                                                {
                                                    echo "        <li><i style=\"color:green;\" class=\"bi bi-check\"></i>Geld auf Konto</li>\n";
                                                }
                                                else
                                                {
                                                    echo "        <li><i style=\"color:red;\" class=\"bi bi-x\"></i>Geld noch nicht auf Konto</li>\n";
                                                }
                                            }

                                            if ($res_numCrates > 0)
                                            {
                                                if ($res_crateStatusId == 1)
                                                {
                                                    echo "        <li><i style=\"color:green;\" class=\"bi bi-check\"></i>Harass zurück</li>\n";
                                                }
                                                else
                                                {
                                                    echo "        <li><i style=\"color:red;\" class=\"bi bi-x\"></i>Harass noch nicht zurück</li>\n";
                                                }
                                            }
                                            echo "        </ul>\n";
                                            echo "    </td>\n";
                                            echo "    <td>$res_notes</td>\n";
                                            echo "</tr>\n";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Alle Bestellungen
                            </div>

                            <div class="card-body">
                                <table id="datatablesSimple2">
                                    <thead>
                                        <tr>
                                            <th>Erstelldatum</th>
                                            <th>Erstellt von</th>
                                            <th>Kunde</th>
                                            <th>Bestellung</th>
                                            <th>Preis</th>
                                            <th>Status</th>
                                            <th>Notizen</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT * FROM orders";
                                        $query = mysqli_query($conn, $sql) or die("Could not run SQL query.");

                                        while ($result = mysqli_fetch_assoc($query))
                                        {
                                            $res_id = $result['id'];
                                            $res_date = $result['createDate'];
                                            $res_userId = $result['userId'];
                                            $res_clientId = $result['clientId'];
                                            $res_price = $result['price'];
                                            $res_numCrates = $result['numCrates'];
                                            $res_crateStatusId = $result['crateStatusId'];
                                            $res_deliveryStatusId = $result['deliveryStatusId'];
                                            $res_paymentStatusId = $result['paymentStatusId'];
                                            $res_bankaccountStatusId = $result['bankaccountStatusId'];
                                            $res_notes = $result['notes'];

                                            echo "<tr>\n";
                                            echo "    <td>$res_date</td>\n";

                                            // get name for userId
                                            $sqlUser = "SELECT * FROM users WHERE id = $res_userId";
                                            $queryUser = mysqli_query($conn, $sqlUser) or die("Could not run SQL query.");
                                            $resultUser = mysqli_fetch_assoc($queryUser);
                                            $userName = $resultUser['prename'];
                                            echo "    <td>$userName</td>\n";

                                            // get name for clientId
                                            $sqlClient = "SELECT * FROM clients WHERE id = $res_clientId";
                                            $queryClient = mysqli_query($conn, $sqlClient) or die("Could not run SQL query.");
                                            $resultClient = mysqli_fetch_assoc($queryClient);
                                            $clientName = $resultClient['prename']." ".$resultClient['lastname'];
                                            echo "    <td>$clientName</td>\n";

                                            // get all the order items that belong to that order
                                            $sqlItem = "SELECT * FROM orderItems WHERE orderId = $res_id";
                                            $queryItem = mysqli_query($conn, $sqlItem) or die("Could not run SQL query.");

                                            echo "    <td>\n";
                                            echo "        <ul style=\"list-style-type:none;\">\n";

                                            while ($resultItem = mysqli_fetch_assoc($queryItem))
                                            {
                                                $orderItems_beerId = $resultItem['beerId'];
                                                $orderItems_amount = $resultItem['amount'];
                                                $orderItems_containerId = $resultItem['containerId'];
                                                $orderItems_gift = $resultItem['gift'];

                                                // get name for beerId
                                                $sqlBeer = "SELECT * FROM beers WHERE id = $orderItems_beerId";
                                                $queryBeer = mysqli_query($conn, $sqlBeer) or die("Could not run SQL query.");
                                                $resultBeer = mysqli_fetch_assoc($queryBeer);
                                                $beerType = $resultBeer['type'];

                                                $container = ($orderItems_containerId == 0) ? "Fl." : "Keg";
                                                $gift = ($orderItems_gift == 1) ? "<i class=\"bi bi-gift-fill\"></i>" : "";

                                                switch($beerType)
                                                {
                                                    case "Lovely Amber":
                                                        echo "    <li><span style=\"height: 15px; width: 15px; background-color: #f46a1e; border-radius: 50%; display: inline-block;\"></span> $orderItems_amount $container $beerType $gift</li>\n";
                                                        break;
                                                    case "Funky IPA":
                                                        echo "    <li><span style=\"height: 15px; width: 15px; background-color: #4cc070; border-radius: 50%; display: inline-block;\"></span> $orderItems_amount $container $beerType $gift</li>\n";
                                                        break;
                                                    case "Frisches Mais":
                                                        echo "    <li><span style=\"height: 15px; width: 15px; background-color: #e8e004; border-radius: 50%; display: inline-block;\"></span> $orderItems_amount $container $beerType $gift</li>\n";
                                                        break;
                                                    case "Freaky Craft":
                                                        echo "    <li><span style=\"height: 15px; width: 15px; background-color: #ed1c24; border-radius: 50%; display: inline-block;\"></span> $orderItems_amount $container $beerType $gift</li>\n";
                                                        break;
                                                    case "Volles Dinkel":
                                                        echo "    <li><span style=\"height: 15px; width: 15px; background-color: #b746aa; border-radius: 50%; display: inline-block;\"></span> $orderItems_amount $container $beerType $gift</li>\n";
                                                        break;
                                                    default:
                                                        echo "    <li><span style=\"height: 15px; width: 15px; background-color: #ffffff; border-radius: 50%; display: inline-block;\"></span> $orderItems_amount $container $beerType $gift</li>\n";
                                                        break;
                                                }
                                            }

                                            if ($res_numCrates > 1)
                                            {
                                                echo "    <li>&mdash;</li><li><i class=\"bi bi-box-seam\"></i> $res_numCrates Harasse</li>\n";
                                            }
                                            else if ($res_numCrates > 0)
                                            {
                                                echo "    <li>&mdash;</li><li><i class=\"bi bi-box-seam\"></i> $res_numCrates Harass</li>\n";
                                            }

                                            echo "        </ul>\n";
                                            echo "    </td>\n";

                                            echo "    <td>$res_price CHF</td>\n";

                                            // status
                                            echo "    <td>\n";
                                            echo "        <ul style=\"list-style-type:none;\">\n";
                                            if ($res_deliveryStatusId == 1)
                                            {
                                                echo "        <li><i style=\"color:green;\" class=\"bi bi-check\"></i>geliefert</li>\n";
                                            }
                                            else
                                            {
                                                echo "        <li><i style=\"color:red;\" class=\"bi bi-x\"></i>noch nicht geliefert</li>\n";
                                            }
                                            if ($res_paymentStatusId == 1)
                                            {
                                                echo "        <li><i style=\"color:green;\" class=\"bi bi-check\"></i>bezahlt</li>\n";
                                            }
                                            else
                                            {
                                                echo "        <li><i style=\"color:red;\" class=\"bi bi-x\"></i>noch nicht bezahlt</li>\n";
                                            }
                                            if ($res_bankaccountStatusId == 1)
                                            {
                                                echo "        <li><i style=\"color:green;\" class=\"bi bi-check\"></i>Geld auf Konto</li>\n";
                                            }
                                            else
                                            {
                                                echo "        <li><i style=\"color:red;\" class=\"bi bi-x\"></i>Geld noch nicht auf Konto</li>\n";
                                            }
                                            if ($res_numCrates > 0)
                                            {
                                                if ($res_crateStatusId == 1)
                                                {
                                                    echo "        <li><i style=\"color:green;\" class=\"bi bi-check\"></i>Harass zurück</li>\n";
                                                }
                                                else
                                                {
                                                    echo "        <li><i style=\"color:red;\" class=\"bi bi-x\"></i>Harass noch nicht zurück</li>\n";
                                                }
                                            }
                                            echo "        </ul>\n";
                                            echo "    </td>\n";
                                            echo "    <td>$res_notes</td>\n";
                                            echo "</tr>\n";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted"></div>
                            <div class="text-muted">order responsibly</div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>
</html>
