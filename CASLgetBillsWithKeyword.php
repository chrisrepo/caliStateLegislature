<?php
if(isset($_GET['keyword'])){
	 $keyword = $_GET['keyword'];
	 //connect to database
	 $user = 'root';
	 $password = 'root';
	 $db = 'casl';
	 $host = 'localhost';
	 $port = 8889;

	 $con=new mysqli($host,$user,$password,$db);
	 // Check connection
	 if ($con->connect_error)
	 {
	   echo "Failed to connect to MySQL: " . mysqli_connect_error();
	 }
	 //get contents with keyword
	 $sql = "SELECT measureNumber FROM basicBill WHERE billSubject LIKE '%".$keyword."%';";
	 $result = $con->query($sql);
	 $output = array();
	 if ($result->num_rows > 0) {
	 	// output data of each row
	    while($row = $result->fetch_assoc()) {
	        $toAdd['measureNumber'] = $row['measureNumber'];
	        array_push($output, $toAdd);
	    }
	 }
	 echo json_encode($output);
} else {
	echo 'Error: No keyword received';
}

?>