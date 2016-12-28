<?php 

include("db-config.php");
include("admin-include.php");
include("function_common.php");


if($_SESSION['adminlevel']>0)
{
//header("Location:".SITE_URL."/qp/admin-main.php");
//exit;
}


$msg = ""; $action = "Add";


//if(isset($_GET['del'])){

	//$id = $_GET['id'];
if(isset($_REQUEST['delBoq'])){

	$id = $_REQUEST['delBoqid'];
//echo $id;
//exit;

	$stmt11 = $dbcon->prepare("DELETE FROM bouquets WHERE id = ".$id."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt11->execute();
	
	$msg = "<span style='color:green'>Bouquet deleted Successfully</span>";
	
}


if(isset($_POST['Add_Submit'])){
	
	$bouquetname=$bouquetname2=trim($_POST['bouquetname']);
	$bouquetname=strtolower(preg_replace('/\s+/', '', $bouquetname));
	//echo $customername;
	//$customernames=array($customername,preg_replace('/\s+/', '', $customername),strtoupper($customername),strtolower($customername),ucfirst($customername));

//echo $customernames."\n";


	$metadata=trim($_POST['metadata']);
	$metadesc=trim($_POST['metadesc']);
	
	$status=trim($_POST['status']);
	

//echo $bouquetname.$metadata.$metadesc.$status;
//exit;
	//$authkey="";
	$date = gmdate("Y-m-d H:i:s");
	
	//$stmt21 = $dbcon->prepare('SELECT id FROM customer_info WHERE mobile = "'.$phone.'" OR customername = "'.$customername.'" OR email = "'.$email.'" OR channel_id = "'.$channel.'"', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	

	$userexit=0;
	//foreach($customernames as $customername){
	//echo $customername;
	$stmt21 = $dbcon->prepare('SELECT * FROM bouquets WHERE LOWER(REPLACE(name," ","")) = "'.$bouquetname.'" ', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
	$stmt21->execute();
	$result21 = $stmt21->fetchAll(PDO::FETCH_ASSOC);
	//print_r($result21)."\n";
	//$userexit = $userexit + count($result21);//}
	$userexit = count($result21);


//echo "yes:".$userexit;
//exit;
	if((int)$userexit > 0){
		
		$msg1="";

		$stmt212 = $dbcon->prepare('SELECT * FROM bouquets WHERE LOWER(REPLACE(name," ","")) = "'.$bouquetname.'"', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
		$stmt212->execute();
		$result212 = $stmt212->fetchAll(PDO::FETCH_ASSOC);
		$boqnameexist = count($result212);

		//echo "cust exist:".$custnameexist;
		if((int)$boqnameexist > 0){	
			$msg1.= "<i class='fa fa-arrow-right' aria-hidden='true'></i> Bouquet Name already exist <br>";}

		/*$stmt211 = $dbcon->prepare('SELECT * FROM customer_info WHERE channel_id = "'.$channel.'"', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
		$stmt211->execute();
		$result211 = $stmt211->fetchAll(PDO::FETCH_ASSOC);
		$chanexist = count($result211);	
//echo $userexit;	
//exit;
		//echo "channel exist:".$chanexist;
		if((int)$chanexist > 0){	
			$msg1.= "<i class='fa fa-arrow-right' aria-hidden='true'></i> Channel already exist <br>";}*/


		$msg = "<span id='msg' style='color:red'>".$msg1."</span>";
		//echo $msg;
		
	}else{	
		//echo "NEW";
		if ($_FILES["logofile"]["error"] > 0){
			$msg = "<span style='color:red'>Problem with Bouquet image. Please try again.</span>";
		}else{	
							
			$present_time = date("dmYHis");
			$image_extention = explode("/",$_FILES["logofile"]["type"]);
			$sNewFileName = $present_time.".".$image_extention[1];
											
			if(move_uploaded_file($_FILES["logofile"]["tmp_name"],"bouquet_logo/".$sNewFileName)){
				
				//insert query
				$sql22='INSERT INTO bouquets(name,image,meta_data,meta_description,status,updated_datetime) VALUES("'.$bouquetname2.'","'.$sNewFileName.'","'.$metadata.'","'.$metadesc.'","'.$status.'","'.$date.'")';				
				$stmt22 = $dbcon->prepare($sql22, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
				$res22=$stmt22->execute();
	if($res22)
	{
	//echo "Success...<br>";
	//echo "Inserted:".$dbcon->lastInsertId();
		
	
	}
	else
	{
	//echo "Failed";
	//print_r($stmt22->errorInfo(),true);
	}

				/*$customerloginlink = SITE_URL."/qp/customer-login.php";
		
				// Always set content-type when sending HTML email
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
				// More headers
				$headers .= 'From: <'.ADMIN_EMAIL.'>' . "\r\n";
		
				$subjectCustomer = "Your account info on Qezyplay.com as Broadcaster";
				$regards = "<p>Regards, <br>Admin - QezyPlay</p>";
				$bodyCustomer = "<p>Hi ".$customername.",</p>
				<p>Your account has been created in Qezyplay.com as Broadcaster</p>
				<p>Find below credentials to login with Broadcaster portal</p>
				<p>
				<b>Email:</b> $email<br>
				<b>Auth Key:</b> $authkey<br>
				</p>
				<p>Log in to your Broadcaster portal here: $customerloginlink</p>".$regards;	
		
				//mail Agent
				mail($email, $subjectCustomer, print_r($bodyCustomer,true), $headers);*/

				$msg = "<span style='color:green'>Bouquet added Successfully</span>";				
	
			} else{					
				$msg = "<span style='color:red'>Problem with Bouquet image. Please try again.</span>";
			}
			
		}			
	}
}


if(isset($_POST['Edit_Submit'])){

	$bouquetid=trim($_POST['bouquetid']);
	
	
	$bouquetname=trim($_POST['bouquetname']);
	$metadate=trim($_POST['metadata']);
	$metadesc=trim($_POST['metadesc']);	
	$status=trim($_POST['status']);
	$date = gmdate("Y-m-d H:i:s");
	
	//$stmt21 = $dbcon->prepare('SELECT id FROM customer_info WHERE id != '.$customerid.' AND (mobile = "'.$phone.'" OR customername = "'.$customername.'" OR email = "'.$email.'" OR channel_id = "'.$channel.'")', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
	$stmt21 = $dbcon->prepare('SELECT * FROM bouquets WHERE id != '.$bouquetid.' AND (name = "'.$bouquetname.'")', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt21->execute();
	$result21 = $stmt21->fetchAll(PDO::FETCH_ASSOC);	
	//$userexit = $result21['id']; 
	$userexit = count($result21);
	if((int)$userexit > 0){
		
		$msg1="";

		$stmt212 = $dbcon->prepare('SELECT * FROM bouquets WHERE id != '.$bouquetid.' AND (name = "'.$bouquetname.'")', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
		$stmt212->execute();
		$result212 = $stmt212->fetchAll(PDO::FETCH_ASSOC);
		$boqnameexist = count($result212);

		if((int)$boqnameexist > 0){	
			$msg1.= "<i class='fa fa-arrow-right' aria-hidden='true'></i> Bouquet Name already exist <br>";}

		/*$stmt211 = $dbcon->prepare('SELECT * FROM customer_info WHERE id != '.$customerid.' and channel_id = "'.$channel.'"', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
		$stmt211->execute();
		$result211 = $stmt211->fetchAll(PDO::FETCH_ASSOC);
		$chanexist = count($result211);	
//echo $userexit;	
//exit;
		 if((int)$chanexist > 0){	
			$msg1.= "<i class='fa fa-arrow-right' aria-hidden='true'></i> Channel already exist <br>";}*/


		$msg = "<span id='msg' style='color:red'>".$msg1."</span>";
		
	}else{
		
		if ($_FILES["logofile"]["error"] > 0 && $_FILES["logofile"]["error"] != "4"){
			$msg = "<span style='color:red'>Problem with Bouquet Image. Please try again.</span>";
		}else{	

			if($_FILES["logofile"]["name"] !=""){

				$select_q_content = "SELECT * FROM bouquets WHERE `id` = :id";
				$select_query = $dbcon->prepare($select_q_content);
				$select_query->bindParam(":id",$bouquetid);
				$select_query->execute();
				if($select_query->rowCount() > 0){
					$cData = $select_query->fetch(PDO::FETCH_ASSOC);

					if($cData["image"]!=""){
						$req_image_path = "bouquet_logo/".$cData["image"];
						if (file_exists($req_image_path)){
							unlink($req_image_path);
						}
					}		
				}
				
				$present_time = date("dmYHis");
				$image_extention = explode("/",$_FILES["logofile"]["type"]);
				$sNewFileName = $present_time.".".$image_extention[1];

				if(move_uploaded_file($_FILES["logofile"]["tmp_name"],"bouquet_logo/".$sNewFileName)){

					//update query
					$stmt22 = $dbcon->prepare('UPDATE bouquets SET name = "'.$bouquetname.'", meta_data = "'.$metadata.'", meta_description = "'.$meta_desc.'", image = "'.$sNewFileName.'", status = "'.$status.'",updated_datetime = "'.$date.'" WHERE id = '.$bouquetid, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	 
					$stmt22->execute();

					$msg = "<span style='color:green'>Bouquet updated Successfully</span>";

				} else{					
					$msg = "<span style='color:red'>Problem with bouquet image. Please try again.</span>";
				}

			}else{

				//update query
				$stmt22 = $dbcon->prepare('UPDATE bouquets SET name = "'.$bouquetname.'", meta_data = "'.$metadata.'", meta_description = "'.$meta_desc.'", status = "'.$status.'",updated_datetime = "'.$date.'" WHERE id = '.$bouquetid, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	 
				$stmt22->execute();

				$msg = "<span style='color:green'>Bouquet updated Successfully</span>";

			}								
			
		}								
	}		
}	


if(isset($_REQUEST['editBouquet'])){

	$id = $_REQUEST['editBouquetid'];		

//echo $id;
//exit;		

	$stmt1 = $dbcon->prepare("SELECT * FROM bouquets where id='".$id."'", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt1->execute();
	$result1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
	foreach($result1 as $row1){
		$bouquetid1=$row1['id'];
		$bouquetname1=$row1['name'];
		$metadata1=$row1['meta_data'];
		$metadesc1=$row1['meta_description'];
		$logo1=$row1['image'];
		$status1=$row1['status'];
	}

		
	$action = "Edit";
	//echo $action;

}	

include("header-admin.php");
?>


<style>
.xoouserultra-field-value, .xoouserultra-field-type{
	width: unset !important;
	padding: 5px;
}
.xoouserultra-field-value input[type="file"]{
	display:block;
}

form[name="f_timezone"] {
    display: none;
}
.link_btn:hover{
    background: #000;
    color: #fff;
    border: solid 1px #000;
}

.link_btn:visited{color:white !important}

.link_btn{
    border-radius: 3px;
    line-height: 2.75;
    text-align: center;
    margin: 5px;
    padding: 6.5px 25px;
    outline: none;
    background: #4141a0;
    border: solid 1px #4141a0;
    color: #fff;
    transition: all .2s ease;
    text-decoration:none;
	border-radius:5px;
}
</style>

<div id="content" role="main" style="min-height:500px;">
	
	<div class="pmpro_box" id="pmpro_account-invoices" style="margin: 2% 5%;">
		<h2 style="text-align:center">Bouquet Management</h2>
<?php		if($_SESSION['adminlevel']<1)
{ ?>
		<div class="xoouserultra-wrap" style="max-width:100% !important;">
						
					<div class="panel panel-default">
   					<div align="center" style="font-size: 16px;
    color: #4141a0;margin:0 auto" class="panel-heading"><span style="float:left"><?php echo $action;?> Bouquet</span><div class="msg" align="center" style="display:inline-block"><h4><?php echo $msg;?></h4></div></div>
   					 <div class="panel-body">
					<form id="xoouserultra-login-form-1" method='post' onsubmit="return callsubmit();" enctype="multipart/form-data">
						<input type="hidden" id="bouquetid" name="bouquetid" value="<?php echo $id; ?>">
						<div class="xoouserultra-field xoouserultra-edit xoouserultra-edit-show">
							<label class="xoouserultra-field-type">	
								<span>Bouquet Name:</span>
							</label>
							<div class="xoouserultra-field-value">
								<input onclick="clearError()" type="text" alt="" value="<?php echo @$bouquetname1;?>" id="bouquetname" name="bouquetname" />	
							</div>
							&nbsp;&nbsp;&nbsp;
							<label class="xoouserultra-field-type">	
								<span>Meta Data:</span>
							</label>
							<div class="xoouserultra-field-value">
								<input  onclick="clearError()" type="text" alt="" placeholder="Ex:Bengali" value="<?php echo @$metadata1;?>" id="metadata" name="metadata"/>
							</div>
							&nbsp;&nbsp;&nbsp;
							<label class="xoouserultra-field-type">	
								<span>Meta Description:</span>
							</label>
							<div class="xoouserultra-field-value">
								<textarea rows="4" cols="50" onclick="clearError()" alt="" placeholder="Ex:Bengali Bouquet consists of channels from both WestBengal and Bangladesh" value="<?php echo @$metadesc1;?>"  id="metadesc" name="metadesc" ></textarea>
							</div>
						</div>		
						<div class="xoouserultra-clear"></div>
						<div class="xoouserultra-field xoouserultra-edit xoouserultra-edit-show">
						
							<label class="xoouserultra-field-type">	
								<span>Image(path):</span>
							</label>
							<div class="xoouserultra-field-value">
								<?php if($logo1 !="" ) { echo '<img src="bouquet_logo/'.@$logo1.'" style="height:100px;width:200px;">'; } ?>
								<input onclick="clearError()" type="file" accept="image/*" id="logofile" name="logofile">
							</div>
							&nbsp;&nbsp;&nbsp;

							<label class="xoouserultra-field-type">	
								<span>Status:</span>
							</label>
							<div class="xoouserultra-field-value">
								<select name="status" alt="" id="status" onclick="clearError()">
									<option value="">-Set Bouquet Status-</option>
									<?php
									//$selected = ($row['status'] == $status1) ? "selected='selected'" : "";									
									for($i=0;$i<=1;$i++)
									{
										$selected = ($i == $status1) ? "selected='selected'" : "";
										$ival = ($i == 1) ? "Active" : "Inactive";
									echo "<option value='".$i."'".$selected.">".$ival."</option>";
									}
									
									?>
								</select>
							</div>
							&nbsp;&nbsp;&nbsp;
							
							<div class="xoouserultra-field-value" style="padding-left: 10px;">
								<?php if($action=="Edit"){?>
								<input type="hidden" name="editBoq"  value="true" />
								<input type="hidden" name="editBoqid"  value="<?php echo @$bouquetid1;?>" />								<?php } ?>
								<input type="submit" name="<?php echo $action;?>_Submit"  value="Submit" />
								<?php if($action=="Edit"){?>
								
								<a class="link_btn" href="<?php echo SITE_URL.'/qp/admin_bouquet_management.php'?>">Back</a>
   								<?php } ?>
							
							</div>
							&nbsp;&nbsp;&nbsp;
							<div class="xoouserultra-field-value" style="padding-left: 10px;">
								<div id='error' style='color:red;padding-left:10px;'";></div>
							</div>
							
						</div>						
						<div class="xoouserultra-clear"></div>
					</form></div>
    					
  					<!-- </div> -->
				<!--  /div> -->
			</div>

		</div>
<?php }
?>
		<form id="frm2" method="post">
		<table style="overflow-x:auto;min-width:50%;max-width:100% !important;" width="100%" border="0" cellspacing="0" cellpadding="0">
			<thead>
				<tr>					
					<th>Bouquet Name</th>
					<th>Logo</th>
					<th>Meta Data</th>
					<th>Meta Description</th>					
					<th>Status</th>
					
					
					<?php		if($_SESSION['adminlevel']<1)
{ ?> <th>Action</th> <?php } ?>
				</tr>
			</thead>
			<tbody class="ui-sortable">
			<?php
			$stmt4 = $dbcon->prepare("SELECT * FROM bouquets", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
			$stmt4->execute();
			$result4 = $stmt4->fetchAll(PDO::FETCH_ASSOC);

			foreach($result4 as $row){

				$id=$row['id'];
				$bouquetname=$row['name'];
				$metadata=$row['meta_data'];
				$metadesc=$row['meta_description'];
				
				$logo = trim($row['imageUrl'],"'");
				$stat = $row['status'];
				if($stat==1)
				{$status="Active";}
				if($stat==0)
				{$status="Inactive";}

				if($row['is_admin'] != 1){
			?>
				<tr style="" class="ui-sortable-handle">							
				<td style="width: 342px;color:blue;" class="level_name"><a style="color:blue;"><?php echo $bouquetname;?></a></td>
				<td style="width: 192px;"><img height="50px" width="150px" src="<?php echo $logo;?>"></td>
							<!-- src="bouquet_logo/<?php echo $logo;?>" -->
				<td style="width: 184px;"><?php echo $metadata;?></td>				
				<td style="width: 192px;"><?php echo $metadesc;?></td>
				<td style="width: 192px;"><?php echo $status;?></td>	
				
				
<?php		if($_SESSION['adminlevel']<1)
{ ?>

				
				<td style="width: 332px;">


<a style="cursor:pointer;color:blue;" id="editbouquet" name="editbouquet" onclick="return confirmEditBouquet(<?php echo $id;?>)">Edit</a>
&nbsp;
<a style="cursor:pointer;color:blue;" id="removebouquet" name="removebouquet" onclick="return confirmDelBouquet(<?php echo $id;?>)">Remove</a>	


</td> <?php } ?>
				</tr>
			<?php } }?>
			</tbody>
		</table></form>
	</div>
</div>


<script>


function validateEmail(email) {
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(email);
}

function validatePhone(phone) {
	var re = /^([0|\+[0-9]{1,5})?([1-9][0-9]{9})$/;
	return re.test(phone);
}

function callsubmit(){	
	return true;
	var customerid = jQuery("#customerid").val();
	var email = jQuery("#email").val();
	var customername = jQuery("#customername").val();
	var phone = jQuery("#phone").val();
	var logo = jQuery("#logofile").val();
	var channel = jQuery("#channel").val();


	if(customername == ""){
		jQuery("#error").html("Please enter Broadcaster name");return false;		
	}
	
			
	if(phone == ""){
		jQuery("#error").html("Please enter phone no");
		return false;		
	}else if (!validatePhone(phone)) {

		jQuery("#error").html("Please enter valid phone no");
		return false;
			
			}


	 if(email == ""){
		jQuery("#error").html("Please enter email address");	
		return false;	
	}else if (!validateEmail(email)) {
		jQuery("#error").html("Please enter valid email address");
		return false;	
	}

	 if(logo == "" && customerid == ""){
		jQuery("#error").html("Please select logo file");	return false;		
	}

	 if(channel == ""){
		jQuery("#error").html("Please choose channel");		return false;	
	}

	if(customername != "" && phone != "" && email != "" && (logo != "" || customerid != "") && channel != ""){
		if (validateEmail(email)) {
			if (validatePhone(phone)) {
				return true;
			
			} else {
				jQuery("#error").html("Please enter valid phone no");
			}
			
		} else {
			jQuery("#error").html("Please enter valid email address");
		}
	}
	return false;
}





		function clearError()
		{

		jQuery("#error").html("");
		jQuery(".msg h4").html("");		
	
		}	
	
function confirmDelBouquet(Delid)
{

 swal({
 		title:' ', 
  text: 'Do you really want to remove this Bouquet?',
  type: 'warning',
  
  showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Remove",   cancelButtonText: "Cancel",  closeOnCancel: true
},function(){ 

var input1 = jQuery("<input>")
               .attr("type", "hidden")
               .attr("name", "delBoqid").val(Delid);
var input2 = jQuery("<input>")
               .attr("type", "hidden")
               .attr("name", "delBoq").val(true);
jQuery('#frm2').append(jQuery(input1));
jQuery('#frm2').append(jQuery(input2));
 jQuery("#frm2").submit();

}); 

}



function confirmEditBouquet(Editid)
{

 swal({
 		title:' ', 
  text: 'Do you want to edit this Bouquet?',
  type: 'warning',
  
  showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Edit",   cancelButtonText: "Cancel",  closeOnCancel: true
},function(){ 

var input1 = jQuery("<input>")
               .attr("type", "hidden")
               .attr("name", "editBouquetid").val(Editid);
var input2 = jQuery("<input>")
               .attr("type", "hidden")
               .attr("name", "editBouquet").val(true);
jQuery('#frm2').append(jQuery(input1));
jQuery('#frm2').append(jQuery(input2));
 jQuery("#frm2").submit();

}); 

}




setTimeout(function(){
  jQuery("#msg").html("");
}, 3000);
</script>
<?php include("footer-admin.php"); ?>
