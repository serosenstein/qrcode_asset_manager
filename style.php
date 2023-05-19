<?php
/*** set the content type header ***/

/*** Without this header, it wont work ***/
header("Content-type: text/css");


$font_family = 'Arial, Helvetica, sans-serif';
$font_size = '0.7em';
$border = '1px solid #FFFFFF';
?>
th {
font-family: <?=$font_family?>;
font-size: <?=$font_size?>;
background: #666;
color: #FFF;
padding: 2px 6px;
border-collapse: separate;
border-color: white;
border: white;
}

td {
font-family: <?=$font_family?>;
font-size: <?=$font_size?>;
border: <?=$border?> #FFFFFF;
border-color: #FFFFFF;
border: white;
}

.button1 {
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
  border-radius: 4px;
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
.templatedisplay {
all: revert;
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

input[type=text],input[type=number], select {
  width: 30%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}
.spacer {
  width: 50%;
  margin-top: -10px;
}
input[type=submit] {
  width: 50%;
  background-color: #4CAF50;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}
input[type=submit]#smallbutton::before {
  background: "favicon.ico";
  display: inline-block;
  font-color: black;
}
input[type=submit]#smallbutton {
  background-color: #4CAF50;
  content: "X";
  color: white;
  padding: 0px 0px;
  margin: 1px 0;
  font-size: 12px;
  height: 20px;
  border: none;
  border-radius: 4px;
  clear: left;
  cursor: pointer;
  float: left;
  #width: 100px; // only for IE8
  min-width: 100px;
  width: 200px;
  max-width: 100%;
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
background-color: black;
text-align:center;
color: white;
//overflow: auto;
position: relative;

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
#pagenumbers {
/*position the whole div to be in the center of the rendered page*/
text-align: center;
display: block;
clear: both;
margin-bottom: 10px;
bottom: 10;

}
#pages {
/*position in center*/
text-align: center;
display: block;
width: 100%;
margin-bottom: 10px;
bottom: 10;
clear: right;
//position: absolute;
height: 100%;
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
#wrapper {
    margin:auto;
    width:100%;
    overflow:auto;
    height:auto;
    text-align:center;
}
 
#float-left {
    float:left;
    width:70%;
}
 
#float-right {
    float:right;
    width:70%;
}
#float-center{
    float:center;
    width:70%;
}
table, th, td {
  border: 1px solid white;
}
* {box-sizing: border-box;}

body {
  margin: 0;
  font-family: Arial, Helvetica, sans-serif;
}

.topnav {
  overflow: hidden;
  background-color: #e9e9e9;
}

.topnav a {
  float: left;
  display: block;
  color: black;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
}

.topnav a:hover {
  background-color: #ddd;
  color: black;
}

.topnav a.active {
  background-color: #2196F3;
  color: white;
}

.topnav .search-container {
  float: right;
}

.topnav input[type=text] {
  padding: 6px;
  margin-top: 8px;
  font-size: 17px;
  border: none;
}

.topnav .search-container button {
  float: right;
  padding: 6px 10px;
  margin-top: 8px;
  margin-right: 16px;
  background: #ddd;
  font-size: 17px;
  border: none;
  cursor: pointer;
}

.topnav .search-container button:hover {
  background: #ccc;
}

@media screen and (max-width: 600px) {
  .topnav .search-container {
    float: none;
  }
  .topnav a, .topnav input[type=text], .topnav .search-container button {
    float: none;
    display: block;
    text-align: left;
    width: 100%;
    margin: 0;
    padding: 14px;
  }
  .topnav input[type=text] {
    border: 1px solid #ccc;  
  }
}
input[type="password"] {
width: 30%;
padding: 12px 20px;
margin: 8px 0;
display: inline-block;
border: 1px solid #ccc;
border-radius: 4px;
box-sizing: border-box;
}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 0px;
  /*border: 1px solid #888;*/
  width: 80%;
  border-radius: 5px;
  background-color: grey;
}

/* The Close Button */
.close {
  color: #FFF;
  /*color: #aaaaaa;*/
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  /*color: #000;*/
  color: #FFF;
  text-decoration: none;
  cursor: pointer;
}
.effectfront {
  border: none;
  margin: 0 auto;
}
.effectfront:hover {
  -webkit-transform: scale(2.2);
  -moz-transform: scale(2.2);
  -o-transform: scale(2.2);
  transform: scale(2.2);
  transition: all 0.3s;
  -webkit-transition: all 0.3s;
  cursor: none;
}
img.effectfront {
  border: none;
  margin: 0 auto;
}
img.effectfront:hover {
  -webkit-transform: scale(2.2);
  -moz-transform: scale(2.2);
  -o-transform: scale(2.2);
  transform: scale(2.2);
  transition: all 0.3s;
  -webkit-transition: all 0.3s;
  cursor: none;
}
/* The Close Button */
.close1 {
  color: #FFF;
  /*color: #aaaaaa;*/
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close1:hover,
.close1:focus {
  /*color: #000;*/
  color: #FFF;
  text-decoration: none;
  cursor: pointer;
}

form#sidebyside {
 clear: right;
 float: left;
 /* with some space to the left of the second form */
/* margin-right: 20px; */
margin-left: -5%;
margin-right: -5%;
 width: 20%;
 /*width: 100px; // only for IE8*/
 max-width: 100%;
}
form#sidebyside1 {
 float: right;
 /* with some space to the left of the second form */
margin-left: -10%;
width: 20%;
}
form#rowsPerPage {
float: right;
#width: 15%;
}
form#smallbutton{
float: left;
clear: right;
margin-left: 5px;
/*width: 25%;*/
/*width: 100px; // only for IE8*/
margin-top: 7px;
max-width: 100%;
background: "favicon.ico";
}
.search_results {
margin-right: 40%;
#make it align to center of page
}

.tag-remove::before {
  content: 'x'; // here is your X(cross) sign.
  color: #fff;
  font-weight: 300;
  font-family: Arial, sans-serif;
}
.input-holder {
  /* This bit sets up the horizontal layout */
  display:flex;
  flex-direction:row;
  
  /* This bit draws the box around it */
  border:1px solid grey;

  /* I've used padding so you can see the edges of the elements. */
  padding:1px;
}
.input-holder-inner {
  /* Tell the input to use all the available space */
  flex-grow:2;
  /* And hide the input's outline, so the form looks like the outline */
  border:none;
}
.input-holder-inner:focus {
  outline: none;
}
