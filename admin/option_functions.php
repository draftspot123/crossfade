<?php

/**

 * Admin init

 */

if ( ! defined( 'ABSPATH' ) ) {

	exit; // Exit if accessed directly

}



/**

 * Add options to wp-admin

 */



function rbdm_settings_page(){

	?>

    

	<section class="rb_list_discounts_wrapper">

		<h2><?php _e('Discount Manager', 'rb-discount-manager');?></h3>

		<button class="rbdm_add_discount_btn "><?php _e('Add new Discount', 'rb-discount-manager');?></button>

		<div class="rbdm_add_discount_wrapper ">

			<div class="rbdm_add_discount_row rbdm_add_discount_row_users rbdm_hide">

				<h5><?php _e('Select Users', 'rb-discount-manager');?></h5>

				<div class="rbdm_add_discount_row_users_search_wrapper">

					<label><?php _e('All Users', 'rb-discount-manager');?>

						<input type="checkbox" class="rbdm_add_discount_users" name="rbdm_add_discount_users_all" value="all">

					</label>

					<div class="rbdm_add_discount_row_users_search_fields">

						<label><?php _e('Browse Users', 'rb-discount-manager');?>

							<input type="text" class="rbdm_add_discount_users_search"  value="">

						</label>

						<div class="rbdm_add_discount_users_search_result"></div>

					</div>

				</div>

				<input type="hidden" class="rbdm_add_discount_users_search_input"  value="">

			</div>

			<div class="rbdm_add_discount_form_wrapper"></div>

		</div>

	</section>

	

	

	<section class="rb_list_discounts_wrapper">

		<h3><?php _e('List of Discounts', 'rb-discount-manager');?></h3>

		<header class="rb_list_discounts_header rbdm_hide">

			<div class="rbdm_hide"><?php _e('Filters', 'rb-discount-manager');?></div>

			<label for="">

				by user

			</label>

			<label for="">

				by date

			</label>

			<label for="">

				by product target

			</label>

			<label for="">

				by product source

			</label>

		</header>

		<div class="rb_list_discounts_content">

			<div class="rb_list_discounts_content_table_head">

				<div class="rb_list_discounts_content_table_id"><?php _e('ID', 'rb-discount-manager');?></div>

				<div class="rb_list_discounts_content_table_user"><?php _e('User', 'rb-discount-manager');?></div>

				<div class="rb_list_discounts_content_table_typ"><?php _e('Type', 'rb-discount-manager');?></div>

				<div class="rb_list_discounts_content_table_value"><?php _e('Value', 'rb-discount-manager');?></div>

				<div class="rb_list_discounts_content_table_source"><?php _e('Conditional Type', 'rb-discount-manager');?></div>

				<div class="rb_list_discounts_content_table_target"><?php _e('Value', 'rb-discount-manager');?></div>

				<div class="rb_list_discounts_content_table_date"><?php _e('From...To', 'rb-discount-manager');?></div>

				<div class="rb_list_discounts_content_table_action"><?php _e('Action', 'rb-discount-manager');?></div>

			</div>

			<div class="rb_list_discounts_content_table_body">

			<?php

			$rbdm_now=strtotime(date("Y-m-d H:i:s"));

			$rbmd_args=array(

				'post_type'        => 'shop_coupon',

				'post_status'      => 'publish',

				'numberposts'=>1000,

				'meta_query' => array(

					'relation' => 'AND',

					/*array(

						'key'     => 'date_expires',

						'value'   => $rbdm_now,

						'compare' => '>',

					),

					array(

						'key'     => 'RBDM_start',

						'value'   => $rbdm_now,

						'compare' => '>',

					),*/

					array(

						'key'     => 'RBDM',

						'value'   => '1',

						'compare' => '=',

					),

				),

			);

			$rbmd_coupons=get_posts($rbmd_args);

			foreach($rbmd_coupons as $item){

				if(get_post_meta($item->ID,'date_expires',true)<$rbdm_now){

					$ds_expired_class='ds_expired';

				}else{

					$ds_expired_class='';

				}

			?>

				<div class="rb_list_discounts_content_table_row <?php echo $ds_expired_class;?>">

					<div class="rb_list_discounts_content_table_id"><?php echo $item->ID;?></div>

					<div class="rb_list_discounts_content_table_user"><?php echo get_post_meta($item->ID,'RBDM_users',true);?></div>

					<div class="rb_list_discounts_content_table_typ"><?php echo get_post_meta($item->ID,'discount_type',true);?></div>

					<div class="rb_list_discounts_content_table_value"><?php echo get_post_meta($item->ID,'coupon_amount',true);?></div>

					<div class="rb_list_discounts_content_table_source"><?php echo get_post_meta($item->ID,'RBDM_cond_type',true);?></div>

					<div class="rb_list_discounts_content_table_target"><?php echo get_post_meta($item->ID,'RBDM_cond_type_value',true);?></div>

					<div class="rb_list_discounts_content_table_date"><?php echo date('m/d/Y',get_post_meta($item->ID,'RBDM_start',true)).' ... '.date('m/d/Y',get_post_meta($item->ID,'date_expires',true));?></div>

					<div class="rb_list_discounts_content_table_action">

						<span class="rb_list_discounts_content_table_action_delete" data-id="<?php echo $item->ID;?>"><?php _e('Delete', 'rb-discount-manager');?></span>

						<span class="rb_list_discounts_content_table_action_update" data-id="<?php echo $item->ID;?>"><?php _e('Update', 'rb-discount-manager');?></span>

					</div>

				</div>

			<?php	

			}

			?>

			</div>	

		</div>

		<div class="rbdm_update_discount_modal_wrapper rbdm_hide">

			

		</div>

	</section>

	<?php

}





function rbdm_discount_delete_function(){

	$rbdm_discount_id=$_REQUEST['rbdm_coupon_id'];

	wp_delete_post( $rbdm_discount_id, true);

	echo '1';

	wp_die();

}

add_action ('wp_ajax_rbdm_discount_delete', 'rbdm_discount_delete_function');

add_action ('wp_ajax_nopriv_rbdm_discount_delete', 'rbdm_discount_delete_function');





function rbdm_add_discount_product_search_function() {

	$search_str=$_REQUEST['input_content'];

	$rbdm_args=array(

		'post_type'=>'product',

		'post_status'=>'publish',

		'numberposts'=>1000,

		's'=>$search_str,

	);

	$rbdm_products=get_posts($rbdm_args);

	ob_start();



	echo'<select class="rbdm_add_discount_products_search_result_list" multiple>';

	foreach($rbdm_products as $item){

		echo '<option class="rbdm_add_discount_products_search_result_item" value="'.$item->ID.'" data-name="'.$item->post_title.'">'.$item->post_title.'</option>';

	}

	echo'</select>';

	echo ob_get_clean();

	wp_die();

}

