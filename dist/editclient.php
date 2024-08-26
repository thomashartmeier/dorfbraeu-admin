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
                    <?php
                    $submitted = $_POST['submitted'];
                    $clientId = $_GET['clientid'];

                    if (!empty($submitted))
                    {
                        // the form was submitted, so we check for valid form data first
                        $prename = $_POST['prename'];
                        $lastname = $_POST['lastname'];

                        // we need at least first- or lastname
                        if (empty($prename) && empty($lastname))
                        {
                            echo "<p style=\"background-color:#E6B7B1;\">Brauche mindestens Vor- oder Nachname! <i class=\"bi bi-hand-thumbs-down-fill\"></i></p>";
                        }
                        else
                        {
                            // all good, we have valid form data and can update the database entry

                            $company = $_POST['company'];
                            $isReseller = $_POST['isReseller'];
                            $email = $_POST['email'];
                            $phone = $_POST['phone'];
                            $address = $_POST['address'];
                            $billingAddress = $_POST['billingAddress'];
                            $notes = $_POST['notes'];

                            $sqlUpdate = "UPDATE clients SET isReseller = $isReseller, prename = '$prename', lastname = '$lastname', company = '$company', email = '$email', phone = '$phone', address = '$address', billingAddress = '$billingAddress', notes = '$notes' WHERE id = $clientId";

                            $queryUpdate = mysqli_query($conn, $sqlUpdate) or die("Could not run SQL query.");

                            echo "<p style=\"background-color:powderblue;\">Kundeneintrag editiert <i class=\"bi bi-hand-thumbs-up-fill\"></i></p>";
                        }
                    }
                    else
                    {
                        // nothing to say if form was not submitted yet
                    }

                    if (empty($clientId))
                    {
                        echo "<p style=\"background-color:#E6B7B1;\">Brauche clientid! <i class=\"bi bi-hand-thumbs-down-fill\"></i></p>";
                    }

                    // invalid user id
                    $client_userId = 99;

                    if (!empty($clientId))
                    {
                        // get the user that is assigned to this client
                        $sqlClient = "SELECT * FROM clients WHERE id = $clientId";
                        $queryClient = mysqli_query($conn, $sqlClient) or die("Could not run SQL query.");
                        $resultClient = mysqli_fetch_assoc($queryClient);
                        $client_userId = $resultClient['userId'];
                    }

                    // check that only the user assigned to this client can edit it
                    if ($client_userId != $_SESSION['id'])
                    {
                        echo "<p style=\"background-color:#E6B7B1;\">Du darfst diesen Kundeneintrag nicht editieren! <i class=\"bi bi-hand-thumbs-down-fill\"></i></p>";
                    }
                    else
                    {
                        // get the needed data for this clientId
                        $sqlClient = "SELECT * FROM clients WHERE id = $clientId";
                        $queryClient = mysqli_query($conn, $sqlClient) or die("Could not run SQL query.");
                        $resultClient = mysqli_fetch_assoc($queryClient);

                        $client_prename = $resultClient['prename'];
                        $client_lastname = $resultClient['lastname'];
                        $client_company = $resultClient['company'];
                        $client_isReseller = $resultClient['isReseller'];
                        $client_email = $resultClient['email'];
                        $client_phone = $resultClient['phone'];
                        $client_address = $resultClient['address'];
                        $client_billingAddress = $resultClient['billingAddress'];
                        $client_notes = $resultClient['notes'];

                        $checkedString = $client_isReseller ? "checked" : "";
                    ?>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Kundeneintrag #<?php echo "$clientId";?> editieren</h1>
                        <form id="formIdentifier" method="POST" action="./editclient.php?clientid=<?php echo $clientId; ?>">
                            <table>
                                <tr valign="top">
                                    <td>Vorname:</td>
                                    <td>
                                        <input name="prename" type="text" value="<?php echo $client_prename;?>">
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Nachname:</td>
                                    <td>
                                        <input name="lastname" type="text" value="<?php echo $client_lastname;?>">
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Firma:</td>
                                    <td>
                                        <input name="company" type="text" value="<?php echo $client_company;?>">
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Wiederverkäufer:</td>
                                    <td>
                                        <input type='hidden' value='0' name='isReseller'><input type="checkbox" name='isReseller' value='1' <?php echo $checkedString; ?> />
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>E-mail:</td>
                                    <td>
                                        <input name="email" type="email" value="<?php echo $client_email;?>">
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Telefon/Mobile:</td>
                                    <td>
                                        <input name="phone" type="tel" pattern="[0-9]{3}( )?[0-9]{3}( )?[0-9]{2}( )?[0-9]{2}" placeholder="079 123 45 67" value="<?php echo $client_phone;?>">
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Adresse:</td>
                                    <td>
                                        <input name="address" type="text" value="<?php echo $client_address;?>">
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Rechnungsadresse:</td>
                                    <td>
                                        <input name="billingAddress" type="text" value="<?php echo $client_billingAddress;?>">
                                        <ul>
                                            <span style="color:gray"><li>Muss nur ausgefüllt werden falls abweichend von obiger (Liefer-)Adresse.</li></span>
                                        </ul>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Notiz:</td>
                                    <td>
                                        <textarea name="notes"><?php echo $client_notes;?></textarea>
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
