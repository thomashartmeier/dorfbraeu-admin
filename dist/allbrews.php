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
                        <h1 class="mt-4">Alle Sude/Abfüllungen</h1>
                        <ul>
                            <span style="color:gray"><li>Grundsätzlich wollen wir unsere Sude auf <a href="https://brauen.online">brauen.online</a> protokollieren. Jedoch ist es hilfreich auch in diesem Tool v.a. die Anzahl abgefüllter Flaschen zu haben, sodass wir einen Überblick haben können (siehe <a href="./index.php">Dashboard</a>), wieviele Flaschen wir noch pro Sorte haben.</li></span>
                            <span style="color:gray"><li>Anders als bei den Kunden und Bestellungen können hier alle die Sude/Abfüllungen Einträge editieren (da es häufig vorkommen wird, dass jemand anders abfüllt als gebraut hat).</li></span>
                        </ul>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Alle Sude/Abfüllungen
                            </div>

                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>Sudnummer</th>
                                            <th>Braudatum</th>
                                            <th>Biertyp</th>
                                            <th>Abfülldatum</th>
                                            <th>Anzahl Flaschen</th>
                                            <th>Best before (=Lotnummer)</th>
                                            <th>Notiz</th>
                                            <th>Edit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT * FROM brews WHERE id > 0";
                                        $query = mysqli_query($conn, $sql) or die("Could not run SQL query.");

                                        while ($result = mysqli_fetch_assoc($query))
                                        {
                                            $res_id = $result['id'];
                                            $res_brewDate = $result['brewDate'];
                                            $res_beerId = $result['beerId'];
                                            $res_bottleDate = $result['bottleDate'];
                                            $res_numBottles = $result['numBottles'];
                                            $res_lotNumber = $result['lotNumber'];
                                            $res_notes = $result['notes'];

                                            echo "<tr>\n";
                                            echo "    <td>$res_id</td>\n";
                                            echo "    <td>$res_brewDate</td>\n";

                                            // get name for beerId
                                            $sqlBeer = "SELECT * FROM beers WHERE id = $res_beerId";
                                            $queryBeer = mysqli_query($conn, $sqlBeer) or die("Could not run SQL query.");
                                            $resultBeer = mysqli_fetch_assoc($queryBeer);
                                            $beerType = $resultBeer['type'];
                                            $beerColor = $resultBeer['color'];

                                            echo "    <td><span style=\"height: 15px; width: 15px; background-color: $beerColor; border-radius: 50%; display: inline-block;\"></span> $beerType</td>\n";

                                            echo "    <td>$res_bottleDate</td>\n";
                                            echo "    <td>$res_numBottles</td>\n";
                                            echo "    <td><pre>$res_lotNumber</pre></td>\n";
                                            echo "    <td>$res_notes</td>\n";

                                            // allow edit for all users
                                            echo "    <td><a href=\"editbrew.php?brewid=$res_id\">edit</a></td>\n";

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
