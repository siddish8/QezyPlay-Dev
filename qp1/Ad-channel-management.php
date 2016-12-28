<?php 
include('header.php');
?>
<article class="content items-list-page">

<?php
//added for channel_creation
//include('db-config.php');
//include('function_common.php');

require('../qp/phpxmlrpc-4.0.0/lib/xmlrpc.inc');
require('../qp/Wordpress-XML-RPC-Library-master/new-post.php');

include('../wp-admin/includes/image.php');

$globalerr = null;

$xmlrpcurl = SITE_URL.'/xmlrpc.php';
$username = 'qezyplay';
$password = '*&(qezyplay)*';

//end

$msg = ""; $action = "Add";

if(isset($_REQUEST['delEpg'])){

	$id = $_REQUEST['delEpgid'];


	$stmt11 = $dbcon->prepare("DELETE FROM epg_info WHERE id = ".$id."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt11->execute();

	$stmt22 = $dbcon->prepare("DELETE FROM epg_timing_info WHERE epg_id = ".$id."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt22->execute();

	$msg = "<span id='msg' style='color:green'>EPG info deleted Successfully</span>";
	
}


if(isset($_POST['Add_Submit'])){

//$channel=trim($_POST['channel']);
//$progname=trim($_POST['programname']);
//$genre=trim($_POST['genre']);		
//$number=trim($_POST['number']);
		
$date = gmdate("Y-m-d H:i:s");

$title = trim($_POST['channel_name']);//'NEW6';
$content = trim($_POST['channel_desc']);//'hi new is test 6.';
$categories=trim($_POST['channel_cat']);//'Trailer VODs';
$keywords=trim($_POST['channel_tags']);//"Sports, All";
$slug=trim($_POST['channel_metadata']);//chn-1
$time_video=trim($_POST['channel_dur']);//'LIVE';

$vid=trim($_POST['channel_vidurl']); // 51/auto
$vod=trim($_POST['channel_vodurl']); // ch51-vod.mp4

$tm_video_code='[qp_channel vidurl=\''.$vid.'\' vodurl=\''.$vod.'\']';//'[qp_channel vidurl=\'51/auto\' vodurl=\'ch51-vod.mp4\']';

$octo_url='octoshape://streams.octoshape.net/ideabytes/live/ib-'.$vid.'';
$vod_url='octoshape://streams.octoshape.net/ideabytes/vod/'.$vod.'';

$octo_js=trim($_POST['channel_vidjs']);//'var _0x8d8c=["\x6F\x63\x74\x6F\x73\x68\x61\x70\x65\x3A\x2F\x2F\x73\x74\x72\x65\x61\x6D\x73\x2E\x6F\x63\x74\x6F\x73\x68\x61\x70\x65\x2E\x6E\x65\x74\x2F\x69\x64\x65\x61\x62\x79\x74\x65\x73\x2F\x6C\x69\x76\x65\x2F\x69\x62\x2D\x63\x68\x33\x32\x2F\x61\x75\x74\x6F"];var streamURL=_0x8d8c[0]';
$vod_js=trim($_POST['channel_vodjs']);//'var _0x1b26=["\x6F\x63\x74\x6F\x73\x68\x61\x70\x65\x3A\x2F\x2F\x73\x74\x72\x65\x61\x6D\x73\x2E\x6F\x63\x74\x6F\x73\x68\x61\x70\x65\x2E\x6E\x65\x74\x2F\x69\x64\x65\x61\x62\x79\x74\x65\x73\x2F\x76\x6F\x64\x2F\x73\x61\x74\x76\x2E\x66\x6C\x76"];var streamURL=_0x1b26[0]';

$status=$publish=trim($_POST['channel_status']);//1;


$post_settings='a:1:{s:10:"vc_grid_id";a:0:{}}'; //default setting
$edit_last='1'; //default admin_id=1

$date=new DateTime("now");
$updated_datetime=$created_datetime=$date->format("Y-m-d H:i:s");
$edit_lock="1477674175:1";
$thumbnail_id="";
$slide_template="default";
$tm_multi_link="";
$show_feature_image="2";
$page_layout="def";
$single_ly_ct_video="def";
$ct_bg_repeat="no-repeat";
$wp_old_slug="";

$customfields= 	array(

		array(	"key"=>'_vc_post_settings', "value"=>$post_settings ),
		array(	"key"=>'vc_post_settings', "value"=>$post_settings ),
		//array(	"key"=>'time_video', "value"=>"LIVE" ),
		array(	"key"=>"_edit_last", "value"=>$edit_last),
		array(	"key"=>"_edit_lock", "value"=>$edit_lock),
		array(	"key"=>"_thumbnail_id", "value"=>$thumbnail_id	),
		array(	"key"=>"slide_template", "value"=>$slide_template),
		array(	"key"=>"tm_multi_link", "value"=>$tm_multi_link	),
		array(	"key"=>"show_feature_image", "value"=>$show_feature_image ),
		array(	"key"=>"page_layout", "value"=>$page_layout),
		array(	"key"=>"single_ly_ct_video", "value"=>$single_ly_ct_video ),
		array(	"key"=>"ct_bg_repeat", "value"=>$ct_bg_repeat	),
		array(	"key"=>"tm_video_code", "value"=>$tm_video_code	),	
		array(	"key"=>"_wp_old_slug", "value"=>$wp_old_slug),

			);


		$post = wordpress_new_post($xmlrpcurl, $username, $password, $blogid = 0, $slug, $wp_password="", $author_id = "1", $title, $content, $excerpt, $text_more, $keywords, $allowcomments = "0", $allowpings = "0", $pingurls, $categories, $date_created = '', $customfields, $publish, $proxyipports = "");

	if($post == false){
	    echo $globalerr."\n";
	    die();
	}
	else {
	    print_r($post);  

	// 1 for standard
	//post-format =2 for video

	//$sql1="UPDATE wp_term_relationships SET term_taxonomy_id=2 where object_id=$post and term_taxonomy_id= ";
	$sql1="INSERT INTO wp_term_relationships(object_id,term_taxonomy_id) values($post,2) ";
	$stmt1=$dbcon->prepare($sql1,array(PDO::ATTR_CURSOR=>PDO::CURSOR_SCROLL));
	$stmt1->execute();

	//post settings
	$sql2="UPDATE wp_postmeta SET meta_key='_vc_post_settings' where post_id=$post and meta_key='vc_post_settings'";
	$stmt2=$dbcon->prepare($sql2,array(PDO::ATTR_CURSOR=>PDO::CURSOR_SCROLL));
	$stmt2->execute();

	//DURATION
	$sql5="UPDATE wp_postmeta SET meta_value='$time_video' where post_id=$post and meta_key='time_video'";
	$stmt5=$dbcon->prepare($sql5,array(PDO::ATTR_CURSOR=>PDO::CURSOR_SCROLL));
	$stmt5->execute();

	 
	
	
		//echo "NEW";
		if ($_FILES["logofile"]["error"] > 0){
			$msg = "<span style='color:red'>Problem with Channel logo1. Please try again.</span>";
		}else{	
				
			$present_time = date("dmYHis");
			$file_name = $_FILES["logofile"]["name"];
			$image_extention = explode("/",$_FILES["logofile"]["type"]);
			//$sNewFileName = $present_time.".".$image_extention[1];
				$sNewFileName = $file_name;
				
									
			if(move_uploaded_file($_FILES["logofile"]["tmp_name"],MEDIA_FOLDER."/".$sNewFileName)){			
		
			
				$imageurl=DATED_PATH."/".$sNewFileName;
			
			//THUMBNAIL or FEATURED IMAGE

			$attach_id=($post+2);

			$sql6="INSERT INTO wp_postmeta(post_id,meta_key,meta_value) values(".$post.",'_thumbnail_id',".$attach_id.")";
			$stmt6=$dbcon->prepare($sql6,array(PDO::ATTR_CURSOR=>PDO::CURSOR_SCROLL));
			$stmt6->execute();

			$sql7="INSERT INTO wp_postmeta(post_id,meta_key,meta_value) values(".$attach_id.",'_wp_attached_file','".$imageurl."')";
			$stmt7=$dbcon->prepare($sql7,array(PDO::ATTR_CURSOR=>PDO::CURSOR_SCROLL));
			$stmt7->execute();


				//$imageurl=MEDIA_FOLDER."/".$sNewFileName;

			 //$attach_data = wp_generate_attachment_metadata( $attach_id, $imageurl );
			//  wp_update_attachment_metadata( $attach_id,  $attach_data );


			//insert into channels,boqs, boq vs channels

			$sql3="INSERT into channels(id,name,url,octo_js,vodurl,vod_octo_js,imageurl,meta_data,meta_description,status,updated_datetime,created_datetime,image2xurl,image3xurl,imagehdpiurl,imageldpiurl,
			imagemdpiurl,imagexhdpiurl,imagexxhdpiurl,imagexxxhdpiurl,downloadUrl,category) values(".$post.",'".$title."','".$octo_url."','".$octo_js."','".$vod_url."','".$vod_js."','".$imageurl."','".$slug."','".$content."','".$status."','".$updated_datetime."','".$created_datetime."','".$image2."','".$image3."','".$imagehd."','".$imageld."','".$imagemd."','".$imagexhd."','".$imagexxhd."','".$imagexxxhd."','".$dldurl."','".$keywords."')";
			$stmt3=$dbcon->prepare($sql3,array(PDO::ATTR_CURSOR=>PDO::CURSOR_SCROLL));
			$stmt3->execute();

			echo $boq_id=get_var("SELECT term_id FROM wp_term_taxonomy a inner join wp_term_relationships b on a.term_id=b.term_taxonomy_id where a.taxonomy='category' and b.object_id=$post");

			$sql4="INSERT into bouquet_vs_channels(bouquet_id,channel_id,created_datetime,updated_datetime) values(".$boq_id.",".$post.",'".$created_datetime."','".$updated_datetime."') ";
			$stmt4=$dbcon->prepare($sql4,array(PDO::ATTR_CURSOR=>PDO::CURSOR_SCROLL));
			$stmt4->execute();
		
		

		$msg = "<span style='color:green'>Channel added Successfully</span>";
					
			} 
			else{					
				$msg = "<span style='color:red'>Problem with Program logo2. Please try again.</span>";
			}
			
		}			

	}	
}


if(isset($_POST['Edit_Submit'])){

	$epgid=trim($_POST['epgid']);

	
	$channel=trim($_POST['channel']);
	$progname=trim($_POST['programname']);
	$genre=trim($_POST['genre']);
		
	$number=trim($_POST['number']);

	
	$timing="";
	for($i=1;$i<=$number;$i++)
		{
			$timing.=$_POST['day-'.$i]."-".$_POST['time-'.$i].",";
		}
	$timing=rtrim($timing,',');
	
	$date = gmdate("Y-m-d H:i:s");
	
	$stmt21 = $dbcon->prepare('SELECT * FROM epg_info WHERE WHERE id != '.$epgid.' AND (channel = "'.$channel.'" AND programname = "'.$progname.'") ', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
	$stmt21->execute();
	$result21 = $stmt21->fetchAll(PDO::FETCH_ASSOC);
	$epgexist = count($result21);	
//echo $userexit;	
//exit;
	if((int)$epgexist > 0){

		$msg1="";
		

		
			$msg1.= "<i class='fa fa-arrow-right' aria-hidden='true'></i> EPG info already exist <br>";

			$msg = "<span id='msg' style='color:red'>".$msg1."</span>";
		
	}else{
		if ($_FILES["logofile"]["error"] > 0 && $_FILES["logofile"]["error"] != "4"){
			$msg = "<span style='color:red'>Problem with Program logo3. Please try again.</span>";
		}else{	

			if($_FILES["logofile"]["name"] !=""){

				$select_q_content = "SELECT * FROM epg_info WHERE `id` = :id";
				$select_query = $dbcon->prepare($select_q_content);
				$select_query->bindParam(":id",$epgid);
				$select_query->execute();
				if($select_query->rowCount() > 0){
					$cData = $select_query->fetch(PDO::FETCH_ASSOC);

					if($cData["program_logo_url"]!=""){
						$req_image_path = MEDIA_FOLDER."/".$cData["program_logo_url"];
						if (file_exists($req_image_path)){
							unlink($req_image_path);
						}
					}		
				}
				
				$present_time = date("dmYHis");
			$file_name = $_FILES["logofile"]["name"];
			$image_extention = explode("/",$_FILES["logofile"]["type"]);
			//$sNewFileName = $present_time.".".$image_extention[1];
				$sNewFileName = $file_name;
				
									
			if(move_uploaded_file($_FILES["logofile"]["tmp_name"],MEDIA_FOLDER."/".$sNewFileName)){	

					//update query
					$stmt22 = $dbcon->prepare('UPDATE epg_info SET channel = "'.$channel.'", programname = "'.$progname.'", genre = "'.$genre.'", program_logo_url = "'.MEDIA_FOLDER_LINK.'/'.$sNewFileName.'", created_datetime = "'.$date.'" WHERE id = '.$epgid, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	 
					$stmt22->execute();


					$stmt24 = $dbcon->prepare('DELETE FROM epg_timing_info WHERE epg_id = '.$epgid.'', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
		$stmt24->execute();
		
		$timingsArray =explode(',',$timing); //added
		foreach($timingsArray as $timesAr)
		{
			$timeAr=explode('-',$timesAr);
			
			$stmt23 = $dbcon->prepare('INSERT INTO epg_timing_info(epg_id,day,time) VALUES("'.$epgid.'","'.$timeAr[0].'","'.$timeAr[1].'")', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
			$stmt23->execute();
		}

					$msg = "<span style='color:green'>EPG info updated Successfully</span>";

				} else{					
					$msg = "<span style='color:red'>Problem with program logo4. Please try again.</span>";
				}

			}else{

				//update query
		$stmt22 = $dbcon->prepare('UPDATE epg_info SET channel = "'.$channel.'", programname = "'.$progname.'", genre = "'.$genre.'",created_datetime = "'.$date.'" WHERE id = '.$epgid, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	 
		$stmt22->execute();
		
		$stmt24 = $dbcon->prepare('DELETE FROM epg_timing_info WHERE epg_id = '.$epgid.'', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
		$stmt24->execute();
		
		$timingsArray =explode(',',$timing); //added
		foreach($timingsArray as $timesAr)
		{
			$timeAr=explode('-',$timesAr);
			
			$stmt23 = $dbcon->prepare('INSERT INTO epg_timing_info(epg_id,day,time) VALUES("'.$epgid.'","'.$timeAr[0].'","'.$timeAr[1].'")', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
			$stmt23->execute();
		}
		$msg = "<span style='color:green'>EPG info updated Successfully</span>";
								
	}		
	
	}	
}

}
if(isset($_REQUEST['editEpg'])){

	$id = $_REQUEST['editEpgid'];		

//echo $id;
//exit;
	$stmt1 = $dbcon->prepare("SELECT * FROM epg_info where id='".$id."'", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt1->execute();
	$result1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
	foreach($result1 as $row1){
		$epgid1=$id;
		$channel1=$row1['channel'];
		$progname1=$row1['programname'];
		$genre1=$row1['genre'];
		$logo1=$row1['program_logo_url'];
	}

	$stmt2 = $dbcon->prepare("SELECT group_concat(concat(day,'-',time)) as timing FROM epg_timing_info where epg_id='".$id."'", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
	$stmt2->execute();
	$result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
	$timing1 = $result2['timing'];
	$times=explode(',', $timing1);
	$no_times=sizeof($times);
	//$location1 = "AA-BB-CC,DD-EE-FF,";
	//echo $no_locs=sizeof($locs);

		
			
	$action = "Edit";

}	

?>

<style>
.xoouserultra-field-value, .xoouserultra-field-type{
	width: unset !important;
	padding: 5px;
}

form[name="f_timezone"] {
    display: none;
}

#search_epg > input[type="text"] {
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
<h2 style="text-align:center">Channel Management</h2>
<?php		if($_SESSION['adminlevel']<1)
{ ?>	
<section class="section">
<span style="float:"></span><div class="msg" align="center" style="display:inline-block"><h4><?php echo $msg;?></h4></div>
                        <div class="row sameheight-container">
				 <div class="col-md-3">
                             
                            </div>
                            <div class="col-md-6">
                                <div id="epg-form-section" class="card card-block sameheight-item" style="height:auto;/*height: 721px;*/">
                                   <div class="card card-primary">
					<div class="card-header">
                                        <div class="header-block">
                                            <p class="title"> <?php echo $action;?> Channel </p>
                                        </div>
                                    </div>
				     <div class="xoouserultra-field-value" style="">
						<div id='error' style='color:red;'";></div>
					</div>
					<div class="card-block">
                                    <form role="form" method='post' enctype="multipart/form-data">
					<input type="hidden" id="chanid" name="chanid" value="<?php echo $id; ?>"/>
                                        <div class="form-group"> <label class="control-label">Channel Name</label> <input onclick="clearError()" type="text" value="<?php echo @$channame1;?>" id="channel_name" name="channel_name" class="form-control underlined"> </div> 

					<div class="form-group"> <label class="control-label">Channel Description</label> <textarea onclick="clearError()" rows="4" cols="20" value="<?php echo @$chandesc1;?>" placeholder="Ex: This channel is top E-language" id="channel_desc" name="channel_desc" class="form-control underlined"></textarea> </div>

					<div class="form-group"> <label class="control-label">Logo (path)</label> 
					<?php if($logo1 !="" ) { echo '<img src="'.@$logo1.'" style="height:100px;width:200px;">'; } ?>
					<input onclick="clearError()" type="file" accept="image/*" id="logofile" name="logofile" class="form-control underlined"> </div>


					<div class="form-group"> <label class="control-label">Channel URL</label> <input onclick="clearError()" type="text" alt="" placeholder="Ex: 35/auto" value="<?php echo @$chanvidurl1;?>" id="channel_vidurl" name="channel_vidurl" class="form-control underlined"><input type="button" onclick="get_octo()" value="Get Full URL"/><span id="octo_url_full"></span> </div> 

					

					<div class="form-group"> <label class="control-label">Channel URL(JS)</label> <textarea rows="4" cols="50"  onclick="clearError()" type="text" alt=""  value="<?php echo @$chanvidjs1;?>" id="channel_vidjs" name="channel_vidjs" class="form-control underlined"></textarea>*Click on "Get Full URL", copy the code and click "Go For JS" and use it there to get js</div> 


					<div class="form-group"> <label class="control-label">Channel VOD URL</label> <input onclick="clearError()" type="text" alt="" placeholder="Ex: ch35.flv" value="<?php echo @$chanvodurl1;?>" id="channel_vodurl" name="channel_vodurl" class="form-control underlined"><input type="button" onclick="get_vod_octo()" value="Get Full URL"/><span id="vod_octo_url_full"></span> </div> 

				
					<div class="form-group"> <label class="control-label">Channel VOD URL(JS)</label> <textarea rows="4" cols="50"  onclick="clearError()" type="text" alt=""  value="<?php echo @$chanvodjs1;?>" id="channel_vodjs" name="channel_vodjs" class="form-control underlined"></textarea>*Click on "Get Full URL", copy the code and click "Go For JS" and use it there to get js</div>


					<div class="form-group"> <label class="control-label">Channel Link/Meta</label> <input onclick="clearError()" type="text" value="<?php echo @$chanmeta1;?>" id="channel_metadata" name="channel_metadata" class="form-control underlined"> </div> 

					<div class="form-group"> <label class="control-label">Channel Play Duration</label> <input onclick="clearError()" type="text" placeholder="Ex1: LIVE  Ex2: 30:56" value="<?php echo @$channame1;?>" id="channel_dur" name="channel_dur" class="form-control underlined"> </div> 

					<div class="form-group"> <label class="control-label">Channel Status</label> 
					<select name="channel_status" alt="" id="channel_status" onclick="clearError()" class="form-control underlined">
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

					<div class="form-group"> <label class="control-label">Channel category</label> <select name="channel" alt="" id="channel" onclick="clearError()" class="form-control underlined">
									<option value="">-Select Category-</option>
									<?php
									$stmt5 = $dbcon->prepare("SELECT a.name FROM wp_terms a inner join wp_term_taxonomy b on a.term_id=b.term_taxonomy_id where b.taxonomy='category' and a.term_id>1;", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
									$stmt5->execute();
									$result5 = $stmt5->fetchAll(PDO::FETCH_ASSOC);

									foreach($result5 as $row){
										$selected = ($row['name'] == $chancat1) ? "selected='selected'" : "";
										echo "<option value='".$row['name']."'".$selected.">".$row['name']."</option>";
									}
									?>
								</select> <span>Do you wish to create New Category <a href="Boq-mgmtn.php">Click Here; 1. 1.Create 2.Comeback 3.Refresh </a></div>	


                                           <div class="form-group"> <label class="control-label">Channel tags</label> <input onclick="clearError()" type="text" placeholder="Ex1: Entertainment,Music" value="<?php echo @$chantags1;?>" id="channel_tags" name="channel_tags" class="form-control underlined"> </div> 
                                        
					<div class="xoouserultra-field-value" style="padding-left: 10px;">
								<?php if($action=="Edit"){?>
								<input type="hidden" name="editChan"  value="true" />
								<input type="hidden" name="editChanid"  value="<?php echo @$epgid1;?>" />
								<?php } ?>
								<input class="btn btn-primary" type="submit" onclick="return callsubmit();" name="<?php echo $action;?>_Submit"  value="Submit" />
								<?php if($action=="Edit"){?>
								<a class="btn btn-primary" href="<?php echo SITE_URL.'/qp1/Ad-channel-management.php'?>">Back</a>
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

<!-- <section class="section">
 <div class="col-md-3">
                             
                            </div>
<div id="search_epg" align="center" style="margin:15px auto;" class="col-md-6">
		Search EPG List using any criteria: <br /> 
		<div class="form-group"> 
		<input placeholder="Enter agent-name" id="searchAN" type="text" name="searchAN" class="form-control boxed"><span class="_or"> </span> 
	</div>
	<div class="form-group">   
		<input placeholder="Enter agent-phone" id="searchPH" type="text" name="searchPH" class="form-control boxed" /> <span class="_or"> </span>
	</div>
	<div class="form-group"> 
		<input placeholder="Enter agent-email" id="searchEM" type="text" name="searchEM" class="form-control boxed" /> 
			</div>	
		</div>
 <div class="col-md-3">
                             
                            </div>
   </section>
-->
<section class="section">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-block">
                                        <div class="card-title-block">
                                            <h3 class="title">
							Channels
						</h3> </div>
                                        <section class="example">		
		<form id="frm2" method="post">
		 <div class="table-responsive">
                                                <table class="table table-striped table-bordered table-hover">
		
			<thead>
				<tr>	
					<?php		if($_SESSION['adminlevel']<1)
{ ?> <th>Channel ID</th> <?php } ?>
					<th>Channel Name</th>
					<th>Logo</th>				
					<th>Description</th>
					<th>Bouquet</th>
					<th>Metadata</th>
					<th>Tags/Categories</th>
					<!-- <th>Duration</th> -->
					<th>VID URL</th>
					<th>VID JS</th>
					<th>VOD URL</th>
					<th>VOD JS</th>
					<th>Status</th>
					
					<?php		if($_SESSION['adminlevel']<1)
{ ?> <th> Action  <i class="fa fa-cog"></i></th> <?php } ?>
				</tr>
			</thead>
			<tbody id="chan_list" class="ui-sortable">
			<?php

			$stmt4 = $dbcon->prepare("SELECT * FROM channels", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
			$stmt4->execute();
			$result4 = $stmt4->fetchAll(PDO::FETCH_ASSOC);
			
			foreach($result4 as $row){

				$id=$row['id'];
				$channelname = $row['name'];
				$logo=$row['imageurl'];
				$desc=$row['meta_description'];
				$cat=get_var("SELECT a.name FROM tradmin_newqezy.bouquets a inner join bouquet_vs_channels b on a.id=b.bouquet_id where b.channel_id=$id");
				$meta_data=$row['meta_data'];
				$vidurl=$row['url'];
				$vodurl=$row['vodurl'];
				$vidjs=$row['octo_js'];
				$vodjs=$row['vod_octo_js'];
				$status=$row['status'];

				$status=$status==1?"Active":"Inactive";

				$c_time=$row['created_datetime'];
				$u_time=$row['updated_datetime'];
				$tags=$row['category'];
								
				
								
			?>
				<tr style="" class="ui-sortable-handle">	
				<?php		if($_SESSION['adminlevel']<1)
{ ?> <td><?php echo $id;?></td> <?php } ?>
				<td style="width: 200px;" class="level_name"><?php echo $channelname;?></td>						
				<td style="width: 250px;"><img height="50px" width="150px" src="<?php echo SITE_URL.'/'.UPLOAD_FOLDER.'/'.$logo;?>"></td>
				<td style="width: 200px;" class="level_name"><?php echo $desc;?></td>
				
				<td style="width: 300px;"><?php echo $cat ?></td>
				<td style="width: 185px;"><?php echo $meta_data;?></td>
				<td style="width: 185px;"><?php echo $tags;?></td>
				<td style="width: 185px;"><?php echo $vidurl;?></td>
				<td style="width: 185px;"><?php echo $vidjs;?></td>
				<td style="width: 185px;"><?php echo $vodurl;?></td>
				<td style="width: 185px;"><?php echo $vodjs;?></td>
				<td style="width: 185px;"><?php echo $status;?></td>
				
<?php		if($_SESSION['adminlevel']<1)
{ ?>
				
				<td style="width: 100px;">

<a style="cursor:pointer;color:blue;" id="editEpg" name="editEpg" onclick="return confirmEditEpg(<?php echo $id;?>)"><em title="Edit" class="fa fa-pencil"></em></a>
&nbsp;
<a style="cursor:pointer;color:blue;" id="removeEpg" name="removeEpg" onclick="return confirmDelEpg(<?php echo $id;?>)"><i title="Remove" class="fa fa-trash-o "></i></a>				

</td>
<?php } ?>				
</tr>
			<?php } ?>
			</tbody>
			<tr id="no_res_div" align="center" style="margin: 5px auto;
    border-bottom: 1px solid #999;
    max-width: 80%;
    font-size: 15px;"><td colspan="6" id="no_res" style="margin:0 auto;text-align:center"></td></tr>
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
	
	$("#epg-form-section").css("height","auto");
	
	var genre = $("#genre").val();
	var channel = $("#channel").val();
	var progname = $("#programname").val();
	//var timing = $("#timing").val();
	var logo = jQuery("#logofile").val();


	if(channel == ""){
		$("#error").html("Please enter channel name");
		return false;		
	}
			
	if(progname == ""){
		$("#error").html("Please enter program name");
		return false;		
	}


	 if(genre == ""){
		$("#error").html("Please enter any genre");	
		return false;	
	}

	//if(timing == ""){
	//	$("#error").html("Please enter program timing information");	
	//	return false;		
	//}

	if(channel != "" && progname  != "" && genre != ""){
		
				return true;
			
			
	}
	return false;

}



function clearError()
{

		$("#error").html("");
		$(".msg h4").html("");		
	
}


function findRows(table,searchText1,col1,searchText2,col2,searchText3,col3) {

//var table=document.querySelector("#sub_list > tr:visible");
//var table=$('#sub_list tr:visible');
    var rows = table.rows,
        r = 0,
        found = false,
        anyFound = false;
       // var found1,found2,found3;
        //alert(rows.length);
        //alert(searchText1+"in"+col1);
       // alert(searchText2+"in"+col2);
       // alert(searchText3+"in"+col3);
       //  alert(searchText4+"in"+col4);
       // alert(searchText5+"in"+col5);
        //alert(searchText1+"in"+col1);

//alert($('#sub_list tr:visible').length);
	
  var found1=false,found2=false,found3=false;
  
    for (; r < rows.length; r += 1) {
        row = rows.item(r);
          
     // var i=column;
       // alert(searchText1+"in col:"+col1+"  row::"+r);
        
        if(col1!=99)
        found1 = (row.cells.item(col1).textContent.toLowerCase().indexOf(searchText1.toLowerCase().trim()) !== -1);
        else
        found1=true;
         if(col2!=99)
          found2 = (row.cells.item(col2).textContent.toLowerCase().indexOf(searchText2.toLowerCase().trim()) !== -1);else
        found2=true;
          if(col3!=99)
            found3 = (row.cells.item(col3).textContent.toLowerCase().indexOf(searchText3.toLowerCase().trim()) !== -1);else
        found3=true;
          
            
            found=found1 && found2 && found3;
        anyFound = anyFound || found;
//alert(found);
			//if(row.style.display=="none")
      	//	found=false;
        row.style.display = found ? "table-row" : "none";
       }
        
	   
	if(col1==99 && col2==99 && col3==99)
	{
		for (; r < rows.length; r += 1) {
			row = rows.item(r);
			 row.style.display = "table-row" ;}
	}
    //document.getElementById('no_res').style.display = anyFound ? "none" : "block";
var x = document.getElementById("epg_list").rows.length;
//alert("x"+x);
var cnt=0;

for(i=0;i<x;i=i+1)
{
 var y=document.getElementById("epg_list").rows[i].style.display;

if(y=="none")
cnt=cnt+1;
}

//alert("cnt:"+cnt);
if(x==(cnt))
{
document.getElementById("no_res").innerHTML="NO SEARCH RESULTS";
$("#no_res").html("NO SEARCH RESULTS");
$("#no_res_div").show();
}
else
{
$("#no_res").html("");
$("#no_res_div").hide();
}

}

function performSearch() {
    var searchText1 = document.getElementById('searchAN').value,
        searchText3 = document.getElementById('searchEM').value,
        searchText2= document.getElementById('searchPH').value,
        targetTable = document.getElementById('epg_list');
	var searchText=[],col;
	
	if(searchText1!="")
	{
	searchText[0]=searchText1;
	col1=0;
	}
  else{col1=99;}
  
  
	if(searchText2!="")
	{
	searchText[1]=searchText2;
	col2=1;
	}
  else{col2=99;}
  
  
 if(searchText3!="")
	{
	searchText[2]=searchText3;
	col3=3;
	}
  else{col3=99;
}

  	

	//alert(searchText);
    findRows(targetTable,searchText1,col1,searchText2,col2,searchText3,col3);

}

//document.getElementById("search").onclick = performSearch;
//document.getElementById("searchAN").onkeyup = performSearch;
//document.getElementById("searchEM").onkeyup = performSearch;
//document.getElementById("searchPH").onkeyup = performSearch;


function confirmDelEpg(Delid)
{

 swal({
 		title:' ', 
  text: 'Do you really want to remove this EPG info?',
  type: 'warning',
  
  showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Remove",   cancelButtonText: "Cancel",  closeOnCancel: true
},function(){ 

var input1 = $("<input>")
               .attr("type", "hidden")
               .attr("name", "delEpgid").val(Delid);
var input2 = $("<input>")
               .attr("type", "hidden")
               .attr("name", "delEpg").val(true);
$('#frm2').append($(input1));
$('#frm2').append($(input2));
 $("#frm2").submit();

}); 

}

function confirmEditEpg(Editid)
{

 swal({
 		title:' ', 
  text: 'Do you want to edit this EPG info?',
  type: 'warning',
  
  showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Edit",   cancelButtonText: "Cancel",  closeOnCancel: true
},function(){ 

var input1 = $("<input>")
               .attr("type", "hidden")
               .attr("name", "editEpgid").val(Editid);
var input2 = $("<input>")
               .attr("type", "hidden")
               .attr("name", "editEpg").val(true);
$('#frm2').append($(input1));
$('#frm2').append($(input2));
 $("#frm2").submit();

}); 

}




function add_timing_field()
{

var i;
i=$("#number").val();
j=parseInt(i)+1;
$("#number").val(j);


var head=$("<p>")
		.html("Timing "+j+":");		
var input01 =$("<label>")
		.attr("class","control-label").html("Day");


var input1 = $ ("<select>")
			.attr("id", "day-"+j)
               .attr("name", "day-"+j)
		.attr("onclick", "clearError()")
		.attr("class", "form-control underlined")
		.append($('<option>', {
    value: "",
    text: '-Select Day-'
})).append($('<option>', {
    value: "1",
    text: 'Sunday'
})).append($('<option>', {
    value: "2",
    text: 'Monday'
})).append($('<option>', {
    value: "3",
    text: 'Tuesday'
})).append($('<option>', {
    value: "4",
    text: 'Wednesday'
})).append($('<option>', {
    value: "5",
    text: 'Thursday'
})).append($('<option>', {
    value: "6",
    text: 'Friday'
})).append($('<option>', {
    value: "7",
    text: 'Saturday'
})).append($('<option>', {
    value: "99",
    text: 'Daily'
}));

/*var input1 = $("<input>")
		.attr("type", "text")
		.attr("id", "day-"+j)
               .attr("name", "day-"+j)
		.attr("onclick", "clearError()")
		.attr("class", "form-control underlined");*/

var input02 =$("<label>")
		.attr("class","control-label").html("Time");
var input2 = $("<input>")
		.attr("type", "text")
               .attr("id", "time-"+j)
               .attr("name", "time-"+j)
		.attr("onclick", "clearError()")
		.attr("class", "form-control underlined");


$("#time").append(head);
$("#time").append(input01);
$("#time").append(input1);
$("#time").append(input02);
$("#time").append(input2);

$("#epg-form-section").css("height","auto");



}

function get_octo()
{
var octo=jQuery("#channel_vidurl").val();
octo="var streamURL='octoshape://streams.octoshape.net/ideabytes/live/ib-ch"+octo+"'";
octo=octo+"<br><a target='_blank' href='https://javascriptobfuscator.com/Javascript-Obfuscator.aspx'>Go for JS</a>"
jQuery("#octo_url_full").html(octo);
}

function get_vod_octo()
{
var octo=jQuery("#channel_vodurl").val();
octo="var streamURL='octoshape://streams.octoshape.net/ideabytes/vod/"+octo+"'";
octo=octo+"<br><a target='_blank' href='https://javascriptobfuscator.com/Javascript-Obfuscator.aspx'>Go for JS</a>"
jQuery("#vod_octo_url_full").html(octo);
}

$("#epg-form-section").css("height","auto");

if($("tr#no_res_div td").html()=="")
	$("tr#no_res_div").hide();
</script>
</article>
<?php
include('footer.php');
?>
