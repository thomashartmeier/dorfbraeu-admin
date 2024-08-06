<?php
session_start();

include("connection.php");


function getCustomerSelection()
{
    global $conn;

    $customerList = "";

    $sql = "SELECT * FROM clients ORDER BY lastname";
    $query = mysqli_query($conn, $sql) or die("Could not run SQL query.");

    while ($result = mysqli_fetch_assoc($query))
    {
        $res_id = $result['id'];
        $res_prename = $result['prename'];
        $res_lastname = $result['lastname'];
        $res_isReseller = $result['isReseller'];
        $resellerFlag = '';

        if ($res_isReseller)
        {
            $resellerFlag = ' (*)';
        }

        $customerList .= "<option value=\"${res_id}\">$res_lastname, ${res_prename}${resellerFlag}</option>\n";
    }

    return $customerList;
}

function getBeertypeSelection()
{
    global $conn;

    $beertypeList = "";

    $sql = "SELECT * FROM beers";
    $query = mysqli_query($conn, $sql) or die("Could not run SQL query.");

    while ($result = mysqli_fetch_assoc($query))
    {
        $res_id = $result['id'];
        $res_type = $result['type'];

        $beertypeList .= "<option value='${res_id}' name='beertype${res_id}' id='beertype${res_id}' size='2'>$res_type</option>";
    }

    return $beertypeList;
}

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
        <script>
        var counter = 1;
        var limit = 50;

        function addInput(divName)
        {
            if (counter === limit)
            {
                alert("You have reached the limit of adding " + counter + " inputs");
            }
            else
            {
                var newrow = document.createElement("tr");
                newrow.innerHTML  = "<td><input type='number' name='numBottles[]' size='2' onchange='calculatePrice()'></td>\n \
                <td>\n \
                    <select name='bierselect[]' onchange='calculatePrice()'>\n \
                        <?php
                            echo getBeertypeSelection();
                        ?>
                    </select>\n \
                </td>\n \
                <td style=\"text-align:center\"><input type='hidden' value='0' name='giftselect[" + counter + "]'><input type='checkbox' name='giftselect[" + counter + "]' value='1' onchange='calculatePrice()' /></td>";
                document.getElementById(divName).appendChild(newrow);
                counter++;
            }
            calculatePrice();
        }

        function calculatePrice()
        {
            var number = Math.random()*10;
            document.getElementById("calculatedPrice").innerHTML = number;
        }
        </script>
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
                    <?php
                    $price = $_POST['price'];
                    $client = $_POST['customers'];
                    $numCrates = $_POST['numCrates'];
                    $notes = $_POST['notes'];

                    if (empty($price))
                    {
                        // nothing to say
                    }
                    else
                    {
                        // check if we have any non-gift item in the orderlist
                        $anyNonGift = 0;

                        $arr_numBottles = $_POST["numBottles"];
                        $arr_beerType = $_POST["bierselect"];
                        $arr_gift = $_POST["giftselect"];

                        // loop over all items and check if we have any non-gift item
                        for ($i = 0; $i < sizeof($arr_numBottles); $i++)
                        {
                            if ($arr_gift[$i] == 0)
                            {
                                $anyNonGift = 1;
                                // found any non-gift item, can break the loop
                                break;
                            }
                        }

                        // if we have any non-gift item, we need a payment and therefore the payment status is set to 'open'
                        $paymentStatus = ($anyNonGift == 1) ? 0 : 1;

                        // if we have any crates to deliver, the crate status is set to 'open' (i.e. we need them back)
                        $crateStatus = ($numCrates > 0) ? 0 : 1;

                        $createDate = date("Y-m-d");

                        $sql = "INSERT INTO orders (createDate,    userId, clientId, deliveryStatusId, paymentStatusId, bankaccountStatusId, price,  numCrates,  crateStatusId, notes) VALUES
                                                   ('$createDate', 0,      $client,  0,                $paymentStatus,  $paymentStatus,      $price, $numCrates, $crateStatus,  '$notes')";

                        $query = mysqli_query($conn, $sql) or die("Could not run SQL query.");

                        $newOrderId = mysqli_insert_id($conn);

                        // to the order with the new id that we received we now attach the ordered items

                        // loop over all items and add them to the DB
                        for ($i = 0; $i < sizeof($arr_numBottles); $i++)
                        {
                            $beerId = $arr_beerType[$i];
                            $amount = $arr_numBottles[$i];
                            $gift = $arr_gift[$i];

                            $sqlOrderItem = "INSERT INTO orderItems (beerId,  containerId, amount,  gift,  orderId) VALUES
                                                                    ($beerId, 0,           $amount, $gift, $newOrderId)";

                            $query = mysqli_query($conn, $sqlOrderItem) or die("Could not run SQL query.");
                        }

                        echo "<p style=\"background-color:powderblue;\">Neue Bestellung dazugefügt <i class=\"bi bi-hand-thumbs-up-fill\"></i></p>";
                    }
                    ?>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Neue Bestellung</h1>
                        <form id="formIdentifier" method="POST" action="./neworder.php">
                            <table>
                                <tr valign="top">
                                    <td width="150">
                                        <label for="beertypes">Kunde:</label>
                                    </td>
                                    <td>
                                        <select id="customers" name="customers">
                                            <?php
                                            echo getCustomerSelection();
                                            ?>
                                        </select>
                                        <ul>
                                            <li>(*) Kunde ist Wiederverkäufer</li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Preis (CHF):</td>
                                    <td>
                                        <input name="price" type="number" id="price" size="2" required>
                                        <ul>
                                            <li>Preis inkl. Harassendepot (10.-/Harass)</li>
                                            <li>Berechneter Preis: <span id="calculatedPrice"></span></li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Anzahl Harasse:</td>
                                    <td>
                                        <input name="numCrates" type="number" value="0" id="numCrates" size="2" onchange="calculatePrice()" required>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Notiz:</td>
                                    <td>
                                        <input name="notes" type="text">
                                    </td>
                                </tr>
                            </table>
                            <br />
                            <table id="ordertable">
                                <tr>
                                    <th width="150">Anzahl Flaschen</th>
                                    <th>Biersorte</th>
                                    <th>Geschenk</th>
                                </tr>
                                <tr name="orderItem[]">
                                    <td><input type="number" name="numBottles[]" size="2" onchange="calculatePrice()"></td>
                                    <td>
                                        <select name="bierselect[]" onchange="calculatePrice()">
                                            <?php
                                            echo getBeertypeSelection();
                                            ?>
                                        </select>
                                    </td>
                                    <td style="text-align:center"><input type='hidden' value='0' name='giftselect[0]'><input type="checkbox" name='giftselect[0]' value='1' onchange="calculatePrice()" /></td>
                                </tr>
                            </table>
                            <input type="button" value="+" onClick="addInput('ordertable');">
                            <hr>
                            <p><input type="submit" value="Speichern"></p>
                        </form>
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
