<?php 
    /*
    Plugin Name: FB-Registration Form Land page
    Plugin URI: 
    Description: This creates a registration form
    Author: IB
    Version: 1.0
    Author URI: ib
    */

add_shortcode('fb_reg_form','fb_reg_form_fn');

function fb_reg_form_fn(){
?>
    <div id="top" style="position: relative; z-index: 0; background: none;">
        <div class="container">
            <div class="row">

                <div class="slider span8">
                    <?php echo do_shortcode('[sp_responsiveslider design="design-1" height="380" effect="fade" pagination="true" navigation="true"
 speed="3000" autoplay="true" autoplay_interval="3000"]');?>
                </div>
                <!-- End slider -->

                <div class="span4 text">
                    <h4>Qezyplay - Media Without Boundaries</h4>
                    <p>Watch Live Bengali, Bangla, Deccan South Channels Anywhere Anytime on Internet</p>
                    <form id="register-form" method="post">
			  <input type="text" name="name" placeholder="User Name">
                        <input type="text" name="email" placeholder="Email Address*">
                        <input type="text" name="name" placeholder="Password">
			<input type="text" name="name" placeholder="Re-type Password">
                        <input type="text" name="phone" placeholder="Phone Number">
                        <input type="submit" name="submit" value="Register Now!" class="btn">
                    </form>
                </div>
                <!-- End text -->

            </div>
            <!-- End row -->
        </div>
        <!-- End container -->
        <div class="backstretch" style="left: 0px; top: 0px; overflow: hidden; margin: 0px; padding: 0px; height: 468px; width: 1440px; z-index: -999998; position: absolute;"><img src="http://dotstheme.com/themeforest/templates/premi_business_landing_page/html/layout-1/images/base-top.jpg" style="position: absolute; margin: 0px; padding: 0px; border: none; width: 1440px; height: 720px; max-width: none; z-index: -999999; left: 0px; top: -126px;">
        </div>
    </div>
<?php
}
?>

<?php
