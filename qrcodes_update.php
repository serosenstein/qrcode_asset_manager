<?php
include 'vars.php';
#print_r($_POST);

#echo "$id_clause";
#echo "$CLAUSE";

if (isset($_POST["device_id"]))
{
	$device_id = $_POST["device_id"];
}
if (isset($_POST["device_name"]))
{
	$device_name = $_POST["device_name"];
}
if ($device_id == "" ){
	echo "No device ID provided";
	exit(1);
}	
if ($device_name == "" ){
		echo "No device name provided";
		exit(1);
}	
if (isset($_POST["device_details"]))
{
        $device_details= $_POST["device_details"];
        $fileName = "$filePath/$device_name.png";
        $mailto = "\"mailto:$email?subject=INFO%20$device_name&body=";
        $cmd = "$mailto%20$device_name%0A$device_details";
        $space_cmd = str_replace(' ','%20', $cmd);
        $newline_cmd = str_replace('\n','%0A',$space_cmd);
        #echo "command $cmd\n";
        $final_cmd = "echo $newline_cmd\" | qrencode -o - | base64";
        #echo "<br><br>final command: $final_cmd<br><br>";
        $qr_result = shell_exec("$final_cmd 2>&1");
        echo "\n\n<br><br>\n";
} else {
        exit("device details not set");
}

$CLAUSE = "update qrcodes set device_name = \"$device_name\", device_details=\"$device_details\",qrcode=\"$qr_result\" where device_id  = \"$device_id\"";
#echo "CLAUSE: $CLAUSE\n<br>\n";
echo "<html><body><title>QR Code Asset Delete</title>";
try {
  $conn = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // use exec() because no results are returned
  $result = $conn->query($CLAUSE);
  #echo $result; 
  echo "Record updated successfully!\n<br>";
} catch(PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
}
  echo  "<br><a class=\"fcc-btn\" href=\"index.html\">Back to main page</a><br>\n";
$conn = null;
echo "</body></html>\n";
?>
