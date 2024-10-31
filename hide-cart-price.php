<?php 
/**
* Plugin Name: Remove Cart Button and Price
* Description: Learning Plugin Development.
* Version: 1.0
* Author: Harshit Chaudhary
* License: GPL2
*/
add_action('admin_menu', 'HKSV_remove_cart');
if (!function_exists('HKSV_remove_cart')) {
function HKSV_remove_cart(){
	add_menu_page( 'Remove Cart Page', 'Remove Cart', 'manage_options', 'remove_cart', 'HKSV_RC_html', 'dashicons-cart', 100 );	
}
}
add_action('admin_init', 'HKSV_RC_settings');
if (!function_exists('HKSV_RC_settings')) {
function HKSV_RC_settings(){
	add_settings_section( 'rc_first_section', null, null, 'remove-cart' );

	add_settings_field('rc_checkbox','Hide Add to Cart Button', 'HKSV_rc_checkbox_html','remove-cart','rc_first_section');
	register_setting( 'rc_option_group', 'rc_checkbox', array(
		'sanitize_callback' => 'sanitize_text_field',
		'default' => 0
	));
	add_settings_field('rp_checkbox','Hide Price', 'HKSV_rp_checkbox_html','remove-cart','rc_first_section');
	register_setting( 'rc_option_group', 'rp_checkbox', array(
		'sanitize_callback' => 'sanitize_text_field',
		'default' => 0
	));
}
}
if (!function_exists('HKSV_rc_checkbox_html')) {
function HKSV_rc_checkbox_html(){ ?>
	<input type="checkbox" class="custom_chk" name="rc_checkbox" value="1" <?php checked( get_option('rc_checkbox'), '1' ) ?>>
<?php }
}
if (!function_exists('HKSV_rp_checkbox_html')) {
function HKSV_rp_checkbox_html(){ ?>
	<input type="checkbox" class="custom_chk" name="rp_checkbox" value="1" <?php checked( get_option('rp_checkbox'), '1' ) ?>>
<?php }
}

add_action('init','HKSV_remove_add_to_cart_button');
if (!function_exists('HKSV_remove_add_to_cart_button')) {
function HKSV_remove_add_to_cart_button(){
	$chk_value_rc_checkbox = get_option('rc_checkbox');
	if ( $chk_value_rc_checkbox == 1 ) {
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
		return RC_styles();
	}
}
}

add_action('init','HKSV_remove_price');
if (!function_exists('HKSV_remove_price')) {
function HKSV_remove_price(){
	$chk_value_rp_checkbox = get_option('rp_checkbox');
	if ( $chk_value_rp_checkbox == 1 ) {
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
	}
}
}

add_action( 'admin_print_styles', 'HKSV_RC_styles' );
if (!function_exists('HKSV_RC_styles')) {
function HKSV_RC_styles(){
	wp_enqueue_style('remove_cart_page_css', plugin_dir_url( __FILE__ ) . 'style.css');
}
}

if (!function_exists('HKSV_RC_html')) {
function HKSV_RC_html(){ ?>
	<div class="wrap">
		<h1>Remove Cart and Price</h1>
		<form action="options.php" method="POST">
			<?php 
				//display wordpress defualt messages on top
				settings_errors();
				settings_fields( 'rc_option_group' );
				do_settings_sections( 'remove-cart' );
				submit_button();
			?>
		</form>
	</div>
<?php }
}

