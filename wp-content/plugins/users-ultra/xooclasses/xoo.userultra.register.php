<?php
class XooUserRegister {

	function __construct() 
	{
		add_action( 'init', array($this, 'uultra_handle_hooks_actions') );			
		add_action( 'init', array($this, 'uultra_handle_post') );		

	}
	
	//this function handles the registration hook - 10-24-2014	
	function uultra_handle_hooks_actions ()	
	{
		if (function_exists('uultra_registration_hook')) 
		{
		
			add_action( 'user_register', 'uultra_registration_hook' );	
		
		}
		
		if (function_exists('uultra_after_login_hook')) 
		{		
			add_action( 'wp_login', 'uultra_after_login_hook' , 100,2);		
		}	
		
	}
	
	function uultra_handle_post () 
	{
		
		
		/*Form is fired*/
	    
		if (isset($_POST['xoouserultra-register-form'])) {
			
			/* Prepare array of fields */
			$this->uultra_prepare_request( $_POST );
       			
			/* Validate, get errors, etc before we create account */
			$this->uultra_handle_errors();
			
			/* Create account */
			$this->uultra_create_account();
				
		}
		
	}
	
	/*Prepare user meta*/
	function uultra_prepare_request ($array ) 
	{
		foreach($array as $k => $v) 
		{
			if ($k == 'usersultra-register' || $k == 'user_pass_confirm' || $k == 'user_pass' || $k == 'xoouserultra-register-form') continue;
			
			
			$this->usermeta[$k] = $v;
		}
		return $this->usermeta;
	}

//added
function new_user_regsub($user_id,$plan_id,$coupon_code,$startdate,$enddate)
{
		global $wpdb;
		$txt="---------------- /n ";

		$old_planid=0;
		if($old_planid == 0)
		{
		$old_planname="---";
		}
		else
		{
		$old_planname=$wpdb->get_var("SELECT name FROM wp_pmpro_membership_levels where id=".$old_planid." ");
		}

		
		$txt.="oldplanname:".$old_planname;
		$planid=$plan_id;
		$planname=$wpdb->get_var("SELECT name FROM wp_pmpro_membership_levels where id=".$planid." ");

		$txt.="planid:".$planid;
		$txt.="planname:".$planname;
		if($planid == 0)
		{
		$cancelled=true;
		$txt.="cancelled:true";
		}

		$startdate=$startdate;

		$trail_period_no=$wpdb->get_var("SELECT expiration_number FROM wp_pmpro_membership_levels where id=".$planid." ");
		$trail_period=$wpdb->get_var("SELECT expiration_period FROM wp_pmpro_membership_levels where id=".$planid." ");

		$cycle_period_no=$wpdb->get_var("SELECT cycle_number FROM wp_pmpro_membership_levels where id=".$planid." ");
		$cycle_period=$wpdb->get_var("SELECT cycle_period FROM wp_pmpro_membership_levels where id=".$planid." ");
	

		$txt.="1.startdate:".$startdate;

		$txt.="trail_period_no:".$trail_period_no;
		$txt.="trail_period:".$trail_period;

		$delay= get_option("pmpro_subscription_delay_" . $planid, "");
		$txt.="delay:".$delay;
		if($delay=="")
		$free="free";
		$abc="new user-".$free;



		//if delay is null pass 0
		 if($delay=="")
		$delay=0; 

		$txt.="delay:".$delay;
		$txt.="abc:".$abc;

		update_option("pmpro_sub_support_prev_delay_".$user_id,$delay); //added
		$plan_startdate=$startdate;

		$txt.="plan_startdate:".$plan_startdate;

		if($cycle_period_no==0 or $cycle_period=="") //$planid == 4 //free trial plan
		{
			/*if( ($trail_period_no != "") && ($trail_period != ""))
			{
				$next_paydate=new DateTime($plan_startdate);
				$next_paydate=$next_paydate->add(new DateInterval('P'.$trail_period_no.$trail_period[0]));
				$next_paydate=$next_paydate->format("Y-m-d");
			}*/
			
			$agent="free-trial/$coupon_code";
		}

		else
		{
			/*if( ($cycle_period_no != "") && ($cycle_period != ""))
			{
				$next_paydate=new DateTime($plan_startdate);
				$next_paydate=$next_paydate->add(new DateInterval('P'.$cycle_period_no.$cycle_period[0]));
				$next_paydate=$next_paydate->format("Y-m-d");
			}*/
			$agent=$coupon_code;

		}
				$next_paydate=$enddate;
				$txt.="next_paydate:".$next_paydate;	
				$txt.="agent:".$agent;	
				//delay update after the new/change plan
						$today=new DateTime("now");
						$today=$today->format("Y-m-d");
						$today=new DateTime($today);

						/*if( (new DateTime($plan_startdate)) > $today )
						{
							$delayeddate=$plan_startdate;
						}
						else
						{
							$delayeddate=$next_paydate;
						}*/
				
						$delayeddate=$next_paydate;
						$txt.="delayeddate:".$delayeddate;	

						$delayeddate=new DateTime($delayeddate);
						$temp = date_diff($today,$delayeddate);
						$delayUpd=$temp->format('%R%a');

						if($delayUpd < 0)
		 					{$delayUpd=0;}
						else
						{$delayUpd=$temp->format('%a');}


						$txt.="delayUpd:".$delayUpd;			
		
		//insert or update only if the plan is not cancelled ie. plan id exists >0
		if($planid > 0)
		{

		$wpdb->replace("pmpro_dates_chk1", array(
		   "user_id" => $user_id,
		"old_plan_id" => $old_planid,
		"old_plan_name" => $old_planname,
		"plan_id" => $planid,
		"plan_name" => $planname,
		    "startdate" => $startdate, 
		   "plan_startdate" => $plan_startdate,
			"next_paydate"=>$next_paydate,
			"delay"=>$delayUpd,
			"agent"=>$agent
		  )); 		

		//copy delayUpd to wp_options
		update_option("pmpro_sub_support_delay_".$user_id, $delayUpd);
		//update_option("pmpro_sub_support_delay_" . $current_user->ID, $subscription_delay);

		$fromdb=get_option("pmpro_sub_support_delay_".$user_id);

		$txt.="delay_from_db:".$fromdb;
		}

		$txt.="startdate:".$startdate."-or-".$level['startdate']."-usercount-".$userCount."-delay-".$delay."-nextpay-".$next_paydate;

		mail("siddish.gollapelli@ideabytes.com","Subscription After Registration",print_r($txt,true));
		$file = fopen("test.txt","w");
		fwrite($file,$txt);
		fclose($file);
}
	//end new_user_regsub				


//added for curl
function sendPost($data,$url) {

    $ch = curl_init();
    // you should put here url of your getinfo.php script
    curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $result = curl_exec ($ch); 
	
    return $result; 
}

//ended

	
	/*Handle/return any errors*/
	function uultra_handle_errors() 
	{
	    global $xoouserultra;
		
		$file=fopen("cpn_chk.txt","a");

			$info="cpn_usage:";
			$info.=$cpn_used=$_POST['coupon'];
			$info="cpn:";
			$info.=$coupon=$_POST['cc'];
			fwrite($file,$info);

		
		//check if retype-password
		$password_retype = $xoouserultra->get_option("set_password_retype");
		
		if(get_option('users_can_register') == '1')
		{
		    foreach($this->usermeta as $key => $value) {
		    
		        /* Validate username */
		        if ($key == 'user_login') {

			
			$info="userlogin";
			$info.="key:".$key;
			fwrite($file,$info);

		            if (esc_attr($value) == '') {
		                $this->errors[] = __('<strong>ERROR:</strong> Please enter a username.','xoousers'); return;
		            }
				elseif(!preg_match("/^((?!_)[A-Za-z0-9])+[A-Za-z0-9_]*$/", $value))
				{
				
					
				$this->errors[] = __('<strong>ERROR:</strong> UserName should not contain special char and shouldn\'t start with underscore','xoousers'); return;

				}
				 elseif (username_exists($value)) {
		                $this->errors[] = __('<strong>ERROR:</strong> This username is already registered. Please choose another one.','xoousers');

				$info.="value:".$value;
				$info.="already exist error";
				fwrite($file,$info);
				return;//added for only one error
		            }

				$fileR=fopen("registrations.txt","a");
				$reg="\n";
				$reg.="Registrations: ".date("Y-m-d H:i:s");
				$reg.="   UserName: ".$value;
				
				//fwrite($fileR,$reg);
		        }
		    
		        /* Validate email */
		        if ($key == 'user_email') 
				{
					$info="useremail";
					$info.="key:".$key;
					fwrite($file,$info);

					  		  /* if (esc_attr($value) == '') 
							{
						$this->errors[] = __('<strong>ERROR:</strong> Please type your e-mail address.','xoousers');
					    } elseif (!is_email($value)) 
							{
						$this->errors[] = __('<strong>ERROR:</strong> The email address isn\'t correct.','xoousers');
						
							} elseif ($value!=$_POST['user_email_2']) 
							{
								if($password_retype!='no')						
								{					
					       		 $this->errors[] = __('<strong>ERROR:</strong> The emails are different.','xoousers');						
								}
							
					    } else*/
					if($value=="")
					{
					$this->errors[] = __('<strong>ERROR:</strong> Please enter an email-id.','xoousers'); 
						$info.="key:".$key;
						$info.="value".$value;
						fwrite($file,$info);
						return;
					}
					elseif(!preg_match("/^[a-zA-Z0-9.!#$%&Â’*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+[\.].[a-zA-Z0-9]+(?:\.[a-zA-Z0-9-]+)*$/", $value)) 
					{
 					$this->errors[] = __('<strong>ERROR:</strong> Enter a valid Email-id.','xoousers'); 
					$info.="key:".$key;
				$info.="value".$value;
				fwrite($file,$info);					
					return;
					}

					
					elseif (email_exists($value)) 
					{
		                $this->errors[] = __('<strong>ERROR:</strong> This email is already registered, please choose another one.','xoousers'); return;

				$info.="value:".$value;
				$info.="already email exist error";
				fwrite($file,$info);
		            		}

				
				$reg.="Email: ".$value;
				//fwrite($fileR,$reg);
		        	
				}

			 if ($key == 'phone') 
			{

					if($value=="")
					{
					$this->errors[] = __('<strong>ERROR:</strong> Please enter a contact no.','xoousers'); 
					$info.="key:".$key;
				$info.="value".$value;
				fwrite($file,$info);					
					return;

					}
					elseif(!preg_match("/^\+(?:[0-9] ?){6,14}[0-9]$/", $value))
					{
					
					$this->errors[] = __('<strong>ERROR:</strong> Please enter a valid contact no.','xoousers'); 
					$info.="key:".$key;
				$info.="value".$value;
				fwrite($file,$info);					
					return;
					}
					
				$reg.="Phone: ".$value;
				$reg.="\n";
				fwrite($fileR,$reg);
					
		
			}

			
				
					


			
		    
		    }
			
			


			//check if auto-password
			$auto_password = $xoouserultra->get_option("set_password");	
			
			//check if retype-password
			$password_retype = $xoouserultra->get_option("set_password_retype");				
			
			if($auto_password =='' || $auto_password==1)
			{
			
				 /* Validate passowrd */
				 if ($_POST["user_pass"]=="") 
				 {
					$this->errors[] = __('<strong>ERROR:</strong> Please type your password.','xoousers'); 
					$info.="value:".$value;
				$info.="empty password error";
				fwrite($file,$info);  
					return;        
					
				 }
				 
				 //password strenght 				 
				 if(!$this->uultra_check_pass_strenght($_POST["user_pass"]))
				{
					$info.="value:".$value;
				$info.=" password min length 8 error";
				fwrite($file,$info);  
					return;
				}
				
					
				
				 if ($_POST["user_pass"]!= $_POST["user_pass_confirm"]) 
				 {
						 $this->errors[] = __('<strong>ERROR:</strong> The passwords must be identical','xoousers');           
				$info.="value:".$value;
				$info.=" passwords did not match error";
				fwrite($file,$info);
				return; 
						
				}
				 
				
				 
				
			 
			 }
		    
		   if(!is_in_post('no_captcha','yes'))
		    {
		        if(!$xoouserultra->captchamodule->validate_captcha(post_value('captcha_plugin')))
		        {
		            $this->errors[] = __('<strong>ERROR:</strong> Please complete Captcha Test first.','xoousers');
		        }
		    } 
		}
		else
		{
		    $this->errors[] = __('<strong>ERROR:</strong> Registration is disabled for this site.','xoousers');
		}
		


		if($coupon!="")
			{

				global $wpdb;
			$cpn_details=$wpdb->get_results("SELECT * from buy_a_friend where coupon_code='".$coupon."' and status='Paid'");		

						$promo_cpn_details=$wpdb->get_results("SELECT * from promo_codes where coupon_code='".$coupon."' and usage_count<max_count");			

						if(count($cpn_details)<1 && count($promo_cpn_details)<1)
						{
							$this->errors[] = __('<strong>ERROR:</strong> Please enter a valid promo/coupon code.','xoousers'); 					
						}
						else
						{
							//valid
						}

			}





		//cpn $coupon
		if($cpn_used=="true")
			{
			if($coupon != "")
					{
					/*$this->errors[] = __('<strong>ERROR:</strong> Please enter your coupon code.','xoousers'); 
					$info.="key:".$key;
					$info.="value".$value;
					fwrite($file,$info);					
					return;

					}
				else{*/
					$file=fopen("cpn_chk.txt","a");
					$info1="coupon".$coupon;
					$url=site_url()."/qp/uservalidation_check.php";					
					$data = $this->sendPost( array('action'=>'couponCheck','cc'=>$coupon),$url);
					
					//$cpn = json_decode($data);
						$cpn=$data[0];
					$info1.="cpn".$cpn;

					
					fwrite($file,$info1);	
						if($cpn=="1" or $cpn=="2")
							{
							if($cpn=="1")
							$this->errors[] = __('<strong>ERROR:</strong> Please enter a valid Couponcode/Promocode.','xoousers'); 					
							if($cpn=="2")
							$this->errors[] = __('<strong>ERROR:</strong> Couponcode/Promocode  expired','xoousers'); 					
							
							$info1.=$cpn;
							fwrite($file,$info1);					
							return;
							}
						fwrite($file,"end");			
					}
				}
				//end cpn



			


		
	}
	
