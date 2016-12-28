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



if(isset($_REQUEST['delChannel'])){

	$id = $_REQUEST['delChannelid'];
//echo $id;
//exit;

	$stmt11 = $dbcon->prepare("DELETE FROM channels WHERE id = ".$id."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt11->execute();
	
	$msg = "<span style='color:green'>Channel deleted Successfully</span>";
	
}


if(isset($_POST['Add_Submit'])){
	
	$channelname=$channelname2=trim($_POST['channelname']);
	$channelname=strtolower(preg_replace('/\s+/', '', $channelname));

	
	$url=trim($_POST['url']);
	$octo_js=trim($_POST['octo_js']);

	$metadata=trim($_POST['metadata']);
	$metadesc=trim($_POST['metadesc']);
	
	$status=trim($_POST['status']);
	

echo $channelname.$metadata.$metadesc.$status.$url.$octo_js;
//exit;
	
	$date = gmdate("Y-m-d H:i:s");
	
	

	$userexit=0;
	//foreach($customernames as $customername){
	//echo $customername;
	$stmt21 = $dbcon->prepare('SELECT * FROM channels WHERE LOWER(REPLACE(name," ","")) = "'.$channelname.'" OR url = "'.$url.'" OR octo_js = "'.$octo_js.'"', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
	$stmt21->execute();
	$result21 = $stmt21->fetchAll(PDO::FETCH_ASSOC);
	//print_r($result21)."\n";
	//$userexit = $userexit + count($result21);//}
	$userexit = count($result21);


echo "yes:".$userexit;
//exit;
	if((int)$userexit > 0){
		
		$msg1="";

		$stmt212 = $dbcon->prepare('SELECT * FROM channels WHERE LOWER(REPLACE(name," ","")) = "'.$channelname.'"', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
		$stmt212->execute();
		$result212 = $stmt212->fetchAll(PDO::FETCH_ASSOC);
		$channameexist = count($result212);

		//echo "cust exist:".$custnameexist;
		if((int)$channameexist > 0){	
			$msg1.= "<i class='fa fa-arrow-right' aria-hidden='true'></i> Channel Name already exist <br>";}

		$stmt211 = $dbcon->prepare('SELECT * FROM channels WHERE url = "'.$url.'"', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
		$stmt211->execute();
		$result211 = $stmt211->fetchAll(PDO::FETCH_ASSOC);
		$urlexist = count($result211);	
//echo $userexit;	
//exit;
		//echo "channel exist:".$chanexist;
		if((int)$urlexist > 0){	
			$msg1.= "<i class='fa fa-arrow-right' aria-hidden='true'></i> Channel URL already exist <br>";}

		$stmt213 = $dbcon->prepare('SELECT * FROM channels WHERE octo_js = "'.$octo_js.'"', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
		$stmt213->execute();
		$result213 = $stmt213->fetchAll(PDO::FETCH_ASSOC);
		$octo_jsexist = count($result213);	
//echo $userexit;	
//exit;
		//echo "channel exist:".$chanexist;
		if((int)$octo_jsexist > 0){	
			$msg1.= "<i class='fa fa-arrow-right' aria-hidden='true'></i> Channel Octo js already exist <br>";}


		$msg = "<span id='msg' style='color:red'>".$msg1."</span>";
		//echo $msg;
		
	}else{	
		//echo "NEW";
		if ($_FILES["logofile"]["error"] > 0){
			$msg = "<span style='color:red'>Problem with Channel logo. Please try again.</span>";
		}else{	
							
			$present_time = date("dmYHis");
			$image_extention = explode("/",$_FILES["logofile"]["type"]);
			$sNewFileName = $present_time.".".$image_extention[1];
											
			if(move_uploaded_file($_FILES["logofile"]["tmp_name"],"channel_logo/".$sNewFileName)){
				
				//insert query
				$sql22='INSERT INTO channels(name,url,octo_js,image,meta_data,meta_description,status,updated_datetime) VALUES("'.$channelname2.'","'.$url.'","'.$octo_js.'","'.$sNewFileName.'","'.$metadata.'","'.$metadesc.'","'.$status.'","'.$date.'")';				
				$stmt22 = $dbcon->prepare($sql22, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
				$res22=$stmt22->execute();
	if($res22)
	{
	echo "Success...<br>";
	echo "Inserted:".$dbcon->lastInsertId();
		
	
	}
	else
	{
	echo "Failed";
	print_r($stmt22->errorInfo(),true);
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

				$msg = "<span style='color:green'>Channel added Successfully</span>";				
	
			} else{					
				$msg = "<span style='color:red'>Problem with Channel logo. Please try again.</span>";
			}
			
		}			
	}
}


if(isset($_POST['Edit_Submit'])){

	$channelid=trim($_POST['channelid']);
	
	
	$channelname=trim($_POST['channelname']);

	$url=trim($_POST['url']);
	$octo_js=trim($_POST['octo_js']);	

	$metadate=trim($_POST['metadata']);
	$metadesc=trim($_POST['metadesc']);	
	$status=trim($_POST['status']);
	$date = gmdate("Y-m-d H:i:s");
	
	//$stmt21 = $dbcon->prepare('SELECT id FROM customer_info WHERE id != '.$customerid.' AND (mobile = "'.$phone.'" OR customername = "'.$customername.'" OR email = "'.$email.'" OR channel_id = "'.$channel.'")', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
	$stmt21 = $dbcon->prepare('SELECT * FROM channels WHERE id != '.$channelid.' AND (name = "'.$channelname.'" OR url = "'.$url.'" OR octo_js = "'.$octo_js.'")', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt21->execute();
	$result21 = $stmt21->fetchAll(PDO::FETCH_ASSOC);	
	//$userexit = $result21['id']; 
	$userexit = count($result21);
	if((int)$userexit > 0){
		
		$msg1="";

		$stmt212 = $dbcon->prepare('SELECT * FROM channels WHERE id != '.$channelid.' AND (name = "'.$channelname.'")', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
		$stmt212->execute();
		$result212 = $stmt212->fetchAll(PDO::FETCH_ASSOC);
		$channameexist = count($result212);

		if((int)$channameexist > 0){	
			$msg1.= "<i class='fa fa-arrow-right' aria-hidden='true'></i> Channel Name already exist <br>";}

		$stmt211 = $dbcon->prepare('SELECT * FROM channels WHERE id != '.$channelid.' AND url = "'.$url.'"', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
		$stmt211->execute();
		$result211 = $stmt211->fetchAll(PDO::FETCH_ASSOC);
		$urlexist = count($result211);	
//echo $userexit;	
//exit;
		//echo "channel exist:".$chanexist;
		if((int)$urlexist > 0){	
			$msg1.= "<i class='fa fa-arrow-right' aria-hidden='true'></i> Channel URL already exist <br>";}

		$stmt213 = $dbcon->prepare('SELECT * FROM channels WHERE octo_js = "'.$octo_js.'"', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
		$stmt213->execute();
		$result213 = $stmt213->fetchAll(PDO::FETCH_ASSOC);
		$octo_jsexist = count($result213);	
//echo $userexit;	
//exit;
		//echo "channel exist:".$chanexist;
		if((int)$octo_jsexist > 0){	
			$msg1.= "<i class='fa fa-arrow-right' aria-hidden='true'></i> Channel Octo js already exist <br>";}


		$msg = "<span id='msg' style='color:red'>".$msg1."</span>";
		
	}else{
		
		if ($_FILES["logofile"]["error"] > 0 && $_FILES["logofile"]["error"] != "4"){
			$msg = "<span style='color:red'>Problem with Channel Logo. Please try again.</span>";
		}else{	

			if($_FILES["logofile"]["name"] !=""){

				$select_q_content = "SELECT * FROM channels WHERE `id` = :id";
				$select_query = $dbcon->prepare($select_q_content);
				$select_query->bindParam(":id",$channelid);
				$select_query->execute();
				if($select_query->rowCount() > 0){
					$cData = $select_query->fetch(PDO::FETCH_ASSOC);

					if($cData["image"]!=""){
						$req_image_path = "channel_logo/".$cData["image"];
						if (file_exists($req_image_path)){
							unlink($req_image_path);
						}
					}		
				}
				
				$present_time = date("dmYHis");
				$image_extention = explode("/",$_FILES["logofile"]["type"]);
				$sNewFileName = $present_time.".".$image_extention[1];

				if(move_uploaded_file($_FILES["logofile"]["tmp_name"],"channel_logo/".$sNewFileName)){

					//update query
					$stmt22 = $dbcon->prepare('UPDATE channels SET name = "'.$channelname.'", url = "'.$url.'",octo_js = "'.$octo_js.'",meta_data = "'.$metadata.'", meta_description = "'.$meta_desc.'", image = "'.$sNewFileName.'", status = "'.$status.'",updated_datetime = "'.$date.'" WHERE id = '.$channelid, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	 
					$stmt22->execute();

					$msg = "<span style='color:green'>Channel updated Successfully</span>";

				} else{					
					$msg = "<span style='color:red'>Problem with channel logo. Please try again.</span>";
				}

			}else{

				//update query
				$stmt22 = $dbcon->prepare('UPDATE channels SET name = "'.$channelname.'", url = "'.$url.'",octo_js = "'.$octo_js.'", meta_data = "'.$metadata.'", meta_description = "'.$meta_desc.'", status = "'.$status.'",updated_datetime = "'.$date.'" WHERE id = '.$channelid, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	 
				$stmt22->execute();

				$msg = "<span style='color:green'>Channel updated Successfully</span>";

			}								
			
		}								
	}		
}	


if(isset($_REQUEST['editChannel'])){

	$id = $_REQUEST['editChannelid'];		

//echo $id;
//exit;		

	$stmt1 = $dbcon->prepare("SELECT * FROM channels where id='".$id."'", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt1->execute();
	$result1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
	foreach($result1 as $row1){
		$channelid1=$row1['id'];
		$channelname1=$row1['name'];

		$url=trim($_POST['url']);
	$octo_js=trim($_POST['octo_js']);

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
		<h2 style="text-align:center">Channel Management</h2>
<?php		if($_SESSION['adminlevel']<1)
{ ?>
		<div class="xoouserultra-wrap" style="max-width:100% !important;">
						
					<div class="panel panel-default">
   					<div align="center" style="font-size: 16px;
    color: #4141a0;margin:0 auto" class="panel-heading"><span style="float:left"><?php echo $action;?> Channel</span><div class="msg" align="center" style="display:inline-block"><h4><?php echo $msg;?></h4></div></div>
   					 <div class="panel-body">
					<form id="xoouserultra-login-form-1" method='post' onsubmit="return callsubmit();" enctype="multipart/form-data">
						<input type="hidden" id="channelid" name="channelid" value="<?php echo $id; ?>">
						<div class="xoouserultra-field xoouserultra-edit xoouserultra-edit-show">
							<label class="xoouserultra-field-type">	
								<span>Channel Name:</span>
							</label>
							<div class="xoouserultra-field-value">
								<input onclick="clearError()" type="text" alt="" value="<?php echo @$channelname1;?>" id="channelname" name="channelname" />	
							</div>

							&nbsp;&nbsp;&nbsp;
							<div class="xoouserultra-clear"></div>
							<label class="xoouserultra-field-type">	
								<span>Url:</span>
							</label>
							<div class="xoouserultra-field-value">
								<input id="octo_url" onclick="clearError()" type="text" alt="" value="<?php echo @$url1;?>" id="url" name="url" />	<input type="button" onclick="get_octo()" value="Get Full URL"/><span id="octo_url_full"></span>
							</div>

							&nbsp;&nbsp;&nbsp;

							<label class="xoouserultra-field-type">	
								<span>Octo js:</span>
							</label>
							<div class="xoouserultra-field-value">
								<textarea rows="4" cols="50"  onclick="clearError()" type="text" alt=""  value="<?php echo @$octo_js1;?>" id="octo_js" name="octo_js"></textarea>*Click on "Get Full URL", copy the code and click "Go For JS" and use it there to get js
							</div>
							&nbsp;&nbsp;&nbsp;

						<div class="xoouserultra-clear"></div>
							<label class="xoouserultra-field-type">	
								<span>Meta Data:</span>
							</label>
							<div class="xoouserultra-field-value">
								<input  onclick="clearError()" type="text" alt="" placeholder="Ex:Maa TV" value="<?php echo @$metadata1;?>" id="metadata" name="metadata"/>
							</div>
							&nbsp;&nbsp;&nbsp;

							
							<label class="xoouserultra-field-type">	
								<span>Meta Description:</span>
							</label>
							<div class="xoouserultra-field-value">
								<textarea rows="4" cols="50" onclick="clearError()" alt="" placeholder="Ex:No. 1 Telugu entertainment channel" value="<?php echo @$metadesc1;?>"  id="metadesc" name="metadesc" ></textarea>
							</div>
						</div>		
						<div class="xoouserultra-clear"></div>
						<div class="xoouserultra-field xoouserultra-edit xoouserultra-edit-show">
						
							<label class="xoouserultra-field-type">	
								<span>Logo(path):</span>
							</label>
							<div class="xoouserultra-field-value">
								<?php if($logo1 !="" ) { echo '<img src="channel_logo/'.@$logo1.'" style="height:100px;width:200px;">'; } ?>
								<input onclick="clearError()" type="file" accept="image/*" id="logofile" name="logofile">
							</div>
							&nbsp;&nbsp;&nbsp;

							<label class="xoouserultra-field-type">	
								<span>Status:</span>
							</label>
							<div class="xoouserultra-field-value">
								<select name="status" alt="" id="status" onclick="clearError()">
									<option value="">-Set Channel Status-</option>
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
								<input type="hidden" name="editBoqid"  value="<?php echo @$channelid1;?>" />								<?php } ?>
								<input type="submit" name="<?php echo $action;?>_Submit"  value="Submit" />
								<?php if($action=="Edit"){?>
								
								<a class="link_btn" href="<?php echo SITE_URL.'/qp/admin_channel_management.php'?>">Back</a>
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
					<th>Channel Name</th>
					<th>Logo</th>
					<th>URL</th>
					<th>Meta Data</th>
					<th>Meta Description</th>					
					<th>Status</th>
					
					
					<?php		if($_SESSION['adminlevel']==-1)
{ ?> <th>Octo JS</th> <?php } ?>
					<?php		if($_SESSION['adminlevel']<1)
{ ?> <th>Action</th> <?php } ?>
				</tr>
			</thead>
			<tbody class="ui-sortable">
			<?php
			$stmt4 = $dbcon->prepare("SELECT * FROM channels", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
			$stmt4->execute();
			$result4 = $stmt4->fetchAll(PDO::FETCH_ASSOC);

			foreach($result4 as $row){

				$id=$row['id'];
				$channelname=$row['name'];

				$url=$row['url'];
				$octo_js=$row['octo_js'];

				$metadata=$row['meta_data'];
				$metadesc=$row['meta_description'];
				
				$logo = $row['image'];
				$stat = $row['status'];
				if($stat==1)
				{$status="Active";}
				if($stat==0)
				{$status="Inactive";}

				if($row['is_admin'] != 1){
			?>
				<tr style="" class="ui-sortable-handle">							
				<td style="width: 342px;color:blue;" class="level_name"><a style="color:blue;"><?php echo $channelname;?></a></td>
				<td style="width: 184px;"><?php echo $url;?></td>	
				<td style="width: 192px;"><img height="50px" width="150px" src="channel_logo/<?php echo $logo;?>"></td>
							
				<td style="width: 184px;"><?php echo $metadata;?></td>				
				<td style="width: 192px;"><?php echo $metadesc;?></td>
				<td style="width: 192px;"><?php echo $status;?></td>	
				
				
			<?php		if($_SESSION['adminlevel']==-1)
{ ?>

				
				<td style="width: 332px;"><?php echo $octo_js;?></td> <?php } ?>

<?php		if($_SESSION['adminlevel']<1)
{ ?>

				
				<td style="width: 332px;">


<a style="cursor:pointer;color:blue;" id="editchannel" name="editchannel" onclick="return confirmEditChannel(<?php echo $id;?>)">Edit</a>
&nbsp;
<a style="cursor:pointer;color:blue;" id="removechannel" name="removechannel" onclick="return confirmDelChannel(<?php echo $id;?>)">Remove</a>	


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
	
function confirmDelChannel(Delid)
{

 swal({
 		title:' ', 
  text: 'Do you really want to remove this Channel?',
  type: 'warning',
  
  showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Remove",   cancelButtonText: "Cancel",  closeOnCancel: true
},function(){ 

var input1 = jQuery("<input>")
               .attr("type", "hidden")
               .attr("name", "delChannelid").val(Delid);
var input2 = jQuery("<input>")
               .attr("type", "hidden")
               .attr("name", "delChannel").val(true);
jQuery('#frm2').append(jQuery(input1));
jQuery('#frm2').append(jQuery(input2));
 jQuery("#frm2").submit();

}); 

}



function confirmEditChannel(Editid)
{

 swal({
 		title:' ', 
  text: 'Do you want to edit this Channel?',
  type: 'warning',
  
  showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Edit",   cancelButtonText: "Cancel",  closeOnCancel: true
},function(){ 

var input1 = jQuery("<input>")
               .attr("type", "hidden")
               .attr("name", "editChannelid").val(Editid);
var input2 = jQuery("<input>")
               .attr("type", "hidden")
               .attr("name", "editChannel").val(true);
jQuery('#frm2').append(jQuery(input1));
jQuery('#frm2').append(jQuery(input2));
 jQuery("#frm2").submit();

}); 

}

function get_octo()
{
var octo=jQuery("#octo_url").val();
octo="var streamURL='octoshape://streams.octoshape.net/ideabytes/live/ib-"+octo+"'";
octo=octo+"<br><a target='_blank' href='https://javascriptobfuscator.com/Javascript-Obfuscator.aspx'>Go for JS</a>"
jQuery("#octo_url_full").html(octo);
}



setTimeout(function(){
  jQuery("#msg").html("");
}, 3000);
</script>
<?php include("footer-admin.php"); ?>
