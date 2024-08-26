<?php
session_start();

include("connection.php");


function getCustomerSelection($input_clientId)
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

        $selectedString = '';

        if ($res_id == $input_clientId)
        {
            $selectedString = 'selected="selected"';
        }

        $customerList .= "<option value=\"${res_id}\" $selectedString>$res_lastname, ${res_prename}${resellerFlag}</option>";
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

if (!isset($_SESSION['username']))
{
    header("location:login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
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
            newrow.innerHTML  = "<td><input type='number' name='numBottles[]' size='2' onchange='calculatePrice()' min='1' required></td>\n \
            <td>\n \
                <select name='bierselect[]' onchange='calculatePrice()'><?php echo getBeertypeSelection(); ?>\n \
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
    <?php include "./inc/head.html" ?>
    <body class="sb-nav-fixed">
        <?php include "./inc/bars.html" ?>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <?php include "./inc/sidenav.php" ?>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <?php
                    $submitted = $_POST['submitted'];
                    $orderId = $_GET['orderid'];

                    if (!empty($submitted))
                    {
                        // the form was submitted, so we can send it (no need to check for valid form data)

                        $price = $_POST['price'];
                        $client = $_POST['customers'];
                        $numCrates = $_POST['numCrates'];
                        $notes = $_POST['notes'];
                        $paymentMethod = $_POST['paymentMethod'];
                        $deliveryStatusId = $_POST['deliveryStatusId'];
                        $invoiceStatusId = $_POST['invoiceStatusId'];
                        $paymentStatusId = $_POST['paymentStatusId'];
                        $bankaccountStatusId = $_POST['bankaccountStatusId'];

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
                        $paymentStatusId = ($anyNonGift == 1) ? 0 : 1;

                        $sqlUpdate = "UPDATE orders SET clientId = $client, paymentMethod = $paymentMethod, deliveryStatusId = $deliveryStatusId, invoiceStatusId = $invoiceStatusId, paymentStatusId = $paymentStatusId, bankaccountStatusId = $bankaccountStatusId, price = $price, numCrates = $numCrates, notes = '$notes' WHERE id = $orderId";

                        $queryUpdate = mysqli_query($conn, $sqlUpdate) or die("Could not run SQL query.");

                        // simply remove any existing orderItems in the DB for this orderId
                        $sqlDeleteOrderItem = "DELETE FROM orderItems WHERE orderId=$orderId;";
                        $query = mysqli_query($conn, $sqlDeleteOrderItem) or die("Could not run SQL query.");

                        // loop over all items and add them to the DB
                        for ($i = 0; $i < sizeof($arr_numBottles); $i++)
                        {
                            $beerId = $arr_beerType[$i];
                            $quantity = $arr_numBottles[$i];
                            $gift = $arr_gift[$i] ? '1' : '0';

                            $sqlOrderItem = "INSERT INTO orderItems (beerId,  containerId, quantity,  gift,  orderId) VALUES
                                                                    ($beerId, 0,           $quantity, $gift, $orderId)";

                            $query = mysqli_query($conn, $sqlOrderItem) or die("Could not run SQL query.");
                        }

                        echo "<p style=\"background-color:powderblue;\">Bestellung editiert <i class=\"bi bi-hand-thumbs-up-fill\"></i></p>";
                    }
                    else
                    {
                        // nothing to say if form was not submitted yet
                    }

                    if (empty($orderId))
                    {
                        echo "<p style=\"background-color:#E6B7B1;\">Brauche orderid! <i class=\"bi bi-hand-thumbs-down-fill\"></i></p>";
                    }

                    // invalid user id
                    $order_userId = 99;

                    if (!empty($orderId))
                    {
                        // get the user that is assigned to this order
                        $sqlOrder = "SELECT * FROM orders WHERE id = $orderId";
                        $queryOrder = mysqli_query($conn, $sqlOrder) or die("Could not run SQL query.");
                        $resultOrder = mysqli_fetch_assoc($queryOrder);
                        $order_userId = $resultOrder['userId'];
                    }

                    // check that only the user assigned to this order can edit it
                    if ($order_userId != $_SESSION['id'])
                    {
                        echo "<p style=\"background-color:#E6B7B1;\">Du darfst diese Bestellung nicht editieren! <i class=\"bi bi-hand-thumbs-down-fill\"></i></p>";
                    }
                    else
                    {
                        // get the needed data for this orderId
                        $sqlOrder = "SELECT * FROM orders WHERE id = $orderId";
                        $queryOrder = mysqli_query($conn, $sqlOrder) or die("Could not run SQL query.");
                        $resultOrder = mysqli_fetch_assoc($queryOrder);

                        $order_clientId = $resultOrder['clientId'];
                        $order_price = $resultOrder['price'];
                        $order_numCrates = $resultOrder['numCrates'];
                        $order_notes = $resultOrder['notes'];
                        $order_paymentMethod = $resultOrder['paymentMethod'];
                        $order_deliveryStatusId = $resultOrder['deliveryStatusId'];
                        $order_invoiceStatusId = $resultOrder['invoiceStatusId'];
                        $order_paymentStatusId = $resultOrder['paymentStatusId'];
                        $order_bankaccountStatusId = $resultOrder['bankaccountStatusId'];
                    ?>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Bestellung #<?php echo "$orderId";?> editieren</h1>
                        <form id="formIdentifier" method="POST" action="./editorder.php?orderid=<?php echo $orderId; ?>">
                            <table>
                                <tr valign="top">
                                    <td width="150">Stati:</td>
                                    <td><ul>
                                        <li>Bestellung geliefert:
                                            <select name="deliveryStatusId">
                                                <option value='0' size='2' <?php if ($order_deliveryStatusId==0) { echo 'selected="selected"'; } ?>>offen</option>
                                                <option value='1' size='2' <?php if ($order_deliveryStatusId==1) { echo 'selected="selected"'; } ?>>erledigt</option>
                                            </select>
                                        </li>
                                        <li>Rechnung geschickt:
                                            <select name="invoiceStatusId">
                                                <option value='0' size='2' <?php if ($order_invoiceStatusId==0) { echo 'selected="selected"'; } ?>>offen</option>
                                                <option value='1' size='2' <?php if ($order_invoiceStatusId==1) { echo 'selected="selected"'; } ?>>erledigt</option>
                                            </select>
                                        </li>
                                        <ul><span style="color:gray"><li>Nur relevant wenn die Bezahlart unten auf "Rechnung" gesetzt ist.</li></span></ul>
                                        <li>Bestellung bezahlt:
                                            <select name="paymentStatusId">
                                                <option value='0' size='2' <?php if ($order_paymentStatusId==0) { echo 'selected="selected"'; } ?>>offen</option>
                                                <option value='1' size='2' <?php if ($order_paymentStatusId==1) { echo 'selected="selected"'; } ?>>erledigt</option>
                                            </select>
                                        </li>
                                        <ul><span style="color:gray"><li>Falls unten alle Flaschen als Geschenk markiert werden, wird "Bestellung bezahlt" automatisch immer als "erledigt" gespeichert.</li></span></ul>
                                        <li>Geld auf Konto:
                                            <select name="bankaccountStatusId">
                                                <option value='0' size='2' <?php if ($order_bankaccountStatusId==0) { echo 'selected="selected"'; } ?>>offen</option>
                                                <option value='1' size='2' <?php if ($order_bankaccountStatusId==1) { echo 'selected="selected"'; } ?>>erledigt</option>
                                            </select>
                                        </li>
                                        <ul><span style="color:gray"><li>Wenn der Kunde via Rechnung bezahlt, geht das Geld sowieso direkt aufs Konto und somit ist "Bestellung bezahlt" und "Geld auf Konto" gleichbedeutend. Falls der Kunde direkt an dich bezahlt hat, kannst du diesen Status erst auf "erledigt" setzen, wenn du das Geld auf unser Konto überwiesen hast.</li></span></ul>
                                    </ul></td>
                                </tr>
                                <tr valign="top">
                                    <td width="150">Kunde:</td>
                                    <td>
                                        <select id="customers" name="customers">
                                            <?php
                                            echo getCustomerSelection($order_clientId);
                                            ?>
                                        </select>
                                        <ul>
                                            <span style="color:gray"><li>(*) Kunde ist Wiederverkäufer</li></span>
                                        </ul>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Preis (CHF):</td>
                                    <td>
                                        <input name="price" type="number" id="price" size="2" min="0" step="0.05" value="<?php echo $order_price;?>" required>
                                        <ul>
                                            <span style="color:gray"><li>Preis inkl. Harassendepot (10.-/Harass)</li></span>
                                            <span style="color:gray"><li>Berechneter Preis: <span id="calculatedPrice"></span></li></span>
                                        </ul>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Anzahl Harasse:</td>
                                    <td>
                                        <input name="numCrates" type="number" id="numCrates" size="2" onchange="calculatePrice()" value="<?php echo $order_numCrates;?>" required>
                                        <ul>
                                            <span style="color:gray"><li>Die hier eingetragene Anzahl Harasse soll der Menge "gelieferter minus zurückerhaltener" bei erfolgter Auslieferung entsprechen. Wichtig: Da es sein kann, dass bei der Auslieferung mehr Harasse zurückgekommen sind (von früheren Aufträgen) als ausgeliefert, kann dieses Feld auch negativ sein! Die Anzahl Harasse, die uns ein Kunde noch schuldet, kann im <a href="index.php">Dashboard</a> nachgeschaut werden.</li></span>
                                            <span style="color:gray"><li>Falls der Kunde nach einer Weile einfach den/die Harass(e) zurückgibt ohne eine neue Bestellung aufzugeben, kann seine letzte Bestellung editiert werden und dort das Feld "Anzahl Harasse" auf 0 gesetzt werden.</li></span>
                                        </ul>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Bezahlart:</td>
                                    <td>
                                        <select name="paymentMethod">
                                            <option value='0' size='2' <?php if ($order_paymentMethod==0) { echo 'selected="selected"'; } ?>>Kunde bezahlt direkt an dich (bar, Twint, ...)</option>
                                            <option value='1' size='2' <?php if ($order_paymentMethod==1) { echo 'selected="selected"'; } ?>>Kunde bezahlt via Rechnung</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Notiz:</td>
                                    <td>
                                        <textarea name="notes"><?php echo $order_notes;?></textarea>
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
                                <?php
                                $sqlItem = "SELECT * FROM orderItems WHERE orderId = $orderId";
                                $queryItem = mysqli_query($conn, $sqlItem) or die("Could not run SQL query.");

                                $orderItemIx = 0;

                                // loop over all orderitems for this orderid
                                while ($resultItem = mysqli_fetch_assoc($queryItem))
                                {
                                    $orderItems_beerId = $resultItem['beerId'];
                                    $orderItems_quantity = $resultItem['quantity'];
                                    $orderItems_containerId = $resultItem['containerId'];
                                    $orderItems_gift = $resultItem['gift'];

                                    $checkedString = $orderItems_gift ? "checked" : "";

                                    echo "<tr name=\"orderItem[]\">\n";
                                    echo "<td><input type=\"number\" name=\"numBottles[]\" size=\"2\" onchange=\"calculatePrice()\" min=\"1\" value=\"$orderItems_quantity\" required></td>\n";
                                    echo "<td>\n";
                                    echo "    <select name=\"bierselect[]\" onchange=\"calculatePrice()\">\n";
                                    echo getBeertypeSelection();
                                    echo "    </select>\n";
                                    echo "</td>\n";
                                    echo "<td style=\"text-align:center\"><input type='hidden' value='0' name='giftselect[$orderItemIx]'><input type=\"checkbox\" name='giftselect[$orderItemIx]' value='1' onchange=\"calculatePrice()\" $checkedString /></td>\n";
                                    echo "</tr>\n";

                                    $orderItemIx++;
                                }
                               ?>
                            </table>
                            <input type="button" value="+" onClick="addInput('ordertable');">
                            <hr>
                            <input type='hidden' value='1' name='submitted'>
                            <p><input type="submit" value="Update"></p>
                        </form>
                    </div>
                    <?php
                    }
                    ?>
                </main>
                <?php include "./inc/footer.html" ?>
            </div>
        </div>
        <?php include "./inc/scripts.html" ?>
    </body>
</html>
