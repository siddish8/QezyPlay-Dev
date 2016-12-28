<?php 
if(! defined( 'ABSPATH' )) exit;	
global $wpdb;

function set_html_content_type2(){
	return 'text/html';
}

function contact_form_validation_callback(){
	function wpse_14108_upload_dir( $dir ) {
					return array(
						'path'   => $dir['basedir'] . '/mycustomdir',
						'url'    => $dir['baseurl'] . '/mycustomdir',
						'subdir' => '/mycustomdir',
					) + $dir;
	}
	define('MB', 1048576);
	$submition_text = '';
	$sub_label = '';
	$files_url='';
	$files_type='';
	$checkBoxes='';
	$email='';
	$submition_errors='';
	////////////////////////////////get ip ////////////////////////////////////////
	$ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
    $ipaddress = 'UNKNOWN';
	////////////////////////////////get ip ////////////////////////////////////////
	global $wpdb; 
	$tablenameSub=$wpdb->prefix . "huge_it_contact_submission";
   	$query2=$wpdb->prepare("SELECT 'submission_ip','customer_spam' FROM %s order by id ASC",$tablenameSub);
   	$query2=str_replace("'","",$query2);
   	$submissionSub=$wpdb->get_results($query2);
   	$tablename = $wpdb->prefix . "huge_it_contact_general_options";
   	$query2=$wpdb->prepare("SELECT * FROM %s order by id ASC",$tablename);
   	$query2=str_replace("'","",$query2);
   	$huge_it_gen_opt=$wpdb->get_results($query2);
   	$spamError=$huge_it_gen_opt[14]->value;
   	$postDataStr=$_POST['postData'];
	$all=$_POST['postData'];
	parse_str("$all",$myArray);
	$frontendformid=$_POST['formId'];
	$browser=$_POST['browser'];
	$_POSTED=$myArray;	  
    $query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_it_contact_contacts_fields where hugeit_contact_id = %d order by ordering ASC",$frontendformid);
    $rowim=$wpdb->get_results($query);     
   	$email='';
   	$emailArray='';
   	$buttonsField='';
   	$fileSize='';
   	$afterSubmit='';
   	$afterSubmitUrl='';
   	foreach ($submissionSub as $submission) {
   		if($submission->submission_ip==$ipaddress&&$submission->customer_spam==1){
   			foreach ($rowim as $key=>$rowimages){
				$inputtype = $rowimages->conttype;
				if($inputtype == 'buttons'){
					$buttonsField='huge-contact-field-'.$rowimages->id;
				}
			}			
   			echo json_encode(array("markedAsSpam"=>$spamError,"spamButton"=>$buttonsField));
   			exit;
   		}
   	}
	
	if(isset($_POSTED['submitok'])){
		if(isset($_POST['nonce'])){
			$nonce = $_POST['nonce'];
		}else{
			$nonce='';
		}		
		//if ( !wp_verify_nonce( $nonce, 'front_nonce' ) )die('ji');
		if($_POSTED['submitok'] == 'ok'){
			//print_r($_POSTED);
			$thisdate = date("d.m.Y H:i");
			foreach ($rowim as $key=>$rowimages){
				$inputtype = $rowimages->conttype;
				$rowimages->hc_field_label=addslashes($rowimages->hc_field_label); 
				if($inputtype == 'text' or $inputtype == 'textarea' or $inputtype == 'selectbox' or $inputtype == 'checkbox' or $inputtype == 'radio_box' or $inputtype == 'file_box' or $inputtype == 'e_mail' or $inputtype == 'buttons' or $inputtype == 'captcha' or $inputtype =='nameSurname' or $inputtype =='phone' or $inputtype =='license'){
					if($inputtype == 'captcha'){
						$url='https://www.google.com/recaptcha/api/siteverify';
						$privatekey=$huge_it_gen_opt[10]->value;
						$response=file_get_contents($url."?secret=".$privatekey."&response=".$_POSTED['g-recaptcha-response']."&remoteip=".$ipaddress);
						$dataOfCaptcha=json_decode($response);
						if(!isset($dataOfCaptcha->success)||$dataOfCaptcha->success!=true){
							$submition_errors.='huge-contact-field-'.$rowimages->id.':'.$huge_it_gen_opt[37]->value.'*()*';
						}						
					}
					if($inputtype == 'buttons'){
						$buttonsField='huge-contact-field-'.$rowimages->id;
						$afterSubmit=$rowimages->hc_other_field;
						$afterSubmitUrl=$rowimages->field_type;
					}
					if($inputtype == 'text' or $inputtype == 'textarea'){
						if(!isset($_POSTED['huge_it_'.$frontendformid.'_'.$rowimages->id]))$_POSTED['huge_it_'.$frontendformid.'_'.$rowimages->id]='';						
						$contactField=$_POSTED['huge_it_'.$frontendformid.'_'.$rowimages->id];
						if($rowimages->hc_required=='on'&&$contactField==''){$submition_errors.='huge-contact-field-'.$rowimages->id.':'.$huge_it_gen_opt[36]->value.'*()*';}else{

						}
					}
					if($inputtype == 'selectbox'){
						if(!isset($_POSTED['huge_it_'.$frontendformid.'_'.$rowimages->id]))$_POSTED['huge_it_'.$frontendformid.'_'.$rowimages->id]='';						
						$contactField=$_POSTED['huge_it_'.$frontendformid.'_'.$rowimages->id];
						if($rowimages->hc_required=='on'&&$contactField==''){$submition_errors.='huge-contact-field-'.$rowimages->id.':'.$huge_it_gen_opt[36]->value.'*()*';}else{

						}
					}
					if($inputtype == 'e_mail'){
						if(!isset($_POSTED['huge_it_'.$frontendformid.'_'.$rowimages->id]))$_POSTED['huge_it_'.$frontendformid.'_'.$rowimages->id]='';						
						$email=	$_POSTED['huge_it_'.$frontendformid.'_'.$rowimages->id];
						if(($rowimages->hc_required=='on'&&$email!='')||$rowimages->hc_required!='on'){						
							if(is_email($email)||$email==''){
								$emailArray.=$email.'*()*';								
							}else{
								$submition_errors.='huge-contact-field-'.$rowimages->id.':'.$huge_it_gen_opt[20]->value.'*()*';
							}
						}else{
							$submition_errors.='huge-contact-field-'.$rowimages->id.':'.$huge_it_gen_opt[36]->value.'*()*';
						}
					}
					$checkBoxes='';
					if($inputtype == 'checkbox'){
						if(!isset($_POSTED['check_'.$frontendformid.'_'.$rowimages->id]))$_POSTED['check_'.$frontendformid.'_'.$rowimages->id]='';						
						$checkbox=$_POSTED['check_'.$frontendformid.'_'.$rowimages->id];
						if(($rowimages->hc_required=='on'&&$checkbox!='')||$rowimages->hc_required!='on'){
							$options=explode(';;',$rowimages->name);
								foreach($options as $keys=>$option){
									if(isset($_POSTED['check_'.$frontendformid.'_'.$rowimages->id]['huge_it_'.$frontendformid.'_'.$rowimages->id.'_'.$keys])){
										$checkBoxes .= $_POSTED['check_'.$frontendformid.'_'.$rowimages->id]['huge_it_'.$frontendformid.'_'.$rowimages->id.'_'.$keys].',';
									}
								}
							$sub_label.= $rowimages->hc_field_label.'*()*';
							$checkBoxes=substr_replace($checkBoxes, "", -1);
							$submition_text.= $checkBoxes.'*()*';
						}else{
							$submition_errors.='huge-contact-field-'.$rowimages->id.':'.$huge_it_gen_opt[36]->value.'*()*';
						}
					}
					$fullname='';
					if($inputtype == 'nameSurname'){
						if(!isset($_POSTED['fullName_'.$frontendformid.'_'.$rowimages->id]))$_POSTED['fullName_'.$frontendformid.'_'.$rowimages->id]='';						
						$fullname=$_POSTED['fullName_'.$frontendformid.'_'.$rowimages->id];
						//print_r($fullname);
						if(($rowimages->hc_required=='on'&&($fullname['huge_it_1']!=''&&$fullname['huge_it_2']!=''))||$rowimages->hc_required!='on'){
							//echo "string";
							$sub_label.=$rowimages->hc_field_label.'*()*';	
							if($fullname['huge_it_1']!=''&&$fullname['huge_it_2']!=''){
								$submition_text.=$fullname['huge_it_1'].' '.$fullname['huge_it_2'].'*()*';
							}							
						}else{
							$submition_errors.='huge-contact-field-'.$rowimages->id.':'.$huge_it_gen_opt[36]->value.'*()*';
						}
					}
					if($inputtype == 'phone'){
						if(!isset($_POSTED['huge_it_'.$frontendformid.'_'.$rowimages->id]))$_POSTED['huge_it_'.$frontendformid.'_'.$rowimages->id]='';						
						$phoneNum=$_POSTED['huge_it_'.$frontendformid.'_'.$rowimages->id];
						if(($rowimages->hc_required=='on'&&$phoneNum!='')||$rowimages->hc_required!='on'){
							//$submition_text.=$phoneNum.'*()*';
						}else{
							$submition_errors.='huge-contact-field-'.$rowimages->id.':'.$huge_it_gen_opt[36]->value.'*()*';
						}
					}	
					if($inputtype == 'license'){
						if(!isset($_POSTED['huge_it_'.$frontendformid.'_'.$rowimages->id])){
							$_POSTED['huge_it_'.$frontendformid.'_'.$rowimages->id]='';	
							$submition_errors.='huge-contact-field-'.$rowimages->id.': Please tick on checkbox*()*';	
						}	
					}	
					if($inputtype == 'file_box'){	
						if(($rowimages->hc_required=='on'&&isset($_FILES['userfile_'.$rowimages->id]))||$rowimages->hc_required!='on'){
							require_once("mime_types.php");
							$user_mime_types=$rowimages->hc_other_field;
							$user_mime_types_array=array_filter(explode(',',$user_mime_types),'strlen');
							foreach ($user_mime_types_array as $key => $value) {
								 $user_mime_types_array[$key] = trim($value);
							}
							$result_array=array();
							foreach ($user_mime_types_array as $key => $uservalue) {
								foreach ($huge_it_mime_types as $huge_it_key => $value) {
									if(preg_match("/".$uservalue."/",$huge_it_key)){
										$result_array[$huge_it_key]=$value;
									}
								}
							}
							if(isset($_FILES['userfile_'.$rowimages->id])&&!empty($_FILES['userfile_'.$rowimages->id]['tmp_name']) ){
								//Checking Type							
								if(!in_array($_FILES['userfile_'.$rowimages->id]['type'], $result_array)){
									$submition_errors.='huge-contact-field-'.$rowimages->id.':'.$huge_it_gen_opt[27]->value.'*()*';
								}
								//Checking FileSize
								$fileSize=$rowimages->name;
								if($_FILES['userfile_'.$rowimages->id]['size']> $fileSize*MB ){
									$submition_errors.='huge-contact-field-'.$rowimages->id.':'.$huge_it_gen_opt[28]->value.'*()*';
								}									 
							}
						}else{
							$submition_errors.='huge-contact-field-'.$rowimages->id.':'.$huge_it_gen_opt[36]->value.'*()*';
						}						  			
						
					}
					if(!isset($_POSTED['huge_it_'.$frontendformid.'_'.$rowimages->id]))$_POSTED['huge_it_'.$frontendformid.'_'.$rowimages->id]='';	
					if($inputtype != 'checkbox' && $inputtype != 'nameSurname'){
						$submition_text.= $_POSTED['huge_it_'.$frontendformid.'_'.$rowimages->id].'*()*';
						$sub_label.= $rowimages->hc_field_label.'*()*';	
					}
				}
			}
			///////////////
			if($submition_errors==''){
				if(isset($_FILES)){
					function retFile($my_var){
						return $my_var;
					}
					foreach ($_FILES as $keyofFile=>$fileSingle) {
						include_once ABSPATH . 'wp-admin/includes/media.php';
						include_once ABSPATH . 'wp-admin/includes/file.php';
						include_once ABSPATH . 'wp-admin/includes/image.php';
						require_once("mime_types.php");
						$user_mime_types=$rowimages->hc_other_field;
						$user_mime_types_array=array_filter(explode(',',$user_mime_types),'strlen');
						foreach ($user_mime_types_array as $key => $value) {
							 $user_mime_types_array[$key] = trim($value);
						}
						$result_array=array();
						foreach ($user_mime_types_array as $key => $uservalue) {
							foreach ($huge_it_mime_types as $huge_it_key => $value) {
								if(preg_match("/".$uservalue."/",$huge_it_key)){
									$result_array[$huge_it_key]=$value;
								}
							}
						}
						$overrides = array('test_form' => false,'mimes' => $result_array);
						$int = filter_var($keyofFile, FILTER_SANITIZE_NUMBER_INT);

						$fieldPath=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."huge_it_contact_contacts_fields where id =".$int."");
						$fieldPath=$fieldPath[0]->field_type;
						global $filePath;
						if($fieldPath==''){
							$filePath='';
						}else{
							$filePath='/'.$fieldPath;
						}				
						add_filter( 'upload_dir', 'huge_upl');
						if(!function_exists('huge_upl')){
							function huge_upl($dir){
								global $filePath;
								return array(
									'path'   => $dir['basedir'] .retFile($filePath),
									'url'    => $dir['baseurl'] .retFile($filePath),
									'subdir' => retFile($filePath),
								) + $dir;
							} 
						}						
						$file = wp_handle_upload( $_FILES[$keyofFile], $overrides );
						if(!isset($file['error'])){
							$files_url.=$file['url'].'*()*';
							$files_type.=$file['type'].'*()*';
							remove_filter( 'upload_dir', 'huge_upl_remove');
							if(!function_exists('huge_upl_remove')){
								function huge_upl_remove($dir){
									global $filePath;
									return array(
										'path'   => $dir['basedir'] .retFile($filePath),
										'url'    => $dir['baseurl'] .retFile($filePath),
										'subdir' => retFile($filePath),
									) + $dir;
								} 
							}
						}
					}
				}
				$emailArray=array_filter(explode('*()*',$emailArray),'strlen');
				$email_form_id=$frontendformid;
				foreach ($emailArray as  $emailSingle) {
					$subscribers=$wpdb->get_results("SELECT `subscriber_email` FROM ".$wpdb->prefix ."huge_it_contact_subscribers WHERE subscriber_form_id=".$email_form_id."",ARRAY_A);
					$insert=1;
					foreach ($subscribers as $subscriber) {
						if($subscriber['subscriber_email']==$emailSingle){
							$insert=0;
						}
					}
					if($insert==1){
						$table_name = $wpdb->prefix . "huge_it_contact_subscribers";
						$email_insert = " INSERT INTO `" . $table_name . "` (`subscriber_form_id`,`subscriber_email`) VALUES (".$email_form_id.",'".$emailSingle."')";
						$wpdb->query($email_insert);
					}
					if($huge_it_gen_opt[6]->value=='on'){
						$subject='';
						$sendmessage='';									
						if(isset($_POSTED['hc_email_r'])){
							$subject=$huge_it_gen_opt[7]->value;
							$sendmessage=$huge_it_gen_opt[8]->value;
							$namee=$huge_it_gen_opt[35]->value;
							add_filter( 'wp_mail_content_type', 'set_html_content_type2' );
							$headers = array('From: '.$huge_it_gen_opt[35]->value.' <'.$huge_it_gen_opt[34]->value.'>');
							wp_mail($emailSingle, $subject, $sendmessage,$headers);
							remove_filter( 'wp_mail_content_type', 'set_html_content_type2' );
						}
					}
				}
			////
				if($huge_it_gen_opt[2]->value=='on'){
					$subject='';
					$sendmessage='';
					function set_html_content_type() {
						return 'text/html';
					}
					
					$subject=$huge_it_gen_opt[4]->value;
					$sendmessage=$huge_it_gen_opt[5]->value;
					$email=$huge_it_gen_opt[3]->value;
					add_filter( 'wp_mail_content_type', 'set_html_content_type' );
					$messagelabbelsexp = array_filter(explode("*()*", $sub_label),'strlen');
					$messagesubmisexp = explode("*()*", $submition_text);
					$adminSub='<table class="message-block">';
					$separator=':';
					foreach($messagelabbelsexp as $key=>$messagelabbelsexpls){	
						$messagelabbelsexpls=stripslashes($messagelabbelsexpls);
						if($messagesubmisexp[$key]!=''){
							$adminSub.='<tr>
											<td><strong>'.$messagelabbelsexpls.'</strong>'.$separator.' '.$messagesubmisexp[$key].'</td>
										</tr>';					
						}
					}
					$adminSub.='</table>';
					$attachments = array();
					$fileUrls = array_filter(explode("*()*", $files_url),'strlen');
					foreach ($fileUrls as $key => $value) {
						$link_pattern='/^(.*)\/uploads\//';
						$file_path=preg_replace($link_pattern,'',$value);
						array_push($attachments, WP_CONTENT_DIR . '/uploads/'.$file_path);
					}
					$sendmessage=preg_replace('/{formContent}/', $adminSub, $sendmessage);
					$headers = array('From: '.$huge_it_gen_opt[35]->value.' <'.$huge_it_gen_opt[34]->value.'>');
					wp_mail($email, $subject, $sendmessage,$headers,$attachments);
					remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
									
				}
				if($huge_it_gen_opt[1]->value=='on'){
					$table_name = $wpdb->prefix . "huge_it_contact_submission";
					$form_submit = " INSERT INTO `" . $table_name . "` ( `contact_id`, `sub_labels`, `submission`, `submission_date`, `submission_ip`, `customer_country`, `customer_spam`, `customer_read_or_not`, `files_url`, `files_type`) VALUES ( ". $frontendformid .", '".$sub_label."', '".$submition_text."', '".$thisdate."', '".$ipaddress.'*()*'.$browser."', '(Only In Pro)', '0', '0' ,'".$files_url."','".$files_type."')";
					$wpdb->query($form_submit);
				}	
				//print_r($sub_label);		
				$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				$success_message=$huge_it_gen_opt[11]->value;
				echo json_encode(array("success"=>$success_message,"buttons"=>$buttonsField,"afterSubmit"=>$afterSubmit,"afterSubmitUrl"=>$afterSubmitUrl));
			}else{
				$submition_errors_array=array();
				$submition_errors=array_filter(explode('*()*',$submition_errors),'strlen');
				foreach ($submition_errors as $key => $value) {
					$value=array_filter(explode(':',$value),'strlen');
					$submition_errors_array[$value[0]]=$value[1];
				}
				echo json_encode(array("errors"=>$submition_errors_array));
			}
		}
	}
	
	die();	
}