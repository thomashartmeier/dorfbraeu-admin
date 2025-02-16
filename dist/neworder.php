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

        $customerList .= "<option value=\"${res_id}\">$res_lastname, ${res_prename}${resellerFlag}</option>";
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
    // we have 5 different styles of beer so we need 10 (because we can have gift or no-gift)
    var limit = 10;

    function addInput(divName)
    {
        if (counter === limit)
        {
            alert("You have reached the limit of adding " + counter + " inputs");
        }
        else
        {
            var newrow = document.createElement("tr");
            newrow.innerHTML  = "<td><input type='number' name='numBottles[]' size='2' onchange='calculatePrice()' min='0' required></td>\n \
            <td>\n \
                <select name='bierselect[]' onchange='calculatePrice()' required><option value=''>Biersorte auswählen...</option><?php echo getBeertypeSelection(); ?>\n \
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
        // count total number of bottles
        let totalNumBottles = 0;

        var numBottles = document.getElementsByName('numBottles[]');

        for (var i = 0; i < numBottles.length; i++)
        {
            // get whether this selection of bottles is a gift
            var giftselect = document.getElementsByName('giftselect['+i+']');

            // only count if not tagged as gift
            // (giftselect[0] is the hidden field, so we read giftselect[1])
            if (!giftselect[1].checked)
            {
                totalNumBottles += Number(numBottles[i].value);
            }
        }

        // get price per bottle
        var pricePerBottle;

        if (totalNumBottles > 239)
        {
            pricePerBottle = 3.25;
        }
        else if (totalNumBottles > 96)
        {
            pricePerBottle = 3.50;
        }
        else if (totalNumBottles > 23)
        {
            pricePerBottle = 3.75;
        }
        else
        {
            pricePerBottle = 4.00;
        }

        // check if client is reseller
        var selectedIndex = document.getElementById('customers').selectedIndex;
        var clientString = document.getElementById('customers').options[selectedIndex].text;
        var isReseller = clientString.match(/\(\*\)/);

        if (isReseller)
        {
            pricePerBottle -= 0.25;
        }

        // get number of crates
        var numCrates = document.getElementById('numCrates').value;

        var totalPrice = totalNumBottles*pricePerBottle + numCrates*10;

        document.getElementById("calculatedPrice").innerHTML = totalPrice + "CHF";
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

                    if (!empty($submitted))
                    {
                        // the form was submitted, so we can send it (no need to check for valid form data)

                        $price = $_POST['price'];
                        $client = $_POST['customers'];
                        $numCrates = $_POST['numCrates'];
                        $notes = $_POST['notes'];
                        $paymentMethod = $_POST['paymentMethod'];

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

                        // create order number
                        $orderNumber1 = rand(0, 999);
                        $orderNumber2 = rand(0, 999);

                        // compose string of order number
                        $orderNumberString = sprintf("%03d-%03d", $orderNumber1, $orderNumber2);

                        $createDate = date("Y-m-d");

                        $userId = $_SESSION['id'];

                        $sql = "INSERT INTO orders (orderNumber,          createDate,    userId,  clientId, paymentMethod,  deliveryStatusId, invoiceStatusId, paymentStatusId, bankaccountStatusId, price,  numCrates,  notes) VALUES
                                                   ('$orderNumberString', '$createDate', $userId, $client,  $paymentMethod, 0,                0,               $paymentStatus,  $paymentStatus,      $price, $numCrates, '$notes')";

                        $query = mysqli_query($conn, $sql) or die("Could not run SQL query.");

                        $newOrderId = mysqli_insert_id($conn);

                        // to the order with the new id that we received we now attach the ordered items

                        // loop over all items and add them to the DB
                        for ($i = 0; $i < sizeof($arr_numBottles); $i++)
                        {
                            $beerId = $arr_beerType[$i];
                            $quantity = $arr_numBottles[$i];
                            $gift = $arr_gift[$i] ? '1' : '0';

                            $sqlOrderItem = "INSERT INTO orderItems (beerId,  containerId, quantity,  gift,  orderId) VALUES
                                                                    ($beerId, 0,           $quantity, $gift, $newOrderId)";

                            $query = mysqli_query($conn, $sqlOrderItem) or die("Could not run SQL query.");
                        }

                        echo "<p style=\"background-color:powderblue;\">Neue Bestellung dazugefügt <i class=\"bi bi-hand-thumbs-up-fill\"></i></p>";
                    }
                    else
                    {
                        // nothing to say if form was not submitted yet
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
                                        <select id="customers" name="customers" onchange="calculatePrice()" required>
                                            <option value="">Kunde auswählen...</option>
                                            <?php
                                            echo getCustomerSelection();
                                            ?>
                                        </select>
                                        <ul>
                                            <span style="color:gray"><li>Falls der Kunde noch nicht in der Liste ist, muss er zuerst unter <a href="./newclient.php">Neuer Kundeneintrag</a> dazugefügt werden.</li></span>
                                            <span style="color:gray"><li>(*) Kunde ist Wiederverkäufer</li></span>
                                        </ul>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Preis (CHF):</td>
                                    <td>
                                        <input name="price" type="number" id="price" size="2" min="0" step="0.05" required>
                                        <ul>
                                            <span style="color:gray"><li>Preis inkl. Harassendepot (10.-/Harass)</li></span>
                                            <span style="color:gray"><li>Berechneter Preis: <span id="calculatedPrice"></span></li></span>
                                        </ul>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Anzahl Harasse:</td>
                                    <td>
                                        <input name="numCrates" type="number" value="0" id="numCrates" size="2" onchange="calculatePrice()" required>
                                        <ul>
                                            <span style="color:gray"><li>Die hier eingetragene Anzahl Harasse soll der Menge "gelieferter minus zurückerhaltener" bei erfolgter Auslieferung entsprechen. Wichtig: Da es sein kann, dass bei der Auslieferung mehr Harasse zurückgekommen sind (von früheren Aufträgen) als ausgeliefert, kann dieses Feld auch negativ sein! Die Anzahl Harasse, die uns ein Kunde noch schuldet, kann im <a href="index.php">Dashboard</a> nachgeschaut werden.</li></span>
                                            <span style="color:gray"><li>Falls der Kunde nach einer Weile einfach den/die Harass(e) zurückgibt ohne eine neue Bestellung aufzugeben, kann seine letzte Bestellung editiert werden und dort das Feld "Anzahl Harasse" auf 0 gesetzt werden.</li></span>
                                        </ul>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Bezahlart:</td>
                                    <td>
                                        <select name="paymentMethod" required>
                                            <option value="">Bezahlart auswählen...</option>
                                            <option value='0' size='2'>Kunde bezahlt direkt an dich (bar, Twint, ...)</option>
                                            <option value='1' size='2'>Kunde bezahlt via Rechnung</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Notiz:</td>
                                    <td>
                                        <textarea name="notes"></textarea>
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
                                    <td><input type="number" name="numBottles[]" size="2" onchange="calculatePrice()" min="0" required></td>
                                    <td>
                                        <select name="bierselect[]" onchange="calculatePrice()" required>
                                            <option value="">Biersorte auswählen...</option>
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
                            <input type='hidden' value='1' name='submitted'>
                            <p><input type="submit" value="Speichern"></p>
                        </form>
                    </div>
                </main>
                <?php include "./inc/footer.html" ?>
            </div>
        </div>
        <?php include "./inc/scripts.html" ?>
    </body>
</html>
