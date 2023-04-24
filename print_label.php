<?php
require('print_lib.php');
$str = file_get_contents('vars.json');
$json = json_decode($str, true);
foreach ($json as $field => $value) {
	$$field = $value;
}
$template = $_POST["template"];
$device_array = $_POST["print_device_id"];
if (is_array($device_array)) {
	$device_array_length = sizeof($device_array);
	$device_commas="";
	for($i=0;$i<count($device_array);$i++){
		$device_commas .= "'$device_array[$i]',";
	     }
	$id_clause = rtrim($device_commas, ',');
	$id_clause = "($id_clause);";
	$CLAUSE = "select * from qrcodes where device_id in $id_clause";
} else {
	$CLAUSE = "select * from qrcodes;";
}

$template_array = array("5160","5161","5162","5163","5164","8600","L7163","3422","22805","94103");
if (!in_array($template, $template_array)) {
	echo " invalid template $template";
	exit(1);
} 
if (isset($_POST["skip_number"])) {
	$skip_number = $_POST["skip_number"];
}
$justify = "L";
if("$template" == "22805" || "$template" == "94103" ) {
	$justify = "C";
}
/*------------------------------------------------
To create the object, 2 possibilities:
either pass a custom format via an array
or use a built-in AVERY name
------------------------------------------------*/

// Example of custom format
// $pdf = new PDF_Label(array('paper-size'=>'A4', 'metric'=>'mm', 'marginLeft'=>1, 'marginTop'=>1, 'NX'=>2, 'NY'=>7, 'SpaceX'=>0, 'SpaceY'=>0, 'width'=>99, 'height'=>38, 'font-size'=>14));

// Standard format
$pdf = new PDF_Label($template);
$skip_counter = 0;

$pdf->AddPage();
try {
  $conn = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // use exec() because no results are returned
  $result = $conn->query($CLAUSE);
  if ($result->rowCount() > 0) {
	  if ($skip_number) {
		while($skip_counter < $skip_number) {
			$pdf->Add_Label_Pic(""," ","$justify");
			$skip_counter++;
		}
		};
          while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $new_device_name = $row["device_name"];
                $new_device_qrcode = $row["qrcode"];
    		$text = "$base_64_image";;
    		$pdf->Add_Label_Pic("$new_device_qrcode","$new_device_name","$justify");
	}
		}
} catch(PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
}

$pdf->Output();
?>
