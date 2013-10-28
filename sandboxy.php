<?php


$allTeams[0]="Buffalo Bills";
$allTeams[1]="St. Louis Rams"; 
$allTeams[2]="Baltimore Ravens";
$allTeams[3]="Cincinnati Bengals";
$allTeams[4]="Cleveland Browns";
$allTeams[5]="Pittsburgh Steelers";
$allTeams[6]="Chicago Bears";
$allTeams[7]="Detroit Lions";
$allTeams[8]="Green Bay Packers"; 
$allTeams[9]="Minnesota Vikings";
$allTeams[10]="Houston Texans";
$allTeams[11]="Indianapolis Colts";
$allTeams[12]="Jacksonville Jaguars";
$allTeams[13]="Tennessee Titans";
$allTeams[14]="Atlanta Falcons";
$allTeams[15]="Carolina Panthers";
$allTeams[16]="New Orleans Saints"; 
$allTeams[17]="Tampa Bay Buccaneers";
$allTeams[18]="Miami Dolphins";
$allTeams[19]="New England Patriots";
$allTeams[20]="New York Jets";
$allTeams[21]="Dallas Cowboys";
$allTeams[22]="New York Giants";
$allTeams[23]="Philadelphia Eagles";
$allTeams[24]="Washington Redskins"; 
$allTeams[25]="Denver Broncos";
$allTeams[26]="Kansas City Chiefs";
$allTeams[27]="Oakland Raiders";
$allTeams[28]="San Diego Chargers";
$allTeams[29]="Arizona Cardinals";
$allTeams[30]="San Francisco 49ers";
$allTeams[31]="Seattle Seahawks";


$thisWeek=9;


function make_seed()
{
  list($usec, $sec) = explode(' ', microtime());
  return ((float) $usec * 100000);
}

function swap(&$pos, $i, $j)
{
      $temp=$pos[$i];
       $pos[$i]=$pos[$j];
       $pos[$j]=$temp;
  //     echo "made a swap<br>";
}

function qsortdkd(&$pos, $keys, $left, $right)
{
       if($left>=$right)
               return;
       swap($pos, $left,($left+$right)/2);
       $last=$left;
       for($i=$left+1;$i<=$right;$i++)
               if($keys[$pos[$i]]>$keys[$pos[$left]])
                       swap($pos, ++$last,$i);

       swap($pos, $left, $last);
       qsortdkd($pos, $keys, $last+1, $right);
       qsortdkd($pos, $keys, $left, $last-1);

}

function printformteaminputs($numTeams)
{
    for($i=0;$i<$numTeams;$i++)
    {
      echo "<INPUT TYPE=hidden NAME=stack" . $i .">";
    }
  
}

function printstack($pos,$numTeams)
{
     
   global $allTeams;
    
    
    for($i=0;$i<$numTeams;$i++)
    {
        echo '<li id=' . $pos[$i] . ' class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>'.$allTeams[$pos[$i]]."</li>\n";
    }
}

function printprevstack($numTeams)
{

	for($i=0;$i<$numTeams;$i++)
	{
        	$pos[i]=$_POST['stack'.$i];
	}

        
        printstack($pos,$numTeams);
}

function printrandstack($pos,$numTeams)
{
      $numkeys=0;

      srand(make_seed());
      while ($numkeys<$numTeams)
      {
        $keys[$numkeys] = rand(0,256);
        //$pos[$numkeys]=$numkeys;
        //echo "$keys[$numkeys]<br>";
        $numkeys=$numkeys+1;
      }
      qsortdkd($pos, $keys, 0, 7);
      
      printstack($pos, $numTeams);
      
}


//Setting The Session Saving path to "sessions", must be protected from reading
//session_save_path("sessions"); // This function is an alternative to ini_set("session.save_path","sessions");
//Session Cookie's Lifetime ( not effective, but use! )
ini_set("session.cookie_lifetime",time()+600);
//Change the Session Name from PHPSESSID to SessionID
session_name("SessionID");
//Start The session
session_start();
//Set a session cookie ( Required for some browsers, as settings that had been done before are not very effective 
setcookie(session_name(), session_id(), time()+3600*24, "/");


$timeout = 5 * 60; // 5 minutes
$fingerprint = md5('SECRET-SALT'.$_SERVER['HTTP_USER_AGENT']);
session_start();
if ( (isset($_SESSION['last_active']) && (time() > ($_SESSION['last_active']+$timeout)))
     || (isset($_SESSION['fingerprint']) && $_SESSION['fingerprint']!=$fingerprint)
     || isset($_GET['logout']) ) {
    //do_logout();
    session_destroy();
}
session_regenerate_id(); 
$_SESSION['last_active'] = time();
$_SESSION['fingerprint'] = $fingerprint;

