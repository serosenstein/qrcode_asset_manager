<?php
print <<< EOD1
<!DOCTYPE html>
	<html>
	<head>
	<link rel="stylesheet" href="style.php" media="screen">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<ul>
	  <li><a href="index.html">Home</a></li>
	    <li><a href="config.php">Settings</a></li>
	      <form action="qrcodes_search.php" method="post">
	        <input type="text" name="quick_search" placeholder="Quick Search..." >
		  <button type="submit"><i class="fa fa-search"></i></button>
	    </form>
	    </ul>
EOD1;
$allowedArray=["servername","username","dbname","port","email","foreground_color","background_color","password","foreground_color1","server_fqdn"];
if (!empty($_POST)) {
	$tempArray = [];
	foreach($_POST as $key => $value)
	{
		if(in_array($key,$allowedArray)) {
			#echo "$key and $value";
			if ($key == "background_color" || $key == "foreground_color") {
				$value = str_replace('#','', $value);
			}
			$tempArray[$key] = $value;
			if($value == "") {
				if ($key == "server_fqdn") {
					$serverName = $_SERVER['SERVER_NAME'];
					$serverPort = $_SERVER['SERVER_PORT'];
					$value = "http://$serverName:$serverPort";	
					$tempArray["server_fqdn"] = $value;
				} else {
					echo "$key is empty\n<br>";
					unset($_POST);
					echo "<button onClick=\"window.location.href='config.php';\">Go Back</button>\n";
					exit(1);
				}
			}
		}
		foreach ($allowedArray as $variableName) {
				if(!isset($variableName)) {
					echo "$variableName not set<br>";
					unset($_POST);
					echo "<button onClick=\"window.location.href='config.php';\">Go Back</button>\n";
					exit(1);
		}

	}
	}
	
	file_put_contents('vars.json',json_encode($tempArray, JSON_PRETTY_PRINT));
	echo "<h2>Settings saved succesfully!</h2>";


	#print_r($tempArray);
}
if(!is_file('vars.json')) {
	echo "Creating empty file";
	$variableFile = fopen('vars.json',"w") or die("Unable to open file");
	fwrite($variableFile, "{}\n");
	fclose($variableFile);
}
$str = file_get_contents('vars.json');
$json = json_decode($str, true);
foreach ($json as $field => $value) {
$$field = $value;
}
print <<< EOD
</style>
</head>
  
<body>
<center>
<form method="post">
    <h1>Servername: <input name="servername" id="servername" value="$servername" required>
    <br>DB App user: <input name="username" id="username" value="$username" required>
    <br>DB App password: <input type="password" name="password" id="password" value="$password" style="-webkit-text-security: circle" required />
    <br>Database name: <input name="dbname" id="dbname" value="$dbname" required>
    <br>Database port: <input name="port" id="port" value="$port" required>
    <br>Email address: <input name="email" id="email" value="$email" required>
    <br>Server FQDN: <input name="server_fqdn" id="server_fqdn" value="$server_fqdn" >
    <br>Foreground color: <input name="foreground_color" type="color" value="#$foreground_color"/ required>
    <br>Background color: <input name="background_color" type="color" value="#$background_color"/ required>
<br>
  <input type="submit" name="submit" value="Update settings"/>
</form>
</center>
</h1>

</body>
</html>
EOD;
?>
