<?php
include 'vars.php';
#print_r($_POST);
#$device_id = $_POST["device_id"];
#echo "$device_arry is $device_array_length length";

#echo "$id_clause";
#echo "$CLAUSE";

if (isset($_POST["device_id"]))
{
	$device_id = $_POST["device_id"];
}
if ($device_id == "" ){
	echo "No device ID provided";
	exit(1);
}	
$CLAUSE = "delete from qrcodes where device_id = \"$device_id\"";
echo "<br>\nCLAUSE: $CLAUSE\n";
echo "<html><body><title>QR Code Asset Delete</title>";
try {
  $conn = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // use exec() because no results are returned
  $result = $conn->query($CLAUSE);
  #echo $result; 
  echo "Deleted record!\n";
} catch(PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
}
echo  "<br><a class=\"fcc-btn\" href=\"index.html\">Back to main page</a><br>\n";
$conn = null;
echo "</body></html>\n";
?>