?>

<!DOCTYPE html>
<html lang="en">



<?php
//do all database reads and writes here
$cxn = mysqli_connect ("", "", "", "");


   if($_SESSION['newness']!="oldness")
  {
	global $thisWeek;
	 $byeQuery="SELECT Team.TeamID FROM Team, ByeWeek WHERE Team.TeamID = ByeWeek.TeamID AND ByeWeek.Week !=".$thisWeek;
    	$result2 = mysqli_query ($cxn, $byeQuery, MYSQLI_USE_RESULT) or die (mysqli_error ($cxn) . " The query was: " . $findidstring);
  	$numTeams=0;

	//echo $numTeams."hey hey hey\n";
	//echo $byeQuery;
	//echo $result2;
	

	//now to populate pos array with the teams that are actually playing this week.

	$pos = array();
	while($row = mysqli_fetch_array($result2, MYSQLI_NUM))
	{
		$numTeams++;
		$pos[] = $row[0];
	}

	echo $numTeams;

	$_SESSION['newness']="newness";
    	$_SESSION['numTeams']=$numTeams;
	
   }



  if ($_POST['todo']=="login")
  {
    $hashpass=md5($_POST['passwd']);
    
    //echo $_POST['username']." ".$hashpass;
    
    $findidstring="SELECT UserID, prevset FROM User WHERE username='".$_POST['username']."' AND hashpass='".$hashpass."';";
    $result2 = mysqli_query ($cxn, $findidstring, MYSQLI_USE_RESULT) or die (mysqli_error ($cxn) . " The query was: " . $findidstring);
   // mysqli_free_result ( $result2 );
    $row = mysqli_fetch_array($result2, MYSQLI_ASSOC);
    mysqli_free_result ( $result2 );
    
    $userid=$row['UserID'];
    $prevset=$row['prevset'];
    
    
    

   
     if ($_POST['subcurrent']=="yes")
    {
	for($i=0;$i<$numTeams;$i<numTeams)
	{
      		$stack[$i]=$_POST['stack'.$i];
	}
      
    
      if($prevset==1)
      {
        for($i=0;$i<$numTeams;$i++)
        {
          //update each pick
          $j=$i+1;
          $updatestring="UPDATE Pick" . $j .  " SET TeamID='" . $stack[$i] . "' WHERE UserID='". $userid . "';";
          $result = mysqli_query ($cxn, $updatestring) or die (mysqli_error ($cxn) . " The query was: " . $updatestring);;
        }
      }
      else
      {
        for($i=0;$i<$numTeams;$i++)
        {
          //update each pick
          $j=$i+1;
          $insertstring="INSERT INTO Pick" . $j .  " (UserID, TeamID) VALUES (" . $userid. ",".$stack[$i] . ");";
          $result = mysqli_query ($cxn, $insertstring) or die (mysqli_error ($cxn) . " The query was: " . $insertstring);;
        }
        
        $updatestring="UPDATE User SET prevset=1 WHERE UserID='". $userid . "';";
        $result = mysqli_query ($cxn, $updatestring) or die (mysqli_error ($cxn) . " The query was: " . $updatestring);
      
      }
    }
      
      $_SESSION['userid']=$userid;
      //need to set session user to $userid
    
  
  }
  
  else if ($_POST['todo']=="create")
  {
    $hashpass=md5($_POST['passwd']);
  //  echo $_POST['passwd'];
   // echo $_POST['username']." ".$hashpass;
    
    $createstring="INSERT INTO User (username, fname, lname, hashpass) VALUES ('" . $_POST['username']. "', '".  $_POST['fname'] . "', '" . $_POST['lname']. "', '" . $hashpass . "');";
    $result = mysqli_query ($cxn, $createstring) or die (mysqli_error ($cxn) . " The query was: " . $createstring);
    //mysqli_free_result ( $result );
      
      
   
    $getidstring='SELECT UserID FROM User WHERE username="'. $_POST['username'].'";';
    //echo $getidstring;
    $result2 = mysqli_query ($cxn, $getidstring, MYSQLI_USE_RESULT) or die (mysqli_error ($cxn) . " The query was: " . $getidstring);
   // mysqli_free_result ( $result2 );
    $row = mysqli_fetch_array($result2, MYSQLI_ASSOC);
    mysqli_free_result ( $result2 );
    
    $userid=$row['UserID'];
    //need to get $userid here

    $setstring="INSERT INTO PrevSet";
    
    if ($_POST['subcurrent']=="yes")
    {
	for($i=0;$i<numTeams;$i++)
	{
		$stack[$i]=$_POST['stack'.$i];
	}

      
      //echo $_POST['subcurrent'];
      //echo $_POST['stack7'];
    
      for($i=0;$i<$numTeams;$i++)
      {
        //update each pick
        $j=$i+1;
        $insertstring="INSERT INTO Pick" . $j.  "(UserId, TeamID) VALUES (". $userid . ", " . $stack[$i] . ");";
        //echo $insertstring;
        $result = mysqli_query ($cxn, $insertstring) or die (mysqli_error ($cxn) . " The query was: " . $insertstring);
        //mysqli_free_result ( $result );
      }
      
      $updatestring="UPDATE User SET prevset=1 WHERE UserID='". $userid . "';";
      $result = mysqli_query ($cxn, $updatestring) or die (mysqli_error ($cxn) . " The query was: " . $updatestring);
    
    }
  }
  
  else if ($_POST['todo']=="update")
  {
	for($i=0;$i<$numTeams;$i++)
	{
    		$stack[$i]=$_POST['stack'.$i];
	}

    
    //echo $stack[0];
    //echo $stack[1];
    
    $getidstring='SELECT UserID, prevset FROM User WHERE username="'. $_POST['username'].'";';
    //echo $getidstring;
    $result2 = mysqli_query ($cxn, $getidstring, MYSQLI_USE_RESULT) or die (mysqli_error ($cxn) . " The query was: " . $getidstring);
   // mysqli_free_result ( $result2 );
    $row = mysqli_fetch_array($result2, MYSQLI_ASSOC);
    mysqli_free_result ( $result2 );
    
    $userid=$row['UserID'];
    $prevset=$row['prevset'];
    
    
   if($prevset==1)
    {
    for($i=0;$i<$numTeams;$i++)
    {
      //update each pick
      $j=$i+1;
      $updatestring="UPDATE Pick" . $j .  " SET TeamID='" . $stack[$i] . "' WHERE UserID='". $userid . "';";
      //echo $updatestring;
      $result = mysqli_query ($cxn, $updatestring);
    }
    }
    else
    {
      for($i=0;$i<$numTeams;$i++)
    {
      //update each pick
      $j=$i+1;
      $updatestring="INSERT INTO Pick" . $j .  " (TeamID, UserID) VALUES (" . $stack[$i] . ",". $userid . ");";
      //echo $updatestring;
      $result = mysqli_query ($cxn, $updatestring);
    }
   
   $updatestring="UPDATE User SET prevset=1 WHERE UserID='". $userid . "';"; 
   $result = mysqli_query ($cxn, $updatestring);
    
   } 
    
   $handystring="updated...<br>";
  
  }
