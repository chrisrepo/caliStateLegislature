<?php
 class senator {
 	public $name;
 	public $district;
 	public $politicalParty;
 	public $officeAddress;
 	public $officePhoneNumber;
 	public $homepage;
 	public $contact;
 	public $imgHtml;
 }
 include('simple_html_dom.php');
 //get html of senators page
 $html = file_get_html('http://senate.ca.gov/senators');
 $senators = array();
 foreach($html->find('div[class*=views-row]') as $div){
	$senator = new senator();
	//get img html code
	$senator->imgHtml = $div->children(0)->children(0)->children(0)->outertext;
	//get name/party
	$nameAndParty = explode(" (", $div->children(1)->children(0)->children(0)->innertext);
	$senator->name = $nameAndParty[0];
	$senator->politicalParty = substr($nameAndParty[1], -2,1);
	//get district
	$senator->district = substr($div->children(2)->children(0)->innertext, -2);
	//get hompage
	$senator->homepage = $div->children(3)->children(0)->children(0)->href;
	//get contact page
	$senator->contact = $div->children(4)->children(0)->children(0)->href;
	//get capitol office address and phone number
	$addrPhone = explode("; ", 
		$div->children(5)->children(0)->children(0)->children(1)->innertext);
	$senator->officeAddress = $addrPhone[0];
	if (strpos($addrPhone[1], 'br')){
		//get just the string before the tag 
		$addrPhone[1] = explode("<", $addrPhone[1])[0] ;
	}
	$senator->officePhoneNumber = $addrPhone[1];

	//push senator to array
	array_push($senators, $senator);
 }
//print it (true value makes it more readable)
 echo json_encode($senators, true);
//unset variable to clear memory/reference
 $html->clear(); 
 unset($html);

?>