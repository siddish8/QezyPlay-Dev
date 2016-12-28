<?php
/**
 * Related Products
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop;

$related = $product->get_related( $posts_per_page );

if ( sizeof( $related ) == 0 ) return;

$args = apply_filters( 'woocommerce_related_products_args', array(
	'post_type'            => 'product',
	'ignore_sticky_posts'  => 1,
	'no_found_rows'        => 1,
	'posts_per_page'       => $posts_per_page,
	'orderby'              => $orderby,
	'post__in'             => $related,
	'post__not_in'         => array( $product->id )
) );

$products = new WP_Query( $args );

$woocommerce_loop['columns'] = $columns;
?>
<?php
if ( $products->have_posts() ) : ?>

	<div class="related products">

		<h3><?php _e( 'Related Products', 'woocommerce' ); ?></h3>
		<div class="re-content">
		<?php woocommerce_product_loop_start();
		$count_item = count($products);
		$i=0;
		 ?>
			<div class="row">
			<?php while ( $products->have_posts() ) : $products->the_post();
			$i++;
			 ?>
				<div class="col-sm-3 related-item">
					<?php wc_get_template_part( 'content', 'product' ); ?>
				</div>
                 <?php  
				  //if(($i%3==0) && ($count_item > $i)){?>
					  <!--</div><div class="row">-->
                  <?php // }?>
			<?php endwhile; // end of the loop. ?>
			</div>
		<?php woocommerce_product_loop_end(); ?>
		</div>
	</div>

<?php endif;

wp_reset_postdata();
