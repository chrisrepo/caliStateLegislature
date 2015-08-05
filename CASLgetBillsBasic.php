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
	$miniArray = array();
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
		$toAdd->subject = $sub[0];
		//add lastAction
		$action = $bill->children(4)->innertext;
		$action = strip_tags($action);
		$date = substr($action, 0, 10);
		$act = substr($action, 10);
		$toAdd->lastAction = $date . ": " . $act;
		array_push($miniArray, $toAdd);
	} 
	
	$basicBills = array_merge($basicBills, $miniArray);

 }
 echo json_encode($basicBills, 0);
?>