add_action ('wp_ajax_rbdm_add_discount_product_search', 'rbdm_add_discount_product_search_function');

add_action ('wp_ajax_nopriv_rbdm_add_discount_product_search', 'rbdm_add_discount_product_search_function');



function rbdm_add_discount_user_search_function() {

	$search_str=$_REQUEST['input_content'];

	$rbdm_args=array(

		

		'search'=>'*'.$search_str.'*',

	);

	$rbdm_users=get_users($rbdm_args);

	ob_start();



	echo'<select class="rbdm_add_discount_users_search_result_list" multiple>';

	foreach($rbdm_users as $item){

		echo '<option class="rbdm_add_discount_users_search_result_item" value="'.$item->ID.'" data-name="'.$item->display_name.'">'.$item->display_name.'</option>';

	}

	echo'</select>';

	echo ob_get_clean();

	wp_die();

}

add_action ('wp_ajax_rbdm_add_discount_user_search', 'rbdm_add_discount_user_search_function');

add_action ('wp_ajax_nopriv_rbdm_add_discount_user_search', 'rbdm_add_discount_user_search_function');



/*

* save to db and generate coupon

*/

function rbdm_add_discount_save_function() {

	$discount_JSON= stripslashes($_REQUEST['discount_JSON']) ;

	/*$discount_JSON = stripslashes(preg_replace('/\\\"/',"", $discount_JSON));*/

	$discount_obj=json_decode($discount_JSON, JSON_UNESCAPED_SLASHES);

	/*json_encode('yourjsonvariable'), true, JSON_UNESCAPED_SLASHES);*/

	ob_start();

	echo rbdm_generate_coupon($discount_obj);/**/

	echo ob_get_clean();

	wp_die();

}

add_action ('wp_ajax_rbdm_add_discount_save', 'rbdm_add_discount_save_function');

add_action ('wp_ajax_nopriv_rbdm_add_discount_save', 'rbdm_add_discount_save_function');



function rbdm_update_discount_save_function() {

	$discount_JSON= stripslashes($_REQUEST['discount_JSON']) ;

	/*$discount_JSON = stripslashes(preg_replace('/\\\"/',"", $discount_JSON));*/

	$discount_obj=json_decode($discount_JSON, JSON_UNESCAPED_SLASHES);

	/*json_encode('yourjsonvariable'), true, JSON_UNESCAPED_SLASHES);*/

	ob_start();

	echo rbdm_update_coupon($discount_obj);/**/

	echo ob_get_clean();

	wp_die();

}

add_action ('wp_ajax_rbdm_update_discount_save', 'rbdm_update_discount_save_function');

add_action ('wp_ajax_nopriv_rbdm_update_discount_save', 'rbdm_update_discount_save_function');



/*

* save to db 

*/

function rbdm_add_discount_save_db($discount_obj){

	global $wpdb;

	$wpdb->insert('table_name', array(

		'id_user' => 'value 1',

		'typ_discount' => 'value 2',

		'value_discount' => 'value 3',

		'source_discount' =>'value 3',

		'target_discount' =>'value 3',

		'start_discount' =>'value 3',

		'end_discount' =>'value 3',

	));

}



/*

*  generate coupon

*/

function rbdm_generate_coupon($discount_obj) {

	$rbdm_coupon_code='RBDM'.strtotime(date("Y-m-d H:i:s"));

    $rbdm_coupon = new WC_Coupon();

    $rbdm_coupon->set_code($rbdm_coupon_code);

    //the coupon discount type can be 'fixed_cart', 'percent' or 'fixed_product', defaults to 'fixed_cart'

    $rbdm_coupon->set_discount_type($discount_obj['type']);

    //the discount amount, defaults to zero

    $rbdm_coupon->set_amount($discount_obj['type_value']);

    //the coupon's expiration date defaults to null

	if($discount_obj['time_end']!=0){

		$rbdm_expire=strtotime($discount_obj['time_end']);

    	$rbdm_coupon->set_date_expires($rbdm_expire);

	}else{

		$rbdm_expire=strtotime('2030-12-30');

    	$rbdm_coupon->set_date_expires($rbdm_expire);

	}

    //determines if the coupon can only be used by an individual, defaults to false   ********todo

	if($discount_obj['individual']){

    	$rbdm_coupon->set_individual_use(true);

	}else{

		$rbdm_coupon->set_individual_use(false);

	}	

    //the individual prodcuts that the disciunt will apply to, default to an empty array

	if($discount_obj['products']!='all'){

		$rbdm_products=explode(',',$discount_obj['products']);

    	$rbdm_coupon->set_product_ids($rbdm_products);

	}

    //the individual products that are excluded from the discount, default to an empty array   ********todo

    $rbdm_coupon->set_excluded_product_ids(array());

    //the times the coupon can be used, defaults to zero

    $rbdm_coupon->set_usage_limit(0);

    //the times the coupon can be used per user, defaults to zero  ********todo

	if($discount_obj['individual']){

		$rbdm_coupon->set_usage_limit_per_user($discount_obj['individual']);

	}else{

		$rbdm_coupon->set_usage_limit_per_user(0);

	}

   

    //whether the coupon awards free shipping, defaults to false  ********todo

	if($discount_obj['free_shipping']){

		$rbdm_coupon->set_free_shipping(true);

	}else{

		$rbdm_coupon->set_free_shipping(false);

	}

   

    //the product categories included in the promotion, defaults to an empty array  ********todo

    $rbdm_coupon->set_product_categories(array());

    //the product categories excluded from the promotion, defaults to an empty array  ********todo

    $rbdm_coupon->set_excluded_product_categories(array());

    //whether sale items are excluded from the coupon, defaults to false

    $rbdm_coupon->set_exclude_sale_items(false);

    //the minimum amount of spend required to make the coupon active, defaults to an empty string  ********todo

    $rbdm_coupon->set_minimum_amount('');

    //the maximum amount of spend required to make the coupon active, defaults to an empty string   ********todo

    $rbdm_coupon->set_maximum_amount('');

    //a list of email addresses, the coupon will only be applied if the customer is linked to one of the listed emails, defaults to an empty array   ********todo

    $rbdm_coupon->set_email_restrictions(array());

    //save the coupon

    $rbdm_coupon_id=$rbdm_coupon->save();

	update_post_meta($rbdm_coupon_id,'RBDM',1);

	update_post_meta($rbdm_coupon_id,'RBDM_users',$discount_obj['users']);

	update_post_meta($rbdm_coupon_id,'RBDM_cond_type',$discount_obj['cond_type']);

	update_post_meta($rbdm_coupon_id,'RBDM_cond_type_value',$discount_obj['cond_type_value']);

	update_post_meta($rbdm_coupon_id,'RBDM_products_origin',$discount_obj['products_origin']);

	update_post_meta($rbdm_coupon_id,'RBDM_start',strtotime($discount_obj['time_start']));/**/

    return $rbdm_coupon_id;

}



