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
if (isset($_POST["device_details"]))
{
	$device_details= $_POST["device_details"];
	if ($device_details == "") {
		echo "ERROR: ID10T: device details not!\n";
		exit(1);
	}
	$fileName = "$filePath/$device_name.png";
	$mailto = "\"mailto:$email?subject=INFO%20$device_name&body=";
	$cmd = "$mailto%20$device_name%0A$device_details";
	$space_cmd = str_replace(' ','%20', $cmd);
	$newline_cmd = str_replace('\n','%0A',$space_cmd);
	$strLength = strlen($newline_cmd);
	if ($strLength > 4269) {
		echo "your length is $strLength, max lenght allowed is 4269";
		exit(1);
	}
	$final_cmd = "echo $newline_cmd\" | qrencode --foreground=$foreground_color --background=$background_color -o - | base64";
	$qr_result = shell_exec("$final_cmd 2>&1");
	echo "\n\n<br><br>\n";
} else {
	exit("device details not set");
}
echo "<link rel=\"stylesheet\" href=\"style.php\" media=\"screen\">\n";
echo "<html><body>\n";
echo "Device name: $device_name\n";
echo "Device details: $device_details\n";
try {
  $conn = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "INSERT INTO qrcodes (device_name, device_details, qrcode) VALUES (\"$device_name\", \"$device_details\", \"$qr_result\");";
  echo "$sql";

  // use exec() because no results are returned
  $conn->exec($sql);
  echo "New record created successfully";
} catch(PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
  exit(1);
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
