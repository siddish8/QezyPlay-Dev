<?php
global $xoouserultra;

?>
<div class="uultra-profile-basic-wrap" style="width:<?php echo $template_width?>">

<div class="commons-panel xoousersultra-shadow-borers" >

        <div class="uu-left">
        
        
           <div class="uu-main-pict " style="margin-right:25px"> 
           <br>
	
             <h2 id="profilepic"><?php //echo $xoouserultra->userpanel->get_user_pic( $user_id, $pic_size, $pic_type, $pic_boder_type,  $pic_size_type) ; 

//echo get_avatar($user_id,96,$default='http://ideabytestraining.com/demoqezyplay/wp-content/uploads/2016/06/gravatar.png');
echo $xoouserultra->userpanel->get_user_pic( $user_id, $pic_size, $pic_type, $pic_border_type,  $pic_size_type,$default='') ;
?>
           
           <br>
<br>
              <p style="text-align:center">    <?php echo $xoouserultra->userpanel->get_display_name($current_user->ID);?></p></h2>
               
                 <button id="edit_link" onclick="location.href='<?php echo site_url()?>/edit-profile/'" >Edit Profile</button> 


          
           </div>
           
                      
        </div>
        
        
        <div class="uu-right">

<div>
 <?php if ($optional_fields_to_display=="all") { ?>    
		          
                 
             <?php echo $xoouserultra->userpanel->display_optional_fields3( $user_id,$display_country_flag, $optional_fields_to_display);
		?>   
          
<?php } ?>

                 
<?php if ($optional_fields_to_display!="") { ?>    
		          
                 
                   <?php echo $xoouserultra->userpanel->display_optional_fields( $user_id,$display_country_flag, $optional_fields_to_display);

?>                 
<?php } ?>

		<?php if ($profile_fields_to_display=="all") { ?>                 
                 
                   <?php echo $xoouserultra->userpanel->get_profile_info2( $user_id); ?>  
             
 <?php } ?>
</div>
        
   
                   
 </div>   

</div>
