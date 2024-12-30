<?php
session_start();

include("connection.php");

if (!isset($_SESSION['username']))
{
    header("location:login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
    <?php include "./inc/head.html" ?>
    <body class="sb-nav-fixed">
        <?php include "./inc/bars.html" ?>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <?php include "./inc/sidenav.php" ?>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Alle Kunden</h1>
                        <ul>
                            <span style="color:gray"><li>Kundeneinträge können nur von der Person editiert werden, die den entsprechenden Kundeneintrag erstellt hat.</li></span>
                        </ul>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Unsere Kunden
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
                                            <th>Edit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT * FROM clients ORDER BY createDate DESC";
                                        $query = mysqli_query($conn, $sql) or die("Could not run SQL query.");

                                        while ($result = mysqli_fetch_assoc($query))
                                        {
                                            $res_id = $result['id'];
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

                                            // only allow edit for user who added this client
                                            if ($res_userId == $_SESSION['id'])
                                            {
                                                echo "    <td><a href=\"editclient.php?clientid=$res_id\">edit</a></td>\n";
                                            }
                                            else
                                            {
                                                echo "    <td></td>\n";
                                            }

                                            echo "</tr>\n";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
                <?php include "./inc/footer.html" ?>
            </div>
        </div>
        <?php include "./inc/scripts.html" ?>
    </body>
</html>
