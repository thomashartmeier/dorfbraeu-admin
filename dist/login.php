<?php
session_start();

include("connection.php");
?>

<!DOCTYPE html>
<html lang="en">
    <?php include "./inc/head.html" ?>
    <body class="bg-dark">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <?php
                    $submitted = $_POST['submitted'];

                    if (!empty($submitted))
                    {
                        $inputUser = $_POST['inputUser'];
                        $inputPassword = $_POST['inputPassword'];

                        $sql = "SELECT * FROM users WHERE username='$inputUser'";
                        $query = mysqli_query($conn, $sql) or die("Could not run SQL query.");
                        $resultUser = mysqli_fetch_assoc($query);
                        $userPasswordHashed = $resultUser['password'];

                        $success = password_verify($inputPassword, $userPasswordHashed);

                        if ($success)
                        {
                            // store to SESSION
                            $_SESSION['id'] = $resultUser['id'];
                            $_SESSION['username'] = $resultUser['username'];
                            $_SESSION['prename'] = $resultUser['prename'];

                            echo "<p style=\"background-color:powderblue;\">Here we go! <i class=\"bi bi-hand-thumbs-up-fill\"></i></p>";

                            // direct to index page
                            header("location: index.php");
                        }
                        else
                        {
                            echo "<p style=\"background-color:#E6B7B1;\">Falsches Passwort! <i class=\"bi bi-hand-thumbs-down-fill\"></i></p>";
                        }
                    }
                    ?>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card border-1 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Login</h3></div>
                                    <div class="card-body">
                                        <form method="POST" action="#">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" name="inputUser" id="inputUser" type="text" />
                                                <label for="inputUser">User</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" name="inputPassword" id="inputPassword" type="password" />
                                                <label for="inputPassword">Password</label>
                                            </div>
                                            <input type='hidden' value='1' name='submitted'>
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <input type="submit" value="Login" class="button">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                <?php include "./inc/footer.html" ?>
            </div>
        </div>
        <?php include "./inc/scripts.html" ?>
    </body>
</html>
