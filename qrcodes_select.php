<?php
print <<< EOD
<!DOCTYPE html>
	<html>
	<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="icon" type="image/x-icon" href="favicon.ico">
<script src="floater.js"></script>
	<link rel="stylesheet" href="style.php" media="screen">
	        <script>
        function textCounter(field,field2,maxlimit)
                {
                 var countfield = document.getElementById(field2);
                 if ( field.value.length > maxlimit ) {
                  field.value = field.value.substring( 0, maxlimit );
                  return false;
                 } else {
                  countfield.value = maxlimit - field.value.length;
                 }
                }
        </script>
	<ul>
	  <li><a href="add_device.php">Add Device</a></li>
	    <li><a href="config.php">Settings</a></li>
	    <li><a href="qrcodes_tags.php">Tag Colors</a></li>
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
if(isset($_POST["device_id"])) {
	$device_id = $_POST["device_id"];
	$device_array = $_POST["device_id"];
} else if (isset($_GET["device_id"])) {
	$device_id = $_GET["device_id"];
	$device_array = $_GET["device_id"];
} else {
	echo "No device ID provided";
	exit(1);
}
if (is_array($device_array)) {
	$device_array_length = sizeof($device_array);
} else if (isset($device_array)){ 
	$device_array_length = "1";
} else {
		echo "<br><h1>ERROR: you didn't select a radio button to update or delete</h1>\n";
		exit(1);
}
if (isset($_POST['update_button']) || isset($_GET['update_button'])) {
   echo "<h1>Update Menu</h1>\n";
	if ($device_array_length == "0") {
		echo "<br><h1>ERROR: you didn't select a radio button to update</h1>\n";
		exit(1);
	}
   $action = "update";
} else if (isset($_POST['delete_button'])) {
   echo "</h1>Delete Menu</h1>\n";
	if ($device_array_length == "0") {
		echo "<br><h1>ERROR: you didn't select a radio button to delete</h1>\n";
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
                $sql_qrcode_action = $row["qrcode_action"];
	}
  if (isset($sql_qrcode_action)) {
	  if ($sql_qrcode_action == "URL") {
		$url_checked = "checked";
	  } else if ($sql_qrcode_action == "email") {
		  $email_checked = "checked";	
	  }
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
			echo "<br>Device Name: <input type=\"text\" name=\"device_name\" maxlength=\"255\" value=$sql_device_name><br>\n";
			echo "Device Details<br><textarea class=\"FormElement\" name=\"device details\" id=\"device_details\" cols=\"100\" rows=\"20\" minlength=\"2\" maxlength=\"255\" onkeyup=\"textCounter(this,'counter',255);\" placeholder=\"Device Details\">$sql_device_details</textarea><br>Chracters remaining (out of 255):<input disabled maxlength=\"255\" size=\"1\" value=\"0\" id=\"counter\" style=\"color:white;\">";
                        echo "<br><br><br>QR Code Action:<br><br>URL: <input type=\"radio\" name=\"qrcode_action\" value=\"URL\" required $url_checked>";
	                echo "<br><br>Email: <input type=\"radio\" name=\"qrcode_action\" value=\"email\" required $email_checked><br><br>";
                        $file_path = "color-templates.json";

                        // Load existing templates
                        $templates = [];
                        if (file_exists($file_path)) {
                                    $templates = json_decode(file_get_contents($file_path), true);
                        }
                        if (empty($templates)) {
                                   echo "<p>No color profiles exist yet, but you can <a href=\"qrcodes_tags.php\">create one here</a> </p>";
                        } else
                        {
			echo "<label class=\"templatedisplay\" for=\"colors\"><strong>Choose a color profile</strong></label><br>";
			echo "<select name=\"colors\" id=\"colorchooser\">";
                                        foreach ($templates as $tag => $template) {
                                                $is_default = $template["default"];
                                                $default_tag = $tag;
                                                $default_fg = $template["foreground"];
                                                $default_bg = $template["background"];
                                                if ($is_default) {
                                                echo "<option value=$tag|$default_fg|$default_bg foreground=\"$default_fg\" background=\"$default_bg\">$tag (default)</option>\n";
                                                }
                                                echo "<br>            <br>\n";


                                                                }
                                        foreach ($templates as $tag => $template) {
                                                $is_default = $template["default"];
                                                $fg = $template["foreground"];
                                                $bg = $template["background"];
                                                if (!$is_default) {
                                                echo "<option value=$tag|$fg|$bg foreground=\"$fg\" background=\"$bg\">$tag</option>\n";
                                                }
                                                echo "<br><br>\n";


                                                                }
                                        echo "</select>";
                        }

		} else {
			echo "not sure how you got here";
			exit(1);
		}

		if ($action == "delete") {
                	echo "<br><br><button type=\"submit\" class=\"button\" action=\"qrcode_delete.php\">Yes, I'm sure I want to delete this record!</button>\n";
		} else if ($action = "update") {
                	echo "<br><button type=\"submit\" class=\"button\" action=\"qrcode_update\">Update!</button>\n";
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
$conn = null;
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
