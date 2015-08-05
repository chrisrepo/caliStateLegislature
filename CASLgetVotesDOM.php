<?php
 class billVotes {
 	public $measureNumber;
 	public $yesVotes;
 	public $noVotes;
 	public $nvrVotes;
 	public $nvrMembers;
 	public $yesMembers;
 	public $noMembers;
 	public $voteDate;
 	public $location;
 	public $result;
 	public $motion;
 }
 //get bill IDs from db
 $user = 'root';
 $password = 'root';
 $db = 'casl';
 $host = 'localhost';
 $port = 8889;
 $sql = "SELECT measureNumber FROM basicBill;";
 //connect
 $con=new mysqli($host,$user,$password,$db);
 // Check connection
 if ($con->connect_error)
 {
   echo "Failed to connect to MySQL: " . mysqli_connect_error();
 }
 //get result
 $result = $con->query($sql);
 $billIDs = array();
 if ($result->num_rows > 0) {
 	// output data of each row
    while($row = $result->fetch_assoc()) {
        array_push($billIDs, $row['measureNumber']);
    }
 }
 flush();
 //allow errors to be reported so i can see why it fails
 error_reporting(E_ALL);
 ini_set('display_errors', 1);
 $voteList = array();
 foreach($billIDs as $id) {
 	//get page to scrape
    $doc = new DOMDocument('1.0');
    $doc->loadHTML('http://leginfo.legislature.ca.gov/faces/billVotesClient.xhtml?bill_id=201520160'.$id);
    echo '<br>'.$id.'&nbsp;Memory: '.memory_get_usage();
    echo '<br>doctype: '.strtolower($doc->doctype->publicId);
    $all = $doc->getElementsByTagName('tbody');
    echo '<br>'.count($all);
    echo '<br>'.true;
    break;

 }
 echo json_encode($voteList,0);

?>