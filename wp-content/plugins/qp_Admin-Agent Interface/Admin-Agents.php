<?php 

/*
Plugin Name: Admin - Agents
Plugin URI: 
Description: Adding agents to the site by admin only
Author: IB
Version: 1.0
Author URI: ib
*/

add_action('admin_menu', 'admin_agent_setup_menu');
 
function admin_agent_setup_menu(){
        add_menu_page( 'Admin - Agent Management', 'Admin - Agent', 'manage_options', 'admin-agent-plugin', 'agent_init' );
}
 
function agent_init(){
	
    echo "<h1>Agent Management</h1><br/>";
	agents_to_db();
}


add_shortcode('AgentAdmin','agents_to_db');
function agents_to_db(){
	
	global $current_user;
	global $wpdb;
	
	$msg = ""; $action = "Add";

	get_currentuserinfo();
	$user_id = get_current_user_id();
	$dispName=$current_user->display_name;
	
	if(isset($_GET['del'])){

		$id = $_GET['id'];

		$wpdb->delete("agent_info",array("id"=>$id));
		$wpdb->delete("agent_location_info",array("agent_id"=>$id));
		
		$msg = "<span style='color:green'>Agent deleted Successfully</span>";
		
	}


	if(isset($_POST['Add_Submit'])){
		
		$agentname=trim($_POST['agentname']);
		$phone=trim($_POST['phone']);
		$email=trim($_POST['email']);
		//$authkey=trim($_POST['add_authkey']);

		$city=trim($_POST['city']);
		//$cityArray = explode(',', $city); //uncomment
	
				
		
		$authkey = md5($phone.$email);
		$authkey = substr($authkey, 0, 10);	
		//$authkey="";
		$date = gmdate("Y-m-d H:i:s");
		
		$userexit = $wpdb->get_var('select id from agent_info where mobile = "'.$phone.'" OR agentname = "'.$agentname.'" OR email = "'.$email.'"'); 		
		if((int)$userexit > 0){
			
			echo $msg = "<span style='color:red'>Agent already exist</span>";
			
		}else{			
			
			//insert query
			$wpdb->insert("agent_info", array(
						"agentname" => $agentname,
						"mobile" => $phone, 
						"email" => $email,
						"authkey" => $authkey,
						"created_datetime"=>$date)
					);

			$agentloginlink = SITE_URL."/qp/agent-login.php";
			
			// Always set content-type when sending HTML email
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			// More headers
			$headers .= 'From: <'.ADMIN_EMAIL.'>' . "\r\n";
			
			$subjectAgent = "Your account info on Qezyplay.com as Agent";
			$regards = "<p>Regards, <br>Admin - QezyPlay</p>";
			$bodyAgent = "<p>Hi ".$agentname.",</p>
			<p>Your account has been created in Qezyplay.com as Agent</p>
			<p>Find below credentials to login with agent portal</p>
			<p>
			<b>Email:</b> $email<br>
			<b>Auth Key:</b> $authkey<br>
			</p>
			<p>Log in to your agent portal here: $agentloginlink</p>".$regards;	
			
			//mail Agent
			mail($email, $subjectAgent, print_r($bodyAgent,true), $headers);

			$agent_id = $wpdb->get_var('select id from agent_info order by id desc limit 1'); 

			$locationsArray =explode(',',$city); //added
			//echo "<pre>";
			//print_r($locationsArray);
			foreach($locationsArray as $locAr)
			{
				$cityAr=explode('-',$locAr);				
			$wpdb->insert("agent_location_info", array("agent_id"=>$agent_id, "location" => $cityAr[0],"region"=>$cityAr[1],"country"=>$cityAr[2]));
			}
			/* foreach($cityArray as $cityItem) {
				$wpdb->insert("agent_location_info", array("agent_id"=>$agent_id, "location" => $cityItem));
			}*/

			$msg = "<span style='color:green'>Agent added Successfully</span>";
						
		}
	}


	if(isset($_POST['Edit_Submit'])){

		$agentid=trim($_POST['agentid']);
		
		//$agentid = $_GET['id'];
		$agentname=trim($_POST['agentname']);
		$phone=trim($_POST['phone']);
		$email=trim($_POST['email']);
		$city=trim($_POST['city']);

		$cityArray = explode(',', $city);
		$date = gmdate("Y-m-d H:i:s");
		
		$userexit = $wpdb->get_var('select id from agent_info where id != '.$id.' AND (mobile = "'.$phone.'" OR agentname = "'.$agentname.'" OR email = "'.$email.'")'); 
		if((int)$userexit > 0){
			
			$msg = "<span style='color:red'>Agent already exist</span>";
			
		}else{
			
			//update query
			$wpdb->update("agent_info", array(   
					"agentname" => $agentname,
					"mobile" => $phone, 
					"email" => $email,		
					"created_datetime"=>$date), array("id"=>$agentid)
					);

			$wpdb->delete("agent_location_info",array("agent_id"=>$agentid));
			
		
			/*foreach($cityArray as $cityItem){
				$wpdb->replace("agent_location_info", array("agent_id"=>$agentid, "location" => $cityItem));
			}*/	


			$locationsArray =explode(',',$city); //added
			//echo "<pre>";
			//print_r($locationsArray);
			foreach($locationsArray as $locAr)
			{
				$cityAr=explode('-',$locAr);
				$wpdb->insert("agent_location_info", array("agent_id"=>$agentid, "location" => $cityAr[0],"region"=>$cityAr[1],"country"=>$cityAr[2]));
			}	
			$msg = "<span style='color:green'>Agent updated Successfully</span>";
									
		}		
		
	}	


	if(isset($_GET['edit'])){

		$id = $_GET['id'];		

		$res3 = $wpdb->get_results("SELECT * FROM agent_info where id='".$id."'");
		foreach($res3 as $row3){
			$agentname1=$row3->agentname;
			$mobile1=$row3->mobile;
			$email1=$row3->email;
		}
	
		$location1 = $wpdb->get_var("SELECT group_concat(concat(location,'-',region,'-',country)) as location FROM agent_location_info where agent_id='".$id."'");		
		$action = "Edit";

	}	

	?>

	<style>
	#addagent_submit{
		background-color: #0073aa !important;
		color: azure !important;
		padding: 5px;text-decoration: none;border:solid 1px #0073aa !important;
	}
	#addagent_submit:hover{
		background-color: azure !important;color: #0073aa !important;
	}
	td{
		padding: 5px 20px;
	}
	
	input{
		width: 250px;
	}
	
	</style>

	<div class="msg" align="center" style="display:block"><h4><?php echo $msg;?></h4></div> 	
	<br />

	<div class="clear"></div>
	<div align="center" id="<?php echo $action;?>Agent">
		<form method="post" >
			<table>
				<tr><td colspan="2"><center><span style="font-weight:bold;font-size:18px;"><?php echo $action;?> Agent</span></center><br>
				<input type="hidden" id="agentid" name="agentid" value="<?php echo $id; ?>"/></td></tr>
				<tr><td>Agent name</td><td><input type="text" value="<?php echo @$agentname1;?>" id="agentname" name="agentname" required/></td></tr>
				<tr><td>Mobile</td><td><input type="tel" value="<?php echo @$mobile1;?>" id="phone" name="phone" required/></td></tr>
				<tr><td>Location</td><td><textarea rows="3" cols="35" name="city" required id="city" placeholder="Ex: city1-state1-country1,city2-state2-country2" required ><?php echo @$location1;?></textarea></td></tr>
				<tr><td>E-mail</td><td><input type="email" value="<?php echo @$email1;?>" required id="email" name="email" /></td></tr>
				<tr><td></td><td><input type="submit" name="<?php echo $action;?>_Submit"  value="Submit" style="cursor: pointer;background-color: #0073aa !important;
				color: azure !important;
				padding: 5px;text-decoration: none;border:solid 1px #0073aa !important;" /></td></tr>
			</table>
		</form>
	</div>
	<div class="clear"></div>

	<br />

	<div id="AgentList"><h2>Agents List</h2> 
		<table class="widefat membership-levels" style="width:95% !important;">
			<thead>
				<tr>					
					<th>Agent Name</th>
					<th>Mobile</th>
					<th>Location</th>
					<th>E-mail</th>
					<th>Auth Key</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody class="ui-sortable">
			<?php
			$res=$wpdb->get_results("SELECT * FROM agent_info"); 

			foreach($res as $row){

				$id=$row->id;
				$agentname=$row->agentname;
				$mobile=$row->mobile;
				$email=$row->email;
				$authkey=$row->authkey;

				$location = $wpdb->get_var("SELECT group_concat(concat(location,'-',region,'-',country)) as location FROM agent_location_info where agent_id='".$id."'");
			?>
				<tr style="" class="ui-sortable-handle">							
				<td style="width: 342px;" class="level_name"><?php echo $agentname;?></td>
				<td style="width: 184px;"><?php echo $mobile;?></td>
				<td style="width: 192px;"><?php echo $location?></td>
				<td style="width: 192px;"><?php echo $email;?></td>
				<td style="width: 192px;"><?php echo $authkey;?></td>
				<td style="width: 332px;"><a style="cursor: pointer;" id="editAgent" href="admin.php?page=admin-agent-plugin&edit=true&id=<?php echo $id;?>" title="edit" name="Edit-<?php echo $id ?>" class="button-primary">Edit</a>&nbsp;
				<a style="cursor: pointer;" title="delete" name="removeAgent-<?php echo $id;?>" id="removeAgent-<?php echo $id;?>" onclick="callConfirmation('admin.php?page=admin-agent-plugin&del=true&id=<?php echo $id;?>');" class="button-secondary">Delete</a></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
	<div class="clear"></div><br /><br />


	<script>
	function callConfirmation(url){

		var ans = confirm("Sure, do you want to delete this agent?");
		if(ans){
			window.location.href = url;
		}
	}
	</script>
<?php
} 
