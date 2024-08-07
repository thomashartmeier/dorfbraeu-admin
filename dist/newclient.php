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
                    <?php
                    $prename = $_POST['prename'];
                    $lastname = $_POST['lastname'];
                    $isReseller = $_POST['isReseller'];
                    $email = $_POST['email'];
                    $phone = $_POST['phone'];
                    $address = $_POST['address'];
                    $notes = $_POST['notes'];

                    if (empty($prename) && empty($lastname) && empty($email) && empty($phone) && empty($address))
                    {
                        // nothing to say
                    }
                    else
                    {
                        $createDate = date("Y-m-d");

                        $sql = "INSERT INTO clients (createDate,    prename,    lastname,    isReseller,  email,    phone,    address,    userId,  notes) VALUES
                                                    ('$createDate', '$prename', '$lastname', $isReseller, '$email', '$phone', '$address', 0, '$notes')";

                        $query = mysqli_query($conn, $sql) or die("Could not run SQL query.");

                        echo "<p style=\"background-color:powderblue;\">Neuer Kundeneintrag dazugefügt <i class=\"bi bi-hand-thumbs-up-fill\"></i></p>";
                    }
                    ?>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Neuer Kundeneintrag</h1>
                        <ul>
                            <li>Felder dürfen leergelassen werden (bzw. nachträglich ergänzt werden). Es muss aber mindestens entweder Vorname, Nachname, E-mail, Telefonnummer oder Adresse ausgefüllt werden.</li>
                        </ul>
                        <form id="formIdentifier" method="POST" action="./newclient.php">
                            <table>
                                <tr>
                                    <td>Vorname:</td>
                                    <td>
                                        <input name="prename" type="text">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Nachname:</td>
                                    <td>
                                        <input name="lastname" type="text">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Wiederverkäufer:</td>
                                    <td>
                                        <input type='hidden' value='0' name='isReseller'><input type="checkbox" name='isReseller' value='1' />
                                    </td>
                                </tr>
                                <tr>
                                    <td>E-mail:</td>
                                    <td>
                                        <input name="email" type="text">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Telefon/Mobile:</td>
                                    <td>
                                        <input name="phone" type="text">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Adresse:</td>
                                    <td>
                                        <input name="address" type="text">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Notiz:</td>
                                    <td>
                                        <input name="notes" type="text">
                                    </td>
                                </tr>
                            </table>
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