/*update coupon*/

function rbdm_update_coupon($discount_obj){

	global $woocommerce; 

	$rbdm_coupon = new WC_Coupon($discount_obj['id']);

	$rbdm_coupon->set_discount_type($discount_obj['type']);

    //the discount amount, defaults to zero

    $rbdm_coupon->set_amount($discount_obj['type_value']);

    //the coupon's expiration date defaults to null

	if($discount_obj['time_end']!=0){

		$rbdm_expire=strtotime($discount_obj['time_end']);

    	$rbdm_coupon->set_date_expires($rbdm_expire);

	}else{

		$rbdm_expire=strtotime('2030-12-30');

    	$rbdm_coupon->set_date_expires($rbdm_expire);

	}

    //determines if the coupon can only be used by an individual, defaults to false   ********todo

	if($discount_obj['individual']){

    	$rbdm_coupon->set_individual_use(true);

	}else{

		$rbdm_coupon->set_individual_use(false);

	}	

    //the individual prodcuts that the disciunt will apply to, default to an empty array

	if($discount_obj['products']!='all'){

		$rbdm_products=explode(',',$discount_obj['products']);

    	$rbdm_coupon->set_product_ids($rbdm_products);

	}

    //the individual products that are excluded from the discount, default to an empty array   ********todo

    $rbdm_coupon->set_excluded_product_ids(array());

    //the times the coupon can be used, defaults to zero

    $rbdm_coupon->set_usage_limit(0);

    //the times the coupon can be used per user, defaults to zero  ********todo

	if($discount_obj['individual']){

		$rbdm_coupon->set_usage_limit_per_user($discount_obj['individual']);

	}else{

		$rbdm_coupon->set_usage_limit_per_user(0);

	}

   

    //whether the coupon awards free shipping, defaults to false  ********todo

	if($discount_obj['free_shipping']){

		$rbdm_coupon->set_free_shipping(true);

	}else{

		$rbdm_coupon->set_free_shipping(false);

	}

   

    //the product categories included in the promotion, defaults to an empty array  ********todo

    $rbdm_coupon->set_product_categories(array());

    //the product categories excluded from the promotion, defaults to an empty array  ********todo

    $rbdm_coupon->set_excluded_product_categories(array());

    //whether sale items are excluded from the coupon, defaults to false

    $rbdm_coupon->set_exclude_sale_items(false);

    //the minimum amount of spend required to make the coupon active, defaults to an empty string  ********todo

    $rbdm_coupon->set_minimum_amount('');

    //the maximum amount of spend required to make the coupon active, defaults to an empty string   ********todo

    $rbdm_coupon->set_maximum_amount('');

    //a list of email addresses, the coupon will only be applied if the customer is linked to one of the listed emails, defaults to an empty array   ********todo

    $rbdm_coupon->set_email_restrictions(array());

    //save the coupon

    $rbdm_coupon_id=$rbdm_coupon->save();

	update_post_meta($rbdm_coupon_id,'RBDM',1);

	update_post_meta($rbdm_coupon_id,'RBDM_users',$discount_obj['users']);

	update_post_meta($rbdm_coupon_id,'RBDM_cond_type',$discount_obj['cond_type']);

	update_post_meta($rbdm_coupon_id,'RBDM_cond_type_value',$discount_obj['cond_type_value']);

	update_post_meta($rbdm_coupon_id,'RBDM_products_origin',$discount_obj['products_origin']);

	update_post_meta($rbdm_coupon_id,'RBDM_start',strtotime($discount_obj['time_start']));/**/

    return $rbdm_coupon_id;

}



