<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<head>
<meta charset="utf-8"/>
<title>Temperatur, Luftdruck und Luftfeuchtigkeit vom BME280</title>
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="expires" content="-1" />
<meta http-equiv="refresh" content="1800; URL=bme280.php" />
</head>
<?php
echo "<table style='border: solid 3px black; width:100%; text-align:center;'>";
echo "<tr><th colspan='4'; style='border: solid 3px black; width:25%; text-align:center;'>BME280</th></tr>";
echo "<tr><th style='border: solid 3px grey; width:25%; text-align:center;'>°C</th><th style='border: solid 3px grey; width:25%; text-align:center;'>%</th><th style='border: solid 3px grey; width:25%; text-align:center;'>hPa</th><th style='border: solid 3px grey; width:25%; text-align:center;'>Meßzeit</th></tr>";

class TableRows1 extends RecursiveIteratorIterator {
  function __construct($it) {
    parent::__construct($it, self::LEAVES_ONLY);
  }

  function current() {
    return "<td style='border: solid 3px black; width:25%; text-align:center;'>" . parent::current(). "</td>";
  }

  function beginbme280() {
    echo "<tr style='border: solid 3px black; width:25%; text-align:center;'>";
  }

  function endbme280() {
    echo "</tr>" . "\n";
  }
}

$servername = "localhost";
$username = "sqlUsername";
$password = "sqlPassword";
$dbname = "datenbankname";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $stmt = $conn->prepare("SELECT temperatur, luftfeuchtigkeit, luftdruck, datumzeit FROM daten ORDER BY datumzeit DESC LIMIT 0,1");
  $stmt->execute();

  // set the resulting array to associative
  $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

  foreach(new TableRows1(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
    echo $v;
  }
}
catch(PDOException $e) {
  echo "Error: " . $e->getMessage();
}
$conn = null;

echo "</table>";
?>
</html>
