<?php
/**
 * Handle Team shortcode.
 *
 * @param  
 * @return 
 */
function maintainn_team_shortcode( $attr = array() ) {

  $teamid=$attr['teamid'];
  $post = get_post();

// Get team members attached to this page.
$members = get_post_meta($teamid , '_tcode_teammeta', true );

$output = '';

// Return empty string, if we don't have members.
if ( empty( $members ) ) {
return $output;
}

// We have team members and now we can output them.
$output .= '<div class="tc_team-members">';

foreach ( $members as $member ) {
  $output .= '<div class="tc_team-member tc_member-col-single tc_text-center">';
      $output .= '<div class="tc_member-thumb">';
       $output .= '<img src="' . esc_attr( $member['member_image'] ) . '" alt="' . esc_attr( $member['full_name'] ) . '" />';
        $output .= '<div class="tc_overlay">';
         $output .= '<h3>'. esc_attr( $member['full_name'] ).'</h3>';
          $output .= '<h4 class="tc_job_role">'.esc_attr( $member['job_role'] ).'</h4>';
          $output .= '<p class="tc_member-p">'.esc_attr( $member['description'] ).'</p>';
          $output .= '<ul class="tc_social-links tc_text-center">';
          if(!empty($member['facebook_url'])){
            $output .= '<li><a href="' . esc_attr( $member['facebook_url'] ).'"><i class="fa fa-facebook fa-lg "></i></a></li>';
          }
          if(!empty($member['twitter_url'])){
          $output .= '<li><a href="' . esc_attr( $member['twitter_url'] ).'"><i class="fa fa-twitter fa-lg"></i></a></li>';
            }

          if(!empty($member['linkedin_url'])){

         $output .= '<li><a href="' . esc_attr( $member['linkedin_url'] ).'"><i class="fa fa-linkedin fa-lg"></i></a></li>';

          }
         if(!empty($member['google-plus_url'])){

        $output .= '<li><a href="' . esc_attr( $member['google-plus_url'] ).'"><i class="fa fa-google-plus fa-lg"></i></a></li>';
       }
       $output .= '</ul>';
      $output .= '</div>';
    $output .= '</div>'; 
  $output .= '</div>';

}

$output .= '</div>';

return $output;
}
add_shortcode( 'tc-team-members', 'maintainn_team_shortcode' );
