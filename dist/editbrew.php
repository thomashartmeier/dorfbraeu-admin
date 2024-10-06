<?php
session_start();

include("connection.php");

function getBeertypeSelection($input_beerId)
{
    global $conn;

    $beertypeList = "";

    $sql = "SELECT * FROM beers";
    $query = mysqli_query($conn, $sql) or die("Could not run SQL query.");

    while ($result = mysqli_fetch_assoc($query))
    {
        $res_id = $result['id'];
        $res_type = $result['type'];

        $selectedString = '';

        if ($res_id == $input_beerId)
        {
            $selectedString = 'selected="selected"';
        }

        $beertypeList .= "<option value='${res_id}' $selectedString name='beertype${res_id}' id='beertype${res_id}' size='2'>$res_type</option>";
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
                    $brewId = $_GET['brewid'];

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
                            // all good, we have valid form data and can update the database entry

                            $bottleDate = $_POST['bottleDate'];
                            $numBottles = $_POST['numBottles'];
                            $lotNumber = $_POST['lotNumber'];
                            $notes = $_POST['notes'];

                            $sqlUpdate = "UPDATE brews SET brewDate = '$brewDate', beerId = $beerId, bottleDate = '$bottleDate', numBottles = '$numBottles', lotNumber = '$lotNumber', notes = '$notes' WHERE id = $brewId";

                            $queryUpdate = mysqli_query($conn, $sqlUpdate) or die("Could not run SQL query.");

                            echo "<p style=\"background-color:powderblue;\">Sud/Abfüllung editiert <i class=\"bi bi-hand-thumbs-up-fill\"></i></p>";
                        }
                    }
                    else
                    {
                        // nothing to say if form was not submitted yet
                    }

                    if (empty($brewId))
                    {
                        echo "<p style=\"background-color:#E6B7B1;\">Brauche brewid! <i class=\"bi bi-hand-thumbs-down-fill\"></i></p>";
                    }
                    else
                    {
                        // get the needed data for this brewId
                        $sqlBrew = "SELECT * FROM brews WHERE id = $brewId";
                        $queryBrew = mysqli_query($conn, $sqlBrew) or die("Could not run SQL query.");
                        $resultBrew = mysqli_fetch_assoc($queryBrew);

                        $brew_brewDate = $resultBrew['brewDate'];
                        $brew_beerId = $resultBrew['beerId'];
                        $brew_bottleDate = $resultBrew['bottleDate'];
                        $brew_numBottles = $resultBrew['numBottles'];
                        $brew_lotNumber = $resultBrew['lotNumber'];
                        $brew_notes = $resultBrew['notes'];
                    ?>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Sud/Abfüllung #<?php echo "$brewId";?> editieren</h1>
                        <form id="formIdentifier" method="POST" action="./editbrew.php?brewid=<?php echo $brewId; ?>">
                            <table>
                                <tr valign="top">
                                    <td>Braudatum:</td>
                                    <td>
                                        <input name="brewDate" type="date" value="<?php echo $brew_brewDate;?>" required>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Biersorte:</td>
                                    <td>
                                        <select name="beerId" value="<?php echo $brew_beerId;?>">
                                            <?php
                                            echo getBeertypeSelection($brew_beerId);
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Abfülldatum:</td>
                                    <td>
                                        <input name="bottleDate" type="date" value="<?php echo $brew_bottleDate;?>">
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Anzahl Flaschen:</td>
                                    <td>
                                        <input name="numBottles" type="number" value="<?php echo $brew_numBottles;?>">
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Best before (=Lotnummer):</td>
                                    <td>
                                        <input name="lotNumber" type="text" placeholder="31.05.23" value="<?php echo $brew_lotNumber;?>"></input>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Notiz:</td>
                                    <td>
                                        <textarea name="notes"><?php echo $brew_notes;?></textarea>
                                    </td>
                                </tr>
                            </table>
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