function rbdm_discount_update_form_function(){

	$rbdm_modal_type=$_REQUEST['rbdm_modal_type'];

	if(isset($_REQUEST['rbdm_coupon_id'])){

		$rbdm_discount_id=$_REQUEST['rbdm_coupon_id'];

	}

	if(isset($_REQUEST['rbdm_selected_users'])){

		$selected_users=$_REQUEST['rbdm_selected_users'];

	}

	$rbdm_d=get_post($rbdm_discount_id);

	if($rbdm_modal_type=='add'){

		$selected_users='';

	}

		$rbdm_discount_array=array(

			'ID'=>'',

			'title'=>'Add new discount',

			'type'=>'percent',

			'type_value'=>'',

			'individual_use'=>'no',

			'products'=>'all',

			'usage_limit'=>'',

			'usage_limit_per_user'=>'',

			'free_shipping'=>'',

			'users'=>$selected_users,

			'cond_type'=>'quantity',

			'cond_type_value'=>'',

			'start'=>strtotime(date('Y-m-d')),

			'expired'=>'',

			'products_origin'=>'',

		);

		

	if($rbdm_modal_type=='update'){

		$rbdm_discount_array=array(

			'ID'=>$rbdm_d->ID,

			'title'=>$rbdm_d->post_title,

			'type'=>get_post_meta($rbdm_d->ID,'discount_type',true),

			'type_value'=>get_post_meta($rbdm_d->ID,'coupon_amount',true),

			'individual_use'=>get_post_meta($rbdm_d->ID,'individual_use',true),

			'products'=>get_post_meta($rbdm_d->ID,'product_ids',true),

			'usage_limit'=>get_post_meta($rbdm_d->ID,'usage_limit',true),

			'usage_limit_per_user'=>get_post_meta($rbdm_d->ID,'usage_limit_per_user',true),

			'free_shipping'=>get_post_meta($rbdm_d->ID,'free_shipping',true),

			'users'=>get_post_meta($rbdm_d->ID,'RBDM_users',true),

			'cond_type'=>get_post_meta($rbdm_d->ID,'RBDM_cond_type',true),

			'cond_type_value'=>get_post_meta($rbdm_d->ID,'RBDM_cond_type_value',true),

			'start'=>get_post_meta($rbdm_d->ID,'RBDM_start',true),

			'expired'=>get_post_meta($rbdm_d->ID,'date_expires',true),

			'products_origin'=>get_post_meta($rbdm_d->ID,'RBDM_products_origin',true),

		);

	}

	$ds_obj_json=json_encode($rbdm_discount_array);



	ob_start();

	?>

	<div class="rbdm_update_discount_modal_delete">x</div>

	<h2 class="rbdm_update_discount_modal_title"><?php echo $rbdm_discount_array['title'];?></h2>

	<input type="hidden" class="ds_obj_json" value="<?php echo $ds_obj_json;?>">

	<div class="rbdm_update_discount_modal_user">

		<h5 class="rbdm_update_discount_modal_user_left_label"><?php _e('Set discounts for:', 'rb-discount-manager');?></h5>

		<div class="rbdm_update_discount_modal_user_left rbdm_my10">

			



			<?php

			if($rbdm_discount_array['users']=='all' || !$rbdm_discount_array['users']){

				echo 'All Users';

			}else{

				$rbdm_users_array=explode(',',$rbdm_discount_array['users']);

				$rbdm_users=get_users();

				foreach($rbdm_users_array as $item){

					$rbdm_user=get_user_by('id', $item);

				?>

				<div class="rbdm_update_discount_user" data-id="<?php echo $rbdm_user->ID;?>"><?php echo $rbdm_user->display_name;?><span class="rbdm_update_discount_remove" data-id="<?php echo $rbdm_user->ID;?>">x</span></div>

				<?php

				}

			}

			

			?>

		</div>

		<div class="rbdm_update_discount_modal_user_right rbdm_my10">

			<span class="rbdm_update_discount_modal_user_right_label"><?php _e('Add Users:', 'rb-discount-manager');?></span>

			<input type="text" class="rbdm_update_discount_add_user" value="">

			<div class="rbdm_user_search_result"></div>



		</div>	

		<div class="rbdm_update_discount_modal_user_count rbdm_my10 ">

			<label><?php _e('Number of uses', 'rb-discount-manager');?>

				<input type="text" class="rbdm_update_discount_limit" name="rbdm_add_discount_limit" value="" placeholder="<?php _e('Unlimited usage', 'rb-discount-manager');?>" <?php if(!$rbdm_discount_array['usage_limit']){echo 'checked';}?>>

			</label>

		</div>

		<input type="hidden" class="rbdm_update_discount_users" value="<?php echo $rbdm_discount_array['users'];?>">

	</div>

	<div class="rbdm_update_discount_modal_type rbdm_my10">

		<h5><?php _e('Type of discounts', 'rb-discount-manager');?></h5>

		<div class="rbdm_update_discount_type_wrapper d-flex">

			<label><?php _e('Percentage', 'rb-discount-manager');?>

				<input type="radio" class="rbdm_update_discount_type" name="rbdm_update_discount_type" value="percent" <?php if($rbdm_discount_array['type']=='percent'){echo 'checked';}?>>

			</label>

			<label><?php _e('Fixed amount', 'rb-discount-manager');?>

				<input type="radio" class="rbdm_update_discount_type" name="rbdm_update_discount_type" value="fixed_cart" <?php if($rbdm_discount_array['type']!='percent'){echo 'checked';}?>>

			</label>

			<!--<label><?php _e('Buy X get Y', 'rb-discount-manager');?>

				<input type="radio" class="rbdm_add_discount_type" name="rbdm_add_discount_type" value="percentage">

			</label>-->

			<label class="rbdm_update_discount_type_value_label"><?php _e('Value', 'rb-discount-manager');?>

				<input type="text" class="rbdm_update_discount_type_value"  value="<?php echo $rbdm_discount_array['type_value'];?>">

			</label>

			<label><?php _e('Allow free shipping', 'rb-discount-manager');?>

				<input type="checkbox" class="rbdm_update_discount_shipping" name="rbdm_update_discount_shipping" value="free" <?php if($rbdm_discount_array['free_shipping']!='no'){echo 'checked';}?>>

			</label>

		</div>

	</div>

	<div class="rbdm_update_discount_modal_products rbdm_my10">

		<h5><?php _e('For products', 'rb-discount-manager');?></h5>

		<div class="rbdm_update_discount_modal_products_left rbdm_my10">

			

			<?php

				if($rbdm_discount_array['products']=='all' || !$rbdm_discount_array['products']){

					echo 'All Products';

				}else{

					$rbdm_products_array=explode(',',$rbdm_discount_array['products']);

					foreach($rbdm_products_array as $item){

						$rbdm_product=wc_get_product($item);

						?>

						<div class="rbdm_update_discount_product"><?php echo $rbdm_product->name;?><span class="rbdm_update_discount_remove" data-id="<?php echo $rbdm_product->get_id();?>">x</span></div>

						<?php

					}

				}

			?>

			</div>

			<div class="rbdm_update_discount_modal_products_right rbdm_my10">

			<div class="rbdm_update_discount_row_products_modal_search_wrapper">

				<label><?php _e('All Products', 'rb-discount-manager');?>

					<input type="checkbox" class="rbdm_update_discount_products" name="rbdm_update_discount_products_all" value="all" <?php if($rbdm_discount_array['products']=='all' || !$rbdm_discount_array['products']){echo 'checked';}?>>

				</label>

				<div class="rbdm_update_discount_row_products_modal_search_fields d-flex">

					<label><?php _e('Browse Product', 'rb-discount-manager');?>

						<input type="text" class="rbdm_add_discount_products_search"  value="">

					</label>

					<div class="rbdm_update_discount_products_search_result"></div>

				</div>

			</div>

		</div>

		<input type="hidden" class="rbdm_update_discount_products_search_input"  value="<?php echo $rbdm_discount_array['products'];?>">

	</div>

	<div class="rbdm_update_discount_modal_condition rbdm_my10">

		<h5><?php _e('Conditions', 'rb-discount-manager');?></h5>

		<div class="rbdm_update_discount_modal_condition_wrapper d-flex rbdm_my10">

			<label><?php _e('Quantity in cart', 'rb-discount-manager');?>

				<input type="radio" class="rbdm_update_discount_cond rbdm_update_discount_cond_quantity" name="rbdm_update_discount_cond_type" value="quantity" <?php if($rbdm_discount_array['cond_type']=='quantity'){echo 'checked';}?>>

			</label>

			<label><?php _e('Cart Price', 'rb-discount-manager');?>

				<input type="radio" class="rbdm_update_discount_cond rbdm_update_discount_cond_amount" name="rbdm_update_discount_cond_type" value="amount" <?php if($rbdm_discount_array['cond_type']!='quantity'){echo 'checked';}?>>

			</label>

			<input type="text" class="rbdm_update_discount_cond_value"  value="<?php echo $rbdm_discount_array['cond_type_value'];?>" placeholder="<?php _e('Value', 'rb-discount-manager');?>">

		</div>

		<div class="rbdm_update_discount_modal_condition_wrapper d-flex rbdm_my10">

			<div class="rbdm_update_discount_row_products_modal_search_fields d-flex">

				<label><?php _e('Browse Origin Product', 'rb-discount-manager');?>

						<input type="text" class="rbdm_add_discount_products_s_search"  value="">

				</label>

				<div class="rbdm_update_discount_products_s_search_result"></div>

				<div class="rbdm_update_discount_products_s_search_list"></div>

			</div>

		</div>

		<input type="hidden" class="rbdm_update_discount_products_s_search_input"  value="<?php echo $rbdm_discount_array['products_origin'];?>">

	</div>

	<div class="rbdm_update_discount_modal_expiration rbdm_my10">

		<h5><?php _e('Time Limit', 'rb-discount-manager');?></h5>

		<label><?php _e('From', 'rb-discount-manager');?>

			<input type="date" class="rbdm_update_discount_date_from"  value="<?php echo date('Y-m-d',$rbdm_discount_array['start']); ?>">

		</label>

		<label><?php _e('To', 'rb-discount-manager');?>

			<input type="date" class="rbdm_update_discount_date_to"  value="<?php echo date('Y-m-d',$rbdm_discount_array['expired']); ?>">

		</label>

		<label><?php _e('From now without restrictions', 'rb-discount-manager');?>

			<input type="checkbox" class="rbdm_update_discount_date_nolimit" name="rbdm_update_discount_date_nolimit" value="nolimit">

		</label>

	</div>

	<div class="rbdm_update_discount_modal_button rbdm_my10">

		<label><?php _e('Individual use only', 'rb-discount-manager');?>

			<input type="checkbox" class="rbdm_update_discount_indiv_only" name="rbdm_update_discount_indiv_only" <?php if($rbdm_discount_array['individual_use']!='no'){echo 'checked';}?>>

		</label>

		<?php

		if($rbdm_modal_type=='update'){

			?>

			<button class="btn rbdm_update_discount_submit" data-id="<?php echo $rbdm_discount_array['ID'];?>"><?php _e('Update Discount', 'rb-discount-manager');?></button>

			<?php

		}else{

			?>

			<button class="btn rbdm_add_discount_submit" ><?php _e('Add Discount', 'rb-discount-manager');?></button>

			<?php

		}	

		?>

	</div>

	<?php

	echo ob_get_clean();

	wp_die();

}

