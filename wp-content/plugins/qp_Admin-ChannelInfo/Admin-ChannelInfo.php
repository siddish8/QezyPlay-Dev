<?php 

/*
Plugin Name: Admin - Channel
Plugin URI: 
Description: Adding channel info to the site by admin only
Author: IB
Version: 1.0
Author URI: ib
*/

add_action('admin_menu', 'admin_channel_setup_menu');
 
function admin_channel_setup_menu(){
        add_menu_page( 'Admin - Channel Info', 'Admin - Channel', 'manage_options', 'admin-channel-plugin', 'channel_init' );
}
 
function channel_init(){
	
    echo "<h1>Channel Information</h1><br/>";
	channels_to_db();
}


add_shortcode('ChannelAdmin','channels_to_db');
function channels_to_db(){
	
	global $current_user;
	global $wpdb;
	
	$msg = ""; $action = "Add";

	get_currentuserinfo();
	$user_id = get_current_user_id();
	$dispName=$current_user->display_name;
	
	if(isset($_GET['del'])){

		$id = $_GET['id'];

		$wpdb->delete("channel_info",array("id"=>$id));
				
		$msg = "<span style='color:green'>Channel Info deleted Successfully</span>";
		
	}


	if(isset($_POST['Add_Submit'])){
		$channel_id=trim($_POST['channel_id']);		
		$channel_name=trim($_POST['channel_name']);
		$octo_url=trim($_POST['octo_url']);
		$octo_js=trim($_POST['octo_js']);
		
		
		$userexist = $wpdb->get_var('select id from channel_info where channel_id = "'.$channel_id.'" OR octo_url = "'.$octo_url.'" OR octo_js = "'.$octo_js.'"'); 		
		if((int)$userexist > 0){
			
			echo $msg = "<span style='color:red'>Channel Info already exist</span>";
			
		}else{			
			
			//insert query
			$wpdb->insert("channel_info", array(
						"channel_id" => $channel_id,
						"channel_name" => $channel_name, 
						"octo_url" => $octo_url,
						"octo_js" => $octo_js)
					);

			

			$msg = "<span style='color:green'>Channel Info added Successfully</span>";
						
		}
	}


	if(isset($_POST['Edit_Submit'])){

		$id=trim($_POST['channelid']);
		
		
		$channel_id=trim($_POST['channel_id']);
		$channel_name=trim($_POST['channel_name']);
		$octo_url=trim($_POST['octo_url']);
		$octo_js=trim($_POST['octo_js']);

		$userexit = $wpdb->get_var('select id from channel_info where id != '.$id.' AND (channel_id = "'.$channel_id.'" OR octo_url = "'.$octo_url.'" OR octo_js = "'.$octo_js.'")'); 
		if((int)$userexit > 0){
			
			$msg = "<span style='color:red'>Channel info already exist</span>";
			
		}else{
			
			//update query
			$wpdb->update("channel_info", array(   
					"channel_id" => $channel_id,
					"channel_name" => $channel_name, 
					"octo_url" => $octo_url,		
					"octo_js"=>$octo_js), array("id"=>$id)
					);

			
			$msg = "<span style='color:green'>Channel Info updated Successfully</span>";
			
									
		}		
		
	}	


	if(isset($_GET['edit'])){

		$id = $_GET['id'];		

		$res3 = $wpdb->get_results("SELECT * FROM channel_info where id='".$id."'");
		foreach($res3 as $row3){
			$channel_id1=$row3->channel_id;
			$channel_name1=$row3->channel_name;
			$octo_url1=$row3->octo_url;
			$octo_js1=$row3->octo_js;
			$octo_js1=stripslashes($octo_js1);
		}
	
		$action = "Edit";

	}	

	?>

	<style>
	#addchannel_submit{
		background-color: #0073aa !important;
		color: azure !important;
		padding: 5px;text-decoration: none;border:solid 1px #0073aa !important;
	}
	#addchannel_submit:hover{
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
	<div align="center" id="<?php echo $action;?>Channel">
		<form method="post" >
			<table>
				<tr><td colspan="2"><center><span style="font-weight:bold;font-size:18px;"><?php echo $action;?> Channel</span></center><br>
				<input type="hidden" id="channelid" name="channelid" value="<?php echo $id; ?>"/></td></tr>
				<tr><td>Channel Id</td><td><input type="text" value="<?php echo @$channel_id1;?>" id="channel_id" name="channel_id" required/></td></tr>
				<tr><td>Channel Name</td><td><input type="text" value="<?php echo @$channel_name1;?>" id="channel_name" name="channel_name" required/></td></tr>
				
				<tr><td>Octo Url</td><td><input type="textarea" value="<?php echo @$octo_url1;?>" required id="octo_url" name="octo_url" /></td></tr>
				<tr><td>Octo JS</td><td><input type="textarea" value='<?php echo @$octo_js1;?>' required id="octo_js" name="octo_js" /></td></tr>
				<tr><td></td><td><input type="submit" name="<?php echo $action;?>_Submit"  value="Submit" style="cursor: pointer;background-color: #0073aa !important;
				color: azure !important;
				padding: 5px;text-decoration: none;border:solid 1px #0073aa !important;" /></td></tr>
			</table>
		</form>
		<?php if($action=="Edit") { ?> <a href='http://ideabytestraining.com/newqezyplay/wp-admin/admin.php?page=admin-channel-plugin'><button>Back</button></a> <?php } ?>
	</div>

	<div class="clear"></div>

	<br />

	<div id="ChannelList"><h2>Channels List Info</h2> 
		<table class="widefat membership-levels" style="width:95% !important;">
			<thead>
				<tr>					
					<th>Channel Id</th>
					<th>Channel Name</th>
					<th>Octo URL</th>
					<th>Octo JS</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody class="ui-sortable">
			<?php
			$res=$wpdb->get_results("SELECT * FROM channel_info"); 

			foreach($res as $row){

				$id=$row->id;
				$channel_id=$row->channel_id;
				$channel_name=$row->channel_name;
				$octo_url=$row->octo_url;
				$octo_js=$row->octo_js;
				$octo_js=stripslashes( $octo_js );

				
			?>
				<tr style="" class="ui-sortable-handle">
				<td style="width: 1px;" class="level_name"><?php echo $channel_id;?></td>
				<td style="width: 332px;"><?php echo $channel_name;?></td>
				<td style="width: 392px;"><?php echo $octo_url?></td>
				<td style="width: 350px;"><?php echo $octo_js;?></td>
				<td style="width: 332px;"><a style="cursor: pointer;" id="editChannel" href="admin.php?page=admin-channel-plugin&edit=true&id=<?php echo $id;?>" title="edit" name="Edit-<?php echo $id ?>" class="button-primary">Edit</a>&nbsp;
				<a style="cursor: pointer;" title="delete" name="removeChannel-<?php echo $id;?>" id="removeChannel-<?php echo $id;?>" onclick="callConfirmation('admin.php?page=admin-channel-plugin&del=true&id=<?php echo $id;?>');" class="button-secondary">Delete</a></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
	<div class="clear"></div><br /><br />


	<script>
	function callConfirmation(url){

		var ans = confirm("Sure, do you want to delete this channel info?");
		if(ans){
			window.location.href = url;
		}
	}
	</script>
<?php
} 
