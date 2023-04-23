<?php
include 'vars.php';
$device_array = $_POST["device_id"];
$device_array_length = sizeof($device_array);
if (isset($_POST['update_button'])) {
   echo "<h1>Update Menu</h1>\n";
	if ($device_array_length == "0") {
		echo "<br><h1>ERROR: you didn't select a radio button to update</h1>\n";
		echo  "<br><a class=\"fcc-btn\" href=\"index.html\">Back to main page</a><br>\n";
		exit(1);
	}
   $action = "update";
} else if (isset($_POST['delete_button'])) {
   echo "</h1>Delete Menu</h1>\n";
	if ($device_array_length == "0") {
		echo "<br><h1>ERROR: you didn't select a radio button to delete</h1>\n";
		echo  "<br><a class=\"fcc-btn\" href=\"index.html\">Back to main page</a><br>\n";
		exit(1);
	}
   $action = "delete";
} else {
	echo "well not sure how you got here, didn't use update or delete buttons I reckon\n";
	exit(1);
}
$device_commas="";
for($i=0;$i<count($device_array);$i++){
	$device_commas .= "'$device_array[$i]',";
     }
$id_clause = rtrim($device_commas, ',');
$id_clause = "($id_clause);";
$CLAUSE = "select * from qrcodes where device_id in $id_clause";

if (isset($_POST["device_id"]))
{
	$device_id = $_POST["device_id"];
	if ($device_id == "" ){
		echo "No device ID provided";
		exit(1);
	}
}
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
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $sql_device_id = $row["device_id"];
                $sql_device_name = $row["device_name"];
                $sql_device_details = $row["device_details"];
	}

  if ($result->rowCount() > 0) {
	 
		if ($action == "delete") {
	  		echo "<form method=\"post\" action=\"qrcodes_delete.php\" id=\"SubmitForm\">\n";
                	echo "Device ID: <input name=\"device_id\" value=$sql_device_id readonly>\n";
			echo "<br>Device Name: <input type=\"text\" name=\"device_name\" value=$sql_device_name readonly><br>\n";
                        echo "Device Details: <br><textarea class=\"FormElement\" name=\"device details\" id=\"device_details\" cols=\"100\" rows=\"20\" readonly>$sql_device_details</textarea>\n";
		} else if ($action = "update") {
	  		echo "<form method=\"post\" action=\"qrcodes_update.php\" id=\"SubmitForm\">\n";
                	echo "Device ID (immutable): <input name=\"device_id\" value=$sql_device_id readonly>\n";
			echo "<br>Device Name: <input type=\"text\" name=\"device_name\" value=$sql_device_name><br>\n";
                        echo "Device Details: <br><textarea class=\"FormElement\" name=\"device details\" id=\"device_details\" cols=\"100\" rows=\"20\" >$sql_device_details</textarea>\n";
		} else {
			echo "not sure how you got here";
			exit(1);
		}

		if ($action == "delete") {
                	echo "<br><br><button type=\"submit\" action=\"qrcode_delete.php\">Yes, I'm sure I want to delete this record!</button>\n";
		} else if ($action = "update") {
                	echo "<br><button type=\"submit\" action=\"qrcode_update\">Update!</button>\n";
		} else {
			echo "not sure how you got here";
			exit(1);
		}
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