?>

<head>
	<meta charset="UTF-8" />
	<title>NFL Stack Game</title>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <link rel="stylesheet" href="/resources/demos/style.css" />
    
    
	<!--<link type="text/css" href="../demos.css" rel="stylesheet" /> -->
  	<style type="text/css">
	#sortable { list-style-type: none; margin: 0; padding: 0; width: 100%; }
	#sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 18px; }
	#sortable li span { position: absolute; margin-left: -1.3em; }
  #tabs{ width: 450px;}
	</style>
<script type="text/javascript">
	$(function() {

    	$("#sortable").sortable();
		$("#sortable").disableSelection();
    		$("#tabs").tabs();
	});
	</script> 
  
  
  <script type="text/javascript"> 
	
  function wallylogsubmit()
  {
    var rval;
    
    rval=true;
    
    var result = $('#sortable').sortable('toArray');
    if (document.forms["wallylog"].subcurrent.value=="yes")
    {

	<?php
		echo "\t";
		for($i=0;$i<$numTeams;$i++)
		{
			echo "document.forms[\"wallylog\"].stack".$i."value=result[".$i."];\n\t";
		}
	?>

    }
    
    return rval;
  
  }
  
  function wallycreatesubmit()
  {
    var rval=true;
    
    var result = $('#sortable').sortable('toArray');
   
    if (document.forms["wallycreate"].passwd.value!=document.forms["wallycreate"].passwd2.value)
    {

      alert("I don't think that those passwords match.");
      rval=false;

    }
    
    if (document.forms["wallycreate"].subcurrent.value=="yes")
    {
	<?php
	echo "\t";
	for($i=0;$i<$numTeams;$i++)
	{
	      echo "document.forms[\"wallycreate\"].stack".$i.".value=result[".$i."];\n\t";
	}
	?>

    }

    
    return rval;
  }
  
  function wallyupdatesubmit()
  {
    var rval=true;
  
    var result = $('#sortable').sortable('toArray');

 	<?php
		echo "\t";
		for($i=0;$i<$numTeams;$i++)
		{
      			echo "document.forms[\"wallyupdate\"].stack".$i.".value=result[".$i."];\n\t";
		}
	?>
 
    //}

    return rval;
  
  }
  
  
  
	</script> 
  
  
  
