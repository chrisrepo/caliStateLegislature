<?php
 class basicBill {
 	public $measureNumber;
 	public $subject;
 	public $status;
 	public $lastAction;
 }
 include('simple_html_dom.php');
 //set array
 $basicBills = array();
 //set first page
 $html = file_get_html('https://legiscan.com/CA/legislation/2015?page=0');
 //find href of last page to set index limit
 $li = $html->find('li[class*=pager-last]', 0);
 $href = explode("=", $li->children(0)->href);
 $last = $href[1];
 //remove references to clear memory
 $html->clear(); 
 unset($html);
 for ($i=0; $i <= $last; $i++) { 
 	//get page at designated index
 	$html = file_get_html('https://legiscan.com/CA/legislation/2015?page='.$i);
 	$odd = $html->find('tr[class*=odd]');
	$even = $html->find('tr[class*=even]');
	$bills = array_merge($odd,$even);
	foreach ($bills as $bill){
		//create bill
		$toAdd = new basicBill();
		//add measure number
		$toAdd->measureNumber = $bill->children(1)->children(0)->innertext;
		//add status
		$toAdd->status = $bill->children(2)->innertext;
		//add subject
		$subj = $bill->children(3)->innertext;
		$sub = explode("<", $subj);
		$noQuotes = str_replace('"', "'", $sub[0]);
		$toAdd->subject = $noQuotes;
		//add lastAction
		$action = $bill->children(4)->innertext;
		$action = strip_tags($action);
		$date = substr($action, 0, 10);
		$act = substr($action, 10);
		$toAdd->lastAction = $date . ": " . $act;
		array_push($basicBills, $toAdd);
	} 
	//remove references to clear memory
 	$html->clear(); 
 	unset($html);
 }

 //create sql to print

 $sql = 'INSERT INTO basicBill (measureNumber, billSubject, billStatus, lastAction) VALUES ';
 $count = count($basicBills);
 
 $index = 0;
 foreach ($basicBills as $bas){
 	$toAdd ='(';
 	$toAdd = $toAdd .'"'. $bas->measureNumber .'",';
 	$toAdd = $toAdd .'"'. $bas->subject.'",';
 	$toAdd = $toAdd .'"'. $bas->status.'",';
 	$toAdd = $toAdd .'"'. $bas->lastAction.'"';
 	//incr index
 	$index += 1;
 	//check if it's last item in list
 	if ($count == $index){
 		$toAdd = $toAdd . ');';
 	} else {
 		$toAdd = $toAdd . '), ';
 	}
 	$sql = $sql . $toAdd;
 }
 echo $sql;
 
?>