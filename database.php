
 <?php
 $servername = "127.0.0.1";
 $username = "root";
 $password = "";
 $dbname = "marvel1";
 $port = "3308";

 // Create connection
 $conn = new mysqli($servername.':'.$port, $username, $password, $dbname);
 
 // Check connection
 if ($conn->connect_error) {
     die("Connection failed: " . $conn->connect_error);
 }
//  echo "Connected successfully";
 ?> 