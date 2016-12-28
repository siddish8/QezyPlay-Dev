<?php 

/*
Plugin Name: Admin Display: Forms-Contact & Feedback 
Plugin URI: 
Description: List of user sent details from form
Author: IB
Version: 1.0
Author URI: ib
*/

add_action('admin_menu', 'admin_user_contact_menu');
 
function admin_user_contact_menu(){
        add_menu_page( 'Admin - Forms Plugin Page', 'Admin- Forms', 'manage_options', 'admin-forms-plugin', 'forms_init' );
}
 
function forms_init(){
	
    echo "<h1>User Forms</h1><br/>";
	forms_from_db();
}

add_shortcode('UserFormsAdmin','forms_from_db');

function forms_from_db()
{
global $wpdb;


echo '<style>

.mx{max-width:80%;margin:0 auto !important;}
 /* Style the list */
ul.tab {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
}

/* Float the list items side by side */
ul.tab li {float: left;}

/* Style the links inside the list items */
ul.tab li a {
    display: inline-block;
    color: black;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
    transition: 0.3s;
    font-size: 17px;
}

/* Change background color of links on hover */
ul.tab li a:hover {background-color: #ddd;}

/* Create an active/current tablink class */
ul.tab li a:focus, .active {background-color: #ccc;}

/* Style the tab content */
.tabcontent {
    display: none;
       border-top: none;
}
</style>';

echo '<ul class="tab mx">
  <li><a id="UC" href="#" class="tablinks active" onclick="userForms(event, \'UserContact\')">User Contact List</a></li>
  <li><a id="UF" href="#" class="tablinks" onclick="userForms(event, \'UserFeedback\')">User Feedback List</a></li>
 </ul>';

echo '<div id="UserContact" class="tabcontent mx" style="display:block">
		<table class="widefat membership-levels" style="width:100% !important;">
			<thead>
				<tr>					
					<th>First Name</th>
					<th>Last Name</th>
					<th>E-mail</th>
					<th>Phone</th>
					<th>Address</th>
					<th>Message</th>
					
				</tr>
			</thead>
			<tbody class="ui-sortable">';
			
			$res=$wpdb->get_results("SELECT * FROM user_contact"); 

			foreach($res as $row){

				$id=$row->id;
				$fisrtname=$row->first_name;
				$lastname=$row->last_name;
				$email=$row->email;
				$phone=$row->phone;
				$address=$row->address;
				$message=$row->message;
				
		
			echo 	'<tr style="" class="ui-sortable-handle">							
				<td style="width: 192px;" class="level_name">'.$fisrtname.'</td>
				<td style="width: 192px;" class="level_name">'.$lastname.'</td>
				<td style="width: 192px;">'.$email.'</td>
				<td style="width: 184px;">'.$phone.'</td>
				<td style="width: 342px;">'.$address.'</td>
				<td style="width: 342px;">'.$message.'</td>
				
				</tr>';
			 } 
			
			echo '</tbody>
		</table>
	</div>
	<div class="clear"></div><br /><br />
	 <div id="UserFeedback" class="tabcontent mx">
		<table class="widefat membership-levels" style="width:100% !important;">
			<thead>
				<tr>					
					<th>Name</th>
					<th>Gender</th>
					<th>Country</th>
					<th>E-mail</th>
					<th>Phone</th>
					<th>Qezy Platform</th>
					<th>Channel</th>
					<th>Message</th>
					
				</tr>
			</thead>
			<tbody class="ui-sortable">';
			
			$res=$wpdb->get_results("SELECT * FROM user_feedback"); 

			foreach($res as $row){

				$id=$row->id;
				$name=$row->name;
				$gender=$row->gender;
				$country=$row->country;
				$email=$row->email;
				$phone=$row->phone;
				$qezy_platform=$row->qezy_platform;
				$channel=$row->channel;
				$message=$row->message;
				
		
			echo 	'<tr style="" class="ui-sortable-handle">							
				<td style="width: 192px;" class="level_name">'.$name.'</td>
				<td style="width: 192px;" class="level_name">'.$gender.'</td>
				<td style="width: 192px;" class="level_name">'.$country.'</td>
				<td style="width: 192px;">'.$email.'</td>
				<td style="width: 184px;">'.$phone.'</td>
				<td style="width: 192px;">'.$qezy_platform.'</td>
				<td style="width: 192px;">'.$channel.'</td>
				<td style="width: 342px;">'.$message.'</td>
				
				</tr>';
			 } 
			
			echo '</tbody>
		</table>
	</div>';
echo '<script>
function userForms(evt, cityName) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the link that opened the tab
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";

}
</script>';
}
?>
<?php

