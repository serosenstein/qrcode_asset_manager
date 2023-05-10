<?php
$str = file_get_contents('vars.json');
$json = json_decode($str, true);
foreach ($json as $field => $value) {
	$$field = $value;
}

session_start();
#see if we already have session variables
if (sizeof($_SESSION) > 0) {
	$device_id = $_SESSION['device_id'];
	$device_name = $_SESSION['device_name'];
	$device_details = $_SESSION['device_details'];
	$quick_search = $_SESSION['quick_search'];
	$page = $_SESSION['page'];
	$rowsPerPage = $_SESSION['rowsPerPage'];
	$total_count = $_SESSION['total_count'];
	$offset = $_SESSION['offset'];
	$lastpage = $_SESSION['lastpage'];
	$firstpage = $_SESSION['firstpage'];
	$nextpage = $_SESSION['nextpage'];
	$previouspage = $_SESSION['previouspage'];
	$rowsPerPage = $_SESSION['rowsPerPage'];
}
if(isset($_POST['clear_search'])){
	session_unset();
	session_start();
	$_POST['device_id'] = "";
	$_POST['device_name'] = "";
	$_POST['device_details'] = "";
	$_POST['quick_search'] = "";
}

if(isset($_GET['rowsPerPage']) && $_GET['rowsPerPage'] != ""){
	$rowsPerPage = $_GET['rowsPerPage'];
} else if(isset($_POST['rowsPerPage']) && $_POST['rowsPerPage'] != ""){
	$rowsPerPage = $_POST['rowsPerPage'];
} else {
	if($defaultrowsPerPage != ""){
		$rowsPerPage = $defaultrowsPerPage;
	} else {
		//default setting if not set in config.php
		$rowsPerPage = "10";
	}
} 
if (isset($_POST["page"])) {
	$page = $_POST["page"];
	$_SESSION['page'] = $page;
}
if (isset($_POST["rowsPerPage"])) {
	$rowsPerPage = $_POST["rowsPerPage"];
	$_SESSION['rowsPerPage'] = $rowsPerPage;

}
if (isset($_POST["total_count"])) {
	$total_count = $_POST["total_count"];
	$_SESSION['total_count'] = $total_count;
}
if (isset($_POST["offset"])) {
	$offset = $_POST["offset"];
	$_SESSION['offset'] = $offset;
}
if (isset($_POST["lastpage"])) {
	$lastpage = $_POST["lastpage"];

}
if (isset($_POST["firstpage"])) {
	$firstpage = $_POST["firstpage"];

}
if (isset($_POST["nextpage"])) {
	$nextpage = $_POST["nextpage"];
}
if (isset($_POST["previouspage"])) {
	$previouspage = $_POST["previouspage"];
	$_SESSION['previouspage'] = $previouspage;
}
if (isset($_POST["device_id"])) {
	$device_id = $_POST["device_id"];
	$_SESSION['device_id'] = $device_id;
}
if (isset($_POST["device_name"])) {
	$device_name = $_POST["device_name"];
	$_SESSION['device_name'] = $device_name;
}
if (isset($_POST["device_details"])) {
	$device_details = $_POST["device_details"];
	$_SESSION['device_details'] = $device_details;
}
if (isset($_POST["quick_search"])) {
	$quick_search = $_POST["quick_search"];
	$_SESSION['quick_search'] = $quick_search;
}
if (isset($_GET["device_id"])) {
	$device_id = $_GET["device_id"];
	$_SESSION['device_id'] = $device_id;
}

$_SESSION['device_id'] = $device_id;
$_SESSION['device_name'] = $device_name;
$_SESSION['device_details'] = $device_details;
$_SESSION['quick_search'] = $quick_search;
$_SESSION['page'] = $page;
$_SESSION['rowsPerPage'] = $rowsPerPage;
$_SESSION['total_count'] = $total_count;
$_SESSION['offset'] = $offset;
$_SESSION['lastpage'] = $lastpage;
$_SESSION['firstpage'] = $firstpage;
$_SESSION['nextpage'] = $nextpage;
$_SESSION['previouspage'] = $previouspage;



