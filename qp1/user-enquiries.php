<?php 
include('header.php');
?>
<article class="content items-list-page">
<?php

$msg = ""; $action = "Add";

$num_rec_per_page=10;
if (isset($_GET["page"])) 
{ $page  = $_GET["page"];

$sno= $_GET["last"]-1;


	if (isset($_GET["tb"])) {
			if($_GET["tb"]=="C"){$userC=1;
			}
			elseif($_GET["tb"]=="F"){$userF=1;
				}
		}
} 
else { $page=1;

 }; 
$start_from = ($page-1) * $num_rec_per_page; 


if(isset($_GET['delF'])){

	$id = $_GET['id'];

	$stmt11 = $dbcon->prepare("DELETE FROM user_feedback WHERE id = ".$id."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt11->execute();
	
	/*echo '<script>
		document.getElementById("UC").setAttribute("class","tablinks");
		document.getElementById("UF").setAttribute("class","tablinks active");
		document.getElementById("UserContact").style.display="none !important";
		document.getElementById("UserFeedback").style.display="block !important";
</script>';*/
	$msg = "<span style='color:green'>Feedback Info deleted Successfully</span>";

		$userF=1;
//echo '<script>window.location.href="http://ideabytestraining.com/newqezyplay/qp/user_forms.php";</script>';
	
}

if(isset($_GET['delC'])){

	$id = $_GET['id'];

	$stmt11 = $dbcon->prepare("DELETE FROM user_contact WHERE id = ".$id."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt11->execute();
	
/* echo '<script>
		document.getElementById("UF").setAttribute("class","tablinks");
		document.getElementById("UC").setAttribute("class","tablinks active");
		document.getElementById("UserFeedback").style.display="none !important";
		document.getElementById("UserContact").style.display="block !important";
</script>';*/

	$msg = "<span style='color:green'>Contact Info deleted Successfully</span>";
		
	$userC=1;

	//echo '<script>window.location.href="http://ideabytestraining.com/newqezyplay/qp/user_forms.php";</script>';
}


if(isset($_GET['readF'])){

		$id = $_GET['id'];


		$stmt22 = $dbcon->prepare('UPDATE user_feedback SET status = "read" WHERE id = '.$id, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	 
		$stmt22->execute();

			$userF=1;	
	
	
	}	


if(isset($_GET['readC'])){

		$id = $_GET['id'];

		$stmt22 = $dbcon->prepare('UPDATE user_contact SET status = "read" WHERE id = '.$id, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	 
		$stmt22->execute();


				
		$userC=1;
	
	}	


echo '<style>

.mx{max-width:80%;margin:10px auto !important;}
 /* Style the list */
ul.tab {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
}

/* Float the list items side by side */
ul.tab li {float: left;}

/* Style the links inside the list items */
ul.tab li a {
    display: inline-block;
    color: black;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
    transition: 0.3s;
    font-size: 17px;
}

/* Change background color of links on hover */
ul.tab li a:hover {background-color: #ddd;}

/* Create an active/current tablink class */
ul.tab li a:focus, .active {background-color: #ccc;}

/* Style the tab content */
.tabcontent {
    display: none;
       border-top: none;
min-height:500px;
}

.show
{
display:block !important;
}
.hide
{
display:none !important;
}

.link_btn:hover{
    background: #000;
    color: #fff;
    border: solid 1px #000;
}

.link_btn{
   border-radius: 3px;
    line-height: 1.5;
    text-align: center;
    margin: 1px;
    padding: 4px 8px;
    outline: none;
    background: #4141a0;
    border: solid 1px #4141a0;
    color: #fff;
    transition: all .2s ease;
    text-decoration: none;
}


th{color:whitesmoke !important;text-align: center;border: 1px solid rgba(255,255,255,.15) !important;}
thead{background-color: #4141a0}
table td {
    color: #444;
border-bottom: 1px solid #777 !important;
	text-align: center;}
td:nth-child(even) {background: #FFF}
td:nth-child(odd) {background: #DDD}
form[name="f_timezone"] {
    display: none;
}
</style>';

echo '<ul class="tab mx">
  <li><a id="UC" href="#" class="tablinks active" onclick="userForms(event, \'UserContact\')">User Contact List</a></li>
  <li><a id="UF" href="#" class="tablinks" onclick="userForms(event, \'UserFeedback\')">User Feedback List</a></li>
 </ul>';

echo '<div id="UserContact" class="tabcontent mx" style="display:block">
<div class="msg" align="center" style="display:block"><h4><?php echo $msg;?></h4></div> 	
	<br />

	<div class="clear"></div>
<form>
		<table class="widefat membership-levels" style="width:100% !important;max-width:100% !important;">
			<thead>
				<tr>	
					<th>Read Status</th>				
					<th>First Name</th>
					<th>Last Name</th>
					<th>E-mail</th>
					<th>Phone</th>
					<th>Address</th>
					<th>Message</th>
					<th>Action</th>
					
				</tr>
			</thead>
			<tbody class="ui-sortable">';
			


			$stmtT1 = $dbcon->prepare("SELECT * FROM user_contact", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$stmtT1->execute();
			$resT1 = $stmtT1->fetchAll(PDO::FETCH_ASSOC);	
			$stmt1 = $dbcon->prepare("SELECT * FROM user_contact LIMIT $start_from, $num_rec_per_page", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
			$stmt1->execute();
			$result1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

			foreach($result1 as $row1){

				$id=$row1['id'];
				$fisrtname=$row1['first_name'];
				$lastname=$row1['last_name'];
				$email=$row1['email'];
				$phone=$row1['phone'];
				$address=$row1['address'];
				$message=$row1['message'];

				$status=$row1['status'];

				if($status=="read")
				{
				//echo "<style>#C-".$id."{background-color:green !important;color:black !important;}</style>";
				//echo "<style>#readContact-".$id."{display:none !important}</style>";
				echo "<style>#readContact-".$id."{  pointer-events: none;  cursor: default;opacity:0.65}#gr_imgC-".$id."{display:block !important}</style>";
				
				}
				else
				{
				//echo "<style>#C-".$id."{background-color:yellow !important;color:red !important;}</style>";
				}
				
		
			echo 	'<tr id="C-'.$id.'" style="" class="ui-sortable-handle">			
				<td style="width: 50px;" ><span id="gr_imgC-'.$id.'" src="'.SITE_URL.'/qp/images/green_tick.png" style="display:none;width:30px;"><em class="fa fa-check"></em></span></td>			
				<td style="width: 192px;" class="level_name">'.$fisrtname.'</td>
				<td style="width: 192px;" class="level_name">'.$lastname.'</td>
				<td style="width: 192px;">'.$email.'</td>
				<td style="width: 184px;">'.$phone.'</td>
				<td style="width: 342px;">'.$address.'</td>
				<td style="width: 342px;">'.$message.'</td>
				<td style="width: 350px;"><a class="" style="cursor: pointer;color:#2a85e8 !important" id="readContact-'.$id.'" href="user_forms.php?readC=true&id='.$id.'" title="read" name="Read-'.$id.'" class="button-primary"><!-- img src="'.SITE_URL.'/qp/images/book2.jpg" width="50px" title="Read" --> <em class="fa fa-eye-slash" title="Read"></em> </a>				<a title="Reply" href="mailto:'.$email.'?subject=QezyPlay%20Contact%20Reply&amp;">
<!-- img src="'.SITE_URL.'/qp/images/email-logo.png" width="50px" title="Reply"--> <em class="fa fa-envelope" title="Reply"></em></a>
				<a class="" style="cursor: pointer;color:#2a85e8 !important;" title="delete" name="removeContact-'.$id.'" id="removeContact-'.$id.'" onclick="callConfirmationC(\'user_forms.php?delC=true&id='.$id.'\');" class="button-secondary"><!-- img src="'.SITE_URL.'/qp/images/remove.jpg" width="37px" title="Remove" --><em class="fa fa-trash-o" title="Remove"></em></a></td>
				</tr>';
			 } 
			
			echo '</tbody>
		</table></form><br />';

$total_records = count($resT1);  //count number of records
$total_pages = ceil($total_records / $num_rec_per_page); 
echo "<span style='color:black;font-size:16px'>Total:$total_records </span> <a class='link_btn' href='?page=1&tb=C'>".'<'."</a> "; // Goto 1st page  
for ($i=1; $i<=$total_pages; $i++) { 
		if(isset($_SESSION['date']) and $_SESSION['date']!="")
		 echo "<a class='link_btn' href='?page=".$i."&tb=C&this_date=".$_SESSION['date']."'>".$i."</a> ";
		elseif(isset($_SESSION['days']) and $_SESSION['days']!="")
		 echo "<a class='link_btn' href='?page=".$i."&tb=C&this_dateInt=".$_SESSION['days']."'>".$i."</a> ";  
		else
            echo "<a class='link_btn' href='?page=".$i."&tb=C'>".$i."</a> "; 
}; 

echo "<a class='link_btn' href='?page=$total_pages&tb=C'>".'>'."</a> "; // Goto last page
	echo '</div>
	<div class="clear"></div>
	 <div id="UserFeedback" class="tabcontent mx">
		<div class="msg" align="center" style="display:block"><h4>'.$msg.'</h4></div> 	
	<br />

	<div class="clear"></div>
		<form>
		<table class="widefat membership-levels" style="width:100% !important;max-width:100% !important;">
			<thead>
				<tr>	<th>Read Status</th>					
					<th>Name</th>
					<th>Gender</th>
					<th>Country</th>
					<th>E-mail</th>
					<th>Phone</th>
					<th>Qezy Platform</th>
					<th>Channel</th>
					<th>Message</th>
					<th>Action</th>
					
				</tr>
			</thead>
			<tbody class="ui-sortable">';
			
			$stmtT2 = $dbcon->prepare("SELECT * FROM user_feedback", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$stmtT2->execute();
			$resT2 = $stmtT2->fetchAll(PDO::FETCH_ASSOC);	
			$stmt2 = $dbcon->prepare("SELECT * FROM user_feedback  LIMIT $start_from, $num_rec_per_page", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
			$stmt2->execute();
			$result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

			foreach($result2 as $row2){

			
				$id=$row2['id'];
				$name=$row2['name'];
				$gender=$row2['gender'];
				$country=$row2['country'];
				$email=$row2['email'];
				$phone=$row2['phone'];
				$qezy_platform=$row2['qezy_platform'];
				$channel=$row2['channel'];
				$message=$row2['message'];
				$status=$row2['status'];

				if($status=="read")
				{
				echo "<style>#F-".$id."{background-color:green !important;color:black !important;}</style>";
				//echo "<style>#readFeedback-".$id."{display:none !important}</style>";
				echo "<style>#readFeedback-".$id."{  pointer-events: none;  cursor: default;opacity:0.65}#gr_imgF-".$id."{display:block !important}</style>";
				}
				else
				{
				//echo "<style>#F-".$id."{background-color:yellow !important;color:red !important;}</style>";
				}
				
		
			echo 	'<tr id="F-'.$id.'" style="" class="ui-sortable-handle">			
				<td style="width: 50px;" ><span id="gr_imgF-'.$id.'" src="'.SITE_URL.'/qp/images/green_tick.png" style="display:none;width:30px;"><em class="fa fa-check"></em></span></td>			
				<td style="width: 192px;" class="level_name">'.$name.'</td>
				<td style="width: 192px;" class="level_name">'.$gender.'</td>
				<td style="width: 192px;" class="level_name">'.$country.'</td>
				<td style="width: 192px;">'.$email.'</td>
				<td style="width: 184px;">'.$phone.'</td>
				<td style="width: 192px;">'.$qezy_platform.'</td>
				<td style="width: 192px;">'.$channel.'</td>
				<td style="width: 342px;">'.$message.'</td>
				<td style="width: 350px;"><a class="" style="cursor: pointer;color:#2a85e8 !important" id="readFeedback-'.$id.'" href="user_forms.php?readF=true&id='.$id.'" title="read" name="Read-'.$id.'" class="button-primary"><em title="Read" class="fa fa-eye-slash"></em></a>
				<a href="mailto:'.$email.'?subject=QezyPlay%20Contact%20Reply&amp;">
<em class="fa fa-envelope" title="Reply"></em></a>
				<a class="" style="cursor: pointer;color:#2a85e8 !important" title="delete" name="removeFeedback-'.$id.'" id="removeFeedback-'.$id.'" onclick="callConfirmationF(\'user_forms.php?delF=true&id='.$id.'\');" class="button-secondary"><em class="fa fa-trash-o" title="Remove"></em></a></td>
				
				</tr>';
			 } 
			
			echo '</tbody>
		</table></form> <br />';

$total_records = count($resT2);  //count number of records
$total_pages = ceil($total_records / $num_rec_per_page); 
echo "<span style='color:black;font-size:16px'>Total:$total_records </span> <a class='link_btn' href='?page=1&tb=F'>".'<'."</a> "; // Goto 1st page  
for ($i=1; $i<=$total_pages; $i++) { 
		if(isset($_SESSION['date']) and $_SESSION['date']!="")
		 echo "<a class='link_btn' href='?page=".$i."&tb=F&this_date=".$_SESSION['date']."'>".$i."</a> ";
		elseif(isset($_SESSION['days']) and $_SESSION['days']!="")
		 echo "<a class='link_btn' href='?page=".$i."&tb=F&this_dateInt=".$_SESSION['days']."'>".$i."</a> ";  
		else
            echo "<a class='link_btn' href='?page=".$i."&tb=F'>".$i."</a> "; 
}; 

echo "<a class='link_btn' href='?page=$total_pages&tb=F'>".'>'."</a> "; // Goto last page
echo '	</div>';

if($userC==1)
{
echo '<script>
		document.getElementById("UF").setAttribute("class","tablinks");
		document.getElementById("UC").setAttribute("class","tablinks active");
		document.getElementById("UserContact").setAttribute("class","tabcontent mx show");
		document.getElementById("UserFeedback").setAttribute("class","tabcontent mx hide");
</script>';
}

if($userF==1)
{
echo '<script>
		document.getElementById("UC").setAttribute("class","tablinks");
		document.getElementById("UF").setAttribute("class","tablinks active");
		document.getElementById("UserFeedback").setAttribute("class","tabcontent mx show");
		document.getElementById("UserContact").setAttribute("class","tabcontent mx hide");
		
		
</script>';
}		

echo '<script>
function userForms(evt, cityName) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

	ele=document.getElementsByClassName("tabcontent mx show");
	 for (i = 0; i < ele.length; i++) {
        ele[i].setAttribute("class","tabcontent mx hide");;
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the link that opened the tab
    document.getElementById(cityName).style.display = "block";
	document.getElementById(cityName).setAttribute("class","tabcontent mx show");
    evt.currentTarget.className += " active";

}
</script>';
echo '
<script>
	function callConfirmationF(url){

		var ans = confirm("Sure, do you want to delete this feedback info?");
		if(ans){
			window.location.href = url;
		}
	}


	function callConfirmationC(url){

		var ans = confirm("Sure, do you want to delete this contact info?");
		if(ans){
			window.location.href = url;
		}
	}

setTimeout(function(){
jQuery(".msg").slideUp();
},3000);
	</script>


';

?>
</article>
<?php
include('footer.php');
?>
