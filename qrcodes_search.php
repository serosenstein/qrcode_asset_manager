<?php
include 'vars.php';
$CLAUSE = "select * from qrcodes where ";
if (isset($_POST["device_id"]))
{
	$device_id = $_POST["device_id"];
	if ($device_id != "" ){
		#if ($_POST["device_name"] != "" || $_POST["device_details"] != "") {
		#$CLAUSE .= "device_id = '$device_id' AND ";
		#} else {
		$CLAUSE .= "device_id = '$device_id' AND ";
		#}
	}
	#echo "Clause $CLAUSE";
}
if (isset($_POST["device_name"]))
{
	$device_name = $_POST["device_name"];
	$device_name = preg_replace('/[^a-zA-Z0-9-_\.]/','', $device_name);
	$CLAUSE .= " device_name like '%$device_name%' ";
 	$CLAUSE_COUNT++;
	

}
if (isset($_POST["device_details"]))
{
	$device_details= $_POST["device_details"];
	echo "<br><br>\n";
	$CLAUSE .= " AND device_details like '%$device_details%' ";
 	$CLAUSE_COUNT++;
}
if ( $device_name == "" && $device_id == "" && $device_details == "" ) {
	echo "Searching for all...";
	$CLAUSE = "select * from qrcodes";
} else {
	if ($device_name == "") {
		$device_name = "NULL";
	}
	if ( $device_id == "" ) {
		$device_id = "NULL";
	}
	if ( $device_details == "" ) {
		$device_details = "NULL";
	}
	echo "Display Results for<br>\nDevice ID: $device_id<br>\nDevice Name: $device_name<br>\nDevice Details: $device_details";
	echo  "<br><a class=\"fcc-btn\" href=\"index.html\">Back to main page</a><br>\n";
}
$CLAUSE .= ";";
echo "<html><body><title>QR Code Asset Search</title>";
echo "<style>\ntable, th, td {\nborder: 1px solid black;\n}\n@media print {\n.noprint { display: none; }\n}</style>\n";
echo "<script>function printpage() {\nwindow.print();}</script>";
try {
  $conn = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // use exec() because no results are returned
  $result = $conn->query($CLAUSE);
  if ($result->rowCount() > 0) {
	  echo "\n<br><center><input class=\"noprint\" type=\"button\" value=\"Print Page\" onclick=\"printpage()\" /></center>";
	  echo "<table style=\"width:100%\"><tr><th>Device ID</th><th>Device Name</th><th>Device Details</th><th>QR Code</th></tr>\n";
          while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                #echo "id: " . $row["device_id"]. " - Name: " . $row["device_name"]. " " . $row["device_details"]. "<br>";
                $new_device_id = $row["device_id"];
                $new_device_name = $row["device_name"];
                $new_device_details = $row["device_details"];
                $new_device_qrcode = $row["qrcode"];
                $base_64_image = base64_encode($new_device_qrcode);
		echo "<tr>\n";
		echo "<td>\n$new_device_id</td>";
		echo "<td>\n$new_device_name</td>";
		echo "<td>\n$new_device_details</td>";
                #echo "<br><img src=\"data:image/png:base64"."\"$base_64_image\""." alt=\"\" />\n<br>";

                echo '<td><img src="data:image/png;base64,'.$base_64_image .'" /><td>';
                #echo '<img src="data:image/png;base64,'.base64_encode($new_device_qrcode->load()) .'" />';
		echo "</tr>\n";
  }
	  	echo "</table>\n";
	        echo '<center><input class="noprint" type="button" value="Print Page" onclick="printpage()" /></center>';
  } else {
	echo "<h1>No matches found for your search, please try again</h1>";
  }
} catch(PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
}
echo  "<br><a class=\"fcc-btn\" href=\"index.html\">Back to main page</a><br>\n";
$conn = null;
echo "</body></html>\n";
?>