add_action ('wp_ajax_rbdm_discount_update_form', 'rbdm_discount_update_form_function');

add_action ('wp_ajax_nopriv_rbdm_discount_update_form', 'rbdm_discount_update_form_function');



function rbdm_get_selected_users_function(){

	$selected_ids=$_REQUEST['selected_ids'];

	$rbdm_users_array=explode(',',$selected_ids);

	ob_start();

	?>

	<span class="rbdm_update_discount_modal_user_left_label"><?php _e('Set discounts for:', 'rb-discount-manager');?></span>

	<?php

	foreach($rbdm_users_array as $item){

		$rbdm_user=get_user_by('id', $item);

	?>

	<div class="rbdm_update_discount_user" data-id="<?php echo $rbdm_user->ID;?>"><?php echo $rbdm_user->display_name;?><span class="rbdm_update_discount_remove" data-id="<?php echo $rbdm_user->ID;?>">x</span></div>

	<?php

	}	

	echo ob_get_clean();

	wp_die();

}

add_action ('wp_ajax_rbdm_get_selected_users', 'rbdm_get_selected_users_function');

add_action ('wp_ajax_nopriv_rbdm_get_selected_users', 'rbdm_get_selected_users_function');



function rbdm_get_selected_products_function(){

	$selected_ids=$_REQUEST['selected_ids'];

	$rbdm_products_array=explode(',',$selected_ids);

	ob_start();

	

	foreach($rbdm_products_array as $item){

		$rbdm_product=get_post($item);

	?>

	<div class="rbdm_update_discount_product" data-id="<?php echo $rbdm_product->ID;?>"><?php echo $rbdm_product->post_title;?><span class="rbdm_update_discount_remove" data-id="<?php echo $rbdm_product->ID;?>">x</span></div>

	<?php

	}	

	echo ob_get_clean();

	wp_die();

}

add_action ('wp_ajax_rbdm_get_selected_products', 'rbdm_get_selected_products_function');

add_action ('wp_ajax_nopriv_rbdm_get_selected_products', 'rbdm_get_selected_products_function');

/*

* coupon aplly

*/

function rbdm_apply_RBDM_coupons(){

	// load all valid coupons

	if ( !WC()->cart->is_empty() && is_user_logged_in()){

		$rbdm_now=strtotime(date("Y-m-d H:i:s"));

		$rbmd_args=array(

			'post_type'        => 'shop_coupon',

			'post_status'      => 'publish',

			'numberposts'=>1000,

			'meta_query' => array(

				'relation' => 'AND',

				/*array(

					'key'     => 'date_expires',

					'value'   => $rbdm_now,

					'compare' => '>',

				),

				array(

					'key'     => 'RBDM_start',

					'value'   => $rbdm_now,

					'compare' => '>',

				),*/

				array(

					'key'     => 'RBDM',

					'value'   => '1',

					'compare' => '=',

				),

			),

		);

		$rbmd_coupons=get_posts($rbmd_args);

		echo count($rbmd_coupons);

		foreach($rbmd_coupons as $item){

			//check user

			$rbdm_check_user=0;

			$rbdm_check_date=0;

			$rbdm_check_cond=0;

			$rbdm_check_orig=0;

			$rbdm_users=explode(',',get_post_meta($item->ID,'RBDM_users',true));

			$rbdm_current_user=get_current_user_id();

			if(!get_post_meta($item->ID,'RBDM_users',true)){

				$rbdm_check_user=1;

			}

			if (in_array($rbdm_current_user, $rbdm_users)) {

				$rbdm_check_user=1;

			}

			//check date

			$rbdm_now=strtotime(date('j.nY H:i:s'));

			$rbdm_coupon_start=get_post_meta($item->ID,'RBDM_start',true);

			$rbdm_coupon_expire=get_post_meta($item->ID,'date_expires',true);

			if($rbdm_coupon_start>$rbdm_now && $rbdm_coupon_expire>$rbdm_now){

				$rbdm_check_date=1;

			}

			// origin product

			echo get_post_meta($item->ID,'RBDM_products_origin',true);

			if(get_post_meta($item->ID,'RBDM_products_origin',true)){

				$rbdm_products_origin=explode(',',get_post_meta($item->ID,'RBDM_products_origin',true));

				foreach ($rbdm_products_origin as $item_product){

					$rbdm_product=wc_get_product($item_product);

					foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ){

						if($item_product == $cart_item['product_id']){

							$rbdm_check_orig=1;

						}

					}

				}

			}	

			// product in cart

			if(!get_post_meta($item->ID,'product_ids',true) || get_post_meta($item->ID,'product_ids',true)=='all'){

				$rbdm_args=array(

					'post_type'=>'product',

					'post_status'=>'publish',

					'numberposts'=>'2000',

					'fields'=>'ids',

				);

				$rbdm_products=get_posts($rbdm_args);

			}else{

				$rbdm_products=explode(',',get_post_meta($item->ID,'product_ids',true));

			}

			

			$rbdm_cond_type=get_post_meta($item->ID,'RBDM_cond_type',true);

			$rbdm_cond_type_value=get_post_meta($item->ID,'RBDM_cond_type_value',true);

			foreach ($rbdm_products as $item_product){

				$rbdm_product=wc_get_product($item_product);

				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ){

					if($rbdm_cond_type=='quantity'){

						if($item_product == $cart_item['product_id'] && $cart_item['quantity']>=$rbdm_cond_type_value){

							$rbdm_check_cond=1;

						}

					}

					if($rbdm_cond_type=='amount'){

						$ds_subtotal = WC()->cart->get_product_subtotal( $rbdm_product, $cart_item['quantity'] );

						if($item_product == $cart_item['product_id'] && $ds_subtotal>=$rbdm_cond_type_value){

							$rbdm_check_cond=1;

						}

					}

				}

			}

			

			echo $rbdm_check_user.'-'.$rbdm_check_cond.'-'.$rbdm_check_date.'-'.$rbdm_check_orig;

			//check subtotal cart

			if($rbdm_check_user &&  $rbdm_check_cond && $rbdm_check_date && $rbdm_check_orig){

				if ( WC()->cart->has_discount( $item->post_title ) ) return;

				WC()->cart->apply_coupon( $item->post_title );

				/*WC()->cart->remove_coupon( $item->post_title );

				echo $item->post_title;*/

				wc_print_notices();

			}else{

				/*WC()->cart->remove_coupons();*/

			}



			foreach( WC()->cart->get_coupons() as $code => $coupon ){

				$valid = 0;

				if ( ! $valid ){

					WC()->cart->remove_coupon( $code );

				}

			 }



			

		}

	}

}

