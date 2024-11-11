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
                        <h1 class="mt-4">Offene Bestellungen</h1>
                        <ul>
                            <span style="color:gray"><li>Bestellungen können nur von der Person editiert werden, die die entsprechende Bestellung erstellt hat oder von der Person, die zuständig für den Rechnungsversand ist.</li></span>
                        </ul>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Offene Bestellungen
                            </div>

                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>Bestellnummer</th>
                                            <th>Erstelldatum</th>
                                            <th>Erstellt von</th>
                                            <th>Kunde</th>
                                            <th>Bestellung</th>
                                            <th>Preis</th>
                                            <th>Status</th>
                                            <th>Notiz</th>
                                            <th>Edit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        # - if the payment method is invoice (paymentMethod == 1), we want to list orders
                                        #   which are not delivered yet as well as orders where the invoice is not sent out yet
                                        # - if the payment method is non-invoice (paymentMethod == 0), we want to list orders
                                        #   which are not delivered yet, not payed yet or not yet on bankaccount
                                        $sql = "SELECT * FROM orders WHERE deliveryStatusId=0 OR                         # not delivered yet
                                                                           (paymentMethod=1 AND invoiceStatusId=0) OR    # invoice payment + invoice not sent out yet
                                                                           (paymentMethod=0 AND paymentStatusId=0) OR    # non-invoice payment + not payed yet
                                                                           (paymentMethod=0 AND bankaccountStatusId=0)"; # non-invoice payment + not on bank account yet
                                        $query = mysqli_query($conn, $sql) or die("Could not run SQL query.");

                                        while ($result = mysqli_fetch_assoc($query))
                                        {
                                            $res_id = $result['id'];
                                            $res_orderNumber = $result['orderNumber'];
                                            $res_date = $result['createDate'];
                                            $res_userId = $result['userId'];
                                            $res_clientId = $result['clientId'];
                                            $res_paymentMethod = $result['paymentMethod'];
                                            $res_price = $result['price'];
                                            $res_numCrates = $result['numCrates'];
                                            $res_deliveryStatusId = $result['deliveryStatusId'];
                                            $res_invoiceStatusId = $result['invoiceStatusId'];
                                            $res_paymentStatusId = $result['paymentStatusId'];
                                            $res_bankaccountStatusId = $result['bankaccountStatusId'];
                                            $res_notes = $result['notes'];

                                            echo "<tr>\n";
                                            echo "    <td>$res_orderNumber</td>\n";
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
                                            $clientCompany = $resultClient['company'];

                                            if ($clientCompany != '')
                                            {
                                                echo "    <td>$clientName ($clientCompany)</td>\n";
                                            }
                                            else
                                            {
                                                echo "    <td>$clientName</td>\n";
                                            }

                                            // get all the order items that belong to that order
                                            $sqlItem = "SELECT * FROM orderItems WHERE orderId = $res_id";
                                            $queryItem = mysqli_query($conn, $sqlItem) or die("Could not run SQL query.");

                                            echo "    <td>\n";
                                            echo "        <ul style=\"list-style-type:none;\">\n";

                                            // check if we have any non-gift item in the orderlist
                                            $anyNonGift = 0;

                                            // count how many bottles this order sums up to
                                            $totalNumBottles = 0;

                                            while ($resultItem = mysqli_fetch_assoc($queryItem))
                                            {
                                                $orderItems_beerId = $resultItem['beerId'];
                                                $orderItems_quantity = $resultItem['quantity'];
                                                $orderItems_containerId = $resultItem['containerId'];
                                                $orderItems_gift = $resultItem['gift'];

                                                $totalNumBottles += $orderItems_quantity;

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
                                                        echo "    <li><span style=\"height: 15px; width: 15px; background-color: #f46a1e; border-radius: 50%; display: inline-block;\"></span> $orderItems_quantity $container $beerType $gift</li>\n";
                                                        break;
                                                    case "Funky IPA":
                                                        echo "    <li><span style=\"height: 15px; width: 15px; background-color: #4cc070; border-radius: 50%; display: inline-block;\"></span> $orderItems_quantity $container $beerType $gift</li>\n";
                                                        break;
                                                    case "Frisches Mais":
                                                        echo "    <li><span style=\"height: 15px; width: 15px; background-color: #e8e004; border-radius: 50%; display: inline-block;\"></span> $orderItems_quantity $container $beerType $gift</li>\n";
                                                        break;
                                                    case "Freaky Craft":
                                                        echo "    <li><span style=\"height: 15px; width: 15px; background-color: #ed1c24; border-radius: 50%; display: inline-block;\"></span> $orderItems_quantity $container $beerType $gift</li>\n";
                                                        break;
                                                    case "Volles Dinkel":
                                                        echo "    <li><span style=\"height: 15px; width: 15px; background-color: #b746aa; border-radius: 50%; display: inline-block;\"></span> $orderItems_quantity $container $beerType $gift</li>\n";
                                                        break;
                                                    default:
                                                        echo "    <li><span style=\"height: 15px; width: 15px; background-color: #ffffff; border-radius: 50%; display: inline-block;\"></span> $orderItems_quantity $container $beerType $gift</li>\n";
                                                        break;
                                                }
                                            }

                                            if ($res_numCrates > 1 || $res_numCrates < -1)
                                            {
                                                echo "    <li>&mdash;</li><li><i class=\"bi bi-box-seam\"></i> $res_numCrates Harasse</li>\n";
                                            }
                                            else if ($res_numCrates == 1 || $res_numCrates == -1)
                                            {
                                                echo "    <li>&mdash;</li><li><i class=\"bi bi-box-seam\"></i> $res_numCrates Harass</li>\n";
                                            }

                                            echo "    <li><i class=\"bi bi-hash\"></i> total $totalNumBottles Flaschen</li>\n";

                                            echo "        </ul>\n";
                                            echo "    </td>\n";

                                            echo "    <td>$res_price CHF</td>\n";

                                            echo "    <td>\n";
                                            echo "        <ul style=\"list-style-type:none;\">\n";

                                            // invoice status
                                            // only care about invoice status if payment method is invoice (=1)
                                            if ($res_paymentMethod == 1)
                                            {
                                                if ($res_invoiceStatusId == 1)
                                                {
                                                    echo "        <li><i style=\"color:green;\" class=\"bi bi-check\"></i>Rechnung geschickt</li>\n";
                                                }
                                                else
                                                {
                                                    echo "        <li><i style=\"color:red;\" class=\"bi bi-x\"></i>Rechnung noch nicht geschickt</li>\n";
                                                }
                                            }

                                            // delivery status
                                            if ($res_deliveryStatusId == 1)
                                            {
                                                echo "        <li><i style=\"color:green;\" class=\"bi bi-check\"></i>geliefert</li>\n";
                                            }
                                            else
                                            {
                                                echo "        <li><i style=\"color:red;\" class=\"bi bi-x\"></i>noch nicht geliefert</li>\n";
                                            }

                                            // payment status
                                            // any orderitem which is not a gift?
                                            if ($anyNonGift == 1)
                                            {
                                                // payment status
                                                // only care about payment status if payment method is not invoice (!=1)
                                                if ($res_paymentMethod != 1)
                                                {
                                                    if ($res_paymentStatusId == 1)
                                                    {
                                                        echo "        <li><i style=\"color:green;\" class=\"bi bi-check\"></i>bezahlt</li>\n";
                                                    }
                                                    else
                                                    {
                                                        echo "        <li><i style=\"color:red;\" class=\"bi bi-x\"></i>noch nicht bezahlt</li>\n";
                                                    }
                                                }
                                                // bank account status
                                                // only care about bank account status if payment method is not invoice (!=1)
                                                if ($res_paymentMethod != 1)
                                                {
                                                    if ($res_bankaccountStatusId == 1)
                                                    {
                                                        echo "        <li><i style=\"color:green;\" class=\"bi bi-check\"></i>Geld auf Konto</li>\n";
                                                    }
                                                    else
                                                    {
                                                        echo "        <li><i style=\"color:red;\" class=\"bi bi-x\"></i>Geld noch nicht auf Konto</li>\n";
                                                    }
                                                }
                                            }

                                            echo "        </ul>\n";
                                            echo "    </td>\n";
                                            echo "    <td>$res_notes</td>\n";

                                            // allow editing this order for the person that created this order and for the person that is
                                            // handing out the invoices
                                            $allowEdit = (($_SESSION['id'] == $res_userId) || ($_SESSION['id'] == 1));

                                            // only allow edit for user who created this order
                                            if ($allowEdit)
                                            {
                                                echo "    <td><a href=\"editorder.php?orderid=$res_id\">edit</a></td>\n";
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
