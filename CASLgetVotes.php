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
 include('simple_html_dom.php');
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
 error_reporting(E_ERROR | E_PARSE);
 $voteList = array();
 $indice = 0;
 $badHtml = 0;
 set_time_limit(0);
 foreach($billIDs as $id) {
 	$indice++;
 	//get page to scrape
 	$html = file_get_html('https://leginfo.legislature.ca.gov/faces/billVotesClient.xhtml?bill_id=201520160'.$id);

    if (!$html){
        //bad html
        $badHtml++;
        continue;
    }
    //find trs (list of votes)
    $tbody = $html->find('tr');
    if (count($tbody) < 2) {
    	//not valid address
    } else {
    //shift the thead tr off the array
    array_shift($tbody);
    //get index value to loop
    $totalVotes = count($tbody)/5;
    for ($i = 0; $i < $totalVotes; $i++){

    	$vote = new billVotes();
    	$mainTr = $tbody[$i*5];
    	$vote->measureNumber = $id;
    	$vote->voteDate = $mainTr->children(0)->innertext;
    	$vote->result = $mainTr->children(1)->innertext;
    	$vote->location = $mainTr->children(2)->innertext;
    	$vote->yesVotes = $mainTr->children(3)->innertext;
    	$vote->noVotes = $mainTr->children(4)->innertext;
    	$vote->nvrVotes = $mainTr->children(5)->innertext;
    	$vote->motion = $mainTr->children(6)->innertext;
    	
    	$ayeTr = $tbody[$i*5+1];
    	$vote->yesMembers = $ayeTr->children(1)->children(1)->innertext;

    	$noeTr = $tbody[$i*5+2];
    	$vote->noMembers = $noeTr->children(1)->children(1)->innertext;

    	$nvrTr = $tbody[$i*5+3];
    	$vote->nvrMembers = $nvrTr->children(1)->children(1)->innertext;
    	array_push($voteList, $vote);
    }
	}
 	//remove references to clear memory
	 $html->clear(); 
	 unset($html);
 }
 echo json_encode($voteList,0);

?>