add_action( 'woocommerce_before_cart', 'rbdm_apply_RBDM_coupons' );









function rbdm_change_coupon_label ($label, $coupon) {

	$rbdm_code=$coupon->get_code();

	if (strpos($rbdm_code, 'rbdm')!== false) {

		return _e('Custom discount', 'rb-discount-manager');

	}else{

		return $rbdm_code;

	}

	

}



function rbdm_get_content_options_page_function(){

	ob_start();

	?>

			<div class="rb_list_discounts_content_table_head">

				<div class="rb_list_discounts_content_table_id"><?php _e('ID', 'rb-discount-manager');?></div>

				<div class="rb_list_discounts_content_table_user"><?php _e('User', 'rb-discount-manager');?></div>

				<div class="rb_list_discounts_content_table_typ"><?php _e('Type', 'rb-discount-manager');?></div>

				<div class="rb_list_discounts_content_table_value"><?php _e('Value', 'rb-discount-manager');?></div>

				<div class="rb_list_discounts_content_table_source"><?php _e('Conditional Type', 'rb-discount-manager');?></div>

				<div class="rb_list_discounts_content_table_target"><?php _e('Value', 'rb-discount-manager');?></div>

				<div class="rb_list_discounts_content_table_date"><?php _e('From...To', 'rb-discount-manager');?></div>

				<div class="rb_list_discounts_content_table_action"><?php _e('Action', 'rb-discount-manager');?></div>

			</div>

			<div class="rb_list_discounts_content_table_body">

			<?php

			$rbdm_now=strtotime(date("Y-m-d H:i:s"));

			$rbmd_args=array(

				'post_type'        => 'shop_coupon',

				'post_status'      => 'publish',

				'numberposts'=>1000,

				'meta_query' => array(

					'relation' => 'AND',

					/*array(

						'key'     => 'date_expires',

						'value'   => $rbdm_now,

						'compare' => '>',

					),

					array(

						'key'     => 'RBDM_start',

						'value'   => $rbdm_now,

						'compare' => '>',

					),*/

					array(

						'key'     => 'RBDM',

						'value'   => '1',

						'compare' => '=',

					),

				),

			);

			$rbmd_coupons=get_posts($rbmd_args);

			foreach($rbmd_coupons as $item){

			?>

				<div class="rb_list_discounts_content_table_row">

					<div class="rb_list_discounts_content_table_id"><?php echo $item->ID;?></div>

					<div class="rb_list_discounts_content_table_user"><?php echo get_post_meta($item->ID,'RBDM_users',true);?></div>

					<div class="rb_list_discounts_content_table_typ"><?php echo get_post_meta($item->ID,'discount_type',true);?></div>

					<div class="rb_list_discounts_content_table_value"><?php echo get_post_meta($item->ID,'coupon_amount',true);?></div>

					<div class="rb_list_discounts_content_table_source"><?php echo get_post_meta($item->ID,'RBDM_cond_type',true);?></div>

					<div class="rb_list_discounts_content_table_target"><?php echo get_post_meta($item->ID,'RBDM_cond_type_value',true);?></div>

					<div class="rb_list_discounts_content_table_date"><?php echo date('m/d/Y',get_post_meta($item->ID,'RBDM_start',true)).' ... '.date('m/d/Y',get_post_meta($item->ID,'date_expires',true));?></div>

					<div class="rb_list_discounts_content_table_action">

						<span class="rb_list_discounts_content_table_action_delete" data-id="<?php echo $item->ID;?>"><?php _e('Delete', 'rb-discount-manager');?></span>

						<span class="rb_list_discounts_content_table_action_update" data-id="<?php echo $item->ID;?>"><?php _e('Update', 'rb-discount-manager');?></span>

					</div>

				</div>

			<?php	

			}

			?>

			</div>

			<?php

			echo ob_get_clean();

			wp_die();

}

add_action ('wp_ajax_rbdm_get_content_options_page', 'rbdm_get_content_options_page_function');

add_action ('wp_ajax_nopriv_rbdm_get_content_options_page', 'rbdm_get_content_options_page_function');



/*crossfade*/



/*save order to ds*/ 



 

function rbdm_save_order_function( $order_id,$new_status ) {

	if( $new_status == "completed" ) {

		$rbdm_order = wc_get_order( $order_id );

		$rbdm_user=$rbdm_order->get_customer_id();

		foreach ( $rbdm_order->get_items() as $item_id => $item ) {

			$product_id = $item->get_product_id();

			$variation_id = $item->get_variation_id();

			$quantity = $item->get_quantity();

			$subtotal = $item->get_subtotal();

			rbdm_save_order_item($rbdm_user,$product_id,$variation_id,$quantity,$subtotal,$order_id);

			add_user_meta($rbdm_user,'order_item',$rbdm_user.','.$product_id.','.$variation_id.','.$quantity.','.$subtotal.','.$order_id);   

		}

	}

}

add_action ( 'woocommerce_order_status_changed' , 'rbdm_save_order_function', 99, 2 ) ;

function rbdm_save_order_item($rbdm_user,$product_id,$variation_id=0,$quantity,$subtotal,$order_id){

	global $wpdb;

	$table_name = $wpdb->prefix . 'rbdm_crossfade_order';

	$wpdb->insert($table_name, array(

		'id_user' => $rbdm_user,

		'id_product' => $product_id,

		'id_variation' => $variation_id,

		'id_order' => $order_id,

		'quantity' =>$quantity,

		'amount' =>$subtotal,

	));

}



