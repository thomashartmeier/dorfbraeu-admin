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
                            // all good, we have valid form data and can create a new database entry

                            $company = $_POST['company'];
                            $isReseller = $_POST['isReseller'];
                            $email = $_POST['email'];
                            $phone = $_POST['phone'];
                            $address = $_POST['address'];
                            $billingAddress = $_POST['billingAddress'];
                            $notes = $_POST['notes'];

                            $createDate = date("Y-m-d");

                            $sql = "INSERT INTO clients (createDate,    prename,    lastname,    company,    isReseller,  email,    phone,    address,    billingAddress,    userId,          notes) VALUES
                                                        ('$createDate', '$prename', '$lastname', '$company', $isReseller, '$email', '$phone', '$address', '$billingAddress', $_SESSION['id'], '$notes')";

                            $query = mysqli_query($conn, $sql) or die("Could not run SQL query.");

                            echo "<p style=\"background-color:powderblue;\">Neuer Kundeneintrag dazugefügt <i class=\"bi bi-hand-thumbs-up-fill\"></i></p>";
                        }
                    }
                    else
                    {
                        // nothing to say if form was not submitted yet
                    }
                    ?>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Neuer Kundeneintrag</h1>
                        <ul>
                            <span style="color:gray"><li>Felder dürfen leergelassen (bzw. nachträglich ergänzt) werden. Es muss aber mindestens Vor- oder Nachname ausgefüllt werden.</li></span>
                        </ul>
                        <form id="formIdentifier" method="POST" action="./newclient.php">
                            <table>
                                <tr valign="top">
                                    <td>Vorname:</td>
                                    <td>
                                        <input name="prename" type="text">
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Nachname:</td>
                                    <td>
                                        <input name="lastname" type="text">
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Firma:</td>
                                    <td>
                                        <input name="company" type="text">
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Wiederverkäufer:</td>
                                    <td>
                                        <input type='hidden' value='0' name='isReseller'><input type="checkbox" name='isReseller' value='1' />
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>E-mail:</td>
                                    <td>
                                        <input name="email" type="email">
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Telefon/Mobile:</td>
                                    <td>
                                        <input name="phone" type="tel" pattern="[0-9]{3}( )?[0-9]{3}( )?[0-9]{2}( )?[0-9]{2}" placeholder="079 123 45 67">
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Adresse:</td>
                                    <td>
                                        <input name="address" type="text">
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Rechnungsadresse:</td>
                                    <td>
                                        <input name="billingAddress" type="text">
                                        <ul>
                                            <span style="color:gray"><li>Muss nur ausgefüllt werden falls abweichend von obiger (Liefer-)Adresse.</li></span>
                                        </ul>
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
