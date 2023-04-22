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
	#echo "<br><br>\n";
	$CLAUSE .= " AND device_details like '%$device_details%' ";
 	$CLAUSE_COUNT++;
}
if ( $device_name == "" && $device_id == "" && $device_details == "" ) {
	echo "Displaying results for all";
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
echo  "<br><a class=\"fcc-btn\" href=\"index.html\">Back to main page</a><br>\n";
echo "<script>function printpage() {\nwindow.print();}</script>";
try {
  $conn = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // use exec() because no results are returned
  $result = $conn->query($CLAUSE);
  if ($result->rowCount() > 0) {
	  #echo "\n<br><center><input class=\"noprint\" type=\"button\" value=\"Print Page\" onclick=\"printpage()\" /></center>";
	  echo "<form method=\"post\" id=\"SubmitForm\">\n";
	  echo "<table style=\"width:100%\"><tr><th>Device ID</th><th>Device Name</th><th>Device Details</th><th>QR code</th><th>Print</th><th>Delete/Edit Device</th></tr>\n";
          while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                #echo "id: " . $row["device_id"]. " - Name: " . $row["device_name"]. " " . $row["device_details"]. "<br>";
                $new_device_id = $row["device_id"];
                $new_device_name = $row["device_name"];
                $new_device_details = $row["device_details"];
                $new_device_qrcode = $row["qrcode"];
                #$base_64_image = base64_encode($new_device_qrcode);
		echo "<tr>\n";
		echo "<td>\n$new_device_id</td>";
		echo "<td>\n$new_device_name</td>";
		echo "<td>\n$new_device_details</td>";
                echo '<td><img src="data:image/png;base64,'.$new_device_qrcode .'" /></td>';
		echo "<td><input type=\"checkbox\" name=print_device_id[] value=\"$new_device_id\"> Print</td>";
		echo "<td><input type=\"radio\" name=device_id[] value=\"$new_device_id\"> Delete/Edit</td>";
		echo "</tr>";
  }
	  	echo "</table>\n";
		echo "<input type=\"submit\" name=\"update_button\" formaction=\"qrcodes_select.php\" value=\"Update\" />";
		echo "<input type=\"submit\" name=\"delete_button\" formaction=\"qrcodes_select.php\" value=\"Delete\" />\n<br><br>";
print <<<EOD
		Template Name: <select class="template" name="template">
		    <option value="94103">94103</option>
		    <option value="5160">5160</option>
		    <option value="5161">5161</option>
		    <option value="5162">5162</option>
		    <option value="5163">5163</option>
		    <option value="5164">5164</option>
		    <option value="8600">8600</option>
		    <option value="L7163">L7163</option>
		    <option value="3422">3422</option>
		</select><br>
EOD;
		echo "Optional: Number of blank placeholders you want to have at beginning of labels: <input type=\"text\" name=\"skip_number\" placeholder=\"e.g. 4\" value=\"\"><br>\n";
		echo "<input type=\"submit\" name=\"print\" formaction=\"print_label.php\" value=\"Generate Labels\" />";
		echo "</form>\n";

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
