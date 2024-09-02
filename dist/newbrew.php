<?php
session_start();

include("connection.php");

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
                        // the form was submitted, so we check for valid form data first
                        $brewDate = $_POST['brewDate'];
                        $beerId = $_POST['beerId'];

                        // we need at least brew date and beer type
                        if (empty($brewDate))
                        {
                            echo "<p style=\"background-color:#E6B7B1;\">Brauche mindestens Braudatum und Biertyp! <i class=\"bi bi-hand-thumbs-down-fill\"></i></p>";
                        }
                        else
                        {
                            // all good, we have valid form data and can create a new database entry

                            $bottleDate = $_POST['bottleDate'];
                            $numBottles = empty($_POST['numBottles']) ? 0 : $_POST['numBottles'];
                            $lotNumber = $_POST['lotNumber'];
                            $notes = $_POST['notes'];

                            $sql = "INSERT INTO brews (brewDate,    beerId,  bottleDate,    numBottles,  lotNumber,    notes) VALUES
                                                      ('$brewDate', $beerId, '$bottleDate', $numBottles, '$lotNumber', '$notes')";

                            $query = mysqli_query($conn, $sql) or die("Could not run SQL query.");

                            echo "<p style=\"background-color:powderblue;\">Neuer Sud/Abfüllung dazugefügt <i class=\"bi bi-hand-thumbs-up-fill\"></i></p>";
                        }
                    }
                    else
                    {
                        // nothing to say if form was not submitted yet
                    }
                    ?>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Neuer Sud/Abfüllung</h1>
                        <ul>
                            <span style="color:gray"><li>Felder dürfen leergelassen (bzw. nachträglich ergänzt) werden. Es muss aber mindestens das Braudatum und die Biersorte ausgefüllt werden.</li></span>
                        </ul>
                        <form id="formIdentifier" method="POST" action="./newbrew.php">
                            <table>
                                <tr valign="top">
                                    <td>Braudatum:</td>
                                    <td>
                                        <input name="brewDate" type="date" required>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Biersorte:</td>
                                    <td>
                                        <select name="beerId" required>
                                            <option value="">Biersorte auswählen...</option>
                                            <?php
                                            echo getBeertypeSelection();
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Abfülldatum:</td>
                                    <td>
                                        <input name="bottleDate" type="date">
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Anzahl Flaschen:</td>
                                    <td>
                                        <input name="numBottles" type="number">
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Best before (=Lotnummer):</td>
                                    <td>
                                        <input name="lotNumber" type="text" placeholder="31.05.23"></input>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Notiz:</td>
                                    <td>
                                        <textarea name="notes"></textarea>
                                    </td>
                                </tr>
                            </table>
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