</head>


<body>

<?php 
      echo $handystring;
?>

<center><table id="bigtable" width="85%" height="90%" cellpadding="6%"> 
<tr>
<td width="40%">
<div class="stack">

<ul id="sortable">


<?php
  
  //get prevset here
  
  $prevset=0;
  if ($_POST['todo']=="update" || $_POST['todo']=="login" || $_POST['todo']=="create")
  {
    $getprev="SELECT UserID, prevset FROM User WHERE username='". $_POST['username']."';";
    //echo $getidstring;
    $result2 = mysqli_query ($cxn, $getprev, MYSQLI_USE_RESULT) or die (mysqli_error ($cxn) . " The query was: " . $getprev);
   // mysqli_free_result ( $result2 );
    $row = mysqli_fetch_array($result2, MYSQLI_ASSOC);
    mysqli_free_result ( $result2 );
    
    $prevset=$row['prevset'];
    $userid=$row['UserID'];

  
  }
  if($prevset == 1)
  {
      for($i=0;$i<$numTeams;$i++)
      {
        $j=$i+1;
        $getstring="SELECT TeamID FROM Pick" . $j ." WHERE UserID='" . $userid . "';";
        $result2 = mysqli_query ($cxn, $getstring, MYSQLI_USE_RESULT) or die (mysqli_error ($cxn) . " The query was: " . $getstring);
        $row = mysqli_fetch_array($result2, MYSQLI_ASSOC);
        mysqli_free_result ( $result2 );
        
        $place=$row['TeamID'];
        
        $pos2[$i]=$place;
        
      }
    
      printstack($pos2);
  }
   
   else
  {
   
      printrandstack($pos,$numTeams);
   
   
  }
   

?>
</ul>

</div>
</td><td width="5%"></td><td><h1>Rate<br>This<br>week's teams<br></h1></td></tr><!--end of row1 of lefttable i.e. the stack-->
<tr><td width="40%">
<div class="logbox">

<div id="tabs">
	<ul>
  
  <?php 
  
  if ($_POST['todo']=="update" || $_POST['todo']=="login" || $_POST['todo']=="create")
  {
      echo '<li><a href="#tabs-1">Submit</a></li>';
  }
  else
  {
    echo '<li><a href="#tabs-1">Sign In</a></li>';
		echo '<li><a href="#tabs-2">New User</a></li>';
  }
