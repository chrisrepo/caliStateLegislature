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
	$assembly->imgHtml = $div->children(0)->children(1)->children(0)->src;
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

 //connect to database
 $user = 'root';
 $password = 'root';
 $db = 'casl';
 $host = 'localhost';
 $port = 8889;

 $con=mysqli_connect($host,$user,$password,$db);
 // Check connection
 if (mysqli_connect_errno())
 {
   echo "Failed to connect to MySQL: " . mysqli_connect_error();
 }

 $sql = "INSERT INTO assemblyMember (name, districtNumber, politicalParty, officeAddress, officePhone, homepage, contact, imgHtml) VALUES ";
 $count = count($assemblyMembers);
 $index = 0;
 foreach ($assemblyMembers as $mem){
 	$toAdd = "(";
 	$toAdd = $toAdd ."'". $mem->name ."',";
 	$toAdd = $toAdd . $mem->district.",";
 	$toAdd = $toAdd . "'". $mem->politicalParty."',";
 	$toAdd = $toAdd . "'". $mem->officeAddress."',";
 	$toAdd = $toAdd . "'". $mem->officePhoneNumber."',";
 	$toAdd = $toAdd . "'". $mem->homepage."',";
 	$toAdd = $toAdd ."'".$mem->contact."',";
 	$toAdd = $toAdd ."'".$mem->imgHtml."'";
 	//incr index
 	$index += 1;
 	//check if it's last item in list
 	if ($count == $index){
 		$toAdd = $toAdd . ");";
 	} else {
 		$toAdd = $toAdd . "), ";
 	}
 	$sql = $sql . $toAdd;
 }

 if (mysqli_query($con, $sql)) {
 	echo 'success';
 }
//unset variable to clear memory/reference
 $html->clear(); 
 unset($html);


?>