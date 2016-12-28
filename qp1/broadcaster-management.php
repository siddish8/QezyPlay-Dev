<?php 
include('header.php');
?>
<article class="content items-list-page">

<?php
$msg = ""; $action = "Add";

if(isset($_REQUEST['delCust'])){

	$id = $_REQUEST['delCustid'];

	$stmt11 = $dbcon->prepare("DELETE FROM customer_info WHERE id = ".$id."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt11->execute();
	
	$msg = "<span id='msg' style='color:green'>Broadcaster deleted Successfully</span>";
	
}

if(isset($_POST['Add_Submit'])){
	
	$customername=$customername1=trim($_POST['customername']);
	$customername=strtolower(preg_replace('/\s+/', '', $customername));
	//echo $customername;
	//$customernames=array($customername,preg_replace('/\s+/', '', $customername),strtoupper($customername),strtolower($customername),ucfirst($customername));

//echo $customernames."\n";


	$phone=trim($_POST['phone']);
	$email=trim($_POST['email']);
	//$authkey=trim($_POST['add_authkey']);

	$channel=trim($_POST['channel']);
	$authkey = md5($email.rand());
	$authkey = substr($authkey, 0, 10);

//echo $customername.$phone.$email.$channel.$authkey;

	//$authkey="";
	$date = gmdate("Y-m-d H:i:s");
	
	//$stmt21 = $dbcon->prepare('SELECT id FROM customer_info WHERE mobile = "'.$phone.'" OR customername = "'.$customername.'" OR email = "'.$email.'" OR channel_id = "'.$channel.'"', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	

	$userexit=0;
	//foreach($customernames as $customername){
	//echo $customername;
	$stmt21 = $dbcon->prepare('SELECT * FROM customer_info WHERE LOWER(REPLACE(customername," ","")) = "'.$customername.'" OR channel_id = "'.$channel.'"', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
	$stmt21->execute();
	$result21 = $stmt21->fetchAll(PDO::FETCH_ASSOC);
	//print_r($result21)."\n";
	//$userexit = $userexit + count($result21);//}
	$userexit = count($result21);


//echo "yes:".$userexit;
//exit;
	if((int)$userexit > 0){
		
		$msg1="";

		$stmt212 = $dbcon->prepare('SELECT * FROM customer_info WHERE LOWER(REPLACE(customername," ","")) = "'.$customername.'"', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
		$stmt212->execute();
		$result212 = $stmt212->fetchAll(PDO::FETCH_ASSOC);
		$custnameexist = count($result212);

		//echo "cust exist:".$custnameexist;
		if((int)$custnameexist > 0){	
			$msg1.= "<i class='fa fa-arrow-right' aria-hidden='true'></i> Broadcaster Name already exist <br>";}

		$stmt211 = $dbcon->prepare('SELECT * FROM customer_info WHERE channel_id = "'.$channel.'"', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
		$stmt211->execute();
		$result211 = $stmt211->fetchAll(PDO::FETCH_ASSOC);
		$chanexist = count($result211);	
//echo $userexit;	
//exit;
		//echo "channel exist:".$chanexist;
		if((int)$chanexist > 0){	
			$msg1.= "<i class='fa fa-arrow-right' aria-hidden='true'></i> Channel already exist <br>";}


		$msg = "<span id='msg' style='color:red'>".$msg1."</span>";
		//echo $msg;
		
	}else{	
		//echo "NEW";
		if ($_FILES["logofile"]["error"] > 0){
			$msg = "<span style='color:red'>Problem with Broadcaster logo. Please try again.</span>";
		}else{	
							
			$present_time = date("dmYHis");
			$image_extention = explode("/",$_FILES["logofile"]["type"]);
			$sNewFileName = $present_time.".".$image_extention[1];
											
			if(move_uploaded_file($_FILES["logofile"]["tmp_name"],"customer_logo/".$sNewFileName)){
				
				//insert query

				//exit;

				$stmt22 = $dbcon->prepare('INSERT INTO customer_info(channel_id,customername,mobile,customer_logo_url,email,authkey,created_datetime) VALUES('.$channel.',"'.$customername1.'","'.$phone.'","'.$sNewFileName.'","'.$email.'","'.$authkey.'","'.$date.'")', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
				$stmt22->execute();

				$customerloginlink = SITE_URL."/qp/customer-login.php";
		
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
				mail($email, $subjectCustomer, print_r($bodyCustomer,true), $headers);

				$msg = "<span style='color:green'>Broadcaster added Successfully</span>";				
	
			} else{					
				$msg = "<span style='color:red'>Problem with Broadcaster logo. Please try again.</span>";
			}
			
		}			
	}
}


if(isset($_POST['Edit_Submit'])){

	$customerid=trim($_POST['customerid']);
	
	//$customerid = $_GET['id'];
	$customername=trim($_POST['customername']);
	$phone=trim($_POST['phone']);
	$email=trim($_POST['email']);	
	$channel=trim($_POST['channel']);
	$date = gmdate("Y-m-d H:i:s");
	
	//$stmt21 = $dbcon->prepare('SELECT id FROM customer_info WHERE id != '.$customerid.' AND (mobile = "'.$phone.'" OR customername = "'.$customername.'" OR email = "'.$email.'" OR channel_id = "'.$channel.'")', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
	$stmt21 = $dbcon->prepare('SELECT * FROM customer_info WHERE id != '.$customerid.' AND (customername = "'.$customername.'" OR channel_id = "'.$channel.'")', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt21->execute();
	$result21 = $stmt21->fetchAll(PDO::FETCH_ASSOC);	
	//$userexit = $result21['id']; 
	$userexit = count($result21);
	if((int)$userexit > 0){
		
		$msg1="";

		$stmt212 = $dbcon->prepare('SELECT * FROM customer_info WHERE id != '.$customerid.' and customername = "'.$customername.'"', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
		$stmt212->execute();
		$result212 = $stmt212->fetchAll(PDO::FETCH_ASSOC);
		$custnameexist = count($result212);

		if((int)$custnameexist > 0){	
			$msg1.= "<i class='fa fa-arrow-right' aria-hidden='true'></i> Customer Name already exist <br>";}

		$stmt211 = $dbcon->prepare('SELECT * FROM customer_info WHERE id != '.$customerid.' and channel_id = "'.$channel.'"', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
		$stmt211->execute();
		$result211 = $stmt211->fetchAll(PDO::FETCH_ASSOC);
		$chanexist = count($result211);	
//echo $userexit;	
//exit;
		if((int)$chanexist > 0){	
			$msg1.= "<i class='fa fa-arrow-right' aria-hidden='true'></i> Channel already exist <br>";}


		$msg = "<span id='msg' style='color:red'>".$msg1."</span>";
		
	}else{
		
		if ($_FILES["logofile"]["error"] > 0 && $_FILES["logofile"]["error"] != "4"){
			$msg = "<span style='color:red'>Problem with Broadcaster logo. Please try again.</span>";
		}else{	

			if($_FILES["logofile"]["name"] !=""){

				$select_q_content = "SELECT * FROM customer_info WHERE `id` = :id";
				$select_query = $dbcon->prepare($select_q_content);
				$select_query->bindParam(":id",$customerid);
				$select_query->execute();
				if($select_query->rowCount() > 0){
					$cData = $select_query->fetch(PDO::FETCH_ASSOC);

					if($cData["customer_logo_url"]!=""){
						$req_image_path = "customer_logo/".$cData["customer_logo_url"];
						if (file_exists($req_image_path)){
							unlink($req_image_path);
						}
					}		
				}
				
				$present_time = date("dmYHis");
				$image_extention = explode("/",$_FILES["logofile"]["type"]);
				$sNewFileName = $present_time.".".$image_extention[1];

				if(move_uploaded_file($_FILES["logofile"]["tmp_name"],"customer_logo/".$sNewFileName)){

					//update query
					$stmt22 = $dbcon->prepare('UPDATE customer_info SET customername = "'.$customername.'", mobile = "'.$phone.'", email = "'.$email.'", customer_logo_url = "'.$sNewFileName.'", channel_id = "'.$channel.'",created_datetime = "'.$date.'" WHERE id = '.$customerid, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	 
					$stmt22->execute();

					$msg = "<span style='color:green'>Customer updated Successfully</span>";

				} else{					
					$msg = "<span style='color:red'>Problem with customer logo. Please try again.</span>";
				}

			}else{

				//update query
				$stmt22 = $dbcon->prepare('UPDATE customer_info SET customername = "'.$customername.'", mobile = "'.$phone.'", email = "'.$email.'", channel_id = "'.$channel.'",created_datetime = "'.$date.'" WHERE id = '.$customerid, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	 
				$stmt22->execute();

				$msg = "<span style='color:green'>Broadcaster updated Successfully</span>";

			}								
			
		}								
	}		
}	


if(isset($_REQUEST['editCust'])){

	$id = $_REQUEST['editCustid'];		

//echo $id;
//exit;		

	$stmt1 = $dbcon->prepare("SELECT * FROM customer_info where id='".$id."'", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt1->execute();
	$result1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
	foreach($result1 as $row1){
		$customerid1=$row1['id'];
		$customername1=$row1['customername'];
		$mobile1=$row1['mobile'];
		$email1=$row1['email'];
		$logo1=$row1['customer_logo_url'];
		$channel1=$row1['channel_id'];
	}

		
	$action = "Edit";

}	

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

#search_channel > input[type="text"] {
    max-width: 250px;
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


		<h2 style="text-align:center">Broadcaster Management</h2>
<?php		if($_SESSION['adminlevel']<1)
{ ?>	
<section class="section">
<span style="float:"></span><div class="msg" align="center" style="display:inline-block"><h4><?php echo $msg;?></h4></div>
                        <div class="row sameheight-container">
				 <div class="col-md-3">
                             
                            </div>
                            <div class="col-md-6">
                                <div id="broadcaster-form-section" class="card card-block sameheight-item" style="height:auto;/*height: 721px;*/">
                                    <!--<div class="title-block">
                                        <h2 class="title">
						<?php echo $action;?> Agent
					</h2> </div>-->
					<div class="card card-primary">
					<div class="card-header">
                                        <div class="header-block">
                                            <p class="title"> <?php echo $action;?> Broadcaster </p>
                                        </div>
                                    </div>
			     <div class="xoouserultra-field-value" style="">
						<div id='error' style='color:red;'";></div>
					</div>
				<div class="card-block">
					 <form role="form" method='post' enctype="multipart/form-data">
						<input type="hidden" id="customerid" name="customerid" value="<?php echo $id; ?>">
                                        <div class="form-group"> <label class="control-label">Broadcaster Name</label> <input onclick="clearError()" type="text" value="<?php echo @$customername1;?>" id="customername" name="customername" class="form-control underlined"> </div>			
					<div class="form-group"> <label class="control-label">Mobile</label> <input onclick="clearError()" type="text" placeholder="Ex:+919000000000" value="<?php echo @$mobile1;?>" id="phone" name="phone" class="form-control underlined"> </div>
					<div class="form-group"> <label class="control-label">Email</label> <input onclick="clearError()" type="text" value="<?php echo @$Email1;?>" id="email" name="email" class="form-control underlined"> </div>
					<div class="form-group"> <label class="control-label">Logo (path)</label> 
					<?php if($logo1 !="" ) { echo '<img src="customer_logo/'.@$logo1.'" style="height:100px;width:200px;">'; } ?>
					<input onclick="clearError()" type="file" accept="image/*" id="logofile" name="logofile" class="form-control underlined"> </div>
					<div class="form-group"> <label class="control-label">Channel</label> <select name="channel" alt="" id="channel" onclick="clearError()" class="form-control underlined">
									<option value="">-Select channel-</option>
									<?php
									$stmt5 = $dbcon->prepare("SELECT ID,post_title FROM `wp_posts` WHERE post_status = 'publish' and post_type = 'post'", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
									$stmt5->execute();
									$result5 = $stmt5->fetchAll(PDO::FETCH_ASSOC);

									foreach($result5 as $row){
										$selected = ($row['ID'] == $channel1) ? "selected='selected'" : "";
										echo "<option value='".$row['ID']."'".$selected.">".$row['post_title']."</option>";
									}
									?>
								</select> </div>			
							<div class="xoouserultra-field-value" style="padding-left: 10px;">
								<?php if($action=="Edit"){?>
								<input type="hidden" name="editCust"  value="true" />
								<input type="hidden" name="editCustid"  value="<?php echo @$customerid1;?>" />								<?php } ?>
								<input class="btn btn-primary" onclick="return callsubmit();" type="submit" name="<?php echo $action;?>_Submit"  value="Submit" />
								<?php if($action=="Edit"){?>
								<!-- input type="submit" name="Customer_Back"  value="Back" /-->
								<a class="link_btn" href="<?php echo SITE_URL.'/qp/broadcaster_management.php'?>">Back</a>
   								<?php } ?>
							
							</div>
							
													
						 </form>
                                </div>
				</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                             
                            </div>
                        </div>
                    </section>
<?php }

?>
<section class="section">
 <div class="col-md-3">
                             
                            </div>
<div id="search_channel" align="center" style="margin:15px auto;" class="col-md-6">
		Search channel: <br /> <input placeholder="Enter channel name" id="searchCN" type="text" name="searchCN" class="form-control boxed" /> <span class="_or"> </span>   
						
		</div>
<div class="col-md-3">
                             
                            </div>
   </section>
		<section class="section">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-block">
                                        <div class="card-title-block">
                                            <h3 class="title">
							Broadcasters' List
						</h3> </div>
                                        <section class="example">		<!-- <div class="table-responsive"	style="max-height: 400px; overflow-y: scroll;"> -->

		<form id="frm2" method="post">
		<div class="table-responsive">
                                                <table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>					
					<th>Broadcaster Name</th>
					<th>Logo</th>
					<th>Channel Name</th>
					<th>Mobile</th>					
					<th>E-mail</th>
					
					
					<?php		if($_SESSION['adminlevel']<1)
{ ?> <th>Auth Key</th><th>Action</th> <?php } ?>
				</tr>
			</thead>
			<tbody class="ui-sortable" id="bc_list">
			<?php
			$stmt4 = $dbcon->prepare("SELECT * FROM customer_info", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
			$stmt4->execute();
			$result4 = $stmt4->fetchAll(PDO::FETCH_ASSOC);

			foreach($result4 as $row){

				$id=$row['id'];
				$customername=$row['customername'];
				$mobile=$row['mobile'];
				$email=$row['email'];
				$authkey=$row['authkey'];

				$logo = $row['customer_logo_url'];
				$channelid = $row['channel_id'];
				$channelName=get_var("SELECT post_title from wp_posts where ID=".$channelid." ");

				if($row['is_admin'] != 1){
			?>
				<tr style="" class="ui-sortable-handle">							
				<td style="width: 200px;color:blue;" class="level_name"><a style="color:blue;" target="_blank" href="broadcaster-main.php?isadmin=1&customer_id=<?php echo $id; ?>&channel_id=<?php echo $channelid; ?>"><?php echo $customername;?></a></td>
				<td style="width: 250px;"><img height="50px" width="150px" src="customer_logo/<?php echo $logo;?>"></td>
				<td style="width: 192px;"><?php echo $channelName;?></td>				
				<td style="width: 184px;"><?php echo $mobile;?></td>				
				<td style="width: 192px;"><?php echo $email;?></td>
				
				
<?php		if($_SESSION['adminlevel']<1)
{ ?>

				<td style="width: 150px;"><?php echo $authkey;?></td>
				<td style="width: 100px;">

<a style="cursor:pointer;color:blue;" id="editcustomer" name="editcustomer" onclick="return confirmEditCustomer(<?php echo $id;?>)"><em title="Edit" class="fa fa-pencil"></em></a>
&nbsp;
<a style="cursor:pointer;color:blue;" id="removecustomer" name="removecustomer" onclick="return confirmDelCustomer(<?php echo $id;?>)"><i title="Remove" class="fa fa-trash-o "></i></a>	

</td> <?php } ?>
				</tr>
			<?php } }?>
			</tbody>
			<tr id="no_res_div" align="center" style="margin: 5px auto;
    border-bottom: 1px solid #999;
    max-width: 80%;
    font-size: 15px;"><td colspan="7" id="no_res" style="margin:0 auto;text-align:center"></td></tr>
		</table></div></form>
 </section>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>


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
	
	$("#broadcaster-form-section").css("height","auto");

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
	


function confirmDelCustomer(Delid)
{

 swal({
 		title:' ', 
  text: 'Do you really want to remove this Broadcaster?',
  type: 'warning',
  
  showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Remove",   cancelButtonText: "Cancel",  closeOnCancel: true
},function(){ 

var input1 = jQuery("<input>")
               .attr("type", "hidden")
               .attr("name", "delCustid").val(Delid);
var input2 = jQuery("<input>")
               .attr("type", "hidden")
               .attr("name", "delCust").val(true);
jQuery('#frm2').append(jQuery(input1));
jQuery('#frm2').append(jQuery(input2));
 jQuery("#frm2").submit();

}); 

}

function confirmEditCustomer(Editid)
{

 swal({
 		title:' ', 
  text: 'Do you want to edit this Broadcaster?',
  type: 'warning',
  
  showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Edit",   cancelButtonText: "Cancel",  closeOnCancel: true
},function(){ 

var input1 = jQuery("<input>")
               .attr("type", "hidden")
               .attr("name", "editCustid").val(Editid);
var input2 = jQuery("<input>")
               .attr("type", "hidden")
               .attr("name", "editCust").val(true);
jQuery('#frm2').append(jQuery(input1));
jQuery('#frm2').append(jQuery(input2));
 jQuery("#frm2").submit();

}); 

}

function findRows(table,searchText1,col1) {


    var rows = table.rows,
        r = 0,
        found = false,
        anyFound = false;
      
	
  var found1=false;
  
    for (; r < rows.length; r += 1) {
        row = rows.item(r);
          
     // var i=column;
       // alert(searchText1+"in col:"+col1+"  row::"+r);
        
        if(col1!=99)
        found1 = (row.cells.item(col1).textContent.replace(/ +/g, "").toLowerCase().indexOf(searchText1.replace(/ +/g, "").toLowerCase().trim()) !== -1);
        else
        found1=true;
        
          
            
            found=found1;
        anyFound = anyFound || found;
//alert(found);
			//if(row.style.display=="none")
      	//	found=false;
        row.style.display = found ? "table-row" : "none";
       }
        
	   
	if(col1==99)
	{
		for (; r < rows.length; r += 1) {
			row = rows.item(r);
			 row.style.display = "table-row" ;}
	}
    //document.getElementById('no_res').style.display = anyFound ? "none" : "block";
var x = document.getElementById("bc_list").rows.length;
//alert("x"+x);
var cnt=0;

for(i=0;i<x;i=i+1)
{
 var y=document.getElementById("bc_list").rows[i].style.display;

if(y=="none")
cnt=cnt+1;
}

//alert("cnt:"+cnt);
if(x==(cnt))
{
document.getElementById("no_res").innerHTML="NO SEARCH RESULTS";
jQuery("#no_res").html("NO SEARCH RESULTS");
jQuery("#no_res_div").show();
}
else
{
jQuery("#no_res").html("");
jQuery("#no_res_div").hide();
}

}

function performSearch() {
    var searchText1 = document.getElementById('searchCN').value,
       targetTable = document.getElementById('bc_list');
	var searchText=[],col;
	
	if(searchText1!="")
	{
	searchText[0]=searchText1;
	col1=2;
	}
  else{col1=99;}
  
  
	 	

	//alert(searchText);
    findRows(targetTable,searchText1,col1);

}


document.getElementById("searchCN").onkeyup = performSearch;



setTimeout(function(){
  jQuery("#msg").html("");
}, 3000);
</script>
</article>
<?php
include('footer.php');
?>
