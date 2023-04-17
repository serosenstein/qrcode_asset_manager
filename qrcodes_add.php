<?php
include 'vars.php';

if (isset($_POST["device_name"]))
{
	$device_name = $_POST["device_name"];
	$device_name = preg_replace('/[^a-zA-Z0-9-_\.]/','', $device_name);
	echo "devicename: $device_name\n";

} else {
	exit("device name not set");
}
if (isset($_POST["device_details"]))
{
	$device_details= $_POST["device_details"];
	$to_encode = "mailto:serosenstein@gmail.com?subject=INFO%20$device_name&body=$device_details";
	$fileName = "$filePath/$device_name.png";
	$mailto = "\"mailto:serosenstein@gmail.com?subject=INFO%20$device_name&body=";
	$cmd = "$mailto%20$device_name%0A$device_details";
	$space_cmd = str_replace(' ','%20', $cmd);
	$newline_cmd = str_replace('\n','%0A',$space_cmd);
	echo "command $cmd\n";
	$final_cmd = "echo $newline_cmd\" | qrencode -o $sharePath/$device_name.png";
	echo "<br><br>final command: $final_cmd<br><br>";
	#$output = shell_exec($cmd);
	echo exec("$final_cmd 2>&1",$output,$status);
        print_r($output);
	echo "<br><br>\n";
} else {
	exit("device details not set");
}
echo "<html><body>\n";
echo "Device name: $device_name\n";
echo "Device details: $device_details\n";
try {
  $conn = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "INSERT INTO qrcodes (device_name, device_details, qrcode) VALUES (\"$device_name\", \"$device_details\", LOAD_FILE(\"$fileName\"));";
  echo "$sql";

  // use exec() because no results are returned
  $conn->exec($sql);
  echo "New record created successfully";
} catch(PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
}
try {
  $conn = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT * FROM qrcodes ORDER BY device_id DESC LIMIT 1;";
  echo "\n$sql\n";

  // use exec() because no results are returned
  $result = $conn->query($sql);
  print_r($result);
  if ($result->rowCount() > 0) {
	  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
    		echo "id: " . $row["device_id"]. " - Name: " . $row["device_name"]. " " . $row["device_details"]. "<br>";
                $new_device_id = $row["device_id"];
                $new_device_name = $row["device_name"];
                $new_device_details = $row["device_details"];
                $new_device_qrcode = $row["qrcode"];
		$base_64_image = base64_encode($new_device_qrcode);

		echo "<form method=\"post\" action=\"qrcodes_search.php\" id=\"SubmitForm\">\n";
		echo "<input type=\"hidden\" name=\"device_id\" value=$new_device_id>\n";
		echo "<input type=\"hidden\" name=\"device_name\" value=$new_device_name>\n";
		echo "<button type=\"submit\">Submit</button>\n";
		 echo "</form>\n";
		

		echo "<script type=\"text/javascript\">\ndocument.getElementById(\"SubmitForm\").submit();\n</script>\n";
	

		
  }
  }
} catch(PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
}

$conn = null;
echo "</body></html>\n";
?>