function rbdm_settings_crossfade_page(){

	?>

	<section class="rb_list_discounts_wrapper">

		<h2><?php _e('Crossfade Manager', 'rb-discount-manager');?></h2>

		<button class="rbdm_add_rule_btn "><?php _e('Add new Rule', 'rb-discount-manager');?></button>

	</section>

	<section class="rbdm_form_rule_wrapper rbdm_hide">

		<h3><?php _e('Sources Product', 'rb-discount-manager');?></h3>

		<div class="rbdm_form_rule_source_wrapper">

			<div class="rbdm_form_rule_source_product">

				<label><?php _e('Sources Product', 'rb-discount-manager');?>

					<?php echo rbdm_get_product_select('source')?>

				</label>

			</div>

			<div class="rbdm_form_rule_source_quantity">

				<input type="hidden" class="rbdm_cross_source_qty" value="1" >

			</div>

		</div>

		<div class="rbdm_form_rule_target_wrapper">

			<h3><?php _e('Targets Product', 'rb-discount-manager');?></h3>

			<div class="rbdm_form_rule_target_product">

				<label><?php _e('Targets Product', 'rb-discount-manager');?>

					<?php echo rbdm_get_product_select('target')?>

				</label>

			</div>

			<div class="rbdm_form_rule_target_quantity">

				<input type="hidden" class="rbdm_cross_target_qty" value="1" >

			</div>

		</div>

		<div class="rbdm_form_rule_discount_wrapper">

			<h3><?php _e('Discount Option', 'rb-discount-manager');?></h3>

			<div class="rbdm_form_rule_discount_type d-flex">

				<label><?php _e('Percentage', 'rb-discount-manager');?>

					<input type="radio" class="rbdm_cross_discount_type" name="rbdm_cross_discount_type" value="percent" checked>

				</label>

				<label><?php _e('Fixed amount', 'rb-discount-manager');?>

					<input type="radio" class="rbdm_cross_discount_type" name="rbdm_cross_discount_type" value="amount" >

				</label>

			</div>

			<div class="rbdm_form_rule_discount_amount">

			<label><?php _e('Discount Value', 'rb-discount-manager');?>

				<input type="text" class="rbdm_cross_discount_value" name="rbdm_cross_discount_value" >

			</label>

			</div>

		</div>

		<div class="rbdm_form_rule_submit_wrapper">

			<button class="btn rbdm_add_rule_submit" ><?php _e('Save Rule', 'rb-discount-manager');?></button>

		</div>

	</section>

	<section class="rbdm_list_rule_wrapper">

		<h3><?php _e('List of Crossfade Rules', 'rb-discount-manager');?></h3>

		<div class="rb_list_rule_content_table_head d-flex">

			<div class="rb_list_rule_content_table_id"><?php _e('ID', 'rb-discount-manager');?></div>

			<div class="rb_list_rule_content_table_product_s"><?php _e('Sources Product', 'rb-discount-manager');?></div>

			<div class="rb_list_rule_content_table_product_t"><?php _e('Targets Product', 'rb-discount-manager');?></div>

			<div class="rb_list_rule_content_table_discount_type"><?php _e('Type of Discount', 'rb-discount-manager');?></div>

			<div class="rb_list_rule_content_table_discount_value"><?php _e('Value of Discount', 'rb-discount-manager');?></div>

			<div class="rb_list_rule_content_table_discount_action"><?php _e('Actions', 'rb-discount-manager');?>

			</div>

		</div>

		<div class="rb_list_rule_content_table_body ">

			<?php

			$rbdm_rules=rbdm_get_rules();

			foreach($rbdm_rules as $item){

				?>

				<div class="rb_list_rule_content_table_row d-flex">

					<div class="rb_list_rule_content_table_id"><?php echo $item->id;?></div>

					<div class="rb_list_rule_content_table_product_s"><?php echo get_post($item->id_product_source)->post_title;?></div>

					<div class="rb_list_rule_content_table_product_t"><?php echo get_post($item->id_product_target)->post_title;?></div>

					<div class="rb_list_rule_content_table_discount_type"><?php echo $item->discount_type;?></div>

					<div class="rb_list_rule_content_table_discount_value"><?php echo $item->discount_amount;?></div>

					<div class="rb_list_rule_content_table_discount_action">

						<span class="rb_list_rule_content_table_row_delete" data-id="<?php echo $item->id;?>"><?php _e('Delete', 'rb-discount-manager');?></span>

					</div>

				</div>

				<?php

			}

			?>

		</div>

	</section>

	<section class="rbdm_orders_rule_wrapper">

		<h3><?php _e('Options of Orders', 'rb-discount-manager');?></h3><span class="rbdm_orders_rule_notices"></span>

		<div class="rbdm_cross_orders_date_wrapper d-flex" data-now="<?php echo date('Y-m-d');?>">

			<h5><?php _e('Orders count from', 'rb-discount-manager');?></h5>

			<label><?php _e('All Orders', 'rb-discount-manager');?>

				<input type="radio" class="rbdm_cross_orders_date_all" name="rbdm_cross_orders_date_radio" value="all" checked>

			</label>

			<label><?php _e('No Orders', 'rb-discount-manager');?>

				<input type="radio" class="rbdm_cross_orders_date_null" name="rbdm_cross_orders_date_radio" value="null" >

			</label>

			<label><?php _e('Custom Date', 'rb-discount-manager');?>

				<input type="date" class="rbdm_cross_orders_date"  value="<?php echo get_option('rbdm_discount_orders');?>" >

			</label>

			<button class="btn rbdm_orders_submit_btn" ><?php _e('Save Option', 'rb-discount-manager');?></button>

		</div>

		<div class="rbdm_preload_wrapper rbdm_hide"><img src="<?php echo RBDM_PLUGIN_URL.'/assets/img/preloader.gif'?>" class="rbdm_preload_image"></div>	

	</section>



	<?php

}



function rbdm_refresh_list_rule(){

	ob_start();

	

			$rbdm_rules=rbdm_get_rules();

			foreach($rbdm_rules as $item){

				?>

				<div class="rb_list_rule_content_table_row d-flex">

					<div class="rb_list_rule_content_table_id"><?php echo $item->id;?></div>

					<div class="rb_list_rule_content_table_product_s"><?php echo get_post($item->id_product_source)->post_title;?></div>

					<div class="rb_list_rule_content_table_product_t"><?php echo get_post($item->id_product_target)->post_title;?></div>

					<div class="rb_list_rule_content_table_discount_type"><?php echo $item->discount_type;?></div>

					<div class="rb_list_rule_content_table_discount_value"><?php echo $item->discount_amount;?></div>

					<div class="rb_list_rule_content_table_discount_action">

						<span class="rb_list_rule_content_table_row_delete" data-id="<?php echo $item->id;?>"><?php _e('Delete', 'rb-discount-manager');?></span>

					</div>

				</div>

				<?php

			}

	return ob_get_clean();		

}



