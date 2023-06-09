<?php
$str = file_get_contents('vars.json');
$json = json_decode($str, true);
foreach ($json as $field => $value) {
	$$field = $value;
}
print <<< EOD
<!DOCTYPE html>
	<html>
	<head>
	<link rel="stylesheet" href="style.php" media="screen">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="icon" type="image/x-icon" href="favicon.ico">
	<ul>
	  <li><a href="add_device.php">Add Device</a></li>
	    <li><a href="config.php">Settings</a></li>
	    <li><a href="qrcodes_tags.php">Tag Colors</a></li>
	      <form action="qrcodes_search.php" method="post">
	        <input type="text" name="quick_search" placeholder="Quick Search..." name="search">
		  <button type="submit"><i class="fa fa-search"></i></button>
		    </form>
	    </ul>
EOD;
function getNextKey() {
try {
	$str = file_get_contents('vars.json');
	$json = json_decode($str, true);
	foreach ($json as $field => $value) {
		$$field = $value;
	}
	  $conn1 = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
	  // set the PDO error mode to exception
	  $conn1->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
	  $conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	  $analyzesql = "ANALYZE TABLE $dbname;";
	  $nextKeysql = "SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE table_name = '$dbname';";

	  // use exec() because no results are returned
	  $conn1->query($analyzesql);
	    $result1 = $conn1->query($nextKeysql);
	    if ($result1->rowCount() > 0) {
		    while($row1 = $result1->fetch(PDO::FETCH_ASSOC)) {
			 $nextKey = $row1["AUTO_INCREMENT"];
			 return $nextKey;
		    }
	    }
	} catch(PDOException $e) {
		  echo $nextKeysql . "<br>" . $e->getMessage();
		  exit(1);
	}
	$conn1 = null;
}
if (isset($_POST["qrcode_action"]))
{
	$qrcode_action = $_POST["qrcode_action"];
	if ($qrcode_action == "") 
	{
		echo "ERROR: ID10T: you didn't specify a qrcode action!\n";
		exit(1);
	}
	if ($qrcode_action != "email" && $qrcode_action != "URL") {
	
		echo "action $qrcode_action<br>\n";
		echo "ERROR: ID10T: qrcode action is not \"email\" or \"URL\"!\n";
		exit(1);
	}

} else {
	echo "action $qrcode_action<br>\n";
	exit("qrcode action not set: should be \"email\" or \"URL\"");
}
if (isset($_POST["device_name"]))
{
	$device_name = $_POST["device_name"];
	if ($device_name != "") 
	{
		$device_name = preg_replace('/[^a-zA-Z0-9-_\.]/','_', $device_name);
	} else {
		echo "ERROR: ID10T: you didn't specify a device name!\n";
		exit(1);
	}

} else {
	exit("device name not set");
}
if (isset($_POST["colors"])) {
	$color_array = explode("|", $_POST["colors"]);
	$tag_name = $color_array[0];
	$foreground_color = str_replace('#','', $color_array[1]);
	$background_color = str_replace('#','', $color_array[2]);
	global $foreground_color;
	global $background_color;

} else {
	echo "no color set, using black and white as default";
	$background_color  = "ffffff";
	$foreground_color = "000000";
	global $foreground_color;
	global $background_color;
}
if (isset($_POST["device_details"]))
{
	$device_details= $_POST["device_details"];
	if ($device_details == "") {
		echo "ERROR: ID10T: device details not!\n";
		exit(1);
	}
	if ($qrcode_action == "email") {
		if ($email == "") {
			echo "ERROR: ID10T: please set email in settings page";
			exit(1);
		}
		$nextDeviceId = getNextKey();
		$mailto = "mailto:$email?subject=INFO%20$device_name&body=";
		$cmd = "$mailto%20$device_name%0A$device_details";
	} else if ($qrcode_action == "URL") {
		if ($server_fqdn == "") {
			echo "ERROR: ID10T: please set Server FQDN in settings page";
			exit(1);
		}
		$nextDeviceId = getNextKey();
		$cmd = "$server_fqdn" . "/qrcodes_search.php?device_id=" . $nextDeviceId;
	}
	$space_cmd = str_replace(' ','%20', $cmd);
	$newline_cmd = str_replace('\n','%0A',$space_cmd);
	$strLength = strlen($newline_cmd);
	if ($strLength > 4269) {
		echo "your length is $strLength, max lenght allowed is 4269";
		exit(1);
	}
	$final_cmd = "echo \"$newline_cmd\" | qrencode --foreground=$foreground_color --background=$background_color -o - | base64";
	$qr_result = shell_exec("$final_cmd 2>&1");
	echo "\n\n<br><br>\n";
} else {
	exit("device details not set");
}
echo "<link rel=\"stylesheet\" href=\"style.php\" media=\"screen\">\n";
echo "<html><body>\n";
echo "Device name: $device_name<br>\n";
echo "Device details: $device_details<br>\n";
try {
  $conn = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "INSERT INTO qrcodes (device_name, device_details, qrcode_action, qrcode) VALUES (\"$device_name\", \"$device_details\", \"$qrcode_action\", \"$qr_result\");";
  // use exec() because no results are returned
  $conn->exec($sql);
  echo "<br><br><br><h1>New record created successfully!</h1>";
  echo "\n<form action=\"qrcodes_search.php\" method=\"post\">";
  echo "<input type=\"hidden\" name=\"device_name\" value=\"\">\n<input type=\"hidden\" name=\"device_details\" value=\"\">\n";
  echo "<input type=\"hidden\" name=\"device_id\" value=\"$nextDeviceId\">";
  echo "\n<input type=\"submit\" class=\"button\" name=\"submit\" value=\"Click Here to see your new device record\"></input></form>";

} catch(PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
  exit(1);
}

$conn = null;
echo "</body></html>\n";
?>
