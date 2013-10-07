<html>
<body>
<h1>Tabellen anlegen</h1>

<?
error_reporting (E_ALL);
ini_set("display_errors", TRUE);
include "config.php";
include "mysql.php";
openDatabase();

echo "<h2>Anlegen der Tabelle Pagerank</h2>";
createPagerankTable ();
echo "<br>";

echo "<h2>Anlegen der Tabelle Backlink</h2>";
createBacklinkTable ();
echo "<br>";

echo "<h2>Anlegen der Tabelle Request</h2>";
createRequestTable ();
echo "<br>";

echo "<h2>Anlegen der Tabelle Proxy</h2>";
createProxyTable ();
echo "<br>";

closeDatabase();
?>

</body>
</html>
