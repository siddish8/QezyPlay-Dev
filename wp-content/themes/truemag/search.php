<?php get_header();
global $count;
$layout = ot_get_option('blog_layout');
if(ot_get_option('search_page','search')){
	global $post;
	$post = get_page(ot_get_option('search_page','search'));
	if(!$layout = get_post_meta(ot_get_option('search_page','search'),'sidebar',true)){
		$template = get_post_meta( ot_get_option('search_page','search'), '_wp_page_template', true );
	$template='page-templates/full-width.php'; //added
		if($template == 'page-templates/full-width.php'){
			$layout = 'full';
		}elseif($template == 'page-templates/sidebar-left.php'){
			$layout = 'left';
		}elseif($template == 'page-templates/sidebar-right.php'){
			$layout = 'right';
		}
	}
}
global $sidebar_width;
?>

<div id="body">
	<div class="container">
		<div class="row">
			<?php $pagination = ot_get_option('pagination_style','page_def');?>
			<div id="content" class="<?php echo $layout!='full'?($sidebar_width?'col-md-9':'col-md-8'):'col-md-12' ?><?php echo ($layout == 'left') ? " revert-layout":"";?>" role="main">
				<section class="video-listing">
					<div class="video-listing-head">
						<!--<h2 class="light-title"><?php echo __('Search result: ','cactusthemes').'<i>'.$_GET['s'].'</i>' ?></h2>
						<?php
						if(ot_get_option('show_search_form',1)){
						if (shortcode_exists('advance-search')){
						echo do_shortcode('[advance-search]');
						}else{
						get_search_form();
						}
						}
						?>-->
					</div>
					<?php if (have_posts()) : ?>
					<div class="search-listing-content <?php if($pagination=='page_ajax'||$pagination==''){ echo 'tm_load_ajax';} ?>  ">
						<?php $count=1; while (have_posts()) : the_post(); 
						if (is_search() && ($post->post_type=='page')){ $pagination='else_go_to_no_res';  continue; } //added						
						?>
						<!--<div id="post-<?php the_ID(); ?>" <?php post_class('blog-item video-item'.(has_post_thumbnail()?'':' no-thumbnail')) ?>>
							<!-- <div class="post_ajax_tm" >-->
								<!--<div class="row">-->
									<?php if(has_post_thumbnail()){?>
									<div class="col-md-3 col-sm-3" style="padding:20px;margin-left:0px !important;">
										<div class="item-thumbnail">
											<?php get_template_part('blog-thumbnail'); ?>
										</div>
										<!-- <div class="clearfix"></div>-->
									</div><!--/col6-->
									<?php }?>

									<!--<div class="<?php if(has_post_thumbnail()){?> col-md-9 col-sm-9<?php }else{?> col-md-12 <?php }?>">-->
										<!-- <div class="item-head">
											<h3><a href="<?php the_permalink() ?>" rel="<?php the_ID(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
											<!--  <div class="item-info">
												<?php if(ot_get_option('blog_show_meta_author',1)){ ?>
													<span class="item-author"><?php the_author_posts_link(); ?></span>
												<?php }
												if(ot_get_option('blog_show_meta_date',1)){ ?>
													<span class="item-date"><?php echo date_i18n(get_option('date_format') ,get_the_time('U')); ?></span>
												<?php }?>
												<div class="item-meta">
													<?php echo tm_html_video_meta(false,false,false,true) ?>
												</div>
											</div>-->
										<!-- </div>
										<!-- <div class="blog-excerpt">
											<?php the_excerpt(); ?>
										</div>-->
									<!--</div><!--/col6-->
								<!-- </div><!--/row-->
							<!-- </div>-->
						<!-- <div class="clearfix"></div>-->
					<!-- </div>-->
					<?php $count=$count+1; endwhile;//added count ?>  
					</div><!--/video-listing-content-->
					<div class="clearfix"></div>
					<?php if($count==1) {	echo '<div class="no-results">'.__('NO SEARCH RESULT','cactusthemes').'</div>';} /* added */
					?>
					<?php if($pagination=='page_navi' && function_exists( 'wp_pagenavi' )){
					wp_pagenavi();
					}else if($pagination=='page_def'){
					cactusthemes_content_nav('paging');
					}?>
					<?php
					else:
						echo '<div class="no-results">'.__('No results found','cactusthemes').'</div>';
					endif; wp_reset_query(); ?>
				</section>

			</div><!--#content-->
			<?php if($layout!='full'){ get_sidebar('search'); } ?>
		</div><!--/row-->
	</div><!--/container-->
</div><!--/body-->
<?php get_footer(); ?>
