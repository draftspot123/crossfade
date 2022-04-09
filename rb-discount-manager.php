<?php

/*

Plugin Name: 	Audio Imperia Discount Manager

Description: 	Manage all Discounts in your shop.

Version: 		1.2.0

Author: 		Lightspace

Author URI: 	

Text Domain: 	rb-discount-manager

License: 		GPLv2 or later

License URI:	http://www.gnu.org/licenses/gpl-2.0.html

*/



if ( ! defined( 'ABSPATH' ) ) {

	exit; // Exit if accessed directly

}



/**

 * Global variables

 */

$rbdm_plugin_version = '1.0.0';									// for use on admin pages

$plugin_file = plugin_basename(__FILE__);							// plugin file for reference

define( 'RBDM_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );	// define the absolute plugin path for includes

define( 'RBDM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );		// define the plugin url for use in enqueue

$rbdm_options = get_option('rbdm_settings');			// retrieve our plugin settings from the options table



/**

 * include

 */

require_once( RBDM_PLUGIN_PATH . 'admin/option_functions.php' );





/**

 * options

 */

function rbdm_admin_menu() {



	add_menu_page(

		

    esc_html__( 'Crossfade Options', 'rb-discount-manager' ),
    esc_html__( 'Crossfade Options', 'rb-discount-manager' ),
    'manage_woocommerce',
    'rbdm-options',
    'rbdm_settings_crossfade_page'

	);
  add_submenu_page(
    'rbdm-options',
    
    __('Discount Options', 'rb-discount-manager'),

		__('Discount Options', 'rb-discount-manager'),

		'manage_options',

		'rbdm-options',

		'rbdm_settings_page'
  );
}

add_action( 'admin_menu', 'rbdm_admin_menu' );



/**

 * db

 */

function rbdm_plugin_table_install() {

  global $wpdb;

  $charset_collate = $wpdb->get_charset_collate();

/* order table */
  $table_name = $wpdb->prefix . 'rbdm_crossfade_order';

   $sql = "CREATE TABLE  $table_name (

    id bigint(20) NOT NULL AUTO_INCREMENT,

    id_user bigint(20) NOT NULL,

    id_product bigint(20) NOT NULL,

    id_variation bigint(20) NOT NULL,

    id_order bigint(20) NOT NULL,

    quantity bigint(20) NOT NULL,

    amount bigint(20) NOT NULL,

    edit_at timestamp NOT NULL,

    PRIMARY KEY  (id)

  )$charset_collate;";

   require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

   dbDelta( $sql );

   /* rule table */

   $table_name = $wpdb->prefix . 'rbdm_crossfade_rule';

   $sql = "CREATE TABLE  $table_name (

    id bigint(20) NOT NULL AUTO_INCREMENT,

    id_product_source bigint(20) NOT NULL,

    id_product_target bigint(20) NOT NULL,

    quantity_source bigint(20) NOT NULL,

    quantity_target bigint(20) NOT NULL,

    discount_type varchar(200) NOT NULL,

    discount_amount bigint(20) NOT NULL,

    edit_at timestamp NOT NULL,

    PRIMARY KEY  (id)

  )$charset_collate;";

   require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

   dbDelta( $sql ); 

   add_option( 'rbdm_discount_orders', '' );

}

register_activation_hook(__FILE__,'rbdm_plugin_table_install');

/**

 * scripts and styles

 */

function rbdm_front_scripts(){}

add_action( 'wp_enqueue_scripts', 'rbdm_front_scripts' );

function rbdm_admin_scripts(){

    wp_register_script( 'rbdm_admin_js', RBDM_PLUGIN_URL . '/assets/js/rbdm_admin_js.js', array('jquery-core'), false, true );

    wp_enqueue_script( 'rbdm_admin_js' );

    wp_register_style( 'rbdm_admin_css', RBDM_PLUGIN_URL . '/assets/css/rbdm_admin_css.css', false, '1.0.0' );

    wp_enqueue_style( 'rbdm_admin_css' );

    wp_localize_script ('rbdm_admin_js', 'plugin_ajax_object',array ('ajax_url' => admin_url ('admin-ajax.php'))); 

}

add_action( 'admin_enqueue_scripts', 'rbdm_admin_scripts' );



/**

 * add bulk action in user admin

 */

add_filter('bulk_actions-users', function($bulk_actions) {

	$bulk_actions['add-discount'] = __('Add Discount', 'rb-discount-manager');

	return $bulk_actions;

});

add_filter('handle_bulk_actions-users', function ($redirect_url, $action, $post_ids){

    if ($action == 'add-discount') {

		$user_ids_str=implode('-',$post_ids);

		$redirect_url = add_query_arg('add-discount', $user_ids_str, $redirect_url);/**/

	}

	return $redirect_url;

}, 10, 3);



/*coupons label*/

add_filter ( 'woocommerce_cart_totals_coupon_label' , 'rbdm_change_coupon_label', 10 , 2 );

?>