function rbdm_get_product_select($type){

	$rbdm_args=array(

		'post_type'=>'product',

		'post_status'=>'publish',

		'numberposts'=>10000,

	);

	$rbdm_products=get_posts($rbdm_args);

	ob_start();

	?>

	<select id="rbdm_cross_<?php echo $type;?>_product" class="rbdm_select_products" value="">

		<option class="rbdm_select_products_option" value="0" selected><?php _e('Select '.$type.'s Product', 'rb-discount-manager');?></option>

		<?php

		foreach($rbdm_products as $item){

			?>

			<option class="rbdm_select_products_option" value="<?php echo $item->ID;?>"><?php echo $item->post_title;?></option>

			<?php

		}

		?>

	</select>

	<?php

	return ob_get_clean();

}

function rbdm_add_rule_function(){

	$product_s=$_REQUEST['product_s'];

    $quantity_s=$_REQUEST['quantity_s'];

    $product_t=$_REQUEST['product_t'];

    $quantity_t=$_REQUEST['quantity_t'];

    $discount_type=$_REQUEST['discount_type'];

    $discount_value=$_REQUEST['discount_value'];

	global $wpdb;

	$table_name = $wpdb->prefix . 'rbdm_crossfade_rule';

	$wpdb->insert($table_name, array(

		'id_product_source' => $product_s,

		'id_product_target' => $product_t,

		'quantity_source' => $quantity_s,

		'quantity_target' => $quantity_t,

		'discount_type' =>$discount_type,

		'discount_amount' =>$discount_value,

	));

	echo rbdm_refresh_list_rule();

	wp_die();

}

add_action ('wp_ajax_rbdm_add_rule', 'rbdm_add_rule_function');

add_action ('wp_ajax_nopriv_rbdm_add_rule', 'rbdm_add_rule_function');



function rbdm_delete_rule_function(){

	$rule_id=$_REQUEST['rule_id'];

	global $wpdb;

	$table_name = $wpdb->prefix . 'rbdm_crossfade_rule';

	$wpdb->delete( $table_name,array('id' => $rule_id));

	echo rbdm_refresh_list_rule();

	wp_die();

}

add_action ('wp_ajax_rbdm_delete_rule', 'rbdm_delete_rule_function');

add_action ('wp_ajax_nopriv_rbdm_delete_rule', 'rbdm_delete_rule_function');



function rbmd_change_price_display($price_html, $product){

	if ( is_admin() ) return $price_html;

	$user_id=get_current_user_id();

	$rbdm_rules=rbdm_get_rules();

	$pid=$product->get_id();

	foreach($rbdm_rules as $item){

		$product_s=$item->id_product_source;

		$product_t=$item->id_product_target;

		$rbdm_product=wc_get_product($product_t);

		if($pid==$product_t){

			if(rbdm_get_user_rules($user_id,$product_s) ){



				$orig_price = wc_get_price_to_display( $product );

				if($item->discount_type=='amount'){

					$price_html = wc_price( $orig_price + $item->discount_amount );

				}else{

					$discount=(100-$item->discount_amount)/100;

					$price_html = wc_price( $orig_price * $discount );

				}

				

			}else{

				return $price_html;

			}

		}

	}

	return $price_html;

}

add_filter( 'woocommerce_get_price_html', 'rbmd_change_price_display', 9999, 2 );





 

function rbmd_change_price_cart( $cart ) {

 

    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;

    if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ) return;

    foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {

        $product = $cart_item['data'];

		$pid=$product->get_id();

        $price = $product->get_price();

		$user_id=get_current_user_id();

		$rbdm_rules=rbdm_get_rules();

		foreach($rbdm_rules as $item){

			$product_s=$item->id_product_source;

			$product_t=$item->id_product_target;

			$rbdm_product=wc_get_product($product_t);

			if($pid==$product_t){

				if(rbdm_get_user_rules($user_id,$product_s) ){

	

					$orig_price = wc_get_price_to_display( $product );

					if($item->discount_type=='amount'){

						$cart_item['data']->set_price( $price + $item->discount_amount );

					}else{

						$discount=(100-$item->discount_amount)/100;

						$cart_item['data']->set_price( $price * $discount );

					}

					

				}else{

					return $price_html;

				}

			}

		}





        

    }

 

}

add_action( 'woocommerce_before_calculate_totals', 'rbmd_change_price_cart', 9999 );







function rbdm_get_rules(){

	global $wpdb;

	$table_name = $wpdb->prefix . 'rbdm_crossfade_rule';

	$result=$wpdb->get_results( "SELECT * FROM {$table_name}" );

	return $result;

}

function rbdm_get_user_rules($user_id, $id_product){

	global $wpdb;

	$table_name = $wpdb->prefix . 'rbdm_crossfade_order';

	$result=$wpdb->get_results( "SELECT COUNT(*) FROM {$table_name} WHERE id_user ='$user_id' and id_product='$id_product'"  );

	return $result;

}



/*upload data from orders*/

function rbdm_upload_orders_function(){

	$rbdm_orders_date=$_REQUEST['rbdm_orders_date'];

	ob_start();

	print_r(rbdm_upload_order_to_db($rbdm_orders_date));

	update_option( 'rbdm_discount_orders', $rbdm_orders_date );

	echo ob_get_clean();

	wp_die();

}

add_action ('wp_ajax_rbdm_upload_orders', 'rbdm_upload_orders_function');



function rbdm_upload_order_to_db($date_from='0000-00-00',$exclude_users=array(0)){

	$date_from=date('Y-m-d',strtotime($date_from));

	$rbdm_users = get_users( array( 'fields' => array( 'ID' ),'exclude'=>$exclude_users ) );

	$rbdm_users_products_all=array();

	foreach ( $rbdm_users as $user ) {

		$rbdm_orders=wc_get_orders( array(

			'customer_id' => $user->ID,

			'status' => 'wc-completed',

			'numberposts' => -1,

			'date_completed'=>'>='. $date_from /*YYYY-MM-DD*/

		) );

		$rbdm_user_products=array();

		foreach($rbdm_orders as $order){

			foreach($order->get_items() as $item_id => $item){

				$product_id = $item->get_product_id();

            	$rbdm_user_products[] = $product_id;

			}

		}

		$rbdm_user_products=array_unique( $rbdm_user_products );

		$product_ids_str = implode( ",", $rbdm_user_products );

		$rbdm_users_products_all[$user->ID]=$product_ids_str;

		foreach($rbdm_user_products as $item){



		}

	}

	return $rbdm_users_products_all;

}
