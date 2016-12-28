<?php 

include("db-config.php");
include("admin-include.php");
include("function_common.php");

$msg = ""; $action = "Add";

if(isset($_GET['del'])){

	$id = $_GET['id'];

	$stmt11 = $dbcon->prepare("DELETE FROM channel_info WHERE id = ".$id."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt11->execute();
	
	$msg = "<span style='color:green'>Channel Info deleted Successfully</span>";
	
}


if(isset($_POST['Add_Submit'])){
		$channel_id=trim($_POST['channel_id']);		
		$channel_name=trim($_POST['channel_name']);
		$octo_url=trim($_POST['octo_url']);
		$octo_js=trim($_POST['octo_js']);
		
		
		$stmt21 = $dbcon->prepare('select id from channel_info where channel_id = "'.$channel_id.'" OR octo_url = "'.$octo_url.'" OR octo_js = "'.$octo_js.'"', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));		
	$stmt21->execute();
	$result21 = $stmt21->fetch(PDO::FETCH_ASSOC);
	$userexit = $result21['id'];
		if((int)$userexist > 0){
			
			echo $msg = "<span style='color:red'>Channel Info already exist</span>";
			
		}else{			
			
			//insert query
			$stmt22 = $dbcon->prepare('INSERT INTO channel_info(channel_id,channel_name,octo_url,octo_js) VALUES("'.$channel_id.'","'.$channel_name.'","'.$octo_url.'","'.$octo_js.'")', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
		$stmt22->execute();
						

			$msg = "<span style='color:green'>Channel Info added Successfully</span>";
						
		}
	}


if(isset($_POST['Edit_Submit'])){

		$id=trim($_POST['channelid']);
		
		
		$channel_id=trim($_POST['channel_id']);
		$channel_name=trim($_POST['channel_name']);
		$octo_url=trim($_POST['octo_url']);
		$octo_js=trim($_POST['octo_js']);
		
		$stmt21 = $dbcon->prepare('select id from channel_info where id != '.$id.' AND (channel_id = "'.$channel_id.'" OR octo_url = "'.$octo_url.'" OR octo_js = "'.$octo_js.'")', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
	$stmt21->execute();
	$result21 = $stmt21->fetch(PDO::FETCH_ASSOC);	
	$userexit = $result21['id']; 
		 
		if((int)$userexit > 0){
			
			$msg = "<span style='color:red'>Channel info already exist</span>";
			
		}else{
			
			//update query

			$stmt22 = $dbcon->prepare('UPDATE channel_info SET channel_id = "'.$channel_id.'", channel_name = "'.$channel_name.'", octo_url = "'.$octo_url.'", octo_js = "'.$octo_js.'" WHERE id = '.$id, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	 
		$stmt22->execute();
			
			
			$msg = "<span style='color:green'>Channel Info updated Successfully</span>";
			
									
		}		
		
	}	


if(isset($_GET['edit'])){

		$id = $_GET['id'];		

		$stmt1 = $dbcon->prepare("SELECT * FROM channel_info where id='".$id."'", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt1->execute();
	$result1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
	foreach($result1 as $row1){
			$channel_id1=$row1['channel_id'];
			$channel_name1=$row1['channel_name'];
			$octo_url1=$row1['octo_url'];
			$octo_js1=$row1['octo_js'];
			$octo_js1=stripslashes($octo_js1);
		}
	
		$action = "Edit";

	}	

include("header-admin.php");
	?>

<style>
/* define height and width of scrollable area. Add 16px to width for scrollbar          */
div.tableContainer {
    clear: both;
    border: 1px solid #963;

}

/* Reset overflow value to hidden for all non-IE browsers. */
html>body div.tableContainer {
    overflow: hidden;
    width: 756px
}

/* define width of table. IE browsers only                 */
div.tableContainer table {
    float: left;
    width: 740px
}

/* define width of table. Add 16px to width for scrollbar.           */
/* All other non-IE browsers.                                        */
html>body div.tableContainer table {
    width: 756px
}

thead.fixedHeader tr {
    position: relative
}
html>body thead.fixedHeader tr {
    display: block
}

thead.fixedHeader th {
    background: #C96;
    border-left: 1px solid #EB8;
    border-right: 1px solid #B74;
    border-top: 1px solid #EB8;
    font-weight: normal;
    padding: 4px 3px;
    text-align: left
}

html>body tbody {
    display: block;
    width: 100%;
}
    
html>body tbody.scrollContent {
    height: 262px;
    overflow: auto;
}

tbody.scrollContent td, tbody.scrollContent tr.normalRow td {
    background: #FFF;
    border-bottom: none;
    border-left: none;
    border-right: 1px solid #CCC;
    border-top: 1px solid #DDD;
    padding: 2px 3px 3px 4px
}

tbody.scrollContent tr.alternateRow td {
    background: #EEE;
    border-bottom: none;
    border-left: none;
    border-right: 1px solid #CCC;
    border-top: 1px solid #DDD;
    padding: 2px 3px 3px 4px
}

html>body thead.fixedHeader th {
    width: 200px
}

html>body thead.fixedHeader th + th {
    width: 240px
}

html>body thead.fixedHeader th + th + th {
    width: 316px
}
html>body tbody td {
    width: 200px
}

html>body tbody td + td {
    width: 240px
}

html>body tbody td + td + td {
    width: 300px
}


</style>

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
		<?php if($action=="Edit") { ?> <a href='http://ideabytestraining.com/newqezyplay/qp/channel_info_management.php?'><button>Back</button></a> <?php } ?>
	</div>

	<div class="clear"></div>

	<br />

	<div id="ChannelList" align="center" style="margin:0 auto"><h2>Channels List Info</h2> 
		<div id="tableContainer" class="tableContainer">
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="scrollTable widefat membership-levels">
<thead class="fixedHeader">
		
			
				<tr>					
					<th>Channel Id</th>
					<th>Channel Name</th>
					<th>Octo URL</th>
					<th>Octo JS</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody class="ui-sortable  scrollContent">
			<?php
			$stmt4 = $dbcon->prepare("SELECT * FROM channel_info", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
			$stmt4->execute();
			$result4 = $stmt4->fetchAll(PDO::FETCH_ASSOC);

			foreach($result4 as $row){

				$id=$row['id'];
				$channel_id=$row['channel_id'];
				$channel_name=$row['channel_name'];
				$octo_url=$row['octo_url'];
				$octo_js=$row['octo_js'];
				$octo_js=stripslashes( $octo_js );

				
			?>
				<tr style="" class="ui-sortable-handle">
				<td style="width: 1px;" class="level_name"><?php echo $channel_id;?></td>
				<td style="width: 332px;"><?php echo $channel_name;?></td>
				<td style="width: 392px;"><?php echo $octo_url?></td>
				<td style="width: 350px;"><?php echo $octo_js;?></td>
				<td style="width: 332px;"><a style="cursor: pointer;" id="editChannel" href="channel_info_management.php?edit=true&id=<?php echo $id;?>" title="edit" name="Edit-<?php echo $id ?>" class="button-primary">Edit</a>&nbsp;
				<a style="cursor: pointer;" title="delete" name="removeChannel-<?php echo $id;?>" id="removeChannel-<?php echo $id;?>" onclick="callConfirmation('channel_info_management.php?del=true&id=<?php echo $id;?>');" class="button-secondary">Delete</a></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div></div>
	<div class="clear"></div><br /><br />


	<script>
	function callConfirmation(url){

		var ans = confirm("Sure, do you want to delete this channel info?");
		if(ans){
			window.location.href = url;
		}
	}
	</script>

<?php include("footer-admin.php"); ?>


