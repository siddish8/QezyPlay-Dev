<?php 
    /*
    Plugin Name: Channel Page Image VS divs
    Plugin URI: 
    Description: This creates a vertical scroll images in channel page
    Author: IB
    Version: 1.0
    Author URI: ib
    */

add_shortcode('vs_channel','vs_channel_fn');

function vs_channel_fn($atts){

	global $wpdb;
	global $post;
	$channel_atts = shortcode_atts( array(
		'cat' => '',
		'ids' => '',
		'heading' => '',
	
	       ), $atts );

	$args = array( 'cat' => $channel_atts['cat'],'ids' => $channel_atts['ids'],'heading' => $channel_atts['heading']);
	$ids=$channel_atts['ids'];
	$cat=$channel_atts['cat'];
	$heading=$channel_atts['heading'];

	if($ids!=''){
		$sql='SELECT id FROM wp_posts where post_type="post" and post_status="publish" and id in ('.$ids.')';
		

	}


	if($cat!="")
	{

		$sql='SELECT object_id as id FROM wp_term_relationships where term_taxonomy_id=(SELECT term_id FROM wp_terms where slug="'.$cat.'" );';
		
	}


	$channels=$wpdb->get_results($sql);
		echo '<style>.vs-chan img {    border: 1px solid #cfd7de;} .chan_div{overflow-y: scroll;
    height: 500px;
    margin: 0 auto;
    border: 3px solid #CCCCCC;
    margin-bottom: 30px;
    overflow-x: hidden;
    position: relative;
    width: auto;
    padding: 10px 20px;
    display: inline-block;}</style>';
		echo '<h2 style="text-align:center">'.$heading.'</h2>';
		echo '<div align="center" class="chan_div">';
		$count=0;
		//echo $count;
		if (is_array($channels) || is_object($channels)){
	    
			//echo "in1";
			foreach($channels as $channel){
				
		 		$id=$channel->id."<br>";
				

				if($id>0){
		
					$mypost=get_post($id);

								
					if (is_array($mypost) || is_object($mypost)){
		 				foreach ( $mypost as $post ) : setup_postdata( $post );
					
											
							if ( has_post_thumbnail() ) {
							
									$width="175px";
									$height="120px";
									$crop="true";
									set_post_thumbnail_size( $width, $height, $crop ); ?> 
									<p><h4 style='text-align:center'><?php the_title()?></h4><a class="vs-chan" href="<?php the_permalink(); ?>"> <?php echo the_post_thumbnail(); ?></a></p>

									<?php  }
						endforeach;
					}

				}
	 		}
		}
	echo '</div>';		

		echo '<script>jQuery("div.wpb_wrapper").attr("align","center")</script>';
	

	


}
?>

<?php	