	function uultra_check_pass_strenght($password)
	{
		global $xoouserultra;
		$res= true;
		
		$PASSWORD_LENGHT = $xoouserultra->get_option('uultra_password_lenght');
		
		if($PASSWORD_LENGHT==''){$PASSWORD_LENGHT=8;}
		
		if(strlen($password)<$PASSWORD_LENGHT)
		{
			 $this->errors[] = __('<strong>ERROR:</strong> The Password must be at least '.$PASSWORD_LENGHT.' characters long','xoousers');      
		return false;
		}
		
		////must contain at least one number and one letter		
		$active = $xoouserultra->get_option('uultra_password_1_letter_1_number');		
		if($active==1)
		{
			$ret_validate_password = $this->validate_password_numbers_letters($password);
			if(!$ret_validate_password)
			{
				$this->errors[] = __('<strong>ERROR:</strong> The Password must contain at least one number and one letter','xoousers'); 
			}
			    
		}
		
		////must contain at least one upper case character	
		$active = $xoouserultra->get_option('uultra_password_one_uppercase');		
		if($active==1)
		{
			$ret_validate_password = $this->validate_password_one_uppercase($password);
			if(!$ret_validate_password)
			{
				$this->errors[] = __('<strong>ERROR:</strong> The Password must contain at least one upper case character','xoousers'); 
			}
			    
		}
		
		////must contain at least one lower case character
		$active = $xoouserultra->get_option('uultra_password_one_lowercase');		
		if($active==1)
		{
			$ret_validate_password = $this->validate_password_one_lowerrcase($password);
			if(!$ret_validate_password)
			{
				$this->errors[] = __('<strong>ERROR:</strong> The Password must contain at least one lower case character','xoousers'); 
			}
			    
		}
		
			
		
		return $res;
	
	
	}
	