?>
     <li><a href="#tabs-3">Scoring</a></li>
	</ul>
  
  <?php if ($_POST['todo']!="update" && $_POST['todo']!="login" && $_POST['todo']!="create")
  {
  ?>
	<div id="tabs-1">
		<p>
 <form id="wallylog"
      
      method="post"
      action="sandbox2.php"
      onsubmit="return(wallylogsubmit());">
      
      
<?php
printformteaminputs($numTeams);
?>
      
    <input type="hidden" name="todo" value="login">


  <table id="passtable" align="center" border="0" cellpadding="1" cellspacing="0"> 
  <tr> 
<td colspan="2" align="center"> 
  <font size="-1"> 
  </font> 
  
<tr> 
  <td colspan="2" align="center"> 
  </td> 
</tr> 
<tr id="uname-row"> 
  <td nowrap="nowrap"> 
  <div align="right"> 
  <span> 
  Username:
  </span> 
  </div> 
  </td> 
  <td> 
 <input type="text" name="username"  id="username"
  size="18" value="" /> 
  </td> 
</tr> 
<tr> 
  <td></td> 
  <td align="left"> 
  </td> 
</tr> 
<tr id="password-row" class="enabled"> 
  <td align="right" nowrap="nowrap"> 
  <span> 
  Password:
  </span> 
  </td> 
  <td> 
  <input type="password"
   name="passwd" id="passwd"
  size="18" /> 
  </td> 
</tr> 
<tr> 
  <td> </td> 
  <td align="left"> 
  </td> 
</tr> 
 
 <tr>
 <td></td>
 <td>
 <input type="checkbox" name="subcurrent" value="yes" align="left">Submit current stack<br>
 </td>
 </tr>
<tr> 
  <td> 
  </td> 
  <td align="left"> 
  <input type="submit"  name="signIn"
           value="Log in"
                  /> 
  </td> 
</tr> 

  </table>
  </form>  
    
    </p>
	</div>
	<div id="tabs-2">
		<p> 
    
    <form id="wallycreate"
      
      method="post"
      action="sandbox2.php"
      onsubmit="return(wallycreatesubmit());">
      
      <input type="hidden" name="todo" value="create">

<?php
printformteaminputs($numTeams);
?>


  <table id="passtable" align="center" border="0" cellpadding="1" cellspacing="0" width="100%"> 
  <tr> 
<td colspan="2" align="center"> 
  <font size="-1"> 
  
  </font> 
  
<tr> 
  <td colspan="2" align="center"> 
  </td> 
</tr> 
<tr id="uname-row"> 
  <td nowrap="nowrap"> 
  <div align="right"> 
  <span> 
  Username*:
  </span> 
  </div> 
  </td> 
  <td> 
 <input type="text" name="username"  id="username"
  size="18" value=""> 
  </td> 
</tr> 

<tr id="fname-row"> 
  <td nowrap="nowrap"> 
  <div align="right"> 
  <span> 
  First name:
  </span> 
  </div> 
  </td> 
  <td> 
 <input type="text" name="fname"  id="fname"
  size="18" value="" /> 
  </td> 
</tr> 


<tr id="lname-row"> 
  <td nowrap="nowrap"> 
  <div align="right"> 
  <span> 
  Last name:
  </span> 
  </div> 
  </td> 
  <td> 
 <input type="text" name="lname"  id="lname"
  size="18" value="" /> 
  </td> 
</tr> 
<tr> 
  <td></td> 
  <td align="left"> 
  </td> 
</tr> 
<tr id="password-row" class="enabled"> 
  <td align="right" nowrap="nowrap"> 
  <span> 
  Password*:
  </span> 
  </td> 
  <td> 
  <input type="password"
   name="passwd" id="passwd"
  size="18" /> 
  </td> 
</tr> 
<tr id="password-row2" class="enabled"> 
  <td align="right" nowrap="nowrap"> 
  <span> 
  Confirm Password*:
  </span> 
  </td> 
  <td> 
  <input type="password"
   name="passwd2" id="passwd2"
  size="18" /> 
  </td> 
</tr> 
<tr> 
  <td> </td> 
  <td align="left"> 
  </td> 
</tr> 

<tr>
<td></td>
 <td>
 <input type="checkbox" name="subcurrent" value="yes" align="left">Submit current stack<br>
 <!--<input type="checkbox" name="subcurrent" value="yes" align="right">Submit current stack<br>-->
 </td>
 </tr>
 
<tr> 
  <td> 
  </td> 
  <td align="left"> 
  <input type="submit" name="create"
           value="Create account"
                  /> 
  </td> 
</tr> 

  </table>
  </form>
    
    
    
    </p>
</div>
<?php } ?>

<?php if ($_POST['todo']=="update" || $_POST['todo']=="login" || $_POST['todo']=="create")
{
?>
<div id="tabs-1">
		<p>
<form id="wallyupdate"
      
      method="post"
      action="sandbox2.php"
      onsubmit="return(wallyupdatesubmit());">
      
      
 <?php
printformteaminputs();
?>     
      <input type="hidden" name="todo" value="update">
     <?php  echo '<input type="hidden" name="username" value="'. $_POST['username'] . '" >'; ?>

        <input type="submit" name="update"
        value="Submit"/>
        
</form>
</div>


<?php } ?>

<div id="tabs-3"> 
<ul>
<li>For each team in your stack multiply their position in the stack by this week's result (a win: 1, a loss: -1, a tie: 0)</li>  
   <li> Take the score for each team in your stack and add them together</li>
    <li>The person with the highest score wins</li>
    <li>Oh yeah, position 1 is at the bottom of the stack, position 2 is 2nd from the bottom etc.. </li>
    </ul>
	</div> 

</div><!-- End demo -->
</td><td width="5%"></td><td></td></tr></table><!--should be the end of bigtable-->

</body>
</html>