print <<< EOD
<!DOCTYPE html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="icon" type="image/x-icon" href="favicon.ico">
<script src="floater.js"></script>
<link rel="stylesheet" href="style.php" media="screen">
<ul>
  <li><a href="index.php">Home</a></li>
  <li><a href="config.php">Settings</a></li>
  <li><a href="qrcodes_tags.php">Tag Colors</a></li>
  <li><a href="#" id="myBtn">Advanced Search</a></li>
  <form action="qrcodes_search.php" method="post">
  <input type="text" name="quick_search" placeholder="Quick Search..." name="search">
  <button type="submit"><i class="fa fa-search"></i></button>
														                                      </form>

</ul>
<center>
EOD;
$c = "1";
$CLAUSE = "select * from qrcodes where ";
$COUNT_CLAUSE = "select count(*) from qrcodes where ";
#if (isset($_POST["quick_search"]))
if (isset($_POST["quick_search"]) || isset($_SESSION["quick_search"]) && ( !isset($_POST["device_details"]) || !isset($_POST["device_id"]) && !isset($_POST["device_name"])))
{
	if (isset($_POST["quick_search"])){
		$quick_search = $_POST["quick_search"];
	} else if (isset($_SESSION["quick_search"])){
		$quick_search = $_SESSION["quick_search"];
	}
	if ($quick_search != "" ){
		$CLAUSE .= "(device_details LIKE '%$quick_search%') OR (device_name LIKE '%$quick_search%')";
		$COUNT_CLAUSE .= "(device_details LIKE '%$quick_search%') OR (device_name LIKE '%$quick_search%')";
	}
} else {
	$quick_search = "";
}
if (isset($_POST["device_id"]) && $_POST["device_id"] != "")
{
	$device_id = $_POST["device_id"];
	if ($device_id != "" ){
		$CLAUSE .= "device_id = '$device_id' AND ";
		$COUNT_CLAUSE .= "device_id = '$device_id' AND ";
	}
}
if (isset($_GET["device_id"]) && !isset($_POST["device_id"]))
{
	$device_id = $_GET["device_id"];
	if ($device_id != "" ){
		$CLAUSE .= "device_id = '$device_id'";
		$COUNT_CLAUSE .= "device_id = '$device_id'";
	}
}
if (!isset($_GET["device_id"]) && !isset($_POST["device_id"])) {
	$device_id = "";
}
if (isset($_POST["device_name"]) && $_POST["device_name"] != "")
{
	$device_name = $_POST["device_name"];
	$device_name = preg_replace('/[^a-zA-Z0-9-_\.]/','', $device_name);
	$CLAUSE .= " device_name like '%$device_name%' ";
	$COUNT_CLAUSE .= " device_name like '%$device_name%' ";
	

} else {
	$device_name = "";
}
if (isset($_POST["device_details"]) && $_POST["device_details"] != "")
{
	$device_details= $_POST["device_details"];
	global $device_details;
	$CLAUSE .= " AND device_details like '%$device_details%' ";
	$COUNT_CLAUSE .= " AND device_details like '%$device_details%' ";
} else {
	$device_details = "";
}
if ( $device_name == "" && $device_id == "" && $device_details == "" && $quick_search == "" && $quick_search == "") {
	echo "Display search results for all";
	$CLAUSE = "select * from qrcodes";
	$COUNT_CLAUSE = "select * from qrcodes";
} else {
	if ($device_name == "") {
		#$device_name = "N/A";
		$_SESSION['device_name'] = "";
	}
	if ( $device_id == "" ) {
		#$device_id = "N/A";
		$_SESSION['device_id'] = "";
	}
	if ( $quick_search == "" ) {
		#$quick_search = "N/A";
		$_SESSION['quick_search'] = "";
	}
	if ( $device_details == "" || !isset($device_details)) {
		#$device_details = "N/A";
		$_SESSION['device_details'] = "";
	}
	if($device_id != "") {
		$SEARCH_CRITERIA .= "Device ID: <strong>$device_id</strong><br>\n";
	}
	if($device_name != "") {
		$SEARCH_CRITERIA .= "Device Name: <strong>$device_name</strong><br>\n";
	}
	if($device_details != "") {
		$SEARCH_CRITERIA .= "Device Details: <strong>$device_details</strong><br>\n";
	}
	if($quick_search != "") {
		$SEARCH_CRITERIA .= "Quick Search (wildcard): <strong>$quick_search</strong><br>\n";
	}

	echo "Chosen search critera for this search:<br>\n";
	echo $SEARCH_CRITERIA;
}
//button to clear all search criteria
echo "<form action='qrcodes_search.php' style=\"width:40%\" method='post'>\n";
echo "<input type='submit' value='Clear Search' name='clear_search'>\n";
echo "</form>\n";
$CLAUSE .= " order by device_id asc";
$COUNT_CLAUSE .= " order by device_id asc";
echo "<html>\n";
echo "<link rel=\"stylesheet\" href=\"style.php\" media=\"screen\">\n";
echo "<div id=\"floater\"><a href=\"#bottom\"><img src=\"arrow_down.png\"></img></a></div>\n";
echo "<body><title>QR Code Asset Search</title>";
if (isset($_GET['page'])) {
	$page = $_GET['page'];
} else {
$page = "1";
}
try {
  $conn = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //create a way for user to select $rowsPerPage from a drop down on this page

$result = $conn->query($CLAUSE);
  if ($result->rowCount() > 0) {
	$total_count = $result->rowCount();
	echo "<br><strong>Total results: $total_count</strong><br>";
	echo "<strong>Results per page: </strong><form action=\"qrcodes_search.php\" style=\"width:30%;\" method=\"post\">";
	echo "<select id=\"rowsPerPage\" name=\"rowsPerPage\" onchange=\"this.form.submit()\">";
	echo "<option value=\"$rowsPerPage\">$rowsPerPage (current)</option>";
	echo "<option value=\"5\">5</option>";
	echo "<option value=\"10\">10</option>";
	echo "<option value=\"25\">25</option>";
	echo "<option value=\"50\">50</option>";
	echo "<option value=\"100\">100</option>";
	echo "<input type=\"hidden\" name=\"device_id\" value=\"$device_id\">";
	echo "<input type=\"hidden\" name=\"device_name\" value=\"$device_name\">";
	echo "<input type=\"hidden\" name=\"device_details\" value=\"$device_details\">";
	echo "<input type=\"hidden\" name=\"quick_search\" value=\"$quick_search\">";
	echo "</select>";
	echo "</form>";
	echo "<br>";




	$offset = ($page - 1) * $rowsPerPage;
	$lastpage = ceil($total_count/$rowsPerPage);
	$firstpage = 1;
	$nextpage = $page + 1;
	$previouspage = $page - 1;
	$second_last = $lastpage - 1;
	$limit = " limit $offset, $rowsPerPage";
	$number_of_rows = $result->rowCount();
        $totalPages = ceil($result->rowCount() / $rowsPerPage);
	$CLAUSE .= $limit;
	$result = $conn->query($CLAUSE);
            if ($totalPages > 1) {
                echo "<div id=\"pagination\">";
                for ($i = 1; $i <= $totalPages; $i++) {
                    if ($i == $page) {
		        
                        #echo "Current Page: <a href=\"qrcodes_search.php?page=$i\"><b>$i</b></a>";
                        echo "Current Page: <strong>$i</strong>";
                        echo "<div class=\"pagination\">";
			if ($page != $firstpage) {
			    echo "<a href=\"qrcodes_search.php?page=$firstpage\"><<</a> ";
			    echo "<a href=\"qrcodes_search.php?page=$previouspage\">Previous</a> ";
			    echo "<a href=\"qrcodes_search.php?page=$totalPages\">>></a> ";
			    echo "<br>";
			}
			
                        for ($i = 1; $i <= $totalPages; $i++) {
                            echo "<a href='qrcodes_search.php?page=" .
                                $i . "&rowsPerPage=" . $rowsPerPage .
                                "'>" .
                                $i .
                                "</a> ";
                        }
                        echo "</div>";
                    }
                }
            }
	  echo "<form method=\"post\" id=\"SubmitForm\">\n";
	  echo "<table style=\"width:100%\"><tr><th>Device ID</th><th>Device Name</th><th>Device Details</th><th>QR code (click to regenerate)</th><th><input type=\"checkbox\" id=\"all\" checked> Generate Label</th><th>QR Action</th><th>Delete/Edit Device</th></tr>\n";
          while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $new_device_id = $row["device_id"];
                $new_device_name = $row["device_name"];
                $new_device_details = $row["device_details"];
                $new_qrcode_action = $row["qrcode_action"];
                $new_device_qrcode = $row["qrcode"];
		echo '<tr'.(($c = !$c)?' bgcolor=grey':'').">";
		echo "<td><center>\n$new_device_id</center></td>";
		echo "<td><center>\n$new_device_name</center></td>";
		echo "<td><center>\n" . nl2br($new_device_details) . "</center></td>";
                echo '<td><center><a href="qrcodes_regenerate.php?device_id='.$new_device_id.'"><img class="effectfront" src="data:image/png;base64,'.$new_device_qrcode .'" /></a></center></td>';
		echo "<td><center><input type=\"checkbox\" class=\"chk_boxes1\" name=print_device_id[] value=\"$new_device_id\" checked> Generate Label</center></td>";
		echo "<td><center>\n$new_qrcode_action</center></td>";
		echo "<td><center><input type=\"radio\" name=device_id[] value=\"$new_device_id\"> Delete/Edit</center></td>";
		echo "</tr>";
		echo '</div>';
  }
	  	echo "</table>\n";
		echo "<input type=\"submit\" class=\"button\" name=\"update_button\" formaction=\"qrcodes_select.php\" value=\"Update\" />";
		echo "   <input type=\"submit\" class=\"button\" name=\"delete_button\" formaction=\"qrcodes_select.php\" value=\"Delete\" />\n<br><br>";
            if ($totalPages > 1) {
                echo "<div id=\"pagination\">";
                for ($i = 1; $i <= $totalPages; $i++) {
                    if ($i == $page) {
		        
                        #echo "Current Page: <a href=\"qrcodes_search.php?page=$i\"><b>$i</b></a>";
                        echo "Current Page: <strong>$i</strong>";
                        echo "<div class=\"pagination\">";
			if ($page != $firstpage) {
			    echo "<a href=\"qrcodes_search.php?page=$firstpage\"><<</a> ";
			    echo "<a href=\"qrcodes_search.php?page=$previouspage\">Previous</a> ";
			    echo "<a href=\"qrcodes_search.php?page=$totalPages\">>></a> ";
			    echo "<br>";
			}
			
                        for ($i = 1; $i <= $totalPages; $i++) {
                            echo "<a href='qrcodes_search.php?page=" .
                                $i . "&rowsPerPage=" . $rowsPerPage .
                                "'>" .
                                $i .
                                "</a> ";
                        }
                        echo "</div>";
                    }
                }
            }

  } else {
	echo "<h1>No matches found for your search, please try again</h1>";
  }
} catch(PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
}
print <<<EOD
		<div class="section" id="labelmenu" style="display:block">
		<!--<input type="checkbox" id="all">Select all-->
		<script src="toggle.js"></script>
		<h3>If no "Generate Label" boxes are selected, all labels will be printed</h3>
		Template Name<br><select class="template" name="template">
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
		Number of blank placeholders you want to have at beginning of labels (left to right, top to bottom), Default is 0<br><input type="text" name="skip_number" placeholder="Optional, e.g. 4" value=""><br>
		<input type="submit" name="print" class="button" formaction="print_label.php" value="Generate Labels" />
		</form></div><div id="bottom"></div>
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
EOD;
$conn = null;
echo "</center></body></html>\n";
?>
