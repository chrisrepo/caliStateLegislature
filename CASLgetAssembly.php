<?php
 class assembly {
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
 $html = file_get_html('http://assembly.ca.gov/assemblymembers');
 $odd = $html->find('tr[class*=odd]');
 $even = $html->find('tr[class*=even]');
 $members = array_merge($odd,$even);

 $assemblyMembers = array();

foreach( $members as $div){
	$assembly = new assembly();
	//get img html code
	$assembly->imgHtml = $div->children(0)->children(1)->children(0)->outertext;
	//get name
	$assembly->name = $div->children(0)->children(0)->innertext;
	//get hompage
	$assembly->homepage = $div->children(0)->children(0)->href;
	//get district
	$assembly->district = $div->children(1)->innertext;
	//get party
	$assembly->politicalParty = $div->children(2)->innertext;
	//get contact page
	$assembly->contact = $div->children(3)->children(0)->href;
	//get capitol office address and phone number
	$addrPhone = explode("; ", 
		$div->children(3)->children(2)->innertext);
	$assembly->officeAddress = $addrPhone[0];
	if (strpos($addrPhone[1], 'br')){
		//get just the string before the tag 
		$addrPhone[1] = explode("<", $addrPhone[1])[0] ;
	}
	$assembly->officePhoneNumber = $addrPhone[1];

	//push senator to array
	array_push($assemblyMembers, $assembly);
 }

 
//print it (true value makes it more readable)
 echo json_encode($assemblyMembers, true);
//remove references to clear memory
 $html->clear(); 
 unset($html);

 function getAssemblyMembers(){
 	return 'hiiii';
 }
?>