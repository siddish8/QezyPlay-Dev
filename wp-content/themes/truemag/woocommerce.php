<?php 
/* This is default template for Woo */
global $global_page_layout;
$layout = get_post_meta(get_the_ID(),'sidebar',true);
if(is_shop()){
	$layout = get_post_meta(get_option('woocommerce_shop_page_id'),'sidebar',true);
}
if(!$layout){
	$layout = $global_page_layout ? $global_page_layout : ot_get_option('page_layout','right');
}
if(is_product() && !is_active_sidebar( 'single_woo_sidebar' )){
	$layout = 'full';
}
global $sidebar_width;
global $post;
get_header();
$topnav_style = ot_get_option('topnav_style','dark');	
?>
	<div class="blog-heading <?php echo $topnav_style=='light'?'heading-light':'' ?>">
    	<div class="container">
            <h1><?php if(is_single()){ the_title(); }else{ woocommerce_page_title(); } ?></h1>
        </div>
    </div><!--blog-heading-->
    <div id="body">
        <div class="container">
            <div class="row">
  				<div id="content" class="<?php echo $layout!='full'?($sidebar_width?'col-md-9':'col-md-8'):'col-md-12' ?><?php echo ($layout == 'left') ? " revert-layout":"";?>" role="main">
                <?php
					//content
					woocommerce_content();
					//share
					$social_post= get_post_meta($post->ID,'showhide_social',true);
					if($social_post=='show'){ //check if show social share
						gp_social_share(get_the_ID());
					}
					if($social_post=='def'){
						if(ot_get_option( 'page_show_socialsharing', 1)){ //check if show social share
							gp_social_share(get_the_ID());
						}
					}
					?>
                    <?php $pagination = ot_get_option('pagination_style');
					if($pagination=='page_ajax'){?>
                    <script type="text/javascript">
						jQuery(document).ready(function($) {
							$('nav.woocommerce-pagination').css('display','none');
							var pageNum = <?php echo ( get_query_var('paged') > 1 ) ? get_query_var('paged') : 1;?>;
							var ot_permali = "<?php echo get_option('permalink_structure');?>";
							var pageNum = parseInt(pageNum) + 1;
							<?php global $wp_query;?>
							var max = "<?php echo $wp_query->max_num_pages;?>";
							var nextLink = "<?php echo next_posts(5, false);?>";
							var textLb1 = "<?php echo __('MORE','cactusthemes');?>";
							var textLb2 = "<?php echo __('LOADING POSTS','cactusthemes')?>";
							if(pageNum <= max) {
								// Insert the "More Posts" link.
								$('ul.products')
								.append('<div class="ajax-load-post-'+ pageNum +'"></div>')
								.append('<p id="load_shop"><a href="#" class="light-button btn btn-default btn-lg btn-block">'+ textLb1 +'...</a></p>');
									
								// Remove the traditional navigation.
								$('.navigation').remove();
							}
							$('#load_shop a').click(function() {
								$(this).text(textLb2+'...');
								$('.ajax-load-post-'+ pageNum).load(nextLink + ' ul.products li',
									function() {
										// Update page number and nextLink.
										if (history.pushState) {
											history.pushState(null, "", nextLink); 
										}
										pageNum++;
										if(ot_permali){
											nextLink = nextLink.replace(/\/page\/[0-9]?/, '/page/');
											nextLink = nextLink.replace(/\/page\/[0-9]?/, '/page/'+ pageNum);
										} else {
											//alert(pageNum);
											nextLink = nextLink.replace(/paged\=[0-9]?/, 'paged=');
											nextLink = nextLink.replace(/paged\=[0-9]?/, 'paged='+ pageNum);						
											//alert(nextLink);
										}
										// Add a new placeholder, for when user clicks again.
										$('#load_shop').before('<div class="ajax-load-post-'+ pageNum +'"></div>');
										
										// Update the button message.
										if(pageNum <= max) {
											$('#load_shop a').text( textLb1+'...');
										} else {
											$('#load_shop a').css('display','none');
										}
									}
								);
								$('#pbd-alp-load-posts').before('<div class="ajax-load-post-'+ pageNum +'"></div>');
								event.preventDefault();
							});
						});
					</script>
                    <?php }?>
                </div><!--#content-->
                <?php
				if($layout != 'full'){
					get_sidebar();
				}?>
            </div><!--/row-->
        </div><!--/container-->
    </div><!--/body-->
<?php get_footer(); ?>