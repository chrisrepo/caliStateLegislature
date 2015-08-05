<!DOCTYPE html>
<html>
   <head>
      <title>CA Votes</title>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="shortcut icon" href="images/favicon.ico" />
      <link href="css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" type="text/css">
       <link href="css/custom.css" rel="stylesheet">
      <script src="js/jquery-2.1.3.min.js"></script>
      <script src="js/bootstrap.min.js"></script>
   </head>
   <script language="javascript">
    $(document).on( 'shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
      //get active tab, create a cookie with it and document it.
       var href = e.target.href;
       var splits = href.split("/");
       var activeTab = splits[splits.length-1];//active tab
       var d = new Date();
       d.setTime(d.getTime() + (1*24*60*60*1000));
       var expires = "expires="+d.toUTCString();
       document.cookie = "activeTab="+activeTab+"; "+expires;
    });

    function toggleDiv(div,index){
      if($(div).html() == "-"){
        $(div).html("+");
      }
      else{
          $(div).html("-");
      }
      $("#"+index.id).slideToggle();
    }
   </script>
   <body>
      <div class='container'>
         <div class='headsUp'>
            <div class='center centerText'>
               <h1> California Bill/Vote Info</h1>
            </div>
         </div>
         <?php 
         if (empty($_COOKIE['activeTab'])){
          $_COOKIE['activeTab'] == '#about';
         }


         $user = 'root';
         $password = 'root';
         $db = 'casl';
         $host = 'localhost';
         $port = 8889;

         ?>
         <ul id='myTabs' class="nav nav-tabs">
           <li role="presentation" <?php echo ($_COOKIE['activeTab'] == '#about') ?  'class="active"' : '' ?>
            ><a href="inde.php/#about" data-toggle="tab">About</a></li>
           <li role="presentation" <?php echo ($_COOKIE['activeTab'] == '#bills') ?  'class="active"' : '' ?>
            ><a href="index.php/#bills" data-toggle="tab">Search Bills</a></li>
           <li role="presentation" <?php echo ($_COOKIE['activeTab'] == '#members') ?  'class="active"' : '' ?>
            ><a href="index.php/#members" data-toggle="tab">Search Members</a></li>
           <li role="presentation" <?php echo ($_COOKIE['activeTab'] == '#contact') ?  'class="active"' : '' ?>
            ><a href="index.php/#contact" data-toggle="tab">Contact</a></li>
         </ul>
         <div id="myTabContent" class="tab-content tabBackground">
            <div <?php echo ($_COOKIE['activeTab'] == '#about') ?  'class="tab-pane active"' : 'class="tab-pane fade"' ?> id='about'>
               <div class='centerP'>
                  <h3>What is this?</h3>
                  <p>This is a little tool to search bills from the 2015-2016 session for info and vote tallies. You can also search by senator/assemblyman to see what how they've voted on these bills.
                  </p>
                  <h3>How do I use this?</h3>
                  <p>1. The <strong>Search Bills</strong> tab can search all of the bills that have been passed for the 2015-2016 term. You can search by the measure number or a keyword.</p>
                  <p>2. The <strong>Search Members</strong> tab can search individual senate or assembly members to show what they have voted on recently.</p>
                  <p>What you do with this handy information is completely up to you</p>
                  <h3>Why?</h3>
                  <p>Simply put: I was bored and wanted to tool around with some data. I decided to look into the state legislature because that is where most of the law reform comes from. The amount of bills passed in the US Congress pales in comparison to the amount of bills a state can pass. I like scraping the web for info and using php scripts to pull that data and shove it into a database. It's like a quick little app.</p>
                  <h3>Questions?</h3>
                  <p>Is there an error on this page? Do you think something is missing? Have a suggestion for the site? Fill out the form on the <strong>Contact</strong> tab to let me know!</p>
                  </div>
            </div>
            <div <?php echo ($_COOKIE['activeTab'] == '#bills') ?  'class="tab-pane active"' : 'class="tab-pane fade"' ?> id='bills'>
               <p class='centerP center warning'>Bill search under development (Completely functional, but not pretty)
               </p>

                <div class='billTabContainer'>
                  <div class="row">
                    <div class="col-md-6">
                      <div clas="row">
                        <div class="col-md-1"></div>
                        <div class="col-md-10 billSearch">
                          <h4 class="center">Search by Bill ID Number</h4>
                          <form action="" method="post" autocomplete="off" class="center padBottom">
                          <input type="text" placeholder="Enter a Bill ID... (Ex: AB1, AB2)" name="billID" id="billIDInput" 
                          <?php echo (isset($_POST['sendBill'])) ? 'value = "'.$_POST['billID'].'"' : ''?> >
                          <input type="submit" name="sendBill" class="btn btn-default" value='Search'>
                          </form>
                        </div>
                        <div class="col-md-1"></div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div clas="row">
                        <div class="col-md-1"></div>
                        <div class="col-md-10 billSearch">
                          <h4 class="center">Search by Keyword</h4>
                          <form action="" method="post" autocomplete="off" class="center padBottom">
                          <input type="text" placeholder="Enter a keyword... (Ex: drought)" name="billKey" id="billKeyInput" 
                          <?php echo (isset($_POST['sendBillKey'])) ? 'value = "'.$_POST['billKey'].'"' : ''?> >
                          <input type="submit" name="sendBillKey" class="btn btn-default" value='Search'>
                          </form>
                        </div>
                        <div class="col-md-1"></div>
                      </div>
                    </div>
                  </div>
                  <br>
               <?php
            #PRAGMA-BILLID
            if(isset($_POST['sendBill']) && !empty($_POST['billID'])){
              //connect to database
               $con=new mysqli($host,$user,$password,$db);
               // Check connection
               if ($con->connect_error)
               {
                 echo "Failed to connect to MySQL: " . mysqli_connect_error();
               }
               $billID = $_POST['billID'];
               $sql = "SELECT * FROM basicBill WHERE measureNumber ='".$billID."';";
               $result = $con->query($sql);
               echo "<div class='row'>";
               echo "<div class='col-md-2'><div class='row'><div class='col-md-1'></div>";
               echo "<div class='col-md-11'><h4>Measure Number</h4></div></div></div>";
               echo "<div class='col-md-4'><h4>Bill Subject</h4></div>";
               echo "<div class='col-md-4'><h4>Last Action</h4></div>";
               echo "<div class='col-md-2'><div class='row'><div class='col-md-9'><h4>Bill Status</h4></div>";
               echo "<div class='col-md-3'></div></div></div>";
               echo "</div>";
               while ($row = mysqli_fetch_assoc($result)) {
                //loop through basic bills
                  echo "<div class='row padBottom'>";
                  echo "<div class='col-md-2'><div class='row'><div class='col-md-1'></div>";
                  echo "<div class='col-md-11'>".$row['measureNumber']."</div></div></div>";
                  echo "<div class='col-md-4'>".$row['billSubject']."</div>";
                  echo "<div class='col-md-4'>".$row['lastAction']."</div>";
                  echo "<div class='col-md-2'><div class='row'><div class='col-md-9'>".$row['billStatus']."</div>";
                  echo "<div class='col-md-3'></div></div></div>";
                  echo "</div>";
                  $voteSql = "SELECT * FROM billVotes WHERE billNumber ='".$row['measureNumber']."';";
                  $voteResult = $con->query($voteSql);
                  $index = 0;
                  echo "<div class='billHeaders tabIn'>Recent Votes:</div>";
                  if (!mysqli_num_rows($voteResult)==0){
                    echo "<div class='voteContainer' id='bill".$billIndex."'>";
                  }
                  $billIndex++;
                  while ($vRow = mysqli_fetch_assoc($voteResult)){
                    if ($index%2==0){
                      $read = "voteBackgroundEven";
                    } else {
                      $read = "voteBackgroundOdd";
                    }
                    $index++;
                    echo "<div class='padding5'>";
                    echo "<div class='".$read."'>";
                    echo "<div class='row voteResults'>";
                    echo "<div class='col-md-4 yesVotes'><div class='row'>";
                    echo "<div class='col-md-4'>Yes Votes: ".$vRow['yesVotes']."</div>";
                    echo "<div class='col-md-4'>No Votes: ".$vRow['noVotes']."</div>";
                    echo "<div class='col-md-4'>NVR Votes: ".$vRow['nvrVotes']."</div></div></div>";
                    echo "<div class='col-md-2'>Vote Date: ".$vRow['voteDate']."</div>";
                    echo "<div class='col-md-4'>Location of Vote: ".$vRow['location']."</div>";
                    echo "<div class='col-md-2'>Result: ".$vRow['result']."</div>";
                    echo "</div>";
                    echo "<div class='row voteResults ".$read."'>";
                    echo "<div class='col-md-12'>Motion: ".$vRow['motion']."</div></div>";
                    echo "<div class='row voteMembers ".$read."'>";
                    echo "<div class='col-md-1'>Voted Yes:</div>";
                    echo "<div class='col-md-11'>".$vRow['yesMembers']."</div>";
                    echo "</div>";
                    echo "<div class='row voteMembers ".$read."'>";
                    echo "<div class='col-md-1'>Voted No:</div>";
                    echo "<div class='col-md-11'>".$vRow['noMembers']."</div>";
                    echo "</div>";
                    echo "<div class='row voteMembers ".$read."'>";
                    echo "<div class='col-md-1'>NVR:</div>";
                    echo "<div class='col-md-11'>".$vRow['nvrMembers']."</div>";
                    echo "</div></div></div>";
                  }
                  if (!mysqli_num_rows($voteResult)==0){
                    echo "</div>";
                  }
               }
            }
            #PRAGMA-KEYWORD
            if(isset($_POST['sendBillKey']) && !empty($_POST['billKey'])){
              //connect to database
               $con=new mysqli($host,$user,$password,$db);
               // Check connection
               if ($con->connect_error)
               {
                 echo "Failed to connect to MySQL: " . mysqli_connect_error();
               }
               $billKey = $_POST['billKey'];
               $sql = "SELECT * FROM basicBill WHERE billSubject LIKE '%".$billKey."%';";
               $result = $con->query($sql);
               echo "<br><br>";
               echo "<div class='billContainer'>";
               $billIndex = 1;
               while ($row = mysqli_fetch_assoc($result)) {
                //loop through basic bills
                  echo "<div class='individualBill'>";
                  echo "<div class='row'>";
                  echo "<div class='col-md-2'>";
                  echo "<div class='row'><div class='col-md-4'><span id='minimizer' onClick='toggleDiv(this,bill".$billIndex.")'>-</span></div>";
                  echo "<div class='col-md-8'><span class='billHeaders'>ID:</span> ".$row['measureNumber']."</div></div></div>";
                  echo "<div class='col-md-4'><span class='billHeaders'>Subject:</span> ".$row['billSubject']."</div>";
                  echo "<div class='col-md-4'><span class='billHeaders'>Last Action:</span> ".$row['lastAction']."</div>";
                  echo "<div class='col-md-2'><span class='billHeaders'>Status:</span> ".$row['billStatus']."</div>";
                  echo "</div>";
                  $voteSql = "SELECT * FROM billVotes WHERE billNumber ='".$row['measureNumber']."';";
                  $voteResult = $con->query($voteSql);
                  $index = 0;
                  if (!mysqli_num_rows($voteResult)==0){
                    echo "<div class='voteContainer' id='bill".$billIndex."'>";
                  }
                  $billIndex++;
                  while ($vRow = mysqli_fetch_assoc($voteResult)){
                    if ($index%2==0){
                      $read = "voteBackgroundEven";
                    } else {
                      $read = "voteBackgroundOdd";
                    }
                    $index++;
                    echo "<div class='padding5'>";
                    echo "<div class='".$read."'>";
                    echo "<div class='row voteResults ".$read."''>";
                    echo "<div class='col-md-4 yesVotes'><div class='row'>";
                    echo "<div class='col-md-4'>Yes Votes: ".$vRow['yesVotes']."</div>";
                    echo "<div class='col-md-4'>No Votes: ".$vRow['noVotes']."</div>";
                    echo "<div class='col-md-4'>NVR Votes: ".$vRow['nvrVotes']."</div></div></div>";
                    echo "<div class='col-md-2'>Vote Date: ".$vRow['voteDate']."</div>";
                    echo "<div class='col-md-4'>Location of Vote: ".$vRow['location']."</div>";
                    echo "<div class='col-md-2'>Result: ".$vRow['result']."</div>";
                    echo "</div>";
                    echo "<div class='row voteResults ".$read."'>";
                    echo "<div class='col-md-12'>Motion: ".$vRow['motion']."</div></div>";
                    echo "<div class='row voteMembers ".$read."'>";
                    echo "<div class='col-md-1'>Voted Yes:</div>";
                    echo "<div class='col-md-11'>".$vRow['yesMembers']."</div>";
                    echo "</div>";
                    echo "<div class='row voteMembers ".$read."'>";
                    echo "<div class='col-md-1'>Voted No:</div>";
                    echo "<div class='col-md-11'>".$vRow['noMembers']."</div>";
                    echo "</div>";
                    echo "<div class='row voteMembers ".$read."'>";
                    echo "<div class='col-md-1'>NVR:</div>";
                    echo "<div class='col-md-11'>".$vRow['nvrMembers']."</div>";
                    echo "</div></div></div>";
                  }
                  if (!mysqli_num_rows($voteResult)==0){
                    echo "</div>";
                  }
               echo "</div>";
               }
               echo "</div>";

            }
             ?>
            </div>
          </div>
            <div <?php echo ($_COOKIE['activeTab'] == '#members') ?  'class="tab-pane active"' : 'class="tab-pane fade"' ?> id='members'>
               <p class='centerP center warning'>Member search under development
               </p>
               <div class='memberTabContainer'>
                  <div class="row">
                    <div class="col-md-6">
                      <div clas="row">
                        <div class="col-md-1"></div>
                        <div class="col-md-10 billSearch">
                          <h4 class="center">Search Senate</h4>
                          <form action="" method="post" autocomplete="off" class="center padBottom">
                          <input type="text" placeholder="Enter a name to search..." name="senateName" id="senateNameInput" 
                          <?php echo (isset($_POST['sendSenate'])) ? 'value = "'.$_POST['senateName'].'"' : ''?> >
                          <input type="submit" name="sendSenate" class="btn btn-default" value='Search'>
                          </form>
                        </div>
                        <div class="col-md-1"></div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div clas="row">
                        <div class="col-md-1"></div>
                        <div class="col-md-10 billSearch">
                          <h4 class="center">Search Assembly</h4>
                          <form action="" method="post" autocomplete="off" class="center padBottom">
                          <input type="text" placeholder="Enter a name to search..." name="asmName" id="asmNameInput" 
                          <?php echo (isset($_POST['sendAsm'])) ? 'value = "'.$_POST['asmName'].'"' : ''?> >
                          <input type="submit" name="sendAsm" class="btn btn-default" value='Search'>
                          </form>
                        </div>
                        <div class="col-md-1"></div>
                      </div>
                    </div>
                  </div>
            </div>
            <?php
            #PRAGMA-MEMBER
            if((isset($_POST['sendSenate']) && !empty($_POST['senateName'])) || 
              (isset($_POST['sendAsm']) && !empty($_POST['asmName']))){
              //connect to database
               $con=new mysqli($host,$user,$password,$db);
               // Check connection
               if ($con->connect_error)
               {
                 echo "Failed to connect to MySQL: " . mysqli_connect_error();
               }
               //set name string to search
               $name = "";
               $house = "";
               if (isset($_POST['senateName'])){
                $name = $_POST['senateName'];
                $house = "senateMember";
               } else {
                $name = $_POST['asmName'];
                $house = "assemblyMember";
               }

               $sql = "SELECT * FROM ".$house." WHERE name LIKE '%".$name."%' ORDER BY districtNumber ASC;";
               $result = $con->query($sql);
               echo "<div class='memberBox'>";
               //output extra info if it's a single member
               if (mysqli_num_rows($result)==1){
                while ($row = mysqli_fetch_assoc($result)) {
                  echo "<div class='paddingContainer'>";
                  echo "<div class='row memberContainer'>";
                  echo "<div class='col-md-2'>";
                  echo "<img src='".$row['imgHtml']."'></div>";
                  echo "<div class='col-md-10'>";
                  echo "<div class='row'><div class='col-md-12'><span class='memberTable'><strong>Information</strong></span></div></div>";
                  echo "<div class='row'><div class='col-md-12'><strong>Name: </strong>".$row['name']."</div></div>";
                  echo "<div class='row'><div class='col-md-12'><strong>District: </strong>".$row['districtNumber']."</div></div>";
                  echo "<div class='row'><div class='col-md-12'><strong>Party: </strong>".$row['politicalParty']."</div></div>";
                  echo "<div class='row topSpace'><div class='col-md-12'><span class='memberTable'><strong>Contact</strong><span></div></div>";
                  echo "<div class='row'><div class='col-md-12'><strong>Office Address: </strong>".$row['officeAddress']."</div></div>";
                  echo "<div class='row'><div class='col-md-12'><strong>Office Phone: </strong>".$row['officePhone']."</div></div>";
                  echo "<div class='row'><div class='col-md-12'><strong>Contact Page: </strong>".$row['contact']."</div></div>";
                  echo "<div class='row'><div class='col-md-12'><strong>Homepage: </strong>".$row['homepage']."</div></div>";
                  echo "</div></div></div>";
                }
               } else {
                while ($row = mysqli_fetch_assoc($result)) {
                  echo "<div class='paddingContainer'>";
                  echo "<div class='row memberContainer'>";
                  echo "<div class='col-md-2'>";
                  echo "<img src='".$row['imgHtml']."'></div>";
                  echo "<div class='col-md-10'>";
                  echo "<div class='row'><div class='col-md-12'><span class='memberTable'><strong>Information</strong></span></div></div>";
                  echo "<div class='row'><div class='col-md-12'><strong>Name: </strong>".$row['name']."</div></div>";
                  echo "<div class='row'><div class='col-md-12'><strong>District: </strong>".$row['districtNumber']."</div></div>";
                  echo "<div class='row'><div class='col-md-12'><strong>Party: </strong>".$row['politicalParty']."</div></div>";
                  echo "<div class='row topSpace'><div class='col-md-12'><span class='memberTable'><strong>Contact</strong><span></div></div>";
                  echo "<div class='row'><div class='col-md-12'><strong>Office Address: </strong>".$row['officeAddress']."</div></div>";
                  echo "<div class='row'><div class='col-md-12'><strong>Office Phone: </strong>".$row['officePhone']."</div></div>";
                  echo "<div class='row'><div class='col-md-12'><strong>Contact Page: </strong>".$row['contact']."</div></div>";
                  echo "<div class='row'><div class='col-md-12'><strong>Homepage: </strong>".$row['homepage']."</div></div>";
                  echo "</div></div></div>";
                }
               }
             
              echo "</div>";
            }
            ?>
            <div <?php echo ($_COOKIE['activeTab'] == '#contact') ?  'class="tab-pane active"' : 'class="tab-pane fade"' ?> id='contact'>
               <p class='centerP'>Sorry, form hasn't been created yet. You're SOL buddy 
               </p>
            </div>
         </div>
      </div>
   </body>
</html>
