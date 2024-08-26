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
                <?php include "./inc/sidenav.html" ?>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Preisgestaltung</h1>
                        <hr>

                        <h2 class="mt-4">Preise 0.3l Bierflaschen</h2>

                        <h3 class="mt-4">Normale Kunden</h3>

                        <table style="border: 1px; border-color: black;">
                            <tr>
                                <th>Anz. Flaschen</th>
                                <th>Anz. Harasse</th>
                                <th>Preis pro Flasche (CHF)</th>
                                <th>Preis pro Harass (CHF)</th>
                                <th>Rabatt</th>
                            </tr>
                            <tr>
                                <td>&lt;24</td>
                                <td>&lt;1</td>
                                <td>4.-</td>
                                <td>(96.-)</td>
                                <td>0%</td>
                            </tr>
                            <tr>
                                <td>24-96</td>
                                <td>1-4</td>
                                <td>3.75</td>
                                <td>90.-</td>
                                <td>6.25%</td>
                            </tr>
                            <tr>
                                <td>97-239</td>
                                <td>5-9</td>
                                <td>3.50</td>
                                <td>84.-</td>
                                <td>12.5%</td>
                            </tr>
                            <tr>
                                <td>&ge;240</td>
                                <td>&ge;10</td>
                                <td>3.25</td>
                                <td>78.-</td>
                                <td>18.75%</td>
                            </tr>
                            <tr>
                                <td>ganzer Sud</td>
                                <td>ganzer Sud</td>
                                <td>3.-</td>
                                <td>72.-</td>
                                <td>25%</td>
                            </tr>
                        </table>

                        <h3 class="mt-4">Business Kunden, Wiederverkäufer</h3>

                        <ul>
                            <li>25Rp. günstiger als für "normale Kunden"</li>
                            <li>Anzahl Bier, das verkauft wurde, wird übers ganze Jahr kumuliert und Mengenrabatt auf kumulierte Menge berechnet. Der Rabatt wird Ende Jahr rückerstattet.</li>
                        </ul>

                        <table style="border: 1px; border-color: black;">
                            <tr>
                                <th>Anz. Flaschen</th>
                                <th>Anz. Harasse</th>
                                <th>Preis pro Flasche (CHF)</th>
                                <th>Preis pro Harass (CHF)</th>
                                <th>Rabatt</th>
                            </tr>
                            <tr>
                                <td>&lt;24</td>
                                <td>&lt;1</td>
                                <td>3.75</td>
                                <td>(90.-)</td>
                                <td>6.25%</td>
                            </tr>
                            <tr>
                                <td>24-96</td>
                                <td>1-4</td>
                                <td>3.50</td>
                                <td>84.-</td>
                                <td>12.5%</td>
                            </tr>
                            <tr>
                                <td>97-239</td>
                                <td>5-9</td>
                                <td>3.25</td>
                                <td>78.-</td>
                                <td>18.75%</td>
                            </tr>
                            <tr>
                                <td>&ge;240</td>
                                <td>&ge;10</td>
                                <td>3.-</td>
                                <td>72.-</td>
                                <td>25%</td>
                            </tr>
                            <tr>
                                <td>ganzer Sud</td>
                                <td>ganzer Sud</td>
                                <td>2.75</td>
                                <td>66.-</td>
                                <td>31.25%</td>
                            </tr>
                        </table>

                        <h2 class="mt-4">Preise Harasse</h2>

                        <p>Harasse werden nur ausgeliehen und wir verlangen 10.- Depot pro Harass.</p>
                    </div>
                </main>
                <?php include "./inc/footer.html" ?>
            </div>
        </div>
        <?php include "./inc/scripts.html" ?>
    </body>
</html>
