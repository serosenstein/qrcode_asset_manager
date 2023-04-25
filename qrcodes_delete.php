<?php
print <<< EOD
<!DOCTYPE html>
	<html>
	<head>
	<link rel="stylesheet" href="style.php" media="screen">
	<ul>
	  <li><a href="index.html">Home</a></li>
	    <li><a href="config.php">Settings</a></li>
	    </ul>
EOD;
$str = file_get_contents('vars.json');
$json = json_decode($str, true);
foreach ($json as $field => $value) {
$$field = $value;
}

if (isset($_POST["device_id"]))
{
	$device_id = $_POST["device_id"];
}
if ($device_id == "" ){
	echo "No device ID provided";
	exit(1);
}	
$CLAUSE = "delete from qrcodes where device_id = \"$device_id\"";
echo "<html><body><title>QR Code Asset Delete</title>";
try {
  $conn = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // use exec() because no results are returned
  $result = $conn->query($CLAUSE);
  echo "Deleted record!\n";
} catch(PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
}
$conn = null;
echo "</body></html>\n";
?>
