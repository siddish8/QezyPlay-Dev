<?php 

/*
Plugin Name: Admin - Customers
Plugin URI: 
Description: Adding customers to the site by admin only
Author: IB
Version: 1.0
Author URI: ib
*/

add_action('admin_menu', 'admin_customer_setup_menu');
 
function admin_customer_setup_menu(){
        add_menu_page( 'Admin - Customer Management', 'Admin - Customer', 'manage_options', 'admin-customer-plugin', 'customer_init' );
}
 
function customer_init(){
	
    echo "<h1>Customer Management</h1><br/>";
	customers_to_db();
}


add_shortcode('CustomerAdmin','customers_to_db');
function customers_to_db(){
	
	global $current_user;
	global $wpdb;
	
	$msg = ""; $action = "Add";


/* wp_dropdown_categories( array(
    'hide_empty'   => 0,
    'name'         => 'select_name',
    'id'           => 'select_name',
    'hierarchical' => true
) ); */



	
		

	get_currentuserinfo();
	$user_id = get_current_user_id();
	$dispName=$current_user->display_name;
	
	if(isset($_GET['del'])){

		$id = $_GET['id'];

		$wpdb->delete("customer_info",array("id"=>$id));
		
		$msg = "<span style='color:green'>Customer deleted Successfully</span>";
		
	}


	if(isset($_POST['Add_Submit'])){
		
		$customername=trim($_POST['customername']);
		$phone=trim($_POST['phone']);
		$email=trim($_POST['email']);
		//$authkey=trim($_POST['add_authkey']);

		$logo=trim($_POST['logo']);
		
		$authkey = md5($phone.$email);
		$authkey = substr($authkey, 0, 10);	
		//$authkey="";
		$date = gmdate("Y-m-d H:i:s");
		
		$userexit = $wpdb->get_var('select id from customer_info where mobile = "'.$phone.'" OR customername = "'.$customername.'" OR email = "'.$email.'"'); 		
		if((int)$userexit > 0){
			
			echo $msg = "<span style='color:red'>Customer already exist</span>";
			
		}else{			
			
			//insert query
			$wpdb->insert("customer_info", array(
						"customername" => $customername,
						"mobile" => $phone, 
						"customer_logo_url" => $logo, 
						"email" => $email,
						"authkey" => $authkey,
						"created_datetime"=>$date)
					);

			$customerloginlink = SITE_URL."/qp/customer-login.php";
			
			// Always set content-type when sending HTML email
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			// More headers
			$headers .= 'From: <'.ADMIN_EMAIL.'>' . "\r\n";
			
			$subjectCustomer = "Your account info on Qezyplay.com as Customer";
			$regards = "<p>Regards, <br>Admin - QezyPlay</p>";
			$bodyCustomer = "<p>Hi ".$customername.",</p>
			<p>Your account has been created in Qezyplay.com as customer</p>
			<p>Find below credentials to login with customer portal</p>
			<p>
			<b>Email:</b> $email<br>
			<b>Auth Key:</b> $authkey<br>
			</p>
			<p>Log in to your customer portal here: $customerloginlink</p>".$regards;	
			
			//mail Agent
			mail($email, $subjectCustomer, print_r($bodyCustomer,true), $headers);

			$msg = "<span style='color:green'>customer added Successfully</span>";
						
		}
	}


	if(isset($_POST['Edit_Submit'])){

		$customerid=trim($_POST['customerid']);
		
		//$customerid = $_GET['id'];
		$customername=trim($_POST['customername']);
		$phone=trim($_POST['phone']);
		$email=trim($_POST['email']);
		$logo=trim($_POST['logo']);
		$channel=trim($_POST['channel']);
		$date = gmdate("Y-m-d H:i:s");
		
		$userexit = $wpdb->get_var('select id from customer_info where id != '.$id.' AND (mobile = "'.$phone.'" OR customername = "'.$customername.'" OR email = "'.$email.'")'); 
		if((int)$userexit > 0){
			
			$msg = "<span style='color:red'>Customer already exist</span>";
			
		}else{
			
			//update query
			$wpdb->update("customer_info", array(   
					"customername" => $customername,
					"mobile" => $phone, 
					"email" => $email,
					"channel_id" => $channel,
					"customer_logo_url" => $logo, 		
					"created_datetime"=>$date), array("id"=>$customerid)
					);

			$msg = "<span style='color:green'>Customer updated Successfully</span>";
									
		}		
		
	}	


	if(isset($_GET['edit'])){

		$id = $_GET['id'];		

		$res3 = $wpdb->get_results("SELECT * FROM customer_info where id='".$id."'");
		foreach($res3 as $row3){
			$customername1=$row3->customername;
			$mobile1=$row3->mobile;
			$email1=$row3->email;
			$logo1=$row3->customer_logo_url;
		}
	
			
		$action = "Edit";

	}	

	?>

	<style>
	#addcustomer_submit{
		background-color: #0073aa !important;
		color: azure !important;
		padding: 5px;text-decoration: none;border:solid 1px #0073aa !important;
	}
	#addcustomer_submit:hover{
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
	<div align="center" id="<?php echo $action;?>customer">
		<form method="post" >
			<table>
				<tr><td colspan="2"><center><span style="font-weight:bold;font-size:18px;"><?php echo $action;?> Customer</span></center><br>
				<input type="hidden" id="customerid" name="customerid" value="<?php echo $id; ?>"/></td></tr>
				<tr><td>Customer name</td><td><input type="text" value="<?php echo @$customername1;?>" id="customername" name="customername" required/></td></tr>
				<tr><td>Logo(path)</td><td><input type="text" value="<?php echo @$logo1;?>" id="logo" name="logo" required/></td></tr>
				<tr><td>Mobile</td><td><input type="tel" value="<?php echo @$mobile1;?>" id="phone" name="phone" required/></td></tr>
				
				<tr><td>E-mail</td><td><input type="email" value="<?php echo @$email1;?>" required id="email" name="email" /></td></tr>
				<tr><td>Channel</td><td>
				<!--<select name="channel" id="channel">
				</select>-->
				<?php 
								$defaults = array(
						'selected'              => FALSE,
						'pagination'            => FALSE,
						'posts_per_page'        => - 1,
						'post_status'           => 'publish',
						'cache_results'         => TRUE,
						'cache_post_meta_cache' => TRUE,
						'echo'                  => 1,
						'select_name'           => 'post_id',
						'id'                    => '',
						'class'                 => '',
						'show'                  => 'post_title',
						'show_callback'         => NULL,
						'show_option_all'       => NULL,
						'show_option_none'      => NULL,
						'option_none_value'     => '',
						'multi'                 => FALSE,
						'value_field'           => 'ID',
						'order'                 => 'ASC',
						'orderby'               => 'post_title',
					);
					$r = wp_parse_args( $args, $defaults );
					$posts  = get_posts( $r );
					$output = '';
					$show = $r['show'];
					if( ! empty($posts) ) {
						$name = esc_attr( $r['select_name'] );
						if( $r['multi'] && ! $r['id'] ) {
							$id = '';
						} else {
							$id = $r['id'] ? " id='" . esc_attr( $r['id'] ) . "'" : " id='$name'";
						}
						$output = "<select name='{$name}'{$id} class='" . esc_attr( $r['class'] ) . "'>\n";
						if( $r['show_option_all'] ) {
							$output .= "\t<option value='0'>{$r['show_option_all']}</option>\n";
						}
						if( $r['show_option_none'] ) {
							$_selected = selected( $r['show_option_none'], $r['selected'], FALSE );
							$output .= "\t<option value='" . esc_attr( $r['option_none_value'] ) . "'$_selected>{$r['show_option_none']}</option>\n";
						}
						foreach( (array) $posts as $post ) {
							$value   = ! isset($r['value_field']) || ! isset($post->{$r['value_field']}) ? $post->ID : $post->{$r['value_field']};
							$_selected = selected( $value, $r['selected'], FALSE );
							$display = ! empty($post->$show) ? $post->$show : sprintf( __( '#%d (no title)' ), $post->ID );
							if( $r['show_callback'] ) $display = call_user_func( $r['show_callback'], $display, $post->ID );
							$output .= "\t<option value='{$value}'{$_selected}>" . esc_html( $display ) . "</option>\n";
						}
						$output .= "</select>";
					}
			echo $output;

				?>
				</td></tr>
				<tr><td></td><td><input type="submit" name="<?php echo $action;?>_Submit"  value="Submit" style="cursor: pointer;background-color: #0073aa !important;
				color: azure !important;
				padding: 5px;text-decoration: none;border:solid 1px #0073aa !important;" /></td></tr>
			</table>
		</form>
	</div>
	<div class="clear"></div>

	<br />

	<div id="CustomerList"><h2>Customers List</h2> 
		<table class="widefat membership-levels" style="width:95% !important;">
			<thead>
				<tr>					
					<th>Customer Name</th>
					<th>Logo</th>
					<th>Mobile</th>					
					<th>E-mail</th>
					<th>Auth Key</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody class="ui-sortable">
			<?php
			$res=$wpdb->get_results("SELECT * FROM customer_info"); 

			foreach($res as $row){

				$id=$row->id;
				$customername=$row->customername;
				$mobile=$row->mobile;
				$email=$row->email;
				$authkey=$row->authkey;

				$logo = $row->customer_logo_url;
			?>
				<tr style="" class="ui-sortable-handle">							
				<td style="width: 342px;" class="level_name"><?php echo $customername;?></td>
				<td style="width: 192px;"><img height="50px" width="150px" src="<?php echo $logo;?>"></td>
				<td style="width: 184px;"><?php echo $mobile;?></td>				
				<td style="width: 192px;"><?php echo $email;?></td>
				<td style="width: 192px;"><?php echo $authkey;?></td>
				<td style="width: 332px;"><a style="cursor: pointer;" id="editcustomer" href="admin.php?page=admin-customer-plugin&edit=true&id=<?php echo $id;?>" title="edit" name="Edit-<?php echo $id ?>" class="button-primary">Edit</a>&nbsp;
				<a style="cursor: pointer;" title="delete" name="removecustomer-<?php echo $id;?>" id="removeCustomer-<?php echo $id;?>" onclick="callConfirmation('admin.php?page=admin-customer-plugin&del=true&id=<?php echo $id;?>');" class="button-secondary">Delete</a></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
	<div class="clear"></div><br /><br />


	<script>
	function callConfirmation(url){

		var ans = confirm("Sure, do you want to delete this customer?");
		if(ans){
			window.location.href = url;
		}
	}
	</script>
<?php
} 
