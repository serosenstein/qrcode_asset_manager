<?php
$allowedArray=["servername","username","dbname","port","email","foreground_color","background_color","password","foreground_color1"];
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
				echo "$key is empty\n<br>";
				unset($_POST);
				echo "<button onClick=\"window.location.href='config.php';\">Go Back</button>\n";
				exit(1);
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
<!DOCTYPE html>
<html>
<head>
    <style>
        * {
            font-family: Arial;
            margin: 2px;
            padding: 10px;
            text-align: center;
            position: flex;
        }
  
        body {
            margin-top: 10%;
        }
    </style>
</head>
  
<body>
<form method="post">
    <h1>Servername: <input name="servername" id="servername" value="$servername" required>
    <br>DB App user: <input name="username" id="username" value="$username" required>
    <br>DB App password: <input type="password" name="password" id="password" value="$password" style="-webkit-text-security: circle" required />
    <br>Database name: <input name="dbname" id="dbname" value="$dbname" required>
    <br>Database port: <input name="port" id="port" value="$port" required>
    <br>Email address: <input name="email" id="email" value="$email" required>
    <br>Foreground color: <input name="foreground_color" type="color" value="#$foreground_color"/ required>
    <br>Background color: <input name="background_color" type="color" value="#$background_color"/ required>
<br>
  <input type="submit" name="submit"/ value="Update settings">
</form>
</body>
</html>
EOD;
?>
