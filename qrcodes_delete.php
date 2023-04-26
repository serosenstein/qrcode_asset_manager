<?php
print <<< EOD
<!DOCTYPE html>
	<html>
	<head>
	<link rel="stylesheet" href="style.php" media="screen">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<ul>
	  <li><a href="index.html">Home</a></li>
	    <li><a href="config.php">Settings</a></li>
	      <form action="qrcodes_search.php" method="post">
	        <input type="text" name="quick_search" placeholder="Quick Search..." name="search">
		  <button type="submit"><i class="fa fa-search"></i></button>
		    </form>
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
  echo "<h2>Deleted record!</h2>\n";
} catch(PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
}
$conn = null;
echo "</body></html>\n";
?>
