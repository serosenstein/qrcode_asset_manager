<?php
print <<< EOD
<!DOCTYPE html>
	<html>
	<head>
	<link rel="stylesheet" href="style.php" media="screen">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="icon" type="image/x-icon" href="favicon.ico">
	<ul>
	  <li><a href="index.html">Home</a></li>
	    <li><a href="config.php">Settings</a></li>
		<li><a href="#" id="myBtn">Advanced Search</a></li>
	      <form action="qrcodes_search.php" method="post">
	        <input type="text" name="quick_search" placeholder="Quick Search..." name="search">
		  <button type="submit"><i class="fa fa-search"></i></button>
		    </form>
		    </ul>
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
} else if (isset($_GET["device_id"])) {
	$device_id = $_GET["device_id"];
} else {
	echo "You didn't set a device_id</body></html>";
}
if ($device_id != "" ) {
$CLAUSE = "select * from $dbname where device_id = $device_id;";
try {
  $conn = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // use exec() because no results are returned
  $result = $conn->query($CLAUSE);
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $sql_device_id = $row["device_id"];
                $device_name = $row["device_name"];
                $device_details = $row["device_details"];
                $qrcode_action = $row["qrcode_action"];
		global $device_name;
		global $device_details;
		global $qrcode_action;
        }
  if (isset($qrcode_action)) {
          if ($qrcode_action == "URL") {
                $url_checked = "checked";
          } else if ($qrcode_action == "email") {
                  $email_checked = "checked";
          }
  }
  if ($result->rowCount() > 0) {
	echo "regenerating QR code";
  } else {
        echo "<h1>No matches found for your search, please try again</h1>";
  }
} catch(PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
}

{
	        if ($qrcode_action == "email") {
			if ($email == "") {
				echo "ERROR: ID10T: please set email in settings page";
				exit(1);
			}
                	$mailto = "mailto:$email?subject=INFO%20$device_name&body=";
                	$cmd = "$mailto%20$device_name%0A$device_details";
        	} else if ($qrcode_action == "URL") {
			if ($server_fqdn == "") {
				echo "ERROR: ID10T: please set Server FQDN in settings page";
				exit(1);
			}
	                $cmd = "$server_fqdn" . "/qrcodes_search.php?device_id=" . $device_id;
		}
        $space_cmd = str_replace(' ','%20', $cmd);
        $newline_cmd = str_replace('\n','%0A',$space_cmd);
        $final_cmd = "echo \"$newline_cmd\" | qrencode --foreground=$foreground_color --background=$background_color -o - | base64";
        $qr_result = shell_exec("$final_cmd 2>&1");
        echo "\n\n<br><br>\n";
}

$CLAUSE = "update qrcodes set device_name = \"$device_name\", device_details=\"$device_details\",qrcode=\"$qr_result\",qrcode_action=\"$qrcode_action\" where device_id  = \"$device_id\"";
echo "<html><body><title>QR Code Asset Delete</title>";
try {
  $conn = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // use exec() because no results are returned
  $result = $conn->query($CLAUSE);
  echo "<h2>Record updated successfully!</h2>\n<br>";
    echo "\n<form action=\"qrcodes_search.php\" method=\"post\">";
    echo "<input type=\"hidden\" name=\"device_name\" value=\"\">\n<input type=\"hidden\" name=\"device_details\" value=\"\">\n";
      echo "<input type=\"hidden\" name=\"device_id\" value=\"$device_id\">";
      echo "\n<input type=\"submit\" class=\"button\" name=\"submit\" value=\"Click Here to see your updated device record\"></input></form>";

} catch(PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
}
$conn = null;
}
print <<< EOM1
        <div id="myModal" class="modal">

                  <!-- Modal content -->
                  <div class="modal-content">
                                <span class="close">&times;</span>
                <div class="section" >
                        <h2>Advanced Search<br>(empty values will return all)</h2>
                <form action="qrcodes_search.php" method="post">
                        Device Name (partial match)<br><input type="text" name="device_name"><br>
                        Device Details (partial match)<br><input type="text" name="device_details"><br>
                        Device ID (exact match)<br><input type="text" name="device_id"><br>
                        <br>
                        <input type="submit" class="button" value="Search">
                        <br><br><br>
                </form>
                </div>
                                      </div>

        </div>
        <script>
                // Get the modal
                 var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal
btn.onclick = function() {
          modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
          modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
          if (event.target == modal) {
                      modal.style.display = "none";
                    }
}
        </script>
EOM1;
echo "</body></html>\n";
?>
