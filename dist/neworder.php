<?php
session_start();

include("connection.php");


function getCustomerSelection()
{
    $customerList = "";

    for ($customerIx=0; $customerIx<5; $customerIx++)
    {
        $customerList .= "<option value=\"customer${customerIx}\">Customer $customerIx</option>\n";
    }

    return $customerList;
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
                newrow.innerHTML = "<td><input type='text' name='numBottles[]'></td><td><select id='cars' name='cars'><option value='volvo'>Volvo</option></select></td><td><input type='checkbox'></input></td>";
                document.getElementById(divName).appendChild(newrow);
                counter++;
            }
            calculatePrice();
        }

        function calculatePrice()
        {
            var number = "123";
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
                            <div class="sb-sidenav-menu-heading">Edit</div>
                            <a class="nav-link" href="orders.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Bestellungen
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
                        <h1 class="mt-4">Neue Bestellung</h1>
                        <form id="formIdentifier" method="POST" action="./neworder.php">
                            <table>
                                <tr>
                                    <td width="150">
                                        <label for="beertypes">Kunde:</label>
                                    </td>
                                    <td>
                                        <select id="customers" name="customers">
                                            <?php
                                            echo getCustomerSelection();
                                            ?>
                                            <option value="customer1">Customer 1</option>
                                            <option value="customer2">Customer 2</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Preis (CHF):</td>
                                    <td>
                                        <input name="price" id="price" required></input> (berechnet: <span id="calculatedPrice"></span> CHF)
                                    </td>
                                </tr>
                                <tr>
                                    <td>Anzahl Harasse:</td>
                                    <td>
                                        <input name="numCrates" type="select" id="numCrates" required></input>
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
                                    <td><input type="text" name="numBottles[]" size="2"></td>
                                    <td>
                                        <select id="beertypes" name="beertypes">
                                            <option value="frischesmais">Frisches Mais</option>
                                            <option value="freakycraft">Freaky Craft</option>
                                        </select>
                                    </td>
                                    <td><input type="checkbox"></input></td>
                                </tr>
                            </table>
                            <input type="button" value="+" onClick="addInput('ordertable');">
                            <hr>
                            <p><input type="submit"></input></p>
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
