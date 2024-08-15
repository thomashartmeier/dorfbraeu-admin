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
        <title>Dorfbräu Admin</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <!-- Bootstrap Font Icon CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="index.php">Dorfbräu Admin</a>
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
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <a class="nav-link" href="prices.html">
                                <div class="sb-nav-link-icon"><i class="bi bi-currency-dollar"></i></div>
                                Preisgestaltung
                            </a>
                            <div class="sb-sidenav-menu-heading">Edit</div>
                            <a class="nav-link" href="openorders.php">
                                <div class="sb-nav-link-icon"><i class="bi bi-cart-fill"></i></div>
                                Offene Bestellungen
                            </a>
                            <a class="nav-link" href="allorders.php">
                                <div class="sb-nav-link-icon"><i class="bi bi-cart-fill"></i></div>
                                Alle Bestellungen
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
                        <h1 class="mt-4">Dashboard</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">Primary Card</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-body">Warning Card</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body">Success Card</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body">Danger Card</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-area me-1"></i>
                                        Area Chart Example
                                    </div>
                                    <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar me-1"></i>
                                        Bar Chart Example
                                    </div>
                                    <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid px-4">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                2024: Anzahl verkaufte Flaschen pro Kunde
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>Kunde seit</th>
                                            <th>Name</th>
                                            <th>Firma</th>
                                            <th>Wiederverkäufer</th>
                                            <th>E-mail</th>
                                            <th>Telefon/Mobile</th>
                                            <th>Adresse</th>
                                            <th>Erstellt von</th>
                                            <th>Notiz</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT * FROM clients";
                                        $query = mysqli_query($conn, $sql) or die("Could not run SQL query.");

                                        while ($result = mysqli_fetch_assoc($query))
                                        {
                                            $res_date = $result['createDate'];
                                            $res_prename = $result['prename'];
                                            $res_lastname = $result['lastname'];
                                            $res_company = $result['company'];
                                            $res_isReseller = $result['isReseller'];
                                            $res_email = $result['email'];
                                            $res_phone = $result['phone'];
                                            $res_address = $result['address'];
                                            $res_billingAddress = $result['billingAddress'];
                                            $res_userId = $result['userId'];
                                            $res_notes = $result['notes'];

                                            $res_billingAddressString = ($res_billingAddress == '') ? "" : "<br><b>Rechnungsadresse:</b> $res_billingAddress";

                                            echo "<tr>\n";
                                            echo "    <td>$res_date</td>\n";
                                            echo "    <td>$res_prename $res_lastname</td>\n";
                                            echo "    <td>$res_company</td>\n";

                                            $resellerFlag = ($res_isReseller) ? "<i class=\"bi bi-star-fill\" style=\"color: #e8e004;\"></i>" : '';
                                            echo "    <td>$resellerFlag</td>\n";
                                            echo "    <td>$res_email</td>\n";
                                            echo "    <td>$res_phone</td>\n";
                                            echo "    <td>$res_address $res_billingAddressString</td>\n";

                                            // get name for userId
                                            $sqlUser = "SELECT * FROM users WHERE id = $res_userId";
                                            $queryUser = mysqli_query($conn, $sqlUser) or die("Could not run SQL query.");
                                            $resultUser = mysqli_fetch_assoc($queryUser);
                                            $userName = $resultUser['prename'];
                                            echo "    <td>$userName</td>\n";

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
