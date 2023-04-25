<?php
/*** set the content type header ***/
/*** Without this header, it wont work ***/
header("Content-type: text/css");


$font_family = 'Arial, Helvetica, sans-serif';
$font_size = '0.7em';
$border = '1px solid blue';
?>

table {
margin: 8px;
}

th {
font-family: <?=$font_family?>;
font-size: <?=$font_size?>;
background: #666;
color: #FFF;
padding: 2px 6px;
border-collapse: separate;
border: <?=$border?> #000;
}

td {
font-family: <?=$font_family?>;
font-size: <?=$font_size?>;
border: <?=$border?> #DDD;
}

.button {
  background-color: #4CAF50;
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
}
ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  overflow: hidden;
  #background-color: #333;
  background-color: #4CAF50;
  position: -webkit-sticky; /* Safari */
  position: sticky;
  top: 0;
}

li {
  float: left;
}

li a {
  display: block;
  color: white;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
}

li a:hover {
  background-color: #111;
}

.active {
  background-color: #4CAF50;
}

input[type=text], select {
  width: 10%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

input[type=submit] {
  width: 10%;
  background-color: #4CAF50;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

input[type=submit]:hover {
  background-color: #45a049;
}

.section {
  border-radius: 5px;
  background-color: grey;
  padding: 20px;
}

body {
background-color: white;
}

.FormElement {
    width: 300px;
    height: 300px;
    border-radius: 10px;
    box-shadow: 0 0 0 3px #000;
    border: 5px solid transparent;
}
.FormElement:focus {
    outline:none;
}
#floater {
    position: absolute;
    top: 100px;
    right: 1px;
    width: 100px;
    height: 100px;
    -webkit-transition: all 2s ease-in-out;
    transition: all 2s ease-in-out;
    z-index: 1;
    border-radius: 3px 0 0 3px;
    padding: 10px;
    #background-color: #41a6d9;
    color: white;
    text-align: center;
    box-sizing: border-box;
}