	//validate password one letter and one number	
	function validate_password_numbers_letters ($myString)
	{
		$ret = false;
		
		
		if (preg_match('/[A-Za-z]/', $myString) && preg_match('/[0-9]/', $myString))
		{
			$ret = true;
		}
					
		return $ret;
	
	
	}
	
	//at least one upper case character 	
	function validate_password_one_uppercase ($myString)
	{	
		
		if( preg_match( '~[A-Z]~', $myString) ){
   			 $ret = true;
		} else {
			
			$ret = false;
		  
		}
					
		return $ret;
	
	}
	
	//at least one lower case character 	
	function validate_password_one_lowerrcase ($myString)
	{	
		
		if( preg_match( '~[a-z]~', $myString) ){
   			 $ret = true;
		} else {
			
			$ret = false;
		  
		}
					
		return $ret;	
	
	}
	
	// File upload handler:
	function upload_front_avatar($o_id)
	{
		global $xoouserultra;
		global $wpdb;
		
		require_once(ABSPATH . 'wp-includes/link-template.php');
		$site_url = '';
		
				
		/// Upload file using Wordpress functions:
		$file = $_FILES['user_pic'];
		
		
		$original_max_width = $xoouserultra->get_option('media_avatar_width'); 
        $original_max_height =$xoouserultra->get_option('media_avatar_height'); 
		
		if($original_max_width=="" || $original_max_height==80)
		{			
			$original_max_width = 100;			
			$original_max_height = 100;
			
		}
		
				
		$info = pathinfo($file['name']);
		$real_name = $file['name'];
        $ext = $info['extension'];
		$ext=strtolower($ext);
		
		$rand = $this->genRandomString();
		
		$rand_name = "avatar_".$rand."_".session_id()."_".time(); 	
			

		$upload_dir = wp_upload_dir(); 
		$path_pics =   $upload_dir['basedir'].'/'.$xoouserultra->get_option('media_uploading_folder');
		
		
			
			
		if($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif') 
		{
			if($o_id != '')
			{
				
				   if(!is_dir($path_pics."/".$o_id."")) {
						$this->CreateDir($path_pics."/".$o_id);								   
					}					
										
					$pathBig = $path_pics."/".$o_id."/".$rand_name.".".$ext;
					
					if (copy($file['tmp_name'], $pathBig)) 
					{
												
						$upload_dir = wp_upload_dir(); 
						$upload_folder =   $upload_dir['baseurl'].'/'.$xoouserultra->get_option('media_uploading_folder');
						
										
						$path = $site_url.$upload_folder."/".$o_id."/";
						
						//check max width
												
						list( $source_width, $source_height, $source_type ) = getimagesize($pathBig);
						
						if($source_width > $original_max_width) 
						{
							//resize
							if ($this->createthumb($pathBig, $pathBig, $original_max_width, $original_max_height,$ext)) 
							{
								$old = umask(0);
								chmod($pathBig, 0755);
								umask($old);
														
							}
						
						
						}
						
						
						
						$new_avatar = $rand_name.".".$ext;						
						$new_avatar_url = $path.$rand_name.".".$ext;					
						
						//check if there is another avatar						
						$user_pic = get_user_meta($o_id, 'user_pic', true);						
						
						if ( $user_pic!="" )
			            {
							//there is a pending avatar - delete avatar										
									
													
							$path_avatar = $path_pics."/".$o_id."/".$user_pic;					
														
							//delete								
							if(file_exists($path_avatar))
							{
								unlink($path_avatar);
							}
							
							//update meta
							update_user_meta($o_id, 'user_pic', $new_avatar);
							
						}else{
							
							//update meta
							update_user_meta($o_id, 'user_pic', $new_avatar);
												
						
						}
						
						
						
					}
									
					
			     }  		
			
			  
			
        } // image type
		
		// Create response array:
		$uploadResponse = array('image' => $new_avatar_url);
		
	}
	
	 public function createthumb($imagen,$newImage,$toWidth, $toHeight,$extorig)
	{             				
				
                 $ext=strtolower($extorig);
                 switch($ext)
                  {
                   case 'png' : $img = imagecreatefrompng($imagen);
                   break;
                   case 'jpg' : $img = imagecreatefromjpeg($imagen);
                   break;
                   case 'jpeg' : $img = imagecreatefromjpeg($imagen);
                   break;
                   case 'gif' : $img = imagecreatefromgif($imagen);
                   break;
                  }

               
                $width = imagesx($img);
                $height = imagesy($img);  
				

				
				$xscale=$width/$toWidth;
				$yscale=$height/$toHeight;
				
				// Recalculate new size with default ratio
				if ($yscale>$xscale){
					$new_w = round($width * (1/$yscale));
					$new_h = round($height * (1/$yscale));
				}
				else {
					$new_w = round($width * (1/$xscale));
					$new_h = round($height * (1/$xscale));
				}
				
				
				
				if($width < $toWidth)  {
					
					$new_w = $width;	
				
				//}else {					
					//$new_w = $current_w;			
				
				}
				
				if($height < $toHeight)  {
					
					$new_h = $height;	
				
				//}else {					
					//$new_h = $current_h;			
				
				}
			
				
				
				
                $dst_img = imagecreatetruecolor($new_w,$new_h);
				
				/* fix PNG transparency issues */                       
				imagefill($dst_img, 0, 0, IMG_COLOR_TRANSPARENT);         
				imagesavealpha($dst_img, true);      
				imagealphablending($dst_img, true); 				
                imagecopyresampled($dst_img,$img,0,0,0,0,$new_w,$new_h,imagesx($img),imagesy($img));
               
                
				
				 switch($ext)
                  {
                   case 'png' : $img = imagepng($dst_img,"$newImage",9);
                   break;
                   case 'jpg' : $img = imagejpeg($dst_img,"$newImage",100);
                   break;
                   case 'jpeg' : $img = imagejpeg($dst_img,"$newImage",100);
                   break;
                   case 'gif' : $img = imagegif($dst_img,"$newImage");
                   break;
                  }
				  
				   imagedestroy($dst_img);	
				
				
				
                return true;

        }
	
	public function CreateDir($root){

               if (is_dir($root))        {

                        $retorno = "0";
                }else{

                        $oldumask = umask(0);
                        $valrRet = mkdir($root,0777);
                        umask($oldumask);


                        $retorno = "1";
                }

    }
	
	public function genRandomString() 
	{
		$length = 5;
		$characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWZYZ";
		
		$real_string_legnth = strlen($characters) ;
		//$real_string_legnth = $real_string_legnth– 1;
		$string="ID";
		
		for ($p = 0; $p < $length; $p++)
		{
			$string .= $characters[mt_rand(0, $real_string_legnth-1)];
		}
		
		return strtolower($string);
	}
	
	
	/*Create user*/
	function uultra_create_account() 
	{
		
		global $xoouserultra;
		session_start();
		
		
			/* Create profile when there is no error */
			if (!isset($this->errors)) 
			{
				
				/* Create account, update user meta */
				$sanitized_user_login = sanitize_user($_POST['user_login']);
				
				/* Get password */
				if (isset($_POST['user_pass']) && $_POST['user_pass'] != '') 
				{
					$user_pass = $_POST['user_pass'];
				} else {
					//$user_pass = wp_generate_password( 12, false);
					//$user_pass = wp_generate_password( 8, false);
					global $wpdb;
					$id_arr=$wpdb->get_results("SHOW TABLE status LIKE 'wp_users'");
									
					foreach ($id_arr as $idA)
					{
					$id1=$idA->Auto_increment;
					//$pa.="id1:".$id1;
					}
					$user_pass = $sanitized_user_login.$id1;
					//$file2=fopen("user__pass_chk.txt","a");
					//$pa.="userpass:";
					//$pa.=$user_pass;
					//fwrite($file2,$pa);
				}
				
				/* We create the New user */
				$user_id = wp_create_user( $sanitized_user_login, $user_pass, $_POST['user_email'] );
				
				if ( ! $user_id ) 
				{

				}else{
					
					/*We've got a valid user id then let's create the meta informaion*/						
					foreach($this->usermeta as $key => $value) 
					{
						if (is_array($value))   // checkboxes
						{
							$value = implode(', ', $value);
						}
											
							
						update_user_meta($user_id, $key, esc_attr($value));
						
						//$file3=fopen("reg_fields_chk.txt","a");
					
							//$info="user:".$user_id." -> "."key:".$key." -> "."value:".$value." -> "."from_db:".get_user_meta($user_id,$key);
						
						

						/* update core fields - email, url, pass */
						if ( in_array( $key, array('user_email', 'user_url', 'display_name') ) )
						{
							wp_update_user( array('ID' => $user_id, $key => esc_attr($value)) );
						}

						if($key=="phone")
						{
						global $wpdb;
						$wpdb->update("wp_users", array("phone" => $value), array("ID"=>$user_id));
						}

						
						//fwrite($file3,$info);
					}



					
					//update user pic
					if(isset($_FILES['user_pic']))
					{
						$this->upload_front_avatar($user_id );
							
					}
					
					//set account status					
					$xoouserultra->login->user_account_status($user_id);
					
					$verify_key = $xoouserultra->login->get_unique_verify_account_id();					
					update_user_meta ($user_id, 'xoouser_ultra_very_key', $verify_key);					


		
					
					 //mailchimp					 
					 if(isset($_POST["uultra-mailchimp-confirmation"]) && $_POST["uultra-mailchimp-confirmation"]==1)
					 {
						 $list_id =  $xoouserultra->get_option('mailchimp_list_id');					 
						 $xoouserultra->subscribe->mailchimp_subscribe($user_id, $list_id);
						 update_user_meta ($user_id, 'xoouser_mailchimp', 1);	
						 						
					
					 }


					//Registration Done: Give 7-day free Trail: only if there is no coupon

					if($_POST['cc']=="")
					{

						global $wpdb;
						$status="active";
						$d=new DateTime("now");
						$startdate=$d->format("Y-m-d H:i:s");

						$e=$d->add(new DateInterval('P7D'));
						$enddate=$e->format("Y-m-d H:i:s");

						$plan_id=4;

						$wpdb->insert("wp_pmpro_memberships_users",array('user_id'=>$user_id,'membership_id'=>4,'initial_payment'=>0.00,'billing_amount'=>0.00,'cycle_number'=>0,'cycle_period'=>'','billing_limit'=>0,'trial_amount'=>0.00,'trial_limit'=>0,'status'=>'active','startdate'=>$startdate,'enddate'=>$enddate));
					$coupon_code='-';

					}
					else
					{
						global $wpdb;
						$status="active";
						$d=new DateTime("now");
						$startdate=$d->format("Y-m-d H:i:s");
						$start=$d->format("Y-m-d");
						$coupon_code=$_POST['cc'];
						$cpn_details=$wpdb->get_results("SELECT * from buy_a_friend where coupon_code='".$coupon_code."' and status='Paid'");		

						$promo_cpn_details=$wpdb->get_results("SELECT * from promo_codes where coupon_code='".$coupon_code."' and usage_count<max_count");			

						if(count($cpn_details)<1 && count($promo_cpn_details)<1)
						{
							echo "Invalid Code"; 
						}
		
						if(count($cpn_details)>=1)
						{
							foreach($cpn_details as $cpn)
							{
								$plan_id=$cpn->plan_id;
							}
						}
						if(count($promo_cpn_details)>=1)
						{
							foreach($promo_cpn_details as $cpn)
							{
								$plan_id=$cpn->plan_id;
							}
						}
						//$e=$d->add(new DateInterval('P7D'));


						$plan_details=$wpdb->get_results("SELECT * from wp_pmpro_membership_levels where id=".$plan_id." ");					

						foreach($plan_details as $plan){
							//$plan_id=$plan->plan_id;
							$i_amt=$plan->initial_payment;
							$b_amt=$plan->billing_amount;
							$c_no=$plan->cycle_number;
							$c_prd=$plan->cycle_period;
							$b_limit=$plan->billing_limit;
							$t_amt=$plan->trial_amount;
							$t_limit=$plan->trial_limit;
							$exp_no=$plan->expiration_number;
							$exp_prd=$plan->expiration_period;
						}

						if($c_no==0){
							//$plan_id==4 free trial
						
							$e=$d->add(new DateInterval('P'.$exp_no.$exp_prd[0]));
							if(count($promo_cpn_details)>=1){
									$e=$e->add(new DateInterval('P2W')); //extra 2weeks as for promo_code
							}						
						}
						else
						{
						$e=$d->add(new DateInterval('P'.$c_no.$c_prd[0]));
						}
						$enddate=$e->format("Y-m-d H:i:s");
						$end=$e->format("Y-m-d");


						

						$wpdb->insert("wp_pmpro_memberships_users",array('user_id'=>$user_id,'membership_id'=>$plan_id,'code_id'=>$coupon_code,'initial_payment'=>$i_amt,'billing_amount'=>$b_amt,'cycle_number'=>$c_no, 'cycle_period'=>$c_prd,'billing_limit'=>$b_limit,'trial_amount'=>$t_amt,'trial_limit'=>$t_limit,'status'=>'active','startdate'=>$startdate,'enddate'=>$enddate));

						if(count($promo_cpn_details)>=1){
							$count=$wpdb->get_var("SELECT usage_count from promo_codes where coupon_code='".$coupon_code."' ");
							$wpdb->update("promo_codes", array("usage_count"=>$count+1), array("coupon_code"=>$coupon_code));			

							$wpdb->insert("promocodes_vs_users",array("promocode"=>$coupon_code,"user_id"=>$user_id,"start_datetime"=>$start,"end_datetime"=>$end));

							update_option('promo_used_'.$user_id,'true');					
			}
						
						else{
							$wpdb->update("buy_a_friend", array("status"=>"Coupon Used"), array("coupon_code"=>$coupon_code));					}

					}
					
					update_option("subscribed_".$user_id,"true");
					$this->new_user_regsub($user_id,$plan_id,$coupon_code,$startdate,$enddate);


			
					
				}
				

				//check if it's a paid sign up				
				
				if($xoouserultra->get_option('registration_rules')==4)
				{
					//this is a paid sign up					
										
					//get package
					$package = $xoouserultra->paypal->get_package($_POST["usersultra_package_id"]);
					$amount = $package->package_amount;
					$p_name = $package->package_name;
					$package_id = $package->package_id;
					
					//payment Method
					$payment_method = 'paypal';
					
					//create transaction
					$transaction_key = session_id()."_".time();
					
					$order_data = array('user_id' => $user_id,
					 'transaction_key' => $transaction_key,
					 'amount' => $amount,
					 'order_package_id' => $package_id ,
					 'product_name' => $p_name ,
					 'status' => 'pending',
					 'method' => $payment_method); 
					 
					if( $amount > 0)
					 {
						 $xoouserultra->order->create_order($order_data);
						
					 }	
					
										
					
					//update status
					 update_user_meta ($user_id, 'usersultra_account_status', 'pending_payment');
					 
					 
					 //package 
					 update_user_meta ($user_id, 'usersultra_user_package_id', $package_id);
					 
					 //mailchimp					 
					 if(isset($_POST["uultra-mailchimp-confirmation"]) && $_POST["uultra-mailchimp-confirmation"]==1)
					 {						
						 //do mailchimp stuff	
						 $list_id =  $xoouserultra->get_option('mailchimp_list_id');					 
						 $xoouserultra->subscribe->mailchimp_subscribe($user_id, $list_id);	
						 update_user_meta ($user_id, 'xoouser_mailchimp', 1);					
					
					  }
					 
					 
					 
					 //set expiration date
					 
					 if($payment_method=="paypal" && $amount > 0)
					 {
						  $ipn = $xoouserultra->paypal->get_ipn_link($order_data);
						  
						  //redirect to paypal
						  header("Location: $ipn");exit;						  
						  exit;					  
						 
					 }else{						 
						 
						 //paid membership but free plan selected						 
						 //notify depending on status
					      $xoouserultra->login->user_account_notify($user_id, $_POST['user_email'],  $sanitized_user_login, $user_pass);
						  
						  //check if requires admin approvation
						  
						  if($package->package_approvation=="yes")
						  {
							  
							  
							 
						  }else{
							  
							  //this package doesn't require moderation
							   update_user_meta ($user_id, 'usersultra_account_status', 'active');
							  //notify user					   
		 					   $xoouserultra->messaging->welcome_email($_POST['user_email'], $sanitized_user_login, $user_pass);
							  
							   //login
							   $secure = "";		
							  //already exists then we log in
							  wp_set_auth_cookie( $user_id, true, $secure );	
							  //redirect
							  $xoouserultra->login->login_registration_afterlogin();
							  
						  
						  }
						 
						 
					 }
					 
				
				}else{
					
					//this is not a paid sign up
					
					//notify depending on status
					$xoouserultra->login->user_account_notify($user_id, $_POST['user_email'],  $sanitized_user_login, $user_pass);
										
				
				}	
				
				
				 //check if login automatically
				  $activation_type= $xoouserultra->get_option('registration_rules');
				  
				  if($activation_type==1)
				  {					  					  
					  //login
					   $secure = "";		
					  //already exists then we log in
					  wp_set_auth_cookie( $user_id, true, $secure );	
					  //redirect
		              $xoouserultra->login->login_registration_afterlogin();						
	  
	              } 
				
				
			} //end error link
			
	}
	
	/*Get errors display*/
	function get_errors() {
		global $xoouserultra;
		global $wpdb;
		$display = null;
		if (isset($this->errors) && count($this->errors)>0) 
		{
		$display .= '<div class="usersultra-errors">';
			foreach($this->errors as $newError) {
				
				$display .= '<span class="usersultra-error usersultra-error-block"><i class="usersultra-icon-remove"></i>'.$newError.'</span>';
			
			}
		$display .= '</div>';
		} else {
		
			$this->registered = 1;
			
			
			$uultra_settings = get_option('userultra_options');

			$reg_email=$wpdb->get_var("SELECT user_email from wp_users where user_email='".$_POST['user_email']."' ");

            // Display custom registraion message
            if (isset($uultra_settings['msg_register_success']) && !empty($uultra_settings['msg_register_success']))
			{
               // $display .= '<div class="xoouserultra-success"><span><i class="fa fa-ok"></i>' . remove_script_tags($uultra_settings['msg_register_success']) . '</span></div>';

		$display .= '<div class="xoouserultra"><span><i class="fa fa-ok"></i>
			<script>
			
			swal({
         title:"Thank you for Registering with Qezyplay", 
  text: "<p>Please activate your account from Registered Email: <u>'.$reg_email.'</u></p> <p>To change your Email, click on Edit-Email</p><p> If you did not receive the email in next 3-5 min click on Resend-Email</p><p>On receiving the email , click on activation link</p><p> To close this box, click on Done</p><p> Thank You</p><a href=\"'.site_url().'\" style=\"padding: 10px 10%;   background-color: green !important;    border-radius: 5px !important;    text-align: center;    font-size: 14px;    margin-bottom: -12px;color: white;  text-decoration: none;\">Done</a>",
type: "warning",
  html:"true",
  showCancelButton: true,   confirmButtonColor: "#DD6B55", cancelButtonColor: "#29a1d8 !important",  confirmButtonText: "Resend-Email",   cancelButtonText: "Edit-Email", closeOnEsc:true, closeOnConfirm: false, closeOnCancel: false, showLoaderOnConfirm: true, showLoaderOnCancel: true
},function(isConfirm){

if(isConfirm) {


var values = {"action":"resendEmail", "user_email":"'.$reg_email.'"};
jQuery.ajax({url: "http://ideabytestraining.com/newqezyplay/qp/uservalidation_check.php",
			type: "post",
			data: values,
			success: function(data){ 

				swal(data);
				return true;
			 }
		});	

return false;		
/*.done(function(data) {        swal(data);      })*/


}

else{


var values;
swal({   title: "Change Email",   text: "Enter Email",   type: "input",   showCancelButton: true,  showLoaderOnConfirm: true, closeOnConfirm: false,   animation: "slide-from-top",   inputPlaceholder: "please enter new valid email" }, function(inputValue){  
       
       if (inputValue === false) return false;      
       
       if (inputValue === "") {     
       
       swal.showInputError("Please Enter Email");     return false   }     


	var ch=validateEmail(inputValue);
	if (!ch) {     
       
       swal.showInputError("Please Enter Valid Email");     return false   } 

       
       if(inputValue!=="")
       	{
        values = {"action":"regEditEmail", "user_email":inputValue,"old_user_email":"'.$reg_email.'"};
         jQuery.ajax({
            url: "http://ideabytestraining.com/newqezyplay/qp/uservalidation_check.php",
            type: "POST",
            data: values,
             success: function (data) {
                swal(data);
                	return true;
            }
           
        });
        
        return false;
        }
       
       });


return false;

}

}); 

			</script>

</span></div>';
		$display .='<script>  clear="ok"; </script>';
            
			}else{
				
                $display .= '<div class="xoouserultra-success"><span><i class="fa fa-ok"></i>'.__('Registration successful. Please check your email.','xoousers').'</span></div>';
            }

            // Add text/HTML setting to be displayed after registration message
            if (isset($uultra_settings['html_register_success_after']) && !empty($uultra_settings['html_register_success_after'])) 
			
			{
             //   $display .= '<div class="xoouserultra-success-html">' . remove_script_tags($uultra_settings['html_register_success_after']) . '</div>';

		//$display .= '<div style="text-align:center" class="xoouserultra-success-html"><a href="'.site_url('login').'">LOGIN</a></div>';
            }
			
			
			
			if (isset($_POST['redirect_to'])) {
				wp_redirect( $_POST['redirect_to'] );
			}
			
		}
		return $display;
	}

}
$key = "register";
$this->{$key} = new XooUserRegister();
