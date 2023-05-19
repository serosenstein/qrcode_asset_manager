<!DOCTYPE HTML>
<html>  
	<title>QR Code Asset Inventory</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="style.php" media="screen">
	<link rel="icon" type="image/x-icon" href="favicon.ico">
	<head>
			<link rel="stylesheet" href="style.php" media="screen">
			<ul>
				  <li><a href="add_device.php">Add Device</a></li>
				    <li><a href="config.php">Settings</a></li>
				    <li><a href="qrcodes_tags.php">Tag Colors</a></li>
				    <li><a href="#" id="myBtn">Advanced Search</a></li>
				<div class="search-container">
				    <form action="qrcodes_search.php" method="post">
				      <input type="text" name="quick_search" placeholder="Quick Search..." name="search">
				      <button type="submit"><i class="fa fa-search"></i></button>
				    </form>
				  </div>
			</ul>

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

	<!-- The Modal -->
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
	</head>
	<body>
			<h2>Add a new device to DB and display QR codes<br>Both fields required</h2>
		<form action="qrcodes_add.php" method="post">
			Device Name<br><input type="text" name="device_name" placeholder="Device name" maxlength="255" required><br>
			<!--Device Details: <input type="text" name="device_details"><br>-->
			Device Details<br><textarea class="FormElement" name="device details" id="device_details" cols="100" rows="20" minlength="2" maxlength="255" onkeyup="textCounter(this,'counter',255);" placeholder="Device Details" required></textarea>
                        <br>Chracters remaining (out of 255):<input disabled maxlength="255" size="1" value="0" id="counter" style="color:white;">
			<br><br><br>QR Code Action:<br><br>
			URL: <input type="radio" name="qrcode_action" value="URL" required>
			<br><br>Email: <input type="radio" name="qrcode_action" value="email" required><br>
			<br><br>
			<?php
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
				        foreach ($templates as $tag => $template) {
						$is_default = $template["default"];
						$default_tag = $tag;
						$default_fg = $template["foreground"];
						$default_bg = $template["background"];
						if ($is_default) {
						echo "<label class=\"templatedisplay\" for=\"colors\"><strong>Choose a color profile</strong></label><br>";
						echo "<select name=\"colors\" id=\"colorchooser\">";
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


			?>
			<br>
			<input type="submit" class="button" value="Add new device">
		</form>
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

	</body>
</html>

