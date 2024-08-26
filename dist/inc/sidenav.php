<?php

echo "<nav class=\"sb-sidenav accordion sb-sidenav-dark\" id=\"sidenavAccordion\">\n";
echo "    <div class=\"sb-sidenav-menu\">\n";
echo "        <div class=\"nav\">\n";
echo "            <div class=\"sb-sidenav-menu-heading\">Info</div>\n";
echo "            <a class=\"nav-link\" href=\"index.php\">\n";
echo "                <div class=\"sb-nav-link-icon\"><i class=\"fas fa-tachometer-alt\"></i></div>\n";
echo "                Dashboard\n";
echo "            </a>\n";
echo "            <a class=\"nav-link\" href=\"prices.php\">\n";
echo "                <div class=\"sb-nav-link-icon\"><i class=\"bi bi-currency-dollar\"></i></div>\n";
echo "                Preisgestaltung\n";
echo "            </a>\n";
echo "            <div class=\"sb-sidenav-menu-heading\">Bestellungen</div>\n";
echo "            <a class=\"nav-link\" href=\"openorders.php\">\n";
echo "                <div class=\"sb-nav-link-icon\"><i class=\"bi bi-cart-fill\"></i></div>\n";
echo "                Offene Bestellungen\n";
echo "            </a>\n";
echo "            <a class=\"nav-link\" href=\"allorders.php\">\n";
echo "                <div class=\"sb-nav-link-icon\"><i class=\"bi bi-cart-fill\"></i></div>\n";
echo "                Alle Bestellungen\n";
echo "            </a>\n";
echo "            <a class=\"nav-link\" href=\"neworder.php\">\n";
echo "                <div class=\"sb-nav-link-icon\"><i class=\"bi bi-plus\"></i></div>\n";
echo "                Neue Bestellung\n";
echo "            </a>\n";
echo "            <div class=\"sb-sidenav-menu-heading\">Kunden</div>\n";
echo "            <a class=\"nav-link\" href=\"clients.php\">\n";
echo "                <div class=\"sb-nav-link-icon\"><i class=\"bi bi-file-earmark-person\"></i></div>\n";
echo "                Alle Kunden\n";
echo "            </a>\n";
echo "            <a class=\"nav-link\" href=\"newclient.php\">\n";
echo "                <div class=\"sb-nav-link-icon\"><i class=\"bi bi-plus\"></i></div>\n";
echo "                Neuer Kundeneintrag\n";
echo "            </a>\n";
echo "            <div class=\"sb-sidenav-menu-heading\">Sude/Abfüllungen</div>\n";
echo "            <a class=\"nav-link\" href=\"allbrews.php\">\n";
echo "                <div class=\"sb-nav-link-icon\"><i class=\"bi bi-droplet-fill\"></i></div>\n";
echo "                Alle Sude/Abfüllungen\n";
echo "            </a>\n";
echo "            <a class=\"nav-link\" href=\"newbrew.php\">\n";
echo "                <div class=\"sb-nav-link-icon\"><i class=\"bi bi-plus\"></i></div>\n";
echo "                Neuer Sud/Abfüllung\n";
echo "            </a>\n";
echo "        </div>\n";
echo "    </div>\n";
echo "    <div class=\"sb-sidenav-footer\">\n";
echo "        <div class=\"small\">Logged in as:</div>\n";

$prename = $_SESSION['prename'];
echo "$prename\n";

echo "    </div>\n";
echo "</nav>\n";

?>